<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_temp_file".
 *
 * @property integer $id
 * @property string $session_id
 * @property string $attachment
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $extension
 * @property string $file_name //random generated filename
 */
class TempFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_temp_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attachment', 'file_name'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['session_id'], 'string', 'max' => 50],
            [['name','extension'], 'string' , 'max' => 255 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Session ID',
            'attachment' => 'Attachment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Name',
            'extension' => 'Extension',
            'file_name' => 'File Name',
        ];
    }
}
