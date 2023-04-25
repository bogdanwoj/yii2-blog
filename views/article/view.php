<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var app\models\Category $model */
/** @var app\models\Comment $commentModel */
/** @var app\models\CommentForm $commentForm */
?>
<a href="<?= Url::to(['article/index']) ?>">Back to Articles</a>
<div class="article-view">
  <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'ADMIN'): ?>
    <p>
      <?= Html::a('Update', ['update', 'slug' => $model->slug], ['class' => 'btn btn-primary']) ?>
      <?= Html::a('Delete', ['delete', 'slug' => $model->slug], [
        'class' => 'btn btn-danger',
        'data' => [
          'confirm' => 'Are you sure you want to delete this item?',
          'method' => 'post',
        ],
      ]) ?>
    </p>
  <?php endif; ?>
  <div><h1><?= Html::encode($model->title) ?></h1></div>
  
  
  <p class="text-muted">
    <small>
      Created at: <?php echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
      By: <?php echo $model->createdBy->username ?>
      <p>Category: <a href="<?= \yii\helpers\Url::to(['category/view', 'id' => $model->category_id]) ?>" class="text-dark"> <b> <?php echo $model->category->name; ?></b> </a></p>
    </small>
  </p>
  <?php if ($model->image): ?>
    <?= \yii\helpers\Html::img($model->getImageUrl(), ['class' => 'card-img-top rounded mx-auto d-block', 'style' => 'width: 400px; height: 300px;']) ?>
  <?php endif; ?>
  <div class="border rounded p-3">
    <div>
      <?php echo $model->getEncodedBody(); ?>
    </div>
  </div>
  
  <hr>
  
  <h4>Comments</h4>
  
  <?php if(count($model->comments) > 0): ?>
    
    <?php foreach ($model->comments as $comment): ?>
      <div class="comment border mt-2">
        <p><?= Html::encode($comment->text) ?></p>
        <p class="comment-meta d-flex flex-row-reverse bd-highlight">
          <?= Yii::$app->formatter->asRelativeTime($comment->created_at) ?>
          by  <?php echo $model->createdBy->username ?>
        </p>
        <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->role == 'ADMIN' || Yii::$app->user->identity->id == $comment->created_by)): ?>
          <?= Html::a('Update', ['comment/update', 'id' => $comment->id], ['class' => 'btn btn-primary btn-xs btn-sm']) ?>
          <?= Html::a('Delete', ['comment/delete', 'id' => $comment->id], [
            'class' => 'btn btn-danger btn-xs btn-sm',
            'data' => [
              'confirm' => 'Are you sure you want to delete this comment?',
              'method' => 'post',
            ],
          ]) ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No comments yet.</p>
  <?php endif; ?>
  
  <?php if(!Yii::$app->user->isGuest): ?>
    <h4>Add Comment</h4>
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($commentForm, 'text')->textarea(['rows' => 6]) ?>
    <?= $form->field($commentForm, 'article_id')->hiddenInput(['value' => $model->id])->label(false) ?>
    
    <div class="form-group">
      <?= Html::submitButton('Add Comment', ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    <hr>
  <?php endif; ?>
</div>