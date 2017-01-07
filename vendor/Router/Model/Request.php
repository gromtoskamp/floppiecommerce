<?php

namespace Router\Model;

use Object\Model\Object;

/**
 * Class Request
 *
 * TODO: split this up with different request types.
 *
 * @package Router\Model
 */
class Request extends Object
{

    /**
     * 200
     * Request Method not found.
     */
    const ERROR_REQUEST_METHOD_NOT_FOUND_CODE = '200';
    const ERROR_REQUEST_METHOD_NOT_FOUND = 'Request method %s is not supported at this moment.';

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

    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const CONTENT_TYPE_APP_XML = 'application/xml';
    const CONTENT_TYPE_TEXT_XML = 'text/xml';
    const CONTENT_TYPE_JSON = 'application/json';

    /**
     * @builder
     *
     * @return mixed
     */
    public function requestUri()
    {
        return isset($_SERVER['REQUEST_URI']) ?
            $_SERVER['REQUEST_URI'] :
            null;
    }

    /**
     * @builder
     *
     * @return mixed
     */
    public function requestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ?
            $_SERVER['REQUEST_METHOD'] :
            null;
    }

    /**
     * @builder
     *
     * @return mixed
     */
    public function httpContentType()
    {
        return isset($_SERVER['HTTP_CONTENT_TYPE']) ?
            $_SERVER['HTTP_CONTENT_TYPE'] :
            null;
    }

    /**
     * @builder
     *
     * @return mixed
     */
    public function httpCacheControl()
    {
        return isset($_SERVER['HTTP_CACHE_CONTROL']) ?
            $_SERVER['HTTP_CACHE_CONTROL'] :
            null;
    }

    /**
     * @builder wrapper
     *
     * @return mixed
     */
    public function requestHeaders()
    {
        return $this->setRequestUri()
                    ->setRequestMethod()
                    ->setHttpContentType()
                    ->setHttpCacheControl();
    }

    /**
     * @builder
     *
     * @return Request\Params
     */
    public function requestParams()
    {
        return new Request\Params(['test' => true]);
    }

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->parseRequest();
    }

    /**
     * Parse the request parameters to the data array.
     *
     * @throws \Exception
     */
    public function parseRequest()
    {
        /**
         * If the Request method is not in the list of supported request methods,
         * throw an Exception.
         */
        if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods)) {
            throw new \Exception(
                sprintf(self::ERROR_REQUEST_METHOD_NOT_FOUND, $_SERVER['REQUEST_METHOD']),
                self::ERROR_REQUEST_METHOD_NOT_FOUND_CODE
            );
        }

        $this->requestHeaders();

        /**
         * Get the request parameters.
         * Map the parameters to the data array.
         */
        $this->setRequestParams();

        $params = $this->getRequestParams();

        foreach ($params as $index => $value) {
            $this->add('requestParams', array($index => $value));
        }

        return $this;
    }

    /**
     * Sets the request headers
     */
    public function setRequestHeaders()
    {
        return $this->setRequestUri()
                    ->setRequestMethod()
                    ->setHttpContentType()
                    ->setHttpCacheControl();
    }


    /**
     * Read the POST XML input and parse it as SimpleXML.
     *
     * @return \SimpleXMLElement
     */
    public function parseXmlRequest()
    {
        return simplexml_load_string(
            trim(
                file_get_contents('php://input')
            )
        );

    }

    /**
     * Read the POST JSON input and parse it as JSON object.
     *
     * @return \stdClass
     */
    public function parseJsonRequest()
    {
        return json_decode(
            trim(
                file_get_contents('php://input')
            )
        );
    }

    /**
     * Reads GET params and returns as array.
     * @return mixed
     */
    public function parseGetRequest()
    {
        return $_GET;
    }



    /**
     * Reads POST or DELETE params from input and returns as array.
     * @return mixed
     */
    public function parsePutDeleteRequest()
    {
        parse_str(file_get_contents("php://input"),$post_vars);
        return $post_vars;
    }

    /**
     * Depending on the request method, get the parameters in different ways.
     *
     * @return mixed
     */
    public function getRequestParams()
    {
        /**
         * Deal with GET,
         */
        if ($this->getRequestMethod() == self::REQUEST_METHOD_GET) {
            return $this->parseGetRequest();
        }

        /**
         * or with POST,
         */
        if ($this->getRequestMethod() == self::REQUEST_METHOD_POST) {
            return $this->parsePostRequest();
        }

        /**
         * And even with PUT and DELETE.
         */
        return $this->parsePutDeleteRequest();
    }

    /**
     * Determines if the request is an XML request.
     *
     * @return bool
     */
    public function isXmlRequest()
    {
        if ($this->getHttpContentType() == self::CONTENT_TYPE_APP_XML) {
            return true;
        }

        if ($this->getHttpContentType() == self::CONTENT_TYPE_TEXT_XML) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the request is a JSON request.
     *
     * @return bool
     */
    public function isJsonRequest()
    {
        if ($this->getHttpContentType() == self::CONTENT_TYPE_JSON) {
            return true;
        }

        return false;
    }

    /**
     * Handle Post Request
     *
     * @return mixed|\SimpleXMLElement|\stdClass
     */
    public function parsePostRequest()
    {
        if ($this->isXmlRequest()) {
            return $this->parseXmlRequest();
        }

        if ($this->isJsonRequest()) {
            return $this->parseJsonRequest();
        }

        return $this->parsePostRequest2();
    }

    /**
     * Reads POST param and returns as array.
     * @return mixed
     */
    public function parsePostRequest2()
    {
        return $_POST;
    }
}