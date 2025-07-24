<?php

use yii\db\Schema;

class m230104_100101_create_tbl_api_req_res_log extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tbl_api_req_res_log', [
            'id' => $this->primaryKey(),
            'api_type' => $this->string(45),
            'api_url' => $this->string(255),
            'request_body' => $this->text(),
            'response_body' => $this->text(),
            'created_at' => $this->datetime()->defaultValue(CURRENT_TIMESTAMP),
            ], $tableOptions);
                
    }

    public function down()
    {
        echo "Cannot be deleted"; 
        return true;
        // $this->dropTable('tbl_api_req_res_log');
    }
}
