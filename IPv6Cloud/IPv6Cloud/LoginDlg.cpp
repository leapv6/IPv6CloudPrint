// LoginDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "IPv6Cloud.h"
#include "LoginDlg.h"
#include "afxdialogex.h"
#include "IPv6CloudDlg.h"

#include <tlhelp32.h>

// CLoginDlg 对话框
// HttpJson
CHttpJsonReader* g_pHttpJsonReader = NULL;

IMPLEMENT_DYNAMIC(CLoginDlg, CDialogEx)

CLoginDlg::CLoginDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CLoginDlg::IDD, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);

	memset(m_szServerAddr, 0, 256);
	memset(m_szPassword, 0, 256);
	memset(m_szUserName, 0, 256);
	memset(m_szLoginKey, 0, 256);
	memset(m_szActionKey, 0, 256);
	memset(m_szUserID, 0, 256);
	
	strcpy(m_szLoginKey, STR_LOGIN_KEY);
}

CLoginDlg::~CLoginDlg()
{
}

void CLoginDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CLoginDlg, CDialogEx)
	ON_WM_PAINT()
END_MESSAGE_MAP()

/************************************************************************/
/* 对URL编码(GBK字符转UTF8,并以%xx的形式输出)                           */
/************************************************************************/
char* UrlEncode(const char* strUrlGbk)
{
	int nLen = MultiByteToWideChar(CP_ACP, 0, strUrlGbk, -1, NULL, 0);
	WCHAR* wstrUniCode = new WCHAR[nLen];
	MultiByteToWideChar(CP_ACP, 0, strUrlGbk, -1, wstrUniCode, nLen);

	int nLenOut = nLen * 9 + 1;
	char* out = new char[nLenOut];
	memset(out, 0, nLenOut);
	int i = 0;
	int j = 0;
	int ch = 0;
	int ch2 = 0;

	static char myhex[0xFF + 1][4];
	static bool isinital = false;

	if (!isinital)
	{
		for (i = 0; i <= 0xFF; i++)
		{
			myhex[i][0] = '%';
			sprintf(myhex[i]+1, "%02x", i);
		}
		isinital = true;
	}

	for (i = 0, j = 0; i < nLen; i++)
	{
		ch = wstrUniCode[i];
		if (ch > 0)
		{
			if ('A' <= ch && ch <= 'Z')				// 'A'..'Z'
			{
				out[j++] = ch;
			}
			else if ('a' <= ch && ch <= 'z')		// 'a'..'z'
			{
				out[j++] = ch;
			}
			else if ('0' <= ch && ch <= '9')		// '0'..'9'
			{
				out[j++] = ch;
			}
			else if (ch == ' ')						// space
			{
				out[j++] = '+';
			}
			else if (ch == '/' || ch == '-'			// 不需要转化
				|| ch == '_' || ch == '.' 
				|| ch == '!' || ch == '~' 
				|| ch == '*' || ch == '\''
				|| ch == '(' || ch == ')')
			{
				out[j++] = ch;
			}
			else if (ch <= 0x007f)					// ASCII控制字符
			{    
				strcat(out, myhex[ch]);
				j += 3;
			}
			else if (ch <= 0x07FF)					// 非ASCII <= 0x7FF
			{
				strcat(out, myhex[0xc0 | (ch >> 6)]);
				strcat(out, myhex[0x80 | (ch & 0x3F)]);
				j += 6;
			}
			else									// 0x7FF < ch <= 0xFFFF
			{
				strcat(out, myhex[0xe0 | (ch >> 12)]);
				strcat(out, myhex[0x80 | ((ch >> 6) & 0x3F)]);
				strcat(out, myhex[0x80 | (ch & 0x3F)]);
				j += 9;
			}
		}
	}

	out[j] = '\0';
	USES_CONVERSION;
	//CString result = A2W(out);

	//delete out;
	//out = NULL;

	delete[] wstrUniCode;
	wstrUniCode = NULL;

	return out;
}

