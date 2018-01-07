<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectCoOwners */

$this->title = 'Update Project Co Owners: ' . ' ' . $model->project_co_owner_id;
$this->params['breadcrumbs'][] = ['label' => 'Project Co Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project_co_owner_id, 'url' => ['view', 'id' => $model->project_co_owner_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="project-co-owners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
