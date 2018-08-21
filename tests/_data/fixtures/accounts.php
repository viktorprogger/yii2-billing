<?php

use miolae\billing\models\Account;

return [
    'first test'  => [
        'id'     => 1,
        'title'  => 'first test',
        'amount' => 0,
        'hold'   => 0,
        'type'   => Account::TYPE_NORMAL,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
    'second test' => [
        'id'     => 2,
        'title'  => 'second test',
        'amount' => 0,
        'hold'   => 0,
        'type'   => Account::TYPE_NORMAL,
        'created_at' => 1534844663,
        'updated_at' => 1534844663,
    ],
];
