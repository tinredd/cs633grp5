<?php

require_once('includes/bootstrap.php');

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;

if (strlen(trim($postA['dir']))>0) $dir=$postA['dir'];
else $dir='ASC';

//	Contains all of the basic functions...
require_once(DOC_ROOT.'/app/models/Model.php');
require_once(DOC_ROOT.'/app/models/JobModel.php');
require_once(DOC_ROOT.'/app/controllers/JobController.php');

if ($_REQUEST['action']=='add') $title="Add a Job";
elseif ($_REQUEST['action']=='modify') $title="Update Job";
else $title="List Jobs";

if (in_array($_REQUEST['action'],array('add','modify'))) $bcA['/job.php']='List Jobs';

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
	echo form($job_id,$_REQUEST['e'],$action);
} else {
	echo tabularize($_POST,$dir,$columns);
}

require_once(DOC_ROOT.'/includes/footer.php');
?>