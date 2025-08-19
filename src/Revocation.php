<?php
namespace deemru;

class Revocation
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Отправить запрос на отзыв сертификата
     */
    public function createRevocationRequest( string $rawRequest ): string
    {
        $url = '/api/ra/revRequests';
        $body = [
            'rawRequest' => $rawRequest
        ];
        $result = $this->client->fetcher->fetch( $url, true, json_encode( $body ), null, [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_string( $data ) && !is_string( $result ) )
            throw new \Exception( 'Invalid response: ' . $result );
        return is_string( $data ) ? $data : $result;
    }

    /**
     * Получить список запросов на отзыв
     */
    public function getRevocationRequests( array $params = [] ): array
    {
        $url = '/api/ra/revRequests';
        if( $params )
            $url .= '?' . http_build_query( $params );
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Получить данные запроса на отзыв
     */
    public function getRevocationRequest( string $id ): array
    {
        $url = "/api/ra/revRequests/{$id}";
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Одобрить запрос на отзыв
     */
    public function approveRevocationRequest( string $id, string $rawRequest ): bool
    {
        $url = "/api/ra/revRequests/{$id}/approve";
        $body = [
            'rawRequest' => $rawRequest
        ];
        $result = $this->client->fetcher->fetch( $url, true, json_encode( $body ), null, [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }

    /**
     * Отклонить запрос на отзыв
     */
    public function rejectRevocationRequest( string $id ): bool
    {
        $url = "/api/ra/revRequests/{$id}/reject";
        $result = $this->client->fetcher->fetch( $url, true );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }
} 