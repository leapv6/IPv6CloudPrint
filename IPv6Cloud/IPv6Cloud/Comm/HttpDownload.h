////////////////////////////////////////////////////////////////////////////////
//  File Name: DownloadHttp.h
////////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_DOWNLOADHTTP_H__EA7F8F4B_C6DD_4ED9_81D6_A1C5FECDB26C__INCLUDED_)
#define AFX_DOWNLOADHTTP_H__EA7F8F4B_C6DD_4ED9_81D6_A1C5FECDB26C__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "AFXINET.H"
#include "ATLCONV.H"
#pragma comment(lib, "Wininet.lib")

const UINT WM_HTTPDOWNLOAD_THREAD_FINISHED = WM_APP + 81;
const UINT WM_HTTPDOWNLOAD_FILESTATUS = WM_APP + 82;
const UINT WM_HTTPDOWNLOAD_PERCENTAGE = WM_APP + 83;
const UINT WM_HTTPDOWNLOAD_TIMELEFT = WM_APP + 84;
const UINT WM_HTTPDOWNLOAD_STATUS = WM_APP + 85;
const UINT WM_HTTPDOWNLOAD_TRANSFERRATE = WM_APP + 86;

#define IDS_HTTPDOWNLOAD_FILESTATUS "%s from %s"
#define IDS_HTTPDOWNLOAD_CONNECTED "Connected to %s"
#define IDS_HTTPDOWNLOAD_RESOLVING_NAME "Resolving name: %s"
#define IDS_HTTPDOWNLOAD_RESOLVED_NAME "Resolved name to %s"
#define IDS_HTTPDOWNLOAD_CONNECTING "Connecting to %s"
#define IDS_HTTPDOWNLOAD_REDIRECTING "Redirecting to %s"
#define IDS_HTTPDOWNLOAD_GETTING_FILE_INFORMATION "Getting file information"
#define IDS_HTTPDOWNLOAD_FAIL_PARSE_ERROR "An error occurred parsing the url: %s"
#define IDS_HTTPDOWNLOAD_GENERIC_ERROR \
"An error occurred while attempting to download the file, Error:%s"
#define IDS_HTTPDOWNLOAD_FAIL_CONNECT_SERVER \
"An error occurred connecting to the server, Error:%s"


#define IDS_HTTPDOWNLOAD_BYTESPERSECOND "下载速度:%s 字节/秒"
#define IDS_HTTPDOWNLOAD_KILOBYTESPERSECOND "下载速度:%s KB/秒"
#define IDS_HTTPDOWNLOAD_OK_TO_OVERWRITE \
"The file '%s' already exists.\nDo you want to replace it?"
#define IDS_HTTPDOWNLOAD_FAIL_FILE_OPEN \
"An error occured while opening the file to be downloaded, Error:%s"
#define IDS_HTTPDOWNLOAD_ABORTING_TRANSFER "Aborting transfer"
#define IDS_HTTPDOWNLOAD_FAIL_FILE_SEEK \
"An error occurred while seeking to the end of the file to be downloaded, Error:%s"


#define IDS_HTTPDOWNLOAD_INVALID_SERVER_RESPONSE \
"Failed to receive a valid response from the server, Error:%s"
#define IDS_HTTPDOWNLOAD_INVALID_HTTP_RESPONSE \
"Failed to receive a valid HTTP response from the server, Response Code:%s"
#define IDS_HTTPDOWNLOAD_ERROR_READFILE \
"An error occurred while downloading the file, Error:%s"
#define IDS_HTTPDOWNLOAD_PERCENTAGE "%s％ of %s Completed"
#define IDS_HTTPDOWNLOAD_RETREIVEING_FILE "Retrieving the file"
#define IDS_HTTPDOWNLOAD_OF "%s / %s"
#define IDS_HTTPDOWNLOAD_SECONDS "%s秒"
#define IDS_HTTPDOWNLOAD_MINUTES "%s分"
#define IDS_HTTPDOWNLOAD_MINUTES_AND_SECONDS "%s分%s秒"
#define IDS_HTTPDOWNLOAD_BYTES "%s字节"
#define IDS_HTTPDOWNLOAD_KILOBYTES "%sKB"
#define IDS_HTTPDOWNLOAD_MEGABYTES "%sMB"
#define IDS_HTTPDOWNLOAD_TIMELEFT "剩余%s, %s"

