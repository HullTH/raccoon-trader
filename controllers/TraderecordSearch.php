<?php

namespace app\controllers;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TradeRecord;

/**
 * TraderecordSearch represents the model behind the search form about `app\models\TradeRecord`.
 */
class TraderecordSearch extends TradeRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'updated_time', 'created_time'], 'integer'],
            [['symbol', 'date'], 'safe'],
            [['open', 'high', 'low', 'close'], 'number'],
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
        $query = TradeRecord::find();

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
            'id' => $this->id,
            'date' => $this->date,
            'open' => $this->open,
            'high' => $this->high,
            'low' => $this->low,
            'close' => $this->close,
            'updated_time' => $this->updated_time,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'symbol', $this->symbol]);

        return $dataProvider;
    }
}
