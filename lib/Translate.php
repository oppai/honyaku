<?php
define( 'CLIENT_ID', 'speaking_translation' );
define( 'CLIENT_SECRET', 'nrhsB6onnhVOGll4/7LYDSvW4jFIXzI6iBgiGEqSFwA=' );
define( 'GRANT_TYPE', 'client_credentials' );
define( 'SCOPE_URL', 'http://api.microsofttranslator.com' );
define( 'AUTH_URL', 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/' );

class Translate
{
    private $auth_header = '';

    public function __construct()
    {
        $access_token = $this->getAccessTokens( GRANT_TYPE, SCOPE_URL, CLIENT_ID, CLIENT_SECRET, AUTH_URL );
        $this->auth_header = 'Authorization: Bearer '.$access_token;
    }

    protected function getAccessTokens( $grant_type, $scope, $client_id, $client_secret, $auth_url )
    {
        $params = http_build_query( compact( 'grant_type', 'scope', 'client_id', 'client_secret' ) );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $auth_url );
        curl_setopt( $ch, CURLOPT_POST, TRUE );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

        $response = curl_exec( $ch );
        if( curl_errno( $ch ) )
        {
            throw new Exception( curl_error( $ch ) );
        }
        curl_close( $ch );

        $json = json_decode( $response );
        if( isset( $json->error ) )
        {
            throw new Exception( $json->error_description );
        }
        return $json->access_token;
    }

    protected function request( $url, $authHeader )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $authHeader, 'Content-Type: text/xml' ) );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

        $response = curl_exec( $ch );
        if( curl_errno( $ch ) )
        {
            throw new Exception( curl_error( $ch ) );
        }
        curl_close( $ch );

        return $response;
    }

    public function getRequest($text,$from,$to)
    {
        $url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?'
            .http_build_query( compact( 'text', 'from', 'to' ) );
        return $this->request( $url, $this->auth_header );
    }
}

