<?php

namespace common\models;
use yii\base\Model;
use yii\web\UploadedFile;

class ProfileImage extends Model
{
    /**
     * @var UploadedFile
     */
    public $user_image;

    public function rules()
    {
        return [
            [['user_image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
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
            	$this->user_image->saveAs($folder. $this->user_image->baseName . '_' . $date . '.' . $this->user_image->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
