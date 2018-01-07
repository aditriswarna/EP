<?php if($is_coowner == 1) { ?>
<p>You are the co-owner of the project <strong><?php echo $pname; ?></strong></br>You have access to view and access the project details.</p>
<?php } else { ?>
<p>You have declined to be a co-owner of the project <strong><?php echo $pname; ?></strong></p>
<?php } ?>
