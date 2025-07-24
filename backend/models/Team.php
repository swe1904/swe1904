<?php
namespace backend\models;

use common\models\User as ModelsUser;
use User;
use Yii;
use yii\db\ActiveRecord;

class Team extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_teams';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name','team_manager'], 'string', 'max' => 255],
            [['parent_dept_id'], 'safe'], 
            [['name'], 'unique', 'message' => 'This team name already exists.'], // Duplicate check message
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Team Name',
        ];
    }
    public function getTeamManager()
{
    return $this->hasOne(ModelsUser::class, ['id' => 'team_manager']);
}

public function getParentTeam()
{
    return $this->hasOne(Team::class, ['id' => 'parent_dept_id']);
}

}
