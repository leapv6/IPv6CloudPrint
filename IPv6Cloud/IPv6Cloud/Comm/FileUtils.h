// FileUtils.h: interface for the CFileUtils class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_FILEUTILS_H__BDB29779_8E3C_4AE5_BDBF_EE2279346A60__INCLUDED_)
#define AFX_FILEUTILS_H__BDB29779_8E3C_4AE5_BDBF_EE2279346A60__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CFileUtils  
{
public:
	CFileUtils();
	virtual ~CFileUtils();
public:	
	BOOL Read(char* cpFullName, CString &strBuffer);
	BOOL Write(char* cpDir, char* cpName, char *cpBuffer);
	bool DirExist(const TCHAR  *pszDirName);
	bool CreateDir(const TCHAR  *pszDirName);
};

#endif // !defined(AFX_FILEUTILS_H__BDB29779_8E3C_4AE5_BDBF_EE2279346A60__INCLUDED_)
