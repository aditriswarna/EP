<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[MediaAgencies]].
 *
 * @see MediaAgencies
 */
class MediaAgenciesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MediaAgencies[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MediaAgencies|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
