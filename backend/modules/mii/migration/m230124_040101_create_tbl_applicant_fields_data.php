<?php

use yii\db\Schema;

class m230124_040101_create_tbl_applicant_fields_data extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tbl_applicant_fields_data', [
            'id' => $this->primaryKey(),
            'fields_json' => $this->json(),
            ], $tableOptions);
                
    }

    public function down()
    {
        // $this->dropTable('tbl_applicant_fields_data');
        echo 'Cannot be deleted';
        return true;
    }
}
