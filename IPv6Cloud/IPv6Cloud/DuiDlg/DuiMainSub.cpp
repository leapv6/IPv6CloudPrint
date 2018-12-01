// LoginDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "DuiMainDlg.h"
#include "../IPv6CloudDlg.h"

CDuiMainDlg::CDuiMainDlg()
{
	m_pParent = NULL;
}

CDuiMainDlg::~CDuiMainDlg()
{
}

LRESULT CDuiMainDlg::OnCreate(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	CPaintManagerUI::SetInstance(AfxGetInstanceHandle());
	CPaintManagerUI::SetResourcePath(CPaintManagerUI::GetInstancePath() + _T("skin"));

	m_pm.Init(m_hWnd);
	CDialogBuilder builder;
	CControlUI* pRoot = builder.Create("MainDlg.xml", (UINT)0,  NULL, &m_pm);
	ASSERT(pRoot && "Failed to parse XML");
	m_pm.AttachDialog(pRoot);
	m_pm.AddNotifier(this);

	m_pCloseBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonClose")));
	m_pResizeBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonResize")));
	m_pMaxBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMax")));
	m_pMinBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMin")));
	
	//
	m_listMain = static_cast<CListUI*>(m_pm.FindControl(_T("listMain")));
	m_btnMainCount  = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnMainCount")));
	m_btnMainSpe  = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnMainSpe")));
	m_labMainUn  = static_cast<CLabelUI*>(m_pm.FindControl(_T("labMainUn")));
	m_btnMainInfo = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnMainInfo")));
	m_btnUserInfo = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnUserInfo")));
	
	m_btnPrintOff = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonPrintOff")));
	m_btnPrintOn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonPrintOn")));

	m_btnMainSelAll = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMainSelAll")));
	m_btnMainFresh = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMainFresh")));
	m_btnMainPrePage = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMainPrePage")));
	m_btnMainNextPage = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMainNextPage")));
	m_labMainCurPage = static_cast<CLabelUI*>(m_pm.FindControl(_T("labMainCurPage")));
	m_labMainTotalPage = static_cast<CLabelUI*>(m_pm.FindControl(_T("labMainTotalPage")));
	m_editMainGotoPage = static_cast<CEditUI*>(m_pm.FindControl(_T("editMainGotoPage")));
	m_btnMainGotoPage = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMainGotoPage")));

	m_labMainConnectStatus = static_cast<CLabelUI*>(m_pm.FindControl(_T("labMainConnectStatus")));
	m_pMainSetStatusBtn = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonMainSetStatus")));

	/*历史订单*/
	m_listHistory = static_cast<CListUI*>(m_pm.FindControl(_T("listHistory")));
	m_btnPrint = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonPrint")));
	m_btnExport = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonExport")));
	
	m_btnHisSelAll = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonHisSelAll")));
	m_btnHisFresh = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonHisPrePage")));
	m_btnHisPrePage = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonHisPrePage")));
	m_btnHisNextPage = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonHisNextPage")));
	m_labHisCurPage = static_cast<CLabelUI*>(m_pm.FindControl(_T("labHisCurPage")));
	m_labHisTotalPage = static_cast<CLabelUI*>(m_pm.FindControl(_T("labHisTotalPage")));
	m_editHisGotoPage = static_cast<CEditUI*>(m_pm.FindControl(_T("editHisGotoPage")));
	m_btnHisGotoPage = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonHisGotoPage")));

	/*软件配置*/
	m_editLocalIpv6 = static_cast<CEditUI*>(m_pm.FindControl(_T("editLocalIpv6")));
	m_editServerIpv6 = static_cast<CEditUI*>(m_pm.FindControl(_T("editServerIpv6")));
	m_editSaveFilePath = static_cast<CEditUI*>(m_pm.FindControl(_T("editSaveFilePath")));
	m_btnPathBrowser = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnPathBrowser")));
	m_btnOpenPath = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnOpenPath")));
	m_listBlackPrint = static_cast<CListUI*>(m_pm.FindControl(_T("listBlackPrint")));
	m_listPrint = static_cast<CListUI*>(m_pm.FindControl(_T("listPrint")));
	m_listColorPrint = static_cast<CListUI*>(m_pm.FindControl(_T("listColorPrint")));
	m_btnBlackLeft = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnBlackLeft")));
	m_btnBlackRight = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnBlackRight")));
	m_btnColorLeft = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnColorLeft")));
	m_btnColorRight = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnColorRight")));
	m_btnSetOK = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnSetOK")));
	m_btnSetCancle = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnSetCancle")));

	//
	m_labAccount1 = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount1")));
	btnColorCount = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount2")));
	btnSingleCount = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount3")));
	m_btnOrderCount = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount4")));
	btnPaperCount = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount5")));
	btnBlackCount = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount6")));
	btnDoubleCount = static_cast<CButtonUI*>(m_pm.FindControl(_T("labAccount7")));
	m_btnAccountAddr = static_cast<CButtonUI*>(m_pm.FindControl(_T("btnAccountAddr")));

	//搜索
	m_labSearch = static_cast<CLabelUI*>(m_pm.FindControl(_T("labSearch")));
	m_editSearch = static_cast<CEditUI*>(m_pm.FindControl(_T("editSearch")));
	m_btnSearch = static_cast<CButtonUI*>(m_pm.FindControl(_T("ButtonSearch")));

	/*表头*/
	m_headMainNormal = static_cast<CListHeaderItemUI*>(m_pm.FindControl(_T("headMainNormal")));
	m_headMainDouble = static_cast<CListHeaderItemUI*>(m_pm.FindControl(_T("headMainDouble")));
	m_headMainBlack = static_cast<CListHeaderItemUI*>(m_pm.FindControl(_T("headMainBlack")));
	m_headHisNormal = static_cast<CListHeaderItemUI*>(m_pm.FindControl(_T("headHisNormal")));
	m_headHisDouble = static_cast<CListHeaderItemUI*>(m_pm.FindControl(_T("headHisDouble")));
	m_headHisBlack = static_cast<CListHeaderItemUI*>(m_pm.FindControl(_T("headHisBlack")));

	m_optMain = static_cast<COptionUI*>(m_pm.FindControl(_T("optMain")));
	m_optSysSet = static_cast<COptionUI*>(m_pm.FindControl(_T("optSysSet")));
	m_optHistory = static_cast<COptionUI*>(m_pm.FindControl(_T("optHistory")));
	m_optAccount = static_cast<COptionUI*>(m_pm.FindControl(_T("optAccount")));

	return 0;
}

