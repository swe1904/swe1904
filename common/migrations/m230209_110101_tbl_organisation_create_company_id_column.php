<?php

use yii\db\Schema;

class m230209_110101_tbl_organisation_create_company_id_column extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_organisation', 'company_id', $this->string(255));
    }

    public function safeDown()
    {
        // $this->dropTable('tbl_organisation');
        echo "Cannot be reverted"; 
        return true;
    }
}
