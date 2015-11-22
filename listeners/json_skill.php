<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

$rowA=array();
$entered=$_REQUEST['entered'];

$sql="SELECT * FROM skill WHERE skill_name LIKE '%{$_REQUEST['letter']}%'"; 
if (count($entered)>0) $sql.=" AND skill_id NOT IN (".implode(",",$entered).")";
$sql.=" AND skill_status=1 ORDER BY skill_name";

$rs_row=$mysqli->query($sql);
while ($row=$rs_row->fetch_assoc()) {
	$rowA[]=array('value'=>$row['skill_id'],'label'=>$row['skill_name']);
}

echo json_encode($rowA);
?>