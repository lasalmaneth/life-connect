<?php 

if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost'){
    if(!defined('ROOT')) define('ROOT','http://localhost/life-connect');
    /**
     * database config
     */
    if(!defined('DBNAME')) define('DBNAME','life-connect');
    if(!defined('DBHOST')) define('DBHOST','localhost');
    if(!defined('DBUSER')) define('DBUSER','root');
    if(!defined('DBPASS')) define('DBPASS','');
    if(!defined('DBDRIVER')) define('DBDRIVER','');
}else{
    if(!defined('ROOT')) define('ROOT', 'https://www.websitename.com');

    if(!defined('DBNAME')) define('DBNAME','life-connect');
    if(!defined('DBHOST')) define('DBHOST','localhost');
    if(!defined('DBUSER')) define('DBUSER','root');
    if(!defined('DBPASS')) define('DBPASS','');
    if(!defined('DBDRIVER')) define('DBDRIVER','');
}

if(!defined('APP_NAME')) define('APP_NAME',"My website");
if(!defined('APP_DESC')) define('APP_DESC',"this is best website in world");

//true mean show errors
if(!defined('DEBUG')) define('DEBUG',true);

/**
 * Security Config
 */
if(!defined('ENC_KEY')) define('ENC_KEY', 'l1f3-c0nn3ct-s3cr3t-k3y-2026-v1');
