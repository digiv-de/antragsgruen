<?php

use app\components\HTMLTools;
use app\components\UrlHelper;
use app\models\db\Amendment;
use app\models\db\Motion;
use app\models\mergeAmendments\Draft;
use yii\helpers\Html;

/**
 * @var Yii\web\View $this
 * @var Motion $newMotion
 * @var Draft $mergingDraft
 * @var \app\models\MotionSectionChanges[] $changes
 */

/** @var \app\controllers\Base $controller */
$controller = $this->context;
$layout     = $controller->layoutParams;

$layout->robotsNoindex = true;
$layout->loadFuelux();
$layout->addBreadcrumb($newMotion->getBreadcrumbTitle(), UrlHelper::createMotionUrl($newMotion));
$layout->addBreadcrumb(Yii::t('amend', 'merge_confirm_title'));
$layout->loadDatepicker();

$title       = str_replace('%TITLE%', $newMotion->motionType->titleSingular, Yii::t('amend', 'merge_title'));
$this->title = $title . ': ' . $newMotion->getTitleWithPrefix();

?>
    <h1><?= Yii::t('amend', 'merge_confirm_title') ?></h1>
<?php

echo Html::beginForm('', 'post', [
    'id'                       => 'motionConfirmForm',
    'class'                    => 'motionMergeConfirmForm',
    'data-antragsgruen-widget' => 'frontend/MotionMergeAmendmentsConfirm'
]);

$odtText = '<span class="glyphicon glyphicon-download"></span> ' . Yii::t('amend', 'merge_confirm_odt');
$odtLink = UrlHelper::createMotionUrl($newMotion, 'view-changes-odt');
?>
    <section class="toolbarBelowTitle mergeConfirmToolbar">
        <div class="styleSwitcher">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="radio" name="diffStyle" value="full" autocomplete="off" checked>
                    <?= Yii::t('amend', 'merge_confirm_full') ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="diffStyle" value="diff" autocomplete="off">
                    <?= Yii::t('amend', 'merge_confirm_diff') ?>
                </label>
            </div>
        </div>
        <div class="export">
            <?= Html::a($odtText, $odtLink, ['class' => 'btn btn-default']) ?>
        </div>
    </section>
<?php

if ($newMotion->canCreateResolution()) {
    echo $this->render('_confirm_resolution_voting', ['motion' => $newMotion]);
}

