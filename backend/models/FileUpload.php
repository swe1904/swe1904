<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_file_upload".
 *
 * @property integer $id
 * @property string $file_id
 * @property string $attachment
 * @property string $name
 * @property string $extension
 * @property string $file_name
 * @property integer $is_upload_to_s3
 * @property string $s3_file_key
 */
class FileUpload extends \yii\db\ActiveRecord
{
    /*public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => 'date_modified',
                'value' => new Expression('NOW()'),
            ],
        ];
    }*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_file_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'attachment','name','extension', 'file_name', 's3_file_key'], 'string'],
            [['name','extension'], 'string', 'max' => 255],
            [['is_upload_to_s3'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'attachment' => 'Attachment',
            'name' => 'Name',
            'extension' => 'Extension',
            'file_name' => 'File Name',
            'is_upload_to_s3' => 'Is Upload To S3',
            's3_file_key' => 'S3 File Key'
        ];
    }
}
