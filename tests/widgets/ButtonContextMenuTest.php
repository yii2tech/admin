<?php

namespace yii2tech\tests\unit\admin\widgets;

use Yii;
use yii2tech\admin\widgets\ButtonContextMenu;
use yii2tech\tests\unit\admin\TestCase;

class ButtonContextMenuTest extends TestCase
{
    public function testEmptyItems()
    {
        $output = ButtonContextMenu::widget([
            'items' => [],
        ]);
        $this->assertEmpty($output);
    }

    public function testRender()
    {
        $output = ButtonContextMenu::widget([
            'items' => [
                ['url' => ['/controller/action1'], 'label' => 'Action 1'],
                ['url' => ['/controller/action2'], 'label' => 'Action 2'],
            ],
        ]);
        $this->assertContains('action1', $output);
        $this->assertContains('action2', $output);
        $this->assertContains('Action 1', $output);
        $this->assertContains('Action 2', $output);
    }

    public function testDefaults()
    {
        Yii::$app->controller = $this->createController();

        $output = ButtonContextMenu::widget([
            'items' => [
                ['index']
            ],
        ]);
        $this->assertContains('Back', $output);
        $this->assertContains('glyphicon glyphicon-arrow-left', $output);

        $output = ButtonContextMenu::widget([
            'items' => [
                ['create']
            ],
        ]);
        $this->assertContains('Create', $output);
        $this->assertContains('glyphicon glyphicon-plus', $output);
    }
}