<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property integer $subscription_id
 * @property string $email
 * @property string $ip_address
 * @property string $added_on
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_on'], 'safe'],
            [['email'], 'string', 'max' => 250],
            [['ip_address'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subscription_id' => 'Subscription ID',
            'email' => 'Email',
            'ip_address' => 'Ip Address',
            'added_on' => 'Added On',
        ];
    }

    /**
     * @inheritdoc
     * @return SubscriptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubscriptionQuery(get_called_class());
    }
}
