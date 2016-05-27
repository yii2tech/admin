<?php

namespace yii2tech\tests\unit\admin;

use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\FileHelper;
use yii2tech\tests\unit\admin\data\Controller;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\data\Session;

/**
 * Base class for the test cases.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();

        $this->setupTestDbData();

        $testFilePath = $this->getTestFilePath();
        FileHelper::createDirectory($testFilePath);
    }

    protected function tearDown()
    {
        $testFilePath = $this->getTestFilePath();
        FileHelper::removeDirectory($testFilePath);

        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'assetManager' => [
                    'basePath' => $this->getTestFilePath(),
                ],
                'session' => [
                    'class' => Session::className(),
                ],
                'user' => [
                    'identityClass' => 'app\models\User',
                ],
                'request' => [
                    'hostInfo' => 'http://domain.com',
                ],
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'forceTranslation' => false,
                        ],
                    ],
                ],
            ],
        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * @param array $config controller config.
     * @return Controller controller instance.
     */
    protected function createController($config = [])
    {
        return new Controller('test', Yii::$app, array_merge(['modelClass' => Item::className()], $config));
    }

    /**
     * Returns the test file path.
     * @return string file path.
     */
    protected function getTestFilePath()
    {
        return Yii::getAlias('@yii2tech/tests/unit/admin/runtime') . DIRECTORY_SEPARATOR . getmypid();
    }

    /**
     * Setup tables for test ActiveRecord
     */
    protected function setupTestDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :

        $table = 'ItemCategory';
        $columns = [
            'id' => 'pk',
            'name' => 'string',
        ];
        $db->createCommand()->createTable($table, $columns)->execute();

        $table = 'Item';
        $columns = [
            'id' => 'pk',
            'name' => 'string',
            'categoryId' => 'integer',
            'isDeleted' => 'boolean DEFAULT 0',
        ];
        $db->createCommand()->createTable($table, $columns)->execute();

        // Data :

        $db->createCommand()->batchInsert('ItemCategory', ['name'], [
            ['category1'],
            ['category2'],
        ])->execute();

        $db->createCommand()->batchInsert('Item', ['name', 'categoryId'], [
            ['item1', 1],
            ['item2', 2],
        ])->execute();
    }

    /**
     * Setup tables for test ActiveRecord with `yii2tech\ar\variation\VariationBehavior`
     */
    protected function setupTestVariationDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :

        $table = 'Article';
        $columns = [
            'id' => 'pk',
            'name' => 'string',
        ];
        $db->createCommand()->createTable($table, $columns)->execute();

        $table = 'Language';
        $columns = [
            'id' => 'pk',
            'name' => 'string',
            'locale' => 'string',
        ];
        $db->createCommand()->createTable($table, $columns)->execute();

        $table = 'ArticleTranslation';
        $columns = [
            'articleId' => 'integer',
            'languageId' => 'integer',
            'title' => 'string',
            'content' => 'string',
            'PRIMARY KEY(articleId, languageId)'
        ];
        $db->createCommand()->createTable($table, $columns)->execute();

        // Data :

        $db->createCommand()->batchInsert('Language', ['name', 'locale'], [
            ['English', 'en'],
            ['German', 'de'],
        ])->execute();

        $db->createCommand()->batchInsert('Article', ['name'], [
            ['article1'],
            ['article2'],
        ])->execute();

        $db->createCommand()->batchInsert('ArticleTranslation', ['articleId', 'languageId', 'title', 'content'], [
            [1, 1, 'article1-en', 'article1-content-en'],
            [1, 2, 'article1-de', 'article1-content-de'],
            [2, 2, 'article2-de', 'article2-content-de'],
        ])->execute();
    }
}
