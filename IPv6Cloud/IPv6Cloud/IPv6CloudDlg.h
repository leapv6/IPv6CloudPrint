
// IPv6CloudDlg.h : 头文件
//
#include "Comm\DS_Lock.h"
#include "Comm\TCPSocket.h"
#include "Comm\DS_Print.h"
#include "Comm\FileDownload.h"
#include "Comm\DS_Utils.h"
#include "Comm\Collector.h"

#include "DuiDlg\DuiLoginDlg.h"
#include "DuiDlg\DuiMainDlg.h"
#include "DuiDlg\WndShadow.h"

#include "LoginDlg.h"

#include <io.h>

#pragma once

// CIPv6CloudDlg 对话框
class CIPv6CloudDlg : public CDialogEx
{
	DECLARE_EASYSIZE
public:
	// 实现
	CTCPSocket m_TCP;
	// 未完成订单显示列表
	vector<ST_ORDER> m_UnOrderList;
	DSLock m_UnOrderListLock;
	int    m_nTotalUnOrder; //总订单数
	int    m_nSpecialUnOrder; //特殊订单数
	int    m_nNodeliveryUnOrder; //未配送订单数
	int    m_nCurrentPage; //当前页数
	// 历史订单显示列表
	vector<ST_ORDER> m_HisOrderList;
	DSLock m_HisOrderListLock;
	int    m_nHisTotalOrder; //总订单数
	int    m_nHisCurrentPage; //当前页数
	// Print
	vector<ST_ORDER> m_PrintOrderList;
	DSLock m_PrintOrderListLock;
	

	// 本地IPv6地址
	char m_szClientAddr[256];

	//媒体文件下载服务
	CFileDownload m_FileDownload;

	bool  m_bWork; //主线程是否工作
	int   m_nHanding; //正在处理的订单数
	// 全局配置文件信息
	CGlobal m_cGlobal;

	// 搜索手机号
	CString  m_strMainSearch;
	CString  m_strHisSearch;
	// 右键菜单标志
	int  m_nMainMenuFlag;
	int  m_nPrintMenuFlag;
	//搜索
	void Search(int nIndex, CString strSearch);
	int  m_nSelMainNormal;
	int  m_nSelMainDouble;
	int  m_nSelMainBlack;
	int  m_nSelHisNormal;
	int  m_nSelHisDouble;
	int  m_nSelHisBlack;

	//
	bool m_bAutoPrint;
public:
	// 右下角提示
	void ShowTrayTips(CString strTitle, CString strContent);
	// 内存清理
	void ToMemFree();
	// 最小化
	void MinWndByDui();
	// 最大化
	void MaxWndByDui();
	// 恢复
	void RestoreWndByDui();
	//打印
	bool Print(CString strFileName, ST_ORDER stOrder);
	void PrintHis();
	// 打开选择保存路径
	void OpenPath();
	void PathBrowser();
	// 获得未完成订单
	int GetUnOrderList(int nPage);
	void RefreshUnOrderList();
	// 获得未完成订单
	int GetHisOrderList(int nPage);
	void RefreshHisOrderList();
	void GetAndRefresh(int nFlag);
	// 获得文件下载地址
	CString GetDownloadFileUrl(ST_ORDER stOrder);
	// 获得客户端地址
	void GetClientAddr();
	// 保存软件配置
	int SaveCfg(CString strLocalIpv6, CString strServerIpv6, CString strSavePath, CString strColorPrint, CString strBlackPrint);
	// 导出列表
	CString ExportHis(CString strName = "", int nFlag = 1);
	// 点击首页设置按钮
	void OnSetStatusBtn();
	// 设置客户端处理订单状态
	bool SetOrderStatus(char *szOrderId, int nPayStatus, int nPrintStatus, int nSendStatus);
	// 设置订单状态
	void UpdateUnOrderListStatus(FileItem * pMFI, bool bStatus, bool bHanding);
	// 首页右键列表
	void  MainRightDown(int nFlag);
	// 打印机右键列表
	void  PrintRDown(int nFlag);
	// 获得当前所选择的列表项 订单编号
	void GetListOrderST(ST_ORDER &stOrder);
	CString GetListOrderVec();
	void PrePage(int nFlag);
	void NextPage(int nFlag);
	void GotoPage(int nFlag, int nPage);
	void Refresh(int nFlag);
	void SelAll(int nFlag);

	void RDown(ST_ORDER stOrder, bool bPath);
	void RPrint(ST_ORDER stOrder);
	void RSetstatus1(ST_ORDER stOrder);
	void RSetstatus2(ST_ORDER stOrder);

