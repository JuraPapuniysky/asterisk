<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 25.05.16
 * Time: 10:15
 */

namespace common\models;


use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\ListCommandsAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\SIPPeersAction;
use yii\base\Model;

class PAMIConn extends Model
{
    private $_options;

    public $peers;

    public $command = 'sip show peers';

    public $commandAction;

    public $list_command;
    public $originate;

    public $asterisk;
    
    public function init()
    {
        $this->_options = [
            'host' => '10.109.33.150',
            'port' => '5038',
            'username' => 'admin',
            'secret' => 'admin',
            'connect_timeout' => 5000,
            'read_timeout' => 50000,
            'scheme' => 'tcp://',// try tls://
        ];
    }

    public function start()
    {
        try {
            $this->asterisk = new ClientImpl($this->_options);
            $this->asterisk->registerEventListener(new Asterisk());
            $this->asterisk->open();
            $this->list_command = $this->asterisk->send(new ListCommandsAction());
            $this->peers = $this->asterisk->send(new SIPPeersAction())->getEvents();
            $this->commandAction = $this->asterisk->send(new CommandAction($this->command));
            $orig = new OriginateAction('SIP/112');
            $orig->setContext('default');
            $orig->setPriority(1);
            $this->originate = $this->asterisk->send($orig);



            $time = time();
            while((time() - $time) < 60) // Wait for events 60. or while(true)
            {
                usleep(1000); // 1ms delay
                // Since we declare(ticks=1) at the top, the following line is not necessary
                $this->asterisk->process();
            }
            $this->asterisk->close(); // send logoff and close the connection.
        }catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
