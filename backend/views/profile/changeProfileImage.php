<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\UserProfile;
use yii\web\Request;
use common\models\Storage;

/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form ActiveForm */
//print_r($userdata->user_type_ref_id); exit;
?>
<style>
.form-group{ margin:0 }
</style>
<?php 
$userData = UserProfile::find()->select('user_image')->AsArray()->where(['user_ref_id' => Yii::$app->user->identity->id])->one(); 
$bucket = Yii::getAlias('@bucket');
$keyname = 'uploads/profile_images/'.Yii::$app->user->identity->id.'/'.$userData['user_image']; 
$s = new Storage();
$file = $s->download($bucket,$keyname); 

?>
<div class="col-lg-5">

<?php $form = ActiveForm::begin(['id' => 'login-form',
            'method' => 'post',
            'action' => ['profile/change-profile-image'],
            'options' => ['enctype' => 'multipart/form-data']]); ?>
<?php 
        echo $form->field($imagemodel, 'user_image')->fileInput();
?>
    <?php if($userData['user_image'] && isset($file['@metadata']['effectiveUri'])) { ?>
        <div><img src="<?php echo $file['@metadata']['effectiveUri'];?>" height="100px" style="border: 5px #eee solid;"></div>
    <?php } ?>  
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- profile -->
  