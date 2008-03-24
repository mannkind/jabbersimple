<?php
require_once('jabberclass/class_Jabber.php');
require_once('JabberSimple.class.php');

/**
 * NewJabberBot - An example Jabber Bot
 * 
 * @uses JabberSimple
 */
class NewJabberBot extends JabberSimple {

    public function __construct(){
        $params = array(
            'server'=>'example.com',
            'username'=>'exampleuser',
            'password'=>'1ExamplePasswordXorz!',
            'debug'=>TRUE, // Turn on debugging
        );

        parent::__construct($params);
    }

    /**
     * Handle the Jabber message_chat event
     * 
     * <PHPDoc Here>
     */
    public function handle_message_chat($from, $to, $body, $subject, $thread, $id, 
        $extended){

        # Do stuff to handle the message_chat event
    }

    /**
     * Do some stuff during the Jabber heartbeat event
     * 
     * <PHPDoc Here>
     */
    public function handle_heartbeat(){
        # Do stuff to handle the heartbeat event
    }
}

$NewJabberBot = new NewJabberBot();
?>
