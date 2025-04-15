<?php
function encryptPassword($password) {
    $key = getenv('marginal');
    return openssl_encrypt($password, 'AES-128-ECB', $key);
}

function decryptPassword($encryptedPassword) {
    $key = getenv('marginal');
    return openssl_decrypt($encryptedPassword, 'AES-128-ECB', $key);
}
?>
