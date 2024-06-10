<?php 
function encrypt_id($id_to_encrypt, $key) {
    $cipher = "aes-128-ecb"; // AES encryption with ECB mode
    $padded_id = $id_to_encrypt . str_repeat(" ", 16 - (strlen($id_to_encrypt) % 16)); // Ensure ID length is a multiple of 16 bytes
    $encrypted_id = openssl_encrypt($padded_id, $cipher, $key, OPENSSL_RAW_DATA);
    return rtrim(strtr(base64_encode($encrypted_id), '+/', '-_'), '='); // URL-safe Base64 encoding
}
function decrypt_id($encrypted_id, $key) {
    $cipher = "aes-128-ecb"; // AES encryption with ECB mode
    $decoded_id = base64_decode(str_pad(strtr($encrypted_id, '-_', '+/'), strlen($encrypted_id) % 4, '=', STR_PAD_RIGHT)); // Decode URL-safe Base64
    $decrypted_id = openssl_decrypt($decoded_id, $cipher, $key, OPENSSL_RAW_DATA);
    return rtrim($decrypted_id); // Remove trailing spaces
}
?>