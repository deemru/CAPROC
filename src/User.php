<?php
namespace deemru;

class User
{
    private $client;

    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Получить политику имен пользователей
     */
    public function getNamePolicy(): array
    {
        $url = '/api/ra/namePolicy';
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Создать пользователя
     */
    public function createUser( array $nameAttributes, string $folder ): string
    {
        $url = '/api/ra/users';
        $body = [
            'nameAttributes' => $nameAttributes,
            'folder' => $folder
        ];
        $result = $this->client->fetcher->fetch( $url, json_encode( $body ), 'POST', [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_string( $data ) && !is_string( $result ) )
            throw new \Exception( 'Invalid response: ' . $result );
        return is_string( $data ) ? $data : $result;
    }

    /**
     * Получить список пользователей
     */
    public function getUsers( array $params = [] ): array
    {
        $url = '/api/ra/users';
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
     * Изменить данные пользователя
     */
    public function updateUser( string $id, array $nameAttributes ): bool
    {
        $url = "/api/ra/users/{$id}";
        $body = [
            'nameAttributes' => $nameAttributes
        ];
        $result = $this->client->fetcher->fetch( $url, json_encode( $body ), 'POST', [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }

    /**
     * Получить данные пользователя
     */
    public function getUser( string $id ): array
    {
        $url = "/api/ra/users/{$id}";
        $result = $this->client->fetcher->fetch( $url );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        $data = json_decode( $result, true );
        if( !is_array( $data ) )
            throw new \Exception( 'Invalid JSON response: ' . $result );
        return $data;
    }

    /**
     * Поиск пользователя по атрибуту имени
     */
    public function searchUsers( string $value, string $key = '', string $folder = '', string $searchType = 'Equals' ): array
    {
        $url = '/api/ra/users/search';
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
     * Переместить пользователя в другую папку
     */
    public function moveUser( string $id, string $folder ): bool
    {
        $url = "/api/ra/users/{$id}/move";
        $body = [
            'folder' => $folder
        ];
        $result = $this->client->fetcher->fetch( $url, json_encode( $body ), 'POST', [ 'Content-Type: application/json' ] );
        if( $result === false )
            throw new \Exception( 'Fetcher error: ' . $this->client->fetcher->getLastError() );
        return true;
    }
} 