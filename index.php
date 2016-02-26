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
//pagina visibile a tutti
		/*CONNESSIONE AL db*/
		error_reporting(E_ALL & ~E_NOTICE);
		/* Load connection data */
		require_once('dbcredentials.php');
		/* Connection String */
		$dsn = 'pgsql:host='.$pdo_host.';port='.$pdo_port.';dbname='.$pdo_database.';user='.$pdo_user.'; password='.$pdo_password;
		$dbConn = new PDO($dsn);
		//$dbConn = new PDO('pgsql:host=dblab.dsi.unive.it;port=5432;dbname=a2014u53','a2014u53','.bXiO/jG');
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
<hr>
<div id="main"><div id="content">
			<?php
				if (isset($_SESSION['user'])){//visualizzazione UTENTE
			?>		<div id="elemento">
						<a href="ricette.php">Vedi tutte le ricette</a>
					</div>
					<div id="elemento">
						<a href="ricerca.php">Ricerca</a>
					</div>
					<div id="elemento">
						<a href="aggiungi.php">Aggiungi ricetta</a>
					</div>
					<div id="elemento">
						<a href="logout.php">LOGOUT</a>
					</div>
			
			<?		}else{//visualizzazione GUEST
            ?>
						<h2><a href="registrazione.php">Registrati</a></h2>
						<p><a href="accesso.php">Accedi</a></p>
			<?		}
				//}
			?>
</div></div>
</body>
</body>

</html>
