<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */

$this->title = 'Share link via email'; 
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-participation-create">

    <h1 class="box-title project-pouppric mail-sharpopup"><?php echo Html::encode($this->title) ?></h1>


    <?= $this->render('_email_form', [
        'model' => $model,
        'project' => $project,
    ]) ?>

</div>