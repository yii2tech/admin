<?php

namespace yii2tech\tests\unit\admin\behaviors;

use Yii;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\data\ItemCategory;
use yii2tech\tests\unit\admin\data\ItemSearch;
use yii2tech\tests\unit\admin\TestCase;
use yii2tech\admin\behaviors\ContextModelControlBehavior;

class ContextModelControlBehaviorTest extends TestCase
{
    /**
     * @param array $config
     * @return ContextModelControlBehavior
     */
    protected function createBehavior($config = [])
    {
        return new ContextModelControlBehavior(array_merge(
            [
                'modelClass' => Item::className(),
                'searchModelClass' => ItemSearch::className(),
                'contexts' => [
                    'category' => [
                        'class' => ItemCategory::className(),
                        'attribute' => 'categoryId',
                    ],
                ],
            ],
            $config
        ));
    }

    // Tests :

    public function testGetActiveContexts()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $activeContexts = $behavior->getActiveContexts();
        $model = $activeContexts['category']['model'];
        $this->assertTrue($model instanceof ItemCategory);
        $this->assertEquals(2, $model->id);
    }

    /**
     * @depends testGetActiveContexts
     */
    public function testGetActiveContextsNoContext()
    {
        $behavior = $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams([]);

        $activeContexts = $behavior->getActiveContexts();
        $this->assertEmpty($activeContexts);
    }

    /**
     * @depends testGetActiveContextsNoContext
     */
    public function testGetActiveContextsNoRequiredContext()
    {
        $behavior = new ContextModelControlBehavior([
            'contexts' => [
                'category' => [
                    'class' => ItemCategory::className(),
                    'attribute' => 'categoryId',
                    'required' => true,
                ],
            ],
        ]);

        Yii::$app->request->setQueryParams([]);

        $this->setExpectedException('yii\web\NotFoundHttpException');
        $activeContexts = $behavior->getActiveContexts();
    }

    /**
     * @depends testGetActiveContexts
     */
    public function testFindModel()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $model = $behavior->findModel(2);
        $this->assertNotEmpty($model);
        $this->assertEquals(2, $model->categoryId);
    }

    /**
     * @depends testFindModel
     */
    public function testFindModelContextMissmatch()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 1]);

        $this->setExpectedException('yii\web\NotFoundHttpException');
        $model = $behavior->findModel(2);
    }

    /**
     * @depends testGetActiveContexts
     */
    public function testNewModel()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $model = $behavior->newModel();
        $this->assertTrue($model instanceof $behavior->modelClass);
        $this->assertEquals(2, $model->categoryId);
    }

    /**
     * @depends testGetActiveContexts
     */
    public function testNewSearchModel()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $model = $behavior->newSearchModel();
        $this->assertTrue($model instanceof ItemSearch);
        $this->assertEquals(2, $model->categoryId);
    }

    /**
     * @depends testGetActiveContexts
     */
    public function testGetActiveContextQueryParams()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $this->assertEquals(['categoryId' => 2], $behavior->getActiveContextQueryParams());
    }
}