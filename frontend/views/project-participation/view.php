<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\SqlDataProvider;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */

$this->title = $model['project_participation_id'];
$this->params['breadcrumbs'][] = ['label' => 'Project Participations', 'url' => ['index?id='.$model['project_ref_id']]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="project-participation-view">

    <!--<h1><?php //echo Html::encode($this->title) ?></h1>-->
    <h1><?php echo Html::encode($model['project_title']) ?></h1>
    
    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model['project_participation_id']], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model['project_participation_id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    
    <?php echo DetailView::widget([
        'model' => $model,
        //'dataProvider' => $dataProvider,
        'attributes' => [
            //'project_participation_id',
            //'project_ref_id',
            //'project_title',
            //'user_ref_id',
            'username',           
            [
                'attribute'=>'participation_type',
                'label'=>'Participation Type',
                'value' => !empty($model['participation_type'])?$model['participation_type']:'-NA-'
            ],
            [
                'attribute'=>'investment_type',
                'label'=>'Investment Type',
                'value' => !empty($model['investment_type'])?$model['investment_type']:'-NA-'
            ],
            [
                'attribute'=>'equity_type',
                'label'=>'Equity Type',
                'value' => !empty($model['equity_type'])?$model['equity_type']:'-NA-'
            ],
            [
                'attribute'=>'amount',
                'label'=>'Amount',
                'value' => !empty($model['amount'])?$model['amount']:'-NA-'
            ],
            [
                'attribute'=>'interest_rate',
                'label'=>'Interest Rate',
                'value' => !empty($model['interest_rate'])?$model['interest_rate']:'-NA-'
            ],
            //'created_by',
            'created_date',
        ],
    ]) ?>

</div>
