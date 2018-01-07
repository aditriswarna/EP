<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MediaAgencies;

/**
 * MediaSearch represents the model behind the search form about `common\models\MediaAgencies`.
 */
class MediaSearch extends MediaAgencies
{
    public $from_date, $to_date;
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_agency_id', 'created_by'], 'integer'],
            [['media_agency_name', 'created_date','status','from_date','to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {        
        $query = MediaAgencies::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'media_agency_id' => $this->media_agency_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'media_agency_name', $this->media_agency_name]);
        $query->andFilterWhere(['>=', 'created_date', $this->from_date])
              ->andFilterWhere(['<=', 'created_date', $this->to_date]);
       
        return $dataProvider;
    }
}
