<?php
  
use app\models\Article;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
 use yii\grid\GridView;
  
  /** @var yii\web\View $this */
  /** @var app\models\ArticleSearch $searchModel */
  /** @var yii\data\ActiveDataProvider $dataProvider */
  
  $this->title = 'Admin Control Pannel';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-default-index">
  <h1><?= Html::encode($this->title) ?></h1>
  <p>Here you can manage categories, articles, and comments:</p>
  
  <ul>
    <li><?= Html::a('Categories', ['/category/index']) ?></li>
    <li><?= Html::a('Articles', ['/article/index']) ?></li>
    <li><?= Html::a('Comments', ['/comment/index']) ?></li>
  </ul>
</div>
