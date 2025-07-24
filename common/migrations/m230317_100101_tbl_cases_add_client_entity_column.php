<?php

use yii\db\Schema;

class m230317_100101_tbl_cases_add_client_entity_column extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_cases', 'client_entity', $this->integer());
        $this->addForeignKey('FK_tbl_cases_tbl_client_entity', 'tbl_cases', 'client_entity', 'tbl_client_entity', 'id', 'set null', 'set null');          
    }

    public function safeDown()
    {
        echo 'Cannot be reverted'; 
        return false;
    }
}
