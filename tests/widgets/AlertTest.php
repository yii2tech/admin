<?php

namespace yii2tech\tests\unit\admin\widgets;

use Yii;
use yii2tech\admin\widgets\Alert;
use yii2tech\tests\unit\admin\TestCase;

class AlertTest extends TestCase
{
    public function testNoAlert()
    {
        $output = Alert::widget();
        $this->assertEmpty($output);
    }

    public function testRenderAlert()
    {
        $flashMessage = 'Test flash message';
        Yii::$app->session->addFlash('error', $flashMessage);

        $output = Alert::widget();

        $this->assertContains($flashMessage, $output);
        $this->assertContains('alert-danger', $output);
    }
}