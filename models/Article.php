<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property int $viewed
 * @property int $user_id
 * @property int $status
 * @property int $category_id
 *
 * @property ArticleTag[] $articleTags
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['title'], 'required'],
          [['title','description','content'], 'string'],
          [['date'], 'date', 'format'=>'php:Y-m-d'],
          [['date'], 'default', 'value' => date('Y-m-d')],
          [['title'], 'string', 'max' => 255],
          [['category_id'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

//Image
    public function fileExists($currentImage)
    {
      if(!empty($currentImage) && $currentImage != null)
      {
        return file_exists($this->getFolder() . $currentImage);
      }
    }

    public function saveImage($filename)
    {
      $this->image = $filename;
      return $this->save(false);
    }

    public function getImage()
    {
      return ($this->image) ? '/uploads/' . $this->image : '/no-image';
    }

    public function deleteImage()
    {
        $imageUploadModel = new ImageUpload();
        $imageUploadModel->deleteCurrentImage($this->image);
    }

//автоматическое удален картинки из папки вместе со статьей
    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete();
    }

//category
//1 статья - 1 категория
public function getCategory()
 {
     return $this->hasOne(Category::className(), ['id' => 'category_id']);
 }

 public function saveCategory($category_id)
 {
   //Actiive Record link method
   $category = Category::findOne($category_id);
         if($category != null)
         {
           //связь и модель с которой хотим связаться
             $this->link('category', $category);
             return true;
         }
 }

 //get_meta_tags
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }

    public function getSelectedTags()
    {
//выбрать из БД все теги связ со статьей и из их масива выбрать айдишники
    $selectedIds = $this->getTags()->select('id')->asArray()->all();
    return ArrayHelper::getColumn($selectedIds, 'id');
    }

    public function saveTags($tags)
    {
      if (is_array($tags))
      {
        //удалить текущие связи перед записью новых
          $this->clearCurrentTags();
          foreach($tags as $tag_id)
          {
              $tag = Tag::findOne($tag_id);
              $this->link('tags', $tag);
          }
      }
    }

//удаление текущих связей перед записью новых
    public function clearCurrentTags()
    {
        ArticleTag::deleteAll(['article_id'=>$this->id]);
    }

//формат даты
    public function getDate()
    {
      return Yii::$app->formatter->asDate($this->date);
    }

//пагинация
public static function getAll($pageSize = 5)
{
    // build a DB query to get all articles
    $query = Article::find();
    // get the total number of articles (but do not fetch the article data yet)
    $count = $query->count();
    // create a pagination object with the total count
    $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>$pageSize]);
    // limit the query using the pagination and retrieve the articles
    $articles = $query->offset($pagination->offset)
                      ->limit($pagination->limit)
                      ->all();

    $data['articles'] = $articles;
    $data['pagination'] = $pagination;

    return $data;
}

//статьи по популярности
    public static function getPopular()
    {
      return Article::find()->orderBy('viewed desc')->limit(3)->all();
    }

    public static function getRecent()
    {
      return Article::find()->orderBy('date asc')->limit(3)->all();
    }

    public function saveArticle()
    {
      $this->user_id = Yii::$app->user->id;
      return $this->save();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id'=>'id']);
    }

    public function getArticleComments()
    {
        return $this->getComments()->where(['status'=>1])->all();
    }

    public function getAuthor()
    {
      return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

//колличество просмотров
    public function viewedCounter()
    {
      $this->viewed += 1;
      return $this->save(false);
    }

}
