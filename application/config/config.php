<?php
define('TITLE','CareerHub');

define('AES_KEY','4034ewewfejiooi3');

define('APP_NAME','cs633grp5');

$env = getenv('CURRENT_SYSTEM');
define('CURRENT_SYSTEM', empty($env) ? 'PROD' : $env);

//	DB information
define('DB_NAME','cs633');
define('DB_PORT',3306); 

switch(CURRENT_SYSTEM) { 
  case 'PROD':
    define('APPURL', 'http://www.itpmproject.com/');
    define('DB_HOST','localhost');
    define('DB_USER','cs633');
    define('DB_PASS','cs633grp5');
    define('URLIGNORE','');
    break;
      
  case 'DEV_LJ':  
  default:
    define('APPURL', 'http://localhost/cs633grp5/');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root'); 
    define('DB_PASS', 'hellocar');   
    define('URLIGNORE','cs633grp5');
    break;
    
  case 'DEV_LJ_APPLE':
    define('APPURL', 'http://localhost:8888/cs633grp5/');
    define('DB_HOST', 'localhost:8888');
    define('DB_USER', 'root'); 
    define('DB_PASS', 'hellocar');    
    define('URLIGNORE','cs633grp5');    
    break;
    
  case 'TINA_LOCAL':
    define('APPURL', 'http://dev.cs633.com/');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'cs633'); 
    define('DB_PASS', 'cs633grp5');    
    define('URLIGNORE','');    
    break;
}

// define the file path for this project  
define('DOC_ROOT',$_SERVER['DOCUMENT_ROOT'].'/'.URLIGNORE); 
  
//	User constraints
define('USERNAME_MIN',6);
define('USERNAME_MAX',15);
define('USERNAME_ALPHA',1);
define('USERNAME_NUM',1);
define('USERNAME_UNDERSCORE',1);
define('USERNAME_SPACES',0);
define('USERNAME_SPECCHARS',0);

define('PASSWORD_MIN',8);
define('PASSWORD_MAX',20);
define('PASSWORD_ALPHA',1);
define('PASSWORD_NUM',1);
define('PASSWORD_UNDERSCORE',1);
define('PASSWORD_SPACES',0);
define('PASSWORD_SPECCHARS',1);

//	Temp password settings (in minutes)
define('TMPPASSWORD_EXPIRY',30);