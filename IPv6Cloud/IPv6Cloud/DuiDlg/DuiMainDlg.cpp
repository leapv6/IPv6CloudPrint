// LoginDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "DuiMainDlg.h"
#include "../IPv6CloudDlg.h"

void CDuiMainDlg::AddToMainList(int nIndex, ST_ORDER stOrder)
{
	if(m_listMain == NULL)
	{
		return;
	}

	CString strAtt = "";

	CListContainerElementUI *lstCtEleNode = new CListContainerElementUI;
	lstCtEleNode->ApplyAttributeList(_T("height=\"25\""));
	 
	CHorizontalLayoutUI * hLayUI = new CHorizontalLayoutUI;

	//序号
	CVerticalLayoutUI * vLayUI1 = new CVerticalLayoutUI;
	vLayUI1->ApplyAttributeList(_T("float=\"false\" enabled=\"true\" maxwidth=\"40\""));

	CHorizontalLayoutUI * hLayUI1_1 = new CHorizontalLayoutUI;

	CVerticalLayoutUI * vLayUI1_1 = new CVerticalLayoutUI;
	vLayUI1_1->ApplyAttributeList(_T("maxwidth=\"18\""));
	CButtonUI *btnSeled = new CButtonUI;
	strAtt.Format("name=\"btnSeled\" enabled=\"true\" visible=\"false\" normalimage=\"MainDlg/checked.png\" width=\"15\" height=\"15\" padding=\"0, 4,0,0\"");
	btnSeled->ApplyAttributeList(strAtt.GetBuffer(0));
	CButtonUI *btnUnSel = new CButtonUI;
	strAtt.Format("name=\"btnUnSel\" enabled=\"true\" visible=\"true\" normalimage=\"MainDlg/unchecked.png\" width=\"15\" height=\"15\" padding=\"0, 4,0,0\" ");
	btnUnSel->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI1_1->Add(btnSeled);
	vLayUI1_1->Add(btnUnSel);
	
	CVerticalLayoutUI * vLayUI1_2 = new CVerticalLayoutUI;
	vLayUI1_2->ApplyAttributeList(_T("maxwidth=\"22\""));
	CLabelUI *lab1 = new CLabelUI;
	strAtt.Format("text=\"%02d\" enabled=\"true\" maxwidth=\"22\" textcolor=\"#FF000000\" align=\"left\" padding=\"0,4,0,0\"", nIndex);
	lab1->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI1_2->Add(lab1);

	hLayUI1_1->Add(vLayUI1_1);
	hLayUI1_1->Add(vLayUI1_2);

	vLayUI1->Add(hLayUI1_1);
	
	//订单编号
	CVerticalLayoutUI * vLayUI2 = new CVerticalLayoutUI;
	vLayUI2->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"35\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab2 = new CLabelUI;
	strAtt.Format("text=\"%s\" name=\"labOrderId\" tooltip=\"%s\" enabled=\"true\" maxwidth=\"35\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.orderId, stOrder.orderId);
	lab2->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI2->Add(lab2);

	//下单时间
	char szMakeTime[256] = {0};
	strcpy(szMakeTime, stOrder.makeTime);
	//获得exe文家夹路径
	strrchr( szMakeTime, ':')[0]= '\0';
	CVerticalLayoutUI * vLayUI3 = new CVerticalLayoutUI;
	vLayUI3->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"120\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab3 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" minwidth=\"120\" tooltip=\"%s\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\" endellipsis=\"true\"", stOrder.docName, stOrder.docName);
	lab3->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI3->Add(lab3);

	//用户名&电话
	CVerticalLayoutUI * vLayUI4 = new CVerticalLayoutUI;
	vLayUI4->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"110\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab4 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" tooltip=\"%s\" minwidth=\"110\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.userName, stOrder.userName);
	lab4->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI4->Add(lab4);

	//普通
	CVerticalLayoutUI * vLayUI5 = new CVerticalLayoutUI;
	vLayUI5->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"40\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab5 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"40\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.specialOrder > 0)?"特殊":"普通");
	lab5->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI5->Add(lab5);

	//单
	CVerticalLayoutUI * vLayUI6 = new CVerticalLayoutUI;
	vLayUI6->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"40\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab6 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"40\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.doublePrint > 0)?"双面":"单面");
	lab6->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI6->Add(lab6);

	//黑白
	CVerticalLayoutUI * vLayUI7 = new CVerticalLayoutUI;
	vLayUI7->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"40\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab7 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"40\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.colorPrint > 0)?"彩色":"黑白");
	lab7->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI7->Add(lab7);

	//份数
	CVerticalLayoutUI * vLayUI8 = new CVerticalLayoutUI;
	vLayUI8->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"40\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab8 = new CLabelUI;
	strAtt.Format("text=\"%d\" enabled=\"true\" maxwidth=\"40\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.printCopis);
	lab8->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI8->Add(lab8);

	//价格
	CVerticalLayoutUI * vLayUI9 = new CVerticalLayoutUI;
	vLayUI9->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"40\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab9 = new CLabelUI;
	strAtt.Format("text=\"%0.2f\" enabled=\"true\" maxwidth=\"40\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.price);
	lab9->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI9->Add(lab9);

	//订单状态
	CVerticalLayoutUI * vLayUI10 = new CVerticalLayoutUI;
	vLayUI10->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"60\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab10 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"60\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.printStatus > 0)?"完成":"等待");
	lab10->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI10->Add(lab10);

	//支付状态
	CVerticalLayoutUI * vLayUI11 = new CVerticalLayoutUI;
	vLayUI11->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"60\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab11 = new CLabelUI;
	if(stOrder.payStatus > 0)
	{
		strAtt.Format("text=\"已支付\" enabled=\"true\" maxwidth=\"60\" textcolor=\"#FF00ff00\" align=\"center\" padding=\"0,4,0,0\"");
	}
	else
	{
		strAtt.Format("text=\"未支付\" enabled=\"true\" maxwidth=\"60\" textcolor=\"#FFff0000\" align=\"center\" padding=\"0,4,0,0\"");
	}
	lab11->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI11->Add(lab11);

	//备注
	CVerticalLayoutUI * vLayUI12 = new CVerticalLayoutUI;
	vLayUI12->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"65\""));
	//CLabelUI *lab12 = new CLabelUI;
	//strAtt.Format("text=\"%s\" enabled=\"true\" minwidth=\"65\" textcolor=\"#FF08AEEA\" align=\"left\" padding=\"0,4,0,0\"", stOrder.message);
	//lab12->ApplyAttributeList(strAtt.GetBuffer(0));	

	CString strSendStatus = "";
	if(stOrder.sendStatus == 1)
	{
		strSendStatus = "已配送";
	}
	else if(stOrder.sendStatus == 0)
	{
		strSendStatus = "未配送";
	}
	else
	{
		strSendStatus = "自领取";
	}

	CString strDetail = "";
	strDetail.Format("订单编号：%s\n下单时间：%s\n用户名：%s\n电话：%s\n打印状态：%s\nn支付状态：%s\n配送方式：%s\n配送状态：%s\n备注：%s\n", 
		stOrder.orderId, stOrder.makeTime, stOrder.userName, stOrder.userPhone, (stOrder.printStatus > 0)?"完成":"等待", (stOrder.payStatus > 0)?"已支付":"未支付", (stOrder.sendType > 0)?"配送":"自取", strSendStatus.GetBuffer(0), stOrder.message);
	CButtonUI *btnDetail = new CButtonUI;
	strAtt.Format("name=\"btnDetail\" enabled=\"true\" tooltip=\"%s\" textcolor=\"#FF016EC7\" align=\"center\" text=\"查看详情\" padding=\"0,4,0,0\"", strDetail.GetBuffer(0));
	btnDetail->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI12->Add(btnDetail);

	//<Button name="ButtonForget" text="{a}忘记密码？{/a}"  float="true" pos="533,489,0,0" enabled="true" width="70" height="17" textpadding="2,0,2,0" textcolor="#002AA1F6" disabledtextcolor="#FFA7A6AA" font="0" showhtml="true"/>
	
	hLayUI->Add(vLayUI1);
	hLayUI->Add(vLayUI2);
	hLayUI->Add(vLayUI3);
	hLayUI->Add(vLayUI4);
	hLayUI->Add(vLayUI5);
	hLayUI->Add(vLayUI6);
	hLayUI->Add(vLayUI7);
	hLayUI->Add(vLayUI8);
	hLayUI->Add(vLayUI9);
	hLayUI->Add(vLayUI10);
	hLayUI->Add(vLayUI11);
	hLayUI->Add(vLayUI12);
	lstCtEleNode->Add(hLayUI);
	m_listMain->Add(lstCtEleNode);
}

