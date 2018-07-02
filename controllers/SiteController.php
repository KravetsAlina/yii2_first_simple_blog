<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\data\Pagination;
use app\models\Article;
use app\models\Category;
use app\models\ContactForm;
use app\models\CommentForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }


    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionView($id)
    {
      $article = Article::findOne($id);
      $popular = Article::getPopular();
      $recent = Article::getRecent();
      $categories = Category::getAll();
      $comments = $article->getArticleComments();;
      $commentForm = new CommentForm();
      $article->viewedCounter();

      return $this->render('single', [
        'article'     => $article,
        'popular'     => $popular,
        'recent'      => $recent,
        'categories'  => $categories,
        'commentForm' => $commentForm,
        'comments'     => $comments,
      ]);
    }

    public function actionCategory($id)
    {
      $query = Article::find()->where(['category_id'=>$id]);
      $data = Category::getArticlesByCategory($id);
      $popular = Article::getPopular();
      $recent = Article::getRecent();
      $categories = Category::getAll();

      $pagination = new Pagination([
          'defaultPageSize' => 6,
          'totalCount' => $query->count(),
      ]);

      $articles = $query
          ->offset($pagination->offset)
          ->limit($pagination->limit)
          ->all();

      $data['articles'] = $articles;
      $data['pagination'] = $pagination;

      return $this->render('category',[
        'articles'  => $data['articles'],
        'pagination' => $data['pagination'],
        'popular'    => $popular,
        'recent'     => $recent,
        'categories' =>$categories,
      ]);
    }


// пагинация для постов
    public function actionIndex()
    {
      $data = Article::getAll(3);
      $popular = Article::getPopular();
      $recent = Article::getRecent();
      $categories = Category::getAll();

        return $this->render('index', [
            'articles'   => $data['articles'],
            'pagination' => $data['pagination'],
            'popular'    => $popular,
            'recent'     => $recent,
            'categories' =>$categories,

        ]);
    }


    public function actionComment($id)
    {
        $model = new CommentForm();

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment', 'Your comment will be added soon!');
                return $this->redirect(['site/view','id'=>$id]);
            }
        }
    }

}
