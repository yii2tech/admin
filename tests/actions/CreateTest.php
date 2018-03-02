<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\Create;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class CreateTest extends TestCase
{
    /**
     * Runs the action.
     * @param array $config
     * @return array|Response response.
     */
    protected function runAction(array $config = [])
    {
        $action = new Create('create', $this->createController(), $config);
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
        $newItemName = 'new item name';
        Yii::$app->request->setParsedBody([
            'Item' => [
                'name' => $newItemName
            ]
        ]);

        $response = $this->runAction();
        $this->assertEquals('view', $response['url'][0]);
        $this->assertTrue(Item::find()->where(['name' => $newItemName])->exists());
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitError()
    {
        Yii::$app->request->withParsedBody([
            'Item' => [
                'name' => ''
            ]
        ]);
        $response = $this->runAction();
        $this->assertEquals('create', $response['view']);
    }

    /**
     * @depends testViewForm
     */
    public function testLoadDefaultValues()
    {
        $response = $this->runAction([
            'loadDefaultValues' => function (Item $model) {
                $model->name = 'default name';
            }
        ]);
        $model = $response['params']['model'];
        $this->assertEquals('default name', $model->name);
    }
}