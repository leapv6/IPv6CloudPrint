#pragma once
#include <winsock2.h>
#include <Ws2tcpip.h>
#pragma comment(lib,"ws2_32")  //º”‘ÿws2_32libø‚
#include "DataQueue.h"
#include "FastSemaphore.h"

#define     RECONNECT_TIME      (10 * 1000)
#define     RECV_LENGTH_LARGE   (1024 * 10)

struct StTcpData
{
    char buffer[RECV_LENGTH_LARGE];
	long lsize;
    StTcpData()
    {
        memset(buffer, 0, RECV_LENGTH_LARGE);
    };
};

class CTcpClient
{
public:
	CTcpClient(void);
	~CTcpClient(void);
	bool Start(char *pNodeName, char * pServiceName);
    long Send(char *pBuffer, long lSize);
public:
	bool      m_bWork;
	bool      m_bConnected;
		
	CFastSemaphore m_SendEvent, m_RecvEvent, m_ThreadExitEvent;
	CDataQueue<StTcpData> m_QueueSend;
	CDataQueue<StTcpData> m_QueueRecv;

	SOCKET    m_sCltSocket;
	addrinfo  m_addHints;
	addrinfo* m_addRes;
private:
    HANDLE m_threadreconnect, m_threadsend, m_threadrecv;
};