void CDuiMainDlg::AddToHistoryList(int nIndex, ST_ORDER stOrder)
{
	if(m_listHistory == NULL)
	{
		return;
	}

	CString strAtt = "";
	
									
	CListContainerElementUI *lstCtEleNode = new CListContainerElementUI;
	lstCtEleNode->ApplyAttributeList(_T("height=\"25\""));

	CHorizontalLayoutUI * hLayUI = new CHorizontalLayoutUI;
	//序号
	//CVerticalLayoutUI * vLayUI1 = new CVerticalLayoutUI;
	//vLayUI1->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"50\""));
	//CLabelUI *lab1 = new CLabelUI;
	//strAtt.Format("text=\"%d\" enabled=\"true\" pos=\"20,0,0,0\" maxwidth=\"30\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", nIndex);
	//lab1->ApplyAttributeList(strAtt.GetBuffer(0));
	//vLayUI1->Add(lab1);
	CVerticalLayoutUI * vLayUI1 = new CVerticalLayoutUI;
	vLayUI1->ApplyAttributeList(_T("float=\"false\" enabled=\"true\" maxwidth=\"40\""));

	CHorizontalLayoutUI * hLayUI1_1 = new CHorizontalLayoutUI;

	CVerticalLayoutUI * vLayUI1_1 = new CVerticalLayoutUI;
	vLayUI1_1->ApplyAttributeList(_T("maxwidth=\"20\""));
	CButtonUI *btnSeled = new CButtonUI;
	strAtt.Format("name=\"btnSeled\" enabled=\"true\" visible=\"false\" normalimage=\"MainDlg/checked.png\" width=\"15\" height=\"15\" padding=\"5, 4,0,0\"");
	btnSeled->ApplyAttributeList(strAtt.GetBuffer(0));
	CButtonUI *btnUnSel = new CButtonUI;
	strAtt.Format("name=\"btnUnSel\" enabled=\"true\" visible=\"true\" normalimage=\"MainDlg/unchecked.png\" width=\"15\" height=\"15\" padding=\"5, 4,0,0\" ");
	btnUnSel->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI1_1->Add(btnSeled);
	vLayUI1_1->Add(btnUnSel);
	
	CVerticalLayoutUI * vLayUI1_2 = new CVerticalLayoutUI;
	vLayUI1_2->ApplyAttributeList(_T("maxwidth=\"25\""));
	CLabelUI *lab1 = new CLabelUI;
	strAtt.Format("text=\"%02d\" enabled=\"true\" maxwidth=\"25\" textcolor=\"#FF000000\" align=\"left\" padding=\"0,4,0,0\"", nIndex);
	lab1->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI1_2->Add(lab1);

	hLayUI1_1->Add(vLayUI1_1);
	hLayUI1_1->Add(vLayUI1_2);

	vLayUI1->Add(hLayUI1_1);
	
	//订单编号
	CVerticalLayoutUI * vLayUI2 = new CVerticalLayoutUI;
	vLayUI2->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"35\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab2 = new CLabelUI;
	strAtt.Format("text=\"%s\" name=\"labOrderId\" tooltip=\"%s\" enabled=\"true\" maxwidth=\"35\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.orderId, stOrder.orderId);
	lab2->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI2->Add(lab2);

	//下单时间
	char szMakeTime[256] = {0};
	strcpy(szMakeTime, stOrder.makeTime);
	//获得exe文家夹路径
	strrchr( szMakeTime, ':')[0]= '\0';
	CVerticalLayoutUI * vLayUI3 = new CVerticalLayoutUI;
	vLayUI3->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"120\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab3 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" tooltip=\"%s\" minwidth=\"120\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\" endellipsis=\"true\"", stOrder.docName, stOrder.docName);
	lab3->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI3->Add(lab3);

	//用户名&电话
	CVerticalLayoutUI * vLayUI4 = new CVerticalLayoutUI;
	vLayUI4->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"110\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab4 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" tooltip=\"%s\" minwidth=\"110\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.userName, stOrder.userName);
	lab4->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI4->Add(lab4);

	//普通
	CVerticalLayoutUI * vLayUI5 = new CVerticalLayoutUI;
	vLayUI5->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"50\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab5 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"50\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.specialOrder > 0)?"特殊":"普通");
	lab5->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI5->Add(lab5);

	//单
	CVerticalLayoutUI * vLayUI6 = new CVerticalLayoutUI;
	vLayUI6->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"50\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab6 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"50\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.doublePrint > 0)?"双面":"单面");
	lab6->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI6->Add(lab6);

	//黑白
	CVerticalLayoutUI * vLayUI7 = new CVerticalLayoutUI;
	vLayUI7->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"50\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab7 = new CLabelUI;
	strAtt.Format("text=\"%s\" enabled=\"true\" maxwidth=\"50\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", (stOrder.colorPrint > 0)?"彩色":"黑白");
	lab7->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI7->Add(lab7);

	//打印份数
	CVerticalLayoutUI * vLayUI8 = new CVerticalLayoutUI;
	vLayUI8->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"50\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab8 = new CLabelUI;
	strAtt.Format("text=\"%d\" enabled=\"true\" maxwidth=\"50\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.printCopis);
	lab8->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI8->Add(lab8);

	//价格
	CVerticalLayoutUI * vLayUI9 = new CVerticalLayoutUI;
	vLayUI9->ApplyAttributeList(_T("enabled=\"true\" maxwidth=\"50\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab9 = new CLabelUI;
	strAtt.Format("text=\"%0.2f\" enabled=\"true\" maxwidth=\"50\" textcolor=\"#FF000000\" align=\"center\" padding=\"0,4,0,0\"", stOrder.price);
	lab9->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI9->Add(lab9);

	//支付状态
	CVerticalLayoutUI * vLayUI11 = new CVerticalLayoutUI;
	vLayUI11->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"50\" bordersize=\"1\" bordercolor=\"#00808080\""));
	CLabelUI *lab11 = new CLabelUI;
	if(stOrder.payStatus > 0)
	{
		strAtt.Format("text=\"已支付\" enabled=\"true\" minwidth=\"50\" textcolor=\"#FF00ff00\" align=\"center\" padding=\"0,4,0,0\"");
	}
	else
	{
		strAtt.Format("text=\"未支付\" enabled=\"true\" minwidth=\"50\" textcolor=\"#FFff0000\" align=\"center\" padding=\"0,4,0,0\"");
	}
	lab11->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI11->Add(lab11);

	//备注
	CVerticalLayoutUI * vLayUI12 = new CVerticalLayoutUI;
	vLayUI12->ApplyAttributeList(_T("enabled=\"true\" minwidth=\"65\" "));
	//CLabelUI *lab12 = new CLabelUI;
	//strAtt.Format("text=\"%s\" enabled=\"true\" minwidth=\"65\" textcolor=\"#FF08AEEA\" align=\"left\" padding=\"0,4,0,0\"", stOrder.message);
	//lab12->ApplyAttributeList(strAtt.GetBuffer(0));
	CString strSendStatus = "";
	if(stOrder.sendStatus == 1)
	{
		strSendStatus = "已配送";
	}
	else if(stOrder.sendStatus == 0)
	{
		strSendStatus = "未配送";
	}
	else
	{
		strSendStatus = "自领取";
	}
	CString strDetail = "";
	strDetail.Format("订单编号：%s\n下单时间：%s\n用户名：%s\n电话：%s\n打印状态：%s\nn支付状态：%s\n配送方式：%s\n配送状态：%s\n备注：%s\n", 
		stOrder.orderId, stOrder.makeTime, stOrder.userName, stOrder.userPhone, (stOrder.printStatus > 0)?"完成":"等待", (stOrder.payStatus > 0)?"已支付":"未支付", (stOrder.sendType > 0)?"配送":"自取", strSendStatus.GetBuffer(0), stOrder.message);

	CButtonUI *btnDetail = new CButtonUI;
	strAtt.Format("name=\"btnDetail\" enabled=\"true\" tooltip=\"%s\" textcolor=\"#FF016EC7\" align=\"center\" text=\"查看详情\" padding=\"0,4,0,0\"", strDetail.GetBuffer(0));
	btnDetail->ApplyAttributeList(strAtt.GetBuffer(0));
	vLayUI12->Add(btnDetail);

	hLayUI->Add(vLayUI1);
	hLayUI->Add(vLayUI2);
	hLayUI->Add(vLayUI3);
	hLayUI->Add(vLayUI4);
	hLayUI->Add(vLayUI5);
	hLayUI->Add(vLayUI6);
	hLayUI->Add(vLayUI7);
	hLayUI->Add(vLayUI8);
	hLayUI->Add(vLayUI9);
	hLayUI->Add(vLayUI11);
	hLayUI->Add(vLayUI12);
	lstCtEleNode->Add(hLayUI);
	m_listHistory->Add(lstCtEleNode);
}

