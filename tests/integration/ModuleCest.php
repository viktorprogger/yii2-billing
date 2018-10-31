<?php

namespace miolae\billing\tests\integration;

use miolae\billing\models\Account;
use miolae\billing\Module;
use miolae\billing\tests\fixtures\AccountFixture;

class ModuleCest
{
    public function _before(\UnitTester $I)
    {
        $I->haveFixtures([AccountFixture::class]);
    }

    public function createInvoice(\IntegrationTester $I)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var Module $module */
        $module = \Yii::createObject(Module::class, ['billing']);

        $accountFrom = 1;
        $accountTo = 2;
        $amount = 3;
        $invoice = $module->createInvoice($accountFrom, $accountTo, $amount);

        $I->assertEquals($accountFrom, $invoice->account_id_from);
        $I->assertEquals($accountTo, $invoice->account_id_to);
        $I->assertEquals($amount, $invoice->amount);
        $errors = implode(PHP_EOL, $invoice->getErrorSummary(true));
        $I->assertNotEmpty($invoice->id, 'Invoice is not saved. Reason: ' . $errors);
    }

    public function createInvoiceWithAccountsAR(\IntegrationTester $I)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var Module $module */
        $module = \Yii::createObject(Module::class, ['billing']);

        $accountFrom = Account::findOne(1);
        $accountTo = Account::findOne(2);
        $amount = 3;
        $invoice = $module->createInvoice($accountFrom, $accountTo, $amount);

        /** @noinspection NullPointerExceptionInspection */
        $I->assertEquals($accountFrom->id, $invoice->account_id_from);
        /** @noinspection NullPointerExceptionInspection */
        $I->assertEquals($accountTo->id, $invoice->account_id_to);
        $I->assertEquals($amount, $invoice->amount);
    }

    /**
     * @dataProvider quickPayProvider
     */
    public function quickPay(\IntegrationTester $I, \Codeception\Example $example)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $params = [
            'blackHoleStrategy' => $example['strategy'],
            'dbConnection'      => 'db',
            'class'             => Module::class,
        ];
        \Yii::$app->setModule('billing', $params);

        /** @var Module $module */
        $module = \Yii::$app->getModule('billing');
        /** @noinspection PhpUnhandledExceptionInspection */
        $invoice = $module->quickPay($example['from'], $example['to'], $example['amount']);
        $errors = (string)implode(', ', $invoice->getErrorSummary(true));

        /** @var Account $accountFrom */
        $accountFrom = Account::findOne($example['from']);
        /** @var Account $accountTo */
        $accountTo = Account::findOne($example['to']);


        $I->assertEquals($example['isError'], $invoice->hasErrors(), $errors);
        $I->assertEquals($example['amountFrom'], $accountFrom->amount, $errors);
        $I->assertEquals($example['amountTo'], $accountTo->amount, $errors);
    }

    protected function quickPayProvider(): array
    {
        $accountRegular = 1;
        $accountRegular2 = 2;
        $accountBlackHole = 3;
        $accountBlackHole2 = 4;
        $accountRegularHasFunds = 5;

        $amount = 100;
        $accountFunds = 1000;

        return [
            'zero, regular, regular2' => [
                'strategy'   => Module::BLACK_HOLE_ZERO,
                'amount'     => $amount,
                'from'       => $accountRegular,
                'to'         => $accountRegular2,
                'isError'    => true,
                'amountFrom' => 0,
                'amountTo'   => 0,
            ],
            'zero, regular, BH' => [
                'strategy'   => Module::BLACK_HOLE_ZERO,
                'amount'     => $amount,
                'from'       => $accountRegular,
                'to'         => $accountBlackHole,
                'isError'    => true,
                'amountFrom' => 0,
                'amountTo'   => 0,
            ],
            'zero, funds, regular' => [
                'strategy'   => Module::BLACK_HOLE_ZERO,
                'amount'     => $amount,
                'from'       => $accountRegularHasFunds,
                'to'         => $accountRegular,
                'isError'    => false,
                'amountFrom' => $accountFunds - $amount,
                'amountTo'   => $amount,
            ],
            'zero, funds, BH' => [
                'strategy'   => Module::BLACK_HOLE_ZERO,
                'amount'     => $amount,
                'from'       => $accountRegularHasFunds,
                'to'         => $accountBlackHole,
                'isError'    => false,
                'amountFrom' => $accountFunds - $amount,
                'amountTo'   => 0,
            ],
            'zero, BH, regular' => [
                'strategy'   => Module::BLACK_HOLE_ZERO,
                'amount'     => $amount,
                'from'       => $accountBlackHole,
                'to'         => $accountRegular,
                'isError'    => false,
                'amountFrom' => 0,
                'amountTo'   => $amount,
            ],
            'zero, BH, BH2' => [
                'strategy'   => Module::BLACK_HOLE_ZERO,
                'amount'     => $amount,
                'from'       => $accountBlackHole,
                'to'         => $accountBlackHole2,
                'isError'    => false,
                'amountFrom' => 0,
                'amountTo'   => 0,
            ],

            'endless, regular, regular2' => [
                'strategy'   => Module::BLACK_HOLE_ENDLESS,
                'amount'     => $amount,
                'from'       => $accountRegular,
                'to'         => $accountRegular2,
                'isError'    => true,
                'amountFrom' => 0,
                'amountTo'   => 0,
            ],
            'endless, regular, BH' => [
                'strategy'   => Module::BLACK_HOLE_ENDLESS,
                'amount'     => $amount,
                'from'       => $accountRegular,
                'to'         => $accountBlackHole,
                'isError'    => true,
                'amountFrom' => 0,
                'amountTo'   => 0,
            ],
            'endless, funds, regular' => [
                'strategy'   => Module::BLACK_HOLE_ENDLESS,
                'amount'     => $amount,
                'from'       => $accountRegularHasFunds,
                'to'         => $accountRegular,
                'isError'    => false,
                'amountFrom' => $accountFunds - $amount,
                'amountTo'   => $amount,
            ],
            'endless, funds, BH' => [
                'strategy'   => Module::BLACK_HOLE_ENDLESS,
                'amount'     => $amount,
                'from'       => $accountRegularHasFunds,
                'to'         => $accountBlackHole,
                'isError'    => false,
                'amountFrom' => $accountFunds - $amount,
                'amountTo'   => $amount,
            ],
            'endless, BH, regular' => [
                'strategy'   => Module::BLACK_HOLE_ENDLESS,
                'amount'     => $amount,
                'from'       => $accountBlackHole,
                'to'         => $accountRegular,
                'isError'    => false,
                'amountFrom' => -$amount,
                'amountTo'   => $amount,
            ],
            'endless, BH, BH2' => [
                'strategy'   => Module::BLACK_HOLE_ENDLESS,
                'amount'     => $amount,
                'from'       => $accountBlackHole,
                'to'         => $accountBlackHole2,
                'isError'    => false,
                'amountFrom' => -$amount,
                'amountTo'   => $amount,
            ],
        ];
    }
}
