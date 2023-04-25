<?php
  
  namespace app\controllers;
  

  use app\models\ArticleSearch;
  use app\models\Category;
  use app\models\CategorySearch;
  use yii\filters\AccessControl;
  use yii\web\Controller;
  use yii\web\ForbiddenHttpException;
  use yii\web\NotFoundHttpException;
  use yii\filters\VerbFilter;
  
  /**
   * CategoryController implements the CRUD actions for Category model.
   */
  class CategoryController extends Controller
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
              'roles' => ['@']
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
     * Lists all Category models.
     *
     * @return string
     */
    public function actionIndex()
    {
      $searchModel = new CategorySearch();
      $dataProvider = $searchModel->search($this->request->queryParams);
      
      return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]);
    }
    
    /**
     * Displays a single Category model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $category = $this->findModel($id);
      $searchModel = new ArticleSearch();
      $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
      $dataProvider->query->andWhere(['category_id' => $category->id]);
      return $this->render('view', [
        'category' => $category,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]);
    }
    
    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
      if (\Yii::$app->user->identity->role !=='ADMIN') {
        throw new ForbiddenHttpException('You have no permission to create a category');
      }
      
      $model = new Category();
  
      if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
          // Check if title already exists
          $existingArticle = Category::findOne(['name' => $model->name]);
          if ($existingArticle !== null) {
            $model->addError('name', 'This category name already exists.');
          } else {
            if ($model->save()) {
              return $this->redirect(['view', 'id' => $model->id]);
            }
          }
        }
      } else {
        $model->loadDefaultValues();
      }
  
      return $this->render('create', [
        'model' => $model,
      ]);
    }
    
    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
      if (\Yii::$app->user->identity->role !=='ADMIN') {
        throw new ForbiddenHttpException('You have no permission to create a category');
      }
      
      $model = $this->findModel($id);
  
      if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
          // Check if name already exists
          $existingArticle = Category::findOne(['name' => $model->name]);
      
          if ($existingArticle !== null && $existingArticle->name !== $id) {
            $model->addError('name', 'This title already exists.');
          } else {
            if ($model->save()) {
              return $this->redirect(['view', 'id' => $model->name]);
            }
          }
        }
      }
  
      return $this->render('update', [
        'model' => $model,
      ]);
    }
  
      /**
       * Deletes an existing Article model.
       * If deletion is successful, the browser will be redirected to the 'index' page.
       * @param int $slug ID
       * @return \yii\web\Response
       * @throws NotFoundHttpException if the model cannot be found
       */
      public function actionDelete($id)
    {
      $model = $this->findModel($id);
  
      if (\Yii::$app->user->identity->role !=='ADMIN') {
        throw new ForbiddenHttpException('You have no permission to create a category');
      }
  
      $model->delete();
  
      return $this->redirect(['index']);
    }
    
    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
      if (($model = Category::findOne(['id' => $id])) !== null) {
        return $model;
      }
      
      throw new NotFoundHttpException('The requested page does not exist.');
    }
  }
