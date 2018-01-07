<style>
    .notifyDiv{
        background-color: #B5EBE0;  
    }
    .clsTextbox {
        float: none;
        margin-right: 20px;
        //        width: 20%;
    }
    .searchBox .form-group {
        margin: 0px;
    }
    .empty{
        text-align: center;
    }
</style>
<?php
/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;


$this->title = 'Subscribed Users';
$this->params['breadcrumbs'][] = ['label' => 'Subscribed Users', 'url' => ['user_list']];
$this->params['breadcrumbs'][] = $this->title;


echo "<h1 class='box-title'>$this->title </h1>";
echo "<div class='participation-border fl-left all-userlst'>";
$phpdateformat = Yii::getAlias('@phpdateformat');
?>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'email',
            'ip_address',
            [
                'attribute' => 'added_on',
                'label' => 'Created Date',
                'format' => ['date', 'php:'.$phpdateformat]
            ],
        ],
    ]);

    ?>
                