// Filename: OutPrn.cpp
#include "stdafx.h"
#include <ctype.h>
#include <string.h>
#include <winspool.h>

BOOL SetSystemDefaultPrinter(LPTSTR pPrinterName);
bool StartPrint(char * szFilePath, int nCount);

// 方法1
BOOL SetSystemDefaultPrinter(LPTSTR pPrinterName) 
{ 
	BOOL bFlag = FALSE; 
	LONG lResult = 0; 
	DWORD dwNeeded = 0; 
	LPTSTR pBuffer = NULL; 
	HANDLE hPrinter = NULL; 
	OSVERSIONINFO stOsvInfo = {0}; 
	PRINTER_INFO_2* pstPrintInfo2 = NULL; 

	stOsvInfo.dwOSVersionInfoSize = sizeof(OSVERSIONINFO); 
	GetVersionEx(&stOsvInfo); 

	if(!pPrinterName) 
	{ 
		return FALSE; 
	} 

	if(stOsvInfo.dwPlatformId == VER_PLATFORM_WIN32_WINDOWS) //Win9x 
	{ 
		// Open this printer so we can get information about it 
		bFlag = OpenPrinter(pPrinterName, &hPrinter, NULL); 
		if(!bFlag || hPrinter==NULL) 
		{ 
			return FALSE; 
		} 

		GetPrinter(hPrinter, 2, 0, 0, &dwNeeded); 
		if(dwNeeded == 0) 
		{ 
			ClosePrinter(hPrinter); 
			return FALSE; 
		} 

		pstPrintInfo2 = (PRINTER_INFO_2 *)GlobalAlloc(GPTR, dwNeeded); 
		if(pstPrintInfo2 == NULL) 
		{ 
			ClosePrinter(hPrinter); 
			return FALSE; 
		} 

		bFlag = GetPrinter(hPrinter, 2, (LPBYTE)pstPrintInfo2, dwNeeded, &dwNeeded); 
		if(!bFlag) 
		{ 
			ClosePrinter(hPrinter); 
			GlobalFree(pstPrintInfo2); 
			return FALSE; 
		} 

		// Set default printer attribute for this printer... 
		pstPrintInfo2->Attributes |= PRINTER_ATTRIBUTE_DEFAULT; 
		bFlag = SetPrinter(hPrinter, 2, (LPBYTE)pstPrintInfo2, 0); 
		if(!bFlag) 
		{ 
			ClosePrinter(hPrinter); 
			GlobalFree(pstPrintInfo2); 
			return FALSE; 
		} 

		lResult = 
			SendMessageTimeout(HWND_BROADCAST, WM_SETTINGCHANGE, 0L, (LPARAM)(LPCTSTR)"windows", SMTO_NORMAL, 1000, 

			NULL); 
	} 
	else if (stOsvInfo.dwPlatformId == VER_PLATFORM_WIN32_NT) 
	{ 
#if(WINVER >= 0x0500) 
		if(stOsvInfo.dwMajorVersion >= 5) // Windows 2000 or later... 
		{ 
			bFlag = SetDefaultPrinter(pPrinterName); 
			if(!bFlag) 
			{ 
				return FALSE; 
			} 
		} 
		else // NT4.0 or earlier... 
#endif 
		{ 
			bFlag = OpenPrinter(pPrinterName, &hPrinter, NULL); 
			if(!bFlag || hPrinter==NULL) 
			{ 
				return FALSE; 
			} 

			GetPrinter(hPrinter, 2, 0, 0, &dwNeeded); 
			if(dwNeeded == 0) 
			{ 
				ClosePrinter(hPrinter); 
				return FALSE; 
			} 

			pstPrintInfo2 = (PRINTER_INFO_2*)GlobalAlloc(GPTR, dwNeeded); 
			if(pstPrintInfo2 == NULL) 
			{ 
				ClosePrinter(hPrinter); 
				return FALSE; 
			} 

			bFlag = GetPrinter(hPrinter, 2, (LPBYTE)pstPrintInfo2, dwNeeded, &dwNeeded); 
			if((!bFlag) || (!pstPrintInfo2->pDriverName) || (!pstPrintInfo2->pPortName)) 
			{ 
				ClosePrinter(hPrinter); 
				GlobalFree(pstPrintInfo2); 
				return FALSE; 
			} 

			pBuffer = 
				(LPTSTR)GlobalAlloc(GPTR, lstrlen(pPrinterName)+lstrlen(pstPrintInfo2->pDriverName)+lstrlen(pstPrintInfo2->pPortName)+3); 
			if(pBuffer == NULL) 
			{ 
				ClosePrinter(hPrinter); 
				GlobalFree(pstPrintInfo2); 
				return FALSE; 
			} 

			// Build string in form "printername,drivername,portname" 
			lstrcpy(pBuffer, pPrinterName); lstrcat(pBuffer, ","); 
			lstrcat(pBuffer, pstPrintInfo2->pDriverName); lstrcat(pBuffer, ","); 
			lstrcat(pBuffer, pstPrintInfo2->pPortName); 

			bFlag = WriteProfileString("windows", "device", pBuffer); 
			if(!bFlag) 
			{ 
				ClosePrinter(hPrinter); 
				GlobalFree(pstPrintInfo2); 
				GlobalFree(pBuffer); 
				return FALSE; 
			} 
		} 

		lResult = SendMessageTimeout(HWND_BROADCAST, WM_SETTINGCHANGE, 0L, 0L, SMTO_NORMAL, 1000, NULL); 
	} 

	if(hPrinter) 
	{ 
		ClosePrinter(hPrinter); 
	} 
	if(pstPrintInfo2) 
	{ 
		GlobalFree(pstPrintInfo2); 
	} 
	if(pBuffer) 
	{ 
		GlobalFree(pBuffer); 
	} 

	return TRUE; 
}

