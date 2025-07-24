<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_tmp".
 *
 * @property integer $id
 * @property integer $article_request_id
 * @property string $body
 * @property integer $author_id
 */
class ArticleTmp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_tmp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_request_id', 'author_id'], 'integer'],
            [['body'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_request_id' => 'Article Request ID',
            'body' => 'Body',
            'author_id' => 'Author ID',
        ];
    }
}