void CDuiMainDlg::UpdatePrint(CStringArray &strPrintList)
{
	int nSize = strPrintList.GetSize();
	for(int i=0; i<nSize; i++)
	{
		CListLabelElementUI *lstLabEleNode = new CListLabelElementUI;
		lstLabEleNode->ApplyAttributeList(_T("name=\"labName\" padding=\"10,0,10,0\""));
		lstLabEleNode->SetText(strPrintList[i].GetBuffer(0));
		m_listPrint->Add(lstLabEleNode);
	}

	int nColPriSize = CGlobal::g_ColorPrint.GetSize();
	for(int x=0; x<nColPriSize; x++)
	{
		bool bFind = false;
		for(int i=0; i<nSize; i++)
		{
			if(CGlobal::g_ColorPrint[x] == strPrintList[i])
			{
				bFind = true;
				break;
			}
		}

		CString strPriName = "";
		if(bFind)
		{
			strPriName = CGlobal::g_ColorPrint[x];
		}
		else
		{
			strPriName.Format("[不可用]%s", CGlobal::g_ColorPrint[x].GetBuffer(0));
		}
		
		AddColorPrint(strPriName);
	}

	int nBlaPriSize = CGlobal::g_BlackPrint.GetSize();
	for(int x=0; x<nBlaPriSize; x++)
	{
		bool bFind = false;
		for(int i=0; i<nSize; i++)
		{
			if(CGlobal::g_BlackPrint[x] == strPrintList[i])
			{
				bFind = true;
				break;
			}
		}

		CString strPriName = "";
		if(bFind)
		{
			strPriName = CGlobal::g_BlackPrint[x];
		}
		else
		{
			strPriName.Format("[不可用]%s", CGlobal::g_BlackPrint[x].GetBuffer(0));
		}

		AddBlackPrint(strPriName);
	}
}