void CDuiMainDlg::Notify(TNotifyUI& msg)
{
	CDuiString strName = msg.pSender->GetName();
	if( msg.sType == _T("click") ) {	
		if(strName == _T("ButtonClose")) {
			((CIPv6CloudDlg *)m_pParent)->ShowWindow(SW_HIDE);
			return; 
		}
		else if(strName == _T("ButtonMin")) { 
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->MinWndByDui();
			}
			return;
		}
		else if(strName == _T("ButtonMax")) {
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->MaxWndByDui();
				SendMessage(WM_SYSCOMMAND, SC_MAXIMIZE, 0);
			}
			return;
		}
		else if(strName == _T("ButtonResize")) {
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->RestoreWndByDui();
				SendMessage(WM_SYSCOMMAND, SC_RESTORE, 0);
			}
			return; 
		}
		else if(strName == _T("ButtonMainSetStatus")) {
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->OnSetStatusBtn();
			}
			return; 
		}
		/*业务处理*/
		else if(strName == _T("ButtonPrintOff"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->m_bAutoPrint = true;
				m_btnPrintOff->SetVisible(false);
				m_btnPrintOn->SetVisible(true);
				
				((CIPv6CloudDlg *)m_pParent)->ShowTrayTips("系统提示", "自动打印模式已开启！");
			}
		}
		else if(strName == _T("ButtonPrintOn"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->m_bAutoPrint = false;
				m_btnPrintOff->SetVisible(true);
				m_btnPrintOn->SetVisible(false);
				((CIPv6CloudDlg *)m_pParent)->ShowTrayTips("系统提示", "自动打印模式已关闭！");
			}
		}
		else if(strName == _T("btnMainCount"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->SetSpeOrder(0);
				m_btnMainSpe->SetTextColor(0xFF08AEEA);
			}
		}
		else if(strName == _T("btnMainSpe"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->SetSpeOrder(1);
				m_btnMainSpe->SetTextColor(0xFFFF0000);
			}
		}
		else if(strName == _T("btnSeled"))
		{
			msg.pSender->SetVisible(false);
			CControlUI *pCtrl =m_pm.FindSubControlByName(msg.pSender->GetParent(), _T("btnUnSel"));
			if(pCtrl)
			{
				pCtrl->SetVisible(true);
			}
		}
		else if(strName == _T("btnUnSel"))
		{
			msg.pSender->SetVisible(false);
			CControlUI *pCtrl =m_pm.FindSubControlByName(msg.pSender->GetParent(), _T("btnSeled"));
			if(pCtrl)
			{
				pCtrl->SetVisible(true);
			}

			//CControlUI *pCtEleNode =m_pm.FindSubControlByName(msg.pSender->GetParent()->GetParent()->GetParent()->GetParent()->GetParent(), _T("lstCtEleNode"));
			//if(pCtEleNode)
			//{
			//	pCtEleNode->SetBkColor(0xFFC1E3FF);
			//}
		}
		else if(strName == _T("ButtonMainSelAll"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->SelAll(0);
			}
		}
		else if(strName == _T("ButtonMainFresh"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->Refresh(0);
			}
		}
		else if(strName == _T("ButtonMainPrePage"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->PrePage(0);
			}
		}
		else if(strName == _T("ButtonMainNextPage"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->NextPage(0);
			}
		}
		else if(strName == _T("ButtonMainGotoPage"))
		{
			if(m_pParent != NULL)
			{
				CString strPage = m_editMainGotoPage->GetText();
				((CIPv6CloudDlg *)m_pParent)->GotoPage(0, atoi(strPage));
			}
		}
		else if(strName == _T("btnMainInfo"))
		{
			ShellExecute(NULL, "open", DS_TRAY_WEB_STR, NULL, NULL, SW_SHOWNORMAL);
		}
		else if (strName == _T("btnUserInfo"))
		{
			((CIPv6CloudDlg *)m_pParent)->SetAutoLoginStatus(1);
			((CIPv6CloudDlg *)m_pParent)->Restart();
		}
		/*软件配置*/
		else if(strName == _T("btnPathBrowser")) {
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->PathBrowser();
			}
			return; 
		}
		else if(strName == _T("btnOpenPath")) {
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->OpenPath();
			}
			return; 
		}
		else if(strName == _T("btnBlackLeft")) {
			AddBlackPrint();
			return; 
		}
		else if(strName == _T("btnBlackRight")) {
			DeleteBlackPrint();
			return; 
		}
		else if(strName == _T("btnColorLeft")) {
			AddColorPrint();
			return; 
		}
		else if(strName == _T("btnColorRight")) {
			DeleteColorPrint();
			return; 
		}
		else if(strName == _T("btnSetOK")) {
			SaveCfg();
			//int iIndex = m_listPrint->GetCurSel();
			//if(iIndex < 0)
			//{
			//	AfxMessageBox("打印机测试，请先选择一台打印机,请将测试文件放在d:\\test.docx");
			//	return;
			//}
			//CControlUI *pCtrlEle = m_listPrint->GetItemAt(iIndex);

			//CString strName = pCtrlEle->GetText();
			//((CIPv6CloudDlg *)m_pParent)->TestPrint(strName);
			return; 
		}
		else if(strName == _T("btnSetCancle")) {
			return; 
		}
		/*<!--历史订单-->*/
		else if(strName == _T("ButtonPrint")) {
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->PrintHis();
			}
			return; 
		}
		else if(strName == _T("ButtonExport")) {
			if(m_pParent != NULL)
			{
				CString strPath = ((CIPv6CloudDlg *)m_pParent)->ExportHis();
				if(strPath == "")
				{
					((CIPv6CloudDlg *)m_pParent)->ShowTrayTips("导出失败！", strPath);
				}
				else
				{
					((CIPv6CloudDlg *)m_pParent)->ShowTrayTips("导出成功！", strPath);
				}
			}
			return;
		}
		else if(strName == _T("ButtonHisSelAll"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->SelAll(1);
			}
		}
		else if(strName == _T("ButtonHisFresh"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->Refresh(1);
			}
		}
		else if(strName == _T("ButtonHisPrePage"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->PrePage(1);
			}
		}
		else if(strName == _T("ButtonHisNextPage"))
		{
			if(m_pParent != NULL)
			{
				((CIPv6CloudDlg *)m_pParent)->NextPage(1);
			}
		}
		else if(strName == _T("ButtonHisGotoPage"))
		{
			if(m_pParent != NULL)
			{
				CString strPage = m_editHisGotoPage->GetText();
				((CIPv6CloudDlg *)m_pParent)->GotoPage(1, atoi(strPage));
			}
		}
		else if(strName == _T("ButtonSearch"))
		{
			Search();
		}
		//结算
		else if(strName == _T("labAccount2"))//btnColorCount
		{
			m_optHistory->Selected(true);
			m_optAccount->Selected(false);
			CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisNormal = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisDouble = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisBlack = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nMainMenuFlag = 1;
			((CIPv6CloudDlg *)m_pParent)->OnBlackColor();
		}
		else if(strName == _T("labAccount3"))//btnSingleCount
		{
			m_optHistory->Selected(true);
			m_optAccount->Selected(false);
			CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisNormal = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisDouble = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisBlack = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nMainMenuFlag = 1;
			((CIPv6CloudDlg *)m_pParent)->OnDoubleSingle();
		}
		else if(strName == _T("labAccount4"))//m_btnOrderCount
		{
			m_optHistory->Selected(true);
			m_optAccount->Selected(false);
			CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
			((CIPv6CloudDlg *)m_pParent)->GetAndRefresh(1);
		}
		else if(strName == _T("labAccount5"))//btnPaperCount
		{
			m_optHistory->Selected(true);
			m_optAccount->Selected(false);
			CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
			((CIPv6CloudDlg *)m_pParent)->GetAndRefresh(1);
		}
		else if(strName == _T("labAccount6"))//btnBlackCount
		{
			m_optHistory->Selected(true);
			m_optAccount->Selected(false);
			CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisNormal = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisDouble = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisBlack = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nMainMenuFlag = 1;
			((CIPv6CloudDlg *)m_pParent)->OnBlackBlack();
		}
		else if(strName == _T("labAccount7"))//btnDoubleCount
		{
			m_optHistory->Selected(true);
			m_optAccount->Selected(false);
			CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisNormal = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisDouble = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nSelHisBlack = -1;
			((CIPv6CloudDlg *)m_pParent)->m_nMainMenuFlag = 1;
			((CIPv6CloudDlg *)m_pParent)->OnDoubleDouble();
		}
		else if(strName == _T("btnAccountAddr"))
		{
			ShellExecute(NULL, "open", DS_TRAY_WEB_STR, NULL, NULL, SW_SHOWNORMAL);
		}
	}
	else if(msg.sType == _T("selectchanged"))
	{
		CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));

		if(strName == _T("optMain"))
		{
			pControl->SelectItem(0);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
		}
		else if(strName == _T("optSysSet"))
		{
			m_labSearch->SetVisible(false);
			m_editSearch->SetVisible(false);
			m_btnSearch->SetVisible(false);
			pControl->SelectItem(1);
		}
		else if(strName == _T("optHistory"))
		{
			pControl->SelectItem(2);
			m_labSearch->SetVisible(true);
			m_editSearch->SetVisible(true);
			m_btnSearch->SetVisible(true);
		}
		else if(strName == _T("optAccount"))
		{
			m_labSearch->SetVisible(false);
			m_editSearch->SetVisible(false);
			m_btnSearch->SetVisible(false);
			pControl->SelectItem(3);
		}
	}
	else if(msg.sType == DUI_MSGTYPE_MENU)
	{
		if(strName == _T("listMain"))
		{
			((CIPv6CloudDlg *)m_pParent)->MainRightDown(0);
		}
		else if(strName == _T("listHistory"))
		{
			((CIPv6CloudDlg *)m_pParent)->MainRightDown(1);
		}
		else if(strName == _T("listBlackPrint"))
		{
			((CIPv6CloudDlg *)m_pParent)->PrintRDown(0);
		}
		else if(strName == _T("listPrint"))
		{
			((CIPv6CloudDlg *)m_pParent)->PrintRDown(1);
		}
		else if(strName == _T("listColorPrint"))
		{
			((CIPv6CloudDlg *)m_pParent)->PrintRDown(2);
		}
	}
	else if(msg.sType == DUI_MSGTYPE_SETFOCUS)
	{
		if(strName == _T("editSearch"))
		{
			if(m_editSearch->GetText() == _T("请输入电话号码"))
			{
				m_editSearch->SetText("");
			}
		}
	}
	else if(msg.sType == DUI_MSGTYPE_KILLFOCUS)
	{
		if(strName == _T("editSearch"))
		{
			if(m_editSearch->GetText() == _T(""))
			{
				m_editSearch->SetText("请输入电话号码");
			}
		}
	}
	else if(msg.sType == _T("headerclick"))
	{
		/*单双 黑白 普通*/
		if(strName == _T("headMainNormal"))
		{
			((CIPv6CloudDlg *)m_pParent)->NormalMenu(0);
		}
		else if(strName == _T("headMainDouble"))
		{
			((CIPv6CloudDlg *)m_pParent)->DoubleMenu(0);
		}
		else if(strName == _T("headMainBlack"))
		{
			((CIPv6CloudDlg *)m_pParent)->BlackMenu(0);
		}
		if(strName == _T("headHisNormal"))
		{
			((CIPv6CloudDlg *)m_pParent)->NormalMenu(1);
		}
		else if(strName == _T("headHisDouble"))
		{
			((CIPv6CloudDlg *)m_pParent)->DoubleMenu(1);
		}
		else if(strName == _T("headHisBlack"))
		{
			((CIPv6CloudDlg *)m_pParent)->BlackMenu(1);
		}
	}
	else
	{
		TRACE("no hand msg type->%s, name->%s\n", (CString)msg.sType, (CString)strName);
	}
}

