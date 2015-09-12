<?php header('Content-type: text/html; charset=utf-8'); 
      

// define the core paths
// define them as absolute paths to make sure that require_once works as expected


defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', realpath(__DIR__ .DS.'..'));
  
    

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'inc');

// load config file first
require_once(LIB_PATH.DS.'config_db.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'singleton.php');
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'crud_object.php');


if (DB_ENGINE== 'ORACLE') {
    require_once(LIB_PATH.DS.'oracle_database.php');
    require_once(LIB_PATH.DS.'oracle_crud_object.php');
} else if (DB_ENGINE == 'MYSQL'){
    require_once(LIB_PATH.DS.'mysql_database.php');
    require_once(LIB_PATH.DS.'mysql_crud_object.php');
}

//end of lib-related classes inlude

// load own database-related classes
require_once(LIB_PATH.DS.'user.php');

