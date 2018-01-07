<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project_likes".
 *
 * @property integer $project_likes_id
 * @property integer $project_ref_id
 * @property integer $user_ref_id
 * @property integer $is_liked
 * @property string $created_date
 */
class ProjectLikes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_likes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['project_ref_id', 'user_ref_id', 'is_liked'], 'required'],
            [['project_ref_id', 'user_ref_id', 'is_liked'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_likes_id' => 'Project Likes ID',
            'project_ref_id' => 'Project Ref ID',
            'user_ref_id' => 'User Ref ID',
            'is_liked' => 'Is Liked',
            'created_date' => 'Created Date',
        ];
    }
}
