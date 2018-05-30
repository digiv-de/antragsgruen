<?php

namespace app\plugins\neos;

use app\components\UrlHelper;
use app\models\db\Motion;
use app\models\layoutHooks\HooksAdapter;
use yii\helpers\Html;

class LayoutHooks extends HooksAdapter
{
    /**
     * @param string $before
     * @param Motion $motion
     * @return string
     */
    public function beforeMotionView($before, Motion $motion)
    {
        if ($motion->canMergeAmendments()) {
            $classes = 'content neosMotionMerge';
            if ($motion->replacedMotion) {
                $before  .= '<div class="neosMotionMergeOpener"><button class="btn btn-link" type="button">';
                $before  .= \Yii::t('neos', 'merge_opener');
                $before  .= '</button></div>';
                $classes .= ' hidden';
            }
            $before .= '<div class="' . $classes . '"><div class="alert alert-info">';
            $before .= '<p>' . \Yii::t('neos', 'merge_hint') . '</p>';
            $before .= '<div style="text-align: center; margin-top: 15px;">' . Html::a(
                    \Yii::t('neos', 'merge_btn'),
                    UrlHelper::createMotionUrl($motion, 'merge-amendments-init'),
                    ['class' => 'btn btn-primary']
                ) . '</div>';
            $before .= '</div></div>';
        }

        return $before;
    }
}
