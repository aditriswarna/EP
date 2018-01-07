<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_status".
 *
 * @property integer $user_status_id
 * @property string $status_name
 */
class UserStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_status_id' => 'User Status ID',
            'status_name' => 'Status Name',
        ];
    }
}
