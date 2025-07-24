<?php

use yii\db\Schema;

class m230208_110101_billing_module_feb23_changes extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_receipt', 'case_id', $this->integer());
        $this->addColumn('tbl_receipt', 'due_date', $this->date());
        $this->addColumn('tbl_receipt', 'vat_rate', $this->integer()->notNull()->defaultValue(15));

        $this->addForeignKey('FK_tbl_receipt_tbl_cases', 'tbl_receipt', ['case_id'], 'tbl_cases', ['id'], 'set null', 'set null');

        $this->addColumn('tbl_receipt_item', 'quantity', $this->integer()->notNull()->defaultValue(1));

        $this->addColumn('tbl_organisation', 'trn', $this->string(50));

        $this->addColumn('tbl_case_type', 'price', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
//        $this->dropTable('tbl_receipt');
        echo 'Cannot be deleted';
        return true;
    }
}
