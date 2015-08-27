<?php

namespace yii2tech\tests\unit\admin\data;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $categoryId
 * @property boolean $isDeleted
 */
class Item extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
        ];
    }

    /**
     * Emulates soft-delete behavior
     * @see https://github.com/yii2tech/ar-softdelete
     * @return integer number of updated records
     */
    public function softDelete()
    {
        return $this->updateAttributes(['isDeleted' => true]);
    }

    /**
     * Emulates soft-delete restoration behavior
     * @see https://github.com/yii2tech/ar-softdelete
     * @return integer number of updated records
     */
    public function restore()
    {
        return $this->updateAttributes(['isDeleted' => false]);
    }
}