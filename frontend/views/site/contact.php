<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\web\JsExpression;
use yii\bootstrap\Alert;
$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;

$ckeditor_url = Yii::$app->urlManager->baseUrl.'/themes/metronic/assets/global/plugins/ckeditor/ckeditor.js';
$this->registerJsFile($ckeditor_url,['position' => \yii\web\View::POS_HEAD]);
	
	
?>
<style>
p.help-block.help-block-error {
    position: absolute;
    right: 0;
    /* top: -46px; */
    font-size: 12px !important;
    font-weight: normal !important;
}
.btn-submit{
    margin-left: 7% !important;
}
@media  (max-width:670px){
        .btn-submit{
        margin-left: 27px !important;
    }
}
.text {
    margin: 0 0 20px 69px;
}
@media (max-width:770px){
   .text {
        margin: 0 27px;       
    } 
}
</style>
<div class="site-signup-page">
    <div class="site-login">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="text-title">Contact us</div>
							<br>
							<?php
							if($flash = Yii::$app->session->getFlash('contact_success')){
        echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]);
    } ?>
                            <div class="panel-body" style=" text-align: left;">
                            <p class="text">
                                If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
                            </p>

                            <div class="profile-page participation-border">
                                <div class="row">
                                    <?php $form = ActiveForm::begin(['id' => 'contact-form', 'class' => 'contact_form']); ?>
                                    <div class='col-xs-12 col-sm-12'>
                                        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                                        <?= $form->field($model, 'email') ?>

                                        <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

                                        <?=
                                        $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                            'template' => '<div class="clearfix"><div>{input}</div> <div style="float:left; padding-top: 10px;">{image}</div> <div><a href="javascript:void(0)" id="refresh-captcha"><i class="fa fa-refresh" 
                                                style="background-color: #5e9cd1; padding: 5px 7px;color: #fff; margin-top: 22px;"></i></a></div></div>',
                                            'imageOptions' => [
                                                'id' => 'captcha-image'
                                            ]
                                        ])
                                        ?>
                                       
                                        <?php $this->registerJs("
                                            $('#refresh-captcha').on('click', function(e){
                                                e.preventDefault();

                                                $('#captcha-image').yiiCaptcha('refresh');
                                            })
                                        "); ?>
                                    </div>
                                </div>
                                <div class="form-group  btn-submit">
                                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
</div>

