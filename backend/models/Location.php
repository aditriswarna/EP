<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $location_id
 * @property string $location_name
 *
 * @property AdminLocation[] $adminLocations
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location_name'], 'required'],
            [['location_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'location_id' => 'Location ID',
            'location_name' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminLocations()
    {
        return $this->hasMany(AdminLocation::className(), ['location_ref_id' => 'location_id']);
    }
}
