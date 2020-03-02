<?php

namespace MyApp\Library;

use Phalcon\Http\Response;

class RPCResponse extends Response
{
    /**
     * Request id
     * @var string|int|null
     */
    public $id;

    /**
     * Request version
     * @var string
     */
    public $version = '2.0';

    /**
     * Method execution result
     * @var string
     */
    public $result;

    /**
     * Error occured while executing
     * JsonRPC request
     * @var RPCException32600|RPCException32601|RPCException32700
     */
    public $error;

    /**
     * Returns string representation
     * @return string
     */
    public function getContent(): string
    {
        $response = [
            'id' => $this->id,
            'jsonrpc' => $this->version,
        ];

        // Use the current content
        $result = parent::getContent();

        if (isset($this->error)) {
            $response['error'] = [
                'code' => $this->error->getCode(),
                'message' => $this->error->getMessage(),
            ];
        } else {
            $response['result'] = $result;
        }

        return json_encode($response);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return parent::hasHeader($name);
    }
}