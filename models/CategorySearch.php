<?php
  
  namespace app\models;
  
  use app\models\Category;
  use yii\base\Model;
  use yii\data\ActiveDataProvider;
  
  /**
   * CategorySearch represents the model behind the search form of `app\models\Category`.
   */
  class CategorySearch extends Category
  {
    /**
     * {@inheritdoc}
     */
    
    public $search;
    
    public function rules()
    {
      return [
        [['id', 'created_at', 'updated_at'], 'integer'],
        [['name', 'search'], 'safe'],
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
      $query = Category::find()->orderBy('created_at DESC');
      
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
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ]);
      
      $query->orFilterWhere(['like', 'name', $this->search]);

      
      return $dataProvider;
    }
  }
