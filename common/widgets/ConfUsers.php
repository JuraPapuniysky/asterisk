<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 30.06.16
 * Time: 8:56
 */

namespace common\widgets;


use yii\bootstrap\Widget;

class ConfUsers extends Widget
{

    public $conferences;
    public $size = 2;
    

    public function getColumns($size)
    {
        $count = 0;
        $columns = [];
        foreach ($this->conferences as $conference)
        {
            $columns[$count] = array_chunk($conference, $size);
            $count++;
        }
        return $columns;
    }

    public function run()
    {
        return $this->render('conf_users',[
            'columns'=>$this->getColumns($this->size),
            'conferences' => $this->conferences,
        ]);
    }


    
}