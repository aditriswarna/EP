<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MediaAgencies */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="site-signup participation-border fl-left media-agencies-form">
    <div class="row">
        <div class="col-xs-12 col-sm-6">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'media_agency_name')->textInput(['maxlength' => true]) ?>

            <?php // echo $form->field($model, 'status')->textInput() ?>

            <?php // echo $form->field($model, 'created_date')->textInput() ?>

            <?php // echo $form->field($model, 'created_by')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


