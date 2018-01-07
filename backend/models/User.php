<?php

namespace backend\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirmation_token
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $registration_ip
 * @property string $bind_to_ip
 * @property string $email
 * @property integer $email_confirmed
 * @property integer $user_type_ref_id
 * @property string $user_role_ref_id
 * @property integer $category_ref_id
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $is_profile_set
 * @property integer $media_agency_ref_id
 * 
 * @property AdminAssignedUserTypes[] $adminAssignedUserTypes
 * @property AdminLocation[] $adminLocations
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Communique[] $communiques
 * @property Faq[] $faqs
 * @property ProjectCoOwners[] $projectCoOwners
 * @property ProjectComments[] $projectComments
 * @property ProjectParticipation[] $projectParticipations
 * @property ProjectRating[] $projectRatings
 * @property ProjectRecommend[] $projectRecommends
 * @property ProjectRecommend[] $projectRecommends0
 * @property ProjectSearch[] $projectSearches
 * @property Projects[] $projects
 * @property UserCategory $categoryRef
 * @property UserType $userTypeRef
 * @property UserProfile[] $userProfiles
 * @property UserProfileByUsertype $userProfileByUsertype
 * @property UserSettings[] $userSettings
 * @property UserVisitLog[] $userVisitLogs
 */
class User extends \yii\db\ActiveRecord
{
    public $location, $userEmail, $from_date, $to_date;
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['username', 'email'];
        $scenarios['create'] = ['username', 'email'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required', 'on'=>'create'],
            [['username', 'email'], 'required', 'on'=>'update'],
            [['status', 'superadmin', 'updated_at', 'email_confirmed', 'category_ref_id', 'created_by', 'modified_by','is_profile_set', 'media_agency_ref_id'], 'integer'],
            [['user_role_ref_id'], 'string'],
            [['username', 'password_hash', 'confirmation_token', 'bind_to_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 128],
            ['email','email', 'on'=>'create'],
            ['email','email', 'on'=>'update'],
            ['email', 'filter', 'filter' => 'trim','on'=>'create'],
            ['email', 'filter', 'filter' => 'trim','on'=>'update'],
            ['email','unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.', 'on'=>'create'],
            ['username','unique','on'=>'create'],
            [['location', 'userEmail', 'from_date', 'to_date','user_type_ref_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'confirmation_token' => 'Confirmation Token',
            'status' => 'Status',
            'superadmin' => 'Superadmin',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'registration_ip' => 'Registration Ip',
            'bind_to_ip' => 'Bind To Ip',
            'email' => 'Email',
            'email_confirmed' => 'Email Confirmed',
            'user_type_ref_id' => 'User Type',
            'user_role_ref_id' => 'User Role Ref ID',
            'category_ref_id' => 'Category Ref ID',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'media_agency_ref_id' => 'Media Agency', 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminAssignedUserTypes() 
    { 
        return $this->hasMany(AdminAssignedUserTypes::className(), ['user_ref_id' => 'id']); 
    } 
 
   /** 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getAdminLocations()
    {
        return $this->hasMany(AdminLocation::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommuniques()
    {
        return $this->hasMany(Communique::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqs()
    {
        return $this->hasMany(Faq::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCoOwners()
    {
        return $this->hasMany(ProjectCoOwners::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectComments()
    {
        return $this->hasMany(ProjectComments::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectParticipations()
    {
        return $this->hasMany(ProjectParticipation::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRatings()
    {
        return $this->hasMany(ProjectRating::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRecommends()
    {
        return $this->hasMany(ProjectRecommend::className(), ['user_ref_id_recommended_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRecommends0()
    {
        return $this->hasMany(ProjectRecommend::className(), ['user_ref_id_recommended_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectSearches()
    {
        return $this->hasMany(ProjectSearch::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryRef()
    {
        return $this->hasOne(UserCategory::className(), ['user_category_id' => 'category_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTypeRef($id)
    {
//        return $this->hasOne(UserType::className(), ['user_type_id' => 'user_type_ref_id']);
        $model = \common\models\UserType::find()->where(['user_type_id'=>$id])->one();
         if(!empty($model)){
            return $model->user_type;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfileByUsertype()
    {
        return $this->hasOne(UserProfileByUsertype::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasMany(UserSettings::className(), ['user_ref_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVisitLogs()
    {
        return $this->hasMany(UserVisitLog::className(), ['user_id' => 'id']);
    }
    
    public function signup($randomPwd)
    {
        if (!$this->validate()) {
            return null;
        }        
        
        $user = new \common\models\User();
        $this->password_hash = $randomPwd;
        $user->username = $this->username;
        $user->email = $this->email;  
        $user->status = 1;
        $user->user_role_ref_id = 1;
        $user->setPassword($this->password_hash);
        $user->generateAuthKey();       
        
        
        /* $location = new \app\models\AdminLocation();
        $location->location_ref_id = $this->location;
        $location->user_ref_id = $user->id;   */
              
        
        return $user->save() ? $user : null;
    }    
    
}
