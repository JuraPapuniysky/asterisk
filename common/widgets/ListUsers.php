<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 21.11.16
 * Time: 11:47
 */

namespace common\widgets;


use yii\bootstrap\Widget;

class ListUsers extends Widget
{
    public $conferences;

    public function run()
    {
        return $this->render('list_users',[
            'conferences' => $this->conferences,
        ]);
    }
}