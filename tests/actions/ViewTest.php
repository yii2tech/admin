<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii\web\Response;
use yii2tech\admin\actions\View;
use yii2tech\tests\unit\admin\TestCase;

class ViewTest extends TestCase
{
    /**
     * Runs the action.
     * @param mixed $id
     * @return array|Response response.
     */
    protected function runAction($id)
    {
        $action = new View('view', $this->createController());
        return $action->run($id);
    }

    // Tests :

    public function testView()
    {
        $response = $this->runAction(1);
        $this->assertEquals('view', $response['view']);
    }

    public function testMissingModel()
    {
        $this->setExpectedException('yii\web\NotFoundHttpException');
        $response = $this->runAction(9999);
    }
}