void CDuiMainDlg::AddBlackPrint()
{
	int iIndex = m_listPrint->GetCurSel();
	if(iIndex < 0)
	{
		return;
	}
	CControlUI *pCtrlEle = m_listPrint->GetItemAt(iIndex);
	CString strName = pCtrlEle->GetText();

	AddBlackPrint(strName);
}

void CDuiMainDlg::AddBlackPrint(CString strName)
{
	if(strName == "")
	{
		return;
	}

	int nCount = m_listBlackPrint->GetCount();
	for(int i=0; i<nCount; i++)
	{
		CControlUI* pChildCtrl = m_listBlackPrint->GetItemAt(i);
		if(pChildCtrl != NULL)
		{
			CControlUI *labName =m_pm.FindSubControlByName(pChildCtrl, _T("labName"));
			if(labName)
			{
				CString strPriName = labName->GetText();
				if(strName == strPriName)
				{
					return;
				}
			}
		}
	}

	CListLabelElementUI *lstLabEleNode = new CListLabelElementUI;
	lstLabEleNode->ApplyAttributeList(_T("name=\"labName\" padding=\"10,0,10,0\""));
	lstLabEleNode->SetText(strName.GetBuffer(0));
	m_listBlackPrint->Add(lstLabEleNode);
}

void CDuiMainDlg::DeleteBlackPrint()
{
	int iIndex = m_listBlackPrint->GetCurSel();
	if(iIndex < 0)
	{
		return;
	}
	m_listBlackPrint->RemoveAt(iIndex);
}

