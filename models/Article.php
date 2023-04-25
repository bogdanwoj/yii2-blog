<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property string $image
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property \app\models\User $createdBy
 * @property \app\models\Category $categoryId
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }
    
    
    public function behaviors()
    {
      return
        [
            TimestampBehavior::class,
            [
              'class' => BlameableBehavior::class,
              'updatedByAttribute' => false,
            ],
            [
              'class' => SluggableBehavior::class,
              'attribute' => 'title'
            ]
        ];
    }
  
  /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['body', 'image'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'category_id'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['image'], 'file', 'extensions' => 'png, jpg'],
            [['imageFile'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'body' => 'Body',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'category_id' => 'Category',
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
  public function getCategory()
  {
    return $this->hasOne(Category::class, ['id' => 'category_id']);
  }
  
  public function getEncodedCategory()
  {
    return Html::encode($this->category_id);
  }
  
  public function getEncodedBody()
  {
    return Html::encode($this->body);
  }
  
  public function getComments()
  {
    return $this->hasMany(Comment::class, ['article_id' => 'id']);
  }
  
  public function getCommentCount()
  {
    return Comment::find()->where(['article_id' => $this->id])->count();
  }
  
  public $imageFile;
  

  
  public function uploadImage()
  {
    if ($this->imageFile !== null) {
      $fileName = Yii::$app->security->generateRandomString(12) . '.' . $this->imageFile->extension;
      $filePath = Yii::getAlias('@webroot/uploads/' . $fileName);
      $this->imageFile->saveAs($filePath);
      $this->image = $fileName;
      $this->save(false);
    }
  }
  
  public function getImageUrl()
  {
    if ($this->image !== null) {
      return Yii::$app->request->baseUrl . '/uploads/' . $this->image;
    } else {
      return null;
    }
  }
}
