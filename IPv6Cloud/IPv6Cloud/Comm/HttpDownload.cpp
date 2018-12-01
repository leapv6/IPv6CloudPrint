////////////////////////////////////////////////////////////////////////////////
//  File Name: DownloadHttp.cpp
////////////////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "HttpDownload.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif
//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

CHttpDownload::CHttpDownload()
{
	m_hEventDownloadFinished = NULL;
	m_bDownloadSucceed = false;

	m_hInternetSession = NULL;
	m_hHttpConnection = NULL;
	m_hHttpFile = NULL;
	m_bAbort = FALSE;
	m_bSafeToClose = FALSE;
	m_pThread = NULL;
	m_dwServiceType = 0;
}

CHttpDownload::~CHttpDownload()
{
	Cancel();
	DisInit();
}

BOOL CHttpDownload::Init(
						HWND hNotifyWnd,
						CString sURLToDownload,
						CString sFileToDownloadInto,
						CString sProxyServer,
						CString sProxyUserName,
						CString sProxyPassword,
						CString sHTTPUserName,
						CString sHTTPPassword,
						CHttpDownload::ConnectionType nConnectionType,
						BOOL bPromptFileOverwrite,
						BOOL bPromptForProxyDetails,
						BOOL bPromptForHTTPDetails,
						double dbLimit, //For BANDWIDTH Throptling, The value in Bytes / Second to limit the connection to
						DWORD dwStartPos //Offset to resume the download at 
						)
{
	m_hNotifyWnd = hNotifyWnd;
	m_sURLToDownload = sURLToDownload;
	m_sFileToDownloadInto = sFileToDownloadInto;
	m_sProxyServer = sProxyServer;
	m_sProxyUserName = sProxyUserName;
	m_sProxyPassword = sProxyPassword;
	m_sHTTPUserName = sHTTPUserName;
	m_sHTTPPassword = sHTTPPassword;
	m_ConnectionType = nConnectionType;
	m_bPromptFileOverwrite = bPromptFileOverwrite;
	m_bPromptForProxyDetails = bPromptForProxyDetails;
	m_bPromptForHTTPDetails = bPromptForHTTPDetails;
	m_dbLimit = dbLimit;
	m_dwStartPos = dwStartPos;

	m_hInternetSession = NULL;
	m_hHttpConnection = NULL;
	m_hHttpFile = NULL;
	m_bAbort = FALSE;
	m_bSafeToClose = FALSE;
	m_pThread = NULL;

	m_dwServiceType = 0;
	return TRUE;
}

LRESULT CHttpDownload::OnThreadFinished(WPARAM wParam, LPARAM lParam)
{
	//It's now safe to close since the thread has signaled us
	m_bSafeToClose = TRUE;

	//Stop the animation
	// m_ctrlAnimate.Stop();

	//If an error occured display the message box
	/** by CHJ 2012.1.5
	if (m_bAbort)
	{
		// EndDialog(IDCANCEL);
	}
	else if (wParam)
	{
		// AfxMessageBox(m_sError); // by CHJ 2012.1.5
		// EndDialog(IDCANCEL);
	}
	else
	{
		// EndDialog(IDOK);
	}
	**/ 

	if (m_hNotifyWnd)
	{
		PostMessage(m_hNotifyWnd, WM_HTTPDOWNLOAD_THREAD_FINISHED, wParam, lParam);
	}

	if (m_hEventDownloadFinished)
	{
		SetEvent(m_hEventDownloadFinished);
	}
	return 0L;
}

void CHttpDownload::SetDownloadFinishedEvent(HANDLE hEventDownloadFinished)
{
	m_hEventDownloadFinished = hEventDownloadFinished;
}

DWORD CHttpDownload::WaitDownloadFinished(DWORD dwMilliseconds)
{
	return WaitForSingleObject(m_hEventDownloadFinished, dwMilliseconds);
}

