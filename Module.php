<?php

namespace miolae\billing;

use miolae\billing\exceptions\TransactionException;
use miolae\billing\models\Account;
use miolae\billing\models\Invoice;
use miolae\billing\models\Transaction;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Module as BaseModule;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Class Module
 *
 * @package miolae\billing
 *
 * @property-read Component|null db
 */
class Module extends BaseModule
{
    /** @var array $modelMapDefault */
    protected $modelMapDefault = [
        'Invoice'     => Invoice::class,
        'Account'     => Account::class,
        'Transaction' => Transaction::class,
    ];

    /** @var ActiveRecord[] Model map */
    public $modelMap = [];

    /** @var string DB connection name */
    public $dbConnection = 'db';

    public function init()
    {
        parent::init();

        foreach ($this->modelMapDefault as $key => $model) {
            if (empty($this->modelMap[$key])) {
                $this->modelMap[$key] = $model;
            }
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getDb(): ?Component
    {
        /** @noinspection OneTimeUseVariablesInspection */
        /** @var Component $db */
        $db = \Yii::$app->get($this->dbConnection);

        return $db;
    }

    /**
     * @param int|ActiveRecord $accountFrom
     * @param int|ActiveRecord $accountTo
     * @param                  $amount
     *
     * @return Invoice|ActiveRecord
     */
    public function createInvoice($accountFrom, $accountTo, float $amount): ActiveRecord
    {
        if (is_subclass_of($accountFrom, ActiveRecord::class)) {
            $accountFrom = $accountFrom->id;
        }

        if (is_subclass_of($accountTo, ActiveRecord::class)) {
            $accountTo = $accountTo->id;
        }

        $accountFrom = (int)$accountFrom;
        $accountTo = (int)$accountTo;

        $attributes = [
            'amount'          => $amount,
            'account_id_from' => $accountFrom,
            'account_id_to'   => $accountTo,
        ];

        /** @var ActiveRecord $invoice */
        $invoice = new $this->modelMap['Invoice']($attributes);
        $invoice->save();

        return $invoice;
    }

    /**
     * Hold funds on accountFrom of the given invoice
     *
     * @param ActiveRecord $invoice
     *
     * @return bool
     */
    public function hold(ActiveRecord $invoice): bool
    {
        /** @var Invoice $invoice */
        /** @var Transaction $transactionClass */
        $transactionClass = $this->modelMap['Transaction'];

        if ($invoice->status !== $invoice::STATUS_CREATE) {
            $invoice->addError('status', 'Invoice must be in "CREATED" status when holding');

            return false;
        }

        $attributes = [
            'invoice_id' => $invoice->id,
            'type' => $transactionClass::TYPE_HOLD,
        ];

        /** @var Transaction $transaction */
        $transaction = new $transactionClass($attributes);
        if (!$transaction->save()) {
            throw new TransactionException($transaction->getErrorSummary(true));
        }

        $dbTransact = $this->db->beginTransaction();

        $invoice->accountFrom->hold += $invoice->amount;
        if (!$invoice->accountFrom->save()) {
            $invoice->addLinkedErrors('accountFrom', $invoice->accountFrom);
            $dbTransact->rollBack();
            $transaction->fail();
        }

        $transaction->status = $transactionClass::STATUS_SUCCESS;
        if (!$transaction->save()) {
            $invoice->addLinkedErrors('transaction', $transaction);
            $dbTransact->rollBack();
            $transaction->fail();
        }

        $invoice->status = $invoice::STATUS_HOLD;
        if (!$invoice->save()) {
            $dbTransact->rollBack();
            $transaction->fail();
        }

        return true;
    }

    /**
     * Finish transferring funds for a holded invoice
     *
     * @param Invoice $invoice invoice in status HOLD
     *
     * @return bool
     * @throws InvalidConfigException
     * @throws TransactionException
     * @throws \yii\db\Exception
     */
    public function finish(ActiveRecord $invoice): bool
    {
        if (!$invoice instanceof $this->modelMap['Invoice']) {
            throw new InvalidConfigException('Invoice must be a class of ' . $this->modelMap['Invoice']);
        }

        if ($invoice->status !== $invoice::STATUS_HOLD) {
            $invoice->addError('status', 'Invoice must be in "HOLD" status when finishing');

            return false;
        }

        /** @var Transaction $transactionClass */
        $transactionClass = $this->modelMap['Transaction'];
        $attributes = [
            'invoice_id' => $invoice->id,
            'type' => $transactionClass::TYPE_FINISH,
        ];
        /** @var Transaction $transaction */
        $transaction = new $transactionClass($attributes);
        if (!$transaction->save()) {
            throw new TransactionException($transaction->getErrorSummary(true));
        }

        $dbTransact = $this->db->beginTransaction();

        $invoice->accountFrom->amount -= $invoice->amount;
        $invoice->accountFrom->hold -= $invoice->amount;
        if (!$invoice->accountFrom->save()) {
            $invoice->addLinkedErrors('accountFrom', $invoice->accountFrom);
            $dbTransact->rollBack();
            $transaction->fail();
        }

        $invoice->accountTo->amount += $invoice->amount;
        if (!$invoice->accountTo->save()) {
            $invoice->addLinkedErrors('accountTo', $invoice->accountTo);
            $dbTransact->rollBack();
            $transaction->fail();
        }

        $invoice->accountTo->amount += $invoice->amount;
        if (!$invoice->accountTo->save()) {
            $invoice->addLinkedErrors('accountTo', $invoice->accountTo);
            $dbTransact->rollBack();
            $transaction->fail();
        }

        $invoice->status = $invoice::STATUS_SUCCESS;
        if (!$invoice->save()) {
            $dbTransact->rollBack();
            $transaction->fail();
        }

        $transaction->status = $transactionClass::STATUS_SUCCESS;
        $transaction->save();

        $dbTransact->commit();
        return true;
    }
}
