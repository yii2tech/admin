<?php

namespace yii2tech\tests\unit\admin\data;

use yii\db\ActiveRecord;
use yii2tech\ar\role\RoleBehavior;

/**
 * @property integer $userId
 * @property string $address
 * @property string $bio
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'UserProfile';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'translations' => [
                'class' => RoleBehavior::className(),
                'roleRelation' => 'user',
                'isOwnerSlave' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['userId', 'integer'],
            [['address'], 'required'],
            [['address', 'bio'], 'string'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}