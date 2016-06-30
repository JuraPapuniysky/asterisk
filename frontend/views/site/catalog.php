<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ClientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['clients/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table table-condensed table-bordered">
        <thead>
        <tr>
            <th></th>
            <th>№</th>
            <th>Имя пользователя</th>
            <th>Номер пользователя</th>
            <th>Управление</th>
            <th><?= Html::checkbox('callAll',$checked = false, [
                    'id' => 'all',
                    'value' => '0',
                    'label' => 'Выбрать всех',
                    'onclick' => 'checkAll(this)',
                ]) ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; foreach ($model as $user){ ?>
            <tr>
                <td><?php echo Html::a(
                            "<span class=\"glyphicon glyphicon-trash\">",
                            ['/clients/delete/', 'id' => $user->id],
                            ['id' => 'call-button', 'data-confirm' => "Вы уверены, что хотите удалить пользователя $user->name из справочника?"]
                        );?>
                    <?php echo Html::a(
                        "<span class=\"glyphicon glyphicon-pencil\">",
                        ['/clients/update/', 'id' => $user->id],
                        ['id' => 'call-button']
                    );?></td>
                <td><?= $i ?></td>
                <td><?= $user->name ?></td>
                <td><?= $user->callerid ?></td>
                <td><?php echo Html::a(
                        "Вызов",
                        ['/site/call/', 'conference' => Yii::$app->pamiconn->generalConference, 'callerid' => $user->callerid],
                        ['class' => 'btn btn-lg btn-success', 'id' => 'call-button']
                    );?></td>
                <td><?= Html::checkbox('call',$checked = false, [
                        'id' => $i-1,
                        'value' => $user->callerid,
                        'label' => $user->name,
                        'onclick' => 'do_one(this)',
                    ]) ?></td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
    <?php echo Html::a(
        "Вызов выбраных",
        ['/site/call-checked/', 'conference' => Yii::$app->pamiconn->generalConference, 'callerids' => ""],
        ['class' => 'btn btn-lg btn-success pull-right', 'id' => 'call-all-button']
    );?>

</div>

<script type="text/javascript">
    var elemsId = <?php echo $i-1; ?>;
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
