<?php
namespace deemru;

class Admin
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Выпустить внеочередной CRL
     */
    public function issueCrl( ?string $authority = null, bool $deltaOnly = false ) : bool
    {
        $url = '/api/ra/admin/crls/issue';
        $body = [
            'deltaOnly' => $deltaOnly
        ];
        if( $authority !== null )
            $body['authority'] = $authority;
        $result = $this->client->fetcher->fetch( $url, true, json_encode( $body ), null, [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }
}
