<?php
function getAccessToken($tenantID, $clientID, $clientSecret) {
    $url = "https://login.microsoftonline.com/$tenantID/oauth2/v2.0/token";
    $data = array(
        'grant_type' => 'client_credentials',
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'scope' => 'https://graph.microsoft.com/.default'
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded'
    ));

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpStatus != 200) {
        echo 'HTTP error: ' . $httpStatus . "\n";
        echo 'Response: ' . $response . "\n";
        return null;
    }

    curl_close($ch);

    $response = json_decode($response, true);
    if (isset($response['error'])) {
        echo 'Error: ' . $response['error'] . "\n";
        echo 'Description: ' . $response['error_description'] . "\n";
        return null;
    }

    return $response['access_token'];
}

function downloadFile($fileURL, $accessToken, $destinationPath) {
    $ch = curl_init($fileURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $accessToken",
        "Accept: application/octet-stream"
    ));

    $fileContent = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return false;
    }

    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpStatus != 200) {
        echo 'HTTP error: ' . $httpStatus;
        return false;
    }

    curl_close($ch);

    if ($fileContent !== false) {
        file_put_contents($destinationPath, $fileContent);
        
        // Debugging: Print first few bytes of the content
        $firstBytes = substr($fileContent, 0, 100);
        echo "File Content First 100 Bytes: " . bin2hex($firstBytes) . "\n";

        return true;
    }

    return false;
}

// Variables
$tenantID = '';
$clientID = '';
$clientSecret = '';

$fileURL = 'https://opengovasia.sharepoint.com/:x:/s/OpenGovRPAWorkstation/EbXI1aGv3RNAjB8L4XXSiaMBUDyAj42q5knD9M8U77h-mw?e=QF9kCf';
$destinationPath = 'events_file/filename.xlsx';

// Get the access token
$accessToken = getAccessToken($tenantID, $clientID, $clientSecret);

if ($accessToken === null) {
    echo "Failed to get access token.";
    exit;
}

// Download the file
if (downloadFile($fileURL, $accessToken, $destinationPath)) {
    echo "File downloaded successfully.";
} else {
    echo "Failed to download the file.";
}
?>
