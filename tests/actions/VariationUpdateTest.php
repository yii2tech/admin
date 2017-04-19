<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\VariationUpdate;
use yii2tech\tests\unit\admin\data\Article;
use yii2tech\tests\unit\admin\TestCase;

class VariationUpdateTest extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        if (!class_exists('yii2tech\ar\variation\VariationBehavior')) {
            $this->markTestSkipped('"yii2tech/ar-variation" extension is required.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function setupTestDbData()
    {
        $this->setupTestVariationDbData();
    }

    /**
     * Runs the action.
     * @param mixed $id
     * @return array|Response response.
     */
    protected function runAction($id)
    {
        $action = new VariationUpdate('update', $this->createController(['modelClass' => Article::className()]));
        return $action->run($id);
    }

    // Tests :

    public function testViewForm()
    {
        $response = $this->runAction(1);
        $this->assertEquals('update', $response['view']);
    }

    public function testMissingModel()
    {
        $this->expectException('yii\web\NotFoundHttpException');
        $response = $this->runAction(9999);
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
                ]
            ]
        ];
        $response = $this->runAction(1);
        $this->assertEquals('view', $response['url'][0]);

        $model = Article::findOne(1);
        $this->assertEquals($newItemName, $model->name);
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
        $response = $this->runAction(1);
        $this->assertEquals('update', $response['view']);
    }
}