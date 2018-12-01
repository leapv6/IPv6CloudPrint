#ifndef CDATAQUEUE_H
#define CDATAQUEUE_H

#include <queue>
#include "DS_Lock.h"
using namespace std;

template <class T> class CDataQueue
{
    public:
        CDataQueue(){};
        virtual ~CDataQueue(){};

        bool Set(T *pt)
        {
            if (!pt) return false;

            m_lock.Lock();
            m_queue.push(*pt);
            m_lock.UnLock();

            return true;
        };

        bool Get(T *pt)
        {
            bool bret = false;
            if (!pt) return bret;

            m_lock.Lock();
            if (m_queue.size() > 0)
            {
                *pt = m_queue.front();
                m_queue.pop();
                bret = true;
            }
            m_lock.UnLock();

            return bret;
        };

        int Count(void)
        {
            int n = 0;

            m_lock.Lock();
            n = m_queue.size();
            m_lock.UnLock();

            return n;
        }
    protected:
    private:
        queue<T> m_queue;
        DSLock m_lock;
};

#endif // CDATAQUEUE_H
