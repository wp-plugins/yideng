<?php

if (!defined('YIDENG_HOME_URL')) define('YIDENG_HOME_URL',home_url());
add_action('admin_menu', 'yiDeng_plugin_menu') ;

// Here you can check if plugin is configured (e.g. check if some option is set). If not, add new hook. 
// In this example hook is always added.
add_action( 'admin_notices', 'yiDeng_plugin_admin_notices' );

function yiDeng_plugin_menu() {
    // Add the new admin menu and 页面 and save the returned hook suffix
    $hook_suffix = add_options_page('易登配置', '易登设置', 'manage_options', 'yiDeng.php', 'yiDeng_plugin_options');
    // Use the hook suffix to compose the hook and register an action executed when plugin's options 页面 is loaded
    add_action( 'load-' . $hook_suffix , 'yiDeng_load_function' );
}

function yiDeng_load_function() {
    // Current admin 页面 is the options 页面 for our plugin, so do not display the notice
    // (remove the action responsible for this)
    remove_action( 'admin_notices', 'yiDeng_plugin_admin_notices' );
}

function yiDeng_plugin_admin_notices() {
    if(!get_option('yiDengAppId')) {
        echo '<div id="notice" class="updated fade"><p>请先在设置-><a href="'.admin_url('options-general.php?page=yiDeng.php','http').'">易登配置</a> 中配置易登.</p></div>\n';
    }
}
function yiDengTopbarmessage($msg) {
    echo '<div class="updated fade" id="message"><p>' . $msg . '</p></div>';
}
function getYiDengOption()
{
    $yiDengOptions = array();
    $yiDengOptions['appId']=get_option('yiDengAppId');
    $yiDengOptions['appSecret']=get_option('yiDengAppSecret');
    if(get_option('yiDengApiUrl')){
        $yiDengOptions['apiUrl']=get_option('yiDengApiUrl');
    }else{
        $yiDengOptions['apiUrl']='http://api.ciweimibao.com';
    }

    return $yiDengOptions;
}
function updateYiDengOption()
{
    if ($_POST['action'] == '保存设置') {
        update_option('yiDengAppId', $_POST['yiDengAppId']);
        update_option('yiDengAppSecret', $_POST['yiDengAppSecret']);
        update_option('yiDengApiUrl', $_POST['yiDengApiUrl']);
    }
    yiDengTopbarmessage('恭喜，更新配置成功');
}
function addYiDengDefaultOption(){
    update_option('yiDengApiUrl','http://api.ciweimibao.com');
}
register_activation_hook(__FILE__,'addYiDengDefaultOption');
function yiDengSettingPage()
{
    ?>
    <style type="text/css">
        h2 {
            height: 36px;
            line-height: 36px;
        }

        label {
            display: inline-block;
            font-weight: bold;
        }

        textarea {
            width: 450px;
            height: 80px;
        }

        input {
            width: 450px;
            height: 30px;
        }

        table {
            border: 0px solid #ececec;
        }

        tr {
            margin: 20px 0px;
        }

        .right {
            vertical-align: top;
            padding-top: 10px;
            width: 120px;
            text-align: right;
        }

        .left {
            width: 500px;
            padding-left: 50px;
            text-align: left;
        }

        .wxp-logo {
/*            background: url(*/<?php //echo WXP_URL; ?>/*/images/weixin-big.png) 0px 0px no-repeat;*/
            background-size: 36px 36px;
            height: 36px;
            width: 36px;
            float: left;
        }

        .wxp-notes {
            margin: 10px 0px 30px 0px;
            display: inline-block;
            width: 450px;
        }

        .wxp-submit-btn {
            height: 30px;
            width: 150px;
            background-color: #21759b;
            font-weight: bold;
            color: #ffffff;
            font-family: "Microsoft YaHei";
        }

        .wxp-center {
            text-align: center;
        }

        .wxp-btn-box {
            margin: 15px 0px;
        }

        .wxp-option-main {
            margin: 5px 0px;
            width: 650px;
            float: left;
        }
        .yiDengTips{
            padding-left: 20px;
            line-height: 1.3;
        }

        .wxp-option-sidebar {
            width: 100px;
            float: left;
        }

        .sidebar-box {
            border: 1px solid #dfdfdf;
            width: 200px;
            border-radius: 3px;
            box-shadow: inset 0 1px 0 #fff;
            background-color: #f5f5f5;
        }

        .sidebar-box h3 {
            font-size: 15px;
            font-weight: bold;
            padding: 7px 10px;
            margin: 0;
            line-height: 1;
            background-color: #f1f1f1;
            border-bottom-color: #dfdfdf;
            text-shadow: #fff 0 1px 0;
            box-shadow: 0 1px 0 #fff;
        }

        .sidebar-box a {
            padding: 4px;
            display: block;
            padding-left: 25px;
            text-decoration: none;
            border: none;
        }
    </style>

    <div class="wxp-option-container">
    <div class="wxp-header">
        <div class="wxp-logo"></div>
        <h2>易登设置</h2>
    </div>
    <?php
    if (isset($_POST['action'])) {
        if ($_POST['action'] == '保存设置') {

           updateYiDengOption();
        }
    }
    $yiDengOptionInfo = getYiDengOption();
    ?>
    <div class="wxp-option-main">

    <form name="wxp-options" method="post" action="">
        <div class="yiDengTips">
            <p>如果没有在易登创建过应用，请先在<a target="_blank" href="http://open.ciweimibao.com">易登开放平台</a>创建一个应用。</p>
            <p>获取appId和appSecret后，将appId和appSecret填写在下面，并将以下回调网址复制后填写在易登开放平台的接口设置中:</p>
            <p>注册接口回调网址:<pre><code><?php echo YIDENG_HOME_URL.'/?yiDengRegister';?></code></pre></p>
            <p>修改密码接口回调网址:<pre><code><?php echo YIDENG_HOME_URL.'/?yiDengChangePassword';?></code></pre></p>
            <p>下线接口回调网址:<pre><code><?php echo YIDENG_HOME_URL.'/?yiDengLogout';?></code></pre></p>
        </div>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td class="right"><label>易登api网址：</label></td>
            <td class="left">
                <input type="text" name="yiDengApiUrl" value="<?php echo $yiDengOptionInfo['apiUrl']; ?>"/>
                <span class="wxp-notes">测试阶段为方便开发者调试启用</span>
            </td>
        </tr>
    <tr>
        <td class="right"><label>易登appId：</label></td>
        <td class="left">
            <input type="text" name="yiDengAppId" value="<?php echo $yiDengOptionInfo['appId']; ?>"/>
            <span class="wxp-notes">填写在易登开放平台获取的appId</span>
        </td>
    </tr>
    <tr>
        <td class="right"><label>刺猬appSecret：</label></td>
        <td class="left">
            <input type="text" name="yiDengAppSecret" value="<?php echo $yiDengOptionInfo['appSecret']; ?>"/>
            <span class="wxp-notes">填写在易登开发哪个平台获取的appSecret(重要不可泄露)</span>
        </td>
    </tr>
        <tr>
            <td colspan="2" class="wxp-center wxp-btn-box">
                <input type="submit" class="wxp-submit-btn" name="action" value="保存设置"/>
            </td>
        </tr>
    </table>
    </form>
    </div>
    </div>
<?php
}
function yiDeng_plugin_options() {
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    yiDengSettingPage();

}
?>