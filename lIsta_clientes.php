<html>
	<head>
		<meta charset="UTF-8" />
		<title>Lista de clientes </title>
		<link rel="stylesheet" href="form.css" />
	</head>
	<style>
		body {
		font-family: "Poppins", sans-serif;
		background-color: #fafafa;
		}
		
		.row {
		display: flex;
		justify-content: center;
		align-items: center;
		}

		.card {
			border-radius: 5px;
			box-shadow: 7px 7px 13px 0px rgba(50, 50, 50, 0.22);
			padding: 30px;
			margin: 20px;
			width: 500px;
			transition: all 0.3s ease-out;
			}
	</style>
	<body>	
		
	<div id=interfaces>
	
			<?php
			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=banco_php", "root", "");
				$connection->exec("set names utf8");
			}
			catch(PDOException $e)
			{
				echo "Falha: " . $e->getMessage();
				exit();
			}
			
	
			if(isset($_REQUEST["excluir"]) && $_REQUEST["excluir"] == true)
			{
				$stmt = $connection->prepare("DELETE FROM TB_clientes WHERE codigo = ?");
				$stmt->bindParam(1, $_REQUEST["codigo"]);
				$stmt->execute();
			
				if($stmt->errorCode() != "00000")
				{	
					echo "Erro código" . $stmt->errorCode() . ": ";
					echo implode(", ", $stmt->errorInfo());
				}
				else
				{
					//Mensagem de exclusão
					?>
					<script>
					window.open("popexclusa.html", 'janela', 'width=795, height=590, top=100, left=699, scrollbars=no, status=no, toolbar=no, location=no, menubar=no, resizable=no, fullscreen=no')
	
					</script>
					<?php
				}
			}
			
		
			
			$rs = $connection->prepare("SELECT * from TB_clientes");
			
			if($rs->execute())
			{
				
				while($registro = $rs->fetch(PDO::FETCH_OBJ))
					
					{
						
						?> <div class="row">
						<div class="card"> <table>
						<tr>
				<th>Codigo</th>
				<th>Nome</th>
				<th>Cep</th>
				<th>Estado</th>
				<th>Cidade</th>
				<th>Rua</th>
			</tr><?php
					echo "<tr>";
						
					
						echo "<td>" . $registro->codigo . "</td>";
						echo "<td>" . $registro->nome . "</td>";
						echo "<td>" . $registro->cep . "</td>";
						echo "<td>" . $registro->estado. "</td>";
						echo "<td>" . $registro->cidade . "</td>";
						echo "<td>" . $registro->rua . "</td>";
						
						echo "<td>";
						echo "<a href='?excluir=true&codigo=" . $registro->codigo ."'> Exclusão </a>";
						echo "<a href='alterar.php?id=" . $registro->codigo ."'> Alterar </a>";
						
						echo "</td>";
					echo "</tr>";	
					?>	</table></div> </div><?php					
					}
					
					?> <div class="row">
						<div class="card"> <table>
						<tr>
				<th></th>
			</tr><?php
					echo "<tr>";
					
						echo "<td>" . "" . "</td>";
						
						echo "<td>";
					echo "<a href='cadastro.php?id="."'> Cadastro + </a>";
				
					
					?></div> </div><?php	
			} else
			{
					echo "Falha na seleção de usuários <br />";
					
			}
			?>
			
		
		
		
	
	</div>
	</body>
</html>