<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_request_claim".
 *
 * @property integer $id
 * @property integer $article_request_id
 * @property integer $claim_by
 */
class ArticleRequestClaim extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_request_claim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_request_id', 'claim_by'], 'integer']
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
            'claim_by' => 'Claim By',
        ];
    }
}
