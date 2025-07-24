<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article_paypal_order".
 *
 * @property integer $id
 * @property integer $article_request_id
 * @property integer $article_id
 * @property integer $article_request_author_id
 * @property integer $article_author_id
 * @property string $amount
 * @property string $paymentId
 * @property string $created_at
 * @property string $updated_at
 * @property integer $author_id
 *
 * @property Article $article
 * @property ArticleRequest $articleRequest
 * @property User $articleRequestAuthor
 * @property User $articleAuthor
 * @property User $author
 */
class ArticlePaypalOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_paypal_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_request_id', 'article_id', 'article_request_author_id', 'article_author_id', 'author_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['amount', 'paymentId'], 'string', 'max' => 255]
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
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
            'article_id' => 'Article ID',
            'article_request_author_id' => 'Article Request Author ID',
            'article_author_id' => 'Article Author ID',
            'amount' => 'Amount',
            'paymentId' => 'Payment ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleRequest()
    {
        return $this->hasOne(ArticleRequest::className(), ['id' => 'article_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleRequestAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'article_request_author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'article_author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
}
