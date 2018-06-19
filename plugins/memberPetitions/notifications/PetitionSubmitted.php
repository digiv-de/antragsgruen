<?php

namespace app\plugins\memberPetitions\notifications;

use app\components\UrlHelper;
use app\models\db\EMailLog;
use app\models\db\Motion;
use app\models\exceptions\MailNotSent;
use app\components\mail\Tools as MailTools;

class PetitionSubmitted
{
    /**
     * PetitionSubmitted constructor.
     *
     * @param Motion $motion
     * @throws MailNotSent
     * @throws \app\models\exceptions\ServerConfiguration
     */
    public function __construct(Motion $motion)
    {
        $initiator = $motion->getInitiators();
        if (count($initiator) == 0 || $initiator[0]->contactEmail == '') {
            return;
        }

        $motionLink = UrlHelper::absolutizeLink(UrlHelper::createMotionUrl($motion));
        $plain      = str_replace(
            ['%LINK%', '%NAME%', '%NAME_GIVEN%'],
            [$motionLink, $motion->getTitleWithPrefix(), $initiator[0]->getGivenNameOrFull()],
            \Yii::t('memberPetitions', 'submit_petition_text')
        );

        MailTools::sendWithLog(
            EMailLog::TYPE_MEMBER_PETITION,
            $motion->getMyConsultation(),
            trim($initiator[0]->contactEmail),
            $initiator[0]->userId,
            str_replace('%PREFIX%', $motion->getTitleWithPrefix(), \Yii::t('memberPetitions', 'submit_petition_title')),
            $plain
        );
    }
}
