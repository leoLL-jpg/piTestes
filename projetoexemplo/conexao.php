<?php 

$host = 'www.thyagoquintas.com.br';
$db = 'Alpha';
$user = 'alpha'; //localmente
$pass = 'alpha';  // tem que ser vazio sem espaço
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;$charset";
$options = [ 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //especifica como o PDO deve lidar com erros. Nesse caso o PDO lança uma excessão
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //define o modo de obtenção de dados padão mas consultas. Nesse caso, os resultados retornarão como um array associativo
    PDO::ATTR_EMULATE_PREPARES => false, //controla se o PDO deve emular prepared statements do lado do cliente ou no lado do servidpr false sera do lado do servidor
];

try {
        $pdo = new PDO($dsn,$user,$pass,$options);
        echo 'Conexão bem sucedida';
 } catch (\PDOException $e){
        throw new \PDOException($e->getMessage(),(int)$e->getCode());
}
?>


