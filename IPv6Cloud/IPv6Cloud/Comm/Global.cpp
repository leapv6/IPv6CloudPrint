// Global.cpp: implementation of the CGlobal class.
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "Global.h"
#include "DS_Ini.h"
#include "DS_Utils.h"

#include <string>
using namespace std;

//////////////////////////////////////////////////////////////////////////
#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif
//////////////////////////////////////////////////////////////////////////
/* 配置信息 */
char CGlobal::g_UserName[STRING_LEN_NORMAL];
char CGlobal::g_ServerAddr[STRING_LEN_NORMAL];
char CGlobal::g_ClientIP[STRING_LEN_NORMAL];
int  CGlobal::g_ClientPort;
char CGlobal::g_UserPwd[STRING_LEN_NORMAL];
int  CGlobal::g_Remember;
int  CGlobal::g_AutoLogin;
int  CGlobal::g_AutoPrint;
int  CGlobal::g_AutoLoginStatus;
char CGlobal::g_SavePath[MAX_PATH];
CStringArray  CGlobal::g_ColorPrint;
CStringArray CGlobal::g_BlackPrint;
/* 配置文件路径 */
char CGlobal::g_ExePath[MAX_PATH];
//////////////////////////////////////////////////////////////////////////
CGlobal::CGlobal()
{
	/* 配置信息 */
	memset(g_ClientIP, 0, sizeof(g_ClientIP));
	g_ClientPort = 6000;
	memset(g_UserName, 0, sizeof(g_UserName));
	memset(g_UserPwd, 0, sizeof(g_UserPwd));
	memset(g_ServerAddr, 0, sizeof(g_ServerAddr));
	g_Remember = 0;
	g_AutoLogin = 0;
	g_AutoPrint = 0;
	g_AutoLoginStatus = 0;
	memset(g_SavePath, 0, sizeof(g_SavePath));
	g_ColorPrint.RemoveAll();
	g_BlackPrint.RemoveAll();
	
	/* 配置文件路径 */
	memset(g_ExePath, 0, sizeof(g_ExePath));
}

CGlobal::~CGlobal()
{
}

int CGlobal::Init()
{
	GetExePath(g_ExePath);
	GetConfig(g_ExePath);

	return RET_OK;
}

int CGlobal::GetExePath(char *cpExePath)
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

	return RET_OK;
}

int CGlobal::GetConfig(const char *cpExePath)
{
	/* 获取配置文件全路径 */
	char CfgPath[256]={0};
	sprintf(CfgPath, "%sConfig.ini", cpExePath);
	CIni ini(CfgPath);

	/* 获取配置信息 */
	ini.ReadText("SysSet", "Username", g_UserName);
	ini.ReadText("SysSet", "ServerAddr", g_ServerAddr);
	ini.ReadText("SysSet", "Password", g_UserPwd);
	g_Remember = ini.ReadInt("SysSet", "Remember");
	g_AutoLogin = ini.ReadInt("SysSet", "AutoLogin");
	g_AutoPrint = ini.ReadInt("SysSet", "AutoPrint");
	g_AutoLoginStatus = ini.ReadInt("SysSet", "AutoLoginStatus");
	ini.ReadText("SysSet", "SavePath", g_SavePath);

	ini.ReadText("SysSet", "ClientIP", g_ClientIP);
	g_ClientPort = ini.ReadInt("SysSet", "ClientPort");
	
	char szTmp[1024] = {0};
	ini.ReadText("SysSet", "ColorPrint", szTmp);
	CDSUtils::SplitString(szTmp, "$", g_ColorPrint, TRUE);

	memset(szTmp, 0, sizeof(szTmp));
	ini.ReadText("SysSet", "BlackPrint", szTmp);
	CDSUtils::SplitString(szTmp, "$", g_BlackPrint, TRUE);
	
	return RET_OK;
}

int CGlobal::SaveCfg(const char *cpExePath, CString strClientIP, CString strServerIpv6, CString strSavePath, CString strColorPrint, CString strBlackPrint)
{
	/* 获取配置文件全路径 */
	char CfgPath[256]={0};
	sprintf(CfgPath, "%sConfig.ini", cpExePath);
	CIni ini(CfgPath);
	ini.WriteText("SysSet", "ServerAddr", strServerIpv6);
	ini.WriteText("SysSet", "SavePath", strSavePath);
	ini.WriteText("SysSet", "ColorPrint", strColorPrint);
	ini.WriteText("SysSet", "BlackPrint", strBlackPrint);
	ini.WriteText("SysSet", "ClientIP", strClientIP);

	GetConfig(cpExePath);
	return RET_OK;
}

int CGlobal::SaveServerAddr(const char *cpExePath, CString strServerAddr)
{
	/* 获取配置文件全路径 */
	char CfgPath[256]={0};
	sprintf(CfgPath, "%sConfig.ini", cpExePath);
	CIni ini(CfgPath);
	ini.WriteText("SysSet", "ServerAddr", strServerAddr);

	GetConfig(cpExePath);
	return RET_OK;
}

int CGlobal::SaveUserName(const char *cpExePath, CString strUserName, CString strPassword, int nRemember, int nAuto)
{
	/* 获取配置文件全路径 */
	char CfgPath[256]={0};
	sprintf(CfgPath, "%sConfig.ini", cpExePath);
	CIni ini(CfgPath);
	ini.WriteText("SysSet", "Username", strUserName);
	ini.WriteText("SysSet", "Password", strPassword);
	ini.WriteInt("SysSet", "Remember", nRemember);
	ini.WriteInt("SysSet", "AutoLogin", nAuto);

	GetConfig(cpExePath);
	return RET_OK;
}

int CGlobal::SaveAutoLoginStatus(const char *cpExePath, int nStatus)
{
	/* 获取配置文件全路径 */
	char CfgPath[256]={0};
	sprintf(CfgPath, "%sConfig.ini", cpExePath);
	CIni ini(CfgPath);
	ini.WriteInt("SysSet", "AutoLoginStatus", nStatus);

	GetConfig(cpExePath);
	return RET_OK;
}