<?php
namespace BrummelMW\acciones\origenExcel;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;
use BrummelMW\response\ObjetoResponse;
use Exception;
use Google_Client;
use Google_Service_Sheets;

class Excel extends AccionBasica implements iAcciones
{
    const ID = "";

    /**
     * @param string $id_chat
     * @return ObjetoResponse
     * @throws Exception
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        // Get the API client and construct the service object.
        $client = $this->getClient();
        $service = new Google_Service_Sheets($client);

        // Prints the names and majors of students in a sample spreadsheet:
        $response = $service->spreadsheets_values->get(self::ID, $this->rango());
        $values = $response->getValues();

        $valores_comprobados = $this->comprobarValores($values);

        return new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "chat_id" => $id_chat,
            "parse_mode" => PARSE_MODE,
            "text" => $this->maquetarValores($valores_comprobados),
        ]);
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     * @throws \Google_Exception
     */
    protected function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(__DIR__ . '/credentials.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = 'token.json';
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            $accessToken = $this->createCredentialsPath($client, $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    /**
     * @param $client
     * @param $credentialsPath
     * @return mixed
     * @throws Exception
     */
    protected function createCredentialsPath($client, $credentialsPath)
    {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        //$authCode = "4/TwCN36C_p35MEBoR1RQlYcNk0Le26ft--61lyGwWuxEEE1czRbaWOsc";
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
        return $accessToken;
    }

    /**
     * @param $values
     * @return array|string
     */
    protected function comprobarValores(array $values)
    {
        if (empty($values)) {
            return "No data found.\n";
        } else {
            return $values;
        }
    }

    protected function maquetarValores($valores_comprobados)
    {
        return "<pre>" . print_r((array)$valores_comprobados, true) . "</pre>";
    }

    /**
     * @return string
     */
    protected function rango(): string
    {
        return "";
    }
}