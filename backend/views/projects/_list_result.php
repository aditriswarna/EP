<?php
// YOUR_APP/views/list/_list_item.php

use yii\helpers\Html;
use backend\models\ProjectParticipation;
use backend\models\Projects;

$projects = new Projects();

$projectDetails = ProjectParticipation::getProjectNameRef($model->project_ref_id, $model->user_ref_id);
?>

<article data-key="<?php echo $model['project_ref_id'] ?>">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td rowspan="7" width="30%">
            <?php  
                if(!empty($projects->project_image) && file_exists(Yii::$app->basePath.'/web/uploads/project_images/'.$projects->project_id.'/'. $projects->project_image))
                    $projectImageUrl = Yii::$app->request->baseUrl .'/uploads/project_images/'.$projects->project_id.'/'. $projects->project_image;
                else
                    $projectImageUrl = Yii::$app->request->baseUrl .'/uploads/project_images/no_project_image.jpg';
            ?>
                <img src="<?php echo $projectImageUrl; ?>" />
            </td>
        </tr>
        <tr>
            <th>Project Name</th>
            <td>
                <h4><?php echo $projectDetails[0]['project_title']; ?></h4>
            </td>
        </tr>
        <tr>
            <th>Username</th>
            <td>
                <?php
                    $projectDetails = ProjectParticipation::getProjectNameRef($model->project_ref_id, $model->user_ref_id);
                    echo $projectDetails[0]['username'];
                ?>
            </td>
        </tr>
        <tr>
            <th width="20%">Participation Type </th> 
            <td><?php echo Html::encode($model['participation_type']); ?></td>
        </tr>
        <tr>
            <th>Investment Type </th> 
            <td><?php echo $model['investment_type'] ?></td>
        </tr>
        <tr>
            <th>Amount </th> 
            <td><?php echo $model['amount'] ?></td>
        </tr>
        <tr>
            <th>Interest Rate </th> 
            <td><?php echo $model['interest_rate'] ?></td>
        </tr>
        <tr>
            <td colspan="2"><hr size="1"></td>
        </tr>
    </table>
</article>