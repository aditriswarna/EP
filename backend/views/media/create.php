<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MediaAgencies */

$this->title = 'Create Media Agencies';
$this->params['breadcrumbs'][] = ['label' => 'Media Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-agencies-create">

    <h1 class='box-title'><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
