<?php

use yii\db\Schema;

class m230316_100101_download_case_file_changes extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_organisation', 'country_code', $this->string());
    }

    public function safeDown()
    {
        echo 'Cannot be reverted';
        return false;
    }
}
