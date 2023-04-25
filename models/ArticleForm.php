<?php
  
  namespace app\models;
  
  use Yii;
  use yii\base\Model;
  use yii\web\UploadedFile;
  
  class ArticleForm extends Model
  {
    public $title;
    public $content;
    public $category_id;
    public $image;
    
    public function rules()
    {
      return [
        [['title', 'content', 'category_id'], 'required'],
        [['title', 'content'], 'string'],
        ['category_id', 'integer'],
        ['image', 'file', 'extensions' => 'jpg, jpeg, png, gif'],
      ];
    }
    
    public function upload()
    {
      if ($this->validate()) {
        $filename = Yii::$app->security->generateRandomString() . '.' . $this->image->extension;
        $path = Yii::getAlias('@app/web/uploads/') . $filename;
        $this->image->saveAs($path);
        return $filename;
      } else {
        return false;
      }
    }
  }