LRESULT CDuiMainDlg::OnSize(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	SIZE szRoundCorner = m_pm.GetRoundCorner();
	if( !::IsIconic(*this) && (szRoundCorner.cx != 0 || szRoundCorner.cy != 0) ) {
		CDuiRect rcWnd;
		::GetWindowRect(*this, &rcWnd);
		rcWnd.Offset(-rcWnd.left, -rcWnd.top);
		rcWnd.right++; rcWnd.bottom++;
		HRGN hRgn = ::CreateRoundRectRgn(rcWnd.left, rcWnd.top, rcWnd.right, rcWnd.bottom, szRoundCorner.cx, szRoundCorner.cy);
		::SetWindowRgn(*this, hRgn, TRUE);
		::DeleteObject(hRgn);
	}

	bHandled = FALSE;
	return 0;
}

LRESULT CDuiMainDlg::OnGetMinMaxInfo(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	MONITORINFO oMonitor = {};
	oMonitor.cbSize = sizeof(oMonitor);
	::GetMonitorInfo(::MonitorFromWindow(*this, MONITOR_DEFAULTTOPRIMARY), &oMonitor);
	CDuiRect rcWork = oMonitor.rcWork;
	rcWork.Offset(-oMonitor.rcMonitor.left, -oMonitor.rcMonitor.top);

	LPMINMAXINFO lpMMI = (LPMINMAXINFO) lParam;
	lpMMI->ptMaxPosition.x	= rcWork.left;
	lpMMI->ptMaxPosition.y	= rcWork.top;
	lpMMI->ptMaxSize.x		= rcWork.right;
	lpMMI->ptMaxSize.y		= rcWork.bottom;

	bHandled = FALSE;
	return 0;
}

