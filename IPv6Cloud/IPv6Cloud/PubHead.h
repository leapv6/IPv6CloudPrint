#if !defined(AFX_PUBHEAD_H__4BD818A1_1A68_46A3_851F_5EB5052EED42__INCLUDED_)
#define AFX_PUBHEAD_H__4BD818A1_1A68_46A3_851F_5EB5052EED42__INCLUDED_

#include <vector>
using namespace std;

/************************************************************************/
/* 主界面相关信息                                                       */
/************************************************************************/

/* 自定义托盘右键消息 */
#define  WM_NOTIFYICON                      WM_USER + 0x001
#define  WM_AUTO_PRINT_MSG                  WM_USER + 0x002

/* 托盘Tip信息 */
#define  DS_TRAY_TIP_STR    "v6网印"
#define  DS_TRAY_WEB_STR    "http://www.v6cp.com"

/* 字符长度定义 */
#define STRING_LEN_IP                                                (20)
#define STRING_LEN_TINY                                              (32)
#define STRING_LEN_SHORT                                             (64)
#define STRING_LEN_SMALL                                            (128)
#define STRING_LEN_NORMAL                                           (256)
#define STRING_LEN_LARGE                                            (512)
#define STRING_LEN_HUGE                                            (1024)
#define MAX_MSG_BODY_LENGTH                                       (10240)               /*最大的消息体长度*/
#define MAX_NET_PACKET_LENGTH                                     (30720)               /*报文转义替换后的最大长度*/

#define RET_OK			 0
#define RET_ERROR		 -1


#define  MAIN_ORDER_LIST_PAGE_NUM   13
#define  HIS_ORDER_LIST_PAGE_NUM    15

/*
"orderId":"88",
"docName":"\u6f14\u793a\u6587\u7a3f1.pptx",
"docId":"43",
"userPhone":"123",
"userNick":"123",
"userName":"\u738b\u4e94",
"makeTime":"2015-10-06 21:58:29",
"upTime":"2015-09-15 16:15:37",
"shopName":"\u80e1\u5df4\u6253\u5370\u5e97",
"docFormat":"7",
"businessId":"1",
"message":null,
"price":"0.0",
"doublePrint":"0",
"sendStatus":"0",
"sendType":"1",
"specialOrder":"0",
"colorPrint":"1",
"printCopis":"1",
"payStatus":"0",
"printStatus":"0"
*/
//订单结构体
struct ST_ORDER
{
	char orderId[STRING_LEN_TINY];
	int docId;
	char docName[STRING_LEN_NORMAL];
	char userPhone[STRING_LEN_TINY];
	char userNick[STRING_LEN_NORMAL];
	char userName[STRING_LEN_NORMAL];
	char makeTime[STRING_LEN_TINY];

	char upTime[STRING_LEN_TINY];
	char shopName[STRING_LEN_NORMAL];
	int docFormat;
	int businessId;
	char message[STRING_LEN_NORMAL];
	double price;
	int doublePrint;
	int sendStatus;
	int sendType;
	int specialOrder;
	int colorPrint;
	int printCopis;
	int payStatus;
	int printStatus;

	bool bHanding; //正在处理（打印或下载）
};

#endif // !defined(AFX_PUBHEAD_H__4BD818A1_1A68_46A3_851F_5EB5052EED42__INCLUDED_)

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
/* 标准函数定义
	int nRet = RET_OK;
	try
	{
	}
	catch(...)
	{
		nRet = RET_ERROR;
	}
	return nRet;
*/