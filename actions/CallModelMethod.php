<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;

/**
 * CallModelMethod action allows invocation of specified method of the model.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class CallModelMethod extends Action
{
    /**
     * @var string name of the model method, which should be invoked.
     */
    public $method;


    /**
     * Invokes configured method on the specified model.
     * @param string $id the primary key of the model.
     * @return mixed response.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        call_user_func([$model, $this->method]);

        $url = array_merge(
            ['view'],
            Yii::$app->request->getQueryParams(),
            ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
        );
        return $this->controller->redirect($url);
    }
}