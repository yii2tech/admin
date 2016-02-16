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
 * VariationCreate action supports creation of the new model with [yii2tech/ar-variation](https://github.com/yii2tech/ar-variation) behavior applied.
 *
 * @see https://github.com/yii2tech/ar-variation
 * @see VariationTrait
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class VariationCreate extends Create
{
    use VariationTrait;

    /**
     * @inheritdoc
     */
    public function run()
    {
        /* @var $model Model|ActiveRecordInterface|\yii2tech\ar\variation\VariationBehavior */
        $model = $this->newModel();
        $model->scenario = $this->scenario;

        if ($this->load($model, Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $this->performAjaxValidation($model);
            }
            if ($model->save()) {
                $actionId = $this->getReturnAction('view');
                $url = array_merge(
                    [$actionId],
                    Yii::$app->request->getQueryParams()
                );
                if ($actionId === 'view') {
                    $url = array_merge(
                        $url,
                        ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
                    );
                }
                return $this->controller->redirect($url);
            }
        } else {
            $this->loadModelDefaultValues($model);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}