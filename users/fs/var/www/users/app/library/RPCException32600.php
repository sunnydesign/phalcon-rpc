<?php

namespace MyApp\Library;

use \Exception;

class RPCException32600 extends Exception
{
    public $message = 'Invalid Request';
    public $code = -32600;
}