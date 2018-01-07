<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project Co Owners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-co-owners-index">

    <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a('Create Project Co Owners', ['project-co-owners/create?id='.$project->project_id], ['class' => 'btn btn-success']) ?>
    </p>
<div class="participation-border fl-left">
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'project_co_owner_id',
            //'project_ref_id',
            //'project_title',
            //'user_ref_id',
            [
                'attribute' => 'fname',
                'label' => 'First Name',
            ],
            [
                'attribute' => 'lname',
                'label' => 'Last Name',
            ],
            'email',
            //'created_by',
            'created_date',
            // 'modified_by',
            // 'modified_date',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{delete}',
            /*'buttons'=>[
                        'update' => function ($url, $model) {     
                          return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                  'title' => Yii::t('yii', 'update'),
                          ]);                                

                        }
                      ],*/
                'urlCreator' => function ($action, $model, $key, $index) {
                  return yii\helpers\Url::toRoute([$action, 'id' => $model['project_co_owner_id']]);
                  /*if ($action === 'update') {
                      return yii\helpers\Url::toRoute(['update', 'id' => $model['project_co_owner_id']]);
                  } else {
                      return yii\helpers\Url::toRoute([$action, 'id' => $model['project_co_owner_id']]);
                  }*/
              }
            ],
        ],
    ]); ?>
    </div>

</div>
