<?php

use yii\db\Schema;

class m230209_110101_tbl_receipt_alter_payment_mode_column extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->alterColumn('tbl_receipt', 'payment_mode', $this->integer()->null());
    }

    public function safeDown()
    {
        // $this->dropTable('tbl_receipt');
        echo "Cannot be reverted"; 
        return true;
    }
}
