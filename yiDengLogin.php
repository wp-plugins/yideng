<?php
/*
Plugin Name: 易登
Plugin URI: http://open.ciweimibao.com
Description: 易登——让注册登录变得极其容易与优雅，用易登来集成注册、登录、修改密码与下线。
Version: 1.0.0
Author: 杨国宝
Author URI: http://www.yangguobao.cn
*/

/*  Copyright 2015  杨国宝  (email : dsgygb@126.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as phttp://scuinfo.com/Public/js/CiWei_Authorize.jsublished by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//<script src="http://ciweimibao.oss-cn-hangzhou.aliyuncs.com/js/CiWei_Authorize.js?AS_ID='.get_option('ciWeiAppId').'"></script>
//<script src="http://scuinfo.com/Public/js/CiWei_Authorize.js?AS_ID='.get_option('ciWeiAppId').'"></script>
define('YIDENG_URL',home_url().'/?yiDengToken');
/**
 * 导入管理设置页面代码
 */
require_once('yiDengSetting.php');
if(!function_exists('yiDeng_login')){
    function yiDeng_login(){
      echo '<style> #yiDengLoginButton{
  height: 30px;
  line-height: 28px;
  padding: 0 12px 2px;
    vertical-align: baseline;
    cursor: pointer;
  border-width: 1px;
  border-style: solid;
border-radius: 3px;
  white-space: nowrap;
  box-sizing: border-box;
  text-decoration: none;
background: #1e8cbe;
  border-color: #0074a2;
  -webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,.6);
  box-shadow: inset 0 1px 0 rgba(120,200,230,.6);
  color: #fff;}
  #yiDengLoginDiv{
  height:40px;
  }
  }</style><div id="yiDengLoginDiv"><button id="yiDengLoginButton" type="button"> 使用刺猬密保登录</button></div>
<script src="http://scuinfo.com/Public/Scripts/CiWei_Authorize.js?AS_ID='.get_option('yiDengAppId').'"></script>
<script>
        document.getElementById("yiDengLoginButton").onclick=function(){
         var redirect = (document.referrer?document.referrer.replace("&","_"):"'.get_home_url().'");
            CiWei_Authorize({
                type:      1,
                fields:    "username,password"
            }, {
            Check_URL:    "'.YIDENG_URL.'=true&token=",
                Complete:     function (result) {
                if(result.code==0){
                    window.location.href=redirect;
                    //result.redirect_to;
                }else{
                    alert(result.message);
                }
            }
            });
           // return false;
        };
</script>
      ';
    }
}
if(get_option('yiDengAppId')) {
    add_action('login_form', 'yiDeng_login');
}

add_action('parse_request', 'yiDeng_router');
function yiDeng_router(){
    if( isset($_GET['yiDengRegister'])){
        require('yiDengHandle.php');
    }

    if( isset($_GET['yiDengChangePassword'])){
        require('yiDengHandle.php');
    }

    if( isset($_GET['yiDengLogout'])){
        require('yiDengHandle.php');
    }


    if( isset($_GET['yiDengToken'])){

        require('yiDengToken.php');
    }
}




?>