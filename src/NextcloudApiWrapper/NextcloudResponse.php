<?php

namespace NextcloudApiWrapper;

use GuzzleHttp\Psr7\Response;

class NextcloudResponse
{
    /**
     * @var Response
     */
    protected Response $guzzle;

    /**
     * @var \SimpleXMLElement
     */
    protected \SimpleXMLElement $body;

    /**
     * @throws NCException
     */
    public function __construct(Response $guzzle)
    {
        $this->guzzle   = $guzzle;

        try {
            $this->body = new \SimpleXMLElement($guzzle->getBody()->getContents());
        } catch (\Exception $e) {
            throw new NCException($guzzle, "Failed parsing response");
        }
    }

    /**
     * Returns nextcloud status message
     * @return string
     */
    public function getStatus(): string
    {
        return (string)$this->body->meta->status;
    }

    /**
     * Returns nextcloud message
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return (isset($this->body->meta->message) ? (string)$this->body->meta->message : null);
    }

    /**
     * Returns nextcloud status code
     * @return int
     */
    public function getStatusCode(): int
    {
        return intval($this->body->meta->statuscode);
    }

    /**
     * Returns nextcloud response data if any
     * @return array|null
     */
    public function getData(): ?array
    {
        $data   = $this->body->data;
        return empty((string)$data) ? null : $this->xml2array($data);
    }

    /**
     * Returns the raw guzzle response
     * @return Response
     */
    public function getRawResponse(): Response
    {
        return $this->guzzle;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $out
     * @return array
     */
    protected function xml2array(\SimpleXMLElement $xml, array $out = []): array
    {
        foreach( (array)$xml as $index => $node)
            $out[$index] = $node instanceof \SimpleXMLElement ? $this->xml2array($node) : $node;

        return $out;
    }
}