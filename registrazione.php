<?php
    session_start();
    /* connessione al db */
    $conn = mysqli_connect("localhost", "5ia20", "5ia20","5ia20");
    if (!$conn) {
        die('Impossibile connettersi al database: ' . mysqli_error($conn));
    }
    if(isset($_SESSION['username'])){
        header("location: areapersonale.php");
    }
?>

<html lang = "it">
   <head>
        <title>Pagina di registrazione - condiMP3</title>
        <link href = "css/navbar.css" rel = "stylesheet">
        <link href = "css/registrazione.css" rel = "stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
   </head>
	
   <body>
       <nav>
            <ul>
                <li><a>condiMP3</a></li>
                <li class="right"><a href="login.php">Accedi</a></li>
                <li class="right"><a href="index.php">Home</a></li>
            </ul>
        </nav>
        <main>
            <h1>Registrati a condiMP3</h1> 

                <?php
                    $msg = '';

                    if (isset($_POST['signup']) && !empty($_POST['nome']) && !empty($_POST['cognome']) && !empty($_POST['username']) && !empty($_POST['password'])) {
                        $username = mysqli_real_escape_string($conn,$_POST['username']);
                        $password = mysqli_real_escape_string($conn,$_POST['password']);
                        $nome = mysqli_real_escape_string($conn,$_POST['nome']);
                        $cognome = mysqli_real_escape_string($conn,$_POST['cognome']);

                        $identical_username = "SELECT username FROM utenti WHERE username ='".$username."'";

                        $result = mysqli_query($conn,$identical_username);
                        /* check if the username typed already exists */
                        if(mysqli_num_rows($result) != 0) {
                            $msg = "Username già esistente";
                        } else {
                            /* check if typed password has more than 7 characters */
                            if (strlen($password) > 7){
                                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                                $new_record = "INSERT INTO utenti (username, password, nomeCognome) VALUES ('".$username."', '".$hashed_pass."', '".$nome." ".$cognome."')";
                                mysqli_query($conn,$new_record);
                                $_SESSION['username'] = $username;
                                header("location: areapersonale.php");
                            } else {
                                $msg = "Password troppo corta (almeno 8 caratteri)";
                            }
                        }
                    }
                 ?>

            <div id="registration-box">

                <form id="form-signup" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <h4 class = "form-heading"><?php echo $msg; ?></h4>
                    <label>Nome: </label><br/>
                    <input type="text" class="form-control" name="nome" placeholder="Nome" required autofocus><br/>
                    <label>Cognome: </label><br/>
                    <input type="text" class="form-control" name="cognome" placeholder="Cognome" required autofocus><br/>
                    <label>Username: </label><br/>
                    <input type="text" class="form-control" name = "username" placeholder = "Username" required autofocus><br/>
                    <label>Password: </label><br/>
                    <input type="password" class="form-control" name="password" placeholder="Password" required><br/>
                    <button type="submit" name="signup">Registrati</button>
                </form>
                <h2>Ti sei già registrato? Accedi <a href="login.php">qui</a></h2>
            </div>
        </main>
    <?php
        mysqli_close($conn);
    ?>
    </body>
</html>