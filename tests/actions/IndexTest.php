<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\Index;
use yii2tech\tests\unit\admin\data\Item;
use yii2tech\tests\unit\admin\TestCase;

class IndexTest extends TestCase
{
    /**
     * Runs the action.
     * @return array|Response response.
     */
    protected function runAction()
    {
        $action = new Index('index', $this->createController());
        $action->modelClass = Item::className();
        return $action->run();
    }

    // Tests :

    public function testView()
    {
        $response = $this->runAction();
        $this->assertEquals('index', $response['view']);
    }
}