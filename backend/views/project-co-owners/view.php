<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectCoOwners */

$this->title = $model->project_co_owner_id;
$this->params['breadcrumbs'][] = ['label' => 'Project Co Owners', 'url' => ['index?id='.$model->project_ref_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-co-owners-view">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model->project_co_owner_id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->project_co_owner_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'project_co_owner_id',
            'project_ref_id',
            'user_ref_id',
            'created_by',
            'created_date',
            'modified_by',
            'modified_date',
        ],
    ]) ?>

</div>
