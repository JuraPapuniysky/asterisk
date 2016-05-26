<?php

/* @var $this yii\web\View */
/* @var ast_conn common/models/PAMIConn */

$this->title = 'My Yii Application';

$ast_conn->init();
$ast_conn->start();
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">
        Peers
        <?php foreach ($ast_conn->peers as $peer){ ?>
    <pre>
        <?php  print_r($peer->getKeys()); ?>
    </pre>
        <?php } ?>
        Commands

    <pre>
        <?php  print_r($ast_conn->list_command); ?>
    </pre>


    </div>
</div>
