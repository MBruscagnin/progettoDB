<html>
<?php	//effettua il logout dell'utente e rimanda alla pagina di accesso
session_start();
session_destroy();
header("location: accesso.php");
?>
</html>
