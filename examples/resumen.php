<?php

use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$sum = $util->getSummary();
$sum->setFecGeneracion(new \DateTime('-3days'));
$sum->setFecResumen(new \DateTime('-1days'));

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($sum);
$util->writeXml($sum, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();
    echo 'Ticket :<strong>' . $ticket .'</strong>';

    $result = $see->getStatus($ticket);
    if ($result->isSuccess() && in_array($result->getCode(), ['0', '99'])) {
        $cdr = $result->getCdrResponse();
        $util->writeCdr($sum, $result->getCdrZip());

        echo $util->getResponseFromCdr($cdr);
    } else {
        var_dump($result->getError());
    }
} else {
    var_dump($res->getError());
}
