<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property integer $project_id
 * @property integer $user_ref_id
 * @property integer $project_category_ref_id
 * @property integer $project_type_ref_id
 * @property string $project_title
 * @property string $objective
 * @property string $location
 * @property string $longitude
 * @property string $latitude
 * @property string $city 
 * @property string $state 
 * @property string $project_desc
 * @property string $conditions 
 * @property string $CSR_project_type
 * @property string $CSR_website
 * @property string $targeted_govt_authority
 * @property string $govt_authority_name
 * @property string $estimated_project_cost
 * @property string $project_start_date
 * @property string $project_end_date
 * @property string $project_estimated_date
 * @property double $project_percentage
 * @property string $primary_contact
 * @property string $secondary_contact
 * @property string $primary_email_contact
 * @property string $Status
 * @property string $display_in_home_page
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $project_status
 * @property integer $total_participation_amount
 * @property string $company_name
 * @property string $Organization_name
 * @property string $secondary_email_contact
 * @property integer $funding_status
 *
 * @property Communique[] $communiques
 * @property ProjectCoOwners[] $projectCoOwners
 * @property ProjectComments[] $projectComments
 * @property ProjectMedia[] $projectMedia
 * @property ProjectParticipation[] $projectParticipations
 * @property ProjectRating[] $projectRatings
 * @property ProjectRecommend[] $projectRecommends
 * @property ProjectSearch[] $projectSearches
 * @property ProjectCategory $projectCategoryRef
 * @property ProjectType $projectTypeRef
 * @property User $userRef
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $document_name, $embed_videos, $project_image;
    
    public static function tableName()
    {
        return 'projects';
    }
    /**
     * 
     */
    
   public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['CSR'] = ['project_status', 'funding_status', 'project_start_date', 'project_end_date', 'project_estimated_date','project_category_ref_id', 'objective', 'project_desc', 'company_name', 'Organization_name', 'project_title', 'primary_email_contact', 'primary_contact', 'location', 'project_image'];
        $scenarios['individual'] = ['estimated_project_cost','project_category_ref_id', 'objective', 'project_desc', 'company_name', 'Organization_name', 'project_title', 'primary_email_contact', 'primary_contact', 'location', 'project_image'];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_category_ref_id', 'objective', 'project_desc', 'company_name', 'Organization_name', 'project_title', 'primary_email_contact', 'primary_contact', 'location','project_image'], 'required', 'on'=>'CSR'],
            [['project_category_ref_id', 'objective', 'project_desc', 'company_name', 'Organization_name', 'project_title', 'primary_email_contact', 'primary_contact', 'location', 'project_image'], 'required', 'on'=>'individual'],
            [['project_status', 'funding_status'], 'required', 'on'=>'CSR'],
            [['user_ref_id', 'project_category_ref_id', 'project_type_ref_id', 'estimated_project_cost', 'created_by', 'modified_by', 'project_status', 'total_participation_amount', 'funding_status'], 'integer'],
            [['project_desc'], 'string', 'max' => 500],
            [['project_start_date', 'project_end_date', 'project_estimated_date', 'created_date', 'modified_date'], 'safe'],
            [['project_percentage'], 'number'],
            [['project_title'], 'string', 'max' => 25],
            [['objective'], 'string', 'max' => 50],
            [['location'], 'string', 'max' => 200],
            [['longitude', 'latitude', 'company_name', 'Organization_name'], 'string', 'max' => 30],
            [['city', 'state'], 'string', 'max' => 20],
            [['CSR_website', 'primary_email_contact', 'secondary_email_contact'], 'string', 'max' => 100],
            [['primary_contact', 'secondary_contact'], 'string', 'max' => 15],
            [['project_category_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => \frontend\models\ProjectCategory::className(), 'targetAttribute' => ['project_category_ref_id' => 'project_category_id']],
            [['project_type_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => \frontend\models\ProjectType::className(), 'targetAttribute' => ['project_type_ref_id' => 'project_type_id']],
            [['user_ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_ref_id' => 'id']],
            [['username', 'email'], 'required','on'=>'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'user_ref_id' => 'User Ref ID',
            'project_category_ref_id' => 'Project Category',
            'project_type_ref_id' => 'Project Type Ref ID',
            'project_title' => 'Project Title',
            'objective' => 'Project Vision',
            'location' => 'Location',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'project_desc' => 'Project Description',
            'conditions' => 'Conditions',
            'project_image' => 'Project Image',
            'CSR_project_type' => 'Csr Project Type',
            'CSR_website' => 'Csr Website',
            'targeted_govt_authority' => 'Targeted Govt Authority',
            'govt_authority_name' => 'Govt Authority Name',
            'estimated_project_cost' => 'Estimated Cost',
            'project_start_date' => 'Start Date',
            'project_end_date' => 'End Date',
            'project_estimated_date' => 'Estimated Start Date',
            'project_percentage' => 'Project Percentage',
            'primary_contact' => 'Primary Contact Number',
            'secondary_contact' => 'Secondary Contact',
            'primary_email_contact' => 'Primary Contact Email',
            'Status' => 'Status',
            'display_in_home_page' => 'Display In Home Page',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'project_status' => 'Project Status',
            'total_participation_amount' => 'Total Participation Amount',
            'company_name' => 'Company Name',
            'Organization_name' => 'Organization Name',
            'secondary_email_contact' => 'Secondary Contact Email',
            'funding_status' => 'Funding Status',
        ];
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommuniques()
    {
        return $this->hasMany(Communique::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCoOwners()
    {
        return $this->hasMany(ProjectCoOwners::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectComments()
    {
        return $this->hasMany(ProjectComments::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectMedia()
    {
        return $this->hasMany(ProjectMedia::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectParticipations()
    {
        return $this->hasMany(ProjectParticipation::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRatings()
    {
        return $this->hasMany(ProjectRating::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRecommends()
    {
        return $this->hasMany(ProjectRecommend::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectSearches()
    {
        return $this->hasMany(ProjectSearch::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCategoryRef()
    {
        return $this->hasOne(ProjectCategory::className(), ['project_category_id' => 'project_category_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTypeRef()
    {
        return $this->hasOne(ProjectType::className(), ['project_type_id' => 'project_type_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }
}
