﻿+----------------------------------+
 品牌空间系统简介
+----------------------------------+
品牌空间是康盛创想(北京)科技有限公司（英文简称Comsenz）推出的一套通用的商户软件系统。作为国内最大的社区软件及服务提供商，Comsenz旗下的品牌空间产品，无论在功能、稳定性、负载能力、安全保障等方面都居于国内外同类产品领先地位，是全球成熟度最高、覆盖率最大的商户软件系统之一。

品牌空间 V1.2 20100915

+----------------------------------+
 品牌空间的环境需求
+----------------------------------+
1. 可用的 www 服务器，如 Nginx、Apache、IIS 等
2. php 5.0 及以上
3. MySQL 4.1 及以上

+----------------------------------+
 品牌空间的安装
+----------------------------------+
1. 在服务器上新建一个品牌空间的文件夹（如brand），上传 upload 目录中的文件到该目录
2.设置目录属性（windows 服务器可忽略这一步）
    以下这些目录需要可读写权限
    ./data 含子目录
    ./attachments 含子目录
3.运行安装程序，比如你要安装品牌空间的访问地址为：
http://www.yourwebsite.com/brand ，那么在浏览器地址栏应该填写为：
http://www.yourwebsite.com/brand/install/index.php
4.参照页面提示，进行安装，直至安装完毕。
5.注意删除 /install/ 文件夹

+----------------------------------+
 品牌空间的升级
+----------------------------------+
1. 上传 upload 目录中的文件到服务器上的品牌空间文件夹
2. 设置目录属性（windows 服务器可忽略这一步）
        以下这些目录需要可读写权限
        ./data 含子目录
        ./attachments 含子目录
3. 将 /utility/update.php 上传至服务器上的品牌空间根目录
        请在浏览器中运行更新程序，即访问 http://您的域名/品牌空间目录/update.php
4. 参照页面提示，进行更新，直至安装完毕
5. 注意删除 /install/ 文件夹，以及 update.php文件
6. 请务必注意：
打开config.php ，搜索：$_SC['siteurl'] ，在这行后面增加：
$_SC['local'] = '';                         //所在的地区，地图服务默认显示地区。

例如：
$_SC['local'] = '北京市海淀区';                 //所在的地区，地图服务默认显示地区。
这里设置地图默认显示的地区。

7. 升级之后，管理员需要到管理后台》全局》更新缓存》点击“更新信息统计”，请在此逐个提交每项信息统计。

+----------------------------------+
升级V1.2之后需要注意的事项：
+----------------------------------+

一、V1.2更改了点评模型的关联方式，点评模型在店铺根分类下关联，不再与店铺组关联，因此各位站长升级到V1.2之后，需要重新在店铺根分类下关联点评模型。大家可以放心，之前对各个店铺的点评数据都会一一保留，不会丢失。

二、V1.2增加了推送信息到Discuz!X1.5的功能：当您为某个店铺绑定版块（群组）ID之后，该商家在发布商品、相册、消费券、团购、公告信息的同时，系统会自动将这些信息作为帖子发布到相应版块（群组）。

使用四部曲：

1：在品牌空间里设置论坛数据库信息
      品牌空间后台》全局》论坛设定，设置论坛版本，论坛访问地址，论坛数据库地址，论坛数据库账号，论坛数据库密码，论坛数据库名，论坛数据库字符集，论坛数据库表前缀。

2：确定版块（群组）ID：
     请确定某一个（或者多个）店铺的信息要推送到论坛的哪个版块（群组），并找到这个版块（群组）的ID；

3：在品牌空间里绑定ID：
      品牌空间后台》店铺管理》编辑某个店铺，在“绑定版块或群组的ID”填写您找好的ID，然后点击提交；

4：告之商家激活账号：
      请告之商家提前到论坛激活账号，否则发布到论坛的帖子将无法正常显示。

+----------------------------------+
 品牌空间的注意事项
+----------------------------------+

1.需要提前安装好 UCenter 和 Discuz!
    Discuz!X1 的 UCenter 在 Discuz! 安装目录的 uc_server 文件夹中

2.版本说明：将论坛图片导入品牌空间的功能目前只适用于 Discuz!7.2 ，今后会融合 Discuz!X ，除此功能外，对 Discuz! 版本没有限制。

3.安装品牌空间之后，请进入：品牌空间后台 => 全局 => 站点设置 => 用户注册URL（BBS注册地址），设置用户注册地址，否则用户无法点击注册。

4.如使用系统生成消费券的功能并且内容中含有中文，需要安装中文字体
方法：请将中文ttf字体上传到 static/image/fonts/ch/文件夹，并在后台选择使用该中文字体。
使用方法：管理员后台》》全局》》站点设置》》是否开启系统生成消费劵图片，选择是》》图片中文本 TrueType 字体文件名，选择字体。

5.Google地图功能需要申请API KEY，请按GoogleMapAPIKey.pdf文件提示申请，仅支持一个域名。

6.如果您的论坛使用的是 Discuz!X 版本，安装品牌空间之后，请务必首先更新 UCenter 缓存，然后更新 Discuz!X 缓存，否则会出现无法同步登录的情况。


+----------------------------------+
 品牌空间的技术支持
+----------------------------------+
当您在安装、升级、日常使用当中遇到疑难，请您到以下站点获取技术支持。

讨论区：          http://www.discuz.net
