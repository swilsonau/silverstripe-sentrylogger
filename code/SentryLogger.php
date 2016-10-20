<?php

require_once 'Zend/Log/Writer/Abstract.php';

/**
 * Publish SilverStripe errors and warnings to Sentry using the Raven client.
 */
class SentryLogger extends Zend_Log_Writer_Abstract {

    private $sentry;

    private $logLevels = array(
        'NOTICE'    => Raven_Client::INFO,
        'WARN'      => Raven_Client::WARNING,
        'ERR'       => Raven_Client::ERROR
    );

    /**
     * @config
     */
    private static $sentry_dsn;


    public static function factory($config) {
        return new SentryLogger();
    }

    public function __construct()
    {
        $DSN = Config::inst()->get('SentryLogger', 'sentry_dsn');
        $this->sentry = new Raven_Client($DSN);
        $this->sentry->setEnvironment(Director::get_environment_type());
        
        if(Member::currentUserID()) {
            $this->sentry->user_context(array(
                'email' => Member::curentUser()->Email,
                'id' => Member::currentUserID()
            ));    
        }
    }

    /**
     * Send the error.
     * 
     * @param array $event
     * @return void
    */
    public function _write($event) {
        $data['level'] = $this->logLevels[$event['priorityName']];
        $data['timestamp'] = strtotime($event['timestamp']);
        $backtrace = SS_Backtrace::filter_backtrace(debug_backtrace(), array("SentryLogger->_write"));
        $this->sentry->captureMessage($event['message']['errstr'], array(), $data, $backtrace);
    }

}