// CLoginDlg 消息处理程序
BOOL CLoginDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// 设置此对话框的图标。当应用程序主窗口不是对话框时，框架将自动
	//  执行此操作
	SetIcon(m_hIcon, TRUE);			// 设置大图标
	SetIcon(m_hIcon, FALSE);		// 设置小图标

	// 加载登录对话框
	m_duiLoginDlg.Create(*this, _T("登录窗口"), UI_WNDSTYLE_CHILD, 0, 1, 1, 890, 590);  
	m_duiLoginDlg.ShowWindow(TRUE);
	m_duiLoginDlg.SetIcon(IDR_MAINFRAME);
	
	// 初始化登录信息
	m_duiLoginDlg.InitLoginInfo(CGlobal::g_UserName, CGlobal::g_UserPwd, (bool)CGlobal::g_Remember, (bool)CGlobal::g_AutoLogin, CGlobal::g_ServerAddr, (void *)this, CGlobal::g_AutoLoginStatus);

	// 设置窗口大小
	MoveWindow(1, 1, 891, 591, 1);
	CenterWindow();

	SetClassLong(this->m_hWnd, GCL_STYLE, GetClassLong(this->m_hWnd, GCL_STYLE) | CS_DROPSHADOW);

	//CWndShadow::Initialize(AfxGetInstanceHandle());
	//LONG styleValue = ::GetWindowLong(*this, GWL_STYLE);
	////styleValue &= ~WS_CAPTION;
	//::SetWindowLong(*this, GWL_STYLE, styleValue | WS_CLIPSIBLINGS | WS_CLIPCHILDREN);
 //   m_WndShadow.Create(m_hWnd);
	//m_WndShadow.SetSize(2);
	//m_WndShadow.SetPosition(0, 0);
	//m_WndShadow.Show(m_hWnd);
	//m_duiLoginDlg.Login();
	return TRUE;
}

void CLoginDlg::OnCancelByDui()
{
	CLoginDlg::OnCancel();
}

