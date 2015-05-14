<?php

define('ENV', 'development');

define('BASE_URL', 'http://localhost:8080/workshift');
define('DB_CONNECTION', 'mysql:host=127.0.0.1;dbname=workshift');
define('DB_USER', 'root');
define('DB_PWD', '');
define('DB_CHARSET', 'utf8');
define('DB_TABLE_PREFIX', '');

define('SITE_NAME', 'Workshift WebApp');
define('LOCALE', 'en');
define('THEME', 'default');

define('ADMIN_THEME', 'admin');

define('WEEKEND', 1);
define('PUBLIC_HOLIDAY', 2);    //Holidays
define('COMBINE_DAY', 3);   //weekend meet public holiday

define('NORMAL_HOLIDAYS', '');
define('BEGIN_YEAR', '2013');
define('CURRENT_MONTH', date('m', time()));
define('CURRENT_YEAR', date('Y', time()));
define('CURRENT_DAY', date('d', time()));
define('LIMIT_RECORDS', 30);

