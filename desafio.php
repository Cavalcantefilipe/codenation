<?php

$key = "********************************";

$link = "https://api.codenation.dev/v1/challenge/dev-ps/generate-data?token=".$key;

$ch = curl_init($link);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($ch);

curl_close($ch);

$data = json_decode($response, true);


$array = str_split($data["cifrado"]);


$numero = count($array);


for ($i = 0; $i < $numero; $i++) {

    $asc[$i] = ord($array[$i]);

    if ($asc[$i] == 32) {
        $asc[$i] = 32;
    } elseif ($asc[$i] - (int)$data['numero_casas'] < 97) {
        $resto = 97 - ($asc[$i] - (int)$data['numero_casas']);
        $asc[$i] = 123 - $resto;
    } else {
        $asc[$i] = $asc[$i] - (int)$data['numero_casas'];
    }

    $result[$i] = chr($asc[$i]);
}

$foi = implode($result);

$resulmo = sha1($foi);

$data["decifrado"] = $foi;

$data["resumo_criptografico"] = $resulmo;

file_put_contents('answer.json', json_encode($data));

$ch = curl_init('https://api.codenation.dev/v1/challenge/dev-ps/submit-solution?token='.$key);
curl_setopt_array($ch, [    
    CURLOPT_RETURNTRANSFER => false,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [          
      'answer' => curl_file_create('answer.json')
    ]
]);

$resposta=curl_exec($ch);

curl_close($ch);

$aqui =json_decode($resposta, true);

var_dump($aqui);