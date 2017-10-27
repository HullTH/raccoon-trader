<?php

namespace app\controllers;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TradeConfig;

/**
 * TradeconfigSearch represents the model behind the search form about `app\models\TradeConfig`.
 */
class TradeconfigSearch extends TradeConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'safe'],
            [['updated_time', 'created_time'], 'integer'],
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
        $query = TradeConfig::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'updated_time' => $this->updated_time,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
