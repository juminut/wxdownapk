<?php
/*
由于微信限制下载apk文件。以前通用的办法是，当用户提供下载apk的地址时，通过页面提示，让客户通过自己的浏览器打开。这样感觉用户操作很繁琐，而且有些用户不会操作。

出于此目的，在实际应用中，发现应用宝的提供的下载apk链接，当点击下载，如果是在微信中，会自动弹出用户手机的浏览器进行下载，如果不是微信，则可以直接下载。
此程序的目的，就是获取链接地址。

实现功能

1、微信通过获取的链接，可以直接弹出窗口下载。

2、利用应用宝的服务器，用户下载apk的速度更加快速，用户体验更好。


基本原理

通过获取自己的应用在应用宝的展现地址（地址不随软件更新而改变），然后通过程序分析出当前最新版本的下载地址。

程序实现思路

应用程序的下载地址在展现地址内，可以通过正则表达式来匹配，但是实际测试效率不高，此程序通过字符串截取，获得包含下载地址的部分json。然后进行解析，获得最新的下载地址。


实现过程概述

1、寻找自己的apk程序，打开http://sj.qq.com/myapp/ ，寻找自己的APP。复制找到自己的app页面地址。

2、使用谷歌浏览器，按F12，ctrl+shift+m，进入手机网页调试模式。浏览上一步获得的地址。获得新地址。

3、将新地址作为以后获取下载地址的参数地址。

4、调用geturl函数，返回($versionCode,$versionName,$apkUrl)。

5、可以对返回的url中的fsname进行修改，展现的文件名。
*/
function geturl($url){
	$str=curl_get($url);
	$regs=explode('window.AppInfoData=',$str);
	$regs=explode(';',$regs[1]);
	$regs=json_decode($regs[0]);
	$regs= isset($regs->appDetail)?$regs->appDetail:"";
	$versionCode=isset($regs->versionCode)?$regs->versionCode:"";
	$versionName= isset($regs->versionName)?$regs->versionName:"";
	$apkUrl= isset($regs->apkUrl)?$regs->apkUrl:"";
	if($regs=="" or $versionCode=="" or $versionName=="" or $apkUrl==""){
		return ;
	}
	return array($versionCode,$versionName,$apkUrl);
}

function curl_get($url){
	$ch = curl_init();     
	curl_setopt($ch,CURLOPT_URL,$url);     
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
	curl_setopt($ch,CURLOPT_HEADER,1);
		
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		
	$result = curl_exec($ch);
	curl_close($ch);  
	return $result;  
}
?>