LRESULT CDuiMainDlg::OnDestroy(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	::PostQuitMessage(0L);

	bHandled = FALSE;
	return 0;
}

LRESULT CDuiMainDlg::OnSysCommand(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	// 有时会在收到WM_NCDESTROY后收到wParam为SC_CLOSE的WM_SYSCOMMAND
	if( wParam == SC_CLOSE ) {
		::PostQuitMessage(0L);
		bHandled = TRUE;
		return 0;
	}

	BOOL bZoomed = ::IsZoomed(*this);
	LRESULT lRes = CWindowWnd::HandleMessage(uMsg, wParam, lParam);
	if( ::IsZoomed(*this) != bZoomed ) {
		if( !bZoomed ) {
			m_pMaxBtn->SetVisible(false);
			m_pResizeBtn->SetVisible(true);
		}
		else {
			m_pMaxBtn->SetVisible(true);
			m_pResizeBtn->SetVisible(false);
		}
	}

	return lRes;
}

LRESULT CDuiMainDlg::OnNcHitTest(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	POINT pt; pt.x = GET_X_LPARAM(lParam); pt.y = GET_Y_LPARAM(lParam);
	::ScreenToClient(*this, &pt);

	RECT rcClient;
	::GetClientRect(*this, &rcClient);

	RECT rcCaption = m_pm.GetCaptionRect();
	if( pt.x >= rcClient.left + rcCaption.left && pt.x < rcClient.right - rcCaption.right \
		&& pt.y >= rcCaption.top && pt.y < rcCaption.bottom ) {
			CControlUI* pControl = static_cast<CControlUI*>(m_pm.FindControl(pt));
			if( pControl && _tcscmp(pControl->GetClass(), _T("ButtonUI")) != 0 && 
				_tcscmp(pControl->GetClass(), _T("OptionUI")) != 0 &&
				_tcscmp(pControl->GetClass(), _T("TextUI")) != 0 )
				return HTCAPTION;
	}

	return HTCLIENT;
}

