<?php

namespace frontend\models;

use Yii;
use yii\db\Connection;
use yii\db\Query;

/**
 * This is the model class for table "project_status".
 *
 * @property integer $status_id
 * @property string $status_name
 */
class ProjectStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_name'], 'string', 'max' => 255],
            [['status_id'], 'integer'],
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
}
