<?php

namespace frontend\models;
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
    /**
     * @inheritdoc
     */
    //public $latitude; 
    //public $longitude;
    public $is_profile_set;
    
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
            [['user_ref_id', 'fname', 'lname', 'dob'], 'required'],
            [['user_ref_id', 'modified_by', 'is_profile_set'], 'integer'],
            [['dob', 'modified_date'], 'safe'],
            [['gender'], 'string'],
            [['fname', 'lname'], 'string', 'max' => 100],
            [['citizen', 'domicile', 'latitude', 'longitude', 'occupation', 'domain_expertise'], 'string', 'max' => 30],
            [['current_location'], 'string', 'max' => 100],
            [['user_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $lastId = Yii::$app->user->identity->id;
            $folder = Yii::getAlias('@upload') .'/frontend/web/uploads/profile_images/' . $lastId . '/';
            $date = date("Ymdis");
            if(!is_dir($folder)) {
            mkdir($folder, 0777);
            }
            if($this->user_image){
            $this->user_image->saveAs($folder . $this->user_image->baseName . '_' . $date . '.' . $this->user_image->extension);
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
