<?php
$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;
if ($action=='employeesearch2') {
	$bcA['/employeesearch.php']='Employee Search';
	$title='Employee Search Results';
} else $title='Employee Search';

include_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');


include($_SERVER['DOCUMENT_ROOT'].'/app/models/Model.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/app/models/EmployeeModel.php');

$empl=getEmployee($_SESSION['employee_id']);

if ($action=='employeesearch2') {
	include_once($_SERVER['DOCUMENT_ROOT'].'/app/views/EmployeeSearchResultsView.php');

} elseif (is_null($action)) {
	include_once($_SERVER['DOCUMENT_ROOT'].'/app/views/EmployeeSearchFormView.php');

}
include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');