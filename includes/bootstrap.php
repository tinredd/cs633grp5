<?php
       
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
             
require_once('application/config/config.php');

require_once(DOC_ROOT.'/core/classes/Database.php');
         
require_once(DOC_ROOT.'/core/functions/common.php');

require_once(DOC_ROOT.'/application/applocal/variables.php');

$mysqli = new Database();