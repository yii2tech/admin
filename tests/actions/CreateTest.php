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
     * @return array|Response response.
     */
    protected function runAction()
    {
        $action = new Create('create', $this->createController());
        $action->modelClass = Item::className();
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
        Yii::$app->request->bodyParams = [
            'Item' => [
                'name' => $newItemName
            ]
        ];
        $response = $this->runAction();
        $this->assertEquals('view', $response['url'][0]);
        $this->assertTrue(Item::find()->where(['name' => $newItemName])->exists());
    }

    /**
     * @depends testViewForm
     */
    public function testSubmitError()
    {
        Yii::$app->request->bodyParams = [
            'Item' => [
                'name' => ''
            ]
        ];
        $response = $this->runAction();
        $this->assertEquals('create', $response['view']);
    }
}