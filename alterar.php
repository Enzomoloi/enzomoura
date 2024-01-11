<?php
$erro = null;
$valido = false;

if (isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true) {
   
    if (strlen(utf8_decode($_POST["nome"])) < 2) {
        $erro = "Preencha o campo nome corretamente (2 ou mais caracteres)";
    } else if (strlen(utf8_decode($_POST["estado"])) < 2) {
        $erro = "Insira um estado válido, com 2 caracteres";
    } else {
        $valido = true;

        try {
            $connection = new PDO("mysql:host=localhost;dbname=banco_php", "root", "");
            $connection->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Falha : " . $e->getMessage();
            exit();
        }

        
        $sql = "UPDATE TB_clientes SET nome = ?, cep = ?,estado = ?, cidade = ?, rua = ? WHERE codigo = ?";
        $stmt = $connection->prepare($sql);

        $stmt->bindParam(1, $_POST["nome"]);
        $stmt->bindParam(2, $_POST["cep"]);
        $stmt->bindParam(3, $_POST["estado"]);
        $stmt->bindParam(4, $_POST["cidade"]);
        $stmt->bindParam(5, $_POST["rua"]);
        $stmt->bindParam(6, $_REQUEST["codigo"]);

        $stmt->execute();

        if ($stmt->errorCode() != "00000") {
            $valido = false;
            $erro = "Erro código " . $stmt->errorCode() . ": ";
            $erro .= implode(", ", $stmt->errorInfo());
        } else {
            // Redirecione de volta para a lista após a atualização
            header("Location: lista_clientes.php");
            exit();
        }
    }
}

// Recupere os dados do cliente a ser atualizado
$cliente_id = $_REQUEST["id"];

try {
    $connection = new PDO("mysql:host=localhost;dbname=banco_php", "root", "");
    $connection->exec("set names utf8");
} catch (PDOException $e) {
    echo "Falha : " . $e->getMessage();
    exit();
}

$rs = $connection->prepare("SELECT * FROM TB_clientes WHERE codigo = ?");
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
	<style>
body {font-family: Arial, Helvetica, sans-serif;}	

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 80%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
</style>
</head>
<body>

<h2>Alterar</h2>

<!-- Trigger/Open The Modal -->
<button id="myBtn">Abrir Alterar</button>

<!-- The Modal -->

<div id="myModal" class="modal">

  <!-- Modal content -->
  
  <div class="modal-content">
  
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Alterar</h2>
    </div>
    <div class="modal-body">
    <form method="POST" action="?validar=true">
        <input type="hidden" name="codigo" value="<?php echo $cliente->codigo; ?>">
		
        <p>Nome: <input type="text" name="nome" id="nome" value="<?php echo $cliente->nome; ?>"></p>

        <p>Cep: <input type="text" name="cep" id="cep" value="<?php echo $cliente->cep; ?>"></p>

        <p>Estado: <input type="text" name="estado" id="estado" value="<?php echo $cliente->estado; ?>"></p>

        <p>Cidade: <input type="text" name="cidade" id="cidade" value="<?php echo $cliente->cidade; ?>"></p>

        <p>Rua: <input type="text" name="rua" id="rua" value="<?php echo $cliente->rua; ?>"></p>

        <p><input type="reset" value="Limpar">
		 <input type="submit" value="Enviar"></p>
    </form>
	</div>
    <div class="modal-footer">
      <h3>Alterar</h3>
    </div>
	
  </div>
</div>
<script> function buscaCep(){
	let cep = document.getElementById('cep').value;
	if(cep != null){
		let url = "https://brasilapi.com.br/api/cep/v1/" + cep;
		
		let req = new XMLHttpRequest();
		req.open("GET", url);
		req.send();
		
		req.onload = function() {
			if(req.status === 200){
				let endereco = JSON.parse(req.response);
				document.getElementById("estado").value = endereco.state;
				document.getElementById("cidade").value = endereco.city;
				document.getElementById("rua").value = endereco.street;
			}
		else if(req.status === 404){
				alert("CEP invalido")
		} else{
			alert("Erro ao fazer a requisição");
		}
		}
	}
}

window.onload = function () {
		let cep = document.getElementById("cep");
		cep.addEventListener("change", buscaCep);
}
</script>

<script>


var modal = document.getElementById("myModal");

var btn = document.getElementById("myBtn");

var span = document.getElementsByClassName("close")[0];


btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

    <?php
}
?>
</body>
</html>