BOOL CHttpDownload::Start()
{
	//Validate the URL
	ASSERT(m_sURLToDownload.GetLength()); //Did you forget to specify the file to download
	if (!AfxParseURL(m_sURLToDownload, m_dwServiceType, m_sServer, m_sObject, m_nPort))
	{
		//Try sticking "http://" before it
		m_sURLToDownload = _T("http://") + m_sURLToDownload;
		if (!AfxParseURL(m_sURLToDownload, m_dwServiceType, m_sServer, m_sObject, m_nPort))
		{
			CString sMsg;
			sMsg.Format(_T("Failed to parse the URL: %s\n"), m_sURLToDownload);
			TRACE(sMsg);
			// AfxMessageBox(sMsg);
			return FALSE;
		}
	}

	//Check to see if the file we are downloading to exists and if
	//it does, then ask the user if they were it overwritten
	CFileStatus fs;
	ASSERT(m_sFileToDownloadInto.GetLength());
	BOOL bDownloadFileExists = CFile::GetStatus(m_sFileToDownloadInto, fs);
	if (bDownloadFileExists && m_dwStartPos == 0 && m_bPromptFileOverwrite)
	{
		CString sMsg;
		sMsg.Format(IDS_HTTPDOWNLOAD_OK_TO_OVERWRITE, m_sFileToDownloadInto);
		if (AfxMessageBox(sMsg, MB_YESNO) != IDYES)
		{
			TRACE(_T("Failed to confirm file overwrite, download aborted\n"));
			// EndDialog(IDCANCEL);
			return FALSE;
		}
	}

	//Try and open the file we will download into
	DWORD dwFileFlags = 0;
	if (bDownloadFileExists && (m_dwStartPos > 0))
		dwFileFlags = CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareDenyWrite;
	else
		dwFileFlags = CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite;
	if (!m_FileToWrite.Open(m_sFileToDownloadInto, dwFileFlags))
	{
		CString sError;
		sError.Format(_T("%d"), ::GetLastError());
		CString sMsg;
		sMsg.Format(IDS_HTTPDOWNLOAD_FAIL_FILE_OPEN, sError);
		TRACE("%s\n", sMsg);
		//AfxMessageBox(sMsg);
		//EndDialog(IDCANCEL);
		return FALSE;
	}
	else
	{
		//Seek to the end of the file
		try
		{
			m_FileToWrite.Seek(m_dwStartPos, CFile::begin); 
			m_FileToWrite.SetLength(m_dwStartPos);
		}
		catch(CFileException* pEx) 
		{
			CString sError;
			sError.Format(_T("%d"), pEx->m_lOsError);
			CString sMsg;
			sMsg.Format(IDS_HTTPDOWNLOAD_FAIL_FILE_SEEK, pEx->m_lOsError);
			// AfxMessageBox(sMsg);
			TRACE("%s\n", sMsg);
			// EndDialog(IDCANCEL);
			return FALSE;
		} 
	}

	//Pull out just the filename component
	int nSlash = m_sObject.ReverseFind(_T('/'));
	if (nSlash == -1)
		nSlash = m_sObject.ReverseFind(_T('\\'));

	if (nSlash != -1 && m_sObject.GetLength() > 1)
		m_sFilename = m_sObject.Right(m_sObject.GetLength() - nSlash - 1);
	else
		m_sFilename = m_sObject;

	//Set the file status text
	CString sFileStatus;
	ASSERT(m_sObject.GetLength());
	ASSERT(m_sServer.GetLength());
	sFileStatus.Format(IDS_HTTPDOWNLOAD_FILESTATUS, m_sFilename, m_sServer);
	// m_ctrlFileStatus.SetWindowText(sFileStatus);
	SendMessage(m_hNotifyWnd, WM_HTTPDOWNLOAD_FILESTATUS, (WPARAM)&sFileStatus, 0); 
	//Spin off the background thread which will do the actual downloading
	m_pThread = AfxBeginThread(_DownloadThread, this, THREAD_PRIORITY_NORMAL, 0, CREATE_SUSPENDED);
	
	if (m_pThread == NULL)
	{
		TRACE("Failed to create download thread, dialog is aborting\n");
		// CString sMsg;
		// sMsg.Format(_T("Failed to create download thread, dialog is aborting\n"));
		// AfxMessageBox(sMsg);
		// EndDialog(IDCANCEL);
		return FALSE;
	}
	m_pThread->m_bAutoDelete = FALSE;
	m_pThread->ResumeThread();

	return TRUE;
}

