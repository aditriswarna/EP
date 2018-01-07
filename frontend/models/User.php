<?php

namespace frontend\models;

use yii\base\Model;
use yii;

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
 *
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
 * @property UserProfile[] $userProfiles
 * @property UserProfileByUsertype $userProfileByUsertype
 * @property UserSettings[] $userSettings
 * @property UserVisitLog[] $userVisitLogs
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['status', 'superadmin', 'created_at', 'updated_at', 'email_confirmed', 'user_type_ref_id', 'category_ref_id', 'created_by', 'modified_by'], 'integer'],
            [['user_role_ref_id'], 'string'],
            [['username', 'password_hash', 'confirmation_token', 'bind_to_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 128]
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
            'password_hash' => 'Password Hash',
            'confirmation_token' => 'Confirmation Token',
            'status' => 'Status',
            'superadmin' => 'Superadmin',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'registration_ip' => 'Registration Ip',
            'bind_to_ip' => 'Bind To Ip',
            'email' => 'Email',
            'email_confirmed' => 'Email Confirmed',
            'user_type_ref_id' => 'User Type Ref ID',
            'user_role_ref_id' => 'User Role Ref ID',
            'category_ref_id' => 'Category Ref ID',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }
	public static function findByEmail($email)
    {
        //return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        return static::findOne(['email' => $email]);
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
    
    public static function getUserDetails($id)
    { 
        $sql = 'SELECT u.id,u.email, u.auth_key,u.is_profile_set, u.user_type_ref_id, up.fname, up.lname, up.mobile FROM user AS u LEFT JOIN user_profile AS up ON u.id=up.user_ref_id WHERE id='.$id;
        $userData = yii::$app->db->createCommand($sql)->queryAll();
        return $userData;
    }
    
    public function forgotpassword($randomPwd, $uid)
    {
        $user =  \common\models\User::find()->where(['id' => $uid])->one();
        $this->password_hash = $randomPwd;   
        $user->setPassword($this->password_hash);
        $user->generateAuthKey();
        $user->save();   
        return $user->save() ? $user : null;
    }      
}
