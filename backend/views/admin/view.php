<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['admin_list']];
$this->params['breadcrumbs'][] = $this->title;
$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<?php
echo "<h1 class='box-title'>$this->title </h1>";
?>
<div class="user-view participation-border fl-left">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<!--
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>-->
    <?php 
    $user_type = $model->getUserTypeRef($model->user_type_ref_id);
    $data = backend\controllers\AdminController::getUserDetails($model->id);
    $admin_assigned_user_type = backend\models\AdminAssignedUserTypes::getUserTypes($model->id);           

    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            /*[
                'attribute'=>'id',
                'label'=>'User Id',
                'value' => $model['id'],  
            ],*/
            'username', 
            [
                'attribute'=>'status',
                'value' => $model['status'] == '1'?'Active':'Inactive',  
            ],
            [
                'attribute'=>'superadmin',
                'value' => $model['superadmin'] == '1'?'Yes':'No',  
            ],
            
            [
                'attribute'=>'created_at',       
                'format' => ['date', 'php:'.$phpdateformat]
            ],                  
            'email:email', 
            [
                'attribute'=>'user_type_ref_id',
                'label'=>empty($user_type)?'Assigned User Type':'User Type',
                'value' => $user_type?$user_type:(!empty($admin_assigned_user_type)?$admin_assigned_user_type:'-'),  
            ],            
            [
                'attribute'=>'fname',
                'label'=>'Name',
                'value' => !empty($data['fname'])?$data['fname'].' '.$data['lname']:'-'           
            ],
            [
                'attribute'=>'gender',
                'label'=>'gender',
                'value' => !empty($data['gender'])?$data['gender']:'-'
            ],
           /* [
                'attribute'=>'citizen',
                'label'=>'citizenship',
                'value' => $data['citizen'] 
            ],
            [
                'attribute'=>'current_location',
                'label'=>'current_location',
                'value' => $data['current_location'] 
            ],
            [
                'attribute'=>'occupation',
                'label'=>'occupation',
                'value' => $data['occupation'] 
            ],*/
//            'user_role_ref_id',
//            'category_ref_id',
//            'created_by',
//            'modified_by',
        ],
    ]) ?>

</div>
