<?php

namespace miolae\billing\models;

use miolae\billing\traits\BlameableTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Invoice
 * @package miolae\billing\models
 *
 * @property int     id
 * @property int     from_id
 * @property int     to_id
 * @property Account accountFrom
 * @property Account accountTo
 * @property float   amount
 * @property int     status Current invoice status. May be one of
 * @property string  reason The reason why this invoice is created. E.g. "refill account with PayPal".
 * @property int     created_at
 * @property int     updated_at
 * @property int     created_by
 * @property int     updated_by
 */
class Invoice extends ActiveRecord
{
    use BlameableTrait;

    /**
     * Newly created Invoice
     *
     * @see Invoice::status
     */
    const TYPE_CREATE = 1;
    /** Funds are held within the account */
    const TYPE_HOLD = 2;
    /** Funds are moved to the target account */
    const TYPE_SUCCESS = 3;
    /** Operation is cancelled */
    const TYPE_CANCEL = 4;

    public static function tableName()
    {
        return '{{%billing_invoices}}';
    }

    public function behaviors()
    {
        return array_merge(self::getBlameableBehavior(), [TimestampBehavior::class]);
    }
}
