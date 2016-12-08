<?php


namespace common\models;


use yii\base\Model;
use PAMI\Message\Action\CommandAction;
use common\amiactions\ConfbridgeSetSingleVideoSrc;

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
     *  Sets the static list of conference users
     */
    protected static function getConfList()
    {
        self::$confList = ListModel::find()->where(['name' => '123'])->one()->getListClients()->orderBy('name')->all();
    }

    /**
     * Set active conference users
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
            self::$activeClients = $activeUsers = null;
            return self::$activeClients;
        }
        self::$activeClients = $activeUsers;
        return self::$activeClients;
    }

    /**
     * Gets list of static users
     * @param $activeClients
     * @param $confList
     * @return array
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
            $user->channel = $client->channel;
            $user->callerId = $client->callerid;
            if($client->mutte != null){
                $user->mutte = $client->mutte;
            }else{
                $user->mutte = 'no';
            }
            if($client->video == null){
                $user->video = 'no';
            }else{
                $user->video = $client->video;
            }
            foreach (self::$activeClients as $key => $activeClient){
                if($user->callerId == $activeClient['calleridnum']){
                    $user->isActive = true;
                    $user->conference = $activeClient['conference'];
                    $user->channel = $activeClient['channel'];
                    unset(self::$activeClients[$key]);
                }
            }
            $clients = Clients::findOne(['id' => $client->id]);
            $clients->channel = $user->channel;
            $clients->mutte = $user->mutte;
            $clients->video = $user->video;
            $clients->save();
            array_push($conference, $user);


        }
        return $conference;
    }


    /**
     * Adds to static list of users non staic users
     * @param $conference
     * @return mixed
     */
    public static function nonListPush($conference)
    {
        if (self::$activeClients != null){
            foreach (self::$activeClients as $key => $activeClient){
                if(($client = Clients::findOne(['callerid' => $activeClient['calleridnum']])) !== null) {
                    $listUser = new ConferenceUsers();
                    $listUser->id = $client->id;
                    $listUser->name = $client->name;
                    $listUser->conference = $client->conference;
                    $listUser->callerId = $client->callerid;
                    $listUser->channel = $activeClient['channel'];
                    $listUser->mutte = $client->mutte;
                    if($client->video == null){
                        $listUser->video = 'no';
                    }else{
                        $listUser->video = $client->video;
                    }

                    $listUser->isActive = true;
                    unset(self::$activeClients[$key]);
                    $client->channel = $listUser->channel;
                    if($client->save()){
                        array_push($conference, $listUser);
                    }
                }else{
                    $client = new Clients();
                    $client->callerid = $activeClient['calleridnum'];
                    $client->channel = $activeClient['channel'];
                    $client->conference = $activeClient['conference'];
                    $client->mutte = 'no';
                    $client->name = $activeClient['calleridnum'];
                    $client->video = 'no';
                    if($client->save()){
                        self::nonListPush();
                    }
                }
            }
            \Yii::$app->pamiconn->closeAMI();
            return $conference;
        }else{
            \Yii::$app->pamiconn->closeAMI();
            return $conference;
        }

    }

    /**
     * Mutes a specified user in a specified conference.
     * @param $conference num of conference
     * @param $channel channel of users
     * @return mixed
     */
    public static function mutteUser($client)
    {

        $client->mutte = 'yes';
        if($client->save()) {
            $pami = \Yii::$app->pamiconn;
            $pami->initAmi();
            $message = $pami->clientImpl->send(new CommandAction("confbridge mute $client->conference $client->channel"));
            usleep(1000);
            return $message;
        }
    }

    /**
     * Unmutes a specified user in a specified conference
     * @param $conference
     * @param $channel
     * @return mixed
     */
    public static function unmutteUser($client)
    {

        $client->mutte = 'no';
        if($client->save()) {
            $pami = \Yii::$app->pamiconn;
            $pami->initAmi();
            $message = $pami->clientImpl->send(new CommandAction("confbridge unmute $client->conference $client->channel"));
            usleep(1000);
            return $message;
        }
    }

    public function call($pami)
    {
        if($this->isActive != true){
            $pami->call($pami->generalConference, $this->callerId);
            usleep(1000);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Shows video from user phone in $conference, with $channel
     * @param $conference
     * @param $channel
     * @return mixed
     */
    public static function setVideo($conference, $channel)
    {
        $clients = Clients::findAll(['video' => 'yes']);
        foreach ($clients as $client){
            $client->video = 'no';
            $client->save();
        }
        $client = Clients::findOne(['channel' => $channel]);
        $client->video = 'yes';
        $client->save();
        $pami = \Yii::$app->pamiconn;
        $pami->initAmi();
        $message = $pami->clientImpl->send(new ConfbridgeSetSingleVideoSrc($conference, $channel));
        usleep(1000);
        return $message;
    }

    /**
     * Kick user from conference
     * @param $conference
     * @param $channel
     * @param $pami
     * @return mixed
     */
    public static function confBridgeKick($conference, $channel, $pami)
    {
        return $pami->clientImpl->send(new CommandAction("confbridge kick $conference $channel"));
    }

}