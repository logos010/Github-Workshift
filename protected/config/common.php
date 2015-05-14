<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => SITE_NAME,
    'language' => LOCALE,
    'theme' => THEME,
//    'preload' => array('log'),
    'import' => array(
        'application.models.base.*',
        'application.includes.core.*',
        'application.includes.utils.*',
        'application.modules.role.models.*',
        'application.modules.user.models.*',
        'application.modules.userrole.models.*',
        'application.vendors.phpexcel.PHPExcel',
    ),
    'modules' => array(
        'registration',
        'role',
        'user' => array(
//            'debug' => false
        ),
        'userrole',
        'profile' => array(
            'privacySettingTable' => 'privacysetting',
            'profileFieldTable' => 'profile_field',
            'profileTable' => 'profile',
            'profileCommentTable' => 'profile_comment',
            'profileVisitTable' => 'profile_visit',
        ),
    ),
    'components' => array(
        'cache' => array(
            'class' => 'system.caching.CMemCache',
        ),
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => DB_CONNECTION,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PWD,
            'charset' => DB_CHARSET,
            'tablePrefix' => DB_TABLE_PREFIX,
//            'initSQLs'=>'SET NAMES utf8 ;',
        ),
        'user' => array(
            'class' => 'application.modules.user.components.YumWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('//user/user/login'),
            'returnUrl' => array('//user/user/index'),
        ),
        'mail' => array(
            'class' => 'ext.mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host' => 'smtp.gmail.com',
                'username' => 'no-reply@eswebsoft.com',
                'password' => 'eswebsoft.com',
                'port' => '465',
                'encryption' => 'ssl',
            ),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => true,
            'rules' => array(
                '/' => 'site/index',
                '<controller:\w+>/<id:\d+>' => '<controller>view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
        ),
        'log' => array(
            'class' => 'CLogRouter',
        )
    ),
);