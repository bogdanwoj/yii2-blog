<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $text
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property \app\models\User $createdBy
 * @property \app\models\Article $articleId
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }
  
  public function behaviors()
  {
    return
      [
        TimestampBehavior::class,
        [
          'class' => BlameableBehavior::class,
          'updatedByAttribute' => false,
          'createdByAttribute' => false,
        
        ],
      ];
  }
  
  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['text', 'article_id'], 'required'],
      [['text'], 'string', 'max' => 255],
      [['article_id'], 'integer'],
      [['created_at', 'updated_at'], 'safe'],
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'created_by' => 'Created By',
      'text' => 'Text',
      'article_id' => 'Article ID',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }
  
  /**
   * @return \yii\db\ActiveQuery
   */
  public function getCreatedBy()
  {
    return $this->hasOne(User::class, ['id' => 'created_by']);
  }
  
  /**
   * @return \yii\db\ActiveQuery
   */
  public function getArticle()
  {
    return $this->hasOne(Article::class, ['id' => 'article_id']);
  }
  
  
  public function getEncodedArticle()
  {
    return Html::encode($this->article_id);
  }
  
  public function getEncodedBody()
  {
    return Html::encode($this->text);
  }
}
