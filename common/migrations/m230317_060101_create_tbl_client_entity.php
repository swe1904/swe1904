<?php

use yii\db\Schema;

class m230317_060101_create_tbl_client_entity extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tbl_client_entity', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'address' => $this->string(255),
            'cr_number' => $this->string(255)->notNull(),
            'unified_national_number' => $this->string(255)->notNull(),
            ], $tableOptions);
                
    }

    public function safeDown()
    {
        // $this->dropTable('tbl_client_entity');
        echo 'Cannot be reverted'; 
        return false;
    }
}
