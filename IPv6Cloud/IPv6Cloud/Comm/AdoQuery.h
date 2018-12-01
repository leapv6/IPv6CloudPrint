#if !defined NULLS_ADOQUERY_H
#define NULLS_ADOQUERY_H

#define LENGTH_SHORT     64
#define LENGTH_NORMAL    255
#define LENGTH_LONG      1024
#define LENGTH_LARGE     4096

#define RET_ERROR		 -1
#define RET_OK			 0

typedef enum DATABASE_TYPE 
{
		DATABASE_TYPE_ACCESS		=		0x01,
		DATABASE_TYPE_SQLSERVER		=		0x02,
		DATABASE_TYPE_FIREBIRD		=		0x03,
		DATABASE_TYPE_MYSQL			=		0x04
};

/* 数据库操作类 */
class CAdoQuery
{
private:
	_ConnectionPtr	m_pConnection;
	_RecordsetPtr	m_pRecordset;
	BOOL m_bOpened;

public:
	CAdoQuery() 
	{
		CoInitialize(NULL); 
		m_pConnection = NULL;
		m_pRecordset = NULL;
		m_bOpened = FALSE;
		m_pConnection.CreateInstance(__uuidof(Connection));
		try                 
		{	
			// 打开本地Access库*.mdb
			char szDBFile[LENGTH_LONG];
			memset( szDBFile, 0, LENGTH_LONG );
			GetModuleFileName( NULL, szDBFile, LENGTH_LONG );
			strcpy( szDBFile + strlen( szDBFile ) - 3, "mdb" );

			CString strConn;
			strConn.Format( "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=%s;", szDBFile );
			
			m_pConnection->Open( strConn.GetBuffer( 0 ), "", "", adModeUnknown);			
		}
		catch(_com_error e)
		{
			m_bOpened = FALSE;
			CString strError;
			strError.Format( "数据库连接失败!\r\n错误信息：%s", e.ErrorMessage());
//			AfxMessageBox( strError );
			return;
		}
		
		m_bOpened = FALSE;
	};
	//pszDatabase  数据库表名
	CAdoQuery::CAdoQuery( DATABASE_TYPE iType, char* pszServer, WORD wPort, char* pszUsername, char* pszPassword, char* pszDatabase )
	{
		CoInitialize(NULL); 
		m_pConnection = NULL;
		m_pRecordset = NULL;
		m_bOpened = FALSE;
		m_pConnection.CreateInstance(__uuidof(Connection));
		try                 
		{	
			CString strConn = "";
			if ( iType == DATABASE_TYPE_ACCESS )
			{
				strConn.Format( "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=%s;", pszDatabase );
			}
			else if ( iType == DATABASE_TYPE_FIREBIRD )
			{
				strConn.Format( "Provider=MSDASQL.1;User ID=%s;Password=%s;Data Source=%s", pszUsername, pszPassword, pszDatabase );
				//strConn.Format( "DRIVER=Firebird/InterBase(r) driver;UID=%s;PWD=%s;DBNAME=%s;", pszUsername, pszPassword, pszDatabase );
			}
			else if ( iType == DATABASE_TYPE_SQLSERVER )
			{
				strConn.Format( "Driver=SQL Server;Server=%s,%d;Database=%s;UID=%s;PWD=%s;", pszServer, wPort, pszDatabase, pszUsername, pszPassword );
			}
			else if ( iType == DATABASE_TYPE_MYSQL ) 
			{
//				strConn.Format( "Provider=MSDASQL.1;User ID=%s;Password=%s;Data Source=%s",pszUsername, pszPassword, pszDatabase );
				strConn.Format("DRIVER={MySQL ODBC 5.1 Driver};DESC=;DATABASE=%s;SERVER=%s;UID=%s;PASSWORD=%s;PORT=%d;OPTION=;STMT=;",pszDatabase,pszServer,pszUsername,pszPassword,wPort);
			}
			else
			{
//				AfxMessageBox( "无效的数据库连接参数!\r\n错误信息：不支持的数据库类型" );
				return;
			}
			
			m_pConnection->Open(strConn.GetBuffer( 0 ), "", "", adModeUnknown);
		
		}
		catch(_com_error e)
		{
			CString strError;
			m_bOpened = FALSE;
			int i = GetLastError();
			strError.Format( "数据库连接失败!\r\n错误信息：%s", e.ErrorMessage());
//			AfxMessageBox( strError );
			return;
		}
		
		m_bOpened = FALSE;
	};

