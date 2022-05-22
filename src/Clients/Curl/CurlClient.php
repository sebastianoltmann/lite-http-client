<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use Http\Message\ResponseFactory;
use LiteHttpClient\Clients\ClientInterface;
use LiteHttpClient\Exceptions\ErrorCallbackException;
use LiteHttpClient\Exceptions\ErrorNetworkException;
use LiteHttpClient\Exceptions\ErrorRequestException;
use LiteHttpClient\Exceptions\InvalidClientException;
use LiteHttpClient\Exceptions\InvalidRequestTypeException;
use LiteHttpClient\Responses\ResponseBuilder;
use LiteHttpClient\Responses\ResponseBuilderInterface;
use Psr\Http\Message\RequestInterface as PSRRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class CurlClient implements ClientInterface
{
    private $curl;

    /**
     * @var ResponseFactoryInterface|ResponseFactory
     */
    protected $responseFactory;

    /**
     * @param ResponseFactoryInterface|ResponseFactory $responseFactory
     * @throws InvalidClientException
     */
    public function __construct($responseFactory)
    {
        if (!extension_loaded('curl')) {
            throw new InvalidClientException('The cURL extensions is not loaded.');
        }

        $this->responseFactory = $responseFactory;
        $this->initResource();
    }

    private function initResource()
    {
        $this->curl = curl_init();

        if (defined('CURLOPT_PROTOCOLS')) {
            $this->setOption(CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
            $this->setOption(CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        }

        $this->setOption(CURLINFO_HEADER_OUT, true);
        $this->setOption(CURLOPT_HEADER, false);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
    }

    private function closeResource(): void
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    private function resetResource(): void
    {
        $this->closeResource();
        $this->initResource();
    }

    public function __destruct()
    {
        $this->closeResource();
    }

    public function setOption($option, $value): bool
    {
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * @throws ErrorCallbackException
     * @throws ErrorNetworkException
     * @throws ErrorRequestException
     * @throws InvalidRequestTypeException
     */
    public function sendRequest(PSRRequestInterface $request): ResponseInterface
    {
        $optionsBuilderFactory = new CurlRequestOptionsBuilderFactory();
        $optionsBuilder = $optionsBuilderFactory->build($request);
        
        $self = $optionsBuilder->prepare($this, $request);

        $errorHandler = new CurlErrorHandler();
        $responseBuilder = new ResponseBuilder($self->responseFactory);

        $self->handleResourceResponse($responseBuilder);

        try {
            curl_exec($self->curl);

            $errorHandler->handle($request, curl_errno($self->curl));
        } finally {
            $self->resetResource();
        }

        return $responseBuilder->getResponse();
    }

    protected function handleResourceResponse(ResponseBuilderInterface $responseBuilder): void
    {
        $this->setOption(
            CURLOPT_HEADERFUNCTION,
            function ($ch, $data) use ($responseBuilder) {
                $str = trim($data);
                if ('' !== $str) {
                    if (0 === strpos(strtolower($str), 'http/')) {
                        $responseBuilder->setStatus($str);
                    } else {
                        $responseBuilder->addHeader($str);
                    }
                }

                return strlen($data);
            }
        );

        $this->setOption(CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($responseBuilder) {
            return $responseBuilder->writeBody($data);
        });
    }
}
