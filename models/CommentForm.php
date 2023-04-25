<?php
  
  namespace app\models;
  
  use Yii;
  use yii\base\Model;
  
  class CommentForm extends Model
  {
    public $text;
    public $article_id;
    
    public function rules()
    {
      return [
        ['text', 'required'],
        ['text', 'string', 'max' => 255],
        ['article_id', 'required'],
        ['article_id', 'integer'],
      ];
    }
    
    public function attributeLabels()
    {
      return [
        'text' => 'Comment',
      ];
    }
  }