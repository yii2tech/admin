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
     * {@inheritdoc]
     */
    public static function tableName()
    {
        return 'Item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
        ];
    }

    // Soft Delete :

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

    /**
     * Emulates safe-delete behavior
     * @see https://github.com/yii2tech/ar-softdelete
     * @return integer number of updated records
     */
    public function safeDelete()
    {
        return $this->delete();
    }

    // Position :

    /**
     * Emulates position behavior
     * @see https://github.com/yii2tech/ar-position
     * @return boolean success
     */
    public function movePrev()
    {
        return $this->updateAttributes(['name' => 'prev']) > 0;
    }

    /**
     * Emulates position behavior
     * @see https://github.com/yii2tech/ar-position
     * @return boolean success
     */
    public function moveNext()
    {
        return $this->updateAttributes(['name' => 'next']) > 0;
    }

    /**
     * Emulates position behavior
     * @see https://github.com/yii2tech/ar-position
     * @return boolean success
     */
    public function moveFirst()
    {
        return $this->updateAttributes(['name' => 'first']) > 0;
    }

    /**
     * Emulates position behavior
     * @see https://github.com/yii2tech/ar-position
     * @return boolean success
     */
    public function moveLast()
    {
        return $this->updateAttributes(['name' => 'last']) > 0;
    }

    /**
     * Emulates position behavior
     * @see https://github.com/yii2tech/ar-position
     * @param integer $position
     * @return boolean success
     */
    public function moveToPosition($position)
    {
        return $this->updateAttributes(['name' => $position]) > 0;
    }
}