UINT CHttpDownload::_DownloadThread(LPVOID pParam)
{
	//Convert from the SDK world to the C++ world
	CHttpDownload* pHttpDownload = (CHttpDownload*) pParam;
	ASSERT(pHttpDownload);
	pHttpDownload->DownloadThread();
	return 0;
}

void CHttpDownload::SetPercentage(int nPercentage)
{
	//Change the progress control
	// m_ctrlProgress.SetPos(nPercentage);

	//Change the caption text
	CString sPercentage;
	sPercentage.Format(_T("%d"), nPercentage);
	CString sCaption;
	sCaption.Format(IDS_HTTPDOWNLOAD_PERCENTAGE, sPercentage, m_sFilename);
	// SetWindowText(sCaption);
	SendMessage(m_hNotifyWnd, WM_HTTPDOWNLOAD_PERCENTAGE, (WPARAM)&sCaption, nPercentage); 
}

void CHttpDownload::SetTimeLeft(DWORD dwSecondsLeft, DWORD dwBytesRead, DWORD dwFileSize)
{
	CString sCopied;
	if (dwBytesRead < 1024)
	{
		CString sBytes;
		sBytes.Format(_T("%d"), dwBytesRead);
		sCopied.Format(IDS_HTTPDOWNLOAD_BYTES, sBytes);
	}
	else if (dwBytesRead < 1048576)
	{
		CString sKiloBytes;
		sKiloBytes.Format(_T("%0.1f"), dwBytesRead/1024.0);
		sCopied.Format(IDS_HTTPDOWNLOAD_KILOBYTES, sKiloBytes);
	}
	else
	{
		CString sMegaBytes;
		sMegaBytes.Format(_T("%0.2f"), dwBytesRead/1048576.0);
		sCopied.Format(IDS_HTTPDOWNLOAD_MEGABYTES, sMegaBytes);
	}

	CString sTotal;
	if (dwFileSize < 1024)
	{
		CString sBytes;
		sBytes.Format(_T("%d"), dwFileSize);
		sTotal.Format(IDS_HTTPDOWNLOAD_BYTES, sBytes);
	}
	else if (dwFileSize < 1048576)
	{
		CString sKiloBytes;
		sKiloBytes.Format(_T("%0.1f"), dwFileSize/1024.0);
		sTotal.Format(IDS_HTTPDOWNLOAD_KILOBYTES, sKiloBytes);
	}
	else
	{
		CString sMegaBytes;
		sMegaBytes.Format(_T("%0.2f"), dwFileSize/1048576.0);
		sTotal.Format(IDS_HTTPDOWNLOAD_MEGABYTES, sMegaBytes);
	}

	CString sOf;
	sOf.Format(IDS_HTTPDOWNLOAD_OF, sCopied, sTotal);

	CString sTime;
	if (dwSecondsLeft < 60)
	{
		CString sSeconds;
		sSeconds.Format(_T("%d"), dwSecondsLeft);
		sTime.Format(IDS_HTTPDOWNLOAD_SECONDS, sSeconds);
	}
	else
	{
		DWORD dwMinutes = dwSecondsLeft / 60;
		DWORD dwSeconds = dwSecondsLeft % 60;
		CString sSeconds;
		sSeconds.Format(_T("%d"), dwSeconds);
		CString sMinutes;
		sMinutes.Format(_T("%d"), dwMinutes);
		if (dwSeconds == 0)
		sTime.Format(IDS_HTTPDOWNLOAD_MINUTES, sMinutes);
		else
		sTime.Format(IDS_HTTPDOWNLOAD_MINUTES_AND_SECONDS, sMinutes, sSeconds);
	}

	CString sTimeLeft;
	sTimeLeft.Format(IDS_HTTPDOWNLOAD_TIMELEFT, sTime, sOf);
	// m_ctrlTimeLeft.SetWindowText(sTimeLeft);
	SendMessage(m_hNotifyWnd, WM_HTTPDOWNLOAD_TIMELEFT, (WPARAM)&sTimeLeft, 0);
}

