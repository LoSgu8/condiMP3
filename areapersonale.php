<?php
    session_start();
    /* connessione al db */
    $conn = mysqli_connect("localhost", "5ia20", "5ia20","5ia20");
    if (!$conn) {
        die('Impossibile connettersi al database: ' . mysqli_error($conn));
    }
    /* check if user logged in */
    if(!isset($_SESSION['username'])){
        header("location: login.php");
    }
?>

<html lang = "it">
   <head>
        <title>Area Personale - condiMP3</title>
        <link href = "css/navbar.css" rel = "stylesheet">
        <link href = "css/areapersonale.css" rel = "stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
   </head>
	
   <body>
       <nav>
            <ul>
                <li><a>condiMP3</a></li>
                <li class="right"><a href="logout.php">Esci</a></li>
                <li class="right"><a href="index.php">Home</a></li>
            </ul>
        </nav>
        <main>
            <?php
                $nome_query = "SELECT nomeCognome FROM utenti WHERE username ='".$_SESSION['username']."'";
                $nome = mysqli_query($conn, $nome_query);
                
                print ("<h1>Benvenuto nella tua area personale ".mysqli_fetch_array($nome)[0]."</h1>");
            ?>
            <section class="small" id="caricaBrano">
                <?php
                    $msg = '';

                    if (isset($_POST['caricaBrano']) && !empty($_POST['caricaTitolo']) && !empty($_POST['caricaArtista'])) {
                        $caricaTitolo = mysqli_real_escape_string($conn,$_POST['caricaTitolo']);
                        $caricaArtista = mysqli_real_escape_string($conn,$_POST['caricaArtista']); 

                        $check_already_exists = "SELECT * FROM brani WHERE titolo = '".$caricaTitolo."' AND artista = '".$caricaArtista."'";
                        $result = mysqli_query($conn,$check_already_exists);
                        /* check if the username typed exists */
                        if(mysqli_num_rows($result) == 0) {
                            $insert_brano = "INSERT INTO brani (titolo, artista, caricatoDa) VALUES ( '".$caricaTitolo."', '".$caricaArtista."', '".$_SESSION['username']."')";
                            mysqli_query($conn, $insert_brano);
                            $msg = "Brano caricato correttamente";
                        } else {
                            $msg = "Brano già esistente";
                        }
                    }
                ?>
                <h2>Carica un Brano</h2>
                <form id="form_carica" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <h4 class = "form-heading"><?php echo $msg; ?></h4>
                    <label>Titolo canzone</label> <br/>
                    <input class="carica" type="text" name="caricaTitolo" placeholder="Titolo Brano" /> <br/>
                    <label>Artista canzone</label> <br/>
                    <input class="carica" type="text" name="caricaArtista" placeholder="Artista Brano" /> <br/>
                    <input class="carica" type="submit" name="caricaBrano" value="Carica Brano" />
                </form>
            </section>
            <section id="braniCaricati">
                <?php
                    $msg2 = '';
                    if (isset($_POST['eliminaBrano'])){
                        $eliminaBrano = mysqli_query($conn, "DELETE FROM brani WHERE titolo = '".$_POST['eliminaTitolo']."' AND artista = '".$_POST['eliminaArtista']."' AND caricatoDa = '".$_SESSION['username']."'");
                        if (!$eliminaBrano) {
                            $msg2 = 'Non è stato possibile eliminare il brano';
                        } else {
                            $msg2 = 'Brano eliminato correttamente';
                        }
                    }
                ?>
                <h2>Brani che hai caricato</h2>
                <h4 class = "form-heading"><?php echo $msg2; ?></h4>
                
                
                <?php
                    
                
                    $brani_caricati = mysqli_query($conn, "SELECT titolo, artista FROM brani WHERE caricatoDa = '".$_SESSION['username']."'");
                    if(mysqli_num_rows($brani_caricati) == 0){
                       print('<h4>Non è presente alcun brano caricato da te</h4>');
                    } else {
                        print("<table id='caricati'><tr><th>Titolo</th><th>Artista</th><th>Rimuovi brano</th></tr>");
                        while($riga = mysqli_fetch_array($brani_caricati)) {
                            print("<tr>");
                            print("<td>".$riga["titolo"]."</td>");
                            print("<td>".$riga["artista"]."</td>");
                            /* button which permits to eliminate a record */
                            print("<td><form class='form_elimina' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST'><input type='hidden' name='eliminaTitolo' value='".$riga["titolo"]."'/><input type='hidden' name='eliminaArtista' value='".$riga["artista"]."'/><input class='elimina' type='submit' name='eliminaBrano' value='Elimina brano'/></td>");
                            print("</tr>");
                        }
                        print('</table>');
                    }
                ?>
            </section>
        </main>
    <?php
        mysqli_close($conn);
    ?>
   </body>
</html>