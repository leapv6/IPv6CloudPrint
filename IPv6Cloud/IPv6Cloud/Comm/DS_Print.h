#ifndef PRINT_H_
#define PRINT_H_

#include <string>
using namespace std;

#include "msword.h"
#include "WINSPOOL.H"

class CDSPrint
{
public:
	static int GetWinPrint(CStringArray &strArr)
	{
		strArr.RemoveAll();

		DWORD dwSize, dwPrinters;
		if (::EnumPrinters (PRINTER_ENUM_LOCAL | PRINTER_ENUM_CONNECTIONS, NULL, 5,
			NULL, 0, &dwSize, &dwPrinters))
		{
			CString strErr = "";
			strErr.Format("error is %d",::GetLastError());
			return 0;
		}
		//::EnumPrinters (PRINTER_ENUM_LOCAL | PRINTER_ENUM_CONNECTIONS, NULL, 5, NULL, 0, &dwSize, &dwPrinters);
			
		BYTE* pBuffer = new BYTE[dwSize];
		::EnumPrinters (PRINTER_ENUM_LOCAL | PRINTER_ENUM_CONNECTIONS, NULL, 5,
			pBuffer, dwSize, &dwSize, &dwPrinters); 
		if (dwPrinters != 0) {
			PRINTER_INFO_5* pPrnInfo = (PRINTER_INFO_5*) pBuffer;
			for (UINT i=0; i<dwPrinters; i++) {
				strArr.Add(pPrnInfo->pPrinterName);
				pPrnInfo++;
			}
		}
		delete[] pBuffer;

		/*
		DWORD Flags = PRINTER_ENUM_FAVORITE | PRINTER_ENUM_LOCAL;   //local printers  
		PRINTER_INFO_2* pPrinterEnum=new PRINTER_INFO_2;
		DWORD dwCount=0,dwBytes=0;
		TCHAR Name[500]   ;  
		memset(Name, 0, sizeof(TCHAR) * 500)   ;   
		if (!EnumPrinters(Flags,Name,2,(LPBYTE)pPrinterEnum,sizeof(PRINTER_INFO_2),&dwBytes,&dwCount))
		{
			if (pPrinterEnum) delete pPrinterEnum;
			pPrinterEnum=(PRINTER_INFO_2*)(new BYTE[dwBytes]);
			EnumPrinters(Flags,Name,2,(LPBYTE)pPrinterEnum,dwBytes,&dwBytes,&dwCount);
		}

		for(DWORD i = 0 ; i < dwCount ; i++ ) 
		{
			CString printerName = pPrinterEnum[ i ].pPrinterName ;
			strArr.Add(printerName);
		}

		if (pPrinterEnum) 
		{
			delete []pPrinterEnum;
		}
		*/
		return strArr.GetSize();
	}

