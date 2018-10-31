<?php

namespace miolae\billing\models;

use miolae\billing\traits\BlameableTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Invoice
 *
 * @package miolae\billing\models
 *
 * @property int           id
 * @property int           account_id_from
 * @property int           account_id_to
 * @property Account       accountFrom
 * @property Account       accountTo
 * @property Transaction[] transactions
 * @property float         amount
 * @property int           status Current invoice status.
 * @property string        reason The reason why this invoice is created. E.g. "refill account with PayPal".
 * @property int           created_at
 * @property int           updated_at
 * @property int           created_by
 * @property int           updated_by
 */
class Invoice extends ActiveRecord
{
    use BlameableTrait;

    /**
     * Newly created Invoice
     *
     * @see Invoice::status
     */
    const STATUS_CREATE = 1;
    /** Funds are held within the account */
    const STATUS_HOLD = 2;
    /** Funds are moved to the target account */
    const STATUS_SUCCESS = 3;
    /** Operation is cancelled */
    const STATUS_CANCEL = 4;

    public static function tableName()
    {
        return '{{%billing_invoices}}';
    }

    public function behaviors()
    {
        return array_merge(self::getBlameableBehavior(), [TimestampBehavior::class]);
    }

    public function rules()
    {
        return [
            [['status'], 'default', 'value' => static::STATUS_CREATE],
            [['account_id_from', 'account_id_to', 'amount', 'status'], 'required'],
            [['account_id_from', 'account_id_to', 'amount', 'reason'], 'safe'],
            [['account_id_from', 'account_id_to'], 'integer'],
            [['amount'], 'number', 'min' => 1, 'message' => 'Возможен перевод от 1 рубля и больше'],
            [
                ['account_id_from'],
                'exist',
                'targetRelation' => 'accountFrom',
                'message'        => 'accountFrom doesn\'t exist',
            ],
            [['account_id_to'], 'exist', 'targetRelation' => 'accountTo', 'message' => 'accountTo doesn\'t exist'],
            [['account_id_from', 'account_id_to'], function () {
                if ($this->account_id_from === $this->account_id_to) {
                    $this->addError('account_id_from', 'Source and target accounts can\'t be identical');
                }
            }],
        ];
    }

    public function getAccountFrom()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id_from']);
    }

    public function getAccountTo()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id_to']);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['invoice_id' => 'id']);
    }

    public function addLinkedErrors(string $attribute, ActiveRecord $transaction)
    {
        if (empty($transaction->getErrors())) {
            $this->addError($attribute, "Can't save $attribute");
        } else {
            foreach ($transaction->getErrors() as $attr => $errors) {
                foreach ($errors as $error) {
                    $this->addError($attribute, "$attr: $error");
                }
            }
        }
    }
}
