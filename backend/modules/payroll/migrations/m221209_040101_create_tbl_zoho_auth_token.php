<?php

use yii\db\Schema;

class m221209_040101_create_tbl_zoho_auth_token extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tbl_zoho_auth_token', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'access_token' => $this->string(100)->notNull(),
            'refresh_token' => $this->string(100)->notNull(),
            'expires_on' => $this->date()->notNull(),
            'scope' => $this->string(255)->notNull(),
            ], $tableOptions);

        $this->addForeignKey('fk_tbl_user_tbl_zoho_auth_token', '{{%zoho_auth_token}}', 'user_id', '{{%user}}', 'id', 'cascade', 'no action');
                
    }

    public function safeDown()
    {
        echo "Cannot be deleted"; 
        return true;
        //$this->dropTable('tbl_zoho_auth_token');
    }
}
