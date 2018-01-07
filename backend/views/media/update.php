<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MediaAgencies */

$this->title = 'Update Media Agencies: ' . $model->media_agency_name;
$this->params['breadcrumbs'][] = ['label' => 'Media Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->media_agency_id, 'url' => ['view', 'id' => $model->media_agency_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="media-agencies-update">

    <h1 class='box-title'><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
