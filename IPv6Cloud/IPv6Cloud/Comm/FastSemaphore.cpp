#include "StdAfx.h"
#include "FastSemaphore.h"

//!Implements
CFastSemaphore::CFastSemaphore() {
    sem = CreateSemaphore(NULL, 0, MAX_SEMAPHORE_NUM, NULL);
    this->spinCount = 0;
    this->semCount = 0;
}

CFastSemaphore::~CFastSemaphore(){
	CloseHandle(sem);
}

bool CFastSemaphore::TryWait() {
    for(long tmpCount = semCount; true; tmpCount = semCount) {
        if(tmpCount <= 0)   //If res is not available, fail
            return false;
        // Try to claim the res
        if(InterlockedCompareExchange(&semCount, tmpCount - 1, tmpCount) == tmpCount)
            break;  //success
    }   //Loop for avoiding dirty semCount

    return true;
}

void CFastSemaphore::Wait(){
    //Spin first if spinCount is positive
    for(unsigned i = 0; i < spinCount; i++)
        if(TryWait())
            return;

    if(InterlockedDecrement(&semCount) < 0) //semCount--
        WaitForSingleObject(sem, INFINITE); // We have to wait
}

void CFastSemaphore::Set(long n = 1) {
    if(InterlockedExchangeAdd(&semCount, n) < 0) //semCount+n
        ReleaseSemaphore(sem, 1, NULL);   // Release only one times
}