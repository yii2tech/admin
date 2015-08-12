<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;

/**
 * Delete action performs the deleting of the existing record.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Delete extends Action
{
    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @return mixed response.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        $queryParams = Yii::$app->request->getQueryParams();
        unset($queryParams['id']);
        $url = array_merge(
            ['index'],
            $queryParams
        );
        return $this->controller->redirect($url);
    }
}