void CHttpDownload::SetStatus(const CString& sCaption)
{
	// m_ctrlStatus.SetWindowText(sCaption);
	SendMessage(m_hNotifyWnd, WM_HTTPDOWNLOAD_STATUS, (WPARAM)&sCaption, 0); 
}

void CHttpDownload::SetStatus(char* nID)
{
	CString sCaption;
	sCaption = nID;
	SetStatus(sCaption);
}

void CHttpDownload::SetStatus(char* nID, const CString& lpsz1)
{
	CString sStatus;
	sStatus.Format(nID, lpsz1);
	SetStatus(sStatus);
}

void CHttpDownload::SetTransferRate(double KbPerSecond)
{
	CString sRate;
	if (KbPerSecond < 1)
	{
		CString sBytesPerSecond;
		sBytesPerSecond.Format(_T("%0.0f"), KbPerSecond * 1024);
		sRate.Format(IDS_HTTPDOWNLOAD_BYTESPERSECOND, sBytesPerSecond);
	}
	else if (KbPerSecond < 10)
	{
		CString sKiloBytesPerSecond;
		sKiloBytesPerSecond.Format(_T("%0.2f"), KbPerSecond);
		sRate.Format(IDS_HTTPDOWNLOAD_KILOBYTESPERSECOND, sKiloBytesPerSecond);
	}
	else
	{
		CString sKiloBytesPerSecond;
		sKiloBytesPerSecond.Format(_T("%0.0f"), KbPerSecond);
		sRate.Format(IDS_HTTPDOWNLOAD_KILOBYTESPERSECOND, sKiloBytesPerSecond);
	}
	// m_ctrlTransferRate.SetWindowText(sRate);
	SendMessage(m_hNotifyWnd, WM_HTTPDOWNLOAD_TRANSFERRATE, (WPARAM)&sRate, 0); 
}

void CHttpDownload::HandleThreadErrorWithLastError(char* nIDError, DWORD dwLastError)
{
	//Form the error string to report
	CString sError;
	if (dwLastError)
		sError.Format(_T("%d"), dwLastError);
	else
		sError.Format(_T("%d"), ::GetLastError());
	m_sError.Format(nIDError, sError);

	//Delete the file being downloaded to if it is present
	m_FileToWrite.Close();
	::DeleteFile(m_sFileToDownloadInto);

	OnThreadFinished(1, 0);
}

void CHttpDownload::HandleThreadError(char* nIDError)
{
	m_sError = nIDError;
	OnThreadFinished(1, 0);
}

BOOL CHttpDownload::OnSetOptions()
{
	//Do nothing but your derviced class could override this function and
	//do things ssuch as InternetSetOptions(m_hInternetSession, INTERNET_OPTION_CONNECT_RETRIES, ..) etc.
	return TRUE; //To continue processing
}

BOOL CHttpDownload::QueryStatusNumber(HINTERNET hInternet, DWORD dwFlag, DWORD& dwCode)
{
	dwCode = 0;
	DWORD dwSize = sizeof(DWORD);
	return HttpQueryInfo(hInternet, dwFlag | HTTP_QUERY_FLAG_NUMBER, &dwCode, &dwSize, NULL);
}

BOOL CHttpDownload::QueryStatusCode(HINTERNET hInternet, DWORD& dwCode) 
{
	return QueryStatusNumber(hInternet, HTTP_QUERY_STATUS_CODE, dwCode);
}

BOOL CHttpDownload::QueryContentLength(HINTERNET hInternet, DWORD& dwCode) 
{
	return QueryStatusNumber(hInternet, HTTP_QUERY_CONTENT_LENGTH, dwCode);
}

