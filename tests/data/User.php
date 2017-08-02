<?php

namespace yii2tech\tests\unit\admin\data;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $username
 * @property string $email
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
        ];
    }
}