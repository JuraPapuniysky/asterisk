<?php
use yii\helpers\Html;

//$conferences = $columns;
$headpiece = \common\models\Clients::getHeadpiece();
 echo Html::a(
    '',
    ['/site/index/'],
    ['class' => 'glyphicon glyphicon-refresh', 'id' => 'refreshButton']
);
        if($conferences == null)
        {?>
            <h3><span class="label label-info">Нет активных конференций</span></h3>

        <?php }?>
        <?php foreach ($columns as $arrayConf){?>
        <?php foreach  ($conferences as $conference) { $i = 0; $conf = $conference[$i];?>

    <h3><span class="label label-info">Список учасников конференции <?= $conf->conference?></span></h3>
    <?php
    echo Html::a(
        "Выключить все микрофоны $conf->conference",
        ['/site/mutte-unmutte-all/', 'conference' => $conf->conference, 'action' => 'yes'],
        ['class' => 'btn btn-small btn-danger', 'id' => 'muted_user']
    ).'<br />';

    echo Html::a(
        "Включить все микрофоны $conf->conference",
        ['/site/mutte-unmutte-all/', 'conference' => $conf->conference, 'action' => 'no' ],
        ['class' => 'btn btn-small btn-success', 'id' => 'muted_user']
    );

    ?>

    <div class="row">



    <?php foreach ($arrayConf as $column){ ?>
    <div class="col-md-4">
        <table class="table table table-condensed table-hover table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>№ конф</th>
                <th>Имя</th>
                <th>Номер</th>
                <th>Управление микрофоном</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $i++; $count = 0; foreach ($column as $user){ ?>
                <tr>
                    <td><?= Html::a(
                            "<span class=\"glyphicon glyphicon-remove\">",
                            ['/site/kick/', 'conference' => $user->conference, 'channel' => $user->channel ],
                            ['class' => '', 'data-confirm' => "Вы уверены, что хотите удалить пользователя $user->name из конференции?"]
                        ) ?></td>
                    <td><?= $user->conference ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $user->callerId ?></td>
                    <td><?php if($user->mutted == 'no')
                        {
                            echo Html::a(
                                '<span class="glyphicon glyphicon-remove">Выкл. микрофон</span>',
                                ['/site/mutte/', 'conference' => $user->conference, 'channel' => $user->channel ],
                                ['class' => 'btn btn-danger', 'id' => 'muted_user']
                            );
                        }else if($user->mutted == 'yes'){
                            echo Html::a(
                                '<span class="glyphicon glyphicon-ok">Вкл. микрофон</span>',
                                ['/site/unmutte/', 'conference' => $user->conference, 'channel' => $user->channel ],
                                ['class' => 'btn btn-success', 'id' => 'unmuted_user']
                            );
                        }?></td>
                    <td><?= Html::a(
                            '<span class="glyphicon glyphicon-facetime-video"></span>',
                            ['/site/set-single-video/', 'conference' => $user->conference, 'channel' => $user->channel],
                            ['class' => 'btn btn-default btn-xs', 'id' => 'muted_user']) ?>
                    </td>
                    <td><?= Html::a(
                            '<span class="glyphicon glyphicon-picture"></span>',
                            ['/site/set-single-video/', 'conference' => $headpiece->conference, 'channel' => $headpiece->channel],
                            ['class' => 'btn btn-default btn-xs ', 'id' => 'muted_user']) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>


        <?php } ?>


<?php } }?>


<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 6000);
});

$(document).ready(function() {
    setInterval(function(){ location.reload(); }, 20000);
});
JS;
$this->registerJs($script);
?>
