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
        <title>Pagina di login - condiMP3</title>
        <link href = "css/navbar.css" rel = "stylesheet">
        <link href = "css/login.css" rel = "stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
   </head>
	
   <body>
        <nav>
            <ul>
                <li><a>condiMP3</a></li>
                <li class="right"><a href="index.php">Home</a></li>
                <li class="right"><a href="registrazione.php">Registrati</a></li>
            </ul>
        </nav>
        <main>
            <h1>Esegui il Login</h1> 

                <?php
                    $msg = '';

                    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
                        $username = mysqli_real_escape_string($conn,$_POST['username']);
                        $password = mysqli_real_escape_string($conn,$_POST['password']); 

                        $sql = "SELECT password FROM utenti WHERE username = '".$username."'";
                        $result = mysqli_query($conn,$sql);
                        /* check if the username typed exists */
                        if(mysqli_num_rows($result) != 1) {
                            $msg = "Username non esistente";
                        } else {
                            $hashed_pass = mysqli_fetch_array($result)['0'];

                            /* compare hashed real password with the one typed */
                            if (password_verify($password, $hashed_pass)){
                                $_SESSION['username'] = $username;
                                header("location: areapersonale.php");
                            } else {
                                $msg = "Password non corretta";
                            }
                        }
                    }
                ?>

            <div id="login-box">

                <form id="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <h4 class = "form-heading"><?php echo $msg; ?></h4>
                    <label>Username: </label><br/>
                    <input type="text" class="form-control" name = "username" placeholder = "Username" required autofocus><br/>
                    <label>Password: </label><br/>
                    <input type="password" class="form-control" name="password" placeholder="Password" required><br/>
                    <button type="submit" name="login">Login</button>
                </form>
                    <h2>Non hai ancora un account? Registrati <a href="registrazione.php">qui</a></h2>
               </div>
        </main>
      
    </body>
    <?php
        mysqli_close($conn);
    ?>
</html>