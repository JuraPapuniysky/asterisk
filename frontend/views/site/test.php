<?php



use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'test';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-test">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php

    print_r($info);

    ?>

</div>


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

















