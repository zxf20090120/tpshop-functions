<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if(!function_exists('encrypt_password')){
    //定义一个密码加密函数
    function encrypt_password($password)
    {
        //加盐方式
        $salt = 'dfdjakfalenfskdsl';
        return md5( md5($password) . $salt );
    }
}

if (!function_exists('remove_xss')) {
    //使用htmlpurifier防范xss攻击
    function remove_xss($string){
        //相对index.php入口文件，引入HTMLPurifier.auto.php核心文件
        require_once './plugins/htmlpurifier/HTMLPurifier.auto.php';
        // 生成配置对象
        $cfg = HTMLPurifier_Config::createDefault();
        // 以下就是配置：
        $cfg -> set('Core.Encoding', 'UTF-8');
        // 设置允许使用的HTML标签
        $cfg -> set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,br,p[style],span[style],img[width|height|alt|src]');
        // 设置允许出现的CSS样式属性
        $cfg -> set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置a标签上是否允许使用target="_blank"
        $cfg -> set('HTML.TargetBlank', TRUE);
        // 使用配置生成过滤用的对象
        $obj = new HTMLPurifier($cfg);
        // 过滤字符串
        return $obj -> purify($string);
    }
}

if (!function_exists('getTree')) {
    //递归方法实现无限极分类
    function getTree($list,$pid=0,$level=0) {
        static $tree = array();
        foreach($list as $row) {
            if($row['pid']==$pid) {
                $row['level'] = $level;
                $tree[] = $row;
                getTree($list, $row['id'], $level + 1);
            }
        }
        return $tree;
    }
}

if(!function_exists('get_cate_tree')){
    //递归方式实现 无限极分类树
    function get_cate_tree($list, $pid=0){
        $tree = array();
        foreach($list as $row) {
            if($row['pid']==$pid) {
                $row['son'] = get_cate_tree($list, $row['id']);
                $tree[] = $row;
            }
        }
        return $tree;
    }
}

if(!function_exists('curl_request')){
    //使用curl函数库发送请求
    function curl_request($url, $post = false, $params = [], $https = false){
        //调用curl_init()函数初始化请求会话
        $ch = curl_init($url);
        //调用curl_setopt()函数设置一些请求选项
        //post请求需要单独设置（默认发送get请求）
        if($post){
            //设置请求方式 post
            curl_setopt($ch, CURLOPT_POST, true);
            //设置请求参数
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        //请求协议处理  https
        if($https){
            //禁止从服务器验证本地客户端的证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        //使用curl_exec()函数 发送请求
        //设置 直接将返回参数 通过curl_exec进行返回
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        //关闭请求
        curl_close($ch);
        return $res;
    }
}


if(!function_exists('encrypt_phone')){
    //加密手机号的函数  15312345678  -> 153****5678
    function encrypt_phone($phone)
    {
        return substr($phone, 0, 3) . '****' . substr($phone, 7);
    }
}