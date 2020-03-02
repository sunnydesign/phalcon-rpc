<?php

namespace MyApp\Library;

use \Exception;

class RPCException32700 extends Exception
{
    public $message = 'Parse error';
    public $code = -32700;
}