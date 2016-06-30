<?php


namespace common\widgets;


use yii\bootstrap\Widget;

class ConfUsers extends Widget
{

    public $conferences;
    public $size = 10;
    

    public function getColumns()
    {
        $count = 0;
        $columns = [];
        foreach ($this->conferences as $conference)
        {
            $columns[$count] = array_chunk($conference, $this->size);
            $count++;
        }
        return $columns;
    }

    public function run()
    {
        return $this->render('conf_users',[
            'columns'=>$this->getColumns(),
            'conferences' => $this->conferences,
        ]);
    }


    
}