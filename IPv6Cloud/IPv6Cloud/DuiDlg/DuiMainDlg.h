#pragma once
#include "DuiLoginDlg.h"
class CDuiMainDlg:public CWindowWnd,INotifyUI
{
public:
	CDuiMainDlg();
	~CDuiMainDlg();
	LPCTSTR GetWindowClassName() const { return _T("主界面"); };
	UINT GetClassStyle() const { return CS_DBLCLKS; };
	LRESULT OnCreate(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnSize(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnGetMinMaxInfo(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnNcHitTest(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnDestroy(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnSysCommand(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT OnNcActivate(UINT uMsg, WPARAM wParam, LPARAM lParam, BOOL& bHandled);
	LRESULT HandleMessage(UINT uMsg, WPARAM wParam, LPARAM lParam);
	void Notify(TNotifyUI& msg);
public:
	CPaintManagerUI m_pm;
	void * m_pParent;

	CButtonUI* m_pCloseBtn;
	CButtonUI* m_pMaxBtn;
	CButtonUI* m_pResizeBtn;
	CButtonUI* m_pMinBtn;
	
	/*<!--业务受理-->*/
	CButtonUI * m_btnMainCount; //当前订单总数：
	CButtonUI * m_btnMainSpe; //特殊订单
	CLabelUI * m_labMainUn; //未配送订单
	CButtonUI * m_btnUserInfo;
	CButtonUI * m_btnMainInfo; //底部商家信息
	
	CButtonUI * m_btnPrintOff;
	CButtonUI * m_btnPrintOn;
	
	CButtonUI * m_btnMainSelAll;
	CButtonUI * m_btnMainFresh;
	CButtonUI * m_btnMainPrePage;
	CButtonUI * m_btnMainNextPage;
	CLabelUI *  m_labMainCurPage;
	CLabelUI *  m_labMainTotalPage;
	CEditUI*    m_editMainGotoPage;
	CButtonUI * m_btnMainGotoPage;
	
	CLabelUI * m_labMainConnectStatus; //labMainConnectStatus
	CButtonUI* m_pMainSetStatusBtn; //ButtonMainSetStatus

	CListUI*   m_listMain;
	void AddToMainList(int nIndex, ST_ORDER stOrder);

	void Search();

	BOOL IsMax();

	/*<!--软件配置-->*/
	CEditUI*   m_editLocalIpv6;
	CEditUI*   m_editServerIpv6;
	CEditUI*   m_editSaveFilePath;
	CButtonUI* m_btnPathBrowser;
	CButtonUI* m_btnOpenPath;
	
	CListUI*   m_listBlackPrint;
	CListUI*   m_listPrint;
	CListUI*   m_listColorPrint;

	CButtonUI* m_btnBlackLeft;
	CButtonUI * m_btnBlackRight;
	CButtonUI* m_btnColorLeft;
	CButtonUI * m_btnColorRight;
	
	CButtonUI* m_btnSetOK;
	CButtonUI * m_btnSetCancle;

	void UpdatePrint(CStringArray &strPrintList);
	void AddBlackPrint();
	void AddBlackPrint(CString strName);
	void DeleteBlackPrint();
	void AddColorPrint();
	void AddColorPrint(CString strName);
	void DeleteColorPrint();
	void SetSavePath(CString strPath);
	void SaveCfg();

	/*<!--历史订单-->*/
	CListUI*   m_listHistory;
	void AddToHistoryList(int nIndex, ST_ORDER stOrder);
	CButtonUI* m_btnPrint;
	CButtonUI * m_btnExport;
	
	CButtonUI * m_btnHisSelAll;
	CButtonUI * m_btnHisPrePage;
	CButtonUI * m_btnHisNextPage;
	CLabelUI *  m_labHisCurPage;
	CLabelUI *  m_labHisTotalPage;
	CEditUI*    m_editHisGotoPage;
	CButtonUI * m_btnHisGotoPage;
	/*<!--结算中心-->*/
	CButtonUI *  m_labAccount1;
	CButtonUI *  btnColorCount;
	CButtonUI *  btnSingleCount;
	CButtonUI *  m_btnOrderCount;
	CButtonUI *  btnPaperCount;
	CButtonUI *  btnBlackCount;
	CButtonUI *  btnDoubleCount;
	CButtonUI *  m_btnAccountAddr;

	/*<!--搜索-->*/
	CLabelUI *  m_labSearch;
	CEditUI*    m_editSearch;
	CButtonUI * m_btnSearch;
	CButtonUI * m_btnHisFresh;

	/*表头*/
	CListHeaderItemUI * m_headMainNormal;
	CListHeaderItemUI * m_headMainDouble;
	CListHeaderItemUI * m_headMainBlack;
	CListHeaderItemUI * m_headHisNormal;
	CListHeaderItemUI * m_headHisDouble;
	CListHeaderItemUI * m_headHisBlack;

	COptionUI * m_optMain;
	COptionUI * m_optSysSet;
	COptionUI * m_optHistory;
	COptionUI * m_optAccount;

};

/*
<VerticalLayout enabled="true" width="57">
								<Button name="ButtonHisPrePage" float="true" pos="0,4,0,0" enabled="true" width="57" height="21" textcolor="#FF000000" disabledtextcolor="#FFA7A6AA" align="center" normalimage="MainDlg/pre_page_n.png" hotimage="MainDlg/pre_page_h.png" />
							</VerticalLayout>
							<VerticalLayout enabled="true" width="10" />
							<VerticalLayout enabled="true" width="57">
								<Button name="ButtonHisNextPage" float="true" pos="0,4,0,0" enabled="true" width="57" height="21" textcolor="#FF000000" disabledtextcolor="#FFA7A6AA" align="center" normalimage="MainDlg/next_page_n.png" hotimage="MainDlg/next_page_h.png" />
							</VerticalLayout>
							<VerticalLayout enabled="true" width="10" />
							<VerticalLayout enabled="true" width="60">
								<Label  name="labHisCurPage" text="1" float="true" pos="0,4,0,0" enabled="true" width="20" height="22" textcolor="#002CBAED" disabledtextcolor="#FFA7A6AA" font="1" align="center" />
								<Label text="/" float="true" pos="20,4,0,0" enabled="true" width="10" height="22" textcolor="#FF000000" disabledtextcolor="#FFA7A6AA" font="1" align="center" />
								<Label  name="labHisTotalPage" text="100" float="true" pos="30,4,0,0" enabled="true" width="30" height="22" textcolor="#FF000000" disabledtextcolor="#FFA7A6AA" font="1" align="center" />
							</VerticalLayout>
							<VerticalLayout enabled="true" width="50">
								<Label text="跳转到：" float="true" pos="0,4,0,0" enabled="true" width="50" height="22" textcolor="#FF000000" disabledtextcolor="#FFA7A6AA" font="0" align="center" />
							</VerticalLayout>
							<VerticalLayout enabled="true" width="40" height="400">
								<Edit name="editHisGotoPage" bordersize="1" enabled="true" width="36" height="22" padding="2,4,2,0" bkcolor="#FFFFFFFF" bordercolor="#006DCFF3" textpadding="4,3,4,3" textcolor="#FF000000" disabledtextcolor="#FFA7A6AA" align="center" />
							</VerticalLayout>
							<VerticalLayout enabled="true" width="5" />
							<VerticalLayout enabled="true" width="28" height="400">
								<Button name="ButtonHisGotoPage"
*/