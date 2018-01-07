<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */

$this->title = 'Update Project Participation: ' . ' ' . $model->project_participation_id;
$this->params['breadcrumbs'][] = ['label' => 'Project Participations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project_participation_id, 'url' => ['view', 'id' => $model->project_participation_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="project-participation-update title-log">

    <h1 class="box-title"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'project' => $project
    ]) ?>

</div>
