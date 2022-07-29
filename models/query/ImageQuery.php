<?php

namespace app\models\query;

use app\models\db\Image;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Image]].
 * @see \app\models\db\Image
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\models\query
 */
class ImageQuery extends ActiveQuery
{


    /**
     * @param string $name
     * @return ImageQuery
     */
    public function name(string $name): ImageQuery
    {
        return $this->andWhere([Image::tableName() . '.name' => $name])
            ->orderBy([Image::tableName() . '.name' => SORT_DESC]);
    }

    /**
     * @return array|Image[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Image|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}