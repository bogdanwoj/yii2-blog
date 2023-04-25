<?php

namespace app\controllers;

use app\models\Article;
use app\models\ArticleSearch;
use app\models\Category;
use app\models\Comment;
use app\models\CommentForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * @inheritDoc
     */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['create', 'update', 'delete'],
        'rules' => [
          [
            'actions' => ['create', 'update', 'delete'],
            'allow' => true,
            'roles' => ['@'],
            'matchCallback' => function () {
              return \Yii::$app->user->identity->role === 'ADMIN';
            }
          ]
        ]
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

    /**
     * Lists all Article models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param int $slug ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug)
  {
    // retrieve the article model
    $model = $this->findModel($slug);
    
    // create a new comment model
    $commentModel = new Comment();
    
    // check if the comment form has been submitted
    $commentForm = new CommentForm();
    if ($commentForm->load(yii::$app->request->post()) && $commentForm->validate()) {
      // populate the comment model attributes
      $commentModel->text = $commentForm->text;
      $commentModel->article_id = $model->id;
      $commentModel->created_by = yii::$app->user->identity->id;
      
      // save the comment to the database
      $commentModel->save();
      
      // redirect to the article view page to display the new comment
      return yii::$app->getResponse()->redirect(['/article/view', 'slug' => $model->slug, '#' => 'comment-form']);
    }
    
    // render the article view page with the comment form
    return $this->render('view', [
      'model' => $model,
      'commentForm' => $commentForm,
    ]);
  }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

  public function actionCreate()
  {
    $model = new Article();
    $categories = Category::find()->all();
    
    if ($model->load(\Yii::$app->request->post())) {
      // Check if title already exists
      $existingArticle = Article::findOne(['title' => $model->title]);
      if ($existingArticle !== null) {
        $model->addError('title', 'This title already exists.');
        \Yii::$app->session->setFlash('error', 'This title already exists.');
      } else {
        // Set the created_by attribute to the current user ID
        $model->created_by = \Yii::$app->user->id;
        
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        
        if ($model->save()) {
          if ($model->imageFile !== null) {
            $model->uploadImage();
          }
          return $this->redirect(['view', 'slug' => $model->slug]);
        }
      }
    }
    
    return $this->render('create', [
      'model' => $model,
      'categories' => $categories,
    ]);
  }
    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
  public function actionUpdate($slug)
  {
    $model = $this->findModel($slug);
    $categories = Category::find()->all();
    
    if ($model->load(Yii::$app->request->post())) {
      $existingArticle = Article::findOne(['title' => $model->title]);
      if ($existingArticle !== null && $existingArticle->id !== $model->id) {
        $model->addError('title', 'This title already exists.');
        Yii::$app->session->setFlash('error', 'This title already exists.');
      } else {

        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        if ($model->save()) {
          if ($model->imageFile !== null) {
            $model->uploadImage();
          }
          Yii::$app->session->setFlash('success', 'The article has been updated.');
          return $this->redirect(['view', 'slug' => $model->slug]);
        }
      }
    }
    
    return $this->render('update', [
      'model' => $model,
      'categories' => $categories,
    ]);
  }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {
        $model = $this->findModel($slug);
  
      if (\Yii::$app->user->identity->role !=='ADMIN') {
        throw new ForbiddenHttpException('You have no permission to delete an article');
      }
      
      $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $slug ID
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug)
    {
        if (($model = Article::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
  
  protected function findCategory($id)
  {
    if (($model = Category::findOneById(['id' => $id])) !== null) {
      return $model;
    }
    
    throw new NotFoundHttpException('The requested page does not exist.');
  }
  
  
}
