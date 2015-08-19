<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;
use yii2tech\admin\CrudController;

/**
 * ContextModelControlBehavior allows usage of the filtering context.
 * For example: items per specific category, comments by particular user etc.
 * This controller finds and creates models including possible filtering context.
 *
 * @property array $activeContexts
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class ContextModelControlBehavior extends ModelControlBehavior
{
    /**
     * @var array specifies possible contexts.
     * The array key is considered as context name, value - as context config.
     * Config should contain following keys:
     *
     * - class: string, class name of context model.
     * - attribute: string, name of model attribute, which refers to the context model primary key.
     * - controller: string, id of controller, which manage context models.
     * - required: boolean, whether this context is mandatory for this controller or optional.
     *
     * For example:
     *
     * ```php
     * [
     *     'user' => [
     *         'class' => 'User',
     *         'attribute' => 'userId',
     *         'controller' => 'user',
     *     ]
     * ]
     * ```
     */
    public $contexts;

    /**
     * @var array stores the active context, which means the ones, which passed through the query params.
     * Content of this array will be similar to [[contexts]], but each value will contains
     * key 'model'. This key contains the instance of the context model.
     */
    private $_activeContexts;


    /**
     * @return array
     */
    public function getActiveContexts()
    {
        if (!is_array($this->_activeContexts)) {
            $this->_activeContexts = $this->findActiveContexts();
        }
        return $this->_activeContexts;
    }

    /**
     * @param array $activeContexts
     */
    public function setActiveContexts($activeContexts)
    {
        $this->_activeContexts = $activeContexts;
    }

    /**
     * Initializes all active contexts.
     * @return array active contexts.
     * @throws InvalidConfigException on invalid configuration.
     * @throws NotFoundHttpException on missing required context.
     */
    protected function findActiveContexts()
    {
        $activeContexts = [];
        if (is_array($this->contexts)) {
            $queryParams = Yii::$app->request->getQueryParams();
            foreach ($this->contexts as $name => $config) {
                if (empty($config['attribute'])) {
                    throw new InvalidConfigException('Context "attribute" parameter must be set.');
                }
                $attribute = $config['attribute'];
                if (array_key_exists($attribute, $queryParams)) {
                    $config['model'] = $this->findContextModel($config, $queryParams[$attribute]);
                    $activeContexts[$name] = $config;
                } elseif (isset($config['required']) && $config['required']) {
                    throw new NotFoundHttpException(Yii::t('admin', "Context {name} required.", ['name' => $name]));
                }
            }
        }
        return $activeContexts;
    }

    /**
     * Initializes a particular active context.
     * @param array $config context configuration.
     * @param mixed $id context model primary key value.
     * @return ActiveRecordInterface context model instance.
     * @throws InvalidConfigException on invalid configuration.
     * @throws NotFoundHttpException if context model not found.
     */
    protected function findContextModel($config, $id)
    {
        if (empty($config['class'])) {
            throw new InvalidConfigException('Context "class" parameter must be set.');
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $config['class'];
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
            throw new NotFoundHttpException(Yii::t('admin', "Context object not found: {id}", ['id' => $id]));
        }
    }

    // Override :

    /**
     * @inheritdoc
     */
    public function findModel($id)
    {
        $model = parent::findModel($id);
        foreach ($this->getActiveContexts() as $contextName => $activeContext) {
            $attribute = $activeContext['attribute'];
            /* @var $contextModel ActiveRecordInterface */
            $contextModel = $activeContext['model'];
            if ($model->$attribute != $contextModel->getPrimaryKey()) {
                throw new NotFoundHttpException(Yii::t('admin', "Object not found: {id}", ['id' => $contextModel->getPrimaryKey()]));
            }
        }
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function newModel()
    {
        $model = parent::newModel();
        foreach ($this->getActiveContexts() as $activeContext) {
            $attribute = $activeContext['attribute'];
            /* @var $contextModel ActiveRecordInterface */
            $contextModel = $activeContext['model'];
            $model->$attribute = $contextModel->getPrimaryKey();
        }
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function newSearchModel()
    {
        $model = parent::newSearchModel();
        foreach ($this->getActiveContexts() as $activeContext) {
            $attribute = $activeContext['attribute'];
            /* @var $contextModel ActiveRecordInterface */
            $contextModel = $activeContext['model'];
            $model->$attribute = $contextModel->getPrimaryKey();
        }
        return $model;
    }
}