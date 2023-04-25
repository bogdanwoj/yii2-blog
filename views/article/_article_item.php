<?php

use yii\helpers\Html;

/** @var $model \app\models\Article */

?>

<div class="card text-center mb-5">
  <div class="card-body">
    <h5 class="card-title">
      <a href="<?php echo \yii\helpers\Url::to(['/article/view', 'slug' => $model->slug]) ?>">
        <?php echo \yii\helpers\Html::encode($model->title) ?>
      </a>
    </h5>
    <?php if ($model->image): ?>
      <?= \yii\helpers\Html::img($model->getImageUrl(), ['class' => 'card-img-top rounded mx-auto d-block', 'style' => 'width: 200px; height: 200px;']) ?>
    <?php endif; ?>
    <p class="card-text"><?php echo \yii\helpers\StringHelper::truncateWords($model->getEncodedBody(), 40) ?><?= Html::a('Read more &raquo;', ['article/view', 'slug' => $model->slug]) ?></p>
    <p class="text-muted text-end">
      <small>Created at: <?php echo Yii::$app->formatter->asRelativeTime($model->created_at) ?></small>
      <small>Created by: <?php echo $model->createdBy->username ?> </small>
    </p>
</div>



