<div class="section_title">Matching Employees <span>(<?php echo count($empA);?>)</span></div>

<?php foreach ($empA as $rank=>$emp) { ?>

<div class="form_row title_row">
    <div>
        <div><?php echo ($rank+1);?></div>
    </div>
    <div>
        <div><a href="<?php echo APPURL ?>employee.php?action=modify&amp;employee_id=<?php echo $emp['employee_id'];?>"><?php echo stripslashes($emp['first_name'].' '.$emp['last_name']);?></a></div>
    </div>
</div>

<div class="form_row">
    <div>Office name:</div>
    <div>
        <div><?php echo stripslashes($emp['office_name'].' - '.$emp['office_id'].' ('.$emp['city'].', '.$emp['state'].')');?></div>
    </div>
</div>

<div class="form_row">
    <div>Email:</div>
    <div><?php if ($emp['hr_contact']==1){ ?>
        <div><a href="mailto:<?php echo $emp['email_address'];?>"><?php echo $emp['email_address'];?></a></div>
        <div><a href="mailto:<?php echo $emp['email_address'];?>" class="button">Contact this employee</a></div>
        <?php } else { ?>
            <div>
                <span class="italic" style="color: #FF0000;">This employee chooses not to be contacted for jobs!</span> 
            </div>
        <? } ?>
    </div>
</div>

<div class="form_row">
    <div>Hire date:</div>
    <div>
        <div><?php echo date('n/j/Y',strtotime($emp['hire_date']));?> (<?php echo $emp['years'];?> years)</div>
    </div>
</div>

<div class="form_row">
    <div>Skill(s):</div>
    <div>
        <div><?php
        if (count($emp['skillset'])>0) echo stripslashes(implode(', ',$emp['skillset']));
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>
<?php
    if ($rank<count($empA)-1) echo '
        <div class="form_row" style="margin:10px 25%; width:50%; border-bottom:dotted 1px #AAA;">
        </div>';
    } 
