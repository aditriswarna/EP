<?php
$phpdateformat = Yii::getAlias('@phpdateformat');
echo "<div class='subjectcontent'><div class='mailsubject'>".$maildata['subject']."</div><div class='mailtime'>".date($phpdateformat.' h:i:s A', strtotime($maildata['created_date']))."</div></div>";
echo '<br>';
echo "<div class='mailbody table-responsive'>".$maildata['message']."</div>";

                ?>
