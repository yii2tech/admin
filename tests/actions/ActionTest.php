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

    /**
     * Data provider for [[testSetFlash()]]
     * @return array test data
     */
    public function dataProviderSetFlash()
    {
        return [
            [
                'test flash',
                [],
                [
                    'success' => 'test flash',
                ]
            ],
            [
                null,
                [],
                []
            ],
            [
                [
                    'some' => 'test flash 1',
                    'another' => 'test flash 2',
                ],
                [],
                [
                    'some' => 'test flash 1',
                    'another' => 'test flash 2',
                ]
            ],
            [
                [
                    'test default',
                    'some' => 'test key',
                ],
                [],
                [
                    'success' => 'test default',
                    'some' => 'test key',
                ]
            ],
            [
                'parse content {id}',
                [
                    'id' => 56
                ],
                [
                    'success' => 'parse content 56',
                ]
            ],
            [
                function ($params) {
                    return 'callback id = ' . $params['id'];
                },
                [
                    'id' => 56
                ],
                [
                    'success' => 'callback id = 56',
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderSetFlash
     *
     * @param string|array|null $message
     * @param array $params
     * @param array $expectedFlashes
     */
    public function testSetFlash($message, $params, $expectedFlashes)
    {
        $action = new View('view-test', $this->createController());

        $action->setFlash($message, $params);

        $flashes = Yii::$app->session->getAllFlashes();
        $this->assertEquals($expectedFlashes, $flashes);
    }
}