// Ini.h: interface for the CIni class.
//
//////////////////////////////////////////////////////////////////////

#if !defined _INI_
#define _INI_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CIni  
{
public:
	void ReadText(CString strSection, CString strSectionKey,char* chText);
	void DeleteSection(CString strSectionName);
	void WriteInt(CString strSection, CString strSectionKey, int nValue);
	void WriteText(CString strSection, CString strSectionKey,CString strValue);
	int ReadInt(CString strSection, CString strSectionKey);
	CString ReadText(CString strSection,CString strSectionKey);
	CIni();
	CIni(CString strPath);
	virtual ~CIni();

private:
	CString m_strFilePath;

};

#endif 
