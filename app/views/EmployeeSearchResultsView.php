<?php
	$empsA=getListing($_POST);

	$empA=array();
    $pointsA=array();
	foreach ($empsA as $emp) {
        if ($emp['employee_id']==$_SESSION['employee_id']) continue;
        if ($emp['employee_contact']==0) continue;

		$points=0; $commonskillset=array();
		if ($emp['office_id']==$empl['office_id']) $points++;
		if (isset($emp['skillids'])) $commonskillset=array_intersect(explode(',',$empl['skillids']),explode(',',$emp['skillids']));
		$points+=count($commonskillset);

		if (isset($emp['skillids']) && (count(explode(',',$emp['skillids']))==0 || count($commonskillset)>0) && $emp['office_id']==$empl['office_id']) $points++;

		$pointsA[]=$points;

		$empA[$emp['employee_id']]=$emp;
		if (isset($emp['skillids'])) $empA[$emp['employee_id']]['skillids']=explode(',',$emp['skillids']);
        else $empA[$emp['employee_id']]['skillids']=array();
        $tmpA=array();
        foreach (explode(',',$emp['skillset']) as $tmp) if (strlen(trim($tmp))>0) $tmpA[]=$tmp;
        $empA[$emp['employee_id']]['skillset']=$tmpA;
		$empA[$emp['employee_id']]['points']=$points;

	}

	array_multisort($pointsA,SORT_DESC,$empA);
?>
<div class="section_title">Matching Employees <span>(<?php echo count($empA);?>)</span></div>
<?php if (count($empA)==0) { ?><div class="error">No employees match your search criteria. Please <a href="<?php echo APPURL ?>employeesearch.php">search again</a>.</div><?php } ?>

<?php foreach ($empA as $rank=>$row) { ?>

<div class="form_row title_row">
    <div>
        <div><?php echo ($rank+1);?></div>
    </div>
    <div>
        <div><a href="javascript:void(0)"><?php echo stripslashes($row['first_name'].' '.$row['last_name']);?></a></div>
    </div>
</div>

<div class="form_row">
    <div>Office name:</div>
    <div>
        <div><?php echo stripslashes($row['office_name'].' - '.$row['office_id'].' ('.$row['city'].', '.$row['state'].')');?></div>
    </div>
</div>

<div class="form_row">
    <div>Email address:</div>
    <div>
		<div><a href="mailto:<?php echo $row['email_address'];?>"><?php echo stripslashes($row['email_address']);?></a></div>
		<div><a href="mailto:<?php echo $row['email_address'];?>" class="button">Contact Employee</a></div>
    </div>
</div>

<div class="form_row">
    <div>Phone number:</div>
    <div>
        <div><?php
        if (strlen(trim($row['office_phone']))>0) echo $row['office_phone'];
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Skill(s):</div>
    <div>
        <div><?php
        if (count($row['skillset'])>0) echo stripslashes(implode(', ',$row['skillset']));
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>
<?php
    if ($rank<count($empA)-1) echo '
        <div class="form_row" style="margin:10px 25%; width:50%; border-bottom:dotted 1px #AAA;">
        </div>';
    }