void CDuiMainDlg::AddColorPrint()
{
	int iIndex = m_listPrint->GetCurSel();
	if(iIndex < 0)
	{
		return;
	}
	CControlUI *pCtrlEle = m_listPrint->GetItemAt(iIndex);
	CString strName = pCtrlEle->GetText();

	AddColorPrint(strName);
}

void CDuiMainDlg::AddColorPrint(CString strName)
{
	if(strName == "")
	{
		return;
	}

	int nCount = m_listColorPrint->GetCount();
	for(int i=0; i<nCount; i++)
	{
		CControlUI* pChildCtrl = m_listColorPrint->GetItemAt(i);
		if(pChildCtrl != NULL)
		{
			CControlUI *labName =m_pm.FindSubControlByName(pChildCtrl, _T("labName"));
			if(labName)
			{
				CString strPriName = labName->GetText();
				if(strName == strPriName)
				{
					return;
				}
			}
		}
	}

	CListLabelElementUI *lstLabEleNode = new CListLabelElementUI;
	lstLabEleNode->ApplyAttributeList(_T("name=\"labName\" padding=\"10,0,10,0\""));
	lstLabEleNode->SetText(strName.GetBuffer(0));
	m_listColorPrint->Add(lstLabEleNode);
}

void CDuiMainDlg::DeleteColorPrint()
{
	int iIndex = m_listColorPrint->GetCurSel();
	if(iIndex < 0)
	{
		return;
	}
	m_listColorPrint->RemoveAt(iIndex);
}

