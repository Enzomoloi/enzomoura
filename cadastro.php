<html>
    <head>
        <style type="text/css">
            /* The Modal (background) */
            .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* Modal Content/Box */
            .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            }

            /* The Close Button */
            .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            }

            .close:hover,
            .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
            }
        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!--<script src="jquery-3.7.1.min.js"></script>-->
        
        <script>
            $(document).ready(function(){
                /*var num = 0;

                $("#teste").click(function(){
                    num = num + 1;
                    alert(num);
                });*/

                $("#myBtn").click(function(){
                    $("#myModal").css("display", "block");

                });

                $(".close").click(function(){
                    $("#myModal").css("display", "none");
                });

                $(".editar").click(function(){
                    $("#txtCodigo").val($(this).attr("codigo"));
                    $("#myModal").css("display", "block");
                });
            });
        </script>
    </head>
    <body>
<?php
	$erro = null;
	$valido = false;		
	
	if(isset($_REQUEST["validar"]) && $_REQUEST["validar"]==true)
	{
		if(strlen(utf8_decode($_POST["nome"]))<3)
		{
			$erro = "Preencha o campo nome corretamente(3 ou mais caracteres)";
		}
		else if(strlen(utf8_decode($_POST["endereco"]))==false)
		{
			$erro = "Preencha o campo nome corretamente(3 ou mais caracteres)";
		}
			$valido = true;

			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=cadastro", "root", "");
				$connection->exec("set names utf8");
			}
			catch(PDOException $e)
			{
				echo "Falha : " . $e->getMessage();
				exit();
			}
			/*incio do codigo de conexao*/


		$sql = "INSERT INTO clientes
					(nome, endereco)
					VALUES (?, ?)";
					
			$stmt = $connection->prepare($sql);
			
			$stmt->bindParam(1, $_POST["nome"]);
			$stmt->bindParam(2, $_POST["endereco"]);
			
			
			$stmt->execute();
			
			if($stmt->errorCode() != "00000")
			{
				$valido = false;
				$erro = "Erro código " . $stmt->errorCode() . ": ";
				$erro = implode(", ", $stmt->errorInfo());
			}
			/*fim do codigo de conexao*/
		}
	
	
?>
<html>
	<head>
		<meta charset="UTF-8" /> 
		<title>Cadastro</title>
		<link rel="stylesheet" href="cssbanda/csslegal.css"/>
		<div id=interface>
	</head>
	<body>

	<fieldset>
			<legend id=tituloprincipal>⠀⠀⠀Cadastro⠀⠀</legend>
			<?php
			if($valido == true)
			{
				echo "Dados enviados com sucesso !!!";
				?>
				 <p><a href="..\lista clientes.php">Lista</a></p>
				<?php
			}
			else
			{
			
			if(isset($erro))
			{
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
			
		
		<p><input type=reset value="Limpar"> <input type=submit value="Enviar"></p>
		</legend>
		</form>
		</fieldset>
		<?php
		}
		?>
	</body>
	</div>
</html>