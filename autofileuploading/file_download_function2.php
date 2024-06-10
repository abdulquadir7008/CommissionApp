<?php
$tenantID = '';
$clientID = '';
$clientSecret = '';
// Step 1: Get Access Token
function getAccessToken($tenantID, $clientID, $clientSecret) {
    $url = "https://login.microsoftonline.com/$tenantID/oauth2/v2.0/token";
    $data = array(
        'grant_type' => 'client_credentials',
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'scope' => 'https://graph.microsoft.com/.default'
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result, true);

    return $response['access_token'];
}

// Step 2: Download the File
function downloadFile($fileURL, $accessToken, $destinationPath) {
    $options = array(
        'http' => array(
            'header' => "Authorization: Bearer $accessToken\r\n"
        )
    );

    $context = stream_context_create($options);
    $fileContent = file_get_contents($fileURL, false, $context);

    if ($fileContent !== false) {
        file_put_contents($destinationPath, $fileContent);
        return true;
    }

    return false;
}

// Variables

$fileURL = 'https://opengovasia.sharepoint.com/:x:/s/OpenGovRPAWorkstation/EbXI1aGv3RNAjB8L4XXSiaMBUDyAj42q5knD9M8U77h-mw?e=QF9kCf';
$destinationPath = 'events_file/OGBI_kadir.xlsx';

// Get the access token
$accessToken = getAccessToken($tenantID, $clientID, $clientSecret);

// Download the file
if (downloadFile($fileURL, $accessToken, $destinationPath)) {
    echo "File downloaded successfully.";
} else {
    echo "Failed to download the file.";
}

?>