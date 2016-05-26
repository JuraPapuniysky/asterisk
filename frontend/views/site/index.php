<?php

/* @var $this yii\web\View */
/* @var ast_conn common/models/PAMIConn */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <?php print_r($ast_conn);
                $ast_conn->init();
                $ast_conn->start();
                ?>
            </div>
            <div class="col-lg-4">
                <?php  print_r($ast_conn->peers) ?>
            </div>
            <div class="col-lg-4">



            </div>
        </div>

    </div>
</div>
