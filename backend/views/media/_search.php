<style>
    .clsTextbox {
        float: none;
        margin-right: 20px;
        //        width: 20%;
    }
    .searchBox .form-group {
        margin: 0px;
    }
    .empty{
        text-align: center;
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\search\MediaSearch */
/* @var $form yii\widgets\ActiveForm */
$userStatus = ArrayHelper::map(\backend\models\Status::find()->where(['status_id'=>['1','3']])->all(), 'status_id', 'status_name');
$dateformat = Yii::getAlias('@phpdatepickerformat');
?>

<div style="width: 100%; float: left;" class="searchBox searchBox1 ">
    <div class="col-sm-12 p-left0">
        <?php yii\widgets\Pjax::begin(['id' => 'search-form']); ?>
        <?php $form = ActiveForm::begin([                
                'action' => ['index'],
                'method' => 'get',
                ]); 
        ?>
        <div class="col-sm-2 col-xs-12 p-left0 ad-lst">
            <?php echo $form->field($model, 'media_agency_name')->textInput(array('placeholder' => 'Agency name'), ['class' => 'form-control clsTextbox col-sm-2 col-xs-12']) ?>
        </div>        
        <?php echo $form->field($model, 'status', ['options' => ['class' => 'col-sm-2 col-xs-12 ad-lst']])->dropDownList($userStatus, ['prompt' => 'Status']) ?>

        <div class="col-sm-2 col-xs-12 custom-calendar">
            <?php
            echo $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'col-sm-3 col-xs-12'],
                'clientOptions' => [
                    'changeMonth' => true,
                    'yearRange' => "2015:(date('Y')+5)",
                    'changeYear' => true,
                    'showOn' => 'button',
                    'buttonImage' => 'images/calendar.gif',
                    'buttonImageOnly' => true,
                    'buttonText' => 'Select date',
                    'buttonImage' => Yii::$app->request->BaseUrl . '/images/calendar.gif',
                ],
            ])->textInput(['readonly' => true]);
            ?>
        </div>
        <div class="col-sm-2 col-xs-12 custom-calendar">
            <?php
            echo $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'col-sm-3 col-xs-12'],
                'clientOptions' => [
                    'changeMonth' => true,
                    'yearRange' => "2015:(date('Y')+5)",
                    'changeYear' => true,
                    'showOn' => 'button',
                    'buttonImage' => 'images/calendar.gif',
                    'buttonImageOnly' => true,
                    'buttonText' => 'Select date',
                    'buttonImage' => Yii::$app->request->BaseUrl . '/images/calendar.gif',
                ],
            ])->textInput(['readonly' => true]);
            ?>
        </div>
        <div class="col-sm-2 btn-sbrt ad-lst">
            <div class="col-sm-6 col-xs-12 searchBtn">
                <?php echo Html::submitButton('<i class="icon-magnifier"></i>', ['class' => 'btn btn-success', 'id' => 'btnSearch']) ?>
                <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/media/index" id="searchUrl">
            </div>
            <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
                <?php echo Html::submitButton('<i class="fa fa-refresh"></i>', ['class' => 'btn btn-success res-bnt', 'id' => 'btnReset']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <?php yii\widgets\Pjax::end(); ?>
    </div>
</div>