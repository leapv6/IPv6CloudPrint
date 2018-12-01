#ifndef UTILS_H_
#define UTILS_H_

#include <string>
using namespace std;

class CDSUtils
{
public:
	//////////////////////////////////////////////////////////////////////////
	// 将字符串转为 UTF-8 编码                                              //
	// CP_ACP=ANSI,CP_UTF8=utf-8                                            //
	//////////////////////////////////////////////////////////////////////////
	static void ToUft8(CString &str,int sourceCodepage,int targetCodepage)
	{
		int len=str.GetLength(); 
		
		int unicodeLen=MultiByteToWideChar(sourceCodepage,0,str,-1,NULL,0); 
		
		wchar_t * pUnicode; 
		pUnicode=new wchar_t[unicodeLen+1]; 
		
		memset(pUnicode,0,(unicodeLen+1)*sizeof(wchar_t)); 
		
		MultiByteToWideChar(sourceCodepage,0,str,-1,(LPWSTR)pUnicode,unicodeLen); 
		
		BYTE * pTargetData; 
		int targetLen=WideCharToMultiByte(targetCodepage,0,(LPWSTR)pUnicode,-1,(char *)pTargetData,0,NULL,NULL); 
		
		pTargetData=new BYTE[targetLen+1]; 
		memset(pTargetData,0,targetLen+1); 
		
		WideCharToMultiByte(targetCodepage,0,(LPWSTR)pUnicode,-1,(char *)pTargetData,targetLen,NULL,NULL); 
		
		str.Format("%s",pTargetData);
		
		delete pUnicode; 
		delete pTargetData; 
	}
	
	//////////////////////////////////////////////////////////////////////////
	//获取应用程序全路径                                                    //
	//////////////////////////////////////////////////////////////////////////
	static void GetExePath(char *cpExePath)
	{
		//获得程序模块名称，全路径
		TCHAR exeFullPath[MAX_PATH]; 
		GetModuleFileName(NULL,exeFullPath,MAX_PATH);
		
		//获得程序模块所在文件夹路径
		string strFullExeName(exeFullPath);
		int nLast=strFullExeName.find_last_of("\\");
		strFullExeName=strFullExeName.substr(0,nLast+1);
		
		//获得配置文件全路径
		sprintf(cpExePath, "%s",strFullExeName.c_str());
	}
	
	//////////////////////////////////////////////////////////////////////////
	//获取系统时间                                                    //
	//////////////////////////////////////////////////////////////////////////
	static void GetDsCurrentTime(char *cpTime, int nFlag)
	{
		switch(nFlag) {
		case 0:
			{
				CTime t = CTime::GetCurrentTime();
				CString strTime = t.Format("%Y-%m-%d %H:%M:%S");
				sprintf(cpTime, "%s", strTime);
			}
			break;
		case 1:
			{
				CTime t = CTime::GetCurrentTime();
				CString strTime = t.Format("%Y年%m月%d日 %H:%M:%S");
				sprintf(cpTime, "%s", strTime);
			}
			break;
		}
	}
	
	/*******************************************************
	* 分割字符串 
	* lpszStr 源字符串，lpszSplit 分割字符串，bAllowNullString 分割失败后，是否返回源字符串
	* rArrString 分割后的字符串数组
	* 字符串数组长度 成功
	******************************************************/
	static int SplitString(LPCTSTR lpszStr, LPCTSTR lpszSplit, CStringArray& rArrString, BOOL bAllowNullString)   
	{   
		rArrString.RemoveAll();
		CString szStr = lpszStr;
		szStr.TrimLeft();
		szStr.TrimRight();
		if(szStr.GetLength()==0)   
		{
			return 0;
		}   
		CString szSplit = lpszSplit;
		if(szSplit.GetLength() == 0)
		{   
			rArrString.Add(szStr);
			return 1;
		}
		CString s;   
		int n;
		do {   
			n = szStr.Find(szSplit);
			if(n > 0)
			{   
				rArrString.Add(szStr.Left(n));   
				szStr = szStr.Right(szStr.GetLength()-n-szSplit.GetLength());
				szStr.TrimLeft();   
			}
			else if(n==0)   
			{   
				if(bAllowNullString)   
					rArrString.Add(_T(""));   
				szStr = szStr.Right(szStr.GetLength()-szSplit.GetLength());   
				szStr.TrimLeft();   
			}   
			else
			{   
				if((szStr.GetLength()>0) || bAllowNullString)   
					rArrString.Add(szStr);
				break;   
			}
		} while(1);
		return rArrString.GetSize();   
	}

	//////////////////////////////////////////////////////////////////////////
	//获得随机数                                                            //
	//////////////////////////////////////////////////////////////////////////
	static int GetRand(int nMax)
	{
		srand((unsigned)time(NULL));
		return (rand()%nMax);
	}

	//////////////////////////////////////////////////////////////////////////
	//获得随机数                                                            //
	//////////////////////////////////////////////////////////////////////////
	static CTime CStringToCTime(CString strTime)
	{
		int nYear, nMonth, nDate, nHour, nMin, nSec;
		sscanf(strTime, "%d-%d-%d %d:%d:%d", &nYear, &nMonth, &nDate, &nHour, &nMin, &nSec);
		CTime tTime(nYear, nMonth, nDate, nHour, nMin, nSec);
		return tTime;
	}

	//开机启动
	static int CreateRun(char * szFileName)
	{
		//添加以下代码
		HKEY   hKey; 
		//找到系统的启动项 
		LPCTSTR lpRun = _T("Software\\Microsoft\\Windows\\CurrentVersion\\Run"); 
		//打开启动项Key 
		long lRet = RegOpenKeyEx(HKEY_LOCAL_MACHINE, lpRun, 0, KEY_WRITE, &hKey); 
		if(lRet== ERROR_SUCCESS)
		{
			//添加注册
			RegSetValueEx(hKey, _T("IPv6Cloud"), 0,REG_SZ,(const BYTE*)(LPCSTR)szFileName, MAX_PATH);
			RegCloseKey(hKey); 
		}
		return 0;
	}

	//取消开机启动
	static int DeleteRun(char * szFileName)
	{
		//添加以下代码
		HKEY   hKey; 
		char pFileName[MAX_PATH] = {0}; 
		//得到程序自身的全路径 
		DWORD dwRet = GetModuleFileNameW(NULL, (LPWCH)pFileName, MAX_PATH); 
		//找到系统的启动项 
		LPCTSTR lpRun = _T("Software\\Microsoft\\Windows\\CurrentVersion\\Run"); 
		//打开启动项Key 
		long lRet = RegOpenKeyEx(HKEY_LOCAL_MACHINE, lpRun, 0, KEY_WRITE, &hKey); 
		if(lRet== ERROR_SUCCESS)
		{
			//删除注册
			RegDeleteValue(hKey,_T("IPv6Cloud"));
			RegCloseKey(hKey); 
		}
		return 0;
	}
};
#endif /*UTILS_H_*/

 
