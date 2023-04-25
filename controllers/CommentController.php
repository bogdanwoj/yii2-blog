<?php

namespace app\controllers;

use app\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Comment models.
     *
     * @return string
     */
    public function actionIndex()
    {
      if (yii::$app->user->identity->role != 'ADMIN') {
        yii::$app->session->setFlash('error', 'You do not have permission to this page.');
        return $this->redirect(['layouts/main']);
      }
      
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find(),
            
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      if (yii::$app->user->identity->role != 'ADMIN') {
        yii::$app->session->setFlash('error', 'You do not have permission to this page.');
        return $this->redirect(['layouts/main']);
      }
      
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Comment();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
  public function actionUpdate($id)
  {
    $model = Comment::findOne($id);
    if (yii::$app->user->identity->role != 'ADMIN') {
      yii::$app->session->setFlash('error', 'You do not have permission to update this comment.');
      return $this->redirect(['article/view', 'slug' => $model->article->slug]);
    }
    
    if ($model->load(yii::$app->request->post()) && $model->save()) {
      yii::$app->session->setFlash('success', 'Comment updated successfully.');
      return $this->redirect(['article/view', 'slug' => $model->article->slug]);
    } else {
      return $this->render('update', [
        'model' => $model,
      ]);
    }
  }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
  public function actionDelete($id)
  {
    $comment = $this->findComment($id);
    
    if (\Yii::$app->user->isGuest || \Yii::$app->user->identity->role !== 'ADMIN') {
      throw new ForbiddenHttpException('You are not allowed to perform this action.');
    }
    
    $articleSlug = $comment->article->slug;
    $comment->delete();
    
    \Yii::$app->session->setFlash('success', 'Comment deleted successfully.');
    
    return $this->redirect(['article/view', 'slug' => $articleSlug]);
  }
  
  protected function findComment($id)
  {
    $comment = Comment::findOne($id);
    
    if (!$comment) {
      throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    return $comment;
  }


    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
