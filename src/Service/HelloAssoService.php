<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HelloAssoService 
{
    private ?string $refreshToken;
    private ?string $token;
    private ?int $expireIn;
    private array $headers = [];
    private string $HAclientId;
    private string $HAclientSecret;

    public function __construct(
        private HttpClientInterface $client,
        private Security $security,
    ) {
        $this->HAclientId = $_ENV['HELLO_ASSO_CLIENT_ID'];
        $this->HAclientSecret = $_ENV['HELLO_ASSO_CLIENT_SECRET'];
    }
    
    public string $apiUrl = 'https://api.helloasso-sandbox.com';
    
    public function authorize ()
    {
        $this->oauthTokenquest('client_credentials');
    }

    public function refresh ()
    {
        $this->oauthTokenquest('refresh_token', $this->refreshToken);
    }

    public function verify () 
    {
        if (!isset($this->token)) {
            $this->authorize();
        }
        
        if (isset($this->expireIn) && ($this->expireIn < time())) {
            $this->refresh();
        }
    }

    public function getOrders()
    {
        $this->verify();
        $url = $this->apiUrl . "/v5/organizations/mimblemimbus-test/forms/Event/tm-test/orders";
        $params = [
            'headers' => array_merge($this->headers, [
                'accept' => 'application/json'
            ])
        ];

        $response = $this->client->request('GET', $url, $params);

        return $response->toArray();
    }

    public function getTicket(string $qrcode)
    {   
        $id = explode(':', base64_decode($qrcode))[0];
        $url = $this->apiUrl . "/v5/items/" . $id. "?withDetails=true";
        $params = [
            'headers' => array_merge($this->headers, [
                'accept' => 'application/json'
            ])
        ];

        $response = $this->client->request('GET', $url, $params);

        if (in_array($response->getStatusCode(), [404, 400])) {
            return null;
        }

        $data = $response->toArray();
        $payment = $data['payments'][0];

        if (($payment['state'] == 'Authorized') && ($payment['cashOutState'] == "MoneyIn" && $data['state'] == 'Processed')) {
            $info = [
                'email' => $data['payer']['email'],
                'firstName' => $data['user']['firstName'],
                'lastName' => $data['user']['lastName'],
                'id' => $data['id'],
                'rawQrcode' => $qrcode,
                'type' => $data['type']
            ];

            if ($this->security->isGranted('ROLE_VOLUNTEER')) {
                $info['categorie'] = $data['name'];
            };

            return $info;
        }

        return null;
    }

    private function oauthTokenquest (string $grantType,  ?string $refreshToken = null) 
    {
        $body = [
            'grant_type' => $grantType,
            'client_secret' => $this->HAclientSecret,
            'client_id' => $this->HAclientId,
        ];

        if (isset($refreshToken)) {
            $body['refreshToken'] = $refreshToken;
        }

        $params = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => http_build_query($body)
        ];
        $response = $this->client->request('POST', $this->apiUrl .'/oauth2/token', $params);


        $data = $response->toArray();

        $this->token = $data['access_token'];
        $this->refreshToken = $data['refresh_token'];
        $this->expireIn = $data['expires_in'] + time();
        $this->headers['authorization'] ='Bearer '. $this->token;
    }
}
