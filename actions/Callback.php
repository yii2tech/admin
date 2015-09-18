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
 * Callback action allows invocation of specified method of the model.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Callback extends Action
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
    public $modelCallback;


    /**
     * Invokes configured method on the specified model.
     * @param string $id the primary key of the model.
     * @return mixed response.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->modelCallback === null) {
            throw new InvalidConfigException('"' . get_class($this) . '::method" must be set.');
        }
        if (is_string($this->modelCallback)) {
            call_user_func([$model, $this->modelCallback]);
        } else {
            call_user_func($this->modelCallback, $model);
        }


        $actionId = $this->getReturnAction('view');
        $queryParams = Yii::$app->request->getQueryParams();
        unset($queryParams['id']);
        $url = array_merge(
            [$actionId],
            $queryParams
        );
        if ($actionId === 'view') {
            $url = array_merge(
                $url,
                ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
            );
        }

        return $this->controller->redirect($url);
    }
}