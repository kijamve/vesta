<?php

/**
 * Ajax Handler
 * 
 * @author vesta, http://vestacp.com/
 * @copyright vesta 2010
 */
class AjaxHandler {

    static public $instance = null;

    public $errors = array();
    public $status = TRUE;

    /**
     * Grab current instance or create it
     *
     * @return <type>
     */
    static function getInstance($request=null) {
        return null == self::$instance ? self::$instance = new self() : self::$instance;
    }

    /**
     * Called functions should reply in the following way
     * return $this->reply($result, $data, $msg, $extra);
     * 
     * @param type $request
     * @return type 
     */
    function dispatch($request) {
        $method = Request::parseAjaxMethod($request);
        $inc_file = V_ROOT_DIR . 'api' . DIRECTORY_SEPARATOR . $method['namespace'] . '.class.php';
        if (!is_readable($inc_file)) {
            throw new SystemException(Message::INVALID_METHOD);
        }

        require $inc_file;

        $space = new $method['namespace'];
        $method = $method['function'] . 'Execute';

        if (!method_exists($space, $method)) {
            throw new SystemException(Message::INVALID_METHOD);
        }

        return $space->$method($request);
    }

    function reply($result, $data, $message = '', $extra = array()) {
      return json_encode(array('result' => $result,
			       'data' => $data,
			       'message' => $message,
			       'extra' => $extra,
			       'errors' => $this->errors
			       ));
    }

    static function makeReply($reply) {
        print $reply;
    }

    //
    // Error handlers
    //
    
    static function generalError($error) {
        self::renderGlobalError(Message::ERROR, Message::GENERAL_ERROR, $error);
    }

    static function protectionError($error) {
        self::renderGlobalError(Message::ERROR, Message::PROTECTION_ERROR, $error);
    }

    static function systemError($error) {
        self::renderGlobalError(Message::ERROR, Message::SYSTEM_ERROR, $error);
    }

    static function renderGlobalError($type, $message, $error) {
        $trace = $error->getTrace();
        AjaxHandler::makeReply(
                        AjaxHandler::getInstance()->reply(false, $type, $message . ': ' . $error->getMessage(), $trace[0]['file'] . ' / ' . $trace[0]['line'])
        );
    }

}
