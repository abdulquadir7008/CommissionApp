<?php
/**
 * GenerateApi
 * PHP version 7.4
 *
 * @category Class
 * @package  HubSpot\Client\Conversations\VisitorIdentification
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Visitor Identification
 *
 * The Visitor Identification API allows you to pass identification information to the HubSpot chat widget for otherwise unknown visitors that were verified by your own authentication system.
 *
 * The version of the OpenAPI document: v3
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace HubSpot\Client\Conversations\VisitorIdentification\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use HubSpot\Client\Conversations\VisitorIdentification\ApiException;
use HubSpot\Client\Conversations\VisitorIdentification\Configuration;
use HubSpot\Client\Conversations\VisitorIdentification\HeaderSelector;
use HubSpot\Client\Conversations\VisitorIdentification\ObjectSerializer;

/**
 * GenerateApi Class Doc Comment
 *
 * @category Class
 * @package  HubSpot\Client\Conversations\VisitorIdentification
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class GenerateApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null,
        $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation generateToken
     *
     * Generate a token
     *
     * @param  \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenGenerationRequest $identification_token_generation_request identification_token_generation_request (required)
     *
     * @throws \HubSpot\Client\Conversations\VisitorIdentification\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse|\HubSpot\Client\Conversations\VisitorIdentification\Model\Error
     */
    public function generateToken($identification_token_generation_request)
    {
        list($response) = $this->generateTokenWithHttpInfo($identification_token_generation_request);
        return $response;
    }

    /**
     * Operation generateTokenWithHttpInfo
     *
     * Generate a token
     *
     * @param  \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenGenerationRequest $identification_token_generation_request (required)
     *
     * @throws \HubSpot\Client\Conversations\VisitorIdentification\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse|\HubSpot\Client\Conversations\VisitorIdentification\Model\Error, HTTP status code, HTTP response headers (array of strings)
     */
    public function generateTokenWithHttpInfo($identification_token_generation_request)
    {
        $request = $this->generateTokenRequest($identification_token_generation_request);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                default:
                    if ('\HubSpot\Client\Conversations\VisitorIdentification\Model\Error' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\HubSpot\Client\Conversations\VisitorIdentification\Model\Error' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\HubSpot\Client\Conversations\VisitorIdentification\Model\Error', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\HubSpot\Client\Conversations\VisitorIdentification\Model\Error',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation generateTokenAsync
     *
     * Generate a token
     *
     * @param  \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenGenerationRequest $identification_token_generation_request (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function generateTokenAsync($identification_token_generation_request)
    {
        return $this->generateTokenAsyncWithHttpInfo($identification_token_generation_request)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation generateTokenAsyncWithHttpInfo
     *
     * Generate a token
     *
     * @param  \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenGenerationRequest $identification_token_generation_request (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function generateTokenAsyncWithHttpInfo($identification_token_generation_request)
    {
        $returnType = '\HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenResponse';
        $request = $this->generateTokenRequest($identification_token_generation_request);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'generateToken'
     *
     * @param  \HubSpot\Client\Conversations\VisitorIdentification\Model\IdentificationTokenGenerationRequest $identification_token_generation_request (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function generateTokenRequest($identification_token_generation_request)
    {
        // verify the required parameter 'identification_token_generation_request' is set
        if ($identification_token_generation_request === null || (is_array($identification_token_generation_request) && count($identification_token_generation_request) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $identification_token_generation_request when calling generateToken'
            );
        }

        $resourcePath = '/conversations/v3/visitor-identification/tokens/create';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;





        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json', '*/*']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json', '*/*'],
                ['application/json']
            );
        }

        // for model (json/xml)
        if (isset($identification_token_generation_request)) {
            if ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode(ObjectSerializer::sanitizeForSerialization($identification_token_generation_request));
            } else {
                $httpBody = $identification_token_generation_request;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}
