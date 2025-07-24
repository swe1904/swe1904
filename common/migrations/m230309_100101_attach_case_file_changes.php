<?php

use yii\db\Schema;

class m230309_100101_attach_case_file_changes extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_temp_file', 'uploaded_by', $this->integer());
        $this->addForeignKey('FK_tbl_temp_file_tbl_user', 'tbl_temp_file', 'uploaded_by', 'tbl_user', 'id', 'set null', 'set null');

        $this->addColumn('tbl_file_upload', 'uploaded_by', $this->integer());
        $this->addForeignKey('FK_tbl_file_upload_tbl_user', 'tbl_file_upload', 'uploaded_by', 'tbl_user', 'id', 'set null', 'set null');

        $this->addColumn('tbl_cases', 'additional_attachments', $this->string(255));
    }

    public function safeDown()
    {
        echo 'Cannot be reverted';
        return false;
    }
}
