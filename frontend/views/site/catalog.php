<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ClientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $callUser common\models\CallUserManual */

$this->title = 'Справочник';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="clients-index">
<div class="row">

    <div class="col-md-3">
        <h1>Ручной ввод номера</h1>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($callUser, 'conference')->textInput() ?>

        <?= $form->field($callUser, 'userNumber')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Звонить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="col-md-9">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['clients/create'], ['class' => 'btn btn-success']) ?>
    </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'buttons' => [
                            'update' => function ($url, $model) {
                                            return Html::a(
                                                "<span class=\"glyphicon glyphicon-pencil\">",
                                                ['/clients/update/', 'id' => $model->id],
                                                ['id' => 'call-button']
                                            );
                                        },
                            'delete' => function($url,$model){
                                            return Html::a(
                                                "<span class=\"glyphicon glyphicon-trash\">",
                                                ['/clients/delete/', 'id' => $model->id],
                                                ['id' => 'call-button', 'data-confirm' => "Вы уверены, что хотите удалить пользователя $model->name из справочника?"]
                                            );
                            },

                                ],
                ],
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
                'name',
                //'channel',
                //'conference',
                //'mutte',
                'callerid',
                // 'video',
                [
                    'label' => 'Вызов',
                    'format' => 'raw',
                    'value' => function($data){
                        return  Html::a(
                            "Вызов",
                            ['/site/call/', 'conference' => Yii::$app->pamiconn->generalConference, 'callerid' => $data->callerid],
                            ['class' => 'btn btn-lg btn-success', 'id' => 'call-button']
                        );
                    }
                ],
                [
                    'label' => '',
                    'format' => 'raw',
                    'value' => function($data){
                        return Html::checkbox('call',$checked = false, [
                            'id' => $data->id,
                            'value' => $data->callerid,
                            'label' => $data->name,
                            'onclick' => 'do_one(this)',
                        ]);
                    },

                ],

                [
                    'label' => '',
                    'format' => 'raw',
                    'value' => function($data){
                            if(array_search($data->callerid,  Yii::$app->pamiconn->confUser) != null){
                                return "<h4><span class=\"glyphicon glyphicon-ok text-success\"></span></h4>";
                            }else{
                                return ' ';
                            }
                    },

                ],



            ],
        ]); ?>

    <?php echo Html::a(
        "Вызов выбраных",
        ['/site/call-checked/', 'conference' => Yii::$app->pamiconn->generalConference, 'callerids' => ""],
        ['class' => 'btn btn-lg btn-success pull-right', 'id' => 'call-all-button']
    );?>
        </div>

    </div>
</div>

<script type="text/javascript">
    var elemsId = 0
    var chekedUser = '';
    var callButtonHref = document.getElementById('call-all-button').href;
function checkAll(source)
{

    tmp = '';
    chekedUser = '';
    for(i=0;i<elemsId;i++)
    {
        document.getElementById(i).checked = source.checked;
        tmp = document.getElementById(i).value;
        chekedUser = chekedUser + tmp + ',';
    }

    if(!source.checked) {
        chekedUser = '';
    }
    document.getElementById('call-all-button').href = callButtonHref + chekedUser

}

function do_one(source)
{
    if(!source.checked)
    {
        document.getElementById('all').checked=false;
        if(chekedUser.indexOf(source.value)+1)
        {
            chekedUser = chekedUser.replace(source.value+',', '');
            document.getElementById('call-all-button').href = callButtonHref + chekedUser;
        }

    }
    else
    {

        chekedUser = chekedUser+source.value+',';
        document.getElementById('call-all-button').href = callButtonHref + chekedUser;
        set_checked=true;
        for(i=0;i<elemsId;i++)
		{
            if(!document.getElementById(i).checked)
            {
                set_checked=false;
                break;
            }
        }
        if(set_checked)
        {
            document.getElementById('all').checked=true;
        }
    }
}
</script>
