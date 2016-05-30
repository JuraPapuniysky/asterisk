<?php


namespace common\models;


use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;

class EventListener implements IEventListener
{
    public function handle(EventMessage $event)
    {
        //var_dump($event);
    }
}