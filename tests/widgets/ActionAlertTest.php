<?php

namespace yii2tech\tests\unit\admin\widgets;

use Yii;
use yii2tech\admin\widgets\ActionAlert;
use yii2tech\tests\unit\admin\TestCase;

class ActionAlertTest extends TestCase
{
    public function testNoAlert()
    {
        $output = ActionAlert::widget([
            'actions' => [
                'test' => [],
            ],
        ]);
        $this->assertEmpty($output);
    }

    public function testRenderAlert()
    {
        Yii::$app->session->set('testKey', true);

        $output = ActionAlert::widget([
            'actions' => [
                'testKey' => [
                    'title' => 'Test title',
                    'url' => 'http://test.com',
                    'linkText' => 'ButtonText',
                ],
            ],
        ]);

        $this->assertContains('Test title', $output);
        $this->assertContains('ButtonText', $output);
        $this->assertContains('href="http://test.com"', $output);
    }

    /**
     * @depends testNoAlert
     * @depends testRenderAlert
     */
    public function testCustomVisibility()
    {
        $output = ActionAlert::widget([
            'actions' => [
                'testKey' => [
                    'title' => 'Test title',
                    'url' => 'http://test.com',
                    'visible' => function () {
                        return false;
                    },
                ],
            ],
        ]);
        $this->assertEmpty($output);

        $output = ActionAlert::widget([
            'actions' => [
                'testKey' => [
                    'title' => 'Test title',
                    'url' => 'http://test.com',
                    'visible' => function () {
                        return true;
                    },
                ],
            ],
        ]);
        $this->assertNotEmpty($output);
    }
}