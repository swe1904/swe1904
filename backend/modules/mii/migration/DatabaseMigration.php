<?php
/**
 * Created by PhpStorm.
 * User: ome
 * Date: 06-06-2018
 * Time: 11:21
 */

namespace backend\modules\mii\migration;


use yii\db\Migration;

class DatabaseMigration extends Migration
{
    public $tableName;
    public $sqlNewFieldsArray=[];
    public $sqlOldFieldsArray=[];
    public $sqlAlterFieldsArray=[];
    public $sqlExtraDeleteFieldsArray=[];

    public function createNewTable(){
        $table=$this->db->getTableSchema($this->tableName);
        if(empty($table)){
            $this->createTable($this->tableName, [
                'id' => 'pk',
            ]);
            foreach ($this->sqlNewFieldsArray as $sqlFields){
                $this->addNewColumn($sqlFields);
            }

        }else{
            $this->manipulateColumns();
        }
    }
    public function manipulateColumns(){

        // modify old columns
        if(!empty($this->sqlAlterFieldsArray)){
            foreach ($this->sqlAlterFieldsArray as $sqlFieldsAlter){
                $this->alterOldColumn($sqlFieldsAlter);
            }
        }

        // add new columns
        if(!empty($this->sqlNewFieldsArray)){
            foreach ($this->sqlNewFieldsArray as $sqlFieldsNew){
                $this->addNewColumn($sqlFieldsNew);
            }
        }

        // delete old columns
        if(!empty($this->sqlOldFieldsArray)){
            foreach ($this->sqlOldFieldsArray as $sqlFieldsDelete){
                $this->dropOldColumn($sqlFieldsDelete);
            }
        }
        // delete extra old columns
        if(!empty($this->sqlExtraDeleteFieldsArray)){
            foreach ($this->sqlExtraDeleteFieldsArray as $sqlExtraFieldsDelete){
                $this->dropExtraOldColumn($sqlExtraFieldsDelete);
            }
        }
    }
    public function up()
    {
        $table=$this->db->getTableSchema($this->tableName);
        if(empty($table)){
            $this->createTable($this->tableName, [
                'id' => 'pk',
            ]);
            foreach ($this->sqlNewFieldsArray as $sqlFields){
                $this->addNewColumn($sqlFields);
            }

        }
    }
    public function addNewColumn($sqlFields){
        try{
            $this->addColumn($this->tableName,$sqlFields['column'],$sqlFields['value']);
        }catch(\Exception $exception){
            echo $exception->getMessage();
        }

    }
    public function dropOldColumn($sqlFields){
        try{
            $this->dropColumn($this->tableName,$sqlFields['column']);
        }catch(\Exception $exception){
        }

    }
    public function dropExtraOldColumn($sqlFields){
        try{
            $this->dropColumn($this->tableName,$sqlFields);
        }catch(\Exception $exception){
        }

    }
    public function alterOldColumn($sqlFields){
        try{
            $this->alterColumn($this->tableName,$sqlFields['column'],$sqlFields['value']);
        }catch(\Exception $exception){
        }

    }

    public function down()
    {
        $this->dropTable('news');
    }
    public function getDatabaseFields(){
        $table=$this->db->getTableSchema($this->tableName);
        return $table->columnNames;
    }
}