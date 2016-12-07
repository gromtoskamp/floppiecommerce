<?php

namespace Router\Model;

use Object\Model\Object;

class Request extends Object
{

    /**
     * Supported Request Methods.
     *
     * @var array
     */
    private $methods = array(
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->parseRequestParams();
    }

    /**
     * Parse the request parameters to the data array.
     *
     * @throws \Exception
     */
    public function parseRequestParams()
    {
        /**
         * If the Request method is not in the list of supported Request methods,
         * throw an Exception.
         */
        if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods)) {
            throw new \Exception(
                sprintf(\Router\Declarations::ERROR_REQUEST_METHOD_NOT_FOUND, $_SERVER['REQUEST_METHOD']),
                \Router\Declarations::ERROR_REQUEST_METHOD_NOT_FOUND_CODE
            );
        }

        /**
         * Get the request parameters.
         * Map the parameters to the data array.
         */
        $params = $this->getRequestParams();
        foreach ($params as $index => $value) {
            $this->set($index, $value);
        }
    }

    /**
     * Depending on the request method, get the parameters in different ways.
     *
     * @return mixed
     */
    public function getRequestParams()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $params = $_GET;
                break;
            case 'POST':
                $params = $_POST;
                break;
            default:
                parse_str(file_get_contents("php://input"),$post_vars);
                $params =  $post_vars;
                break;
        }

        return $params;
    }
}