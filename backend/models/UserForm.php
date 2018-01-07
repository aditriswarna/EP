<?php

namespace backend\models;
use yii\base\Model;




/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_profile_id
 * @property integer $user_ref_id
 * @property string $fname
 * @property string $lname
 * @property string $dob
 * @property string $gender
 * @property string $user_image
 * @property string $citizen
 * @property string $domicile
 * @property string $current_location
 * @property string $occupation
 * @property string $domain_expertise
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property User $userRef
 */
class UserForm extends \yii\db\ActiveRecord
{
    public $username;
    public $fname;
    public $lname;
    public $mobile;
    public $gender;
    public $user_image;
    public $email;
      
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
            [[ 'username', 'fname', 'lname', 'dob'], 'required'],
            [['mobile'], 'match', 'pattern'=>'/^[5-9]\d{9}$/'],
            [['gender'], 'string'],
            [['username','fname', 'lname'], 'string', 'max' => 100],
            [['user_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->user_image->saveAs('uploads/' . $this->user_image->baseName . '.' . $this->user_image->extension);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'mobile' => 'Mobile',
            'gender' => 'Gender',
            'user_image' => 'User Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    //public function getUserRef()
    //{
     //   return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
   // }
}
