<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectCoOwners */
/* @var $form yii\widgets\ActiveForm */
?>

<style type="text/css">
    .divMargin {
        width: 45%;
        float: left;
        margin: 0px 15px 0px 15px;
    }
</style>
<div class="participation-border">
<div class="project-co-owners-form col-xs-12 col-sm-6">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'createProjectCoOwner' ,'name' => 'createProjectCoOwner']]); ?>

    <?php echo $form->field($model, 'project_ref_id')->hiddenInput(['value' => $project->project_id])->label(False); ?>
	
    <?php echo '<h4 class="project-usnme">Project Name: <span>' . $project->project_title.'</span></h4>'; ?>
    
    
     <div class="">
    <div class="form-group">
	<?php echo $form->field($model, 'email')->textInput(['minlength' => 3]) ?>

    </div>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

        
</div></div>