class CHttpDownload : public CObject 
{
public:
	CHttpDownload();
	virtual ~CHttpDownload();

public:
	//Enums
	enum ConnectionType
	{
		UsePreConfig, 
		DirectToInternet,
		UseProxy,
	};
	HWND m_hNotifyWnd;

	HANDLE m_hEventDownloadFinished;
	bool m_bDownloadSucceed;

public:
	void SetDownloadFinishedEvent(HANDLE hEventDownloadFinished);
	DWORD WaitDownloadFinished(DWORD dwMilliseconds);

	BOOL Init( HWND hNotifyWnd,
	CString sURLToDownload,
	CString sFileToDownloadInto,
	CString sProxyServer="",
	CString sProxyUserName="",
	CString sProxyPassword="",
	CString sHTTPUserName="",
	CString sHTTPPassword="",
	CHttpDownload::ConnectionType nConnectionType = CHttpDownload::UsePreConfig,
	BOOL bPromptFileOverwrite = FALSE,
	BOOL PromptForProxyDetails = FALSE,
	BOOL bPromptForHTTPDetails = FALSE,
	double dbLimit = 0, //For BANDWIDTH Throptling, The value in Bytes / Second to limit the connection to
	DWORD dwStartPos= 0 //Offset to resume the download at 
	);

	BOOL Start();
	void Cancel();
	void DisInit();
	BOOL IsCancel();

	//Public Member variables
	CString m_sURLToDownload;
	CString m_sFileToDownloadInto;
	CString m_sProxyServer;
	CString m_sProxyUserName;
	CString m_sProxyPassword;
	CString m_sHTTPUserName;
	CString m_sHTTPPassword;
	CString m_sUserAgent;
	ConnectionType m_ConnectionType;
	BOOL m_bPromptFileOverwrite;
	BOOL m_bPromptForProxyDetails;
	BOOL m_bPromptForHTTPDetails;
	double m_dbLimit; //For BANDWIDTH Throptling, The value in Bytes / Second to limit the connection to
	DWORD m_dwStartPos; //Offset to resume the download at 
	protected:
	LRESULT OnThreadFinished(WPARAM wParam, LPARAM lParam);

	//Methods
	static void CALLBACK _OnStatusCallBack(HINTERNET hInternet, DWORD dwContext, DWORD dwInternetStatus, 
	LPVOID lpvStatusInformation, DWORD dwStatusInformationLength);
	static BOOL QueryStatusNumber(HINTERNET hInternet, DWORD dwFlag, DWORD& dwCode);
	static BOOL QueryStatusCode(HINTERNET hInternet, DWORD& dwCode);
	static BOOL QueryContentLength(HINTERNET hInternet, DWORD& dwCode);
	void OnStatusCallBack(HINTERNET hInternet, DWORD dwInternetStatus, 
	LPVOID lpvStatusInformation, DWORD dwStatusInformationLength);
	static UINT _DownloadThread(LPVOID pParam);
	virtual BOOL OnSetOptions();
	virtual void HandleThreadErrorWithLastError(char* nIDError, DWORD dwLastError=0);
	virtual void HandleThreadError(char* nIDError);
	virtual void DownloadThread();
	virtual void SetPercentage(int nPercentage);
	virtual void SetTimeLeft(DWORD dwSecondsLeft, DWORD dwBytesRead, DWORD dwFileSize);
	virtual void SetStatus(const CString& sCaption);
	virtual void SetStatus(char * nID);
	virtual void SetStatus(char* nID, const CString& lpsz1);
	virtual void SetTransferRate(double KbPerSecond);
	virtual void UpdateControlsDuringTransfer(DWORD dwStartTicks, DWORD& dwCurrentTicks, DWORD dwTotalBytesRead, DWORD& dwLastTotalBytes, 
	DWORD& dwLastPercentage, BOOL bGotFileSize, DWORD dwFileSize);

public:
	CString m_sError;

protected:
	//Member variables
	CString m_sServer; 
	DWORD m_dwServiceType;
	CString m_sObject; 
	CString m_sFilename;
	INTERNET_PORT m_nPort;
	HINTERNET m_hInternetSession;
	HINTERNET m_hHttpConnection;
	HINTERNET m_hHttpFile;
	BOOL m_bAbort;
	BOOL m_bSafeToClose;
	CFile m_FileToWrite;
	CWinThread* m_pThread;

};

#endif // !defined(AFX_DOWNLOADHTTP_H__EA7F8F4B_C6DD_4ED9_81D6_A1C5FECDB26C__INCLUDED_)
