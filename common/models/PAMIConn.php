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
use yii\base\Model;

class PAMIConn extends Model
{
    private $_options;

    public $peers;

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
            $this->peers = $this->asterisk->send(new ListCommandsAction());
            $time = time();
            //while((time() - $time) < 60) // Wait for events 60. or while(true)
           // {
                usleep(1000); // 1ms delay
                // Since we declare(ticks=1) at the top, the following line is not necessary
                $this->asterisk->process();
           // }
            $this->asterisk->close(); // send logoff and close the connection.
        }catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
