<?php

namespace yii2tech\tests\unit\admin\data;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ItemSearch extends Model
{
    public $name;
    public $categoryId;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Item::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['categoryId' => $this->categoryId]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}