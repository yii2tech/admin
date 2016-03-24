<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\web\Response;

/**
 * VariationUpdate action supports updating of the existing model with [yii2tech/ar-variation](https://github.com/yii2tech/ar-variation) behavior applied.
 *
 * @see https://github.com/yii2tech/ar-variation
 * @see VariationTrait
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class VariationUpdate extends Update
{
    use VariationTrait;

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /* @var $model Model|ActiveRecordInterface|\yii2tech\ar\variation\VariationBehavior */
        $model = $this->findModel($id);
        $model->scenario = $this->scenario;

        if ($this->load($model, Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $this->performAjaxValidation($model);
            }
            if ($model->save()) {
                return $this->controller->redirect($this->createReturnUrl('view', $model));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}