// Collector.h: interface for the CCollector class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_COLLECTOR_H__8AFC38E9_E9EB_442E_AC2C_C9CA7F4E7030__INCLUDED_)
#define AFX_COLLECTOR_H__8AFC38E9_E9EB_442E_AC2C_C9CA7F4E7030__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include <afxinet.h>
#include <string>
using namespace std;

#define     DS_ERROR_FLAG    "$_FEB_ERROR_$"

#define     DS_ERROR_LEN     256

class CCollector  
{
public:
	// 打开网页地址，返回网页源码字符串 bEncode(TRUE:UTF-8, FALSE:ANSI )
	CString Open(char * cpUrl, BOOL bEncode = TRUE);
	// 编码转换
	void ToUft8(CString &str,int sourceCodepage,int targetCodepage);
	// 是否异常
	BOOL IsError(CString strStr);
	// 获得从pos开始，开始标签为cpStartStr，结束标签为cpEndStr的字符串
	int GetKey(CString strSource, char *cpStartStr, char * cpEndStr, int iStartPos, CString &strValue);
	// 分割字符串
	int SplitString(LPCTSTR lpszStr, LPCTSTR lpszSplit, CStringArray& rArrString, BOOL bAllowNullString);
	// 从字符串cpDest中获得最大长度为iMax的安全字符串
	int GetSafeStr(char * cpDest, char * cpSource, int iSize, int iMax);
	// 字符串时间转换成 time_t 时间
	time_t ChangeTime(CString cpTime);
	// 判断字符串是否UTF8编码
	BOOL IsUTF8String(char * str, ULONGLONG length);

	std::string UrlEncode(const std::string& szToEncode);
	std::string UrlDecode(const std::string& szToDecode);

public:
	CCollector();
	virtual ~CCollector();
};

#endif // !defined(AFX_COLLECTOR_H__8AFC38E9_E9EB_442E_AC2C_C9CA7F4E7030__INCLUDED_)
