<?php

use app\components\UrlHelper;
use app\models\db\Amendment;
use app\models\db\Motion;
use app\models\db\VotingBlock;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \app\components\ProposedProcedureAgenda[] $proposedAgenda
 */

/** @var \app\controllers\ConsultationController $controller */
$controller = $this->context;
$layout     = $controller->layoutParams;

$this->title = \Yii::t('con', 'proposal_title');
$layout->addBreadcrumb(\Yii::t('con', 'proposal_bc'));

echo '<h1>' . Html::encode($this->title) . '</h1>';

?>
    <div class="content">

    </div>
<?php

foreach ($proposedAgenda as $proposedItem) {
    ?>
    <section class="motionHolder motionHolder<?= $proposedItem->blockId ?> proposedProcedureOverview">
        <h2 class="green">
            <?= Html::encode($proposedItem->title) ?>
        </h2>
        <div class="content">
            <?php
            foreach ($proposedItem->votingBlocks as $votingBlock) {
                ?>
                <table class="table votingTable votingTable<?= $votingBlock->getId() ?>">
                    <?php
                    if (count($proposedItem->votingBlocks) > 1 || $votingBlock->voting) {
                        ?>
                        <caption>
                            <?= \Yii::t('con', 'proposal_table_voting') ?>:
                            <?= Html::encode($votingBlock->title) ?>
                        </caption>
                        <?php
                    }
                    ?>
                    <thead>
                    <tr>
                        <th class="prefix"><?= \Yii::t('con', 'proposal_table_motion') ?></th>
                        <th class="initiator"><?= \Yii::t('con', 'proposal_table_initiator') ?></th>
                        <th class="procedure"><?= \Yii::t('con', 'proposal_table_proposal') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($votingBlock->items as $item) {
                        if (is_a($item, Amendment::class)) {
                            $classes = ['amendment' . $item->id];
                        } else {
                            $classes = ['motion' . $item->id];
                        }
                        if ($item->status == \app\models\db\IMotion::STATUS_WITHDRAWN) {
                            $classes[] = 'withdrawn';
                        }
                        ?>
                        <tr class="<?= implode(' ', $classes) ?>">
                            <td><?= Html::a(Html::encode($item->titlePrefix), $item->getViewUrl()) ?></td>
                            <td><?= $item->getInitiatorsStr() ?></td>
                            <td>
                                <?php
                                echo $item->getFormattedProposalStatus();
                                if ($item->proposalExplanation) {
                                    echo '<div class="explanation">';
                                    echo Html::encode($item->proposalExplanation);
                                    echo '</div>';
                                }
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </div>
    </section>
    <?php
}
