/****************************************************************
Copyright (C), 2012-2014,  *** Tech. Co., Ltd.
File name:   IniFile.h
Author:      Feb
Version:     DS1.0
Date:        2012.01
Description: ini文件相关处理
******************************************************************/
#ifndef _READ_WRITE_CONFIG_FILE_H
#define _READ_WRITE_CONFIG_FILE_H

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define SuccessRet 1;
#define FailedRet  0;

#define MAX_CFG_BUF                              512 

#define CFG_OK                                     0 
#define CFG_SECTION_NOT_FOUND                     -1 
#define CFG_KEY_NOT_FOUND                         -2 
#define CFG_ERR                                  -10 
#define CFG_ERR_FILE                             -10 
#define CFG_ERR_OPEN_FILE                        -10 
#define CFG_ERR_CREATE_FILE                      -11 
#define CFG_ERR_READ_FILE                        -12 
#define CFG_ERR_WRITE_FILE                       -13 
#define CFG_ERR_FILE_FORMAT                      -14 
#define CFG_ERR_SYSTEM                           -20 
#define CFG_ERR_SYSTEM_CALL                      -20 
#define CFG_ERR_INTERNAL                         -21 
#define CFG_ERR_EXCEED_BUF_SIZE                  -22 

#define COPYF_OK                                   0 
#define COPYF_ERR_OPEN_FILE                      -10 
#define COPYF_ERR_CREATE_FILE                    -11 
#define COPYF_ERR_READ_FILE                      -12 
#define COPYF_ERR_WRITE_FILE                     -13 

#define TXTF_OK                                    0 
#define TXTF_ERR_OPEN_FILE                        -1 
#define TXTF_ERR_READ_FILE                        -2 
#define TXTF_ERR_WRITE_FILE                       -3 
#define TXTF_ERR_DELETE_FILE                      -4 
#define TXTF_ERR_NOT_FOUND                        -5 

#define CFG_ssl  '['
#define CFG_ssr  ']'                 /*项标志符Section Symbol --可根据特殊需要进行定义更改，如 { }等*/
#define CFG_nis  ':'                 /*name 与 index 之间的分隔符 */
#define CFG_nts  ';'                 /*注释符,只能在行首进行注释*/

class CIniFile  
{
    
public:
    CIniFile();
    virtual ~CIniFile();

    //去除字符串右边的空字符
    char * strtrimr(char * buf);
	
	//去除字符串左边的空字符
    char * strtriml(char * buf);

	//从文件中读取一行
    int  FileGetLine(FILE *fp, char *buffer, int maxlen);

	//分离key和value
    int  SplitKeyValue(char *buf, char **key, char **val);

	//文件拷贝
    int  FileCopy(void *source_file, void *dest_file);

	//分离section为name和index
    int  SplitSectionToNameIndex(char *section, char **name, char **index);

	//合成name和indexsection为section
    int  JoinNameIndexToSection(char **section, char *name, char *index);

	//获得key的值
    int  ConfigGetKey(void *CFG_file, void *section, void *key, void *buf);

	//设置key的值
    int  ConfigSetKey(void *CFG_file, void *section, void *key, void *buf);

	//获得所有section
    int  ConfigGetSections(void *CFG_file, char *sections[]);

	//获得所有key的名字
    int  ConfigGetKeys(void *CFG_file, void *section, char *keys[]);

    int  CFG_section_line_no, CFG_key_line_no, CFG_key_lines;     
};

#endif // !defined(AFX_INIFILE_H__998CAFDF_E1B8_4DD5_B29A_1877455FE305__INCLUDED_)
