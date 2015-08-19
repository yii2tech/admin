<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CrudController implements a common set of actions for supporting CRUD for ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class CrudController extends Controller
{
    /**
     * @var string the model class name. This property must be set.
     * The model class must implement [[ActiveRecordInterface]].
     */
    public $modelClass;
    /**
     * @var string class name of the model which should be used as search model.
     * If not set it will be composed using [[modelClass]].
     */
    public $searchModelClass;
    /**
     * @var string the scenario used for updating a model.
     * @see Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii2tech\admin\actions\Index',
                'modelClass' => $this->modelClass,
                'searchModelClass' => $this->searchModelClass,
            ],
            'view' => [
                'class' => 'yii2tech\admin\actions\View',
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => 'yii2tech\admin\actions\Create',
                'modelClass' => $this->modelClass,
            ],
            'update' => [
                'class' => 'yii2tech\admin\actions\Update',
                'modelClass' => $this->modelClass,
            ],
            'delete' => [
                'class' => 'yii2tech\admin\actions\Delete',
                'modelClass' => $this->modelClass,
            ],
        ];
    }

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
            throw new InvalidConfigException('"' . get_class($this) . '::modelClass" must be set.');
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
        } else {
            throw new NotFoundHttpException(Yii::t('admin', "Object not found: {id}", ['id' => $id]));
        }
    }

    /**
     * Creates new model instance.
     * @return ActiveRecordInterface|Model new model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function newModel()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException('"' . get_class($this) . '::modelClass" must be set.');
        }
        $modelClass = $this->modelClass;
        return new $modelClass();
    }

    /**
     * Creates new model instance.
     * @return Model new model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function newSearchModel()
    {
        $modelClass = $this->searchModelClass;
        if ($modelClass === null) {
            if ($this->modelClass === null) {
                throw new InvalidConfigException('Either "' . get_class($this) . '::searchModelClass" or "' . get_class($this) . '::modelClass" must be set.');
            }
            $modelClass = $this->modelClass . 'Search';
        }

        return new $modelClass();
    }
}