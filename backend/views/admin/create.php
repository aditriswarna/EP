<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Create Admin User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['user_list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1 class="box-title"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'types' => $types,
    ]) ?>

</div>
