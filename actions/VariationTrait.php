<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2tech\ar\variation\VariationBehavior;

/**
 * VariationTrait provides common functionality for the actions, which handle models with [[VariationBehavior]] attached.
 *
 * @see https://github.com/yii2tech/ar-variation
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
trait VariationTrait
{
    /**
     * @var array list of model variation behavior names, which should be affected by the action.
     * If empty - all instances of [[VariationBehavior]] will be picked up.
     */
    public $variationNames = [];

    /**
     * @var array cache for the variation model batches.
     */
    private $_variationModelBatches = [];


    /**
     * Get variation models for the main one in batches.
     * @param Model|ActiveRecordInterface $model main model instance.
     * @return array list of variation models in format: behaviorName => Model[]
     */
    protected function getVariationModelBatches($model)
    {
        $key = serialize($model->getPrimaryKey());
        if (!isset($this->_variationModelBatches[$key])) {
            $this->_variationModelBatches[$key] = $this->findVariationModelBatches($model);
        }
        return $this->_variationModelBatches[$key];
    }

    /**
     * @param Model|ActiveRecordInterface $model
     * @return array list of variation models in format: behaviorName => Model[]
     */
    protected function findVariationModelBatches($model)
    {
        $variationModels = [];
        foreach ($model->getBehaviors() as $name => $behavior) {
            if ((empty($this->variationNames) && ($behavior instanceof VariationBehavior)) || in_array($name, $this->variationNames)) {
                $variationModels[$name] = $behavior->getVariationModels();
            }
        }
        return $variationModels;
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
        foreach ($this->getVariationModelBatches($model) as $variationName => $variationModels) {
            if (!Model::loadMultiple($variationModels, $data)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Performs AJAX validation of the main model and variation models via [[ActiveForm::validate()]].
     * @param Model $model main model.
     * @return array the error message array indexed by the attribute IDs.
     */
    protected function performAjaxValidation($model)
    {
        $response = ActiveForm::validate($model);

        // validate variations manually for tabular input matching :
        foreach ($this->getVariationModelBatches($model) as $variationModels) {
            foreach ($variationModels as $index => $variationModel) {
                /* @var $variationModel Model */
                if (!$variationModel->validate()) {
                    foreach ($model->getErrors() as $attribute => $errors) {
                        $response[Html::getInputId($model, '[' . $index . ']' . $attribute)] = $errors;
                    }
                }
            }
        }
        return $response;
    }
}