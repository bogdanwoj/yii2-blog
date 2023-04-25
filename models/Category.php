<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
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
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string','min' => 4, 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
  
  public function getEncodedBody()
  {
    return Html::encode($this->name);
  }
  
  public function getArticles()
  {
    return $this->hasMany(Article::className(), ['category_id' => 'id']);
  }
  
  public function beforeDelete()
  {
    if (parent::beforeDelete()) {
      if ($this->getArticles()) {
        $this->addError('id', 'This category has articles and cannot be deleted.');
        return false;
      }
      return true;
    } else {
      return false;
    }
  }
}
