<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $list common\models\ListModel */


$this->title = 'Списки вызываемых';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-model-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить список', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row">
        <?php if($lists != null){ ?>

                <?php foreach ($lists as $list){ ?>
                <div class="col-md-4">
                    <p>
                        <?= Html::label('Список '.$list->name) ?>
                    </p>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default">Действия</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Вызвать список', ['call-list', 'id' =>$list->id], ['class' => 'btn btn-success']) ?></li>
                            <li><?= Html::a('Добавить номер', ['add-to-list', 'id' =>$list->id], ['class' => 'btn btn-primary']) ?></li>
                            <li role="separator" class="divider"></li>
                            <li><?= Html::a('Удалить список', ['delete', 'id' =>$list->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                                ])?></li>
                        </ul>
                    </div>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td>№</td>
                            <td>Имя</td>
                            <td>Номер</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach ($list->getListClients()->all() as $client){ ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $client->name ?></td>
                                <td><?= $client->callerid ?></td>
                                <td><?= Html::a(
                                        "<span class=\"glyphicon glyphicon-remove\">",
                                        ['/list/delete-from-list/', 'client_id' => $client->id]
                                    ) ?></td>
                            </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
        <?php }else{ ?>
            <p>Вы не создавали списков </p>
        <?php } ?>
    </div>
</div>

