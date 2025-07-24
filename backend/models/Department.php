<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id
 * @property string $name
 *
 * @property Employee[] $employees
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['department_manager'], 'integer'],
            [['parent_department_id'], 'safe'], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Department',
            'department_manager' => 'Department Manager',
            'parent_department_id' => 'Parent Department Name',
        ];
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::class, ['department_id' => 'id']);
    }

    public function getManager()
{
    return $this->hasOne(User::class, ['id' => 'department_manager']);
}
public function getParent()
{
    return $this->hasOne(Department::class, ['id' => 'parent_department_id']);
}
public function getDepartmentManager()
{
    return $this->hasOne(User::class, ['id' => 'department_manager']);
}


}
