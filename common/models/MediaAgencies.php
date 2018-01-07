<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media_agencies".
 *
 * @property integer $media_agency_id
 * @property string $media_agency_name
 * @property integer $status
 * @property string $created_date
 * @property integer $created_by
 */
class MediaAgencies extends \yii\db\ActiveRecord
{
    public $from_date, $to_date;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_agencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_agency_name'], 'required'],
            [['status', 'created_by'], 'integer'],
            [['created_date'], 'safe'],
            [['media_agency_name'], 'string', 'max' => 50],
            [['from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'media_agency_id' => 'Media Agency ID',
            'media_agency_name' => 'Media Agency Name',
            'status' => 'Status',
            'created_date' => 'Created Date',
            'created_by' => 'Created By',
        ];
    }
}
