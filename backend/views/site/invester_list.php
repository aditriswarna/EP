<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Projects;
use frontend\models\ProjectCategory;
use yii\helpers\ArrayHelper;
?>
<div class="divOverflow pop-table">
    <?php
    //echo $dataProvider->getTotalCount();
    if ($dataProvider->getCount() > 0) {
        echo yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{sorter}\n{pager}\n{items}",
            'columns' => [
                /*  ['class' => 'yii\grid\SerialColumn'], */

                //['attribute' => 'category_name', 'value' => 'project_category.category_name'],

                'username',
                //'participation_type',
                [
                    'attribute' => 'participation_type',
                    'label' => 'Participation',
                    'value' => function ($data) {
                        return $data['participation_type'];
                    }
                ],
                [
                    'attribute' => 'investment_type',
                    'label' => 'Invest Type',
                    'value' => function ($data) {
                        return !empty($data['investment_type']) ? $data['investment_type'] : "";
                    }
                ],
                /* [
                  'attribute' => 'equity_type',
                  'value' => function ($data) {
                  return !empty($data['equity_type']) ? $data['equity_type'] : "";
                  }
                  ], */
                [
                    'attribute' => 'amount',
                    'value' => function ($data) {
                        return !empty($data['amount']) ? abs($data['amount']) : "";
                    }
                ],
                /*  [
                  'attribute' => 'interest_rate',
                  'value' => function ($data) {
                  return !empty($data['interest_rate']) ? $data['interest_rate'] : "";
                  }
                  ], */
                //'created_date',
                [
                    'attribute' => 'created_date',
                    'label' => 'Date',
                    'value' => function ($data) {
                        return $data['created_date'];
                    }
                ],
            //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    } else {
        echo "You are not authorized to view investor's list";
    }
    ?>
</div>
