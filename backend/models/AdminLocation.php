<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_location".
 *
 * @property integer $admin_location_id
 * @property integer $user_ref_id
 * @property integer $location_ref_id
 *
 * @property Location $locationRef
 * @property User $userRef
 */
class AdminLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ref_id', 'location_ref_id'], 'required'],
            [['user_ref_id', 'location_ref_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_location_id' => 'Admin Location ID',
            'user_ref_id' => 'User Ref ID',
            'location_ref_id' => 'Location Ref ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationRef()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }
}
