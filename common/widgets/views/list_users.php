
<?php
/* @var  $user \common\models\ConferenceUsers */

use yii\helpers\Html;
use rmrevin\yii\fontawesome\cdn\AssetBundle;

$headpiece = \common\models\Clients::getHeadpiece();
AssetBundle::register($this);
?>


<h3><span class="label label-info">Список учасников конференции <?= \Yii::$app->pamiconn->generalConference ?></span></h3>
<div class="row">
    <div class="col-md-2">
        <?php
        echo Html::a(
                "Выкл. все мик.",
                ['/site/mutte-unmutte-all/', 'action' => 'yes'],
                ['class' => 'btn btn-danger fa fa-microphone-slash fa-5x', 'id' => 'muted_user']
            ).'<br />';
        ?>
    </div>
    <div class="col-md-2"><?php
        echo Html::a(
            "Вкл. все мик.",
            ['/site/mutte-unmutte-all/','action' => 'no' ],
            ['class' => 'btn btn-success fa fa-microphone fa-5x', 'id' => 'muted_user']
        );

        ?></div>
</div>




<div class="row">

    <div class="col-md-4">

        <table class="table table table-condensed table-hover table-bordered">

            <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>№ конф</th>
                <th>Имя</th>
                <th>Номер</th>
                <th>Мик.</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php $count = 1; foreach ($conferences as $user){ ?>
                    <?php if ($user->isActive){ ?>
                        <tr class="text-success">
                        <?php } else { ?>
                          <tr>
                    <?php } ?>
                        <td><?= Html::a(
                            "<span class=\"glyphicon glyphicon-remove\">",
                            ['/site/kick/', 'conference' => $user->conference, 'channel' => $user->channel ],
                            ['class' => '', 'data-confirm' => "Вы уверены, что хотите удалить пользователя $user->name из конференции?"]
                        ) ?></td>
                    <td><?php
                            if(!$user->isActive) {
                             echo   Html::a(
                                    "",
                                    ['/site/call/', 'conference' => Yii::$app->pamiconn->generalConference, 'callerid' => $user->callerId],
                                    ['class' => 'btn btn-lg btn-success fa fa-phone', 'id' => 'call-button']);
                        }


                        ?></td>
                        <td><?= $count ?></td>
                        <td><?= $user->conference ?></td>
                        <td><?= $user->name ?></td>
                        <td><?= $user->callerId ?></td>
                        <td><?php if($user->mutte == 'no' and $user->isActive)
                            {
                                echo Html::a(
                                    '',
                                    ['/site/mutte/', 'userid' => $user->id,],
                                    ['class' => 'btn btn-lg btn-success fa fa-microphone fa-5x', 'id' => 'muted_user',]
                                );
                            }else if($user->mutte == 'yes' and $user->isActive){
                                echo Html::a(
                                    '',
                                    ['/site/unmutte/', 'userid' => $user->id],
                                    ['class' => 'btn btn-lg btn-danger fa fa-microphone-slash fa-5x', 'id' => 'unmuted_user',]

                                );
                            }?></td>
                        <td><?php if($user->isActive){ echo Html::a(
                                '<span class="glyphicon glyphicon-facetime-video"></span>',
                                ['/site/set-single-video/', 'conference' => $user->conference, 'channel' => $user->channel],
                                ['class' => 'btn btn-default btn-lg', 'id' => 'muted_user']);} ?>
                        </td>
                        <td><?php if($user->isActive){ echo Html::a(
                                '<span class="glyphicon glyphicon-picture"></span>',
                                ['/site/set-single-video/', 'conference' => $headpiece->conference, 'channel' => $headpiece->channel],
                                ['class' => 'btn btn-default btn-lg ', 'id' => 'muted_user']);} ?>
                        </td>
                    </tr>

                <?php $count++; } ?>
            </tbody>
        </table>

    </div>

</div>
<div class="row">
    <div class="col-md-2">
        <?php echo Html::a(
            "Удалить всех учасников",
            ['/site/kick-all/',],
            ['class' => 'btn btn-danger glyphicon glyphicon-remove', 'id' => 'kick_users']
        );

        ?>
    </div>
</div>