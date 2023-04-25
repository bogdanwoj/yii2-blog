<?php
  
  use yii\helpers\Html;
  use yii\widgets\DetailView;
  
  /** @var yii\web\View $this */
  /** @var app\models\Category $category */
  /** @var $model \app\models\Article */
  
  $this->title = $category->name;
  $this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
  $this->params['breadcrumbs'][] = $this->title;
  \yii\web\YiiAsset::register($this);
?>
<div class="category-view">
  
  <h1><?= Html::encode($this->title) ?></h1>
  <p class="text-muted">
    <small>
      Created at: <?php echo Yii::$app->formatter->asRelativeTime($category->created_at) ?>
    </small>
  </p>
  <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'ADMIN'): ?>
    <p>
      <?= Html::a('Update', ['update', 'id' => $category->id], ['class' => 'btn btn-primary']) ?>
      <?= Html::a('Delete', ['delete', 'id' => $category->id], [
        'class' => 'btn btn-danger',
        'data' => [
          'confirm' => 'Are you sure you want to delete this item?',
          'method' => 'post',
        ],
      ]) ?>
    </p>
  <?php endif; ?>
  <div class="border rounded">
    <h2>Articles in <?= Html::encode($category->name) ?></h2>
    
    <ul>
      <?php foreach ($dataProvider->getModels() as $article): ?>
        <li>
          <div class="col-lg-12">
            <h2><?= Html::encode($article->title) ?></h2>
            <p><?= \yii\helpers\StringHelper::truncateWords(Html::encode($article->body), 40) ?></p>
            <p><?= Html::a('Read more &raquo;', ['article/view', 'slug' => $article->slug], ['class' => 'btn btn-outline-secondary']) ?></p>
            <hr>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>