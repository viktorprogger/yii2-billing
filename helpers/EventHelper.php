<?php

namespace miolae\billing\helpers;

use miolae\billing\models\Account;
use miolae\billing\Module;
use yii\base\Event;

class EventHelper
{
    public static function accountBlackHoleZero(Event $event)
    {
        /** @var Account $account */
        $account = $event->sender;

        if ($account->type === Account::TYPE_BLACKHOLE) {
            $account->hold = 0;

            if ($event->data['blackHoleStrategy'] === Module::BLACK_HOLE_ZERO) {
                $account->amount = 0;
            }
        }
    }
}
