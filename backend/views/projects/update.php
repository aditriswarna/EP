<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Update Projects: ' . ' ' . $model->project_title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project_id, 'url' => ['view', 'project_id' => $model->project_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="projects-update">

    <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'projectParticipationData' => $projectParticipationData,
        'projectCategories' => $projectCategories,
        'projectTypes' => $projectTypes,
        'userDetails' => $userDetails,
        'allProjectImages'=>@$allProjectImages,
        'allProjectDocuments'=>@$allProjectDocuments,
          'allProjectVidoes'=> @$allProjectVidoes
    ]) ?>

</div>
