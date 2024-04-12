<?php

class JWT{
    

function test($any)
{
    echo "<pre>";
    var_dump($any);
    die();
}


function create_secret()
{
    $secret = bin2hex(random_bytes(32));
    putenv("SECRET=$secret");
}

function get_secret($env_name = 'SECRET')
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    $env_file_path = "$root/.env";

    if (is_file($env_file_path)) {
        $file = new \SplFileObject($env_file_path);

        while (false === $file->eof()) {
            putenv(trim($file->fgets()));
        }
    }
    $env_value = getenv($env_name);
    return $env_value;
}

function base64url_encode($str)
{
    return str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($str)
    );
}



function generate_jwt($header, $payload, $secret)
{
    $base64url_header = base64url_encode(json_encode($header));
    $base64url_payload = base64url_encode(json_encode($payload));

    $signature = hash_hmac('sha256', $base64url_header . '.' . $base64url_payload, $secret, true);
    $base64url_signature = base64_encode($signature);

    $jwt = "$base64url_header.$base64url_payload.$base64url_signature";

    return $jwt;
}

function checkvalid_jwt($jwt, $secret)
{
    $tokenParts = explode('.', $jwt);

    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
    $signature_provided = $tokenParts[2];

    $expiration = json_decode($payload)->exp;

    $is_token_expired =  ($expiration - time()) < 0;

    $base64url_header = base64url_encode($header);
    $base64url_payload = base64url_encode($payload);

    $signature = hash_hmac('sha256', "$base64url_header.$base64url_payload", $secret, true);

    $base64url_signature = base64_encode($signature);

    $is_signature_valid = ($base64url_signature === $signature_provided);

    if ($is_token_expired || !$is_signature_valid) {
        // return false;
        echo "<H1>CHUA HOP LE</H1>";
    } else {
        echo "<H1>NGON ROI</H1>";
        // return true;
    }
}

/**EXAMPLE
$header = [
    'typ' => 'JWT',
    'alg' => 'HS256'
];

$payload = [
    'user_id' => "yyyuiu22323aasx",
    'role' => 'Nguyen Van Qcxzcasddsadsadsuynh',
    'exp' => time() + 3000 ** 2
];

create_secret();
test(getenv());
test(create_secret());
$jwt = generate_jwt($header, $payload, "haha");
test($jwt);
time_sleep_until(time() + 1);
checkvalid_jwt("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoieXl5dWl1MjIzMjNhYXN4Iiwicm9sZSI6Ik5ndXllbiBWYW4gUWN4emNhc2Rkc2Fkc2Fkc3V5bmgiLCJleHAiOjE3MjA1NjQ2Nzh9.8M6YG8C68a6zI5VYkT/21j7Dwuu8xqzLLU9MXDKF37k=", "haha");
 */
}

?>