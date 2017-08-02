<?php

namespace yii2tech\tests\unit\admin\data;

use yii\db\ActiveRecord;

/**
 * @property integer $articleId
 * @property integer $languageId
 * @property string $title
 * @property string $content
 */
class ArticleTranslation extends ActiveRecord
{
    /**
     * {@inheritdoc]
     */
    public static function tableName()
    {
        return 'ArticleTranslation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['title', 'safe'],
            ['content', 'safe'],
            ['languageId', 'required'],
        ];
    }
}