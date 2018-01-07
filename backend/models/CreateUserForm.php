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
class CreateUserForm extends \yii\db\ActiveRecord
{
    public $email;
    public $validemail;
    public $validmediaagency;
    public $password;
    public $user_type_ref_id;
    public $user_role_ref_id;
    public $media_agency_ref_id;
    public $confirmpassword;
    public $fname;
    public $lname;
    public $mobile;
    public $dob;
    public $citizen;
    public $domicile;
    public $latitude;
    public $longitude;
    public $current_location;
    public $gender;
    public $user_image;
      
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
           // ['validemail', 'required','message' => 'This email address has already been taken...'],
            //['validmediaagency','required','message' => 'Media Agency cannot be blank.'],
            ['email', 'email','message'=>"The email isn't correct"],
            ['email', 'unique','message'=>'This email address has already been taken...'],  
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User','message' => 'This email address has already been taken.'],
            ['email', 'unique', 'targetClass' => '\common\models\User','message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['media_agency_ref_id', 'required', 'when' => function($model) {
             return $model->user_type_ref_id == 9;
            }, 'whenClient' => "function (attribute, value) {
                return $('#createuserform-user_type_ref_id').val() == '9';
            }"],
                    
            ['confirmpassword', 'required'],
            ['confirmpassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
            
            ['user_type_ref_id', 'required'],            
            [['gender'], 'safe'],
            [[ 'username', 'fname', 'lname'], 'required'],
            [['dob'], 'required','message' => 'Date of birth cannot be blank'],
            [['mobile'], 'match', 'pattern'=>'/^[5-9]\d{9}$/'],
            [['dob'], 'safe'],
            [['citizen', 'domicile', 'latitude', 'longitude'], 'string', 'max' => 30],
            [['current_location'], 'string', 'max' => 100],
           
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
            'email' => 'Email',
            'password' => 'Password',
            'confirmpassword' => 'Confirm Password',
            'user_type_ref_id' => 'User Type',
            'user_role_ref_id' => 'User Role',
            
            'username' => 'Username',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'mobile' => 'Mobile',
            'gender' => 'Gender',
            'dob' => 'Date of Birth',
            'user_image' => 'User Image',
             'media_agency_ref_id' =>'Media Agency'
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