	virtual ~CAdoQuery() 
	{
		Close();

		if ( m_pConnection != NULL && m_pConnection->State )
		{
			m_pConnection->Close();
		}
//		CoUninitialize();
	};

	HRESULT Open( char* pszSql )
	{
		HRESULT hResult = FALSE;
		if ( m_bOpened )
		{
			m_pRecordset->Close();
			m_pRecordset = NULL;
		}

		m_pRecordset.CreateInstance( __uuidof( Recordset ) );
		try
		{
			//执行查询语句sql
			hResult = m_pRecordset->Open( pszSql,
				m_pConnection.GetInterfacePtr(),
				adOpenDynamic,
				adLockOptimistic,
				adCmdText);
		}
		catch(_com_error *e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return hResult;
		}
		m_bOpened = TRUE;
		return hResult;
	};

	HRESULT Open( CString strSql )
	{
		HRESULT hResult = RET_ERROR;
		if ( m_bOpened )
		{
			m_pRecordset->Close();
			m_pRecordset = NULL;
		}

		m_pRecordset.CreateInstance( __uuidof( Recordset ) );

		try
		{
			//执行查询语句sql
			hResult = m_pRecordset->Open( strSql.GetBuffer(0),
				m_pConnection.GetInterfacePtr(),
				adOpenDynamic,
				adLockOptimistic,
				adCmdText);

		}
		catch(_com_error *e)
		{
			//AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());

			return hResult;
		}
		m_bOpened = TRUE;
		return hResult;
	};
	
	HRESULT Close()
	{
		HRESULT hResult = FALSE;
		if ( !m_bOpened )
		{
			return hResult;
		}
		try
		{	
			if(m_pRecordset != NULL)
			{
				m_pRecordset->raw_Close();
			}
			
		}
		catch(_com_error *e)
		{
			TRACE(e->ErrorMessage());
			return hResult;
		}
//		m_pRecordset->raw_Close();
//		hResult = m_pRecordset->Close();
		m_pRecordset = NULL;
		m_bOpened = FALSE;
		return hResult;
	};
	
	BOOL IsOpened()
	{
		return m_bOpened;
	};

	HRESULT Execute( char* pszSql )
	{
		HRESULT hResult = FALSE;
		
// 		_CommandPtr pCommand;
// 		pCommand.CreateInstance( __uuidof( Command ) );
// 			pCommand->ActiveConnection = m_pConnection;
// 			pCommand->CommandText = _bstr_t( strSql );
// 			pCommand->Execute( NULL, NULL, adCmdText );
// 			pCommand = NULL;
		_RecordsetPtr pRecordSet;
		pRecordSet.CreateInstance( __uuidof( Recordset ) );
		try
		{
			//执行sql语句
			hResult = pRecordSet->Open( pszSql,
				m_pConnection.GetInterfacePtr(),
				adOpenDynamic,
				adLockOptimistic,
				adCmdText);
			pRecordSet = NULL;
		}
		catch(_com_error *e)
		{
			TRACE( e->ErrorMessage() );
			return hResult;
		}
		m_bOpened = TRUE;
		return hResult;
	};
	
