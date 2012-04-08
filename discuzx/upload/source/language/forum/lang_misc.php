<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_misc.php 29327 2012-04-01 09:37:17Z liudongdong $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array
(
	'discuz_lang' => 'misc',
	'contact' => '联系方式:',
	'anonymous' => '匿名',
	'anonymoususer' => '匿名者',
	'guestuser' => '游客',
	'has_expired' => '该信息已经过期',
	'click_view' => '点击查看',
	'never_expired' => '永不过期',
	'sort_update' => '更新',
	'sort_upload' => '上传',
  	'view_noperm' => '隐藏内容',
	'post_hidden' => '**** 本内容被作者隐藏 ****',
	'post_banned' => '**** 作者被禁止或删除 内容自动屏蔽 ****',
	'post_single_banned' => '**** 该帖被屏蔽 ****',
	'message_ishidden_hiddenreplies' => '此帖仅作者可见',
	'post_reply_quote' => '{author} 发表于 {time}',
	'post_edit' => "[i=s] 本帖最后由 {editor} 于 {edittime} 编辑 [/i]\n\n",
	'post_edit_regexp' => '/^\[i=s\] 本帖最后由 .*? 于 .*? 编辑 \[\/i\]\n\n/s',
	'post_edithtml' => '[i=s] 本帖最后由 {editor} 于 {edittime} 编辑 [/i]<br /><br />',
	'post_edithtml_regexp' => '/^\[i=s\] 本帖最后由 .*? 于 .*? 编辑 \[\/i\]&lt;br \/&gt;&lt;br \/&gt;/s',
	'post_editnobbcode' => '[ 本帖最后由 {editor} 于 {edittime} 编辑 ]\n\n',
	'post_editnobbcode_regexp' => '/^\[ 本帖最后由 .*? 于 .*? 编辑 \]\n\n/s',
	'post_reply' => '回复',
	'post_thread' => '的帖子',

	'price' => '售价',
	'pay_view' => '记录',
	'attachment_buy' => '购买',

	'post_trade_yuan' => '元',
	'post_trade_seller' => '卖家',
	'post_trade_name' => '商品名称',
	'post_trade_price' => '商品价格',
	'post_trade_quality' => '商品成色',
	'post_trade_locus' => '所在地点',
	'post_trade_transport_type' => '物流方式',
	'post_trade_transport_seller' => '卖家承担运费',
	'post_trade_transport_buyer' => '买家承担运费',
	'post_trade_transport_mail' => '平邮',
	'post_trade_transport_express' => '快递',
	'post_trade_transport_virtual' => '虚拟物品或无需邮递',
	'post_trade_transport_physical' => '买家收到货物后直接支付给物流公司',
	'post_trade_locus' => '所在地点',
	'post_trade_description' => '商品描述',
	'post_trade_pm_subject' => '[议价]',
	'post_trade_pm_buynum' => '购买数量',
	'post_trade_pm_wishprice' => '我期望的价格是',
	'post_trade_pm_reason' => '我议价的理由是',
	'postappend_content' => '补充内容',
	'payment_unit' => '元',

	'attach' => '附件',
	'attach_pay' => '收费附件',
	'attach_credits_policy' => '查看积分策略说明',
	'attach_img' => '图片附件',
	'attach_readperm' => '阅读权限',
	'attach_img_zoom' => '点击在新窗口查看全图\\nCTRL+鼠标滚轮放大或缩小',
	'attach_img_thumb' => '点击在新窗口查看全图',
	'attach_downloads' => '下载次数',

	'post_trade_transport' => '邮费',
	'post_trade_transport_mail' => '平邮',
	'post_trade_quality' => '商品成色',
	'post_trade_quality_new' => '全新',
	'post_trade_quality_secondhand' => '二手',

	'trade_unstart' => '<font color="gray">未生效的交易</font>',
	'trade_waitbuyerpay' => '等待买家付款',
	'trade_waitsellerconfirm' => '交易已创建，等待卖家确认',
	'trade_sysconfirmpay' => '确认买家付款中，暂勿发货',
	'trade_waitsellersend' => '买家已付款，等待卖家发货',
	'trade_waitbuyerconfirm' => '卖家已发货，买家确认中',
	'trade_syspayseller' => '买家确认收到货，等待支付宝打款给卖家',
	'trade_finished' => '<font color="green">交易成功结束</font>',
	'trade_closed' => '<font color="gray">交易中途关闭(未完成)</font>',
	'trade_waitselleragree'  => '等待卖家同意退款',
	'trade_sellerrefusebuyer' => '卖家拒绝买家条件，等待买家修改条件',
	'trade_waitbuyerreturn' => '卖家同意退款，等待买家退货',
	'trade_waitsellerconfirmgoods' => '等待卖家收货',
	'trade_waitalipayrefund' => '双方已经一致，等待支付宝退款',
	'trade_alipaycheck' => '支付宝处理中',
	'trade_overedrefund' => '结束的退款',
	'trade_refundsuccess' => '<font color="green">退款成功</font>',
	'trade_refundclosed' => '<font color="green">退款关闭</font>',

	'trade_offline_1' => '交易单生效',
	'trade_offline_4' => '我已付款，等待卖家发货',
	'trade_offline_5' => '我已发货',
	'trade_offline_7' => '我收到货，交易成功结束',
	'trade_offline_8' => '取消此次交易',
	'trade_offline_10' => '我要退货，等待卖家同意退款',
	'trade_offline_11' => '卖家拒绝退款',
	'trade_offline_12' => '卖家同意退款',
	'trade_offline_13' => '我已退货，等待卖家收货',
	'trade_offline_17' => '卖家收到退货，已退款',

	'trade_message_4' => '可输入付款方式、银行账号等信息',
	'trade_message_5' => '可输入发货公司、发货单号等信息',
	'trade_message_13' => '可输入发货公司、发货单号等信息',

	'credit_payment' => '积分充值',
	'credit_forum_payment' => '论坛积分充值',
	'credit_forum_royalty' => '交易手续费',

	'credit_total' => '总积分',

	'invite_payment' => '购买邀请码',
	'invite_forum_payment' => '购买邀请码',
	'invite_forum_payment_unit' => '个',
	'invite_forum_royalty' => '交易手续费',

	'formulaperm_regdate' => '注册时间',
	'formulaperm_regday' => '注册天数',
	'formulaperm_regip' => '注册 IP',
	'formulaperm_lastip' => '最后登录 IP',
	'formulaperm_buyercredit' => '买家信用评价',
	'formulaperm_sellercredit' => '卖家信用评价',
	'formulaperm_digestposts' => '精华帖数',
	'formulaperm_posts' => '发帖数',
	'formulaperm_threads' => '主题数',
	'formulaperm_oltime' => '在线时间(小时)',
	'formulaperm_pageviews' => '页面浏览量',
	'formulaperm_and' => '并且',
	'formulaperm_or' => '或者',
	'formulaperm_extcredits' => '自定义积分',

	'login_normal_mode' => '在线',
	'login_switch_invisible_mode' => '切换在线状态',
	'login_switch_normal_mode' => '我要上线',
	'login_invisible_mode' => '隐身',

	'eccredit_explain' => '解释',

	'google_site_0' => '网页搜索',
	'google_site_1' => '站内搜索',
	'google_sa' => '搜索',

	'modcp_logs_action_home' => '内部留言',
	'modcp_logs_action_moderate' => '审核',
	'modcp_logs_action_member' => '用户管理',
	'modcp_logs_action_forumaccess' => '用户权限',
	'modcp_logs_action_thread' => '主题管理',
	'modcp_logs_action_forum' => '版块管理',
	'modcp_logs_action_announcement' => '公告',
	'modcp_logs_action_log' => '管理日志',
	'modcp_logs_action_stat' => '管理统计',

	'modcp_logs_action_login' => '登录',

	'uch_selectalbum' => '请选择相册',
	'uch_noalbum' => '抱歉，您还没有相册，',
	'click_here' => '点击这里',
	'uch_createalbum' => '创建自己的相册吧！',

	'pm_from' => '发件人',
	'pm_to' => '收件人',
	'pm_date' => '日期',

	'share_message' => '您好！我在 {$_G[setting][bbname]} 看到了这篇帖子，认为很有价值，特推荐给您。\\n\\n$thread[subject]\\n地址 [url={$threadurl}]{$threadurl}[/url]\\n\\n希望您能喜欢',

	'week_0' => '星期日',
	'week_1' => '星期一',
	'week_2' => '星期二',
	'week_3' => '星期三',
	'week_4' => '星期四',
	'week_5' => '星期五',
	'week_6' => '星期六',

	'notice_actor' => '等 $actorcount 人',

	'perms_allowvisit' => '访问论坛',
	'perms_readaccess' => '阅读权限',
	'perms_allowviewpro' => '查看用户资料',
	'perms_allowinvisible' => '隐身',
	'perms_allowsearch' => '使用搜索',
	'perms_allownickname' => '使用昵称',
	'perms_allowcstatus' => '自定义头衔',
	'perms_allowpost' => '发新话题',
	'perms_allowreply' => '发表回复',
	'perms_allowpostpoll' => '发起投票',
	'perms_allowvote' => '参与投票',
	'perms_allowpostreward' => '发表悬赏',
	'perms_allowpostactivity' => '发表活动',
	'perms_allowpostdebate' => '发表辩论',
	'perms_allowposttrade' => '发表交易',
	'perms_maxsigsize' => '最大签名长度',
	'perms_allowsigbbcode' => '签名中使用编辑器代码',
	'perms_allowsigimgcode' => '签名中使用 [img] 代码',
	'perms_maxbiosize' => '自我介绍最大长度',
	'perms_allowrecommend' => '主题评价影响值',
	'perms_allowbiobbcode' => '自我介绍中使用编辑器代码',
	'perms_allowbioimgcode' => '自我介绍中使用 [img] 代码',
	'perms_allowgetattach' => '下载附件',
	'perms_allowgetimage' => '查看图片',
	'perms_allowpostattach' => '上传附件',
	'perms_allowpostimage' => '上传图片',
	'perms_allowsetattachperm' => '允许设置附件权限',
	'perms_maxspacesize' => '空间大小',
	'perms_maxattachsize' => '单个最大附件尺寸',
	'perms_maxsizeperday' => '每天最大附件总尺寸',
	'perms_maxattachnum' => '每天最大附件数量',
	'perms_allowbioimgcode' => '自我介绍中使用 [img] 代码',
	'perms_attachextensions' => '附件类型',
	'perms_allowstickthread' => '主题置顶',
	'perms_allowdigestthread' => '主题精华',
	'perms_allowstickthread_value' => '置顶',
	'perms_allowdigestthread_value' => '精华',
	'perms_allowbumpthread' => '提升主题',
	'perms_allowhighlightthread' => '主题高亮',
	'perms_allowrecommendthread' => '主题推荐',
	'perms_allowstampthread' => '主题鉴定',
	'perms_allowclosethread' => '主题关闭',
	'perms_allowmovethread' => '主题移动',
	'perms_allowedittypethread' => '编辑主题分类',
	'perms_allowcopythread' => '主题复制',
	'perms_allowmergethread' => '主题合并',
	'perms_allowsplitthread' => '主题分割',
	'perms_allowrepairthread' => '主题修复',
	'perms_allowrefund' => '强制退款',
	'perms_alloweditpoll' => '编辑投票',
	'perms_allowremovereward' => '移除悬赏',
	'perms_alloweditactivity' => '管理活动',
	'perms_allowedittrade' => '管理商品',
	'perms_alloweditpost' => '编辑帖子',
	'perms_allowwarnpost' => '警告帖子',
	'perms_allowbanpost' => '屏蔽帖子',
	'perms_allowdelpost' => '删除帖子',
	'perms_allowviewreport' => '查看用户报告',
	'perms_allowmodpost' => '审核帖子',
	'perms_allowmoduser' => '审核用户',
	'perms_allowbanuser' => '禁止用户',
	'perms_allowbanip' => '禁止 IP',
	'perms_allowedituser' => '编辑用户',
	'perms_allowmassprune' => '批量删帖',
	'perms_allowpostannounce' => '发布公告',
	'perms_disablepostctrl' => '发帖不受限制',
	'perms_allowviewip' => '允许查看 IP',
	'perms_viewperm' => '浏览版块',
	'perms_postperm' => '发新话题',
	'perms_replyperm' => '发表回复',
	'perms_getattachperm' => '下载附件',
	'perms_postattachperm' => '上传附件',
	'perms_postimageperm' => '上传图片',
	'perms_allowblog' => '发表日志',
	'perms_allowdoing' => '发表记录',
	'perms_allowupload' => '上传图片',
	'perms_allowshare' => '发布分享',
	'perms_allowpoke' => '允许打招呼',
	'perms_allowfriend' => '允许加好友',
	'perms_allowclick' => '允许表态',
	'perms_allowmyop' => '允许使用应用',
	'perms_allowcomment' => '发表留言/评论',
	'perms_allowstatdata' => '查看统计数据报表',
	'perms_allowstat' => '允许查看趋势统计',
	'perms_allowpostarticle' => '发表文章',
	'perms_raterange' => '允许参与评分',
	'perms_allowcommentpost' => '允许参与点评',
	'perms_allowat' => '允许 @ 的人数',
	'perms_allowreplycredit' => '允许设置回帖奖励',
	'perms_allowposttag' => '允许使用标签',
	'perms_allowcreatecollection' => '允许创建淘专辑的数量',
	'perms_allowsendpm' => '允许发短消息',
	'perms_maximagesize' => '单张图片最大尺寸',
	'perms_allowmediacode' => '允许使用多媒体代码',

	'join_topic' => '参与话题',
	'join_poll' => '参与投票',
	'buy_trade' => '购买商品',
	'join_reward' => '参与悬赏',
	'join_activity' => '参与活动',
	'join_debate' => '参与辩论',
	'at_invite' => '@我的好友',

	'lower' => '低于',
	'higher' => '高于',
	'report_msg_your' => '您的 ',
	'report_noreward' => '不奖惩',
	'activity_viewimg' => '点击查看',

	'crime_postreason' => '{reason} &nbsp; <a href="forum.php?mod=redirect&goto=findpost&pid={pid}&ptid={tid}" target="_blank" class="xi2">查看详情</a>',
	'crime_reason' => '{reason}',

	'connectguest_message_search' => array('尚未登录', '先登录'),
	'connectguest_message_replace' => array('尚未 <a href="member.php?mod=connect" class="xi2">完善帐号信息</a> 或 <a href="member.php?mod=connect&ac=bind" class="xi2">绑定已有帐号</a> ', '先 <a href="member.php?mod=connect" class="xi2">完善帐号信息</a> 或 <a href="member.php?mod=connect&ac=bind" class="xi2">绑定已有帐号</a> '),
	'connectguest_message_mobile_search' => array('尚未登录', '先登录'),
	'connectguest_message_mobile_replace' => '在电脑版完善资料或绑定已有帐号，',

	'avatar' => '头像',
	'signature' => '签名',
	'custom_title' => '自定义头衔',

	'forum_guide' => '导读',

	'patch_site_have' => '您的网站有',
	'patch_is_fixed' => '个安全漏洞，已修复',
	'patch_need_fix' => '个安全漏洞，请尽快修复',
	'patch_fixed_status' => '已修复',
	'patch_unfix_status' => '未修复',
	'patch_fix_failed_status' => '修复失败',
	'patch_fix_right_now' => '立即修复',
	'patch_view_fix_detail' => '查看详情',
	'patch_name' => '漏洞名称',
	'patch_dateline' => '发布日期',
	'patch_status' => '当前状态',
	'patch_close' => '关闭',

);

?>