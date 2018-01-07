<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "project_co_owners".
 *
 * @property integer $project_co_owner_id
 * @property integer $project_ref_id
 * @property integer $user_ref_id
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property Projects $projectRef
 * @property User $userRef
 */
class ProjectCoOwners extends \yii\db\ActiveRecord
{
    public $email;
	 private $_user;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_co_owners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_ref_id',  'created_by', 'created_date', 'email'], 'required'],
            [['project_ref_id', 'user_ref_id', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date', 'email'], 'safe'],
			['email', 'email'],
			['email', 'checkUsername']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_co_owner_id' => 'Project Co Owner ID',
            'project_ref_id' => 'Project Ref ID',
            'user_ref_id' => 'User Ref ID',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
	 
	 public function checkUsername($attribute, $params)
    {
	$user = User::findByEmail($this->email);
	$user = User::find()->where(["email" => $this->email, "status" => 1])->one();
	$verifyuser = $this->verifyCoOwner(isset($user)?$user->id:'', $this->project_ref_id);
	 $projectdetails = Projects::find()->where(["project_id" => $this->project_ref_id])->one();

        if(!$user){
			$this->addError($attribute, 'Email does not exist');
		  }else	if($verifyuser){
			$this->addError($attribute, 'User already added as co-owner');  
		  }else if($projectdetails->user_ref_id == $user->id){
			$this->addError($attribute, 'Project owner cannot be added as co-owner');  
		  }else{
			return true;
		  }
    }
	
	public function verifyCoOwner($uid, $pid)
    {

		$user = ProjectCoOwners::find()->where(["project_ref_id" => $pid, "user_ref_id" => $uid])->one();
		if($user){
			return true;
		}else{
			return false;
		}
		//return $this->hasOne(ProjectCoOwners::className(), ['project_ref_id' => 'project_ref_id', 'user_ref_id' => 'user_ref_id']);
    }
	
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
    
    public static function getProjectCoownerDetails($pid,$uid)
    {
        if($uid){
            $sql = 'SELECT pc.project_co_owner_id, u.id, u.email, up.fname, up.lname, pc.user_ref_id FROM project_co_owners AS pc LEFT JOIN user as u ON u.id=pc.user_ref_id LEFT JOIN user_profile as up ON up.user_ref_id=u.id WHERE pc.user_ref_id <> '.$uid.' AND pc.project_ref_id='.$pid.' AND pc.is_coowner=1';
        }else{
        $sql = 'SELECT pc.project_co_owner_id, u.id, u.email, up.fname, up.lname, pc.user_ref_id FROM project_co_owners AS pc LEFT JOIN user as u ON u.id=pc.user_ref_id LEFT JOIN user_profile as up ON up.user_ref_id=u.id WHERE pc.project_ref_id='.$pid.' AND pc.is_coowner=1';
        }
        $projectdata = yii::$app->db->createCommand($sql)->queryAll();
        return $projectdata;
    }
}
