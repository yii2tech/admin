<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\Restore;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class RestoreTest extends TestCase
{
    /**
     * Runs the action.
     * @param mixed $id
     * @return array|Response response.
     */
    protected function runAction($id)
    {
        $action = new Restore('restore', $this->createController());
        return $action->run($id);
    }

    // Tests :

    public function testDelete()
    {
        Item::findOne(1)->softDelete();
        $response = $this->runAction(1);
        $this->assertEquals('view', $response['url'][0]);
        $this->assertEquals(false, Item::findOne(1)->isDeleted);
    }

    public function testMissingModel()
    {
        $this->expectException('yii\web\NotFoundHttpException');
        $response = $this->runAction(9999);
    }
}