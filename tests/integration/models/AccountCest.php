<?php

namespace miolae\billing\tests\integration\models;

use miolae\billing\models\Account;

class AccountCest
{
    /**
     * @covers       Account::rules()
     * @dataProvider validateProvider
     * @param $result
     * @param $attributes
     */
    public function testValidate(\UnitTester $I, \Codeception\Example $example)
    {
        $account = new Account($example['attributes']);

        $I->assertEquals($example['result'], $account->validate(), implode(PHP_EOL, $account->getErrorSummary(true)));
    }

    protected function validateProvider(): array
    {
        return [
            'hold is less then zero'   => [
                'result'     => false,
                'attributes' => [
                    'title' => 'Test account',
                    'hold'  => -1,
                ],
            ],
            'amount is less then zero' => [
                'result'     => false,
                'attributes' => [
                    'title' => 'Test account',
                    'hold'  => -1,
                ],
            ],
            'amount is less then hold' => [
                'result'     => false,
                'attributes' => [
                    'title'  => 'Test account',
                    'amount' => 1000,
                    'hold'   => 2000,
                ],
            ],
            'empty title'              => [
                'result'     => false,
                'attributes' => [
                    'title' => '',
                ],
            ],
            'minimal OK'              => [
                'result'     => true,
                'attributes' => [
                    'title'   => 'Test account',
                ],
            ],
        ];
    }
}
