#pragma once  


#define DUI_NOTYPES
#include "../../DuiLib/UIlib.h"
using namespace DuiLib;


#ifdef _DEBUG
# ifdef _UNICODE//引用类库lib文件
# pragma comment(lib, "../lib/DuiLib_ud.lib")
# else
# pragma comment(lib, "../lib/DuiLib_d.lib")
# endif

#else
# ifdef _UNICODE
# pragma comment(lib, "../lib/DuiLib_u.lib")
# else
# pragma comment(lib, "../lib/DuiLib.lib")
# endif
#endif

#pragma once

class CDuiLib_Dialog:public CWindowWnd, INotifyUI  
{  
public:  
	CDuiLib_Dialog(void);  
	~CDuiLib_Dialog(void);  

	/********************************************/
	LPCTSTR GetWindowClassName() const;  
	UINT	GetClassStyle() const;  
	void	OnFinalMessage(HWND hWnd);  
	void	Notify(TNotifyUI& msg);  
	LRESULT HandleMessage(UINT uMsg, WPARAM wParam, LPARAM lParam);  
private:  
	CPaintManagerUI m_pm;  
	/********************************************/
};  