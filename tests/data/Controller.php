<?php

namespace yii2tech\tests\unit\admin\data;

use yii2tech\admin\behaviors\ModelControlBehavior;

/**
 * Test controller class.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'dataModel' => [
                'class' => ModelControlBehavior::className(),
                'modelClass' => Item::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function render($view, $params = [])
    {
        return [
            'view' => $view,
            'params' => $params,
        ];
    }

    /**
     * @inheritdoc
     */
    public function redirect($url, $statusCode = 302)
    {
        return [
            'url' => $url,
            'statusCode' => $statusCode,
        ];
    }
}