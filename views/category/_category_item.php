<?php
  /** @var $model \app\models\Category */
  /** @var $model \app\models\Article */
?>


<div class="border mt-4 rounded p-3 col-3">
  <a href="<?php echo \yii\helpers\Url::to(['/category/view', 'id' => $model->id]) ?>" class="btn btn-light position-relative">
        <?php echo \yii\helpers\Html::encode($model->name) ?>
        <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-success">
          <?php echo count($model->articles) ?> articles
        </span>
      </a>
</div>