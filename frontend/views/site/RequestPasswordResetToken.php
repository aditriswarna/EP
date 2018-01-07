<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forgot Password?';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .front-noti{ 
        position: fixed;
        top: 40%;
        left: 50%;
        z-index: 9999;
        margin-left: -245px;
    }
	@media (max-width: 400px) {
	p.help-block.help-block-error{
		    top: 67px !important;    bottom: 25px; left:o; right:auto;		
		}
	}
</style>
<div class="site-request-password-reset login_page">
<div class="site-login">
    

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
        <div class="panel-heading">
        	<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
		</div>
        	<div class="panel-body">
            <p class="login-page-cont">Please fill out your email. New password will be sent there.</p>
				 <?php $form = ActiveForm::begin(['id' => 'password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="button-container1">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary lg-pgbtn']) ?>
                </div>

           <?php ActiveForm::end(); ?>
        </div></div></div>
    </div>
</div>
</div>
