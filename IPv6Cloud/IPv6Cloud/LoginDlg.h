#pragma once
#include "resource.h"
#include "DuiDlg\DuiLoginDlg.h"
#include "Comm\HttpJsonReader.h"
#include "Comm\md5.h"
#include "Comm\BASE64_API.h"
#include "DuiDlg\WndShadow.h"

#define  STR_LOGIN_KEY                             "IPv6Cloud"
// CLoginDlg 对话框

extern CHttpJsonReader* g_pHttpJsonReader;
class CLoginDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CLoginDlg)

public:
	CLoginDlg(CWnd* pParent = NULL);   // 标准构造函数
	virtual ~CLoginDlg();

	void OnCancelByDui();
	void OnOKByDui(char * pUserName, char * pPassword, bool bRemember, bool bAutoLogin, char * pServerAddr, bool bFlag = false);
	void MinWndByDui();
	int SaveServerAddr(CString strServerAddr);
	void ForcedLogin();

	DWORD FindAppProcessID(LPCSTR strAppName, bool bExcludeCurrentProcess);
public:
	//Dui对话框
	CDuiLoginDlg m_duiLoginDlg;
	CWndShadow m_WndShadow;

	char m_szUserID[256];
	char m_szServerAddr[256];
	char m_szPassword[256];
	char m_szUserName[256];
	char m_szLoginKey[256];
	char m_szActionKey[256];

	char m_szNickName[256];

	CString m_strVrsWebAddr;
public:
// 对话框数据
	enum { IDD = IDD_LOGIN_DLG };
	
	virtual BOOL PreTranslateMessage(MSG* pMsg);
protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV 支持
	
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()
protected:
	HICON     m_hIcon;
public:
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
};
