<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use yii\web\Controller;

/**
 * Login action.
 *
 * @return Response|string
 */

class AuthController extends Controller
 {
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
      //регистрация
      $model = new SignupForm();

      if(Yii::$app->request->isPost)
      {
        $model->load(Yii::$app->request->post());
        if($model->signup())
        {
          return $this->redirect(['auth/login']);
        }
      }

      return $this->render('signup', ['model'=>$model]);
    }

}

/**
 * Displays contact page.
 *
 * @return Response|string
 */
