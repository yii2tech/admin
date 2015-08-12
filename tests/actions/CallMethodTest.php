<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\CallMethod;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class CallMethodTest extends TestCase
{
    /**
     * Runs the action.
     * @param mixed $id
     * @param string|callable $method
     * @return array|Response response.
     */
    protected function runAction($id, $method = 'delete')
    {
        $action = new CallMethod('call-method', $this->createController());
        $action->modelClass = Item::className();
        $action->method = $method;
        return $action->run($id);
    }

    // Tests :

    public function testCallMethod()
    {
        $response = $this->runAction(1);
        $this->assertEquals('view', $response['url'][0]);
        $this->assertNull(Item::findOne(1));
    }

    public function testCallback()
    {
        $response = $this->runAction(1, function($model) {$model->delete();});
        $this->assertEquals('view', $response['url'][0]);
        $this->assertNull(Item::findOne(1));
    }

    public function testMissingModel()
    {
        $this->setExpectedException('yii\web\NotFoundHttpException');
        $response = $this->runAction(9999);
    }
}