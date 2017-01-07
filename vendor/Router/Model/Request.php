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
     * Property stating the request method of the request.
     * @var string
     */
    protected $requestMethod;

    /**
     * Property stating the http content type of the request.
     * @var string
     */
    protected $httpContentType;

    /**
     * Property stating the request uri of the request.
     * @var string
     */
    protected $requestUri;

    /**
     * Property stating the http cache control of the request.
     * @var string
     */
    protected $httpCacheControl;

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

        $this->parseRequestHeaders();

        /**
         * Get the request parameters.
         * Map the parameters to the data array.
         */
        $params = $this->getRequestParams();

        foreach ($params as $index => $value) {
            $this->add('requestParams', array($index => $value));
        }

        return $this;
    }

    public function parseRequestHeaders()
    {
        $this->getRequestUri();
        $this->getRequestMethod();
        $this->getHttpContentType();
        $this->getHttpCacheControl();
    }

    /**
     * Determines if required and returns the requestUri property.
     *
     * @return string
     */
    public function getRequestUri()
    {
        if (!$this->hasRequestUri()) {
            $this->addRequestHeaders(['requestUri' => $_SERVER['REQUEST_URI']]);
        }

        return $this->getRequestUri();
    }

    /**
     * Determines if required and returns the requestMethod property.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        if (!$this->hasRequestMethod()) {
            $this->addRequestHeaders(['requestMethod' => $_SERVER['REQUEST_METHOD']]);
        }

        return $this->getRequestMethod();
    }

    /**
     * Determines if required and returns the httpContentType property.
     *
     * @return string
     */
    public function getHttpContentType()
    {
        if (!$this->hasHttpContentType()) {
            $this->addRequestHeaders(['httpContentType' => $_SERVER['HTTP_CONTENT_TYPE']]);
        }

        return $this->getHttpContentType();
    }

    /**
     * Determines if required and returns the httpCacheControl property.
     *
     * @return string
     */
    public function getHttpCacheControl()
    {
        if (!$this->hasHttpCacheControl()) {
            $this->addRequestHeaders(['httpCacheControl '=> $_SERVER['HTTP_CACHE_CONTROL']]);
        }

        return $this->getHttpCacheControl();
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