bool StartPrint(char * szFilePath, int nCount)
{
	SHELLEXECUTEINFO ShExecInfo = {0};
	ShExecInfo.cbSize = sizeof(SHELLEXECUTEINFO);
	ShExecInfo.fMask = SEE_MASK_NOCLOSEPROCESS;
	ShExecInfo.hwnd = NULL;
	ShExecInfo.lpVerb = TEXT("print");
	ShExecInfo.lpFile = TEXT(szFilePath); //此处是待打印的文档 
	ShExecInfo.lpParameters = TEXT(""); 
	ShExecInfo.lpDirectory = NULL;
	ShExecInfo.nShow = SW_HIDE;
	ShExecInfo.hInstApp = NULL;

	bool bRet = false;
	for(int i=0; i<nCount; i++)
	{
		bRet = ShellExecuteEx(&ShExecInfo); //可添加 bool变量，判断是否成功。
		if(!bRet)
		{
			return false;
		}
		WaitForSingleObject(ShExecInfo.hProcess, INFINITE);
	}
	return bRet;
}
// 方法2
//// *******定义变量********
//// 定义实际上指向ADDJOB_INFO_1
//// 结构的指针
//LPBYTE  pJob=0; 
////打印机句柄
//HANDLE m_hPrinter=NULL; 
////********函数定义****************
////获得打印作业的临时文件名和ID号
////并保存在变量pJob所在的空间，成功返
////回true,失败返回false
//bool GetSpoolFileName( ); 
////通知系统数据准备就绪，可以输出，
////同时释放函数运行中占用的内存
//void EndPrint( ) ;
////主函数演示怎样调用上面的函数来
////完成将一个文件完整的不变
////的传给外部设备
//void Demo( );
//
//bool GetDefaultPrinterName(CString &name)
//{
//	CPrintDialog pd(TRUE);
//	if(pd.GetDefaults()==FALSE)
//	{
//		AfxMessageBox("Windows系统没有安装缺省打印机");
//		return false;
//	}
//	name=pd.GetDeviceName();
//	if (pd.m_pd.hDevNames)
//	{
//		::GlobalUnlock(pd.m_pd.hDevNames);
//		::GlobalFree(pd.m_pd.hDevNames);
//		pd.m_pd.hDevNames=NULL;
//	}
//	if (pd.m_pd.hDevMode)
//	{
//		::GlobalFree(pd.m_pd.hDevMode);
//		pd.m_pd.hDevMode=NULL;
//	}
//	return true;
//}
//
////********* 函数具体实现 ***********
//bool GetSpoolFileName(CString name)
//{
//	//定义一些临时变量
//	DWORD dwNeeded=0;
//	DWORD dwReturned=0;
//	LPBYTE pPrinterEnum=0;
//	BOOL nRet=FALSE;
//	//CString name;
//	//获得系统缺省打印机名称首先调用EnumPrinters获得需要
//	//多大的存储空间来放获得的信息，
//	//该大小写入变量dwNeeded 中
//	//if(GetDefaultPrinterName(name)==false) return false;
//	::EnumPrinters(PRINTER_ENUM_NAME,NULL,2,NULL,0,&dwNeeded,&dwReturned);
//	if(dwNeeded<=0) return false;
//    //根据前面结果来分配存储空间
//	pPrinterEnum=new BYTE[dwNeeded];
//	//再一次调用函数EnumPrinters，
//	//将获得系统缺省打印机
//	//信息放入pPrinterEnum中。
//	nRet=::EnumPrinters(PRINTER_ENUM_NAME,NULL,2,pPrinterEnum
//		,dwNeeded,&dwNeeded,&dwReturned);
//	if(nRet==FALSE ||dwReturned==0)
//	{
//		//没有找到所需要的缺省打印机，函数返回
//		delete pPrinterEnum;
//		return false;
//	}
//	// 将pPrinterEnum转换为结构 PRINTER_INFO_2的指针
//	PRINTER_INFO_2 *pInfo=(PRINTER_INFO_2 *)pPrinterEnum;
//	DWORD num;
//	for(num=0L;num<dwReturned;num++)
//	{
//		if(lstrcmp((LPTSTR)(&(pInfo[num].pDevMode->dmDeviceName[0])),name)==0){ break;}
//	}
//	if(num>=dwReturned) 
//	{
//		AfxMessageBox("没有找到对应的打印机");
//		return false;
//	}
//	//根据结构PRINTER_INFO_2中包含的打印机名称来打开该打印机并
//	//将获得的句柄保存在变量 m_hPrinter中
//	if(!::OpenPrinter(pInfo[num].pPrinterName,&m_hPrinter,NULL))
//	{   //打开打印机失败，函数返回
//		AfxMessageBox("打开打印机失败");
//		//释放内存
//		delete pPrinterEnum;
//		m_hPrinter=NULL;
//		return false;
//	}
//	//下面不再需要，释放所占用的内存
//	delete pPrinterEnum;
//	//使用函数AddJob 来获得新添加的打印作业的临时文件名和对应的ID号
//	dwNeeded=0;
//	ASSERT(pJob==NULL);
//	//分配空间用来存放结构 ADDJOB_INFO_1所包含的信息注意不要利用AddJob函数自动检
//	//测需要多大的空间来存
//	pJob=new BYTE[2048];
//	//放ADDJOB_INFO_1所包含的信息，该函数的返回值本人测试了几次
//	//都不正确自己给它分配2K的内存足够了。
//	BOOL flag=::AddJob( m_hPrinter,1,pJob,2048,&dwNeeded);
//	if(!flag)
//	{//函数不成功返回
//		delete []pJob;//释放内存
//		pJob=0;
//		AfxMessageBox("分配内存失败");
//		::ClosePrinter( m_hPrinter);
//		m_hPrinter=NULL;
//		return false;
//	}
//	return true;
//}
//
//// ***************************
//void EndPrint( )
//{
//	ASSERT(pJob);
//	//发送消息给打印管理服务器，当
//	//前的作业可以输出了	
//	::ScheduleJob( m_hPrinter,((ADDJOB_INFO_1 *)pJob)->JobId );
//	//释放打印句柄
//	ClosePrinter( m_hPrinter);
//	m_hPrinter=0;
//	delete []pJob;//释放内存
//	pJob=0;
//	m_hPrinter=0;
//}
//
//// ****************************
//void Demo(CString strPrintName, CString strFileName)
//{
//	//调用函数生成临时文件和JOB
//	if(!GetSpoolFileName(strPrintName)) return;
//
//	//将原始数据放入临时文件中
//	CopyFile(strFileName, ((ADDJOB_INFO_1 *)pJob)->Path, FALSE);
//
//	//通知服务器作业准备就绪
//	EndPrint( );
//}