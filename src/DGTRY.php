<?php
namespace deemru;

interface DGTRYFFI
{
    public function dgtry_certs_from_store( $cert, $certlen, $certs_handle_out ): int;
    public function dgtry_certs_message_sign( $certs_handle, int $include_cert, $msg, $msglen, $msgsig_out, $msgsiglen_out ): int;
    public function dgtry_certs_close( $certs_handle ): int;
}

final class DGTRY
{
    /** @var (\FFI&\deemru\DGTRYFFI) */
    private $ffi;

    private function throwErrorCode( string $operation, int $errorCode )
    {
        throw new \RuntimeException( '"' . $operation . '" failed: ' . sprintf( '0x%08X', $errorCode & 0xFFFFFFFF ) );
    }

    private function throwMessage( string $operation, string $message )
    {
        throw new \RuntimeException( '"' . $operation . '" failed: ' . $message );
    }

    public function __construct( string $dllPath = 'dgtry.dll' )
    {
        if( !extension_loaded( 'ffi' ) )
            $this->throwMessage( '__construct', 'PHP FFI extension is not loaded' );

        $cdef = <<<"CDEF"
typedef unsigned char uint8_t;
typedef int int32_t;
typedef long long int64_t;

typedef void * DGTRY_CERTS_HANDLE;

int32_t dgtry_certs_from_store( const uint8_t * cert, size_t certlen, DGTRY_CERTS_HANDLE * certs_handle );
int32_t dgtry_certs_message_sign( DGTRY_CERTS_HANDLE certs_handle, int32_t include_cert, const uint8_t * msg, size_t msglen, const uint8_t ** msgsig, size_t * msgsiglen );
int32_t dgtry_certs_close( DGTRY_CERTS_HANDLE certs_handle );
CDEF;

        try
        {
            $this->ffi = \FFI::cdef( $cdef, $dllPath );
        }
        catch( \Throwable $e )
        {
            $this->throwMessage( 'FFI::cdef', $e->getMessage() );
        }
    }

    public function certsFromStore( string $certificateDer )
    {
        $certLen = strlen( $certificateDer );
        $certBuf = \FFI::new( "uint8_t[{$certLen}]" );
        for( $i = 0; $i < $certLen; ++$i )
            $certBuf[$i] = ord( $certificateDer[$i] );

        $handleOut = \FFI::new( 'void *[1]' );
        $rc = $this->ffi->dgtry_certs_from_store( $certBuf, $certLen, $handleOut );
        if( $rc !== 0 )
            $this->throwErrorCode( 'dgtry_certs_from_store', $rc );

        return $handleOut[0];
    }

    public function certsMessageSign( $certsHandle, bool $includeCertificate, string $message ): string
    {
        $msgLen = strlen( $message );
        $msgBuf = \FFI::new( "uint8_t[{$msgLen}]" );
        for( $i = 0; $i < $msgLen; ++$i )
            $msgBuf[$i] = ord( $message[$i] );

        $sigPtrOut = \FFI::new( 'uint8_t *[1]' );
        $sigLenOut = \FFI::new( 'size_t[1]' );

        $rc = $this->ffi->dgtry_certs_message_sign( $certsHandle, $includeCertificate ? 1 : 0, $msgBuf, $msgLen, $sigPtrOut, $sigLenOut );
        if( $rc !== 0 )
            $this->throwErrorCode( 'dgtry_certs_message_sign', $rc );

        $sigPtr = $sigPtrOut[0];
        $sigLen = (int)$sigLenOut[0];
        if( $sigPtr === null || $sigLen <= 0 )
            $this->throwMessage( 'dgtry_certs_message_sign', 'empty signature' );

        return \FFI::string( $sigPtr, $sigLen );
    }

    public function certsClose( $certsHandle ): void
    {
        $rc = $this->ffi->dgtry_certs_close( $certsHandle );
        if( $rc !== 0 )
            $this->throwErrorCode( 'dgtry_certs_close', $rc );
    }
}
