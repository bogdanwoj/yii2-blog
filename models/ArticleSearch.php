<?php
  
  namespace app\models;
  
  use app\models\Article;
  use yii\base\Model;
  use yii\data\ActiveDataProvider;
  
  /**
   * ArticleSearch represents the model behind the search form of `app\models\Article`.
   */
  class ArticleSearch extends Article
  {
    /**
     * {@inheritdoc}
     */
    
    public $search;
    
    public function rules()
    {
      return [
        [['id', 'created_by', 'created_at', 'updated_at'], 'integer'],
        [['title', 'slug', 'body', 'search'], 'safe'],
      ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
      // bypass scenarios() implementation in the parent class
      return Model::scenarios();
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
      $query = Article::find()->orderBy('created_at DESC');
      
      // add conditions that should always apply here
      
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      
      $this->load($params);
      
      if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        return $dataProvider;
      }
      
      // grid filtering conditions
      $query->andFilterWhere([
        'id' => $this->id,
        'created_by' => $this->createdBy,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ]);
      
      $query->orFilterWhere(['like', 'title', $this->search])
            ->orFilterWhere(['like', 'slug', $this->search])
            ->orFilterWhere(['like', 'body', $this->search]);
      
      return $dataProvider;
    }
    
  }
