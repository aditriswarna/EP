<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Sign in';
$this->params['breadcrumbs'][] = $this->title;
/*
if(($flash = Yii::$app->session->getFlash('password_changed')) || ($flash = Yii::$app->session->getFlash('forgotpassword'))){
    echo Alert::widget(['options' => ['class' => 'alert-success chag-pwds'], 'body' => $flash]);
}
 */
 if(($flash = Yii::$app->session->getFlash('mailnotconfirmed')) || ($flash = Yii::$app->session->getFlash('statusnotenabled')) || ($flash = Yii::$app->session->getFlash('resendemailsuccess'))){
    echo Alert::widget(['options' => ['class' => 'alert-success mr-top'], 'body' => $flash]);
    }

?>
<style>

	body { background:#F1F1F1; }
	
	.mr-top{ margin-top:85px !important; margin:0px auto; width:95%; }
	
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
<div class="login_page">
<div class="site-login">
    

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
        <div class="panel-heading">
        	<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
		</div>
        	<div class="panel-body">
            <p class="login-page-cont">Please fill out the following fields to login</p>
            <?php $form = ActiveForm::begin(['id' => 'login-page-form',
                'method' => 'post',
                'action' => 'login'
                ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="login-page-cont">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>

                <div class="button-container1">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary lg-pgbtn', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div></div></div>
    </div>
</div>
</div>