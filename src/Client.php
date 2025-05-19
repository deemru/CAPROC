<?php
namespace deemru;

require_once __DIR__ . '/../vendor/autoload.php';
use deemru\Fetcher;

class Client
{
    public $fetcher;
    public $certRequest;
    public $certificate;
    public $folder;
    public $revocation;
    public $session;
    public $user;

    public function __construct( string $baseUrl )
    {
        $this->fetcher = Fetcher::host( $baseUrl );
        $this->certRequest = new CertRequest( $this );
        $this->certificate = new Certificate( $this );
        $this->folder = new Folder( $this );
        $this->revocation = new Revocation( $this );
        $this->session = new Session( $this );
        $this->user = new User( $this );
    }
} 