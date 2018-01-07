<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "project_category".
 *
 * @property integer $project_category_id
 * @property string $category_name
 * @property string $Status
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
	
	public function getCategoryName()
    {
        return $this->hasOne(Project::className(), ['project_category_id' => 'project_category_ref_id']);
    }
	
	public function getCategoryName1($project_category_id=NULL)
    {
        if($project_category_id!=NULL)
            $username=ProjectCategory::findOne($project_category_id);
        
        return $username->category_name;
    }
	
}
