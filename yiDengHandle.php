<?php
/**
 * Created by PhpStorm.
 * User: GuobaoYang
 * Date: 15/3/26
 * Time: 上午10:58
 */
require_once('yiDengAes.php');
// 切换到当前的绝对目录
/*

chdir( dirname(__FILE__) );
// 切换到Wordpress的根目录，加载WP-LOAD.PHP文件

if (chdir('../../../')){
    require_once( 'wp-load.php' );
}
*/
$aes = new yiDengAes(get_option('yiDengAppId'),get_option('yiDengAppSecret'));
if(isset($_GET['yiDengRegister'])){
    $password=$aes->yiDengDecode($_POST['password']);
    ;
if($password){
   $register = wp_create_user($_POST['username'],$password,$_POST['email']);
    if(!is_wp_error($register)){
        $return['retCode']=200;
        $return['retMsg']=$_POST['username'];
        $data=json_encode($return);
        echo $data;
        exit;
    }else{
        $return['retCode']=$register->get_error_code();
        $return['retMsg']=$register->get_error_message();
        $data=json_encode($return);
        echo $data;
        exit;
    };
}
}else if(isset($_GET['yiDengChangePassword'])){

    $password=$aes->yiDengDecode($_POST['password']);
    $oldPassword = $aes->yiDengDecode($_POST['oldPassword']);
    if($oldPassword && $password){
       $checkPassword = wp_authenticate_username_password(null, $_POST['username'], $oldPassword);
      if(!is_wp_error($checkPassword)){

        $userdata['ID']=username_exists($_POST['username']);
        $userdata['user_pass']=$password;
          $userUpdate = wp_update_user($userdata);

        if($userUpdate){
            $return['retCode']=200;
            $return['retMsg']=$_POST['username'];
            $data=json_encode($return);
            echo $data;
            exit;
        }else{
            $return['retCode']="修改失败";
            $return['retMsg']="密码修改失败";
            $data=json_encode($return);
            echo $data;
            exit;
        };

      }else{
          $return['retCode']=$checkPassword->get_error_code();
          $return['retMsg']=$checkPassword->get_error_message();
          $data=json_encode($return);
          echo $data;
          exit;
      }
    }
}else if(isset($_GET['yiDengLogout'])){
    $username=$aes->yiDengDecode($_POST['username']);
    $ID=username_exists($username);
    if($ID){
        $manager = WP_Session_Tokens::get_instance( $ID );
        $manager->destroy_all();
        $return['retCode']=200;
        $return['retMsg']='退出成功';
        $data=json_encode($return);
        echo $data;
        exit;
        }else{
        $return['retCode']=10086;
        $return['retMsg']='退出失败';
        $data=json_encode($return);
        echo $data;
        exit;
    }
}