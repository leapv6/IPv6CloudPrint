// Global.h: interface for the CGlobal class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_GLOBAL_H__93FD2F7D_785C_45B0_B540_526A21FD605B__INCLUDED_)
#define AFX_GLOBAL_H__93FD2F7D_785C_45B0_B540_526A21FD605B__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "../PubHead.h"

class CGlobal  
{
public:
	int Init();
	int GetExePath(char *cpExePath);
	int GetConfig(const char *cpExePath);
	int SaveCfg(const char *cpExePath, CString strClientIP, CString strServerIpv6, CString strSavePath, CString strColorPrint, CString strBlackPrint);
	int SaveServerAddr(const char *cpExePath, CString strServerAddr);
	int SaveUserName(const char *cpExePath, CString strUserName, CString strPassword, int nRemember, int nAuto);
	int SaveAutoLoginStatus(const char *cpExePath, int nStatus);
	CGlobal();
	virtual ~CGlobal();

	/* 配置信息 */
	static char g_UserName[STRING_LEN_NORMAL];
	static char g_UserPwd[STRING_LEN_NORMAL];
	static char g_ServerAddr[STRING_LEN_NORMAL];
	static char g_ClientIP[STRING_LEN_NORMAL];
	static int  g_ClientPort;
	static int  g_Remember;
	static int  g_AutoLogin;
	static int  g_AutoPrint;
	static int  g_AutoLoginStatus;

	static char g_SavePath[MAX_PATH];
	static CStringArray g_ColorPrint;
	static CStringArray g_BlackPrint;
	
	/* 配置文件路径 */
	static char g_ExePath[MAX_PATH];
};

#endif // !defined(AFX_GLOBAL_H__93FD2F7D_785C_45B0_B540_526A21FD605B__INCLUDED_)
