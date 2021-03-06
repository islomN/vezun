<?php

namespace common\models\searchmodels;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TransportInfo as TransportInfoModel;

/**
 * TransportInfo represents the model behind the search form of `common\models\TransportInfo`.
 */
class TransportInfo extends TransportInfoModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'date_id', 'transport_type_id', 'transport_cargo_info_id', 'user_info_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = TransportInfoModel::find()->orderBy('created_at desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_id' => $this->date_id,
            'transport_type_id' => $this->transport_type_id,
            'transport_cargo_info_id' => $this->transport_cargo_info_id,
            'user_info_id' => $this->user_info_id,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
