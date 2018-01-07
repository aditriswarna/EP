<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form ActiveForm */
//print_r($userdata->user_type_ref_id); exit;
?>


<div class="col-lg-5">
<?php $form = ActiveForm::begin([
         'method' => 'post',
        'action' => ['profile/profile'],
        'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
            <?php 
if(isset($userdatamodel->gender))$userformmodel->gender = $userdatamodel->gender;

    echo $form->field($userformmodel, 'username')->textInput(['value' => (isset($userdata->username)? $userdata->username : '') ]);
    echo $form->field($userformmodel, 'fname')->textInput(['value' => (isset($userdatamodel->fname)? $userdatamodel->fname : '') ]);
    echo $form->field($userformmodel, 'lname')->textInput(['value' => (isset($userdatamodel->lname)? $userdatamodel->lname : '') ]);
    echo $form->field($userformmodel, 'mobile')->textInput(['value' => (isset($userdatamodel->mobile)? $userdatamodel->mobile : '') ]);
    echo $form->field($userformmodel, 'gender')->radioList(['Male' => 'Male', 'Female' => 'Female',]);
    echo $form->field($userformmodel, 'email')->textInput(['value' => (isset($userdata->email)? $userdata->email : ''), 'readonly' => true ]);

?>
       
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- profile -->

  