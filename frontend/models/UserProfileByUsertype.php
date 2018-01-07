<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_profile_by_usertype".
 *
 * @property integer $user_ref_id
 * @property string $course_details
 * @property string $college
 * @property string $university
 * @property integer $year_of_joining
 * @property string $field_of_study
 * @property string $communication_address
 * @property string $field_of_excellence
 * @property string $member_of_parliament
 * @property string $state
 * @property string $constituency
 * @property integer $elected_year
 * @property string $department
 * @property string $sector
 * @property string $representing_authority
 * @property string $designation
 * @property string $bank_name
 * @property string $bank_sector
 * @property string $branch
 * @property string $company_name
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property User $userRef
 */
class UserProfileByUsertype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public static function tableName()
    {
        return 'user_profile_by_usertype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ref_id', 'course_details', 'college', 'university', 'year_of_joining', 'field_of_study', 'communication_address', 'field_of_excellence', 'member_of_parliament', 'state', 'constituency', 'elected_year', 'department', 'sector', 'representing_authority', 'designation', 'bank_name', 'bank_sector', 'branch', 'company_name', 'modified_by', 'modified_date'], 'required'],
            [['user_ref_id', 'year_of_joining', 'elected_year', 'modified_by'], 'integer'],
            [['member_of_parliament', 'bank_sector'], 'string'],
            [['modified_date'], 'safe'],
            [['course_details', 'college', 'university'], 'string', 'max' => 30],
            [['field_of_study', 'state', 'constituency', 'department', 'sector', 'representing_authority', 'designation', 'bank_name', 'branch'], 'string', 'max' => 50],
            [['communication_address'], 'string', 'max' => 150],
            [['field_of_excellence', 'company_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_ref_id' => 'User Ref ID',
            'course_details' => 'Course Details',
            'college' => 'College',
            'university' => 'University',
            'year_of_joining' => 'Year Of Joining',
            'field_of_study' => 'Field Of Study',
            'communication_address' => 'Communication Address',
            'field_of_excellence' => 'Field Of Excellence',
            'member_of_parliament' => 'Member Of Parliament',
            'state' => 'State',
            'constituency' => 'Constituency',
            'elected_year' => 'Elected Year',
            'department' => 'Department',
            'sector' => 'Sector',
            'representing_authority' => 'Representing Authority',
            'designation' => 'Designation',
            'bank_name' => 'Bank Name',
            'bank_sector' => 'Bank Sector',
            'branch' => 'Branch',
            'company_name' => 'Company Name',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }
}
