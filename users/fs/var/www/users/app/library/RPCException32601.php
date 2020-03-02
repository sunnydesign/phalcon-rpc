<?php

namespace MyApp\Library;

use \Exception;

class RPCException32601 extends Exception
{
    public $message = 'Procedure not found';
    public $code = -32601;
}