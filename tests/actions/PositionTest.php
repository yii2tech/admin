<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\Position;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class PositionTest extends TestCase
{
    /**
     * Runs the action.
     * @param mixed $id
     * @param mixed $position
     * @return array|Response response.
     */
    protected function runAction($id, $position)
    {
        $action = new Position('position', $this->createController());
        Yii::$app->request->setQueryParams(['at' => $position]);
        return $action->run($id);
    }

    // Tests :

    public function testMoveNamedPosition()
    {
        $response = $this->runAction(1, 'prev');
        $this->assertEquals('view', $response['url'][0]);
        $this->assertEquals('prev', Item::findOne(1)->name);

        $this->runAction(1, 'next');
        $this->assertEquals('next', Item::findOne(1)->name);

        $this->runAction(1, 'first');
        $this->assertEquals('first', Item::findOne(1)->name);

        $this->runAction(1, 'last');
        $this->assertEquals('last', Item::findOne(1)->name);
    }

    public function testMoveToPosition()
    {
        $response = $this->runAction(1, '22');
        $this->assertEquals('view', $response['url'][0]);
        $this->assertEquals('22', Item::findOne(1)->name);
    }

    public function testMoveUnknownPosition()
    {
        $this->expectException(\yii\web\BadRequestHttpException::class);
        $response = $this->runAction(1, 'invalid');
    }

    public function testMissingModel()
    {
        $this->expectException(\yii\web\NotFoundHttpException::class);
        $response = $this->runAction(9999, 'prev');
    }
}