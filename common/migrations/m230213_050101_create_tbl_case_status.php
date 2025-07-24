<?php

use yii\db\Schema;

class m230213_050101_create_tbl_case_status extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tbl_case_status', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->defaultValue(''),
            'is_default' => $this->integer()->defaultValue(0),
            ], $tableOptions);
        

        //adding default status
        $this->insert('tbl_case_status', [
            'name' => 'In Progress',
            'is_default' => 1,
        ]);
    }

    public function safeDown()
    {
        // $this->dropTable('tbl_case_status');
        echo 'Cannot be deleted'; 
        return true;
    }
}
