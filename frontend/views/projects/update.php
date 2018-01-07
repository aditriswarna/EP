<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Update Project: '; // . ' ' . $model->project_id;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project_id, 'url' => ['view', 'project_id' => $model->project_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
 <h1 class="box-title">Update Project<?php //echo Html::encode($this->title) ?></h1>
<div class="projects-update ">


    <?php echo $this->render('_form', [
        'model' => $model,
        'projectParticipationData' => $projectParticipationData,
        'projectCategories' => $projectCategories,
        'projectTypes' => $projectTypes,
        'userData'=>@$userData,
        'allProjectImages'=>@$allProjectImages,
        'allProjectDocuments'=>@$allProjectDocuments,
        'allProjectVidoes'=> @$allProjectVidoes,
    ]) ?>

</div>
