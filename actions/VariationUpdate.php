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
use yii\widgets\ActiveForm;

/**
 * VariationUpdate action supports updating of the existing model with [yii2tech/ar-variation](https://github.com/yii2tech/ar-variation) behavior applied.
 *
 * @see https://github.com/yii2tech/ar-variation
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class VariationUpdate extends Update
{
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /* @var $model Model|ActiveRecordInterface|\yii2tech\ar\variation\VariationBehavior */
        $model = $this->findModel($id);
        $model->scenario = $this->scenario;

        $post = Yii::$app->request->post();
        if ($model->load($post) && Model::loadMultiple($model->getVariationModels(), $post)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return call_user_func_array([ActiveForm::className(), 'validate'], array_merge([$model, $model->getVariationModels()]));
            }
            if ($model->save()) {
                $url = array_merge(
                    ['view'],
                    Yii::$app->request->getQueryParams(),
                    ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
                );
                return $this->controller->redirect($url);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}