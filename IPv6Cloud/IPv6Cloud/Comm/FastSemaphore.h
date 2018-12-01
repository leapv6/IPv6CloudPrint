#ifndef FASTSEMAPHORE_H
#define FASTSEMAPHORE_H

//!Headers
#include <windows.h>
#define  MAX_SEMAPHORE_NUM  10000
//!Declaration
class CFastSemaphore
{
private:
    HANDLE sem; //windows kernel semaphore for blocking
    unsigned spinCount;
    long semCount; //user semaphore count, tread-safe access

public:
    CFastSemaphore();
    ~CFastSemaphore();

    bool TryWait();
    void Wait();
    void Set(long);
};

#endif //FASTSEMAPHORE_H
