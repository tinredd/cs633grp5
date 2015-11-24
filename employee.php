<?php
$includes=false;
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

$action=(isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;

if (strlen(trim($postA['dir']))>0) $dir=$postA['dir'];
else $dir='ASC';

//	Contains all of the basic functions...
include($_SERVER['DOCUMENT_ROOT'].'/app/models/EmployeeModel.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/controllers/EmployeeController.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/views/EmployeeView.php');

if ($action=='add') $title="Add an Employee";
elseif ($action=='modify') $title="Update Employee";
else $title="List Employees";

if (in_array($action,array('add','modify'))) $bcA['/employee.php']='List Employees';

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

$columns=array(
'last_name'=>'Last name',
'first_name'=>'First name',
'employee_id'=>'Employee ID',
'office_name'=>'Office name',
'hire_date'=>'Hire date',
'email_address'=>'Email address',
'office_phone'=>'Office phone',
'job_title'=>'Job title',
'skillset'=>'Skill(s)',
'status'=>'Status',
);

//	The page views of the Employee page
if (in_array($action,array('add','modify'))) {
	echo form($employee_id,$_REQUEST['e'],$action);
} else {
	echo tabularize($_POST,$dir,$columns);
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>