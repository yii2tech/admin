<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\Delete;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class DeleteTest extends TestCase
{
    /**
     * Runs the action.
     * @param mixed $id
     * @return array|Response response.
     */
    protected function runAction($id)
    {
        $action = new Delete('delete', $this->createController());
        $action->modelClass = Item::className();
        return $action->run($id);
    }

    // Tests :

    public function testDelete()
    {
        $response = $this->runAction(1);
        $this->assertEquals('index', $response['url'][0]);
        $this->assertNull(Item::findOne(1));
    }

    public function testMissingModel()
    {
        $this->setExpectedException('yii\web\NotFoundHttpException');
        $response = $this->runAction(9999);
    }
}