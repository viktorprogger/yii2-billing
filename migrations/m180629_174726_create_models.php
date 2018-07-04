<?php

use miolae\billing\models\Account;
use yii\db\Migration;

/**
 * Class m180629_174726_create_models
 */
class m180629_174726_create_models extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable(Account::tableName(), [
            'id'       => $this->primaryKey(),
            'title'    => $this->string()->notNull(),
            'amount'   => $this->float(4)->notNull(),
            'hold'     => $this->float(4)->notNull(),
            'type'     => $this->tinyInteger()->notNull(),
            'owner_id' => $this->integer(),
        ]);

        $this->createTable(Account::tableName(), [
            'id'              => $this->primaryKey(),
            'account_id_from' => $this->integer()->notNull(),
            'account_id_to'   => $this->integer()->notNull(),
            'amount'          => $this->float(4)->notNull(),
            'status'          => $this->tinyInteger()->notNull(),
            'reason'          => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable(Account::tableName());
    }
}
