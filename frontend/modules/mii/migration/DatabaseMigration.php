<?php
/**
 * Created by PhpStorm.
 * User: ome
 * Date: 06-06-2018
 * Time: 11:21
 */

namespace frontend\modules\mii\migration;


use yii\db\Migration;

class DatabaseMigration extends Migration
{
    public $sqlNewFieldsArray=[];
    public $sqlOldFieldsArray=[];
    public $sqlAlterFieldsArray=[];

    public function createNewTable(){
        $table=$this->db->getTableSchema('client');
        if(empty($table)){
            $this->createTable('client', [
                'id' => 'pk',
            ]);
            foreach ($this->sqlNewFieldsArray as $sqlFields){
                $this->addNewColumn($sqlFields);
            }

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
    }
    public function up()
    {
        $table=$this->db->getTableSchema('client');
        if(empty($table)){
            $this->createTable('client', [
                'id' => 'pk',
            ]);
            foreach ($this->sqlNewFieldsArray as $sqlFields){
                $this->addNewColumn($sqlFields);
            }

        }
    }
    public function addNewColumn($sqlFields){
        try{
            $this->addColumn('client',$sqlFields['column'],$sqlFields['value']);
        }catch(\Exception $exception){
        }

    }
    public function dropOldColumn($sqlFields){
        try{
            $this->dropColumn('client',$sqlFields['column']);
        }catch(\Exception $exception){
        }

    }
    public function alterOldColumn($sqlFields){
        try{
            $this->alterColumn('client',$sqlFields['column'],$sqlFields['value']);
        }catch(\Exception $exception){
        }

    }

    public function down()
    {
        $this->dropTable('news');
    }
}