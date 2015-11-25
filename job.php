<?php
$includes=false;
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;

if (strlen(trim($postA['dir']))>0) $dir=$postA['dir'];
else $dir='ASC';

//	Contains all of the basic functions...
include($_SERVER['DOCUMENT_ROOT'].'/app/models/Model.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/models/JobModel.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/controllers/JobController.php');

if ($_REQUEST['action']=='add') $title="Add a Job";
elseif ($_REQUEST['action']=='modify') $title="Update Job";
else $title="List Jobs";

if (in_array($_REQUEST['action'],array('add','modify'))) $bcA['/job.php']='List Jobs';

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/views/JobView.php');

$columns=array(
'job_title'=>'Job title',
'degree'=>'Degree',
'salary'=>'Salary',
'office_name'=>'Office name',
'skillset'=>'Skill(s)',
'status'=>'Status',
);

//	The page views of the Job page
if (in_array($action,array('add','modify'))) {
	echo form($job_id,$_REQUEST['e'],$action);
} else {
	echo tabularize($_POST,$dir,$columns);
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>