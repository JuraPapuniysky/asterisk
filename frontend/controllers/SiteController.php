<?php
namespace frontend\controllers;



use common\models\CallUserManual;
use common\models\Clients;
use common\models\ClientsSearch;
use common\models\ConfBridgeActions;
use common\models\ConfBridgeListAction;
use common\models\ConferenceUsers;
use common\models\ListModel;
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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
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
     * Homepage - the list of conference users
     * @return mixed
     */
    public function actionIndex()
    {

        $confUsers = new ConferenceUsers();

        $conf = $confUsers::getConference();
        $conf = $confUsers::nonListPush($conf);



        return $this->render('index',[
           'conferences' => $conf,
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

    /**
     * Call to user action
     * @param $conference number of conference
     * @param $callerid number of user
     * @return \yii\web\Response
     */
    public function actionCall($conference, $callerid)
    {
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        $pami->call($conference, $callerid);
        usleep(1000);
        $pami->closeAMI();

        return $this->redirect(['index']);
    }

    /**
     * Call to regular users of conference, from list
     * @return \yii\web\Response
     */
    public function actionCallList()
    {
        foreach (ConferenceUsers::getConference() as $user){
            $user->call();
        }

        return $this->redirect(['index']);
    }

    /**
     * Call checked users from calalog
     * @param $conference
     * @param $callerids
     * @return string
     */
    public function actionCallChecked($conference, $callerids)
    {
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        $pami->callChecked($conference, $callerids);
        $pami->closeAMI();



        return $this->redirect(['index']);


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

    /**
     * Mutte user whis id = $userid
     * @param $userid id of user
     * @return \yii\web\Response
     */
    public function actionMutte($userid)
    {
        $client = Clients::findOne(['id' => $userid]);
        ConferenceUsers::mutteUser($client);

        return $this->redirect(['index']);
    }

    /**
     * Unmutte user whis id = $userid.
     * @param $userid id of user
     * @return \yii\web\Response
     */
    public function actionUnmutte($userid)
    {
        $client = Clients::findOne(['id' => $userid]);
        ConferenceUsers::unmutteUser($client);

        return $this->redirect(['index']);
    }

    /**
     * View all users from calalog whis active/non active condition.
     * @return string
     */
    public function actionCatalog()
    {
        $searchModel = new ClientsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);
        $model = new Clients();

        $callUser = new CallUserManual();

        if($callUser->load(Yii::$app->request->post()))
        {
            $pami = Yii::$app->pamiconn;
            $pami->initAMI();
            $pami->call($callUser->conference, $callUser->userNumber);
            usleep(1000);
            $pami->closeAMI();
        }

        Yii::$app->pamiconn->confUser = Clients::getUserConfId($this->viewUsers());

        return $this->render('catalog', [
            'model' => $model->find()->all(),
            'callUser' => $callUser,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Mutte or unmutte active conference users whis depending on the $action
     * @param string $action yes-mutte, no unmutte.
     * @return \yii\web\Response
     */
    public function actionMutteUnmutteAll($action)
    {
        $activeUsers = ConferenceUsers::getActiveClients();

        foreach ($activeUsers as $user){
            $client = Clients::findOne(['callerid' => $user['calleridnum']]);
            if($action == 'yes') {
                ConferenceUsers::mutteUser($client);
            }elseif ($action == 'no'){
                ConferenceUsers::unmutteUser($client);
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Sets video of user whis channel = $channel in conference num = $conference
     * @param string $conference
     * @param string $channel
     * @return \yii\web\Response
     */
    public function actionSetSingleVideo($conference, $channel)
    {
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        $pami->setSingleVideo($conference, $channel);
        $pami->closeAMI();

        return $this->redirect(['index']);
    }

    /**
     * Kicks a user from conference num = $conference, whis channel = $chanel
     * @param string $conference
     * @param string $channel
     * @return \yii\web\Response
     */
    public function actionKick($conference, $channel)
    {
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        $conf = new ConfBridgeActions($pami->clientImpl);
        $conf->confBridgeKick($conference, $channel);
        $pami->closeAMI();

        return $this->redirect(['index']);
    }

    public function actionKickAll()
    {
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        $conf = new ConfBridgeActions($pami->clientImpl);
        foreach (ConferenceUsers::getActiveClients() as $user){
            $conf->confBridgeKick($user['conference'], $user['channel']);
        }
        $pami->closeAMI();
        return $this->redirect(['index']);

    }

    protected function findByCallerId($callerId, $user = null)
    {
        if (($model = Clients::findOne(['callerid' => $callerId,])) !== null) {
            return $model;
        } else {
           return false;
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

    protected function viewUsers($callerId = null)
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

                    if($user->callerId == ""){
                        if($callerId != null) {
                            $user->callerId = $callerId;
                        }else{
                            $user->callerId = self::findByChannel($user->channel)->callerid;
                        }
                    }

                    if(!$client = $this->findByCallerId($user->callerId)) {
                        $client = new Clients();
                        $client->name = $user->callerId;
                        $client->callerid = $user->callerId;
                        $client->conference = $user->conference;
                        $client->channel = $user->channel;
                    }else{
                        $client->conference = $user->conference;
                        $client->channel = $user->channel;
                    }
                    if ($client->save()) {
                        $user->id = $client->id;
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
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        $conf = new ConfBridgeActions($pami->clientImpl);
        $info = $conf->confBridgeList();

        return $this->render('test', [
            'info' => $info,
        ]);
    }


}
