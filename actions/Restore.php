<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;

/**
 * Restore actions performs restoration of the "soft" deleted record.
 *
 * @see https://github.com/yii2tech/ar-softdelete
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Restore extends Action
{
    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @return mixed response.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        $model->restore();

        $actionId = $this->getReturnAction('view');
        $queryParams = Yii::$app->request->getQueryParams();
        unset($queryParams['id']);
        $url = array_merge(
            [$actionId],
            $queryParams
        );
        if ($actionId === 'view') {
            $url = array_merge(
                $url,
                ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
            );
        }

        return $this->controller->redirect($url);
    }
}