<?php

use miolae\billing\models\Account;

return [
    'first test'  => [
        'id'         => 1,
        'title'      => 'first test',
        'amount'     => 0,
        'hold'       => 0,
        'type'       => Account::TYPE_NORMAL,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
    'second test' => [
        'id'         => 2,
        'title'      => 'second test',
        'amount'     => 0,
        'hold'       => 0,
        'type'       => Account::TYPE_NORMAL,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
    'black hole'  => [
        'id'         => 3,
        'title'      => 'black hole',
        'amount'     => 0,
        'hold'       => 0,
        'type'       => Account::TYPE_BLACKHOLE,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
    'second black hole'  => [
        'id'         => 4,
        'title'      => 'second black hole',
        'amount'     => 0,
        'hold'       => 0,
        'type'       => Account::TYPE_BLACKHOLE,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
    'has funds'  => [
        'id'         => 5,
        'title'      => 'has funds',
        'amount'     => 1000,
        'hold'       => 0,
        'type'       => Account::TYPE_NORMAL,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
];
