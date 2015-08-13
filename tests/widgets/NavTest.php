<?php

namespace yii2tech\tests\unit\admin\widgets;

use yii2tech\admin\widgets\Nav;
use yii2tech\tests\unit\admin\TestCase;

class NavTest extends TestCase
{
    public function testIcon()
    {
        $output = Nav::widget([
            'items' => [
                [
                    'label' => 'test',
                    'url' => '#',
                    'icon' => 'pencil',
                ],
            ],
        ]);
        $this->assertContains('glyphicon glyphicon-pencil', $output);
    }
}