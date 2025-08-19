<?php
namespace deemru;

class CertRequest
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Отправить запрос на сертификат
     */
    public function createCertRequest( string $userId, string $authorityName, string $rawRequest ) : string
    {
        $url = '/api/ra/certRequests';
        $body = [
            'userId' => $userId,
            'authorityName' => $authorityName,
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
     * Одобрить запрос на сертификат
     */
    public function approveCertRequest( string $id, string $rawRequest ) : bool
    {
        $url = "/api/ra/certRequests/{$id}/approve";
        $body = [
            'rawRequest' => $rawRequest
        ];
        $result = $this->client->fetcher->fetch( $url, true, json_encode( $body ), null, [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }

    /**
     * Получить данные запроса на сертификат
     */
    public function getCertRequest( string $id ) : array
    {
        $url = "/api/ra/certRequests/{$id}";
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Получить список запросов на сертификаты
     */
    public function getCertRequests( array $params = [] ) : array
    {
        $url = '/api/ra/certRequests';
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
     * Поиск запроса на сертификат по атрибуту имени субъекта
     */
    public function searchCertRequests( string $value, string $key = '', string $folder = '', string $searchType = 'Equals' ) : array
    {
        $url = '/api/ra/certRequests/search';
        $params = [
            'value' => $value,
            'searchType' => $searchType
        ];
        if( $key )
            $params['key'] = $key;
        if( $folder )
            $params['folder'] = $folder;
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
     * Отклонить запрос на сертификат
     */
    public function rejectCertRequest( string $id ) : bool
    {
        $url = "/api/ra/certRequests/{$id}/reject";
        $result = $this->client->fetcher->fetch( $url, null, 'POST' );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }
} 