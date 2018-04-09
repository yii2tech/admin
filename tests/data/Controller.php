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
     * @var array actions configuration, which will be returned by [[actions()]] method.
     */
    public $actions = [];


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'dataModel' => [
                'class' => ModelControlBehavior::class,
                'modelClass' => Item::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function render($view, $params = [])
    {
        return [
            'view' => $view,
            'params' => $params,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function redirect($url, $statusCode = 302)
    {
        return [
            'url' => $url,
            'statusCode' => $statusCode,
        ];
    }

    /**
     * Test inline action.
     * @return mixed response
     */
    public function actionInlineAction()
    {
        return '';
    }

    /**
     * View action stub.
     * @param integer $id
     * @return mixed response
     */
    public function actionView($id)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return $this->actions;
    }
}