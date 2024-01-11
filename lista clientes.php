<html>
	<head>
		<meta charset="UTF-8"/>
		<title>Lista Usuário</title>
		<link rel="stylesheet" href="cssbanda/CSSLISTALEGAL.css"/>
	</head>
	<body>
	<div id=interface>
	<fieldset>
	<legend id=tituloprincipal>Lista</legend>
		<table>
			<tr>
				<th>Código</th>
				<th>Nome</th>
				<th>Endereço</th>
				</tr>
			
			<?php
			try
			{
				$connection = new PDO ("mysql:host=localhost;dbname=cadastro", "root", "");
				$connection->exec("set names utf8");
			}
			catch(PDOException $e)
			{
				echo "Falha: " . $e->getMessage();
				exit();
			}

			if(isset($_REQUEST["excluir"]) && $_REQUEST["excluir"] == true)
			{
				$stmt = $connection->prepare("DELETE FROM clientes WHERE codigo= ?");
				$stmt->bindParam(1, $_REQUEST["codigo"]);
				$stmt->execute();
			
				if($stmt->errorCode() != "00000")
				{	
					echo "Erro código" . $stmt->errorCode() . ": ";
					echo implode(", ", $stmt->errorInfo());
				}
				else
				{
					echo "Usuário removido com sucesso!!!<br/><br/>";
				}
			}
			
		
		$rs = $connection->prepare("SELECT * FROM clientes");
			
			if($rs->execute())
			{
				while($registro = $rs->fetch(PDO::FETCH_OBJ))
				{
					echo "<tr>";
						echo "<td>" . $registro->codigo . "</td>";
						echo "<td>" . $registro->nome . "</td>";
						echo "<td>" . $registro->endereco . "</td>";
						
						
						echo "<td>";
						echo "<a href='?excluir=true&codigo=" . $registro->codigo."'> Exclusão </a>";
						echo  "<a href='lista clientes alterar.php?codigo=". $registro->codigo."'> Alteração</a>"; 
						
						echo "</td>";
					echo "</tr>";
				}
			}
			?>
		</table>
		</fieldset>
		<p><a href="..\cadastro.php">Voltar</a></p>
	</div>
	</body>
</html>