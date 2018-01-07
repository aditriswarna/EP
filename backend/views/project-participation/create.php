<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */

$this->title = 'Create Project Participation';
//$this->params['breadcrumbs'][] = ['label' => 'Project Participations', 'url' => ['index?id='.$project->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-participation-create">

    <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'project' => $project,
        'users' =>$users
    ]) ?>

</div>
