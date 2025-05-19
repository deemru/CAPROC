<?php
require_once __DIR__ . '/../vendor/autoload.php';

use deemru\Client;

function fetchAllPages( callable $fetchFunc, $params = [] )
{
    $allItems = [];
    do
    {
        $result = $fetchFunc( $params );
        if( isset( $result['items'] ) )
            $allItems = array_merge( $allItems, $result['items'] );
        $nextHref = $result['_links']['next']['href'] ?? null;
        if( $nextHref )
        {
            $urlParts = parse_url( $nextHref );
            parse_str( $urlParts['query'], $queryParams );
            $params['pageToken'] = $queryParams['pageToken'] ?? null;
        }
        else
            $params['pageToken'] = null;
    }
    while( $params['pageToken'] );
    return $allItems;
}

$baseUrl = getenv( 'CAPROC_BASE_URL' );
if( !$baseUrl )
    $baseUrl = 'http://127.0.0.1:8080';

$client = new Client( $baseUrl );

echo "==== СЕССИЯ ====" . PHP_EOL;
try
{
    print_r( $client->session->getSession() );
}
catch( Exception $e )
{
    echo 'Session error: ' . $e->getMessage() . "\n";
}

echo "\n==== ПАПКИ ====" . PHP_EOL;
try
{
    print_r( $client->folder->getFolders() );
}
catch( Exception $e )
{
    echo 'Folder error: ' . $e->getMessage() . "\n";
}

echo "\n==== ВСЕ ПОЛЬЗОВАТЕЛИ (с пагинацией) ====" . PHP_EOL;
try
{
    $allUsers = fetchAllPages( [ $client->user, 'getUsers' ] );
    print_r( $allUsers );
    print_r( $client->user->getNamePolicy() );
}
catch( Exception $e )
{
    echo 'User error: ' . $e->getMessage() . "\n";
}

echo "\n==== ВСЕ СЕРТИФИКАТЫ (с пагинацией) ====" . PHP_EOL;
try
{
    $allCerts = fetchAllPages( [ $client->certificate, 'getCertificates' ] );
    print_r( $allCerts );
}
catch( Exception $e )
{
    echo 'Certificate error: ' . $e->getMessage() . "\n";
}

echo "\n==== ВСЕ ЗАПРОСЫ НА СЕРТИФИКАТ (с пагинацией) ====" . PHP_EOL;
try
{
    $allCertReqs = fetchAllPages( [ $client->certRequest, 'getCertRequests' ] );
    print_r( $allCertReqs );
}
catch( Exception $e )
{
    echo 'CertRequest error: ' . $e->getMessage() . "\n";
}

echo "\n==== ВСЕ ЗАПРОСЫ НА ОТЗЫВ (с пагинацией) ====" . PHP_EOL;
try
{
    $allRevReqs = fetchAllPages( [ $client->revocation, 'getRevocationRequests' ] );
    print_r( $allRevReqs );
}
catch( Exception $e )
{
    echo 'Revocation error: ' . $e->getMessage() . "\n";
} 