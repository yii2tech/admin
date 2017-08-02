<?php

namespace yii2tech\tests\unit\admin\data;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 */
class ItemCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ItemCategory';
    }
}