<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\VariationCreate;
use yii2tech\tests\unit\admin\data\Article;
use yii2tech\tests\unit\admin\TestCase;

class VariationCreateTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        if (!class_exists('yii2tech\ar\variation\VariationBehavior')) {
            $this->markTestSkipped('"yii2tech/ar-variation" extension is required.');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setupTestDbData()
    {
        $this->setupTestVariationDbData();
    }

    /**
     * Runs the action.
     * @param array $config
     * @return array|Response response.
     */
    protected function runAction(array $config = [])
    {
        $action = new VariationCreate('create', $this->createController(['modelClass' => Article::className()]), $config);
        return $action->run();
    }

    // Tests :

    public function testViewForm()
    {
        $response = $this->runAction();
        $this->assertEquals('create', $response['view']);
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitSuccess()
    {
        $newItemName = 'new article name';
        $newItemTitle = 'new item title';
        Yii::$app->request->bodyParams = [
            'Article' => [
                'name' => $newItemName
            ],
            'ArticleTranslation' => [
                [
                    'title' => $newItemTitle,
                    'content' => 'new item content',
                ]
            ]
        ];
        $response = $this->runAction();
        $this->assertEquals('view', $response['url'][0]);

        /* @var $model Article */
        $model = Article::find()->where(['name' => $newItemName])->one();
        $this->assertTrue(is_object($model));
        $variationModel = $model->getTranslations()->andWhere(['title' => $newItemTitle])->one();
        $this->assertTrue(is_object($variationModel));
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitError()
    {
        Yii::$app->request->bodyParams = [
            'Article' => [
                'name' => ''
            ]
        ];
        $response = $this->runAction();
        $this->assertEquals('create', $response['view']);
    }

    /**
     * @depends testViewForm
     */
    public function testLoadDefaultValues()
    {
        $response = $this->runAction([
            'loadDefaultValues' => function (Article $model) {
                $model->name = 'default name';
            }
        ]);
        $model = $response['params']['model'];
        $this->assertEquals('default name', $model->name);
    }
}