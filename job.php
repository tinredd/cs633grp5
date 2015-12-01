<?php

require_once('includes/bootstrap.php');

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;

if (isset($_REQUEST['dir']) && strlen(trim($_REQUEST['dir']))>0) $dir=$_REQUEST['dir'];
else $dir='ASC';

//	Contains all of the basic functions...
require_once(DOC_ROOT.'/app/models/Model.php');
require_once(DOC_ROOT.'/app/models/JobModel.php');
require_once(DOC_ROOT.'/app/controllers/JobController.php');

if (isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action=null;

if ($action=='add') $title="Add a Job";
elseif ($action=='modify') $title="Update Job";
else $title="List Jobs";

if (in_array($action,array('add','modify'))) $bcA['/job.php']='List Jobs';

require_once(DOC_ROOT.'/includes/header.php');
require_once(DOC_ROOT.'/app/views/JobView.php');

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
	if (!isset($_REQUEST['e'])) $_REQUEST['e']=null;
	echo form($job_id,$_REQUEST['e'],$action);
} else {
	echo tabularize($_POST,$dir,$columns);
}

require_once(DOC_ROOT.'/includes/footer.php');
?>