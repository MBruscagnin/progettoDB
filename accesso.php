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
				if (isset($_SESSION['user'])){	//visualizzazione UTENTE
					echo "Ciao ".$_SESSION['user'];
			?>		<form action="logout.php">
						<button>Esci</button>
					</form>
			<?		}else{						//visualizzazione GUEST
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
<?php
			if (!empty($_POST)){//passa i valori con aggiornamento pagina in caso di login errato
				$email=$_POST['email'];
				$password=$_POST['password'];
			}
			echo "<p>LOGIN UTENTE</p>";
			echo "<form name='login' method='POST' action='accesso.php'>";
			if (!empty($_POST)){//se login errato mantengo la mail
				echo "email: <input type='text' name='email' value='".$email."'><br>";
			}else{//... altrimenti lo crea vuoto
				echo "email: <input type='text' name='email' ><br>";
			}
			echo "password:<input type='password' name='password'><br>";
			echo "<button>Accedi</button>";
			echo "</form>";
			if (!empty($_POST)){//tentativo ACCESSO
				$result=$conn->query("SELECT * FROM utenti WHERE email = '".$email."' 
									AND password= '".$password."'") or die ("Errore query email");
				if($row = $result->fetch()){//dati CORRETTI
					$_SESSION['user']=$email;
?>
			<script type="text/javascript">//javascript di accesso
				alert("Benvenuto");
				window.location.href="home.php";
			</script>
<?
				}else{// avviso dati ERRATI
						echo "i dati sono errati";
				}
			}
?> 	</div>
</div></div>
</body>
</html>