LRESULT CDuiMainDlg::OnNcActivate(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled)
{
	if( ::IsIconic(*this) ) bHandled = FALSE;
	return (wParam == 0) ? TRUE : FALSE;
}

LRESULT CDuiMainDlg::HandleMessage(UINT uMsg, WPARAM wParam, LPARAM lParam)
{
	LRESULT lRes = 0;
	BOOL bHandled = TRUE;
	switch( uMsg ) {
	case WM_CREATE:        lRes = OnCreate(uMsg, wParam, lParam, bHandled); break;
	case WM_SIZE:          lRes = OnSize(uMsg, wParam, lParam, bHandled); break;
	case WM_NCACTIVATE:    lRes = OnNcActivate(uMsg, wParam, lParam, bHandled); break;
	case WM_GETMINMAXINFO: lRes = OnGetMinMaxInfo(uMsg, wParam, lParam, bHandled); break;
	case WM_NCHITTEST:     lRes = OnNcHitTest(uMsg, wParam, lParam, bHandled); break;
	case WM_DESTROY:       lRes = OnDestroy(uMsg, wParam, lParam, bHandled); break;
	case WM_SYSCOMMAND:    lRes = OnSysCommand(uMsg, wParam, lParam, bHandled); break;
	default:
		bHandled = FALSE;
	}
	//TRACE("HandleMessage %d %d\n",uMsg,  wParam);
	if( bHandled ) return lRes;
	if( m_pm.MessageHandler(uMsg, wParam, lParam, lRes) ) return lRes;
	return CWindowWnd::HandleMessage(uMsg, wParam, lParam);
}

BOOL CDuiMainDlg::IsMax()
{
	return ::IsZoomed(*this);
}