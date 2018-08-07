<?php

namespace models;

use miolae\billing\models\Invoice;
use miolae\billing\tests\fixtures\AccountFixture;

class InvoiceCest
{
    public function _before(\UnitTester $I)
    {
        $I->haveFixtures([AccountFixture::class]);
    }

    /**
     * @covers Invoice::validate()
     * @dataProvider validateProvider
     * @param $result
     * @param $attributes
     */
    public function testValidate(\UnitTester $I, \Codeception\Example $example)
    {
        $invoice = new Invoice($example['attributes']);

        $I->assertEquals($example['result'], $invoice->validate(), implode(PHP_EOL, $invoice->getErrorSummary(true)));
    }

    protected function validateProvider(): array
    {
        return [
            'both accounts are empty' => [
                'result'     => false,
                'attributes' => [
                    'amount' => 1,
                ],
            ],
            'account from is empty'   => [
                'result'     => false,
                'attributes' => [
                    'account_id_to' => 1,
                    'amount'        => 1,
                ],
            ],
            'account to is empty'     => [
                'result'     => false,
                'attributes' => [
                    'account_id_from' => 1,
                    'amount'          => 1,
                ],
            ],
            'amount is empty'         => [
                'result'     => false,
                'attributes' => [
                    'account_id_from' => 1,
                    'account_id_to'   => 2,
                ],
            ],
            'amount is less then 1'   => [
                'result'     => false,
                'attributes' => [
                    'account_id_from' => 1,
                    'account_id_to'   => 2,
                    'amount'          => 0.1,
                ],
            ],
            'all is ok'               => [
                'result'     => true,
                'attributes' => [
                    'account_id_from' => 1,
                    'account_id_to'   => 2,
                    'amount'          => 1000000,
                ],
            ],
        ];
    }
}
