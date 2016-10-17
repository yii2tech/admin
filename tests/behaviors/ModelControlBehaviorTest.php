<?php

namespace yii2tech\tests\unit\admin\behaviors;

use yii\base\Model;
use yii2tech\admin\behaviors\ModelControlBehavior;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\data\ItemSearch;
use yii2tech\tests\unit\admin\TestCase;

class ModelControlBehaviorTest extends TestCase
{
    public function testFindModel()
    {
        $behavior = new ModelControlBehavior();
        $behavior->modelClass = Item::className();

        $model = $behavior->findModel(2);
        $this->assertNotEmpty($model);
        $this->assertEquals(Item::findOne(2), $model);

        $this->setExpectedException('yii\web\NotFoundHttpException');
        $model = $behavior->findModel(999);
    }

    public function testNewModel()
    {
        $behavior = new ModelControlBehavior();
        $behavior->modelClass = Item::className();

        $model = $behavior->newModel();
        $this->assertTrue($model instanceof $behavior->modelClass);
    }

    public function testNewSearchModel()
    {
        $behavior = new ModelControlBehavior();
        $behavior->searchModelClass = ItemSearch::className();

        $model = $behavior->newSearchModel();
        $this->assertTrue($model instanceof ItemSearch);
    }

    /**
     * @depends testNewSearchModel
     */
    public function testNewSearchModelCallback()
    {
        $behavior = new ModelControlBehavior();
        $controller = new \stdClass();
        $controller->id = 'search';
        $behavior->owner = $controller;

        $behavior->searchModelClass = function ($controller) {
            return new ItemSearch(['scenario' => $controller->id]);
        };

        $model = $behavior->newSearchModel();
        $this->assertTrue($model instanceof ItemSearch);
        $this->assertEquals('search', $model->scenario);
    }

    /**
     * @depends testNewSearchModel
     */
    public function testNewSearchModelAutoDetectSearchModel()
    {
        $behavior = new ModelControlBehavior();
        $behavior->modelClass = Item::className();

        $model = $behavior->newSearchModel();
        $this->assertTrue($model instanceof Model);
    }
}