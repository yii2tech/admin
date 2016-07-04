<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\widgets\ActiveForm;
use yii2tech\ar\role\RoleBehavior;

/**
 * RoleTrait provides common functionality for the actions, which handle models with [[RoleBehavior]] attached.
 *
 * @see https://github.com/yii2tech/ar-role
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
trait RoleTrait
{
    /**
     * @var array list of model role behavior names, which should be affected by the action.
     * If empty - all instances of [[RoleBehavior]] will be picked up.
     */
    public $roleNames = [];

    /**
     * @var array cache for the role models.
     */
    private $_roleModels = [];


    /**
     * Get role models for the main one.
     * @param Model|ActiveRecordInterface $model main model instance.
     * @return Model[] list of role models
     */
    public function getRoleModels($model)
    {
        $key = serialize($model->getPrimaryKey());
        if (!isset($this->_roleModels[$key])) {
            $this->_roleModels[$key] = $this->findRoleModels($model);
        }
        return $this->_roleModels[$key];
    }

    /**
     * @param Model|ActiveRecordInterface $model
     * @return array list of variation models in format: behaviorName => Model[]
     */
    private function findRoleModels($model)
    {
        $roleModels = [];
        foreach ($model->getBehaviors() as $name => $behavior) {
            if ((empty($this->roleNames) && ($behavior instanceof RoleBehavior)) || in_array($name, $this->roleNames)) {
                $roleModels[$name] = $behavior->getRoleRelationModel();
            }
        }
        return $roleModels;
    }

    /**
     * Populates the main model and variation models with input data.
     * @param Model $model main model instance.
     * @param array $data the data array to load, typically `$_POST` or `$_GET`.
     * @return boolean whether expected forms are found in `$data`.
     */
    protected function load($model, $data)
    {
        if (!$model->load($data)) {
            return false;
        }
        foreach ($this->getRoleModels($model) as $roleModel) {
            if (!$roleModel->load($data)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Performs AJAX validation of the main model and role models via [[ActiveForm::validate()]].
     * @param Model $model main model.
     * @return array the error message array indexed by the attribute IDs.
     */
    protected function performAjaxValidation($model)
    {
        $roleModels = $this->getRoleModels($model);
        $models = array_merge([$model], $roleModels);
        return call_user_func_array([ActiveForm::className(), 'validate'], $models);
    }
}