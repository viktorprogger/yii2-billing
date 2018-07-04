<?php

namespace miolae\billing\models;

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
 */
class Invoice extends ActiveRecord
{
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
}
