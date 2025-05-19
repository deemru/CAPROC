<?php
namespace deemru;

class Session
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Получить информацию о текущей сессии
     */
    public function getSession(): array
    {
        $url = '/api/ra/session';
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }
} 