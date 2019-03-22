<?php

namespace common\models\searchmodels;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Letter;

/**
 * LetterSearch represents the model behind the search form of `common\models\Letter`.
 */
class LetterSearch extends Letter
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['key', 'word_ru', 'word_oz', 'word_uz'], 'safe'],
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
        $query = Letter::find();

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
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'word_ru', $this->word_ru])
            ->andFilterWhere(['like', 'word_oz', $this->word_oz])
            ->andFilterWhere(['like', 'word_uz', $this->word_uz]);

        return $dataProvider;
    }
}
