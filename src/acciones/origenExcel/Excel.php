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
    const ID = "1L5T8Zso07c5wNKPnW0werZnQ5zS8FAdtD0GWliqg76s";

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
        $range = 'Roster!B2:E';
        $response = $service->spreadsheets_values->get(self::ID, $range);
        $values = $response->getValues();

        if (empty($values)) {
            print "No data found.\n";
        } else {
            echo "<pre>".print_r($values, true)."</pre>";
        }

        return new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "chat_id" => $id_chat,
            "parse_mode" => PARSE_MODE,
            "text" => "-",
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
}