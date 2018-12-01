
// IPv6Cloud.h : PROJECT_NAME 应用程序的主头文件
//

#pragma once

#ifndef __AFXWIN_H__
	#error "在包含此文件之前包含“stdafx.h”以生成 PCH 文件"
#endif

#include "resource.h"		// 主符号


// CIPv6CloudApp:
// 有关此类的实现，请参阅 IPv6Cloud.cpp
//

class CIPv6CloudApp : public CWinApp
{
public:
	CIPv6CloudApp();

// 重写
public:
	virtual BOOL InitInstance();

// 实现

	DECLARE_MESSAGE_MAP()
};

extern CIPv6CloudApp theApp;