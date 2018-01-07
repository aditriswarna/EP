<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "project_comments".
 *
 * @property integer $project_comment_id
 * @property integer $project_ref_id
 * @property integer $user_ref_id
 * @property string $comments
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property Projects $projectRef
 * @property User $userRef
 */
class ProjectComments extends \yii\db\ActiveRecord
{
    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_ref_id', 'user_ref_id', 'comments', 'created_by', 'created_date'], 'required'],
            [['project_ref_id', 'user_ref_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['comments'], 'string', 'max' => 255],
            [['project_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projects::className(), 'targetAttribute' => ['project_ref_id' => 'project_id']],
            [['user_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_ref_id' => 'id']],
            [['from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_comment_id' => 'Project Comment ID',
            'project_ref_id' => 'Project Ref ID',
            'user_ref_id' => 'User Ref ID',
            'comments' => 'Comments',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRef()
    {
        return $this->hasOne(Projects::className(), ['project_id' => 'project_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }
}
