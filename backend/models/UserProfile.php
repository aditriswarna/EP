<?php

namespace backend\models;
use yii;
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
 * @property string $latitude
 * @property string $longitude
 * @property string $occupation
 * @property string $domain_expertise
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property User $userRef
 */
class UserProfile extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ref_id', 'fname', 'lname','mobile'], 'required'],
            [['user_ref_id'], 'integer'],
            [['gender'], 'string'],
            [['fname', 'lname', 'user_image'], 'string', 'max' => 100],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $lastId = Yii::$app->user->identity->id;
            $date = date("Ymdis");
            if(!is_dir("uploads/" . $lastId)) {
            mkdir("uploads/" . $lastId, 0777);
            }
            if($this->user_image){
            $this->user_image->saveAs('uploads/'.$lastId. '/' . $this->user_image->baseName . '_' . $date . '.' . $this->user_image->extension);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }
}
