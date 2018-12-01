#include "StdAfx.h"  
#include "DuiLib_Dialog.h"  


CDuiLib_Dialog::CDuiLib_Dialog(void)  
{
}  

CDuiLib_Dialog::~CDuiLib_Dialog(void)  
{  
}  

LPCTSTR CDuiLib_Dialog::GetWindowClassName() const  
{  
	return "CTestDlg";  
}  

UINT CDuiLib_Dialog::GetClassStyle() const  
{  
	return UI_CLASSSTYLE_FRAME | CS_DBLCLKS;  
}  

void CDuiLib_Dialog::OnFinalMessage(HWND hWnd)  
{  

}  

void CDuiLib_Dialog::Notify(TNotifyUI& msg)  
{  
	if( msg.sType == _T("click") ) 
	{  
		if( msg.pSender->GetName() == _T("closebtn") ) 
		{  
			Close();
		}  
	}  
}  

LRESULT CDuiLib_Dialog::HandleMessage(UINT uMsg, WPARAM wParam, LPARAM lParam)  
{  
	if( uMsg == WM_CREATE ) 
	{  
		/*
		m_pm.Init(m_hWnd);  
		CControlUI *pButton = new CButtonUI;  
		pButton->SetName(_T("closebtn"));  
		pButton->SetBkColor(0xFFFF0000);  
		m_pm.AttachDialog(pButton);  
		m_pm.AddNotifier(this); */ 

		CPaintManagerUI::SetInstance(AfxGetInstanceHandle());
		CPaintManagerUI::SetResourcePath(CPaintManagerUI::GetInstancePath() + _T("skin"));

		m_pm.Init(m_hWnd);  
		CDialogBuilder builder;  
		CControlUI *pRoot = builder.Create("MainActivity.xml", (UINT)0, NULL, &m_pm);  
		ASSERT(pRoot && "Failed to parse XML");  
		m_pm.AttachDialog(pRoot);
		m_pm.AddNotifier(this);  

		return 0;  
	}  
	else if( uMsg == WM_DESTROY ) 
	{  
		::PostQuitMessage(0);  
	}  
	LRESULT lRes = 0;  
	if( m_pm.MessageHandler(uMsg, wParam, lParam, lRes) ) return lRes;
	return CWindowWnd::HandleMessage(uMsg, wParam, lParam);
}  