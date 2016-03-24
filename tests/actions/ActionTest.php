<?php

namespace yii2tech\tests\unit\admin\actions;

use Yii;
use yii2tech\admin\actions\View;
use yii2tech\tests\unit\admin\TestCase;

class ActionTest extends TestCase
{
    public function testActionExists()
    {
        $action = new View('view-test', $this->createController());
        $action->controller->actions = [
            'view-test' => $action
        ];

        $this->assertTrue($action->actionExists('inline-action'));
        $this->assertTrue($action->actionExists('view-test'));
        $this->assertFalse($action->actionExists('unexisting'));
    }

    public function testSetupReturnAction()
    {
        $action = new View('view-test', $this->createController());
        $action->controller->actions = [
            'view-test' => $action
        ];

        $action->setReturnAction();
        $this->assertEquals('view-test', $action->getReturnAction());

        $action->setReturnAction('unexisting-controller/view-test');
        $this->assertEquals('index', $action->getReturnAction());

        $action->setReturnAction('unexisting-action');
        $this->assertEquals('index', $action->getReturnAction());
    }

    public function testCreateReturnUrl()
    {
        $action = new View('view-test', $this->createController());

        $this->assertEquals(['index'], $action->createReturnUrl('index'));
        $this->assertEquals(['view'], $action->createReturnUrl('view'));

        $action->returnUrl = 'http://some.url';
        $this->assertEquals($action->returnUrl, $action->createReturnUrl('view'));

        $action->returnUrl = ['some/action', 'param' => 'foo'];
        $this->assertEquals($action->returnUrl, $action->createReturnUrl('view'));

        $action->returnUrl = function ($model) {
            return ['callback', 'model' => $model];
        };
        $this->assertEquals(['callback', 'model' => 'test'], $action->createReturnUrl('view', 'test'));
    }
}