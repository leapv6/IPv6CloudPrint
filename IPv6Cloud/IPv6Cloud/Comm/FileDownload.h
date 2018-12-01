////////////////////////////////////////////////////////////////////////////////
//  File Name: XDMediaFileDownload.cpp
////////////////////////////////////////////////////////////////////////////////

#ifndef _XD_MediaFile_DownLoad_H_
#define _XD_MediaFile_DownLoad_H_

#include <list>
#include "HttpDownload.h"
#include <string.h>

#define  DEFAULT_DOWNLOAD_FILE_PATH       "DownloadFiles"
#define  DEFAULT_CACHE_FILE_PATH          "CacheFiles"

struct FileItem
{
	char    orderId[STRING_LEN_TINY];               // 订单id
	int     nDocId;                 // 文件id
	int 	byType;					// 媒体文件类型（暂时只有缩略图，后续可能下载一些其他的文件）
	bool	bLocal;					// 是否下载到了本地
	bool	bDeleteOnDownFinish;	// 下载完成后回收free本FileItem对象
	char	strSvrUrl[MAX_PATH];	// URL:web服务器上的url或者下载到本地后的文件路径(含文件名)
	char    szLocalFilePath[MAX_PATH];
	void    *pParent;

	bool    bPrint;
};

class CFileDownload
{
public:
	CFileDownload();
	virtual ~CFileDownload();

	// 增加要下载的FileItem项
	void AddDownloadMediaFileItem(FileItem* pMFI, bool bAddFront = false);

	// 去掉指定类型的下载项目
	void RemoveDownloadMediaFileItemsByType(BYTE* byType, int nTypeCount);


public:

	//// 下载管理
	bool StartDownloadThread();
	void StopDownloadThread();
	FileItem* GetDownloadMediaFileItem();
	void ClearDownloadMediaFileItems();
	static DWORD WINAPI dwDownloadThreadProc(LPVOID lpParameter);

	static bool FileExistAndNotEmpty(char* szPath);
	static void CheckCreateDirectory(char* szPath);
	
	DWORD                  m_dwThreadIdDownload;
	HANDLE                 m_hDownload;
	list<FileItem*>        m_downloadMFIs; // 要下载的媒体文件列表,包括m_mediaFiles与各个会员的pMediaFiles,完成下载后去除
	HANDLE                 m_hMutexDownloadList;
	HANDLE                 m_hEventAddDownload;
	HANDLE                 m_hEventDownloadFinished;
	CHttpDownload*         m_pHttpDownload;
	bool                   m_bDownloadExit;

};



#endif //#ifndef _XD_MediaFile_DownLoad_H_
