<?php
$erro = null;
$valido = false;

if (isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true) {
    // Verifique se os campos são válidos (pode adicionar validações aqui)
    if (strlen(utf8_decode($_POST["nome"])) < 2) {
        $erro = "Preencha o campo nome corretamente (2 ou mais caracteres)";
    } else if (strlen(utf8_decode($_POST["endereco"])) < 3) {
        $erro = "Insira um endereço válido, 3 ou mais caracteres";
    } else {
        $valido = true;

        try {
            $connection = new PDO("mysql:host=localhost;dbname=cadastro", "root", "");
            $connection->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Falha : " . $e->getMessage();
            exit();
        }

        // Atualize os dados no banco de dados
        $sql = "UPDATE clientes SET nome = ?, endereco = ? WHERE codigo = ?";
        $stmt = $connection->prepare($sql);

        $stmt->bindParam(1, $_POST["nome"]);
        $stmt->bindParam(2, $_POST["endereco"]);
        $stmt->bindParam(3, $_REQUEST["codigo"]);

        $stmt->execute();

        if ($stmt->errorCode() != "00000") {
            $valido = false;
            $erro = "Erro código " . $stmt->errorCode() . ": ";
            $erro .= implode(", ", $stmt->errorInfo());
        } else {
            // Redirecione de volta para a lista após a atualização
            header("Location: lista clientes.php");
            exit();
        }
    }
}

// Recupere os dados do cliente a ser atualizado


try {
    $connection = new PDO("mysql:host=localhost;dbname=cadastro", "root", "");
    $connection->exec("set names utf8");
} catch (PDOException $e) {
    echo "Falha : " . $e->getMessage();
    exit();
}

$rs = $connection->prepare("SELECT * FROM clientes WHERE codigo = ?");
$rs->bindParam(1, $cliente_id);

if ($rs->execute()) {
    $cliente = $rs->fetch(PDO::FETCH_OBJ);
} else {
    echo "Falha na captura do registro";
    exit();
}
?>

<html>
<head>
    <meta charset="UTF-8"/>
    <title>Alterar Cliente</title>
</head>
<body>
<?php
if ($valido == true) {
    echo "Dados enviados com sucesso !!!";
} else {
    if (isset($erro)) {
        echo $erro . "<br /><br />";
    }
	?>
	<form method=POST action="?validar=true">
	<p>Nome: <input type=text name=nome
	<?php if(isset($_POST["nome"])) {echo "value= '" . $_POST["nome"] . "'";} ?>
	></p>
	<p>Endereço: <input type=text name=endereco
	<?php if(isset($_POST["endereco"])) {echo "value= '" . $_POST["endereco"] . "'";} ?>
	></p>

	<input type=hidden name=codigo value="<?php echo $_REQUEST["codigo"]; ?>">
	

<p><input type=reset value="Limpar"> <input type=submit value="Enviar"></p>
</legend>
    </form>
    <?php
}
?>
</body>
</html>