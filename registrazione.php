<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ricettario</title>
<style>
div#elemento{
	width: 200px;
	height: 50px;
	margin: 0 auto;
	border: solid;
	margin-top: 1em;
}
body{
text-align: center;
}
</style>
<?
session_start();
if(isset($_SESSION['user']))
	header("location: home.php");//accesso solo a chi non è iscritto

		/*CONNESSIONE AL db*/
		error_reporting(E_ALL);
		/* Load connection data */
		require_once('dbcredentials.php');
		/* Connection String */
		$dsn = 'pgsql:host=localhost port=5432 dbname='.$pdo_database.' user='.$pdo_user.' password='.$pdo_password;
		$conn = new PDO($dsn);
	?>
</head>
<header>
<h1><a href="home.php">Il Ricettario</a></h1> 
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
				}
			?>
</p>
<hr>
		<p>
			<a href="ricette.php">Ricette</a> | <a href="ricerca.php">Cerca</a>
		</p>
</header>			
<body>
<hr>
<div id="main"><div id="content">
<div id="centro">
<h1>REGISTRAZIONE UTENTE:</h1><br>
			
		<form name="registrazione" method="POST" action="registrazione.php">
			email: <input type='text' name='mail' ><br>
			password:<input type='password' name='password'><br>
			conferma password:<input type='password' name='conferma'><br>
			<br><button type="submit" value="registrati" name="registrati">REGISTRATI</button>
		</form>	
		<?php
			if(!empty($_POST['registrati'])){
				if(($_POST['mail']&&$_POST['password']&&$_POST['conferma'])!=''){//se tutti i campi non sono vuoti
					if($_POST['password']==$_POST['conferma']){	//se password uguale a conferma password
						$result=$conn->query("SELECT email FROM utenti WHERE email='".$_POST['mail']."'") or die ("Errore query");
						$row = $result->fetch();	//query per controllare se email già in uso
						if(($varconf=$row['email'])==""){	//se email non è già in uso inserisco su database
							$result=$conn->query("INSERT INTO utenti (email, password)
												VALUES ('".$_POST['mail']."','".$_POST['password']."')")
												or die ("Errore query, Inserimento non riuscito");
							$_SESSION['user']=$_POST['mail'];	//e attivo sessione
	?>						<script type="text/javascript">//javascript registrazione effettuata
								alert("Registrazione effettuata");
								window.location.href="home.php"
							</script>
	<?					}else	//stampo stringhe per controllo errori
							echo "<font color='red'>Email gia in uso</font>";	
					}else
						echo "<font color='red'>Password diverse</font>";
				}else
					echo "<font color='red'>Compila tutti i campi</font>";
			}	
		?> 
</div>
</div></div>
</div>
</body>
</html>
