<?php

namespace app\models\db;

use app\components\EmailNotifications;
use app\models\events\MotionSupporterEvent;
use yii\base\Event;

/**
 * @package app\models\db
 *
 * @property int $id
 * @property int $motionId
 * @property int $position
 * @property int $userId
 * @property string $role
 * @property string $comment
 * @property int $personType
 * @property string $name
 * @property string $organization
 * @property string $resolutionDate
 * @property string $contactName
 * @property string $contactEmail
 * @property string $contactPhone
 * @property string $dateCreation
 * @property string $extraData
 *
 * @property User $user
 * @property Motion $motion
 */
class MotionSupporter extends ISupporter
{
    const EVENT_SUPPORTED = 'supported_official'; // Called if a new support (like, dislike, official) was created; no initiators
    private static $handlersAttached = false;

    public function init()
    {
        parent::init();

        $this->on(static::EVENT_AFTER_UPDATE, [$this, 'onSaved'], null, false);
        $this->on(static::EVENT_AFTER_INSERT, [$this, 'onSaved'], null, false);
        $this->on(static::EVENT_AFTER_DELETE, [$this, 'onSaved'], null, false);

        if (!static::$handlersAttached) {
            static::$handlersAttached = true;
            // This handler should be called at the end of the event chain
            Event::on(MotionSupporter::class, MotionSupporter::EVENT_SUPPORTED, [$this, 'checkOfficialSupportNumberReached'], null, true);
        }
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        /** @var \app\models\settings\AntragsgruenApp $app */
        $app = \Yii::$app->params;
        return $app->tablePrefix . 'motionSupporter';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotion()
    {
        return $this->hasOne(Motion::class, ['id' => 'motionId']);
    }

    /**
     * @return int[]
     */
    public static function getMyAnonymousSupportIds()
    {
        return \Yii::$app->session->get('anonymous_motion_supports', []);
    }

    /**
     * @param MotionSupporter $support
     */
    public static function addAnonymouslySupportedMotion($support)
    {
        $pre   = \Yii::$app->session->get('anonymous_motion_supports', []);
        $pre[] = IntVal($support->id);
        \Yii::$app->session->set('anonymous_motion_supports', $pre);
    }

    /**
     * @param Motion $motion
     * @param User|null $user
     * @param string $name
     * @param string $orga
     * @param int $role
     * @param string $gender
     */
    public static function createSupport(Motion $motion, $user, $name, $orga, $role, $gender = '')
    {
        $maxPos = 0;
        if ($user) {
            foreach ($motion->motionSupporters as $supp) {
                if ($supp->userId === $user->id) {
                    $motion->unlink('motionSupporters', $supp, true);
                } elseif ($supp->position > $maxPos) {
                    $maxPos = $supp->position;
                }
            }
        } else {
            $alreadySupported = static::getMyAnonymousSupportIds();
            foreach ($motion->motionSupporters as $supp) {
                if (in_array($supp->id, $alreadySupported)) {
                    $motion->unlink('motionSupporters', $supp, true);
                } elseif ($supp->position > $maxPos) {
                    $maxPos = $supp->position;
                }
            }
        }

        $support               = new MotionSupporter();
        $support->motionId     = IntVal($motion->id);
        $support->userId       = ($user ? IntVal($user->id) : null);
        $support->name         = $name;
        $support->organization = $orga;
        $support->position     = $maxPos + 1;
        $support->role         = $role;
        $support->dateCreation = date('Y-m-d H:i:s');
        $support->setExtraDataEntry('gender', ($gender !== '' ? $gender : null));
        $support->save();

        if (!$user) {
            static::addAnonymouslySupportedMotion($support);
        }

        $motion->refresh();
        $motion->flushViewCache();

        $support->trigger(MotionSupporter::EVENT_SUPPORTED, new MotionSupporterEvent($support));
    }

    public static function checkOfficialSupportNumberReached(MotionSupporterEvent $event): void
    {
        $support = $event->supporter;
        if ($support->role !== static::ROLE_SUPPORTER) {
            return;
        }
        /** @var Motion $motion */
        $motion = $support->getIMotion();

        $supportType = $motion->getMyMotionType()->getMotionSupportTypeClass();
        if (count($motion->getSupporters()) == $supportType->getSettingsObj()->minSupporters) {
            EmailNotifications::sendMotionSupporterMinimumReached($motion);
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['motionId', 'position', 'role'], 'required'],
            [['id', 'motionId', 'position', 'userId', 'personType'], 'number'],
            [['resolutionDate', 'contactName', 'contactEmail', 'contactPhone'], 'safe'],
            [['position', 'comment', 'personType', 'name', 'organization'], 'safe'],
        ];
    }

    /**
     * @return IMotion
     */
    public function getIMotion()
    {
        if (Consultation::getCurrent()) {
            $motion = Consultation::getCurrent()->getMotion($this->motionId);
        } else {
            $motion = null;
        }
        if ($motion) {
            return $motion;
        } else {
            return $this->motion;
        }
    }

    public function onSaved(): void
    {
        $this->getIMotion()->onSupportersChanged();
    }
}
