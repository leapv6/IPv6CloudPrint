
// IPv6CloudSub.cpp : Main主实现类，无关紧要的东西及系统相关东西，写在这里，以免应用程序逻辑阅读
//

#include "stdafx.h"
#include "IPv6Cloud.h"
#include "IPv6CloudDlg.h"
#include "afxdialogex.h"
#include "PubHead.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

/********************************** 线程函数 **************************************/
void CIPv6CloudDlg::FreeMemSvc(void * pParam)
{
	CIPv6CloudDlg *pThis = (CIPv6CloudDlg *)pParam;
	
	while (pThis->m_bWork)
	{
		pThis->ToMemFree();
		if (pThis->m_nFreeMemTime < 60)
		{
			pThis->m_nFreeMemTime = 60;
		}
		
		Sleep(pThis->m_nFreeMemTime * 1000);
	}
}

void CIPv6CloudDlg::DataArrivedCB(char *data, int length, DWORD userdata)
{
	TRACE("recv len:%d, date:%s\n", length, data);

	CIPv6CloudDlg *pThis = (CIPv6CloudDlg *)userdata;
	if(strstr(data, "offline:1") != NULL)
	{
		pThis->ShowTrayTips("系统提示", "该账号在其它地方登录！");

		CTime tNow = CTime::GetCurrentTime();
		char szMsg[256] = {0};
		sprintf(szMsg, "你的账号于%d点%d分在另一台机器上登录。如非本人操作，则密码可能已泄露，建议前往 http://www.v6cp.com 修改密码!\n是否重新登录？", tNow.GetHour(), tNow.GetMinute());

		if (AfxMessageBox(szMsg, MB_YESNO) != IDYES)
		{
			pThis->SetAutoLoginStatus(1);
			pThis->Restart();
		}
		else
		{
			pThis->m_LoginDlg.ForcedLogin();
			pThis->m_LoginDlg.OnOKByDui(CGlobal::g_UserName, CGlobal::g_UserPwd, CGlobal::g_Remember, CGlobal::g_AutoLogin, CGlobal::g_ServerAddr, true);
		}
	}
	else
	{
		pThis->ShowTrayTips("系统提示", "收到新的订单，等待列表获取！");
		pThis->GetAndRefresh(0);
	}
}

void CIPv6CloudDlg::StatusChangeCB(char *data, int length, DWORD userdata)
{
}

extern BOOL SetSystemDefaultPrinter(LPTSTR pPrinterName);
extern bool StartPrint(char * szFilePath, int nCount);
void CIPv6CloudDlg::PrintHisSvc(void * pParam)
{
	CIPv6CloudDlg *pThis = (CIPv6CloudDlg *)pParam;
	try
	{
		CString strPrintName = "";
		int nCount = pThis->m_DuiMainDlg.m_listBlackPrint->GetCount();
		for(int i=0; i<nCount; i++)
		{
			CControlUI* pChildCtrl = pThis->m_DuiMainDlg.m_listBlackPrint->GetItemAt(i);
			if(pChildCtrl != NULL)
			{
				CControlUI *labName =pThis->m_DuiMainDlg.m_pm.FindSubControlByName(pChildCtrl, _T("labName"));
				if(labName)
				{
					CString strName = labName->GetText();
					if(strName.Find("[不可用]") < 0)
					{
						strPrintName = strName;
						break;
					}
				}
			}
		}

		if(strPrintName == "")
		{
			pThis->ShowTrayTips("错误提示", "打印历史订单失败：请先配置一台打印机！");
			return;
		}

		CString strCache = "";
		strCache.Format("%s%s\\%d.xlsx", CGlobal::g_ExePath, DEFAULT_CACHE_FILE_PATH, GetTickCount());
		CString strFileName = pThis->ExportHis(strCache, 0);
	
		if(strFileName == "")
		{
			pThis->ShowTrayTips("错误提示", "准备打印数据时失败！");
		}

		if(SetSystemDefaultPrinter(strPrintName.GetBuffer(0)))
		{
			char szMsg[1024] = {0};
			if(StartPrint(strFileName.GetBuffer(0),1))
			{
				pThis->ShowTrayTips("打印成功！", "");
			}
			else
			{
				pThis->ShowTrayTips("打印文件失败！", "");
			}
		}
		else
		{
			pThis->ShowTrayTips("错误提示", "打印历史订单时，设置默认打印机失败！");
		}
	}
	catch(...)
	{
		pThis->ShowTrayTips("错误提示", "打印历史订单异常！");
	}
}

