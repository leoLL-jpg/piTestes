<?php 
session_start();

try{
require_once('conexao.php'); //

$nome = $_POST['nome'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM ADMINISTRADOR WHERE ADM_NOME = '$nome' AND ADM_SENHA = '$senha' AND ADM_ATIVO = 1";

$query = $pdo->prepare($sql);
$query->execute();

if($query->rowCount()>0){
    $_SESSION['admin_logado'] = true;
    header('Location: painel_admin.php');
    exit;
} else {
    $_SESSION['mensagem_erro'] = "Nome de usuário ou Senha incorreto";
    header('Location:login.php?erro');
    exit;
}
}catch (Exception $e){
    $_SESSION['mensagem_erro'] = "Erro de conexão: " . $e->getMessage();
    header('Location: login.php?erro');
    exit;
}
?>