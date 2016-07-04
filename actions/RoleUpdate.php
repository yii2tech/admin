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
 * RoleUpdate action supports updating of the existing model with [yii2tech/ar-role](https://github.com/yii2tech/ar-role) behavior applied.
 *
 * @see https://github.com/yii2tech/ar-role
 * @see RoleTrait
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class RoleUpdate extends Update
{
    use RoleTrait;

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /* @var $model Model|ActiveRecordInterface|\yii2tech\ar\role\RoleBehavior */
        $model = $this->findModel($id);
        $model->scenario = $this->scenario;

        if ($this->load($model, Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $this->performAjaxValidation($model);
            }
            if ($model->save()) {
                $this->setFlash($this->flash, ['id' => $id, 'model' => $model]);
                return $this->controller->redirect($this->createReturnUrl('view', $model));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}