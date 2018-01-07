<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_assigned_user_types".
 *
 * @property integer $admin_assigned_user_types_id
 * @property integer $user_ref_id
 * @property integer $user_type_ref_id
 *
 * @property User $userRef
 * @property UserType $userTypeRef
 */
class AdminAssignedUserTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_assigned_user_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ref_id', 'user_type_ref_id'], 'required'],
            [['user_ref_id', 'user_type_ref_id'], 'integer'],
            [['user_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_ref_id' => 'id']],
            [['user_type_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserType::className(), 'targetAttribute' => ['user_type_ref_id' => 'user_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_assigned_user_types_id' => 'Admin Assigned User Types ID',
            'user_ref_id' => 'User Ref ID',
            'user_type_ref_id' => 'User Type Ref ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTypeRef()
    {
        return $this->hasOne(UserType::className(), ['user_type_id' => 'user_type_ref_id']);
    }

    /**
     * @inheritdoc
     * @return AdminAssignedUserTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminAssignedUserTypesQuery(get_called_class());
    }
    
    public static function getUserTypes($user_ref_id){        
        $sql = "SELECT user_ref_id, GROUP_CONCAT(user_type) AS user_type, GROUP_CONCAT(user_type_ref_id) AS user_type_id FROM admin_assigned_user_types aut 
        LEFT JOIN user_type ut ON ut.user_type_id = aut.user_type_ref_id WHERE aut.user_ref_id=".$user_ref_id;
        
        $result = yii::$app->db->createCommand($sql)->queryAll();
       
        return $result[0]['user_type'];        
    }
}
