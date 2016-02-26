<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cinema Multisala</title>
<link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
<?
session_start();//accesso a tutti

		/*CONNESSIONE AL DTABASE*/
		error_reporting(E_ALL);
		/* Load connection data */
		require_once('dbcredentials.php');
		/* Connection String */
		$dsn = 'pgsql:host=localhost port=5432 dbname='.$pdo_database.' user='.$pdo_user.' password='.$pdo_password;
		$conn = new PDO($dsn);
	?>
</head>
<body>
<div id="all">
	<div id="header">
		<div id="logo"> <a  href="home.php"><img src="images/logo.jpg"></a> </div>	 
		<div id="accesso">
			<?php
				if (isset($_SESSION['user'])){
					echo "ciao user ".$_SESSION['user'];
			?>		<form action="logout.php">
						<button>Esci</button>
					</form>
			<?	}else{
					if(isset($_SESSION['admin'])){
						echo "ciao admin ".$_SESSION['admin'];
			?>			<form action="logout.php">
							<button>Esci</button>
						</form>
			<?		}else{
			?>			<p><a href="accesso.php">ACCEDI</a></p>
						<p><a href="registrazione.php">REGISTRATI</a></p>
			<?		}
				}
			?>
		</div>
	</div>
	<div id="menu">
		<ul>
			<li><a href="listafilm.php"><p align="center">FILM</p></a></li>
			<li><a href="programmazione.php"><p align="center">PROGRAMMAZIONE</p></a></li>
			<li><a href="sale.php"><p align="center">SALE</p></a></li>
			<li><a href="contatti.php"><p align="center">CONTATTI</p></a></li>
		</ul>
	</div>
<div id="main"><div id="content">
	<div id="centro">
		<h1>LISTA DEI NOSTRI FILM:</h1>
	</div>
	<form method="post" action="listafilm.php">
		<table width="100%">
			<tr>
				<td>
					Ricerca per titolo:<input type="text" name="titolo" placeholder="Inserisci titolo" />
					<button type="submit" value="titolo" name="ricerca">Titolo</button><br>
				</td><td>
					Ricerca per genere:		
					<?php	//mostra i generi presenti nel menu a tendina				
						$result=$conn->query("SELECT DISTINCT genere FROM film") or die ("Errore query film");
						echo '<select name="genere" placeholder="Ricerca per genere">';
						echo '<option name="genere" value="">Scegli il genere</option>';
						while($row = $result->fetch()){
							echo '<option name="genere" value="'.$row['genere'].'">'.$row['genere'].'</option>';
						}?>
						</select>
						<button type="submit" value="genere" name="ricerca">Cerca Genere</button><br>
				</td>
			</tr><tr>
				<td>
					Ricerca per Regista:
					<?php	//mostra i registi presenti nel menu a tendina
						$result=$conn->query("SELECT DISTINCT regista FROM film") or die ("Errore query film");
						echo '<select name="regista" >';
						echo '<option name="regista" value="">Scegli il regista</option>';
						while($row = $result->fetch()){
							echo '<option name="regista" value="'.$row['regista'].'">'.$row['regista'].'</option>';
						}?>
						</select>
					<button type="submit" value="regista" name="ricerca">Cerca per regista</button><br>	
				</td><td>
	</form>
	<form method="post" action="listafilm.php">
		<button type="submit" value="" name="">Visualizza elenco completo</button><br>		
	</form>		
				</td>
			</tr>
		</table>	
			<?php
				if (!empty($_POST)){	
					echo '<br>hai cercato "'.$_POST['ricerca'].': "'.$_POST[$_POST['ricerca']].'"<br>';
					echo "<br>";
					}	//stampa elemento cercato
			?>
			<table id="table">
				<thead>
					<tr>
						<td width=25%>TITOLO</td>
						<td width=25%>CASA PRODUTTRICE</td>
						<td width=25%>REGISTA</td>
						<td width=25%>GENERE</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						if (!empty($_POST)){/*per ricerca query*/
							$result=$conn->query("SELECT * FROM film WHERE 
							".$_POST['ricerca']." ILIKE '%".$_POST[$_POST['ricerca']]."%'"
							) or die ("Errore query ricerca 1");
							if($_POST[$_POST['ricerca']]==""){
								echo "Campo vuoto";
							}else{
								if($result->fetch()==""){
									echo "Nessun Risultato trovato";
								}else{
									$result=$conn->query("SELECT * FROM film WHERE 
							".$_POST['ricerca']." ILIKE '%".$_POST[$_POST['ricerca']]."%'"
							) or die ("Errore query ricerca 2");
									while($row = $result->fetch()){
										echo '<tr>'.'<td>';
										echo '<a href="singolofilm.php? cod_film=';
										echo $row['cod_film'];
										echo '">'.$row['titolo'].'</a>'.'</td>';
										echo '<td>'.$row['casa_produttrice'].'</td>';
										echo '<td>'.$row['regista'].'</td>';
										echo '<td>'.$row['genere'].'</td>'.'</tr>';
									}
								}
							}
						}else{/*al primo accesso*/
							$result=$conn->query("SELECT * FROM film") or die ("Errore query");	
									while($row = $result->fetch()){
										echo '<tr>'.'<td>';
										echo '<a href="singolofilm.php? cod_film=';
										echo $row['cod_film'];
										echo '">'.$row['titolo'].'</a>'.'</td>';
										echo '<td>'.$row['casa_produttrice'].'</td>';
										echo '<td>'.$row['regista'].'</td>';
										echo '<td>'.$row['genere'].'</td>'.'</tr>';
									}
						}
					?>
				</tbody>
			</table>

</div></div>
</div>
</body>
</html>
