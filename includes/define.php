<?php
define('TITLE','Title Here');

//	DB information
define('DB_HOST','localhost');
define('DB_USER','cs633');
define('DB_PASS','cs633grp5');
define('DB_NAME','cs633');
define('DB_PORT',3306);

define('AES_KEY','4034ewewfejiooi3');

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

?>