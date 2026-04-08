<?php 

if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost'){
    define('ROOT','http://localhost/life-connect');
    /**
     * database config
     */
    define('DBNAME','life-connect');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER','');
}else{
    define('ROOT', 'https://www.websitename.com');

    define('DBNAME','life-connect');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER','');
}

define('APP_NAME',"My website");
define('APP_DESC',"this is best website in world");

//true mean show errors
define('DEBUG',true);

/**
 * Security Config
 */
define('ENC_KEY', 'l1f3-c0nn3ct-s3cr3t-k3y-2026-v1');
