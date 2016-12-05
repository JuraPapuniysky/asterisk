<?php

namespace common\models;



use PAMI\Message\Action\CommandAction;
use yii\base\Model;

class ConfBridgeActions extends Model
{
    public $clientImpl;

    public function __construct($clientImpl)
    {
        $this->clientImpl = $clientImpl;
    }

    /**
     * Removes the specified channel from the conference.
     * @param $conference
     * @param $channel
     * @return mixed
     */
    public function confBridgeKick($conference, $channel)
    {
        foreach (Clients::findAll(['video' => 'yes']) as $client){
            $client->video = 'no';
            $client->save();
        }
        $message = $this->clientImpl->send(new CommandAction("confbridge kick $conference $channel"));
        return $message;
    }

    public function confBridgeList()
    {
        $message = $this->clientImpl->send(new CommandAction('confbridge list'));
        $conferences = $this->unsetElems(explode("\n", $message->getRawContent()), 4);
        array_pop($conferences);
        return $this->strTok($conferences);
    }

    /**
     * Shows a detailed
     * listing of participants in a specified conference.
     * @return mixed
     */
    public function confBridgeConferenceList($conferences)
    {
        if(isset($conferences)) {
            foreach ($conferences as $conference) {

                $message = $this->clientImpl->send(new CommandAction("confbridge list $conference"));

                $users = $this->unsetElems(explode("\n", $message->getRawContent()), 4);

                array_pop($users);
                $i = 0;

                foreach ($users as $user) {
                    $channel = $this->strTok([$user]);
                    $userArray[$i] = new ConferenceUser($conference, $channel[0], $user);
                    $i++;
                }
                $conferenceList = [$conference => $userArray];
            }
            return $conferenceList;
        }
    }

    /**
     * Locks a specified conference so that only Admin users can join.
     * @param $conference
     * @return mixed
     */
    public function confBridgeLock($conference)
    {
        $message = $this->clientImpl->send(new CommandAction("confbridge lock $conference"));
        return $message;
    }

    /**
     * Unlocks a specified conference so that only Admin users can join.
     * @param $conference
     * @return mixed
     */
    public function confBridgeUnlock($conference)
    {
        $message = $this->clientImpl->send(new CommandAction("confbridge unlock $conference"));
        return $message;
    }

    /**
     * Mutes a specified user in a specified conference.
     * @param $conference
     * @param $channel
     * @return mixed
     */
    public function confBridgeMute($conference, $channel)
    {
        $message = $this->clientImpl->send(new CommandAction("confbridge mute $conference $channel"));
        return $message;
    }

    /**
     * Unmutes a specified user in a specified conference
     * @param $conference
     * @param $channel
     * @return mixed
     */
    public function confBridgeUnmute($conference, $channel)
    {
        $message = $this->clientImpl->send(new CommandAction("confbridge unmute $conference $channel"));
        return $message;
    }

    /**
     * Begins recording a conference. If "file" is specified, it will be used, otherwise,
     * the Bridge Profile record_file will be used. If the Bridge Profile does not specify a
     * record_file, one will be automatically generated in Asterisk's monitor directory.
     * @param $conference
     * @param $file
     * @return mixed
     */
    public function confBridgeRecordStart($conference, $file)
    {
        $message = $this->clientImpl->send(new CommandAction("confbridge record start $conference $file"));
        return $message;
    }

    /**
     * Stops recording the specified conference.
     * @param $conference
     * @param $file
     * @return mixed
     */
    public function confBridgeRecordStop($conference)
    {
        $message = $this->clientImpl->send(new CommandAction("confbridge record stop $conference"));
        return $message;
    }
    

    public function unsetElems($array, $count)
    {
        for($i = 0; $i<=$count; $i++)
        {
            unset($array[$i]);
        }
        return $array;
    }

    public function strTok($array)
    {
        $i = 0;
        foreach ($array as $arr)
        {
           $tok = strtok($arr, ' ');
           $str_arr[$i] = $tok;
           $i++;
        }
        if(isset($str_arr)) {
            return $str_arr;
        }
    }

}

