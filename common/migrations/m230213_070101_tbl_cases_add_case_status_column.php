<?php

use yii\db\Schema;

class m230213_070101_tbl_cases_add_case_status_column extends \yii\db\Migration
{
    public function safeUp()
    {   
        $this->addColumn('tbl_cases', 'case_status', $this->integer());
        $this->addForeignKey('FK_tbl_cases_tbl_case_status', 'tbl_cases', ['case_status'], 'tbl_case_status', ['id'], 'set null', 'set null');
    }

    public function safeDown()
    {
        // $this->dropTable('tbl_cases');
        echo 'Cannot be reverted'; 
        return true;
    }
}
