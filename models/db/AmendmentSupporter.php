<?php

namespace app\models\db;

/**
 * @package app\models\db
 *
 * @property int $id
 * @property int $amendmentId
 * @property int $position
 * @property int $userId
 * @property string $role
 * @property string $comment
 * @property string $personType
 * @property string $name
 * @property string $organization
 * @property string $resolutionDate
 * @property string $contactEmail
 * @property string $contextPhone
 *
 * @property User $user
 * @property Amendment $amendment
 */
class AmendmentSupporter extends ISupporter
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'amendmentSupporter';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmendment()
    {
        return $this->hasOne(Amendment::className(), ['id' => 'amendmentId']);
    }
}
