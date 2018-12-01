// FileUtils.cpp: implementation of the CFileUtils class.
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "FileUtils.h"
#include "../PubHead.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

CFileUtils::CFileUtils()
{
}

CFileUtils::~CFileUtils()
{

}

BOOL CFileUtils::Read(char* cpFullName, CString &strBuffer)
{
	CFile fp;
	CFileException e;
	if(!fp.Open(cpFullName, CFile::modeRead,&e))
	{
		return FALSE;
	}

	char cpBuffer[MAX_NET_PACKET_LENGTH] = {0};
	fp.Read(cpBuffer, MAX_NET_PACKET_LENGTH);
	strBuffer.Format("%s", cpBuffer);
	fp.Close();
	return TRUE;
}

BOOL CFileUtils::Write(char* cpDir, char* cpName, char *cpBuffer)
{
	if (!DirExist(cpDir))
	{
		CreateDir(cpDir);
	}
	char cpFullName[MAX_PATH] = {0};
	sprintf(cpFullName, "%s/%s", cpDir, cpName);
	CStdioFile fp;
	CFileException e;
	if(!fp.Open(cpFullName, CFile::modeCreate | CFile::modeWrite,&e))
	{
		return FALSE;
	}

	fp.WriteString(cpBuffer);
	fp.Close();
	return TRUE;
}

bool CFileUtils::DirExist(const TCHAR  *pszDirName)   
{   
    WIN32_FIND_DATA   fileinfo;   
    TCHAR   _szDir[_MAX_PATH];   
    _tcscpy(_szDir,pszDirName);   
    int nLen  =  _tcsclen(_szDir);   
    if((_szDir[nLen-1] == '//') || (_szDir[nLen-1] == '/'))   
    {   
        _szDir[nLen-1] = '/0';   
    }   
    HANDLE hFind  = ::FindFirstFile(_szDir,&fileinfo);   
    if(hFind == INVALID_HANDLE_VALUE)   
    {   
        return false;   
    }   
    if(fileinfo.dwFileAttributes == FILE_ATTRIBUTE_DIRECTORY)   
    {   
        ::FindClose(hFind);   
        return true;   
    }   
    ::FindClose(hFind);   
    return false;   
}

bool CFileUtils::CreateDir(const TCHAR  *pszDirName)   
{   
    bool bRet = false;   
    TCHAR _szDir[_MAX_PATH];   
    TCHAR _szTmp[_MAX_DIR];   
    int nLen = 0;   
    int idx;   
    if((DirExist(pszDirName)) == true)   
        return true;   
    _tcscpy(_szDir,pszDirName);
    nLen   =   _tcslen(_szDir);   
    if(_szDir[nLen-1] == '//' || _szDir[nLen-1] == '/')   
    {   
        _szDir[nLen-1] = '/0';   
    }   
    nLen = _tcslen(_szDir);   
    memset(_szTmp,0,_MAX_DIR);   
    TCHAR _str[2];   
    for(idx = 0;idx < nLen;idx++)   
    {   
        if(_szDir[idx] != '//')   
        {   
            _str[0] = _szDir[idx];   
            _str[1] = 0;   
            _tcscat(_szTmp,_str);   
        }   
        else   
        {   
            bRet = ::CreateDirectory(_szTmp,NULL);   
            if(bRet)   
            {   
                ::SetFileAttributes(_szTmp,FILE_ATTRIBUTE_NORMAL);   
            }   
            _str[0] = _szDir[idx];   
            _str[1] = 0;   
            _tcscat(_szTmp,_str);   
        }   
        if(idx == nLen-1)   
        {   
            bRet = ::CreateDirectory(_szTmp,NULL);   
            if(bRet)   
            {   
                ::SetFileAttributes(_szTmp,FILE_ATTRIBUTE_NORMAL);   
            }   
        }   
    }   
    if(DirExist(_szTmp))   
        return   true;   
    return   false; 
}  