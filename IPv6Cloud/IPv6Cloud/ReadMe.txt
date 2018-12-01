================================================================================
MICROSOFT 基础类库: IPv6Cloud 项目概述
===============================================================================

应用程序向导已为您创建了这个 IPv6Cloud 应用程序。此应用程序不仅演示 Microsoft 基础类的基本使用方法，还可作为您编写应用程序的起点。

本文件概要介绍组成 IPv6Cloud 应用程序的每个文件的内容。

IPv6Cloud.vcxproj
这是使用应用程序向导生成的 VC++ 项目的主项目文件。 
它包含生成该文件的 Visual C++ 的版本信息，以及有关使用应用程序向导选择的平台、配置和项目功能的信息。

IPv6Cloud.vcxproj.filters
    这是使用“应用程序向导”生成的 VC++ 项目筛选器文件。
    它包含有关项目文件与筛选器之间的关联信息。在 IDE 中，通过这种关联，在特定节点下以分组形式显示具有相似扩展名的文件。例如，“.cpp”文件与“源文件”筛选器关联。

IPv6Cloud.h
这是应用程序的主要头文件。它包括其他项目特定的头文件(包括 Resource.h)，并声明 CIPv6CloudApp 应用程序类。

IPv6Cloud.cpp
这是包含应用程序类 CIPv6CloudApp 的主要应用程序源文件。

IPv6Cloud.rc
这是程序使用的所有 Microsoft Windows 资源的列表。它包括 RES 子目录中存储的图标、位图和光标。此文件可以直接在 Microsoft Visual C++ 中进行编辑。项目资源位于 2052 中。

res\IPv6Cloud.ico
这是用作应用程序图标的图标文件。此图标包括在主要资源文件 IPv6Cloud.rc 中。

res\IPv6Cloud.rc2
此文件包含不在 Microsoft Visual C++ 中进行编辑的资源。您应该将不可由资源编辑器编辑的所有资源放在此文件中。


/////////////////////////////////////////////////////////////////////////////

应用程序向导创建一个对话框类:

IPv6CloudDlg.h，IPv6CloudDlg.cpp - 对话框
这些文件包含 CIPv6CloudDlg 类。该类定义应用程序主对话框的行为。该对话框的模板位于 IPv6Cloud.rc 中，该文件可以在 Microsoft Visual C++ 中进行编辑。


/////////////////////////////////////////////////////////////////////////////

其他功能:

ActiveX 控件
应用程序包括对使用 ActiveX 控件的支持。

/////////////////////////////////////////////////////////////////////////////

其他标准文件:

StdAfx.h，StdAfx.cpp
这些文件用于生成名为 IPv6Cloud.pch 的预编译头 (PCH) 文件和名为 StdAfx.obj 的预编译类型文件。

Resource.h
这是标准头文件，它定义新的资源 ID。
Microsoft Visual C++ 读取并更新此文件。

IPv6Cloud.manifest
	应用程序清单文件供 Windows XP 用来描述应用程序
	对特定版本并行程序集的依赖性。加载程序使用此
	信息从程序集缓存加载适当的程序集或
	从应用程序加载私有信息。应用程序清单可能为了重新分发而作为
	与应用程序可执行文件安装在相同文件夹中的外部 .manifest 文件包括，
	也可能以资源的形式包括在该可执行文件中。 
/////////////////////////////////////////////////////////////////////////////

其他注释:

应用程序向导使用“TODO:”指示应添加或自定义的源代码部分。

如果应用程序在共享的 DLL 中使用 MFC，则需要重新发布这些 MFC DLL；如果应用程序所用的语言与操作系统的当前区域设置不同，则还需要重新发布对应的本地化资源 MFC100XXX.DLL。有关这两个主题的更多信息，请参见 MSDN 文档中有关 Redistributing Visual C++ applications (重新发布 Visual C++ 应用程序)的章节。

/////////////////////////////////////////////////////////////////////////////
/*
<ListContainerElement height="25">
	<HorizontalLayout name="" text="" tooltip="" bordersize="1" bordercolor="#00808080">
		<VerticalLayout enabled="true" maxwidth="40" bordersize="1" bordercolor="#00808080">
			<Label text="01" enabled="true" maxwidth="40" textcolor="#FF000000" align="center" padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="65" bordersize="1" bordercolor="#00808080">
			<Label text="201591801" enabled="true" minwidth="65" textcolor="#FF000000" align="center" padding="0,4,0,0" />
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="65" bordersize="1" bordercolor="#00808080">
			<Label text="2015-09-18" enabled="true" minwidth="65" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="110" bordersize="1" bordercolor="#00808080">
			<Label text="root&13800000000" enabled="true" minwidth="110" textcolor="#FF000000" align="center" padding="0,4,0,0" />
		</VerticalLayout>
		<VerticalLayout enabled="true" maxwidth="40" bordersize="1" bordercolor="#00808080">
			<Label text="是" enabled="true" maxwidth="40" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" maxwidth="40" bordersize="1" bordercolor="#00808080">
			<Label text="是" enabled="true" maxwidth="40" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" maxwidth="40" bordersize="1" bordercolor="#00808080">
			<Label text="是" enabled="true" maxwidth="40" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="60" bordersize="1" bordercolor="#00808080">
			<Label text="1" enabled="true" minwidth="60" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="40" bordersize="1" bordercolor="#00808080">
			<Label text="10.0" enabled="true" minwidth="40" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="60" bordersize="1" bordercolor="#00808080">
			<Label text="正常" enabled="true" minwidth="60" textcolor="#FF000000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="60" bordersize="1" bordercolor="#00808080">
			<Label text="已支付" enabled="true" minwidth="60" textcolor="#FFff0000" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
		<VerticalLayout enabled="true" minwidth="65" bordersize="1" bordercolor="#00808080">
			<Label text="查看详情" enabled="true" minwidth="65" textcolor="#FF08AEEA" align="center"  padding="0,4,0,0"/>
		</VerticalLayout>
	</HorizontalLayout>
</ListContainerElement>
*/



