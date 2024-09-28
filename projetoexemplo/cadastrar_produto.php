<?php 

session_start(); 
require_once("conexao.php"); 

if(isset($_SESSION['admin_logado.php'])){ 
    header(('Location:login.php')); 
    exit(); 
} 


//bloco de consulta  para buscar categorias
try{
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria->execute();
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e) {
    echo "<p style='color:red;'> Erro ao buscar categorias:" . $e->getMessage() . "</p>";
}

//bloco que será executado quando o formulario for submetido
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //pegamos os valores do POST enviados via formulario
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $desconto = $_POST['desconto'];
    $estoque = $_POST['estoque'];
    $categoria_id = $_POST['categoria'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $imagem_urls = $_POST['imagem_url'];
    $imagem_ordem = $_POST['imagem_ordem'];

 

}

    //bloco para inserir no banco de dados os dados capturados do form
    try {
        $sql = "INSERT INTO PRODUTO(PRODUTO_NOME, PRODUTO_DESC, PRODUTO_PRECO, CATEGORIA_ID, PRODUTO_ATIVO, PRODUTO_DESCONTO) VALUES (:nome, :descricao, :preco, :desconto, :categoria_id, :ativo, :desconto)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);  
        $stmt->bindParam(':preco', $preco, PDO::PARAM_INT);  
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_STR);  
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);  
        $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);  
        $stmt->execute(); 

        $produto_id = $pdo->lastInsertId();

        $sql_estoque = "INSERT INTO PRODUTO_ESTOQUE(PRODUTO_ID, PRODUTO_QTD) VALUES (:produto_id, :estoque)";
        $stmt_estoque = $pdo->prepare($sql_estoque);
        $stmt_estoque->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);  
        $stmt_estoque->bindParam(':estoque', $estoque, PDO::PARAM_INT); 
        
        $stmt_estoque->execute(); 

        foreach($imagem_urls as $index => $url) {
            $ordem = $imagem_ordens[$index];

            $sql_imagem = "INSERT INTO PRODUTO_IMAGEM(IMAGEM_URL, PRODUTO_ID, IMAGEM_ORDEM) VALUES (:url_imagem, :produto_id, :imagem_ordem)";
            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':url_imagem', $url, PDO::PARAM_STR);  
            $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':imagem_ordem', $ordem, PDO::PARAM_INT); 
            
            $stmt_imagem->execute(); 
            
        }
        
        echo "<p style='color:green;'> Produto cadastrado com sucesso </p>";

    } catch (PDOException $e) {
        echo "<p style='color:red;'> Erro ao cadastrar produto: " . $e->getMessage() . "</p>";
    }

?> 

<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Cadastrar Produto</title> 

    <script>

    //adiciona u novo campo de imagem URL e ordem
    function adicionarImagem() {
        //cria uma variavel que joga nela o lemento identificado por id='containerImagens' que é uma div que contera a div de input
        const containerImagens = document.getElementById('containerImagem');
        
        //cria uma nova div e joga na variavel novoDiv, que tem o nome de classe 'imagem_input'
        const novoDiv = document.createElement('div');
        novoDiv.className = 'imagem_input';

        //criar um elemento de input e jga na variavel novoInputUrl
        const novoInputURL = document.createElement('input');
        novoInputURL.type = 'text';
        novoInputURL.nome = 'imagem_url[]';
        novoInputURL.placeholder = 'URL da imagem';
        novoInputURL.required = true;

        //cria um elemento de input de e jogo na vaariavel novoInputOrdem
        const novoInputOrdem = document.createElement('input');

        //define osatributos desse input criado
        novoInputOrdem.type = 'number';
        novoInputOrdem.name = 'imagem_ordem[]';
        novoInputOrdem.placeholder = 'Ordem';
        novoInputOrdem.min = '1';
        novoInputOrdem.required = true;

        //incorpora esses dois input criando na div definida como novoDiv - que foi criada acima
        novoDiv.appendChild(novoInputURL);
        novoDiv.appendChild(novoInputOrdem);

        //incorpora a div novoDiv na div mais externa  denomina container imagens
        containerImagens.appendChild(novoDiv);

    }

    </script>
</head> 

<body> 

     

    <form action="" method="post" enctype="multipart/form-data"> 

    <!-- campos do formulario para inserir informaçoes do produto --> 

        <label for="name">Nome:</label> 
        <input type="text" name="nome" id="nome" required>  
        <p></p> 

        <label for="descricao">Descrição</label> 
        <textarea name="descricap" id="descricao" required> </textarea> 
        <p></p> 

        <label for="preco">Preço:</label> 
        <input type="number" name="preco" id="preco" step="0.01" required> 
        <p></p> 

        <label for="desconto">Desconto</label> 
        <input type="number" name="desconto" id="desconto" step="0.01" required> 
        <p></p> 

        <label for="estoque">Estoque</label> 
        <input type="number" name="estoque" id="estoque" required> 
        <p></p> 

        <label for="categoria_id"> Categoria ID</label> 

        <select name="categoria_id" id="categoria_id" required> 
            <?php foreach ($categorias as $categoria){?> 
                <option value="<?php echo $categoria['CATEGORIA_ID'];?>"> 
                <?php echo $categoria['CATEGORIA_NOME']; ?> </option> 
            <?php } ?> 
        </select>
        
        <p></p> 

            <label for="ativo"> ativo </label> 
            <input type="checkbox" name="ativo" id="ativo" value="1" checked>

            <div id="containerImagem"> 
                <div class="imagem_input"> 
                    <input type="text" name="imagem_Url[]" placeholder="URL da imagem" required> 
                    <input type="number" name="imagem_ordem" placeholder="Ordem" min="1" required> 
                </div> 
            </div> 

        <button type="button" onclick="adicionarImagem()" >Adicionar mais imagens </button>         
        <p></p> 

        <button type="submit"> Cadastrar Produto </button>

    </form> 

    <a href="painel_admin.php"> Voltar ao Painel do Administrador</a> 

</body> 
</html> 