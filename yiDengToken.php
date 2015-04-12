<?php
require_once('yiDengAes.php');
/*
// 切换到当前的绝对目录
chdir( dirname(__FILE__) );
// 切换到Wordpress的根目录，加载WP-LOAD.PHP文件
if (chdir('../../../')){
    require_once( 'wp-load.php' );
}*/

if(!$_GET['token']){
    $return['code']=10086;
    $return['message']="没有token参数";
    $data=json_encode($return);
    echo $data;
    exit;
}
function yiDengHttpGet($url) {
    $oCurl = curl_init();
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        return false;
    }
}

header("Access-Control-Allow-Origin: *");

$aes = new yiDengAes(get_option('yiDengAppId'),get_option('yiDengAppSecret'));
$tokenEncode=$aes->yiDengEncode($_GET['token']);
$res=yiDengHttpGet(get_option('yiDengApiUrl')."/system/take?appId=".get_option('yiDengAppId')."&token=".$tokenEncode);
if(!$res){
    echo '';
    exit;
}
$result=json_decode($res,1);
if($result['retCode']==200){
    $t=$result['retData']['result'];
    $t2=json_decode($t,1);
        $yiDengLoginInfo = array();
        $yiDengLoginInfo['user_login'] = $t2['u'];
        $yiDengLoginInfo['user_password'] = $aes->yiDengDecode($t2['p']);
        $yiDengLoginInfo['remember'] = true;
        $user = wp_signon( $yiDengLoginInfo, false );
  //  file_put_contents('test4.txt',is_wp_error($user),FILE_APPEND);
    if ( !is_wp_error($user) ) {
        $return['code']=0;
        $data=json_encode($return);
        echo $data;
        exit();
    }else{
        $return['code']=10001;
        $return['message']=$user->get_error_message();
        $data=json_encode($return);
        echo $data;
        exit();
    }

}else{
    echo '';
    exit;
}
exit;

?>