// LoginDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "DuiLoginDlg.h"
#include "../LoginDlg.h"

CDuiLoginDlg::CDuiLoginDlg()
{
	m_nTabSel = 0;
	m_bRemember = false;
	m_bAutoLogin = false;
	m_pParent = NULL;
	m_bIsAutoLogin = false;
}

CDuiLoginDlg::~CDuiLoginDlg()
{
}

LRESULT CDuiLoginDlg::OnCreate(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	CPaintManagerUI::SetInstance(AfxGetInstanceHandle());
	CPaintManagerUI::SetResourcePath(CPaintManagerUI::GetInstancePath() + _T("skin"));

	m_pm.Init(m_hWnd);
	CDialogBuilder builder;
	CControlUI* pRoot = builder.Create("LoginDlg.xml", (UINT)0,  NULL, &m_pm);
	ASSERT(pRoot && "Failed to parse XML");
	m_pm.AttachDialog(pRoot);
	m_pm.AddNotifier(this);

	m_pCloseBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonClose")));
	m_pSetBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonSet")));
	m_pMinBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMin")));

	m_pRememberBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonRemember")));
	m_pForgetBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonForget")));

	m_pLoginBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonLogin")));
	m_pCancleBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonCancle")));

	m_pQQBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonQQ")));
	m_pWeiXinBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonWeiXin")));
	m_pErWeiBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonErWei")));

	m_pUserNameEdit = static_cast<CEditUI*>(m_pm.FindControl(_T("EditUserName")));
	m_pPasswordEdit = static_cast<CEditUI*>(m_pm.FindControl(_T("EditPassword")));

	m_pServerAddrEdit = static_cast<CEditUI*>(m_pm.FindControl(_T("EditServerAddr")));
	m_pSetOkBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonSetOK")));
	m_pSetCancleBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonSetCancle")));
	
	m_pAutoedBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonAuto")));
	m_pUnAutoBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonAutoed")));

	m_pTabMain = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));

	m_labMsg = static_cast<CLabelUI*>(m_pm.FindControl(_T("labMsg")));
	return 0;
}

void CDuiLoginDlg::Notify(TNotifyUI& msg)
{
	if( msg.sType == _T("click") ) {
		if( msg.pSender == m_pCloseBtn) {
			PostQuitMessage(0);
			return; 
		}
		else if( msg.pSender == m_pSetBtn ) { 
			if(m_nTabSel == 0)
			{
				m_pTabMain->SelectItem(1);
				m_nTabSel = 1;
			}
			else
			{
				m_pTabMain->SelectItem(0);
				m_nTabSel = 0;
			}
			return; 
		}
		else if( msg.pSender == m_pMinBtn ) {
			MinWnd();
			return; 
		}
		else if( msg.pSender == m_pRememberBtn ) { 
			m_bRemember = !m_bRemember;
			SetRememberStatus();
			return; 
		}
		else if( msg.pSender == m_pForgetBtn ) { 
			ShellExecute(NULL, "open", "http://www.v6cp.com/welcome/business_forget_pass", NULL, NULL, SW_SHOWNORMAL);
			return; 
		}
		else if( msg.pSender == m_pLoginBtn ) { 
			Login();
			return; 
		}
		else if( msg.pSender == m_pCancleBtn ) { 
			Cancel();
			return; 
		}
		else if( msg.pSender == m_pSetOkBtn ) { 
			SetOk();
			m_pTabMain->SelectItem(0);
			m_nTabSel = 0;
			return; 
		}
		else if( msg.pSender == m_pSetCancleBtn ) {
			m_pTabMain->SelectItem(0);
			m_nTabSel = 0;
			return;  
			return; 
		}
		else if( msg.pSender == m_pQQBtn ) { 
			TRACE("m_pQQBtn\n");
			return; 
		}
		else if( msg.pSender == m_pWeiXinBtn ) { 
			TRACE("m_pWeiXinBtn\n");
			return; 
		}
		else if( msg.pSender == m_pErWeiBtn ) { 
			TRACE("m_pErWeiBtn\n");
			return; 
		}
		else if( msg.pSender == m_pAutoedBtn ) { 
			m_bAutoLogin = false;
			m_pAutoedBtn->SetVisible(false);
			m_pUnAutoBtn->SetVisible(true);
			return; 
		}
		else if( msg.pSender == m_pUnAutoBtn ) { 
			m_bAutoLogin = true;
			m_pAutoedBtn->SetVisible(true);
			m_pUnAutoBtn->SetVisible(false);
			return; 
		}
	}
}

LRESULT CDuiLoginDlg::OnSysCommand(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	LRESULT lRes = CWindowWnd::HandleMessage(uMsg, wParam, lParam);

	return lRes;
}

