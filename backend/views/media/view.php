<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MediaAgencies */

$this->title = $model->media_agency_name;
$this->params['breadcrumbs'][] = ['label' => 'Media Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<div class="media-agencies-view">

    <h1 class='box-title'><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // echo Html::a('Update', ['update', 'id' => $model->media_agency_id], ['class' => 'btn btn-primary']) ?>
        <?php /* echo Html::a('Delete', ['delete', 'id' => $model->media_agency_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'media_agency_id',
            'media_agency_name',
            [
                'attribute'=>'status',
                'value' => $model['status'] == '1'?'Active':'Inactive',  
            ],
            [
                'attribute'=>'created_date',       
                'format' => ['date', 'php:'.$phpdateformat]
            ],
           // 'status',
           // 'created_date',
          //  'created_by',
        ],
    ]) ?>

</div>