void CDuiMainDlg::SetSavePath(CString strPath)
{
	m_editSaveFilePath->SetText(strPath.GetBuffer(0));
}

void CDuiMainDlg::SaveCfg()
{
	CString strLocalIpv6 = m_editLocalIpv6->GetText();
	CString strServerIpv6 = m_editServerIpv6->GetText();
	CString strSavePath = m_editSaveFilePath->GetText();

	CString strColorPrint = "";
	int nCount = m_listColorPrint->GetCount();
	for(int i=0; i<nCount; i++)
	{
		CControlUI* pChildCtrl = m_listColorPrint->GetItemAt(i);
		if(pChildCtrl != NULL)
		{
			CControlUI *labName =m_pm.FindSubControlByName(pChildCtrl, _T("labName"));
			if(labName)
			{
				strColorPrint += labName->GetText();
				strColorPrint += "$";
			}
		}
	}
	strColorPrint.TrimRight("$");

	CString strBlackPrint = "";
	nCount = m_listBlackPrint->GetCount();
	for(int i=0; i<nCount; i++)
	{
		CControlUI* pChildCtrl = m_listBlackPrint->GetItemAt(i);
		if(pChildCtrl != NULL)
		{
			CControlUI *labName =m_pm.FindSubControlByName(pChildCtrl, _T("labName"));
			if(labName)
			{
				strBlackPrint += labName->GetText();
				strBlackPrint += "$";
			}
		}
	}
	strBlackPrint.TrimRight("$");
	
	strColorPrint.Replace("[不可用]", "");
	strBlackPrint.Replace("[不可用]", "");
	if(m_pParent != NULL)
	{
		((CIPv6CloudDlg *)m_pParent)->SaveCfg(strLocalIpv6, strServerIpv6, strSavePath, strColorPrint, strBlackPrint);
	}
}

void CDuiMainDlg::Search()
{
	CTabLayoutUI* pControl = static_cast<CTabLayoutUI*>(m_pm.FindControl(_T("tabMain")));
	int nIndex = pControl->GetCurSel();

	if(m_pParent != NULL)
	{
		CString strSearch = m_editSearch->GetText();
		((CIPv6CloudDlg *)m_pParent)->Search(nIndex, strSearch);
	}
}