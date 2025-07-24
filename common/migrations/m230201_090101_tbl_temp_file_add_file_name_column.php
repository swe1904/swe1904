<?php

use yii\db\Schema;

class m230201_090101_tbl_temp_file_add_file_name_column extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_temp_file', 'file_name', $this->string(255));
    }

    public function safeDown()
    {
        echo "Column file_name cannot be reverted";
        return true;
    }
}
