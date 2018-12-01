#pragma once 

class CDuiLoginDlg:public CWindowWnd,INotifyUI
{
public:
	CDuiLoginDlg();
	~CDuiLoginDlg();

	LPCTSTR GetWindowClassName() const { return _T("登录窗口"); };
	UINT GetClassStyle() const { return CS_DBLCLKS; };
	void Notify(TNotifyUI& msg);
	LRESULT OnCreate(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnSysCommand(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT HandleMessage(UINT uMsg, WPARAM wParam, LPARAM lParam);

public:
	CPaintManagerUI m_pm;

	CButtonUI* m_pCloseBtn;
	CButtonUI* m_pSetBtn;
	CButtonUI* m_pMinBtn;

	CEditUI* m_pUserNameEdit;
	CEditUI* m_pPasswordEdit;

	CButtonUI* m_pRememberBtn;
	CButtonUI* m_pForgetBtn;

	CButtonUI* m_pLoginBtn;
	CButtonUI* m_pCancleBtn;

	CButtonUI* m_pQQBtn;
	CButtonUI* m_pWeiXinBtn;
	CButtonUI* m_pErWeiBtn;

	bool       m_bRemember;
	bool       m_bAutoLogin;
	
	CEditUI*   m_pServerAddrEdit;
	CButtonUI* m_pSetOkBtn;
	CButtonUI* m_pSetCancleBtn;

	CTabLayoutUI* m_pTabMain;

	CButtonUI* m_pAutoedBtn;
	CButtonUI* m_pUnAutoBtn;

	CLabelUI * m_labMsg; //labMainConnectStatus

	int        m_nTabSel;

	bool       m_bIsAutoLogin;

	void * m_pParent;
public:
	// 初始化登录信息
	void InitLoginInfo(char * szUserName, char * szUserPwd, bool bRemember, bool bAutoLogin, char * szServerAddr, void * pParent, bool bAutoLoginStatus);

	void SetRememberStatus();

	// 登录
	bool Login();

	// 取消
	bool Cancel();

	// 设置OK
	bool SetOk();

	// 最小化窗口
	bool MinWnd();
};