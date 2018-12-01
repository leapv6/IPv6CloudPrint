// Ini.cpp: implementation of the CIni class.
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "DS_Ini.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////
CIni::CIni()
{

}

CIni::CIni(CString strPath)
{
	m_strFilePath=strPath;
}

CIni::~CIni()
{

}

CString CIni::ReadText(CString strSection, CString strSectionKey)
{
	CString strValue="";
	char inBuf[80];
	GetPrivateProfileString (strSection,strSectionKey, NULL, inBuf, 80, m_strFilePath); 
	strValue=inBuf;
	return strValue;
}

int CIni::ReadInt(CString strSection, CString strSectionKey)
{
	
	int num=0;
	char inBuf[80];
	GetPrivateProfileString (strSection,strSectionKey, NULL, inBuf, 80, m_strFilePath); 
	num=atoi(inBuf);
	return num;

}

void CIni::WriteText(CString strSection, CString strSectionKey, CString strValue)
{
	WritePrivateProfileString (strSection,strSectionKey,  
        strValue, m_strFilePath); 
}

void CIni::WriteInt(CString strSection, CString strSectionKey, int nValue)
{
	CString strValue;
	strValue.Format("%d",nValue);
	WritePrivateProfileString (strSection,strSectionKey,  
        strValue, m_strFilePath); 

}

void CIni::DeleteSection(CString strSectionName)
{
	WritePrivateProfileString(strSectionName, 0, "", m_strFilePath);
}

void CIni::ReadText(CString strSection, CString strSectionKey, char* chText)
{
	char inBuf[80];
	GetPrivateProfileString (strSection,strSectionKey, NULL, inBuf, 80, m_strFilePath); 
	strcpy(chText,inBuf);
}
