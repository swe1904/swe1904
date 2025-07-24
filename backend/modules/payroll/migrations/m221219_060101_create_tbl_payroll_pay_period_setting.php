<?php

use yii\db\Schema;

class m221219_060101_create_tbl_payroll_pay_period_setting extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tbl_payroll_pay_period_setting', [
            'id' => $this->primaryKey(),
            'start_date' => $this->string(45)->notNull(),
            'end_date' => $this->string(55)->notNull(),
            ], $tableOptions);
                
    }

    public function down()
    {
        echo "Cannot be deleted"; 
        return true;
        // $this->dropTable('tbl_payroll_pay_period_setting');
    }
}
