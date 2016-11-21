<?php

namespace frontend\controllers;

use common\models\Clients;
use common\models\ListClient;
use Yii;
use common\models\ListModel;
use common\models\ListModelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ListController implements the CRUD actions for ListModel model.
 */
class ListController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ListModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $lists = ListModel::find()->all();
        $list = $lists[0];
        $li = $list->getListClients()->orderBy('name')->all();
        return $this->render('index', [
           'lists' => $lists
        ]);
    }

    /**
     * Displays a single ListModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ListModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ListModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ListModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ListModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        foreach (ListClient::findAll(['list_id' => $id]) as $item){
            $item->delete();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAddToList($id = null)
    {
        $model = new ListClient();
        $clients = Clients::find()->all();
        $lists = ListModel::find()->all();
        if($id != null){
            $model->list_id = $id;
        }else{
            $list = null;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('add', [
                'model' => $model,
                'clients' => $clients,
                'lists' => $lists,
            ]);
        }
    }

    public function actionCallList($id)
    {
        $users = ListModel::findOne($id)->getListClients()->all();
        $pami = Yii::$app->pamiconn;
        $pami->initAMI();
        foreach ($users as $user){
            $pami->call($pami->generalConference, $user->callerid);
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteFromList($client_id)
    {
        if(($model = ListClient::findOne(['client_id' => $client_id])) !== null){
            $model->delete();
            return $this->redirect(['index']);
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


    }

    /**
     * Finds the ListModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ListModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ListModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
