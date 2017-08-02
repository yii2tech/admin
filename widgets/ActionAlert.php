<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * ActionAlert renders an action proposition to the web user based on particular condition, usually a session flag.
 * Each alert consists of text message and a link to the related action.
 * Each alert is rendered using [[\yii\bootstrap\Alert]] widget.
 *
 * Note: in order to make alert link (button) appear at the right side, you should add following style to your CSS:
 *
 * ```css
 * .btn-action-alert {
 *     float: right;
 *     margin-top: -6px;
 * }
 * ```
 *
 * @see \yii\bootstrap\Alert
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0.1
 */
class ActionAlert extends Widget
{
    /**
     * @var array[] list of actions to be displayed. Each item should be an array of following structure:
     *
     * - title: string, optional, alert title as HTML (it will not be html-encoded). If not set - it will be composed from
     *   action key in array using [[Inflector]].
     * - url: array|string, action URL.
     * - visible: bool|callable, optional, condition, which check should be successful in order to make alert visible.
     *   If not set - visibility is determined by value of the session variable, which names is equal to the action key in array.
     * - linkText: string, optional, action link text.
     * - linkOptions: array, optional, action link HTML options.
     *
     * For example:
     *
     * ```php
     * [
     *     'cacheFlushRequired' => [
     *         'title' => 'Cache Flush Required',
     *         'url' => ['/maintenance/flush-cache'],
     *     ],
     *     'siteMapRegenerationRequired' => [
     *         'title' => 'Sitemap regeneration Required',
     *         'linkText' => 'Regenerate Sitemap',
     *         'url' => ['/sitemap/generate'],
     *         'visible' => function () {
     *             return SiteMapActiveRecord::find()->max('createdAt') < PageActiveRecord::find->max('createdAt');
     *         },
     *     ],
     * ]
     * ```
     */
    public $actions = [];
    /**
     * @var string alert body layout. Following placeholders are available:
     *
     * - {title} - Alert title text.
     * - {link} - link button HTML.
     */
    public $layout = '{title} {link}';
    /**
     * @var array the HTML attributes for the alert widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [
        'class' => 'alert-warning'
    ];
    /**
     * @var array|false the options for rendering the alert close button tag.
     */
    public $closeButton = false;


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $alerts = [];

        foreach ($this->actions as $key => $action) {
            $action = array_merge(
                [
                    'title' => '',
                    'url' => '',
                    'linkText' => '',
                    'linkOptions' => [
                        'class' => 'btn btn-warning',
                    ],
                ],
                $action
            );

            if (!isset($action['visible'])) {
                $action['visible'] = function () use ($key) {
                    return (bool)Yii::$app->session->get($key, false);
                };
            }

            if (is_bool($action['visible'])) {
                if ($action['visible']) {
                    continue;
                }
            } else {
                if (!call_user_func($action['visible'])) {
                    continue;
                }
            }

            Html::addCssClass($action['linkOptions'], ['widget' => 'btn-action-alert']);

            if (empty($action['title'])) {
                $action['title'] = Inflector::camel2words(Inflector::humanize($key));
            }
            if (empty($action['linkText'])) {
                $action['linkText'] = Yii::t('yii2tech-admin', 'Do it now');
            }

            $body = strtr($this->layout, [
                '{title}' => $action['title'],
                '{link}' => Html::a($action['linkText'], $action['url'], $action['linkOptions']),
            ]);

            $alerts[] = Alert::widget([
                'body' => $body,
                'closeButton' => $this->closeButton,
                'options' => $this->options,
            ]);
        }

        return implode("\n", $alerts);
    }
}