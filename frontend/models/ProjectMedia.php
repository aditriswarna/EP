<?php

namespace frontend\models;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "project_media".
 *
 * @property integer $project_media_id
 * @property integer $project_ref_id
 * @property string $document_name
 *
 * @property Projects $projectRef
 */
class ProjectMedia extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'project_media';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['project_ref_id', 'document_name'], 'required'],
            [['project_ref_id'], 'integer'],
            [['document_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'project_media_id' => 'Project Media ID',
            'project_ref_id' => 'Project Ref ID',
            'document_name' => 'Document Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRef() {
        return $this->hasOne(Projects::className(), ['project_id' => 'project_ref_id']);
    }

    public static function imageUpload($projectImages, $lastInsertId) {

        $folder = Yii::getAlias('@upload') . '/frontend/web/uploads/project_images/' . $lastInsertId . '/';
        $folder_thumb = Yii::getAlias('@upload') . '/frontend/web/uploads/project_images/' . $lastInsertId . '/thumb/';
        // make the directory to store the pic:
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
            chmod($folder, 0777);
            mkdir($folder_thumb, 0777);
            chmod($folder_thumb, 0777);
        }
        foreach ($projectImages as $image => $pic) {
            $ext = pathinfo($pic->name, PATHINFO_EXTENSION);
            $name = pathinfo($pic->name, PATHINFO_FILENAME);
            $projectImageFileName = $name . '_' . date("Ymdis") . '.' . $ext;
            if ($pic->saveAs($folder . $projectImageFileName) && Image::thumbnail($folder . $projectImageFileName, 200, 200)->save($folder_thumb . $projectImageFileName, ['quality' => 80])) {
                // add it to the main model now
                $projectMedia = new ProjectMedia();
                $projectMedia->project_ref_id = $lastInsertId;
                $projectMedia->document_name = $projectImageFileName;
                $projectMedia->document_type = 'projectImage';
                $projectMedia->save();
            } else {
                die('Image upload failed');
            }
        }
        return true;
    }

    public static function mediaUpload($projectMediaFiles, $lastInsertId) {

        $folder = Yii::getAlias('@upload') . '/frontend/web/uploads/project_images/' . $lastInsertId . '/';


        // make the directory to store the pic:
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
        }

        foreach ($projectMediaFiles as $img => $val) {
            ini_set('upload_max_filesize','40M');
            $ext = pathinfo($val->name, PATHINFO_EXTENSION);
            $name = pathinfo($val->name, PATHINFO_FILENAME);
            $projectMediaFileName = $name . '_' . date("Ymdis") . '.' . $ext;
            
            if ($val->saveAs($folder . $projectMediaFileName)) {
                // add it to the main model now
                $projectMedia = new ProjectMedia();
                $projectMedia->project_ref_id = $lastInsertId;
                $projectMedia->document_name = $projectMediaFileName;
                $projectMedia->document_type = 'projectDocument';
                $projectMedia->save();
            } else {
                die('Document upload failed');
            }
        }
    }

    public static function saveembedlink($embed_videos, $lastInsertId) {
        foreach ($embed_videos as $embed_video) {
            if ($embed_video != "") {
                preg_match('/src="([^"]+)"/',$embed_video, $match);
               
                if(isset($match) && @$match[1]!="")
                {
                $projectMedia = new ProjectMedia();
                $projectMedia->project_ref_id = $lastInsertId;
                $projectMedia->document_name = $match[1];
                $projectMedia->document_type = 'projectVideos';
                $projectMedia->save(false);
                }
            }
        }
    }

}