void CLoginDlg::OnOKByDui(char * pUserName, char * pPassword, bool bRemember, bool bAutoLogin, char * pServerAddr, bool bFlag)
{
	char strPathFileName[MAX_PATH];
	GetModuleFileName(NULL, strPathFileName, MAX_PATH);
	char* pstrFileName = strrchr(strPathFileName, '\\');
	CString strName = "";
	strName.Format("%s", pstrFileName);
	strName.Replace("\\", "");
	if (strName != "")
	{
		DWORD dwProcessId = FindAppProcessID(strName.GetBuffer(0), true);
		if (dwProcessId > 0)
		{
			m_duiLoginDlg.m_labMsg->SetVisible(true);
			m_duiLoginDlg.m_pLoginBtn->SetEnabled(true);
			return;
		}
		else if (dwProcessId == 0)
		{
			m_duiLoginDlg.m_labMsg->SetVisible(false);
		}
		else if (dwProcessId < 0)
		{
			AfxMessageBox("进程查找错误，请联系管理员！");
			return;
		}
	}

	//保存用户名密码数据
	strcpy(m_szUserName, pUserName);
	strcpy(m_szPassword, pPassword);
	strcpy(m_szServerAddr, pServerAddr);
	
	//JSON接口初始化
	if(g_pHttpJsonReader == NULL)
	{
		CString strSvrAddr = "";
		strSvrAddr.Format("%s", m_szServerAddr);
		int nPos = strSvrAddr.ReverseFind(':');
		CString strIP = strSvrAddr.Left(nPos);
		g_pHttpJsonReader = new CHttpJsonReader(strIP.GetBuffer(0));
	}

	//AES aes;
	//unsigned char ucEnUserName[256] = {0};
	//unsigned char ucEnPassword[256] = {0};
	//unsigned char uckey[256] = {0};
	//strcpy((char*)ucEnUserName, "root");
	//strcpy((char*)uckey, "IPv6Cloud");
	//aes.Cipher(ucEnUserName, uckey, strlen((char*)uckey));

    MD5 md5Pwd(m_szPassword);

	Json::Value data;  // data
	data["businessPhone"] = Json::Value(m_szUserName);
	data["businessPsw"] = Json::Value(md5Pwd.md5().c_str());
	
	Json::Value root;  // 表示整个 json 对象
	root["data"] = data;
	//root["userID"] = Json::Value("123456");;

	Json::FastWriter fast_writer;
	CString url = fast_writer.write(root).c_str();

	bool bret = false;
	if (g_pHttpJsonReader)
	{
		Json::Value value;
		Json::Value valArray;
		char strUrl[1024] = {0};

		//char *pData = UrlEncode(url.GetBuffer(0));
		sprintf(strUrl, "data=%s", url.GetBuffer(0));
		//delete pData;
		bool bSucceed = g_pHttpJsonReader->PostReadJson(m_szServerAddr, "/clientapi/checklogin", value, strUrl);
		if (bSucceed)
		{
			bool bRet = value["success"].asBool();
			
			char strErr[1024] = {0};
			char *pReason = NULL;
			CHttpJsonReader::UTF8Str2AnsiStr(value["reason"].asCString(), &pReason);
			strcpy(strErr, pReason);
			delete pReason;

			if(bRet)
			{
				char *pUserID = NULL;
				CHttpJsonReader::UTF8Str2AnsiStr(value["userID"].asCString(), &pUserID);
				strcpy(m_szUserID, pUserID);
				delete pUserID;
				
				char *puserNick = NULL;
				CHttpJsonReader::UTF8Str2AnsiStr(value["userNick"].asCString(), &puserNick);
				strcpy(m_szNickName, puserNick);
				delete puserNick;
			}
			else
			{
				if(strstr(strErr, "已经登录") != NULL)
				{
					if(IDYES == AfxMessageBox("该用户已经在其它地方登录，是否强制登录？", MB_YESNO|MB_ICONINFORMATION))
					{
						ForcedLogin();
						m_duiLoginDlg.Login();
					}
				}
				else
				{
					AfxMessageBox(strErr);
				}
				m_duiLoginDlg.m_pLoginBtn->SetEnabled(true);
				m_duiLoginDlg.m_bIsAutoLogin = false;
				return;
			}
		}
		else
		{
			MessageBox(_T("连接服务器失败！"));
			//CLoginDlg::OnCancel();
			m_duiLoginDlg.m_pLoginBtn->SetEnabled(true);
			m_duiLoginDlg.m_bIsAutoLogin = false;
			return;
		}
	}
	else
	{
		MessageBox(_T("软件故障，请重启程序！"));
		//CLoginDlg::OnCancel();
		m_duiLoginDlg.m_pLoginBtn->SetEnabled(true);
		m_duiLoginDlg.m_bIsAutoLogin = false;
		return;
	}

	//
	if (bFlag)
	{
		return;
	}

	//如果勾选了记住密码，则保存密码
	if(bRemember)
	{
		if(GetParent() != NULL)
		{
			((CIPv6CloudDlg *)GetParent())->m_cGlobal.SaveUserName(CGlobal::g_ExePath, m_szUserName, m_szPassword, 1, (int)bAutoLogin);
		}
	}
	else
	{
		if(GetParent() != NULL)
		{
			((CIPv6CloudDlg *)GetParent())->m_cGlobal.SaveUserName(CGlobal::g_ExePath, "", "", 0, (int)bAutoLogin);
		}
	}
	
	if(bAutoLogin)
	{
		//获得程序模块名称，全路径
		TCHAR exeFullPath[MAX_PATH]; 
		GetModuleFileName(NULL,exeFullPath,MAX_PATH);
		
		//获得程序模块所在文件夹路径
		string strFullExeName(exeFullPath);

		char szPath[MAX_PATH] ={0};
		strcpy(szPath, strFullExeName.c_str());
		CDSUtils::CreateRun(szPath);
	}
	else
	{
		//获得程序模块名称，全路径
		TCHAR exeFullPath[MAX_PATH]; 
		GetModuleFileName(NULL,exeFullPath,MAX_PATH);
		
		//获得程序模块所在文件夹路径
		string strFullExeName(exeFullPath);
		
		char szPath[MAX_PATH] ={0};
		strcpy(szPath, strFullExeName.c_str());
		CDSUtils::DeleteRun(szPath);
	}

	m_duiLoginDlg.m_pLoginBtn->SetEnabled(true);
	CLoginDlg::OnOK();
}

void CLoginDlg::MinWndByDui()
{
	SendMessage(WM_SYSCOMMAND, SC_MINIMIZE, 0);
}

