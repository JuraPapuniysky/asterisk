<?php


namespace common\models;


use yii\base\Model;

class ConferenceUsers extends Model
{
    public $id;
    public $name;
    public $conference;
    public $callerId;
    public $channel;
    public $mutte;
    public $video;
    public $isActive = false;

    public static $activeClients;
    public static $confList;


    /**
     *
     */
    public static function getConfList()
    {
        self::$confList = ListModel::find()->where(['name' => '123'])->one()->getListClients()->orderBy('name')->all();
    }

    /**
     *
     */
    public static function getActiveClients()
    {
        $pami = \Yii::$app->pamiconn;
        $pami->initAmi();
        $message = $pami->clientImpl->send(new ConfBridgeListAction($pami->generalConference));
        $events = $message->getEvents();
        $activeUsers = [];
        if(isset($events)) {
            foreach ($events as $event){
                $keys = $event->getKeys();
                if($keys['event'] != 'ConfbridgeListComplete') {
                    array_push($activeUsers, $keys);
                }
            }
        }else{
            $activeUsers = null;
        }
        self::$activeClients = $activeUsers;
    }

    /**
     * @param $activeClients
     * @param $confList
     */
    public static function getConference()
    {
        self::getActiveClients();
        self::getConfList();
        $conference = [];
        foreach (self::$confList as $client){
            $user = new ConferenceUsers();
            $user->id = $client->id;
            $user->name = $client->name;
            $user->conference = $client->conference;
            $user->callerId = $client->callerid;
            $user->mutte = $client->mutte;
            $user->video = $client->video;
            foreach (self::$activeClients as $key => $activeClient){
                if($user->callerId == $activeClient['calleridnum']){
                    $user->isActive = true;
                    $user->channel = $activeClient['channel'];
                    unset(self::$activeClients[$key]);
                }
            }
            $clients = Clients::findOne(['id' => $client->id]);
            $clients->channel = $user->channel;
            $clients->mutte = $user->mutte;
            $clients->save();
            array_push($conference, $user);


        }
        return $conference;
    }

    public static function nonListPush($conference)
    {
        if (self::$activeClients != null){
            foreach (self::$activeClients as $key => $activeClient){
                $client = Clients::findOne(['callerid' => $activeClient['calleridnum']]);
                $listUser = new ConferenceUsers();
                $listUser->id = $client->id;
                $listUser->name = $client->name;
                $listUser->conference = $client->conference;
                $listUser->callerId = $client->callerid;
                $listUser->mutte = $client->mutte;
                $listUser->video = $client->video;
                $listUser->channel = $activeClient['channel'];
                $listUser->isActive = true;
                unset(self::$activeClients[$key]);
                $client->channel = $listUser->channel;
                $client->save();
                array_push($conference, $listUser);
            }
            return $conference;
        }else{
            return $conference;
        }

    }



}