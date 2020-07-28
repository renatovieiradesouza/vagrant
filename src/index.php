<?php 
    echo "Testando conexão PHP <br/><br/>";
    $serverName = "192.168.40.56";
    $userName = "phpuser";
    $password = "pass";

    //Criando conexão
    $conn = new mysqli($serverName, $userName, $password);

    //Checando conexão
    if($conn->connect_error) {
        die("conexão falhou: " . $conn->connect_error);
    }

    echo "Conexão OK."
?>