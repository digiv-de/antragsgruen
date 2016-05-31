<?php
use app\models\forms\SiteCreateForm2;
use yii\helpers\Html;

/**
 * @var SiteCreateForm2 $model
 * @var \Callable $t
 */

?>
<div class="step-pane active" id="panelAmendSinglePara" data-tab="stepAmendments">
    <fieldset class="amendSinglePara">
        <legend><?= $t('amend_singlepara_title') ?></legend>
        <div class="description"><?= $t('amend_singlepara_desc') ?></div>
        <div class="options">
            <label class="radio-label">
                <span class="title"><?= $t('amend_singlepara_single') ?></span>
                <span class="description"></span>
                <span class="input">
                    <?= Html::radio('SiteCreateForm2[amendSinglePara]', $model->amendSinglePara, ['value' => 1]); ?>
                </span>
            </label>
            <label class="radio-label">
                <span class="title"><?= $t('amend_singlepara_multi') ?></span>
                <span class="description"></span>
                <span class="input">
                    <?= Html::radio('SiteCreateForm2[amendSinglePara]', !$model->amendSinglePara, ['value' => 0]); ?>
                </span>
            </label>
        </div>
    </fieldset>
    <div class="navigation">
        <button class="btn btn-lg btn-prev"><span class="icon-chevron-left"></span> <?= $t('prev') ?></button>
        <button class="btn btn-lg btn-next btn-primary"><span class="icon-chevron-right"></span> <?= $t('next') ?>
        </button>
    </div>
</div>
