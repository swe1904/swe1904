<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_api_req_res_log".
 *
 * @property int $id
 * @property string|null $api_type
 * @property string|null $api_url
 * @property string|null $request_body
 * @property string|null $response_body
 * @property string|null $created_at
 */
class ApiReqResLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_api_req_res_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_body', 'response_body'], 'string'],
            [['created_at'], 'safe'],
            [['api_type'], 'string', 'max' => 45],
            [['api_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'api_type' => 'Api Type',
            'api_url' => 'Api URL',
            'request_body' => 'Request Body',
            'response_body' => 'Response Body',
            'created_at' => 'Created At',
        ];
    }
}
