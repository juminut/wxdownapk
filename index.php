<?php
/*
由于微信限制下载apk文件。以前通用的办法是，当用户提供下载apk的地址时，通过页面提示，让客户通过自己的浏览器打开。这样感觉用户操作很繁琐，而且有些用户不会操作。
出于此目的，在实际应用中，发现应用宝的提供的下载apk链接，当点击下载，如果是在微信中，会自动弹出用户手机的浏览器进行下载，如果不是微信，则可以直接下载。
此程序的目的，就是获取链接地址。
实现功能
1、微信通过获取的链接，可以直接弹出窗口下载。
2、利用应用宝的服务器，用户下载apk的速度更加快速，用户体验更好。

实现过程概述
1、寻找自己的apk程序，打开http://sj.qq.com/myapp/，寻找自己的APP。复制找到自己的app页面地址。
2、使用谷歌浏览器，按F12，ctrl+shift+m，进入手机网页调试模式。浏览上一步获得的地址。获得新地址。
3、将新地址作为以后获取下载地址的参数地址
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
