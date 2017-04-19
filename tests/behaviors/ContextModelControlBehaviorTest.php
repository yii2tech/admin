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

    public function testGetContext()
    {
        $behavior = $this->createBehavior();

        $context = $behavior->getContext('category');
        $this->assertEquals($behavior->contexts['category'], $context);

        $context = $behavior->getContext();
        $this->assertEquals($behavior->contexts['category'], $context);
    }

    public function testGetContextModels()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $contextModels = $behavior->getContextModels();
        $model = $contextModels['category'];
        $this->assertTrue($model instanceof ItemCategory);
        $this->assertEquals(2, $model->id);
    }

    /**
     * @depends testGetContextModels
     */
    public function testGetContextModel()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $contextModels = $behavior->getContextModels();

        $contextModel = $behavior->getContextModel('category');
        $this->assertEquals($contextModels['category'], $contextModel);

        $contextModel = $behavior->getContextModel();
        $this->assertEquals($contextModels['category'], $contextModel);
    }

    public function testIsContextAcitve()
    {
        $behavior = $this->createBehavior();

        $this->assertFalse($behavior->isContextActive('category'));
        $this->assertFalse($behavior->isContextActive());

        $behavior = $this->createBehavior();
        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $this->assertTrue($behavior->isContextActive('category'));
        $this->assertTrue($behavior->isContextActive());
    }

    /**
     * @depends testGetContextModels
     */
    public function testGetActiveContextsNoContext()
    {
        $behavior = $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams([]);

        $contextModels = $behavior->getContextModels();
        $this->assertEmpty($contextModels);
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

        $this->expectException('yii\web\NotFoundHttpException');
        $contextModels = $behavior->getContextModels();
    }

    /**
     * @depends testGetContextModels
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

        $this->expectException('yii\web\NotFoundHttpException');
        $model = $behavior->findModel(2);
    }

    /**
     * @depends testGetContextModels
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
     * @depends testGetContextModels
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
     * @depends testGetContextModels
     */
    public function testGetContextQueryParams()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $this->assertEquals(['categoryId' => 2], $behavior->getContextQueryParams());
    }

    /**
     * @depends testGetContextModel
     */
    public function testGetContextModelUrl()
    {
        $behavior = $this->createBehavior();

        Yii::$app->request->setQueryParams(['categoryId' => 2]);

        $url = $behavior->getContextModelUrl('category');
        $this->assertEquals(['/category/view', 'id' => 2], $url);
    }

    /**
     * @depends testGetContext
     */
    public function testGetContextUrl()
    {
        $behavior = $this->createBehavior();

        $url = $behavior->getContextUrl('category');
        $this->assertEquals(['/category/index'], $url);
    }
}