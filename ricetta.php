<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cinema Multisala</title>
<link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
<?
session_start();
if ((empty($_GET)&&empty($_POST)))//pgina visibile solo se si ha scelto il film in listafilm
	header("location: listafilm.php");

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
<?php 		//pagina visibile a tutti ma permette di effettuare prenotazioni solo a utenti registrati
		if (empty($_POST)){//visualizzazione pagina iniziale
			$result=$conn->query("SELECT * FROM film WHERE cod_film=".$_GET['cod_film']) or die ("Errore query");
			while($row = $result->fetch()){	//stampa i dati del film scelto
				echo "<div id='centro'><h1>".$row['titolo'].'</h1><br></div>';
				echo "<img src='images/".$row['cod_film'].".jpg' id=immfilm>";
				echo "Regista: ".$row['regista'].'<br>';
				echo "Casa produttrice: ".$row['casa_produttrice'].'<br>';
				echo "Durata: ".$row['durata']."'<br>";
				echo "<b>Genere</b>: ".$row['genere'].'<br>';
				echo "Nazione e anno: ".$row['nazione'].','.$row['anno'].'<br><br><br>';
				echo "".$row['descrizione'].'<br>';
			}
				echo "<br>";
			?>  
			<div id="centro"><h1>PROGRAMMAZIONE DEL FILM:</h1>
			<form name="prenotazione" method="POST" action="singolofilm.php">
			NUMERO POSTI DA PRENOTARE:<input type='text' name='postidaprenotare'>
			</div>
				<table id="table">
					<thead>
						<tr>
							<td width=25%>PRENOTA</td>
							<td width=25%>GIORNO</td>
							<td width=20%>ORA</td>
							<td width=20%>SALA</td>
							<td width=10%>PREZZO</td>
						</tr>
					</thead>
					<tbody>
						<?php 
							$result=$conn->query("SELECT * FROM programmazione,film WHERE programmazione.film = film.cod_film AND film.cod_film=".$_GET['cod_film']." AND giorno>='".date('Ymd')."'  ORDER BY giorno,ora") or die ("Errore query ");
							while($row = $result->fetch()){	//visualizza le programmazioni del film ancora da effettuare
								echo '<tr>'.'<td>';
								echo '<button value="'.$row['cod_programmazione'].'" name="prog_scelta">PRENOTATI</button>'.'</td>';							
								echo '<td>'.$row['giorno'].'</td>';
								echo '<td>'.$row['ora'].'</td>';
								echo '<td><a href="singolasala.php?sala='.$row['sala'].'">'.$row['sala'].'</a></td>';
								echo '<td>'.$row['prezzo'].'</td>'.'</tr>';
							}
						?>
					</tbody>
				</table>
			</form>
		<?}else{//prenotazione film
					if (isset($_SESSION['user'])){//se accesso con utente
						$sess=$_SESSION['user'];
					}else{
						if(isset($_SESSION['admin'])){//se accesso con admin
							$sess=$_SESSION['admin'];
						}else{
							?>
							<script type="text/javascript">//javascript nessun accesso e indirizzamento a registrazione
								alert("Registrati o accedi per prenotare");
								window.location.href="registrazione.php"
							</script>
							<?
						}
					}
				if($_POST['postidaprenotare']>0){//se ci sono posti disponibili
					$result=$conn->query("SELECT SUM(posti_prenotati) AS nposti 
										FROM prenotazioni WHERE prog_scelta='".$_POST['prog_scelta']."' GROUP BY prog_scelta") 
										or die ("Errore query conteggio posti");//somme dei posti già prenotati
					$row = $result->fetch();
					$postiprenotati=$row['nposti'];
					$result=$conn->query("SELECT n_posti 
										FROM sale,programmazione
										WHERE cod_programmazione=".$_POST['prog_scelta']." AND sala=nome")
										or die ("Errore query posti sala");//posti totali nella sala
					$row =( $result->fetch());
					$postitotali=$row['n_posti'];
					if($postitotali-$postiprenotati>$_POST['postidaprenotare']){//se ci sono posti prenota
						$result=$conn->query("INSERT INTO prenotazioni (prog_scelta,utente,posti_prenotati) 
											VALUES ('".$_POST['prog_scelta']."','".$sess."','".$_POST['postidaprenotare']."')") 
											or die ("Errore query inserimento posti");
						?>
							<script type="text/javascript">//javascript successo prenotzione
								alert("Prenotazione effettuata con successo");
								window.location.href="mieprenotazioni.php"
							</script>
						<?
					}else{
							?>
								<script type="text/javascript">//javascript posti non disponibili
									alert("Prenotazione Non effettuata, posti non disponibili");
									window.location.href="listafilm.php"
								</script>
							<?
					}
				}
				else{?>
					<script type="text/javascript">//javascript posti non disponibili
						alert("inserire posti da prenotare");
						window.location.href="listafilm.php"
					</script>
				<?}
			}
		?>
</div></div>
</div>
</body>
</html>
