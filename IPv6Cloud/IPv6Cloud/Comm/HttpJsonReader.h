////////////////////////////////////////////////////////////////////////////////
//  File Name: XDHttpJsonReader.h
//
//  Functions:
//      通过HTTP来读取网页内容(JSON)的类的头文件.
//
//
////////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_HTTPJSONREADER_H__15EC4021_9FB2_47CA_A43B_B602A1BF1956__INCLUDED_)
#define AFX_HTTPJSONREADER_H__15EC4021_9FB2_47CA_A43B_B602A1BF1956__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include <afxinet.h>
#include "../json/json.h"

#define TIME_FORMAT_DAY				1
#define TIME_FORMAT_HOUR			2
#define TIME_FORMAT_MINUTE			3
#define TIME_FORMAT_SECOND			4

//补充定义
#define INT64_MIN       (-9223372036854775807i64 - 1)
#define INT64_MAX       9223372036854775807i64

//最多存储的帧信息
#define MAX_FRAME_NUM 10000
//最多存储的Packet信息（一般以此为准，关键）
#define MAX_PACKET_NUM 10000
//最多存储的监测输入输出状态的信息
#define MAX_IOCHECK_NUM 10000
//URL长度
#define MAX_URL_LENGTH 500

#ifndef SAFE_CLOSE_HANDLE
#define SAFE_CLOSE_HANDLE(x) { if (x) { CloseHandle(x); x = NULL; } }
#endif
#ifndef SAFE_DEL
#define SAFE_DEL(x) { if (x) { delete x; x = NULL; } }
#endif
#ifndef SAFE_DEL_ARRAY
#define SAFE_DEL_ARRAY(x) { if (x) { delete[] x; x = NULL; } }
#endif
#ifndef SAFE_CLOSE_SOCKET
#define SAFE_CLOSE_SOCKET(x) { if (x) { closesocket(x); x = INVALID_SOCKET; } }
#endif
#ifndef COPY_STRING
#define COPY_STRING(x,y) { if (x) { delete x; x = NULL; } int nLen = strlen(y); x = new char[nLen + 1]; strcpy(x,y);}
#endif

class CHttpJsonReader
{
	public:
		CHttpJsonReader(char* strHttpServerAddr);
		CHttpJsonReader(CHttpJsonReader* pOther);
		virtual ~CHttpJsonReader();

		char* GetHttpServerAddr();

		// 把指定的内容解析为Json对象,读取解析成功返回true
		bool ParseJson(const char* strJson, Json::Value& valueJson);

		// 从指定的相对URL地址读取内容,并将内容解析为Json对象,读取解析成功返回true
		bool ReadJson(const char* strRelativeUrl, Json::Value& valueJson, DWORD dwTimeout = 0, 
						bool* pbIsUTF8 = NULL, char** ppstrValue = NULL);

		//	与上面的区别是指定了Web服务器地址
		bool ReadJson(const char* strWebService, const char* strRelativeUrl, Json::Value& valueJson, DWORD dwTimeout = 0, 
						bool* pbIsUTF8 = NULL, char** ppstrValue = NULL);

		// 从指定的相对URL地址读取内容,并将内容解析为Json对象,读取解析成功返回true
		bool PostReadJson(const char* strWebService, const char* strRelativeUrl, Json::Value& valueJson, const char* strParam, 
							bool* pbIsUTF8 = NULL, char** ppstrValue = NULL);

		// UTF8串转换为ANSI串, strANSI为空时会自动分配内存
		static bool UTF8Str2AnsiStr(const char* strUTF8, char** pstrANSI);

		// 把json字符串转换为ANSI串, strANSI为空时会自动分配内存, isUTF8是否为UFT8编码
		static bool CopyJsonStr(Json::Value& val, char** pstrANSI, bool isUTF8 = true);

		static int ParseJsonInt(Json::Value& val);
		static UINT ParseJsonUInt(Json::Value& val);
		static double ParseJsonDouble(Json::Value& val);

		// 把ANSI的json字符串复制到strANSI中
		static bool CopyAnsiJsonStr(Json::Value& val, char** pstrANSI);

		// 将串转换为时间time_t
		static time_t GetTimeFromStr(const char* strTime, BYTE byFormat = TIME_FORMAT_MINUTE);


	private:
		// 取得URL的内容
		int GetUrlContent(const char* strURL, std::string& cstrResult, bool bExceptBOM = true, 
							DWORD dwTimeout = 0, CString* pcsContentType = NULL);

		int PostUrlContent(LPCSTR strHref, std::string& strResult, LPCSTR strParam, 
							CString* pcsContentType = NULL);
		
		void CloseHttpFile();
		void CloseSession();
		static DWORD WINAPI OpenURLThread(LPVOID lpParam); 

	private:
		Json::Reader m_reader;
		
		char*		 m_strHttpServerAddr; // 要访问的HTTP服务器地址

		CInternetSession* m_pSession;
		CHttpFile*        m_pHttpFile;
		const char*       m_strURL;
};

#endif // !defined(AFX_HTTPJSONREADER_H__15EC4021_9FB2_47CA_A43B_B602A1BF1956__INCLUDED_)