	static bool SetPrinterDuplex(LPTSTR  lpPrinterName, bool duplex)
	{
		HANDLE   hPrinter   =   NULL;  
		DWORD   dwNeeded   =   0;  
		PRINTER_INFO_2   *pi2   =   NULL;  
		DEVMODE   *pDevMode   =   NULL;  
		PRINTER_DEFAULTS   pd;  
		BOOL   bFlag;  
		LONG   lFlag;  

		// Open   printer   handle   (on   Windows   NT,   you   need   full-access   because   you  
		// will   eventually   use   SetPrinter)...  
		ZeroMemory(&pd,   sizeof(pd));  
		pd.DesiredAccess   =   PRINTER_ALL_ACCESS;  
		bFlag = OpenPrinter(lpPrinterName,   &hPrinter,   &pd);  
		if (!bFlag   ||   (hPrinter   ==   NULL))  {
			//AfxMessageBox( "Cannot open the printer specified" ) ;
			return   FALSE;  
		}
		//   The   first   GetPrinter   tells   you   how   big   the   buffer   should   be   in    
		//   order   to   hold   all   of   PRINTER_INFO_2.   Note   that   this   should   fail   with    
		//   ERROR_INSUFFICIENT_BUFFER.     If   GetPrinter   fails   for   any   other   reason    
		//   or   dwNeeded   isn't   set   for   some   reason,   then   there   is   a   problem...  
		SetLastError(0);  
		bFlag  = GetPrinter(hPrinter,   2,   0,   0,   &dwNeeded);  
		if((!bFlag)   &&   (GetLastError()   !=   ERROR_INSUFFICIENT_BUFFER) || (dwNeeded   ==   0))  
		{  

			ClosePrinter(hPrinter);  
			//AfxMessageBox( "Cannot get the size of the DEVMODE structure" ) ;
			return   FALSE;  
		}  

		//   Allocate   enough   space   for   PRINTER_INFO_2...  
		pi2   =   (PRINTER_INFO_2   *)GlobalAlloc(GPTR,   dwNeeded);  
		if(pi2   ==   NULL)  
		{  
			ClosePrinter(hPrinter);  
			return   FALSE;  
		}  
		//   The   second   GetPrinter   fills   in   all   the   current   settings,   so   all   you  
		//   need   to   do   is   modify   what   you're   interested   in...  
		bFlag  = GetPrinter(hPrinter,   2,   (LPBYTE)pi2,   dwNeeded,   &dwNeeded);  
		if   (!bFlag)  
		{  
			GlobalFree(pi2);  
			ClosePrinter(hPrinter);  
			return   FALSE;  
		}  

		//   If   GetPrinter   didn't   fill   in   the   DEVMODE,   try   to   get   it   by   calling  
		//   DocumentProperties...  
		if   (pi2->pDevMode   ==   NULL)  
		{  
			dwNeeded   =   DocumentProperties(NULL,   hPrinter,   lpPrinterName,  NULL,   NULL,   0);  
			if   (dwNeeded   <=   0)  
			{  
				GlobalFree(pi2);  
				ClosePrinter(hPrinter);  
				return   FALSE;  
			}	  

			pDevMode   =   (DEVMODE   *)GlobalAlloc(GPTR,   dwNeeded);  
			if(pDevMode   ==   NULL)  
			{  
				GlobalFree(pi2);  
				ClosePrinter(hPrinter);  
				return   FALSE;  
			}  
			lFlag   =   DocumentProperties(NULL,   hPrinter,  lpPrinterName,  pDevMode,   NULL, DM_OUT_BUFFER);  
			if   (lFlag   !=   IDOK   ||   pDevMode   ==   NULL)  
			{  
				GlobalFree(pDevMode);  
				GlobalFree(pi2);  
				ClosePrinter(hPrinter);  
				return   FALSE;  
			}  
			pi2->pDevMode = pDevMode;  
		}  

		// 	if( pi2->pDevMode->dmFields & DM_DUPLEX ) {
		//             GlobalFree(pDevMode);  
		// 			GlobalFree(pi2);  
		// 			ClosePrinter(hPrinter);  
		// 			return   FALSE;  
		// 	}
		pi2->pDevMode->dmFields = DM_DUPLEX | DM_ORIENTATION;  
		if( duplex ) 
			pi2->pDevMode->dmOrientation = DMORIENT_LANDSCAPE ;  
		else
			pi2->pDevMode->dmOrientation = DMORIENT_PORTRAIT ;  

		pi2->pDevMode->dmDuplex = DMDUP_VERTICAL;
		lFlag   =   DocumentProperties(NULL,   hPrinter, lpPrinterName,  pi2->pDevMode,   pi2->pDevMode,  DM_IN_BUFFER|DM_OUT_BUFFER);  
		if   (lFlag   !=   IDOK)  
		{  
			GlobalFree(pi2);  
			ClosePrinter(hPrinter);  
			if(pDevMode)  
				GlobalFree(pDevMode);  
			return   FALSE;  
		}  
		bFlag   =   SetPrinter(hPrinter,   2,   (LPBYTE)pi2,   0);  
		if(!bFlag)  
		{  
			GlobalFree(pi2);  
			ClosePrinter(hPrinter);  
			if(pDevMode)  
				GlobalFree(pDevMode);  
			return   FALSE;  
		}  
		//   Add   End       By   Masatoshi   Kunishima@fb.tokyo.obic.co.jp   in   2003.2.25  

		SendMessageTimeout(HWND_BROADCAST,   WM_DEVMODECHANGE, 0L,  (LPARAM)(LPCSTR)lpPrinterName,  SMTO_NORMAL,   1000,   NULL);  
		if(pi2)  GlobalFree(pi2);  
		if(hPrinter)  ClosePrinter(hPrinter);  
		if(pDevMode)  GlobalFree(pDevMode);  
		return   TRUE;   
	}

