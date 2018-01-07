<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "project_type".
 *
 * @property integer $project_type_id
 * @property string $project_type
 */
class ProjectType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_type'], 'required'],
            [['project_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_type_id' => 'Project Type ID',
            'project_type' => 'Project Type',
        ];
    }
}
