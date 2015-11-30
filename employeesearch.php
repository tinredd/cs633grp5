<?php

require_once('includes/bootstrap.php');

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;
if ($action=='employeesearch2') {
	$bcA['/employeesearch.php']='Employee Search';
	$title='Employee Search Results';
} else $title='Employee Search';

include_once(DOC_ROOT.'/includes/header.php');


require_once(DOC_ROOT.'/app/models/Model.php');
include_once(DOC_ROOT.'/app/models/EmployeeModel.php');

$empl=getEmployee($_SESSION['employee_id']);

if ($action=='employeesearch2') {
	include_once(DOC_ROOT.'/app/views/EmployeeSearchResultsView.php');

} elseif (is_null($action)) {
	include_once(DOC_ROOT.'/app/views/EmployeeSearchFormView.php');

}
require_once(DOC_ROOT.'/includes/footer.php');