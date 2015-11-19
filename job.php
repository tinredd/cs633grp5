<?php
$includes=false;
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');
if ($_POST['action']=='add2') {

} elseif ($_POST['action']=='modify2') {

} elseif ($_POST['action']=='delete2') {

}

if ($_REQUEST['action']=='add') $title="Add a Job";
elseif ($_REQUEST['action']=='add') $title="Update Job";
elseif (!isset($_REQUEST['action'])) $title="List Jobs";

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

if (in_array($_REQUEST['action'],array('add','modify'))) {

} elseif (!isset($_REQUEST['action'])) {

}


include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>