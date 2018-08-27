<?php
namespace BrummelMW\acciones;

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
        $response = $service->spreadsheets_values->get(ID, $range);
        $values = $response->getValues();

        if (empty($values)) {
            print "No data found.\n";
        } else {
            print "Name, Major:\n";
            foreach ($values as $row) {
                // Print columns A and E, which correspond to indices 0 and 4.
                printf("%s, %s\n", $row[0], $row[4]);
            }
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
     */
    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = 'token.json';
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
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
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}