<?php

require_once('includes/bootstrap.php');

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;
if ($action=='employeesearch2') {
	$bcA['/jobsearch.php']='Job Search';
	$title='Job Search Results';
} else $title='Job Search';

include_once(DOC_ROOT.'/includes/header.php');


require_once(DOC_ROOT.'/app/models/Model.php');
include_once(DOC_ROOT.'/app/models/JobModel.php');

$empl=getEmployee($_SESSION['employee_id']);

if ($action=='employeesearch2') {
	include_once(DOC_ROOT.'/app/views/SearchResultsView.php');

} elseif (is_null($action)) {
	include_once(DOC_ROOT.'/app/views/SearchFormView.php');

}
require_once(DOC_ROOT.'/includes/footer.php');