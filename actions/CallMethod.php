<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;
use yii\base\InvalidConfigException;

/**
 * CallMethod action allows invocation of specified method of the model.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class CallMethod extends Action
{
    /**
     * @var string|callable name of the model method, which should be invoked
     * or callback, which should be executed for the found model.
     * The signature of the callable should be:
     *
     * ```php
     * function ($model) {
     * }
     * ```
     */
    public $method;


    /**
     * Invokes configured method on the specified model.
     * @param string $id the primary key of the model.
     * @return mixed response.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->method === null) {
            throw new InvalidConfigException('"' . get_class($this) . '::method" must be set.');
        }
        if (is_string($this->method)) {
            call_user_func([$model, $this->method]);
        } else {
            call_user_func($this->method, $model);
        }

        $url = array_merge(
            ['view'],
            Yii::$app->request->getQueryParams(),
            ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
        );
        return $this->controller->redirect($url);
    }
}