void CHttpDownload::DownloadThread()
{
	//Create the Internet session handle
	ASSERT(m_hInternetSession == NULL);
	
	m_bDownloadSucceed = false;
	m_sError.Empty();

	switch (m_ConnectionType)
	{
		case UsePreConfig:
		{
			m_hInternetSession = ::InternetOpen(m_sUserAgent.GetLength() ? m_sUserAgent : AfxGetAppName(), INTERNET_OPEN_TYPE_PRECONFIG, NULL, NULL, 0); 
			break;
		}
		case DirectToInternet:
		{
			m_hInternetSession = ::InternetOpen(m_sUserAgent.GetLength() ? m_sUserAgent : AfxGetAppName(), INTERNET_OPEN_TYPE_DIRECT, NULL, NULL, 0); 
			break;
		}
		case UseProxy:
		{
			ASSERT(m_sProxyServer.GetLength()); //You need to give me a proxy Server
			m_hInternetSession = ::InternetOpen(m_sUserAgent.GetLength() ? m_sUserAgent : AfxGetAppName(), INTERNET_OPEN_TYPE_PROXY, m_sProxyServer, NULL, 0); 
			break;
		}
		default: 
		{
			ASSERT(FALSE);
			break;
		}
	}

	if (m_hInternetSession == NULL)
	{
		TRACE(_T("Failed in call to InternetOpen, Error:%d\n"), ::GetLastError());
		HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_GENERIC_ERROR);
		return;
	}

	//Should we exit the thread
	if (m_bAbort)
	{
		OnThreadFinished(0, 0);
		return;
	} 

	//Setup the status callback function
	if (::InternetSetStatusCallback(m_hInternetSession, _OnStatusCallBack) == INTERNET_INVALID_STATUS_CALLBACK)
	{
		TRACE(_T("Failed in call to InternetSetStatusCallback, Error:%d\n"), ::GetLastError());
		HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_GENERIC_ERROR);
		return;
	}

	//Should we exit the thread
	if (m_bAbort)
	{
		OnThreadFinished(0, 0);
		return;
	} 

	//Make the connection to the HTTP server 
	ASSERT(m_hHttpConnection == NULL);
	if (m_sHTTPUserName.GetLength())
		m_hHttpConnection = ::InternetConnect(m_hInternetSession, m_sServer, m_nPort, m_sHTTPUserName, 
									m_sHTTPPassword, INTERNET_SERVICE_HTTP, 0, (DWORD) this);
	else
		m_hHttpConnection = ::InternetConnect(m_hInternetSession, m_sServer, m_nPort, NULL, 
							NULL, INTERNET_SERVICE_HTTP, 0, (DWORD) this);
	if (m_hHttpConnection == NULL)
	{
		TRACE(_T("Failed in call to InternetConnect, Error:%d\n"), ::GetLastError());
		HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_FAIL_CONNECT_SERVER);
		return;
	}

	//Should we exit the thread
	if (m_bAbort)
	{
		OnThreadFinished(0, 0);
		return;
	} 

	//Call the virtual function to allow session customisation
	if (!OnSetOptions())
	{
		TRACE(_T("Failed in call to OnSetOptions\n"));
		return;
	}

	//Start the animation to signify that the download is taking place
	// PlayAnimation();

	//Issue the request to read the file
	LPCTSTR ppszAcceptTypes[2];
	ppszAcceptTypes[0] = _T("*/*"); //We support accepting any mime file type since this is a simple download of a file
	ppszAcceptTypes[1] = NULL;
	ASSERT(m_hHttpFile == NULL);
	DWORD dwFlags = INTERNET_FLAG_RELOAD | INTERNET_FLAG_DONT_CACHE | INTERNET_FLAG_PRAGMA_NOCACHE | INTERNET_FLAG_KEEP_CONNECTION;
	if (m_dwServiceType == AFX_INET_SERVICE_HTTPS) 
	dwFlags |= (INTERNET_FLAG_SECURE | INTERNET_FLAG_IGNORE_CERT_CN_INVALID | INTERNET_FLAG_IGNORE_CERT_DATE_INVALID);
	m_hHttpFile = HttpOpenRequest(m_hHttpConnection, NULL, m_sObject, _T("HTTP/1.1"), NULL, ppszAcceptTypes, dwFlags, (DWORD) this);
	if (m_hHttpFile == NULL)
	{
		TRACE(_T("Failed in call to HttpOpenRequest, Error:%d\n"), ::GetLastError());
		HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_FAIL_CONNECT_SERVER);
		return;
	}

	//Should we exit the thread.
	//The purpose is to check if user has pressed the cancel button
	if (m_bAbort)
	{
		OnThreadFinished(0, 0);
		return;
	} 

