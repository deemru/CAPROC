<?php
namespace deemru;

class Certificate
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Получить список сертификатов
     */
    public function getCertificates( array $params = [] ): array
    {
        $url = '/api/ra/certificates';
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
     * Получить сертификат по идентификатору
     */
    public function getCertificate( string $id ): array
    {
        $url = "/api/ra/certificates/{$id}";
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Получить сертификат по серийному номеру
     */
    public function getCertificateBySerialNumber( string $serialNumber ): array
    {
        $url = "/api/ra/certificates/serialNumber/{$serialNumber}";
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Поиск сертификата по атрибуту имени субъекта
     */
    public function searchCertificates( string $value, string $key = '', string $folder = '', string $searchType = 'Equals' ): array
    {
        $url = '/api/ra/certificates/search';
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
} 