<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Clients;

/**
 * ClientsSearch represents the model behind the search form about `common\models\Clients`.
 */
class ClientsSearch extends Clients
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'conference', 'callerid'], 'integer'],
            [['name', 'channel', 'mutte', 'video'], 'safe'],
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
        $query = Clients::find();

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
            'conference' => $this->conference,
            'callerid' => $this->callerid,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'channel', $this->channel])
            ->andFilterWhere(['like', 'mutte', $this->mutte])
            ->andFilterWhere(['like', 'video', $this->video]);

        return $dataProvider;
    }
}
