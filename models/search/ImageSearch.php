<?php

namespace app\models\search;

use app\models\db\Image;
use app\models\query\ImageQuery;
use rootlocal\crud\components\SearchModelInterface;
use yii\data\ActiveDataProvider;

/**
 * Class ImageSearch
 *
 * @property ImageQuery $query
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\models\search
 */
class ImageSearch extends Image implements SearchModelInterface
{
    /** @var ImageQuery|null */
    private ?ImageQuery $_query = null;


    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['old_name', 'name'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = []): ActiveDataProvider
    {

        $query = $this->getQuery();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_DESC],
            'attributes' => [

                'id' => [
                    'asc' => [self::tableName() . '.id' => SORT_ASC],
                    'desc' => [self::tableName() . '.id' => SORT_DESC],
                    'default' => SORT_ASC,
                ],

                'name' => [
                    'asc' => [self::tableName() . '.name' => SORT_ASC],
                    'desc' => [self::tableName() . '.name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],

                'old_name' => [
                    'asc' => [self::tableName() . '.old_name' => SORT_ASC],
                    'desc' => [self::tableName() . '.old_name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],

                'created_at' => [
                    'asc' => [self::tableName() . '.created_at' => SORT_ASC],
                    'desc' => [self::tableName() . '.created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
            ]
        ]);

        $dataProvider->setPagination([
            'pageSize' => 10,
            'defaultPageSize' => 10,
            'forcePageParam' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->old_name)) {
            $query->andFilterWhere(['ilike', self::tableName() . '.old_name', $this->old_name]);
        }

        if (!empty($this->created_at)) {
            $start = strtotime($this->created_at . ' 00:00:01');
            $stop = strtotime($this->created_at . ' 23:59:59');

            if ($start !== false) {
                $query->andFilterWhere(['>=', self::tableName() . '.created_at', $start]);
            }

            if ($stop !== false) {
                $query->andFilterWhere(['<=', self::tableName() . '.created_at', $stop]);
            }
        }

        return $dataProvider;
    }

    /**
     * @return ImageQuery|null
     */
    public function getQuery(): ?ImageQuery
    {
        if ($this->_query === null) {
            $this->_query = self::find();
        }

        return $this->_query;
    }

    /**
     * @param ImageQuery|null $query
     */
    public function setQuery(?ImageQuery $query): void
    {
        $this->_query = $query;
    }


}