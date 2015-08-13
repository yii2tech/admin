<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\widgets;

use yii\bootstrap\Html;

/**
 * Enhanced version of [[\yii\bootstrap\Nav]], which simplifies icon rendering.
 * This widget adds support for 'icon' key in item array, which will be used as icon short name.
 *
 * @see \yii\bootstrap\Nav
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Nav extends \yii\bootstrap\Nav
{
    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        if (is_array($item)) {
            if (isset($item['icon'])) {
                if (isset($item['label'])) {
                    $label = $item['label'];
                    $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
                    if ($encodeLabel) {
                        $label = Html::encode($label);
                    }
                } else {
                    $label = '';
                }
                $item['encode'] = false;
                $label = Html::icon($item['icon']) . ' ' . $label;
                $item['label'] = $label;
            }
        }
        return parent::renderItem($item);
    }
}