<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * AuthController handles user authentication
 */
class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Display login form
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\models\LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Display registration form
     * @return string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        $model->scenario = 'register';

        if ($model->load(Yii::$app->request->post())) {
            $password = Yii::$app->request->post('User')['password'];
            $passwordConfirm = Yii::$app->request->post('password_confirmation');
            
            if ($password !== $passwordConfirm) {
                $model->addError('password', 'Пароли не совпадают');
            } else {
                $model->password = $password;
                
                if ($model->save()) {
                    // Auto login after registration
                    Yii::$app->user->login($model);
                    return $this->redirect(['link/index']);
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Logout user
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
