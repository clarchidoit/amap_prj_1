<?php
namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    //génération d'un json web token
    public function generate(array $header, array $payload, string $secret, int $validity = 600): string
    {
        if($validity > 0) {
            $now = new DateTimeImmutable();
            $exp = $now->getTimeStamp() + $validity;
    
            $payload['iat'] = $now->getTimeStamp();
            $payload['exp'] = $exp;                
        }
        
        // on encode en base 64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //on nettoie les valeurs encodées (retrait des +, / et =)
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        //on génère la signature (pour ça il faut un secret)
        //dans .env.local insérer à la fin  : JWT_SECRET = '0hLa83lleBroue11e!'
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        // on crée le token
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }

    //on vérifie qu'un token est correctement formé
    public function isvalid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    //on récupère le payload d'un token
    public function getPayload(string $token): array
    {
        //on démonte le token
        $array = explode('.', $token);
        //on décode le payload
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    //on récupère le header d'un token
    public function getHeader(string $token): array
    {
        //on démonte le token
        $array = explode('.', $token);
        //on décode le header
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    //on vérifie si un token a expiré
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTimeImmutable();
        return $payload['exp'] < $now->getTimestamp();
    }

    //on vérifie la signature d'un token
    public function check(string $token, string $secret)
    {
        //on récupère le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        //on régénère un token
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}