<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cinema Multisala</title>
<link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
<?
session_start();
//pagina visibile a tutti
		/*CONNESSIONE AL db*/
		error_reporting(E_ALL & ~E_NOTICE);
		/* Load connection data */
		require_once('dbcredentials.php');
		/* Connection String */
		$dsn = 'pgsql:host='.$pdo_host.';port='.$pdo_port.';dbname='.$pdo_database.';user='.$pdo_user.'; password='.$pdo_password;
		$dbConn = new PDO($dsn);
		$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	?>	
</head>
<header>
<h1><a href="index.php">Il Ricettario</a></h1> 
<p>	
			<?php
				if (isset($_SESSION['user'])){ //visualizzazione UTENTE
					echo "Ciao ".$_SESSION['user'];
			?>		<form action="logout.php">
						<button>Esci</button>
					</form>
			<?		}else{//visualizzazione GUEST
			?>			<p><a href="registrazione.php">Registrati</a> -
						<a href="accesso.php">Accedi</a></p>
			<?		}
				//}
			?>
</p>
<hr>
		<p>
			<a href="ricette.php">Ricette</a> | <a href="ricerca.php">Cerca</a>
		</p>
</header>	
<body>
<div id="all">

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