	HRESULT Execute( CString strSql )
	{
		HRESULT hResult = FALSE;
		
// 		_CommandPtr pCommand;
// 		pCommand.CreateInstance( __uuidof( Command ) );
// 			pCommand->ActiveConnection = m_pConnection;
// 			pCommand->CommandText = _bstr_t( strSql );
// 			pCommand->Execute( NULL, NULL, adCmdText );
// 			pCommand = NULL;
		_RecordsetPtr pRecordSet;
		pRecordSet.CreateInstance( __uuidof( Recordset ) );
		try
		{
			//执行sql语句
			hResult = pRecordSet->Open( strSql.GetBuffer( strSql.GetLength() ),
				m_pConnection.GetInterfacePtr(),
				adOpenDynamic,
				adLockOptimistic,
				adCmdText);
			pRecordSet = NULL;
		}
		catch(_com_error *e)
		{
			TRACE( e->ErrorMessage() );
			return hResult;
		}
		m_bOpened = TRUE;
		return hResult;
	};
	
	BOOL IsEmpty()
	{
		if ( !m_bOpened )
		{
			return TRUE;
		}
		
		if ( m_pRecordset->adoBOF && m_pRecordset->adoEOF )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	};
	
	BOOL Bof()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		return m_pRecordset->adoBOF;
	};
	
	BOOL Eof()
	{
		if ( !m_bOpened )
		{
			return TRUE;
		}
		return m_pRecordset->adoEOF;
	};
	
	HRESULT MoveFirst()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}

		return m_pRecordset->MoveFirst();
	};

	HRESULT MoveLast()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}

		return m_pRecordset->MoveLast();
	};

	HRESULT MoveNext()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		return m_pRecordset->raw_MoveNext();
	};

	HRESULT MovePrevious()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}

		return m_pRecordset->MovePrevious();
	};

	HRESULT Update()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
//		TRACE("bbbbbbbbbb\n");
//		return m_pRecordset->Update();
		return m_pRecordset->raw_Update();
	};

	HRESULT AddNew()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}

		return m_pRecordset->AddNew();
	};

	HRESULT Delete()
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}

		return m_pRecordset->Delete( adAffectCurrent );
	};

	BOOL GetFieldByName( char* pszField, short& sValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			sValue = var.iVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, WORD& wValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			wValue = var.uiVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, int& iValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}

		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			iValue = var.intVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, UINT& uiValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			uiValue = var.uintVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, long& lValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			lValue = var.lVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, DWORD& dwValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			dwValue = var.ulVal;
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, double& fValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			fValue = var.dblVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL GetFieldByName( char* pszField, CString& strVal )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			strVal = var.bstrVal;
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	}

	BOOL GetFieldByName( char* pszField, char* pszValue, DWORD dwSize )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );

			CString strVal = var.bstrVal;
			if ( strVal.GetLength() > dwSize )
			{
				strncpy( pszValue, strVal.GetBuffer( strVal.GetLength() ), dwSize );
			}
			else
			{
				strcpy( pszValue, strVal.GetBuffer( strVal.GetLength() ) );
			}
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	}

	BOOL GetFieldByName( char* pszField, COleDateTime& dtValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			_variant_t var;
			var = m_pRecordset->GetCollect( pszField );
			dtValue = var.date;
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, short sValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%d", sValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
//			AfxMessageBox( e->ErrorMessage() );
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, WORD wValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%u", wValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, int iValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%d", iValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, UINT uiValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%u", uiValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, long lValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%d", lValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	}

	BOOL SetFieldByName( char* pszField, DWORD dwValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%u", dwValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, double fValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			CString strVal;
			strVal.Format( "%f", fValue );
			m_pRecordset->PutCollect( pszField, _variant_t( strVal ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, CString strValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			m_pRecordset->PutCollect( pszField, _variant_t( strValue ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, char* pszValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			m_pRecordset->PutCollect( pszField, _variant_t( pszValue ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};

	BOOL SetFieldByName( char* pszField, COleDateTime dtValue )
	{
		if ( !m_bOpened )
		{
			return FALSE;
		}
		
		try
		{
			m_pRecordset->PutCollect( pszField, _variant_t( dtValue ) );
		}
		catch (_com_error* e)
		{
			TRACE(e->ErrorMessage());
			return FALSE;
		}
		return TRUE;
	};
};

#endif	//NULLS_ADOQUERY_H