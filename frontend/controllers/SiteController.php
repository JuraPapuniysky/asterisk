<?php
namespace frontend\controllers;



use common\models\Clients;
use common\models\ConfBridgeActions;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\RedirectAction;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;


/**
 * Site controller
 */
class SiteController extends Controller
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
     * @inheritdoc
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
     * @return mixed
     */
    public function actionIndex()
    {
        $confArray = $this->viewUsers();
            
        return $this->render('index',[
           'conferences' => $confArray,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionCall($conference, $callerid)
    {
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();

        $pami->call($conference, "SIP/$callerid");
        usleep(1000);

        $confArray = $this->viewUsers();

        return $this->render('index',[
            'conferences' => $confArray,
        ]);
    }


    public function actionRedirect()
    {
        $pami = Yii::$app->pamiconn;
        $pami->init();
        $aster = $pami->clientImpl;
        $aster->open();
        $redir = new RedirectAction('SIP/112', 501, 'dialout', 1);
        $aster->send($redir);
        usleep(1000);

        $aster->process();
        $aster->close();
        return $this->render('index',[
            'module' => $redir,
        ]);
    }

    public function actionMutte($conference, $channel)
    {
        $client = self::findByChannel($channel);
        $client->mutte = 'yes';
        if($client->save()){
            $pami = Yii::$app->pamiconn;
            $pami->initAMI();
            $confBridge = new ConfBridgeActions($pami->clientImpl);
            $confBridge->confBridgeMute($conference, $channel);
        }
        $confArray = $this->viewUsers();

        return $this->render('index', [
            'conferences' => $confArray,
        ]);
    }

    public function actionUnmutte($conference, $channel)
    {
        $client = self::findByChannel($channel);
        $client->mutte = 'no';
        if($client->save()){
            $pami = Yii::$app->pamiconn;
            $pami->initAMI();
            $confBridge = new ConfBridgeActions($pami->clientImpl);
            $confBridge->confBridgeMute($conference, $channel);
        }
        $confArray = $this->viewUsers();

        return $this->render('index', [
            'conferences' => $confArray,
        ]);
    }
    
    public function actionCatalog()
    {
        $model = new Clients();

        return $this->render('catalog', [
            'model' => $model->find()->all(),
        ]);
    }

    public function actionMutteUnmutteAll($conference, $action)
    {
        $confArray = $this->viewUsers();
        foreach ($confArray as $conf)
        {
            foreach ($conf as $user) {
                if($user->conference == $conference) {
                    $model = $this->findByCallerId($user->callerId);
                    $model->mutte = $action;
                    $model->save();
                }
            }
        }
        $this->actionIndex();
    }

    protected static function findByCallerId($callerId)
    {
        if (($model = Clients::findOne(['callerid' => $callerId,])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected static function findByChannel($channel)
    {
        if (($model = Clients::findOne(['channel' => $channel,])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function viewUsers()
    {

        $pami = \Yii::$app->pamiconn;
        $pami->initAMI();
        $confBridge = new ConfBridgeActions($pami->clientImpl);
        $conferences = $confBridge->confBridgeList();
        $confUsers = $confBridge->confBridgeConferenceList($conferences);
        $confArray = [];
        $userArray = [];
        if(isset($confUsers)) {
            $m = 0;
            foreach ($confUsers as $conference) {
                $i = 0;
                foreach ($conference as $user) {

                    $client = self::findByCallerId($user->callerId);
                    $client->conference = $user->conference;
                    $client->channel = $user->channel;
                    if ($client->save()) {
                        $user->name = $client->name;
                        $user->conference = $client->conference;
                        $user->channel = $client->channel;
                        $user->callerId = $client->callerid;
                        $user->mutted = $client->mutte;
                        $user->video = $client->video;
                        if($client->mutte == 'yes')
                        {
                            $confBridge->confBridgeMute($client->conference, $client->channel);
                        }else if($client->mutte == 'no')
                        {
                            $confBridge->confBridgeUnmute($client->conference, $client->channel);
                        }else{
                            $confBridge->confBridgeMute($client->conference, $client->channel);
                        }
                        $userArray[$i] = $user;
                        $i++;
                    }
                }
                $confArray[$m] = $userArray;
            }
        }
        $pami->closeAMI();

        return $confArray;
    }

   

    public function actionTest()
    {
        $pami = \Yii::$app->pamiconn;
        $pami->initAMI();
        $confBridge = new ConfBridgeActions($pami->clientImpl);
        $conferences = $confBridge->confBridgeList();
        $confUsers = $confBridge->confBridgeConferenceList($conferences);
        return $this->render('test', [
            'model' => $confUsers,
        ]);
    }


}
