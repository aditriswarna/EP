<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use common\models\User;
use frontend\models\Projects;
use yii\helpers\ArrayHelper;

$this->registerJsFile(Yii::getAlias('@web/themes/metronic/assets/global/plugins/ckeditor/ckeditor.js'),['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::getAlias('@web/themes/metronic/assets/global/plugins/chosen/chosen.jquery.js'),['position' => \yii\web\View::POS_HEAD]); 
$this->registerCssFile(Yii::getAlias('@web/themes/metronic/assets/global/plugins/chosen/chosen.css'),['position' => \yii\web\View::POS_HEAD]);  
/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */
/* @var $form yii\widgets\ActiveForm */
?>
<style>

.form-group.field-communique-selectemail label.control-label{ display:none;}
</style>
<div class="project-participation-form new-adminmail" style="overflow: hidden;">
    <div class="col-xs-12 col-sm-12 new-msg">  
        <?php $form = ActiveForm::begin(); ?>

        <?= '<h1 class="box-title new-msg-title">New Message</h1>'; ?>        
       
        <?php $users = ArrayHelper::map(User::find()->where('id != :id', [':id'=>Yii::$app->user->identity->id])->all(),'id','username');

        echo $form->field($model, 'existing_email')->dropDownList($users, ['prompt' => 'Select User', 'class' => 'chosen-select', 'multiple' => 'multiple']);
        ?> 
        <div id="errorExistingEmail" class="help-block error-msgmail" style="display: none">
            Please select email id from the list.
        </div>
               
        
        <?php echo $form->field($model, 'selectemail')->checkboxList(['newemail' => 'Other Email'],['class' => 'selectmailtype'])->label(''); ?>  
        
        <?= $form->field($model, 'new_email')->textInput()->label('New Email') ?> 
        <div id="errorNewEmail" class="help-block error-msgmail" style="display: none">
            Email field cannot be blank
        </div>
        
       <!--- <div class="help-block"></div>
        <div class="help-block"></div> -->
        
        <?= $form->field($model, 'subject')->textInput() ?>

        <?= $form->field($model, 'message')->textArea(['rows' => '6', 'id' => 'message']) ?>          

        <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary','onClick'=>'return checkErrors()']) ?>
        </div>

<?php ActiveForm::end(); ?>

    </div>
</div>

<script>

  $(function () {
        $(".chosen-select").chosen({width: "100%"});
        $('.field-communique-new_email').hide();
        $('.selectmailtype').click(function () {
            var emailtype = $('input[name="Communique[selectemail][]"]').parent('span').attr('class');
            if(emailtype == 'checked') {
                $('.field-communique-new_email').show();
            } else {
                $('.field-communique-new_email').hide();
                $('#errorNewEmail').css('display', 'none');
            }
        })
       

        // var data = CKEDITOR.instances.email_text_editor_message.getData();

        var editor = CKEDITOR.replace('Communique[message]', {
            language: 'en',
            uiColor: '#AADC6E',
            // uiColor: '#9AB8F3',
        });
        editor.on('change', function (evt) {
            var text_value = evt.editor.getData();
            $('#message').html(text_value);
        });
    }); 

    function checkErrors(){
        $('#errorNewEmail').css('display', 'none');
        $('#errorExistingEmail').css('display', 'none');
        
        var emailtype = $('input[name="Communique[selectemail][]"]').parent('span').attr('class');
        var new_mail = $.trim($('#communique-new_email').val()); 
        var existing_mail = $.trim($('#communique-existing_email').val());
        
        if($.trim(emailtype) == "checked" && new_mail == ''){            
            $('#errorNewEmail').css('display', 'block');
            return false;
        }
        if($.trim(emailtype) != "checked" && existing_mail == ''){
            $('#errorExistingEmail').css('display', 'block');
            return false;
        }
        return true;
    }

</script>