void CIPv6CloudDlg::GetHisOrderListSvc(void * pParam)
{
}

void CIPv6CloudDlg::AutoPrintSvc(void * pParam)
{
	CIPv6CloudDlg *pThis = (CIPv6CloudDlg *)pParam;

	pThis->SetLoginStatus(1);
	
	//获得数据
	pThis->m_UnOrderListLock.Lock();
	pThis->m_UnOrderList.clear();
	pThis->m_UnOrderListLock.UnLock();
	pThis->GetUnOrderList(0);
	if(pThis->m_nTotalUnOrder > 0)
	{
		int nPageCount = pThis->m_nTotalUnOrder / MAIN_ORDER_LIST_PAGE_NUM;
		if(pThis->m_nTotalUnOrder % MAIN_ORDER_LIST_PAGE_NUM != 0)
		{
			nPageCount ++;
		}
		for(int i=1; i<nPageCount; i++)
		{
			pThis->GetUnOrderList(i);
		}
	}
	pThis->RefreshUnOrderList();

	pThis->m_HisOrderListLock.Lock();
	pThis->m_HisOrderList.clear();
	pThis->m_HisOrderListLock.UnLock();
	pThis->GetHisOrderList(0);
	if(pThis->m_nHisTotalOrder > 0)
	{
		int nPageCount = pThis->m_nHisTotalOrder / HIS_ORDER_LIST_PAGE_NUM;
		if(pThis->m_nHisTotalOrder % HIS_ORDER_LIST_PAGE_NUM != 0)
		{
			nPageCount ++;
		}
		for(int i=1; i<nPageCount; i++)
		{
			pThis->GetHisOrderList(i);
		}
	}
	pThis->RefreshHisOrderList();

	pThis->GetAccount();

	//自动打印
	bool bFirst = true;
	int nIndex = 1;

	//开启后，获得未完成订单
	CString strUnList = "";
	while (pThis->m_bWork)
	{
		pThis->SetLoginStatus(1);

		pThis->m_PrintOrderListLock.Lock();
		pThis->m_PrintOrderList.clear();

		pThis->m_UnOrderListLock.Lock();
		int nSize = pThis->m_UnOrderList.size();
		for(int i=0; i<nSize; i++)
		{
			if(pThis->m_UnOrderList[i].printStatus == 0 && pThis->m_UnOrderList[i].specialOrder == 0)
			{
				pThis->m_PrintOrderList.push_back(pThis->m_UnOrderList[i]);
			}
		}
		pThis->m_UnOrderListLock.UnLock();

		pThis->m_HisOrderListLock.Lock();
		nSize = pThis->m_HisOrderList.size();
		for(int i=0; i<nSize; i++)
		{
			if(pThis->m_HisOrderList[i].printStatus == 0 && pThis->m_HisOrderList[i].specialOrder == 0)
			{
				pThis->m_PrintOrderList.push_back(pThis->m_HisOrderList[i]);
			}
		}
		pThis->m_HisOrderListLock.UnLock();
		pThis->m_PrintOrderListLock.UnLock();

		pThis->m_PrintOrderListLock.Lock();
		nSize = pThis->m_PrintOrderList.size();
		if(nSize > 0)
		{
			if(bFirst)
			{
				pThis->SendMessage(WM_AUTO_PRINT_MSG, NULL, NULL);
				bFirst = false;
			}

			for(int i=0; i<nSize; i++)
			{
				CString strTmp = "";
				strTmp.Format("[%s]", pThis->m_PrintOrderList[i].orderId);
				if(!pThis->m_bAutoPrint)
				{
					if(strUnList.Find(strTmp)<0)
					{
						strUnList += strTmp;
					}
				}

				if(pThis->m_bAutoPrint)
				{
					if(strUnList.Find(strTmp)<0)
					{
						pThis->RPrint(pThis->m_PrintOrderList[i]);
						Sleep(10 * 1000);
					}
				}
			}
		}
		else
		{
			bFirst = false;
		}
		pThis->m_PrintOrderListLock.UnLock();

		//计算结算中心数据
		//if(nIndex % 6 == 0)
		//{
		//	pThis->GetAndRefresh(1);
		//	nIndex = 0;
		//}
		pThis->GetAccount();
		nIndex ++;
		Sleep(10*1000);
	}
}
