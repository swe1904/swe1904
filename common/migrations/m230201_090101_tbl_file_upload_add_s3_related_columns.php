<?php

use yii\db\Schema;

class m230201_090101_tbl_file_upload_add_s3_related_columns extends \yii\db\Migration
{
    public function safeUp()
    {           
        $this->addColumn('tbl_file_upload', 'file_name', $this->string(255));
        $this->addColumn('tbl_file_upload', 'is_upload_to_s3', $this->tinyint()->notNull()->defaultValue(0));
        $this->addColumn('tbl_file_upload', 's3_file_key', $this->string(255));
    }

    public function safeDown()
    {
        echo "S3 related columns cannot be reverted"; 
        return true;
    }
}
