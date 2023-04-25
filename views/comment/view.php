<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
              'attribute' => 'article',
              'value' => function ($model) {
                return $model->article->title;
              },
            ],
            [
              'attribute' => 'created_by',
              'value' => function ($model) {
                return $model->createdBy->username;
              },
            ],
            'text',
            [
              'attribute' => 'created_at',
              'format' => ['datetime', 'php:d-m-Y H:i:s']
            ],
            [
              'attribute' => 'updated_at',
              'format' => ['datetime', 'php:d-m-Y H:i:s']
            ],
        ],
    ]) ?>

</div>
