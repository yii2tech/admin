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

    /**
     * @return array test data.
     */
    public function dataProviderSelectAlertType()
    {
        return [
            // direct :
            ['error', 'alert-danger'],
            ['danger', 'alert-danger'],
            ['success', 'alert-success'],
            ['info', 'alert-info'],
            ['warning', 'alert-warning'],
            // partial :
            ['saveError', 'alert-danger'],
            ['errorSave', 'alert-danger'],
            // default :
            ['unspecified', 'alert-info'],
        ];
    }

    /**
     * @depends testRenderAlert
     * @dataProvider dataProviderSelectAlertType
     *
     * @param string $flashName
     * @param string $expectedCssStyle
     */
    public function testSelectAlertType($flashName, $expectedCssStyle)
    {
        $flashMessage = 'Test flash message';
        Yii::$app->session->addFlash($flashName, $flashMessage);

        $output = Alert::widget();

        $this->assertContains($flashMessage, $output);
        $this->assertContains($expectedCssStyle, $output);
    }
}