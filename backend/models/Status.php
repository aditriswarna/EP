<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property integer $status_id
 * @property string $status_name
 *
 * @property ProjectComments[] $projectComments
 * @property ProjectRecommend[] $projectRecommends
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_name'], 'required'],
            [['status_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'status_name' => 'Status Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectComments()
    {
        return $this->hasMany(ProjectComments::className(), ['status' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRecommends()
    {
        return $this->hasMany(ProjectRecommend::className(), ['status' => 'status_id']);
    }
}
