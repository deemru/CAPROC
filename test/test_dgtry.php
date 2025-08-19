<?php
require_once __DIR__ . '/../vendor/autoload.php';

use deemru\Client;
use deemru\DGTRY;

$baseUrl = getenv( 'CAPROC_BASE_URL' );
if( !$baseUrl )
    $baseUrl = 'http://127.0.0.1:8080';

$client = new Client( $baseUrl );

echo "Searching first NEW cert request...\n";
$list = $client->certRequest->getCertRequests( [ 'status' => 'new' ] );
$items = $list['items'] ?? [];
if( !$items )
{
    echo "No NEW cert requests found.\n";
    exit( 0 );
}

$first = $items[0];
$id = $first['id'] ?? null;
if( !$id )
{
    echo "Invalid list item structure, no id.\n";
    exit( 1 );
}

echo "Loading cert request {$id}...\n";
$details = $client->certRequest->getCertRequest( $id );
$rawB64 = $details['rawRequest'] ?? null;
if( !$rawB64 )
{
    echo "certRequest {$id} has no rawRequest.\n";
    exit( 1 );
}
$raw = base64_decode( $rawB64 );
if( $raw === false )
{
    echo "rawRequest is not valid base64.\n";
    exit( 1 );
}

$approverThumbprint = getenv( 'CAPROC_APPROVER_THUMBPRINT' ) ?: '';
if( extension_loaded( 'ffi' ) && $approverThumbprint )
{
    try
    {
        $dgtry = new DGTRY();
        $certHandle = $dgtry->certsFromStore( $approverThumbprint );
        $approvedRaw = $dgtry->certsMessageSign( $certHandle, true, $raw );
        $dgtry->certsClose( $certHandle );
        echo "Signed rawRequest via DGTRY.\n";
    }
    catch( \Throwable $e )
    {
        echo "DGTRY signing skipped: " . $e->getMessage() . "\n";
    }
}
else
{
    echo "FFI not available or approver cert not configured.\n";
    exit( 1 );
}

echo "Approving cert request {$id}...\n";
$ok = $client->certRequest->approveCertRequest( $id, base64_encode( $approvedRaw ) );
echo $ok ? "Approved.\n" : "Approve returned false.\n";
