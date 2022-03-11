<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<div class="titolo">
    Il mio profilo
</div>
<?php
session_start();
?>
<nav>
    <ul>
        <?php
        if (!isset($_SESSION['utente'])) //se la sessione non è
        {
            header('Location:login.php');
            exit;
        } else {
            echo '<li></li><a href="index.php" title="Homepage">Homepage</a></li>
                <li><a href="queryPage.php" title="Login">Pagina delle query</a></li>                
                <li class="paginascelta"><a href="profilo.php" title="Il mio profilo">Il mio profilo</a></li>
                <li><a href="logout.php" title="Esci">Esci</a></li>
                <li><a href="management.php" title="Management">Management</a></li>';
        }
        ?>
    </ul>
</nav>

<body>
    <?php
    include_once "connLimiti.php";

    $data = $conn->query("SELECT * FROM soci");
    while ($row = $data->fetch_assoc()) {
        if ($row['mail'] == $_SESSION['utente']) {
            $cognome = $row['cognome'];
            $nome = $row['nome'];
            $telefono = $row['nTelefono'];
            $mail = $row['mail'];
        }
    }

    $query = $conn->prepare("SELECT servizi.descrizione AS servDesc FROM servizi
    JOIN capace ON servizi.idServizio=capace.FK_idServizio
    JOIN soci ON capace.FK_idSocio=soci.idSocio
    WHERE mail=?");
    $query->bind_param("s", $mail);
    $query->execute();

    $risultato = $query->get_result();

    echo '<div class="form-center">
        <form action="cambiaDati.php" method="post">
            <fieldset>
                <h3>I tuoi dati</h3>
                Cognome:
                <input type="text" name="txtCognome" value="' . $cognome . '" readonly>
                <br>
                Nome:
                <input type="text" name="txtNome" value="' . $nome . '" readonly>
                <br>
                Numero telefono:
                <input type="text" name="txtNumero" maxlength="10" value="' . $telefono . '">
                <br>
                Mail:
                <input type="text" name="txtMail" value="' . $mail . '" readonly>   
                <br>                              
                <input type="submit" name="btnModifica" value="Aggiorna i dati!">  
                <br>
                Per poter modificare gli altri dati (compresa la mail) è necessario contattare un segretario!                
            </fieldset>
        </form>
    </div>';
    echo '<div class="form-center">
        <form action="cambiaDati.php" method="post">
            <fieldset>
                <h3>Aggiungi dei servizi che puoi offrire!</h3>';
    echo "<table>";
    echo "<tr>";
    echo "<th>Servizi attualmente offerti</th>";
    echo "</tr>";

    while ($array = mysqli_fetch_array($risultato, MYSQLI_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $array['servDesc'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br/><br/>";
    echo '<select name="cmbServizio">';

    include("cmbServizio.php");

    echo '</select>';
    echo '<input type="submit" name="btnAggiungi" value="Aggiungi">';
    echo '</fieldset>
        </form>
    </div>';
    ?>
</body>

</html>