//label used to jump to if we need to resend the request
resend:

	//Issue the request
	CString sRange;
	if (m_dwStartPos != 0) //we will build the range request.
		sRange.Format(_T("Range: bytes=%d-\r\n"), m_dwStartPos);
	BOOL bSend = FALSE;
	if (sRange.IsEmpty())
		bSend = ::HttpSendRequest(m_hHttpFile, NULL, 0, NULL, 0);
	else
		bSend = ::HttpSendRequest(m_hHttpFile, sRange, sRange.GetLength(), NULL, 0);
	if (!bSend)
	{
		TRACE(_T("Failed in call to HttpSendRequest, Error:%d\n"), ::GetLastError());
		HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_FAIL_CONNECT_SERVER);
		return;
	}

	//Check the HTTP status code
	DWORD dwStatusCode = 0;
	if (!QueryStatusCode(m_hHttpFile, dwStatusCode))
	{
		TRACE(_T("Failed in call to HttpQueryInfo for HTTP query status code, Error:%d\n"), ::GetLastError());
		HandleThreadError(IDS_HTTPDOWNLOAD_INVALID_SERVER_RESPONSE);
		return;
	}
	else
	{
		//Handle any authentication errors
		if ((dwStatusCode == HTTP_STATUS_PROXY_AUTH_REQ) || (dwStatusCode == HTTP_STATUS_DENIED))
		{
			// We have to read all outstanding data on the Internet handle
			// before we can resubmit request. Just discard the data.
			char szData[51];
			DWORD dwSize = 0;
			do
			{
				::InternetReadFile(m_hHttpFile, (LPVOID)szData, 50, &dwSize);
			}
			while (dwSize != 0);

			BOOL bPrompt = FALSE;
			if (dwStatusCode == HTTP_STATUS_PROXY_AUTH_REQ)
			{
				//Set the proxy details if we have them
				int nProxyUserLength = m_sProxyUserName.GetLength();
				if (nProxyUserLength)
				{
				if (!InternetSetOption(m_hHttpFile, INTERNET_OPTION_PROXY_USERNAME, (LPVOID) m_sProxyUserName.operator LPCTSTR(), (nProxyUserLength+1) * sizeof(TCHAR)))
				TRACE(_T("Failed in call to InternetSetOption for Proxy Username, Error:%d\n"), ::GetLastError());
				if (!InternetSetOption(m_hHttpFile, INTERNET_OPTION_PROXY_PASSWORD, (LPVOID) m_sProxyPassword.operator LPCTSTR(), (m_sProxyPassword.GetLength()+1) * sizeof(TCHAR)))
				TRACE(_T("Failed in call to InternetSetOption for Proxy Password, Error:%d\n"), ::GetLastError());
				}
				else if (m_bPromptForProxyDetails)
				bPrompt = TRUE;
			}
			else if (dwStatusCode == HTTP_STATUS_DENIED)
			{
				//Set the proxy details if we have them
				int nHTTPUserLength = m_sHTTPUserName.GetLength();
				if (nHTTPUserLength)
				{
				if (!InternetSetOption(m_hHttpFile, INTERNET_OPTION_USERNAME, (LPVOID) m_sHTTPUserName.operator LPCTSTR(), (nHTTPUserLength+1) * sizeof(TCHAR)))
				TRACE(_T("Failed in call to InternetSetOption for HTTP Username, Error:%d\n"), ::GetLastError());
				if (!InternetSetOption(m_hHttpFile, INTERNET_OPTION_PASSWORD, (LPVOID) m_sHTTPPassword.operator LPCTSTR(), (m_sHTTPPassword.GetLength()+1) * sizeof(TCHAR)))
				TRACE(_T("Failed in call to InternetSetOption for HTTP Password, Error:%d\n"), ::GetLastError());
				}
				else if (m_bPromptForHTTPDetails)
				bPrompt = TRUE;
			}

			//Bring up the standard authentication dialog if required
			if (bPrompt && ::InternetErrorDlg(m_hNotifyWnd, m_hHttpFile, ERROR_INTERNET_INCORRECT_PASSWORD, FLAGS_ERROR_UI_FILTER_FOR_ERRORS |
			FLAGS_ERROR_UI_FLAGS_GENERATE_DATA | FLAGS_ERROR_UI_FLAGS_CHANGE_OPTIONS, NULL) == ERROR_INTERNET_FORCE_RETRY)
			goto resend;
		}
		else if (dwStatusCode != HTTP_STATUS_OK && dwStatusCode != HTTP_STATUS_PARTIAL_CONTENT)
		{
			TRACE(_T("Failed to retrieve a HTTP OK or partial content status, Status Code:%d\n"), dwStatusCode);
			HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_INVALID_HTTP_RESPONSE, dwStatusCode);
			return;
		}
	}

	//Update the status control to reflect that we are getting the file information
	SetStatus(IDS_HTTPDOWNLOAD_GETTING_FILE_INFORMATION);

	// Get the length of the file. 
	DWORD dwFileSize = 0;
	BOOL bGotFileSize = FALSE;
	if (QueryContentLength(m_hHttpFile, dwFileSize))
	{
		//Set the progress control range
		bGotFileSize = TRUE;
		//m_ctrlProgress.SetRange(0, 100);
	}

	//Update the status to say that we are now downloading the file
	SetStatus(IDS_HTTPDOWNLOAD_RETREIVEING_FILE);

	//Now do the actual read of the file
	DWORD dwStartTicks = ::GetTickCount();
	DWORD dwCurrentTicks = dwStartTicks;
	DWORD dwBytesRead = 0;
	char szReadBuf[1024];
	DWORD dwBytesToRead = 1024;
	DWORD dwTotalBytesRead = 0;
	DWORD dwLastTotalBytes = 0;
	DWORD dwLastPercentage = 0;

	do
	{
		if (!::InternetReadFile(m_hHttpFile, szReadBuf, 1024, &dwBytesRead))
		{
			TRACE(_T("Failed in call to InternetReadFile, Error:%d\n"), ::GetLastError());
			HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_ERROR_READFILE);
			return;
		}
		else
		{
			if (dwBytesRead && !m_bAbort)
			{
				//Write the data to file
				try
				{
					m_FileToWrite.Write(szReadBuf, dwBytesRead);
				}
				catch(CFileException* pEx)
				{
					TRACE(_T("An exception occured while writing to the download file\n"));
					HandleThreadErrorWithLastError(IDS_HTTPDOWNLOAD_ERROR_READFILE, pEx->m_lOsError);
					pEx->Delete();
					return;
				}

				// For bandwidth throttling
				if (m_dbLimit > 0.0f) 
				{
					double t = (double)(GetTickCount() - dwStartTicks);
					double q = (double)((double)dwTotalBytesRead / t);

					if (q > m_dbLimit) 
					Sleep((DWORD)((((q*t)/m_dbLimit)-t)));
				}
			}

			//Increment the total number of bytes read
			dwTotalBytesRead += dwBytesRead; 

			//Update the UI
			UpdateControlsDuringTransfer(dwStartTicks, dwCurrentTicks, dwTotalBytesRead, dwLastTotalBytes, 
			dwLastPercentage, bGotFileSize, dwFileSize);
		}
	} 
	while (dwBytesRead && !m_bAbort);

	//Just close the file before we return
	m_FileToWrite.Close();

	m_bDownloadSucceed = true;

	//We're finished
	OnThreadFinished(0, 0);
}