	// 设置客户端地址
	void SetClientAddr(CString strIPAddr);

	// 获得收益
	void GetAccount();

	//是否只显示特殊订单
	void SetSpeOrder(int nFlg);

	//列表标题栏菜单
	void NormalMenu(int nFlag);
	void DoubleMenu(int nFlag);
	void BlackMenu(int nFlag);

	//更新客户端在线状态
	void SetLoginStatus(int nStatus);

	int GetIPv6();
	
	void Restart();

	void SetAutoLoginStatus(int nStatus);
private:
	// 获得未完成订单线程
	static void PrintHisSvc(void * pParam);
	// 获得订单线程
	static void GetHisOrderListSvc(void * pParam);
	// 自动打印线程
	static void AutoPrintSvc(void * pParam);

	// 内存清理时间间隔
	int m_nFreeMemTime;

	// 内存清理线程
	static void FreeMemSvc(void * pParam);

	//数据回调
	static void DataArrivedCB(char *data, int length, DWORD userdata);

	//状态回调
	static void StatusChangeCB(char *data, int length, DWORD userdata);

	//Dui对话框
	CLoginDlg m_LoginDlg;
	CDuiMainDlg m_DuiMainDlg;
	CWndShadow m_WndShadow;

	//打印机列表
	CStringArray m_arrPrintList;
protected:
	HICON m_hIcon;

	/*  -----------------------------------------右下角托盘及菜单相关------------------------------------------->*/
	// 初始化托盘
	int InitTray();

	// 定义托盘菜单CMenu对象
	CMenu m_TrayMenu;
	
	// 定义首页CMenu对象
	CMenu m_MainMenu;
	CMenu m_MainListMenu;
	CMenu m_PrintRMenu;

	// 定义首页CMenu对象
	CMenu m_NormalMenu;
	CMenu m_DoubleMenu;
	CMenu m_BlackMenu;

	// 菜单左侧图标
	CBitmap m_TrayMainBmp;
	CBitmap m_TraySyssetBmp;
	CBitmap m_TrayExitBmp;
	CBitmap m_TrayAboutBmp;
	CBitmap m_TrayWebBmp;
	CBitmap m_TrayClearBmp;

	/* 托盘图标数据 */
	NOTIFYICONDATA m_NotifyIconData;

	/*<------------------------------------------右下角托盘及菜单相关----------------------------------------------*/

	// 生成的消息映射函数
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()

	// 构造
public:
	CIPv6CloudDlg(CWnd* pParent = NULL);	// 标准构造函数
	~CIPv6CloudDlg();	// 标准构造函数

	// 对话框数据
	enum { IDD = IDD_IPv6Cloud_DIALOG };

	virtual BOOL PreTranslateMessage(MSG* pMsg);
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV 支持

public:
	afx_msg void OnDestroy();
	virtual LRESULT WindowProc(UINT message, WPARAM wParam, LPARAM lParam);
	afx_msg void OnTrayMenuShow();
	afx_msg void OnTrayMenuWeb();
	afx_msg void OnTrayMenuMemfree();
	afx_msg void OnTrayMenuSysset();
	afx_msg void OnTrayMenuAbout();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnSizing(UINT fwSide, LPRECT pRect);
	afx_msg void OnMainDowns();
	afx_msg void OnMainPrints();
	afx_msg void OnMainSetstatus1();
	afx_msg void OnMainSetstatus2();
	afx_msg void OnMianRDown();
	afx_msg void OnMianRPrint();
	afx_msg void OnMainRSetstatus1();
	afx_msg void OnMainRSetstatus2();
	afx_msg void OnPrintRAddtoB();
	afx_msg void OnPrintRAddtoC();
	afx_msg void OnPrintRDelete();
	afx_msg void OnBlackAll();
	afx_msg void OnBlackBlack();
	afx_msg void OnBlackColor();
	afx_msg void OnDoubleAll();
	afx_msg void OnDoubleSingle();
	afx_msg void OnDoubleDouble();
	afx_msg void OnNormalAll();
	afx_msg void OnNormalNormal();
	afx_msg void OnNormalSpe();
};

// 用于应用程序“关于”菜单项的 CAboutDlg 对话框
class CAboutDlg : public CDialogEx
{
public:
	CAboutDlg();

	// 对话框数据
	enum { IDD = IDD_ABOUTBOX };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV 支持

	// 实现
protected:
	DECLARE_MESSAGE_MAP()
public:
};