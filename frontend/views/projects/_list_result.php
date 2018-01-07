<?php
// YOUR_APP/views/list/_list_item.php

use yii\helpers\Html;
use frontend\models\ProjectParticipation;
use frontend\models\Projects;

$projects = new Projects();

//$projectDetails = ProjectParticipation::getProjectNameRef($model->project_ref_id, $model->user_ref_id);
$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<article class="" data-key="<?php echo $model->project_ref_id; ?>">
    <table border="0" cellpadding="0" cellspacing="0"  class="parti-table Projects-Participated-Me">
        <tr>
            <td rowspan="7" width="35%" class="table-align-midl">
            <?php  
            //echo Yii::$app->request->BaseUrl.'/uploads/project_images/'.$model->project_id.'/'. $model->document_name;
            //echo Yii::$app->basePath.'\web\uploads\project_images\\'.$model->project_id.'\\'. $model->document_name;
                if(!empty($model->document_name) && Yii::$app->basePath.'\web\uploads\project_images\\'.$model->project_id.'\\'. $model->document_name)
                    $projectImageUrl = Yii::$app->request->baseUrl .'/uploads/project_images/'.$model->project_id.'/'. $model->document_name;
                else
                    $projectImageUrl = Yii::$app->request->baseUrl .'/uploads/project_images/no_project_image_small.jpg';
               
                //$projectImageUrl = Yii::$app->request->baseUrl .'/uploads/project_images/'.$model->project_id.'/'. $model->document_name;
            ?>
                <img src="<?php echo $projectImageUrl; ?>" class="img-responsive respim"/>
            </td>
        
        <td width="65%" class="bbbb">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="inner-parti-table">
        <tr>
            <td width="35%" class="parti-table-label">Project Name</th>
            <td width="65%" class="parti-table-fill proj">
                <span data-toggle="tooltip" title="<?php echo $model->project_title ;?>"><?php echo (strlen($model->project_title)>30) ? substr($model->project_title,0,20).'....' : $model->project_title;?></span>
            </td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Project Owner</th>
            <td width="65%" class="parti-table-fill">
                <?php
                    $username = ProjectParticipation::getProjectNameRef($model->project_id, $model->user_ref_id);
                    echo $username ? $username[0]['fname'] : '';
                    echo $username ? ' '.$username[0]['lname'] : '';
                ?>
            </td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Participation Type </th> 
            <td width="65%" class="parti-table-fill"><?php echo Html::encode($model->participation_type) == 'Invest' ? 'Cash' : 'Kind'; ?></td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Investment Type </th> 
            <td width="65%" class="parti-table-fill"><?php echo $model->investment_type; ?></td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Amount </th> 
            <td width="65%" class="parti-table-fill"><?php echo $model->amount; ?></td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Equity Type </th> 
            <td width="65%" class="parti-table-fill"><?php echo $model->equity_type; ?></td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Interest Rate </th> 
            <td width="65%" class="parti-table-fill"><?php echo $model->interest_rate; ?></td>
        </tr>
        <tr>
            <td width="35%" class="parti-table-label">Participation Date </th> 
            <td width="65%" class="parti-table-fill"><?php echo date($phpdateformat,strtotime($model->created_date)) ?></td>
        </tr>
        
        </table>
        </td>
        
        </tr>
    </table>
</article>
