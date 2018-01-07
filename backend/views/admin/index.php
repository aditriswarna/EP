<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'username',
//            'adminLocations.user_ref_id',
//            'auth_key',
//            'password_hash',
//            'confirmation_token',
//             'status',
//             'superadmin',
//             'created_at',
//             'updated_at',
//             'registration_ip',
//             'bind_to_ip',
            'email:email',
//             'email_confirmed:email',
//             'user_type_ref_id',
//             'user_role_ref_id',
//             'category_ref_id',
//             'created_by',
//             'modified_by',
            'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