void CHttpDownload::UpdateControlsDuringTransfer(DWORD dwStartTicks, DWORD& dwCurrentTicks, DWORD dwTotalBytesRead, 
							DWORD& dwLastTotalBytes, DWORD& dwLastPercentage, BOOL bGotFileSize, DWORD dwFileSize)
{
	if (bGotFileSize)
	{
		//Update the percentage downloaded in the caption
		DWORD dwPercentage = (DWORD) ((dwTotalBytesRead + m_dwStartPos) * 100.0 / (dwFileSize + m_dwStartPos));
		if (dwPercentage != dwLastPercentage)
		{
			//Update the progress control bar
			SetPercentage(dwPercentage);
			dwLastPercentage = dwPercentage;
		}
	}

	//Update the transfer rate amd estimated time left every second
	DWORD dwNowTicks = GetTickCount();
	DWORD dwTimeTaken = dwNowTicks - dwCurrentTicks;
	if (dwTimeTaken > 1000)
	{
		double KbPerSecond = ((double)(dwTotalBytesRead) - (double)(dwLastTotalBytes)) / ((double)(dwTimeTaken));
		SetTransferRate(KbPerSecond);

		//Setup for the next time around the loop
		dwCurrentTicks = dwNowTicks;
		dwLastTotalBytes = dwTotalBytesRead;

		if (bGotFileSize)
		{
			//Update the estimated time left
			if (dwTotalBytesRead)
			{
				DWORD dwSecondsLeft = (DWORD) (((double)dwNowTicks - dwStartTicks) / dwTotalBytesRead * 
				(dwFileSize - dwTotalBytesRead) / 1000);
				SetTimeLeft(dwSecondsLeft, dwTotalBytesRead + m_dwStartPos, dwFileSize + m_dwStartPos);
			}
		}
	}
}