	static bool PrintWord(CString strPrintName, CString strDocPath, int Copies) 
	{
		_Application objWord;

		if( strDocPath.GetLength() == 0 || strPrintName.GetLength() == 0) {
			return false;
		}

		// Convenient values declared as ColeVariants.
		COleVariant covTrue((short)TRUE),
			covFalse((short)FALSE),
			covOptional((long)DISP_E_PARAMNOTFOUND, VT_ERROR);

		// Get the IDispatch pointer and attach it to the objWord object.
		if (!objWord.CreateDispatch("Word.Application" , NULL ))
		{
			//AfxMessageBox("Couldn't get Word object.");
			return false;
		}
		objWord.SetVisible(FALSE);  //This shows the application.
		Documents docs(objWord.GetDocuments());
		_Document testDoc;

		testDoc.AttachDispatch(
			docs.Open(COleVariant( strDocPath ,VT_BSTR),
			covFalse,    // Confirm Conversion.
			covFalse,    // ReadOnly.
			covFalse,    // AddToRecentFiles.
			covOptional, // PasswordDocument.
			covOptional, // PasswordTemplate.
			covFalse,    // Revert.
			covOptional, // WritePasswordDocument.
			covOptional, // WritePasswordTemplate.
			covOptional, // Format. // Last argument for Word 97
			covOptional, // Encoding // New for Word 2000/2002
			covTrue,     // Visible
			covOptional, // OpenConflictDocument
			covOptional, // OpenAndRepair
			COleVariant((long)0),     // DocumentDirection wdDocumentDirection LeftToRight
			covOptional  // NoEncodingDialog
			)  // Close Open parameters
		); // Close AttachDispatch(…)

		if( !SetPrinterDuplex(strPrintName.GetBuffer(0) , true ) )
		{
			//AfxMessageBox( "请先配置一台打印机！" ) ;
			return false;
		}

		testDoc.PrintOut(covFalse,              // Background.
			covOptional,           // Append.
			covOptional,           // Range.
			covOptional,           // OutputFileName.
			covOptional,           // From.
			covOptional,           // To.
			covOptional,           // Item.
			COleVariant((long)Copies),  // Copies.
			covOptional,           // Pages.
			covOptional,           // PageType.
			covOptional,           // PrintToFile.
			covOptional,           // Collate.
			covOptional,           // ActivePrinterMacGX.
			covTrue,            // ManualDuplexPrint.
			covOptional,           // PrintZoomColumn  New with Word 2002
			covOptional,           // PrintZoomRow          ditto
			covOptional,           // PrintZoomPaperWidth   ditto
			covOptional);          // PrintZoomPaperHeight  ditto

		SetPrinterDuplex( strPrintName.GetBuffer(0) , false ) ;

		objWord.Quit(covFalse,  // SaveChanges.
			covTrue,   // OriginalFormat.
			covFalse   // RouteDocument.
		);
		return true;
	}
};
#endif /*PRINT_H_*/


