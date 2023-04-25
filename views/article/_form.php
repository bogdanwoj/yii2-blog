<?php
  
  use yii\helpers\Html;
  use yii\widgets\ActiveForm;
  
  /* @var $this yii\web\View */
  /* @var $model app\models\Article */
  /* @var $form yii\widgets\ActiveForm */
  /* @var $categories app\models\Category[] */
  
  $this->title = 'Create Article';
  $this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-form">
  
  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
  
  <?= $form->field($model, 'imageFile')->fileInput() ?>
  
  <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
  
  <?= $form->field($model, 'body')->textarea(['rows' => 10]) ?>
  
  <?= $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map($categories, 'id', 'name')) ?>
  

  
  <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
  </div>
  
  <?php ActiveForm::end(); ?>

</div>