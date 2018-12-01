#ifndef DS_LOCK_H_
#define DS_LOCK_H_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class DSLock
{
public:
    DSLock()
	{
		::InitializeCriticalSection(&m_lock);
	}
	~DSLock()
	{
	    ::DeleteCriticalSection(&m_lock);
	}
    void Lock()
	{
		::EnterCriticalSection(&m_lock);
	}
    void UnLock()
	{
		::LeaveCriticalSection(&m_lock);
	}

#if(_WIN32_WINNT >= 0x0400)
	bool TryLock()
	{
		bool bRet = ::TryEnterCriticalSection(&m_lock);
		return bRet;
	}
#endif
private:
    CRITICAL_SECTION m_lock;
};

#endif /*DS_LOCK_H_*/
