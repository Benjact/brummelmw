<?php
namespace BrummelMW\response;

use BrummelMW\core\Bot;
use ErrorException;

class Response
{
    /**
     * @var Bot
     */
    private $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function devolverMensaje(string $mensaje)
    {
        $url = $this->bot->webSite()."/sendMessage?chat_id={$this->bot->chatId()}&parse_mode=HTML&text=".urlencode($mensaje);

        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);

        curl_close($ch);
    }

    /**
     * Error handler, passes flow over the exception logger with new ErrorException.
     *
     * @param $num
     * @param $str
     * @param $file
     * @param $line
     * @param null $context
     */
    public static function log_error( $num, $str, $file, $line, $context = null )
    {
        self::log_exception( new ErrorException( $str, 0, $num, $file, $line ) );
    }

    /**
     * Uncaught exception handler.
     * @param \Exception $e
     */
    public static function log_exception( $e )
    {
        // developer notification message text
        /*$message  = get_class( $e ) . " - <b>{$e->getMessage()}</b>;".PHP_EOL."File: <b>{$e->getFile()}</b>; Line: <b>{$e->getLine()}</b>; Time: <b>".date("H:i:s / d.m.Y")."</b>;".PHP_EOL."<b>Trace:</b><pre>".$e->getTraceAsString()."</pre>");

        // developer notification message settings
        $fields_string = '';
        $url = $this->bot->webSite().'/sendMessage';

        $fields = [
            'chat_id' => urlencode($DEV_ID),
            'parse_mode' => urlencode('HTML'),
            'text' => urlencode(''.$message)
        ];

        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);*/
    }

    /**
     * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
     */
    public static function check_for_fatal()
    {
        $error = error_get_last();
        if ( $error["type"] == E_ERROR )
            self::log_error( $error["type"], $error["message"], $error["file"], $error["line"] );
    }
}