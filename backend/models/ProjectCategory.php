<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "project_category".
 *
 * @property integer $project_category_id
 * @property string $category_name
 * @property string $Status
 *
 * @property Projects[] $projects
 */
class ProjectCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_name'], 'required'],
            [['Status'], 'string'],
            [['category_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_category_id' => 'Project Category ID',
            'category_name' => 'Category Name',
            'Status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::className(), ['project_category_ref_id' => 'project_category_id']);
    }
}
