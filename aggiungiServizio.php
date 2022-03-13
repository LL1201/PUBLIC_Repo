<?php

session_start();
if (!isset($_SESSION['utente'])) //se la sessione non è
{
    header('Location:login.php');
    exit;
}

if (!isset($_SESSION['ruolo']))
    include_once "connLimiti.php";
else
    switch ($_SESSION['ruolo']) {
        case 'Approvato':
            include_once "connApprovato.php";
            break;
        case 'Segretario':
            include_once "connSegretario.php";
            break;
        default:
            include_once "connLimiti.php";
            break;
    }

$queryInsC = $conn->prepare("INSERT INTO capace(FK_idSocio, FK_idServizio) VALUES((SELECT idSocio FROM soci WHERE mail=?), ?)");
$queryInsC->bind_param("si", $_SESSION['utente'], $_POST['cmbServizio']);
$queryInsC->execute();

header('Location:profilo.php');
