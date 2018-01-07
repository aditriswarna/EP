<?php

namespace frontend\models;
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
    public $fname;
    public $lname;
    public $mobile;
    public $dob;
    public $gender;
    public $user_image;
    public $citizen;
    public $domicile;
    public $current_location;
    public $occupation;
    public $domain_expertise;
    public $course_details;
    public $college;
    public $university;
    public $year_of_joining;
    public $field_of_study;
    public $field_of_excellence;
    public $communication_address;
    public $state;
    public $constituency;
    public $elected_year;
    public $department;
    public $sector;
    public $representing_authority;
    public $designation;
    public $bank_name;
    public $bank_sector;
     public $member_of_parliament;
    public $branch;
    public $company_name;
      
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
            [[ 'fname', 'lname', 'dob', 'mobile', 'bank_name', 'branch'], 'required'],
            [['mobile'], 'match', 'pattern'=>'/^[5-9]\d{9}$/'],
            [['dob'], 'safe'],
            [['gender'], 'string'],
            [['fname', 'lname'], 'string', 'max' => 100],
            [['elected_year', 'year_of_joining'], 'integer'],
            [['elected_year', 'year_of_joining'], 'string', 'min' => 4, 'max' => 4],
            [['user_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
   
            [['citizen', 'domicile', 'occupation', 'domain_expertise','course_details','college','university','year_of_joining','field_of_study','field_of_excellence','communication_address','state','constituency','elected_year','department','state','sector','representing_authority','designation','bank_name','bank_sector','member_of_parliament','branch','company_name'], 'string', 'max' => 30],
            [['current_location'], 'string', 'max' => 200],
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
            //'user_profile_id' => 'User Profile ID',
            //'user_ref_id' => 'User Ref ID',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'mobile' => 'Mobile',
            'dob' => 'Date of Birth',
            'gender' => 'Gender',
            'user_image' => 'User Image',
            'citizen' => 'Citizen',
            'domicile' => 'Domicile',
            'current_location' => 'Current Location',
            'occupation' => 'Occupation',
            'domain_expertise' => 'Domain Expertise',
            'course_details' => 'Course Details',
            'college' => 'College',
            'university' => 'University',
            'year_of_joining' => 'Year of Joining',
            'field_of_study' => 'Field of Study',
            'field_of_excellence' => 'Field of Excellence',
            
            'communication_address' => 'Communication Address',
            'state' => 'State',
            'constituency' => 'Constituency',
            'elected_year' => 'Elected Year',
            'department' => 'Department',
            'sector' => 'Sector',
            'representing_authority' => 'Representing Authority',
            'designation' => 'Designation',
            'bank_name' => 'Bank Name',
            'bank_sector' => 'Bank Sector',
            'member_of_parliament' => 'Member of Parliament',
            'branch' => 'Branch',
            'company_name' => 'Company Name'
            
            
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
