<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\Update;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class UpdateTest extends TestCase
{
    /**
     * Runs the action.
     * @param mixed $id
     * @return array|Response response.
     */
    protected function runAction($id)
    {
        $action = new Update('update', $this->createController());
        $action->modelClass = Item::className();
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
        $this->setExpectedException('yii\web\NotFoundHttpException');
        $response = $this->runAction(9999);
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
        $response = $this->runAction(1);
        $this->assertEquals('view', $response['url'][0]);
        $this->assertEquals($newItemName, Item::findOne(1)->name);
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
        $response = $this->runAction(1);
        $this->assertEquals('update', $response['view']);
    }
}