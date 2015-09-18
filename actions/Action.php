<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

/**
 * Action is a base class for
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Action extends \yii\base\Action
{
    /**
     * @var callable a PHP callable that will be called to return the model corresponding
     * to the specified primary key value. If not set, [[findModel()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($id, $action) {
     *     // $id is the primary key value. If composite primary key, the key values
     *     // will be separated by comma.
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return the model found, or throw an exception if not found.
     */
    public $findModel;


    /**
     * Returns the data model based on the primary key given.
     * If the data model is not found, a 404 HTTP exception will be raised.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return ActiveRecordInterface|Model the model found
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException on invalid configuration
     */
    public function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        } elseif ($this->controller->hasMethod('findModel')) {
            return call_user_func([$this->controller, 'findModel'], $id, $this);
        } else {
            throw new InvalidConfigException('Either "' . get_class($this) . '::findModel" must be set or controller must declare method "findModel()".');
        }
    }

    /**
     * Checks whether action with specified ID exists in owner controller.
     * @param string $id action ID.
     * @return boolean whether action exists or not.
     */
    public function actionExists($id)
    {
        $inlineActionMethodName = 'action' . Inflector::camelize($id);
        if (method_exists($this->controller, $inlineActionMethodName)) {
            return true;
        }
        if (array_key_exists($id, $this->controller->actions())) {
            return true;
        }
        return false;
    }

    /**
     * Sets the return action ID.
     * @param string|null $actionId action ID, if not set current action will be used.
     */
    public function setReturnAction($actionId = null)
    {
        if ($actionId === null) {
            $actionId = $this->id;
        }
        if (strpos($actionId, '/') === false) {
            $actionId = $this->controller->getUniqueId() . '/' . $actionId;
        }
        $sessionKey = '__adminReturnAction';
        Yii::$app->getSession()->set($sessionKey, $actionId);
    }

    /**
     * Returns the ID of action, which should be used for return redirect.
     * If action belongs to another controller or does not exist in current controller - 'index' is returned.
     * @param string $defaultActionId default action ID.
     * @return string action ID.
     */
    public function getReturnAction($defaultActionId = 'index')
    {
        $sessionKey = '__adminReturnAction';
        $actionId = Yii::$app->getSession()->get($sessionKey, $defaultActionId);
        $actionId = trim($actionId, '/');
        if ($actionId === 'index') {
            return $actionId;
        }
        if (strpos($actionId, '/') !== false) {
            $controllerId = StringHelper::dirname($actionId);
            if ($controllerId !== $this->controller->getUniqueId()) {
                return 'index';
            }
            $actionId = StringHelper::basename($actionId);
        }
        if (!$this->actionExists($actionId)) {
            return 'index';
        }
        return $actionId;
    }
}