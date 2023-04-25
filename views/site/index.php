<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Article $model */


$this->title = 'My Yii Application';
?>
<div class="site-index">
  <div>
  <img src="uploads/welcome-to-my-blog-title.jpg" class="img-fluid rounded" alt="Welcome" style="width: 1220px;">
  </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
              
              <div class="row mt-4">
                <div class="col-9">
                  <h2>Read last articles on the blog</h2>
                <?php foreach ($articles as $article): ?>
                  <div class="col-lg-12 border p-1">
                    <h2><?= Html::encode($article->title) ?></h2>
                    <p><?= \yii\helpers\StringHelper::truncateWords(Html::encode($article->body), 40) ?></p>
                    <p><?= Html::a('Read more &raquo;', ['article/view', 'slug' => $article->slug], ['class' => 'btn btn-outline-secondary']) ?></p>
                    
                  </div>
                <?php endforeach; ?>
                </div>
                <div class="col-3">
                  <h2>Top commented articles</h2>
                  <?php foreach ($articlesWithComments as $article): ?>
                    <div class="border p-1">
                    <h2><?= Html::encode($article->title) ?></h2>
                    <p><?= Html::a('Read more &raquo;', ['article/view', 'slug' => $article->slug], ['class' => 'btn btn-outline-secondary']) ?></p>
                    <p>Comments: <?= count($article->comments) ?></p>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

            </div>
        </div>
    </div>
</div>
