<?php

session_start();
require_once('conexao.php');

if(!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

try{       ///pdo serve para conexoes com o banco de dados - é uma classe e prepare um metodo //prepara os dados para consulta //para seguranca
    $stmt = $pdo -> prepare("SELECT PRODUTO.*, CATEGORIA.CATEGORIA_NOME, PRODUTO_IMAGEM.IMAGEM_URL, PRODUTO_ESTOQUE.PRODUTO_QTD FROM PRODUTO
    join CATEGORIA on PRODUTO.CATEGORIA_ID = CATEGORIA.CATEGORIA_ID
    left join PRODUTO_IMAGEM on PRODUTO.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID
    left join PRODUTO_ESTOQUE on PRODUTO.PRODUTO_ID = PRODUTO_ESTOQUE.PRODUTO_ID");

    $stmt->execute(); //executa a consulta
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
     //transforma em uarray sociativo [nome:ssd, categoria:hardware];

    //Busca todos os registros retornados pela consulta 

} catch (PDOException $e) {
    //caso de erro na consulta, exibe mensagem
    echo "<p style='color:red;'> Erro ao istar produtos:" . $e->getMessage() . "</p>";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Podutos</title>

    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: blue;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        img {
            max-width: 50px;
            height: auto;
        }
        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }
        .action-btn {
            background-color: green; /* Edit button color */
        }
        .delete-btn {
            background-color: red; /* Delete button color */
        }
        .action-btn:hover {
            opacity: 0.8;
        }

    </style>

</head>
<body>
    
<h2>Produtos Cadastrados</h2>

<table>

    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço</th>
        <th>Categoria</th>
        <th>Ativo</th>
        <th>Desconto</th>
        <th>Estoque</th>
        <th>Imagem</th>
        <th>Ações</th>

    </tr>
    
    <?php foreach($produtos as $produto): ?>

        <tr>

            <td><?php echo $produto['PRODUTO_ID'];?> </td>
            <td><?php echo $produto['PRODUTO_NOME'];?></td>
            <td><?php echo $produto['PRODUTO_DESC'];?></td>
            <td><?php echo $produto['PRODUTO_PRECO'];?></td>
            <td><?php echo $produto['CATEGORIA_NOME'];?></td>
            <td> <?php echo( $produto['PRODUTO_ATIVO'] == 1 ? 'Sim':'Não');?> </td>
            <td><?php echo $produto['PRODUTO_DESCONTO'];?></td>
            <td><?php echo $produto['PRODUTO_QTD'];?></td>
            <td><img src="<?php echo $produto['IMAGEM_URL']; ?>" alt="<?php echo $produto['PRODUTO_NOME'];?>" width="50"> </td>

            <td> 
                <a href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID'];?>" class= "action-btn"> Editar </a> 
                <a href="excluir_produto.php?id=<?php echo $produto['PRODUTO_ID'];?>" class="action-btn delete-btn">Excluir</a>
            </td>
        </tr>

        <?php endforeach; ?>

</table>

<br>
<a href="painel_admin.php"> Voltar ao Painel do Administrador </a>


</body>
</html>