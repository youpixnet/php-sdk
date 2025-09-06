<?php

require "YouPix.php";

$youPix = new YouPix(
    "seu cliente secreto",
    "informar o token depois de gerado"
);

/*
 * Checar status dos serviços
 */
$status = $youPix->serviceStatus();

if ($status["success"]) {
    echo "Todos os sistemas operacionais";
} else {
    echo "Serviços em manutenção, tente novamente mais tarde.";
}

/*
 * Obter informações da conta
 */
$account = $youPix->getAccount();

if ($account["success"]) {
    print_r("Informação da conta:\n");
    print_r($account["data"]);
} else {
    print_r("Erro ao obter informações: {$account["message"]}");
}

/*
 * Gerar uma cobrança pix
 */
$cobPix = $createCobPix = $youPix->createCobPix(
    "Meu Produto",
    1.00, // R$ 1,00
    "https://youpix.net"
);

if ($cobPix["success"]) {
    echo "TXID: {$cobPix["data"]["txid"]}\n";
    echo "QRCODE: {$cobPix["data"]["qrcode"]}\n";
    echo "COPIA E COLA: {$cobPix["data"]["code"]}\n";
} else {
    print_r("Erro ao gerar cobrança pix: {$account["message"]}");
}

/*
 * Enviar pix via chave
 */
$sendPix = $youPix->sendPix(
    "chave pix",
    "nome completo do destinatário",
    "cpf",
    20.00 // R$ 20,00
);

if ($sendPix["success"]) {
    echo "PIX ENVIADO PARA FILA COM SUCESSO";
} else {
    echo "OCORREU UM ERRO AO ENVIAR PIX:\n";
    print_r($sendPix);
}


/*
 * Token sem expiração, usado para autenticar as rotas, necessário gerar apenas uma única vez;
 * Caso queira revogar o token anterior, basta gerar um novo;
 */

//$response_token = $getToken = $youPix->generateToken();
//
//print_r($response_token["data"]["token"]);