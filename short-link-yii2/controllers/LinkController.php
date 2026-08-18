<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Link;
use app\models\Click;
use app\components\CodeGenerator;

/**
 * ShortLinkController handles short link operations
 */
class LinkController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Redirect to original URL by code
     * @param string $code
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRedirect($code)
    {
        $link = Link::findOne(['code' => $code]);
        
        if (!$link) {
            throw new NotFoundHttpException('Ссылка не найдена');
        }

        // Record click
        $click = new Click();
        $click->link_id = $link->id;
        $click->ip_address = Yii::$app->request->userIP;
        $click->clicked_at = time();
        $click->save(false);

        return $this->redirect($link->original_url);
    }

    /**
     * List all links for current user
     * @return string
     */
    public function actionIndex()
    {
        $links = Link::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'links' => $links,
        ]);
    }

    /**
     * Create a new short link
     * @return \yii\web\Response|string
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        
        if ($request->isPost) {
            $originalUrl = $request->post('original_url');
            
            // Validate
            $validation = \yii\helpers\ArrayHelper::getValue($this->validateUrl($originalUrl), 'errors');
            if (!empty($validation)) {
                Yii::$app->session->setFlash('error', $validation[0]);
                return $this->redirect(['index']);
            }

            try {
                $generator = Yii::createObject([
                    'class' => CodeGenerator::class,
                    'codeLength' => Yii::$app->params['codeLength'],
                ]);
                $code = $generator->generate();
            } catch (\RuntimeException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['index']);
            }

            $link = new Link();
            $link->user_id = Yii::$app->user->id;
            $link->code = $code;
            $link->original_url = $originalUrl;
            
            if ($link->save()) {
                Yii::$app->session->setFlash('success', 'Ссылка создана: ' . $link->shortUrl);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при создании ссылки');
            }

            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    /**
     * Validate URL
     * @param string $url
     * @return \yii\web\Response
     */
    private function validateUrl($url)
    {
        $validator = new \yii\validators\UrlValidator();
        $validator->max = 2048;
        return $validator->validate($url);
    }
}
