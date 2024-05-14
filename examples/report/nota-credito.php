<?php

require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

$note = $util->getNote();

try {
    $pdf = $util->getPdf($note);
    $util->showPdf($pdf, $note->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}