LRESULT CDuiLoginDlg::HandleMessage(UINT uMsg, WPARAM wParam, LPARAM lParam)
{
	LRESULT lRes = 0;
	BOOL bHandled = TRUE;
	switch( uMsg ) {
	case WM_CREATE:        lRes = OnCreate(uMsg, wParam, lParam, bHandled); break;
	case WM_SYSCOMMAND:    lRes = OnSysCommand(uMsg, wParam, lParam, bHandled); break;
	default:
		bHandled = FALSE;
	}
	if( bHandled ) return lRes;
	if( m_pm.MessageHandler(uMsg, wParam, lParam, lRes) ) return lRes;
	return CWindowWnd::HandleMessage(uMsg, wParam, lParam);
}

// 初始化登录信息
void CDuiLoginDlg::InitLoginInfo(char * szUserName, char * szUserPwd, bool bRemember, bool bAutoLogin, char * szServerAddr, void * pParent, bool bAutoLoginStatus)
{
	//如果是记住密码状态，则设置用户名密码信息
	m_bRemember = bRemember;
	if(m_bRemember)
	{
		m_pUserNameEdit->SetText(szUserName);
		m_pPasswordEdit->SetText(szUserPwd);
	}
	else
	{
		m_pUserNameEdit->SetText("");
		m_pPasswordEdit->SetText("");
	}

	SetRememberStatus();
	m_pServerAddrEdit->SetText(szServerAddr);
	m_pParent = pParent;

	if(bAutoLogin)
	{
		m_bAutoLogin = true;
		m_pAutoedBtn->SetVisible(true);
		m_pUnAutoBtn->SetVisible(false);
		m_bIsAutoLogin = true;

		if (bAutoLoginStatus)
		{
			return;
		}

		if (strlen(szUserName) != 0 && strlen(szUserPwd) != 0 && bRemember == true)
		{
			Login();
		}
	}
	else
	{ 
		m_bAutoLogin = false;
		m_pAutoedBtn->SetVisible(false);
		m_pUnAutoBtn->SetVisible(true);
	}
}

void CDuiLoginDlg::SetRememberStatus()
{
	if(m_bRemember)
	{
		m_pRememberBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonRemember")));
		if( m_pRememberBtn)
			m_pRememberBtn->SetVisible(false);
		m_pRememberBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonRemembered")));
		if( m_pRememberBtn ) 
			m_pRememberBtn->SetVisible(true);
	}
	else
	{
		m_pRememberBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonRemembered")));
		if( m_pRememberBtn)
			m_pRememberBtn->SetVisible(false);
		m_pRememberBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonRemember")));
		if( m_pRememberBtn ) 
			m_pRememberBtn->SetVisible(true);
	}
}

// 登录
bool CDuiLoginDlg::Login()
{
	//从界面获得用户名密码数据
	CString strUserName = m_pUserNameEdit->GetText();
	CString strPassword = m_pPasswordEdit->GetText();
	CString strServerAddr = m_pServerAddrEdit->GetText();

	////保存用户名密码数据
	//char szEnUserName[256] = {0};
	//char szEnPassword[256] = {0};

	//strcpy(szEnUserName, strUserName.GetBuffer(0));
	//strcpy(szEnPassword, strPassword.GetBuffer(0));

	//AES aes(STR_LOGIN_KEY);
	//aes.Cipher(
	////STR_LOGIN_KEY

	//char szString[256] = {0};
	//sprintf(szString, "%s%s", strUserName.GetBuffer(0), strPassword.GetBuffer(0));

	//char szKey[256] = {0};
	//strcpy(szKey, szString);

	//MD5 md5key(szKey);

	//AES aes((unsigned char *)md5key.md5().c_str());
	//aes.Cipher((unsigned char *)szString);
	

	if(strUserName == "" || strPassword == "" || strServerAddr == "")
	{
		AfxMessageBox("用户名或密码为空");
		m_bIsAutoLogin = false;
		return false;
	}

	if(m_pParent != NULL)
	{
		m_pLoginBtn->SetEnabled(false);
		((CLoginDlg *)m_pParent)->OnOKByDui(strUserName.GetBuffer(0), strPassword.GetBuffer(0), m_bRemember, m_bAutoLogin, strServerAddr.GetBuffer(0));
	}
	return true;
}

// 取消
bool CDuiLoginDlg::Cancel()
{
	if(m_pParent != NULL)
	{
		((CLoginDlg *)m_pParent)->OnCancelByDui();
	}
	return true;
}

bool CDuiLoginDlg::SetOk()
{
	//SaveSavePath
	CString strServerIpv6 = m_pServerAddrEdit->GetText();
	if(m_pParent != NULL)
	{
		((CLoginDlg *)m_pParent)->SaveServerAddr(strServerIpv6);
	}
	return true;
}

// 最小化
bool CDuiLoginDlg::MinWnd()
{
	if(m_pParent != NULL)
	{
		((CLoginDlg *)m_pParent)->MinWndByDui();
	}
	return true;
}