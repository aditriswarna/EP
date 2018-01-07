<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_requests".
 *
 * @property integer $user_request_id
 * @property integer $user_ref_id
 * @property integer $project_ref_id
 * @property integer $approved_by
 * @property integer $is_approved
 * @property string $approved_on
 */
class UserRequests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ref_id', 'project_ref_id'], 'required'],
            [['user_ref_id', 'project_ref_id', 'approved_by', 'is_approved'], 'integer'],
            [['approved_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_request_id' => 'User Request ID',
            'user_ref_id' => 'User Ref ID',
            'project_ref_id' => 'Project Ref ID',
            'approved_by' => 'Approved By',
            'is_approved' => 'Is Approved',
            'approved_on' => 'Approved On',
        ];
    }
}
