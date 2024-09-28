<?php

//inicia a sessão de gerenciamneto do usuario
session_start();

//importa a configuraçao de conexao com banco de dados
require_once('conexao.php');

//verifica se o admin está logado
if(!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $ativo = isset($_POST['ativo']) ? 1: 0;
}

try {

    $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) VALUES (:nome, :email, :senha, :ativo);"; //placeholders
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome, PDO:: PARAM_STR );
    $stmt->bindParam(':email', $email, PDO:: PARAM_STR );
    $stmt->bindParam(':senha', $senha, PDO:: PARAM_STR );
    $stmt->bindParam(':ativo', $ativo, PDO:: PARAM_STR );

    $stmt->execute();

    //pegar o id do adminstrador inserido
    $adm_id = $pdo->lastInsertId();

    echo "<p style='color:green>;' Administrador cadastrado com sucesso. Id: " . $adm_id . "</p>";

} catch (PDOException $e) {
    echo "<p style='color:red>;' Erro ao cadastrar o Administrador " . $e->getMessage() . "</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Administrador</title>
</head>
<body>
    
    <h2>Cadastrar Administrador</h2>
    <form action="" method="post">
        <!--Campos do formulário para   inserir informações do administrador -->
        <label for="nome"> Nome </label>
        <input type="text" name="nome" id="nome" required>

        <p></p>

        <label for="email">Email</label>
        <textarea name="email" id="email" required></textarea>

        <p></p>

        <label for="senha"> Senha </label>
        <input type="number" name="senha" id="senha" required>

        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo" value="1" checked>

        <p></p>

        <button type="submit"> Cadastrar Administrador </button>

        <p></p>



</form>

</body>
</html>