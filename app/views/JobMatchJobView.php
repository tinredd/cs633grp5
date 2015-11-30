<div class="section_title">Job Details</div>
<div class="form_row title_row">
    <div>&nbsp;</div>
    <div>
        <div><a href="<?php echo APPURL ?>job.php?action=modify&amp;job_id=<?php echo $row['job_id'];?>"><?php echo stripslashes($row['job_title']);?></a></div>
    </div>
</div>

<div class="form_row">
    <div>Office name:</div>
    <div>
        <div><?php echo stripslashes($row['office_name'].' - '.$row['office_id'].' ('.$row['city'].', '.$row['state'].')');?></div>
    </div>
</div>

<div class="form_row">
    <div>Salary:</div>
    <div>
        <div><?php
        if ($row['salary']>0) echo '$'.number_format($row['salary'],2);
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Experience required:</div>
    <div>
        <div><?php
        if (strlen(trim($row['years_experience']))>0) echo round($row['years_experience'],1).' years';
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Education required:</div>
    <div>
        <div><?php
        if (strlen(trim($row['degree']))>0) echo stripslashes($row['degree']);
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Skill(s):</div>
    <div>
        <div><?php
        if (strlen(trim($row['skillset']))>0) echo stripslashes($row['skillset']);
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>
