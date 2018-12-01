#ifndef DS_MEMFREE_H_
#define DS_MEMFREE_H_

#define WIN32_LEAN_AND_MEAN
#define UNICODE
#define _UNICODE

typedef LONG NTSTATUS;
typedef LONG KPRIORITY;

#define NT_SUCCESS(Status) ((NTSTATUS)(Status) >= 0)

#define STATUS_INFO_LENGTH_MISMATCH    ((NTSTATUS)0xC0000004L)

#define SystemProcessesAndThreadsInformation 5

typedef struct _CLIENT_ID {
	DWORD        UniqueProcess;
	DWORD        UniqueThread;
}
CLIENT_ID;

typedef struct _UNICODE_STRING {
	USHORT        Length;
	USHORT        MaximumLength;
	PWSTR        Buffer;
}
UNICODE_STRING;

typedef struct _VM_COUNTERS {
	SIZE_T        PeakVirtualSize;
	SIZE_T        VirtualSize;
	ULONG        PageFaultCount;
	SIZE_T        PeakWorkingSetSize;
	SIZE_T        WorkingSetSize;
	SIZE_T        QuotaPeakPagedPoolUsage;
	SIZE_T        QuotaPagedPoolUsage;
	SIZE_T        QuotaPeakNonPagedPoolUsage;
	SIZE_T        QuotaNonPagedPoolUsage;
	SIZE_T        PagefileUsage;
	SIZE_T        PeakPagefileUsage;
}
VM_COUNTERS;

typedef struct _SYSTEM_THREADS {
	LARGE_INTEGER KernelTime;
	LARGE_INTEGER UserTime;
	LARGE_INTEGER CreateTime;
	ULONG WaitTime;
	PVOID StartAddress;
	CLIENT_ID        ClientId;
	KPRIORITY        Priority;
	KPRIORITY        BasePriority;
	ULONG ContextSwitchCount;
	LONG State;
	LONG WaitReason;
}
SYSTEM_THREADS, * PSYSTEM_THREADS;

// Note that the size of the SYSTEM_PROCESSES structure is different on
// NT 4 and Win2K, but we don't care about it, since we don't access neither
// IoCounters member nor Threads array

typedef struct _SYSTEM_PROCESSES {
	ULONG NextEntryDelta;
	ULONG ThreadCount;
	ULONG Reserved1[6];
	LARGE_INTEGER CreateTime;
	LARGE_INTEGER UserTime;
	LARGE_INTEGER KernelTime;
	UNICODE_STRING   ProcessName;
	KPRIORITY        BasePriority;
	ULONG ProcessId;
	ULONG InheritedFromProcessId;
	ULONG HandleCount;
	ULONG Reserved2[2];
	VM_COUNTERS        VmCounters;
#if _WIN32_WINNT >= 0x500
	IO_COUNTERS        IoCounters;
#endif
	SYSTEM_THREADS   Threads[1];
}
SYSTEM_PROCESSES, * PSYSTEM_PROCESSES;

void EnablePrivilege() 
{
	HANDLE hCurrentProcess;
	HANDLE hProcessToken;
	TOKEN_PRIVILEGES tp;
	LUID luid;
	
	hCurrentProcess = GetCurrentProcess();
	OpenProcessToken(hCurrentProcess, TOKEN_ALL_ACCESS, &hProcessToken);
	LookupPrivilegeValue(NULL, TEXT("SeDebugPrivilege"), &luid);
	
	tp.PrivilegeCount = 1;
	tp.Privileges[0].Luid = luid;
	tp.Privileges[0].Attributes = SE_PRIVILEGE_ENABLED;
	AdjustTokenPrivileges(hProcessToken, FALSE, &tp, sizeof(TOKEN_PRIVILEGES),
		(PTOKEN_PRIVILEGES)NULL, (PDWORD)NULL);
}

void FreeMem() {
	//printf("开始清理内存……\n");
	typedef LONG (WINAPI *ZWQUERYSYSTEMINFORMATION)(UINT, PVOID, ULONG, PULONG);
	ZWQUERYSYSTEMINFORMATION ZwQuerySystemInformation;
	HINSTANCE hDll = NULL;
	PVOID pBuffer = NULL;
	ULONG cbBuffer = 0x8000;
	HANDLE hHeap;
	LONG Status;
	PSYSTEM_PROCESSES pProcesses;
	HANDLE hProcess;
	
	if ((hDll = LoadLibrary(TEXT("ntdll.dll"))) == NULL) {
		return;
	}
	
	if ((ZwQuerySystemInformation = (ZWQUERYSYSTEMINFORMATION)GetProcAddress(hDll,
		"ZwQuerySystemInformation")) == NULL) {
		FreeLibrary(hDll);
		return;
	}
	
	// 获取系统进程信息
	hHeap = GetProcessHeap();
	do {
		pBuffer = HeapAlloc(hHeap, 0, cbBuffer);
		if (pBuffer == NULL) {
			FreeLibrary(hDll);
			return;
		}
		
		Status = ZwQuerySystemInformation(5, pBuffer, cbBuffer, NULL);
		
		if (Status == STATUS_INFO_LENGTH_MISMATCH) {
			HeapFree(hHeap, 0, pBuffer);
			pBuffer = NULL;
			cbBuffer *= 2;
		} else if (!NT_SUCCESS(Status)) {
			HeapFree(hHeap, 0, pBuffer);
			FreeLibrary(hDll);
			return;
		}
	} while (Status == STATUS_INFO_LENGTH_MISMATCH);
	
	// 循环清除每个进程的工作集
	pProcesses = (PSYSTEM_PROCESSES)pBuffer;
	for (;;) {
		// 判断是否是另一个相同的实例
		char cpProcessNameBuffer[256] = {0};
		sprintf(cpProcessNameBuffer, "%x", pProcesses->ProcessName.Buffer);
		if ( (lstrcmp(cpProcessNameBuffer, TEXT("memmaker.exe")) == 0)
            && (pProcesses->ProcessId != GetCurrentProcessId()) ) {
			if ((hProcess = OpenProcess(PROCESS_TERMINATE, FALSE,
				(DWORD)(pProcesses->ProcessId))) != NULL) {
				TerminateProcess(hProcess, 0);
				CloseHandle(hProcess);
			}
		} else {
			if ((hProcess = OpenProcess(PROCESS_SET_QUOTA, FALSE,
				(DWORD)(pProcesses->ProcessId))) != NULL) {
				SetProcessWorkingSetSize(hProcess, (DWORD)-1, (DWORD)-1);
				CloseHandle(hProcess);
			}
		}
		
		if (pProcesses->NextEntryDelta == 0)
			break;
		
		pProcesses = (PSYSTEM_PROCESSES)((LPBYTE)pProcesses + pProcesses->NextEntryDelta);
	}
	
	HeapFree(hHeap, 0, pBuffer);
	FreeLibrary(hDll);
	//printf("内存清理完成！\n");
	return;	
}

/*
void main()
{
// 获得Debug权限
EnablePrivilege();
// 每隔5分钟优化一次
for (;;) {
FreeMem();
Sleep(30000);
}
}
*/

#endif