foreach ($newMotion->getSortedSections(true) as $section) {
    if ($section->getSectionType()->isEmpty()) {
        continue;
    }
    echo '<section class="motionTextHolder">';
    echo '<h2 class="green">' . Html::encode($section->getSettings()->title) . '</h2>';

    echo '<div class="fullText">';
    echo $section->getSectionType()->getSimple(false);
    echo '</div>';

    foreach ($changes as $change) {
        echo '<div class="diffText">';
        if ($change->getSectionId() == $section->sectionId) {
            echo $this->render('@app/views/motion/_view_change_section', ['change' => $change]);
        }
        echo '</div>';
    }

    echo '</section>';
}
if (count($newMotion->replacedMotion->getVisibleAmendments()) > 0) {
    ?>
    <section class="newAmendments fuelux">
        <h2 class="green"><?= Yii::t('amend', 'merge_amend_statuses') ?></h2>
        <div class="content form-horizontal">
            <?php
            foreach ($newMotion->replacedMotion->getVisibleAmendments() as $amendment) {
                //$changeset = (isset($changesets[$amendment->id]) ? $changesets[$amendment->id] : []);
                $changeset = [];
                $data      = 'data-old-status="' . $amendment->status . '"';
                $data      .= ' data-amendment-id="' . $amendment->id . '"';
                $data      .= ' data-changesets="' . Html::encode(json_encode($changeset)) . '"';
                $voting    = $mergingDraft->amendmentVotingData[$amendment->id];
                ?>
                <div class="form-group amendmentStatus" <?= $data ?>>
                    <div class="col-md-2">
                        <div class="amendmentName">
                            <?= Html::encode($amendment->getShortTitle()) ?>
                        </div>
                        <div class="amendSubtitle"><?= Html::encode($amendment->getInitiatorsStr()) ?></div>
                    </div>
                    <div class="col-md-3 statusHolder">
                        <?= HTMLTools::amendmentDiffTooltip($amendment) ?>
                        <label for="amendmentStatus<?= $amendment->id ?>">Status:</label><br>
                        <?php
                        $statusesAll                  = $amendment->getStatusNames();
                        $statuses                     = [
                            Amendment::STATUS_PROCESSED         => $statusesAll[Amendment::STATUS_PROCESSED],
                            Amendment::STATUS_ACCEPTED          => $statusesAll[Amendment::STATUS_ACCEPTED],
                            Amendment::STATUS_REJECTED          => $statusesAll[Amendment::STATUS_REJECTED],
                            Amendment::STATUS_MODIFIED_ACCEPTED => $statusesAll[Amendment::STATUS_MODIFIED_ACCEPTED],
                        ];
                        $statuses[$amendment->status] = $statusesAll[$amendment->status];
                        if (isset($mergingDraft->amendmentStatuses[$amendment->id])) {
                            $statusPre = $mergingDraft->amendmentStatuses[$amendment->id];
                        } else {
                            $statusPre = Amendment::STATUS_PROCESSED;
                        }
                        $opts = ['id' => 'amendmentStatus' . $amendment->id];
                        echo HTMLTools::fueluxSelectbox('amendStatus[' . $amendment->id . ']', $statuses, $statusPre, $opts, true);
                        ?>
                    </div>
                    <div class="col-md-3">
                        <label for="votesComment<?= $amendment->id ?>"><?= Yii::t('amend', 'merge_new_votes_comment') ?></label>
                        <input class="form-control" name="amendVotes[<?= $amendment->id ?>][comment]" type="text"
                               id="votesComment<?= $amendment->id ?>"
                               value="<?= Html::encode($voting->comment ? $voting->comment : '') ?>">
                    </div>
                    <div class="col-md-1">
                        <label for="votesYes<?= $amendment->id ?>"><?= Yii::t('amend', 'merge_amend_votes_yes') ?></label>
                        <input class="form-control" name="amendVotes[<?= $amendment->id ?>][yes]" type="number"
                               id="votesYes<?= $amendment->id ?>"
                               value="<?= Html::encode($voting->votesYes ? $voting->votesYes : '') ?>">
                    </div>
                    <div class="col-md-1">
                        <label for="votesNo<?= $amendment->id ?>"><?= Yii::t('amend', 'merge_amend_votes_no') ?></label>
                        <input class="form-control" name="amendVotes[<?= $amendment->id ?>][no]" type="number"
                               id="votesNo<?= $amendment->id ?>"
                               value="<?= Html::encode($voting->votesNo ? $voting->votesNo : '') ?>">
                    </div>
                    <div class="col-md-1">
                        <label for="votesAbstention<?= $amendment->id ?>"><?= Yii::t('amend', 'merge_amend_votes_abstention') ?></label>
                        <input class="form-control" name="amendVotes[<?= $amendment->id ?>][abstention]" type="number"
                               id="votesAbstention<?= $amendment->id ?>"
                               value="<?= Html::encode($voting->votesAbstention ? $voting->votesAbstention : '') ?>">
                    </div>
                    <div class="col-md-1">
                        <label for="votesInvalid<?= $amendment->id ?>"><?= Yii::t('amend', 'merge_amend_votes_invalid') ?></label>
                        <input class="form-control" name="amendVotes[<?= $amendment->id ?>][invalid]" type="number"
                               id="votesInvalid<?= $amendment->id ?>"
                               value="<?= Html::encode($voting->votesInvalid ? $voting->votesInvalid : '') ?>">
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
    <?php
}

?>
    <div class="content">
        <div style="float: right;">
            <button type="submit" name="confirm" class="btn btn-success">
                <span class="glyphicon glyphicon-ok-sign"></span>
                <?= Yii::t('base', 'save') ?>
            </button>
        </div>
        <div style="float: left;">
            <button type="submit" name="modify" class="btn">
                <span class="glyphicon glyphicon-remove-sign"></span>
                <?= Yii::t('amend', 'button_correct') ?>
            </button>
        </div>
    </div>
<?php
echo Html::endForm();
