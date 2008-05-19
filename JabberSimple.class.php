<?php
/**
 * JabberSimple - A simple extension to the Jabber class by Centova Technologies Inc.
 * (http://www.centova.com)
 *
 * The Jabber class is an *awesome* class. I love it. But
 * getting a JabberBot up and running took quite a bit of code.
 * I wanted to simplify it a bit, make it easier to use... thus
 * JabberSimple. 
 *
 * JabberSimple simplifies things by:
 *   - Automatically connecting and logging into the Jabber Server
 *   - Automatically discovering the handler methods of this class
 *     * Defined by handle_<handler name>
 *
 * Example Usage:
 *   - Create a class that extends JabberSimple (eg. NewJabberBot)
 *   - In NewJabberBot.__construct, pass an array to parent::__construct with:
 *     * 'server'=>
 *     * 'username'=>
 *     * 'password'=>
 *   - Create handler methods in NewJabberBot
 *   - Create a new instance of NewJabberBot 
 *   - Profit!
 *
 * @uses Jabber
 * @version 0.9
 * @author Dustin Brewer 
 * @license Revised BSD
 */
class JabberSimple extends Jabber {

    /**
     * @var Jabber The Jabber instance
     */
    protected $jab;

    /**
     * @var string The server
     */
    protected $jab_server;

    /**
     * @var string The username
     */
    protected $jab_username;

    /**
     * @var string The password
     */
    protected $jab_password;

    /**
     * Create a new JabberSimple class
     *
     * Parameters:
     *     (REQUIRED)
     *     * server: The server of the user
     *     * username: The user
     *     * password: The user's password
     *
     *     (OPTIONAL)
     *     * callback_freq: Callback frequency in seconds (default: 1s)
     *     * runfor: Run the jabber bot for how many seconds? (default: -1)
     *
     *     * debug: Enable debugging? (default: false)
     * 
     * @param array $params The setup parameters
     * @return void
     */
    public function __construct($params){
        # Setup instance variables
        $this->jab_server = $params['server'];
        $this->jab_username = $params['username'];
        $this->jab_password = $params['password']; 

        # How often should the callback happen (in seconds)?
        $this->cbk_freq = (isset($params['callback_freq'])) ?
            $params['callback_freq'] : 1;

        # How long should we run (in seconds)?
        $this->runfor = (isset($params['runfor'])) ?
            $params['runfor'] : -1;

        # Enable debugging?
        $this->debug = (isset($params['debug'])) ?
            $params['debug'] : FALSE;

        # Setup Jabber
        $this->jab = new Jabber($this->debug);

        # Setup callback functions
        $this->setup_handlers();

        # Connect to the Jabber server
        if(!$this->jab->connect($this->jab_server)) {
            die("Could not connect to the Jabber server!\n");
        }

        # And... GO!
        $this->jab->execute($this->cbk_freq, $this->runfor);

        # And... STOP!
        $jab->disconnect();
    }

    /**
     * Automatically handle the connection by logging in
     * @return void
     */
    protected function handle_connected(){
		$this->jab->login($this->jab_username, $this->jab_password);
    }

    /**
     * Automatically handle authentication by setting a status and becoming visible
     * @return void
     */
    protected function handle_authenticated(){
        $this->jab->set_presence("", "At your Service");
    }

    /**
     * Debug Handler -- Used if the constructor is passed a 'debug'=>TRUE in the params
     * @param string $msg 
     * @param int $level 
     * @return void
     */
    protected function handle_debug_log($msg, $level = 1){
       echo 'DEBUG(' . $level .'): ' . $msg . "\n"; 
    }

    /**
     * Automatically setup the handlers. 
     * Handlers are discovered by checking if a method exists of the form:
     *     handle_<handler name>
     *
     * For example, JabberSimple uses handle_connected to handle to the 'connected' handler
     * @return void
     */
    protected function setup_handlers(){
        /**
         * These are all of the handlers defined as of Jabber version 0.9rc2
         */
        $handlers = array(
            'authenticated', 'authfailure', 'deregistered', 'deregfailure',
            'error', 'heartbeat', 'message_chat', 'message_groupchat', 
            'message_headline', 'message_normal', 'msgevent_composing_start',
            'msgevent_composing_stop', 'msgevent_delivered', 
            'msgevent_delivered', 'msgevent_displayed', 'msgevent_offline',
            'passwordchanged', 'passwordfailure', 'regfailure', 'registered',
            'rosteradded', 'rosteraddfailure', 'rosterremoved', 
            'rosterremovefailure', 'rosterupdate', 'servicefieldsfailure',
            'serviceregfailure', 'serviceregistered', 'serviceregfailure',
            'serviceupdate', 'terminated', 'connected', 'disconnected', 
            'probe', 'stream_error', 'subscribe', 'subscribed', 'unsubscribe',
            'unsubscribed', 'privatedata', 'debug_log', 'contactupdated',
            'contactupdatefailure',
        );

        if(count($handlers) < 1){ return; }

        foreach($handlers as $handler){
            # If the method handle_<handler name> exists, setup the handler
            if(method_exists($this, 'handle_' . $handler)){
                $this->jab->set_handler($handler, $this, 'handle_' . $handler);
            }
        }
    }
}
?>
