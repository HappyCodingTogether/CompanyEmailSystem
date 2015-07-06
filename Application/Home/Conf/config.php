<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'companyemailsystem', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  false, // 数据库调试模式 开启后可以记录SQL日志
    'DOWNLOAD_UPLOAD' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 20*1024*1024, //上传的文件大小限制 (0-不做限制)
        'autoSub'  => true, //自动子目录保存文件
        'subName'  =>  array('date','Y-m'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => 'Uploads/Download/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //下载模型上传配置（文件上传类配置）
    'UPLOAD_FILE_EXT'=>'ppt,pptx,xls,xlsx,jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,pdf', //允许上传的文件后缀
);