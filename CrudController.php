<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin;

use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
     */
    public $modelClass;
    /**
     * @var string the search model class name. This property must be set.
     */
    public $searchModelClass;
    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see \yii\base\Model::scenarios()
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
}