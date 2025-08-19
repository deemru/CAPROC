<?php
namespace deemru;

class Template
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Получить список шаблонов сертификата
     */
    public function getTemplates(): array
    {
        $url = '/api/ra/templates';
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }
}
