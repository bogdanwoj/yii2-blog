<?php
  
  use yii\helpers\Html;
  use yii\helpers\Url;
  use yii\grid\ActionColumn;
  use yii\grid\GridView;
  
  /** @var yii\web\View $this */
  /** @var app\models\CategorySearch $searchModel */
  /** @var yii\data\ActiveDataProvider $dataProvider */
  
  $this->title = 'Categories';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
  
  <h1><?= Html::encode($this->title) ?></h1>
  
  <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'ADMIN'): ?>
    <p>
      <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php endif; ?>
  
<!--  <div >--><?php // echo $this->render('_search', ['model' => $searchModel]); ?><!--</div>-->
  
  <?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
    'itemView' => '_category_item'
  ]); ?>


</div>
