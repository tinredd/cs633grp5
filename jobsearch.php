<?php
$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;
if ($action=='employeesearch2') {
	$bcA['/jobsearch.php']='Job Search';
	$title='Job Search Results';
} else $title='Job Search';

include_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');


include($_SERVER['DOCUMENT_ROOT'].'/app/models/Model.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/app/models/JobModel.php');

$empl=getEmployee($_SESSION['employee_id']);

if ($action=='employeesearch2') {
	include_once($_SERVER['DOCUMENT_ROOT'].'/app/views/SearchResultsView.php');

} elseif (is_null($action)) {
	include_once($_SERVER['DOCUMENT_ROOT'].'/app/views/SearchFormView.php');

}
include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');