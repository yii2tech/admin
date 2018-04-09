<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\StringHelper;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * Since 1.0.3 alert type is determined via wildcard match, so messages could be set as following:
 *
 * ```php
 * Yii::$app->session->setFlash('saveSuccess', 'This is the success message');
 * Yii::$app->session->setFlash('errorSave', 'This is the error message');
 * ```
 *
 * @see \yii\bootstrap\Alert
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Alert extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     *
     * - $key is the case-insensitive wildcard pattern for the name of the session flash variable.
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning).
     */
    public $alertTypes = [
        '*error*' => 'alert-danger',
        '*danger*' => 'alert-danger',
        '*success*' => 'alert-success',
        '*warning*' => 'alert-warning',
        '*' => 'alert-info',
    ];
    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        $alerts = [];
        foreach ($flashes as $type => $data) {
            foreach ($this->alertTypes as $pattern => $css) {
                if (StringHelper::matchWildcard($pattern, $type, ['caseSensitive' => false])) {
                    $data = (array) $data;
                    foreach ($data as $i => $message) {
                        /* initialize css class for each alert box */
                        $this->options['class'] = $css . $appendCss;

                        /* assign unique id to each alert box */
                        $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;

                        $alerts[] = \yii\bootstrap\Alert::widget([
                            'body' => $message,
                            'closeButton' => $this->closeButton,
                            'options' => $this->options,
                        ]);
                    }

                    $session->removeFlash($type);
                    break;
                }
            }
        }

        return implode("\n", $alerts);
    }
}