BOOL CLoginDlg::PreTranslateMessage(MSG* pMsg) 
{
	/* 鼠标左键拖动窗口 */
	if ((pMsg->message == WM_LBUTTONDOWN))
	{
		CRect rectRegion1;
		GetWindowRect(&rectRegion1);

		rectRegion1.bottom = rectRegion1.top + 55;
		rectRegion1.right = rectRegion1.left + 740;

		CRect rectRegion2;
		GetWindowRect(&rectRegion2);

		rectRegion2.bottom = rectRegion2.top + 360;
		rectRegion2.top = rectRegion2.top + 55;

		/* 获得鼠标当前所在位置 */
		POINT pt;
		GetCursorPos(&pt);

		/* 判断鼠标是否在系统按钮区域 */
		/* (PtInRect:这个函数判断指定的点是否位于矩形lpRect内部) */
		if(rectRegion1.PtInRect(pt) || rectRegion2.PtInRect(pt))
		{
			//如果鼠标落在系统按钮区域内，则发送左键拖动窗口消息
			CPoint point;
			SetCursor(LoadCursor(NULL, IDC_SIZEALL));
			PostMessage(WM_NCLBUTTONDOWN, HTCAPTION, MAKELPARAM(point.x, point.y));
			return TRUE;
		}
	}

	return CDialog::PreTranslateMessage(pMsg);
}

int CLoginDlg::SaveServerAddr(CString strServerAddr)
{
	if(GetParent() != NULL)
	{
		((CIPv6CloudDlg *)GetParent())->m_cGlobal.SaveServerAddr(CGlobal::g_ExePath, strServerAddr);
		((CIPv6CloudDlg *)GetParent())->ShowTrayTips("系统提示", "保存成功！");
	}

	return 0;
}


HCURSOR CLoginDlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

void CLoginDlg::OnPaint()
{
	CPaintDC dc(this); // device context for painting
	// TODO: 在此处添加消息处理程序代码
	// 不为绘图消息调用 CDialogEx::OnPaint()

	CRect   rect;
	GetClientRect(rect);
	dc.FillSolidRect(rect, RGB(150,150,150));   //设置为绿色背景

	CDialog::OnPaint();
}

//更新客户端在线状态
void CLoginDlg::ForcedLogin()
{
	Json::Value data;  // data
	data["businessPhone"] = Json::Value(m_szUserName);

	Json::Value root;  // 表示整个 json 对象
	root["data"] = data;

	Json::FastWriter fast_writer;
	CString url = fast_writer.write(root).c_str();

	bool bret = true;
	if (g_pHttpJsonReader)
	{
		Json::Value value;
		Json::Value valArray;

		char strParam[1024] = {0};
		sprintf(strParam, "data=%s", url.GetBuffer(0));

		bool bSucceed = g_pHttpJsonReader->PostReadJson(m_szServerAddr, "/clientapi/force_offline", value, strParam);
		if (bSucceed)
		{
			bool bRet = value["success"].asBool();

			if(bRet)
			{
			}
			else
			{
				char strErr[1024] = {0};
				char *pReason = NULL;
				CHttpJsonReader::UTF8Str2AnsiStr(value["reason"].asCString(), &pReason);
				strcpy(strErr, pReason);
				delete pReason;
			}
		}
	}
}

/************************************************************************/
/* 查找指定应用名的进程                                                 */
/************************************************************************/
DWORD CLoginDlg::FindAppProcessID(LPCSTR strAppName, bool bExcludeCurrentProcess)
{
	DWORD dwCurrentProcessId = 0;
	if (bExcludeCurrentProcess)
	{
		dwCurrentProcessId = ::GetCurrentProcessId();
	}

	DWORD dwProcessId = -1;
	HANDLE handle = ::CreateToolhelp32Snapshot(TH32CS_SNAPALL, 0);
	PROCESSENTRY32 Info;
	Info.dwSize = sizeof(PROCESSENTRY32);
	if (::Process32First(handle, &Info))
	{
		dwProcessId = 0;
		do
		{
			if (Info.th32ProcessID != dwCurrentProcessId && strcmpi(Info.szExeFile, strAppName) == 0)
			{
				dwProcessId = Info.th32ProcessID;
				break;
			}
		} while (::Process32Next(handle, &Info));
		::CloseHandle(handle);
	}
	return dwProcessId;
}
