<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;

/**
 * ModelControlBehavior model management for the CRUD operations. It allows funding and creating target model,
 * as well as creating search model for the listing.
 *
 * This behavior should be attached to [[\yii\web\Controller]] instance.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class ModelControlBehavior extends Behavior
{
    /**
     * @var string the model class name. This property must be set.
     * The model class must implement [[ActiveRecordInterface]].
     * @see newModel()
     * @see findModel()
     */
    public $modelClass;
    /**
     * @var string|callable class name of the model which should be used as search model.
     * This can be a PHP callback of following signature:
     *
     * ```php
     * function (\yii\web\Controller $controller) {
     *     //return new \yii\base\Model;
     * }
     * ```
     *
     * If not set it will be composed using [[modelClass]].
     * If [yii2tech/ar-search](https://github.com/yii2tech/ar-search) extension is installed -
     * [[\yii2tech\ar\search\ActiveSearchModel]] instance will be used as a search model.
     *
     * @see newSearchModel()
     */
    public $searchModelClass;


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
        if ($this->modelClass === null) {
            throw new InvalidConfigException('"' . get_class($this) . '::$modelClass" must be set.');
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('yii2tech-admin', "Object not found: {id}", ['id' => $id]));
    }

    /**
     * Creates new model instance.
     * @return ActiveRecordInterface|Model new model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function newModel()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException('"' . get_class($this) . '::$modelClass" must be set.');
        }
        $modelClass = $this->modelClass;
        return new $modelClass();
    }

    /**
     * Creates new search model instance.
     * @return Model new search model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function newSearchModel()
    {
        $modelClass = $this->searchModelClass;
        if ($modelClass === null) {
            if ($this->modelClass === null) {
                throw new InvalidConfigException('Either "' . get_class($this) . '::$searchModelClass" or "' . get_class($this) . '::$modelClass" must be set.');
            }

            if (class_exists('yii2tech\ar\search\ActiveSearchModel')) {
                $searchModel = new \yii2tech\ar\search\ActiveSearchModel();
                $searchModel->setModel($this->modelClass);
                return $searchModel;
            }

            $modelClass = $this->modelClass . 'Search';
        } elseif (!is_string($modelClass)) {
            return call_user_func($modelClass, $this->owner);
        }

        return new $modelClass();
    }
}