void CALLBACK CHttpDownload::_OnStatusCallBack(HINTERNET hInternet, DWORD dwContext, DWORD dwInternetStatus, 
										LPVOID lpvStatusInformation, DWORD dwStatusInformationLength)
{
	//Convert from the SDK C world to the C++ world
	CHttpDownload* pHttpDownload = (CHttpDownload*) dwContext;
	ASSERT(pHttpDownload);
	pHttpDownload->OnStatusCallBack(hInternet, dwInternetStatus, lpvStatusInformation, dwStatusInformationLength);
}

void CHttpDownload::OnStatusCallBack(HINTERNET /*hInternet*/, DWORD dwInternetStatus, 
									LPVOID lpvStatusInformation, DWORD /*dwStatusInformationLength*/)
{
	USES_CONVERSION;

	switch (dwInternetStatus)
	{
		case INTERNET_STATUS_RESOLVING_NAME:
		{
			SetStatus(IDS_HTTPDOWNLOAD_RESOLVING_NAME, A2T((LPSTR) lpvStatusInformation));
			break;
		}
		case INTERNET_STATUS_NAME_RESOLVED:
		{
			SetStatus(IDS_HTTPDOWNLOAD_RESOLVED_NAME, A2T((LPSTR) lpvStatusInformation));
			break;
		}
		case INTERNET_STATUS_CONNECTING_TO_SERVER:
		{
			SetStatus(IDS_HTTPDOWNLOAD_CONNECTING, A2T((LPSTR) lpvStatusInformation));
			break;
		}
		case INTERNET_STATUS_CONNECTED_TO_SERVER:
		{
			SetStatus(IDS_HTTPDOWNLOAD_CONNECTED, A2T((LPSTR) lpvStatusInformation));
			break;
		}
		case INTERNET_STATUS_REDIRECT:
		{
			SetStatus(IDS_HTTPDOWNLOAD_REDIRECTING, A2T((LPSTR) lpvStatusInformation));
			break;
		}
		default:
		{
			break;
		}
	}
}

void CHttpDownload::DisInit() 
{
	//Wait for the worker thread to exit
	if (m_pThread)
	{
		WaitForSingleObject(m_pThread->m_hThread, INFINITE);

		m_pThread->Delete();
		delete m_pThread;
		m_pThread = NULL;
	}

	//Free up the internet handles we may be using
	if (m_hHttpFile)
	{
		::InternetCloseHandle(m_hHttpFile);
		m_hHttpFile = NULL;
	}
	if (m_hHttpConnection)
	{
		::InternetCloseHandle(m_hHttpConnection);
		m_hHttpConnection = NULL;
	}
	if (m_hInternetSession)
	{
		::InternetCloseHandle(m_hInternetSession);
		m_hInternetSession = NULL;
	}
}

void CHttpDownload::Cancel() 
{
	if (!m_bSafeToClose) 
	{
		//Just set the abort flag to TRUE and
		//disable the cancel button
		m_bAbort = TRUE; 
		// GetDlgItem(IDCANCEL)->EnableWindow(FALSE);
		SetStatus(IDS_HTTPDOWNLOAD_ABORTING_TRANSFER);
	}
}

BOOL CHttpDownload::IsCancel()
{
	return m_bAbort;
}