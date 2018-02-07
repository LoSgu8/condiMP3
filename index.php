<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>condiMP3</title>
        
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    </head>
    <body>
        
        <?php
            /* connessione db */
            $conn = mysqli_connect("localhost", "5ia20", "5ia20","5ia20");
            if (!$conn) {
                die('Impossibile connettersi al database: ' . mysqli_error($conn));
            }
        ?>
        
        <nav>
            <ul>
                <li><a class="active" href="#">condiMP3</a></li>
                <li class="right"><a href="login.php">Accedi</a></li>
                <li class="right"><a href="registrazione.php">Registrati</a></li>
            </ul>
        </nav>
        <div class="container">
            <form id="searchbarform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <input type="text" name="search" id="searchbar" placeholder="Cerca un brano..." />
                    <input type="submit" class="hidden">
            </form>
            <label class="right" for="toggle-1">Mostra/nascondi brani non disponibili</label>
            <input type="checkbox" id="toggle-1" />
            <table class="tabellaBrani" id="disponibili">
                
                <tr><th>Titolo</th><th>Artista</th><th>Caricato da</th></tr>
                <?php
                    /* brani disponibili */
                    if(isset($_GET['search'])){
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                        $brani_disponibili = mysqli_query($conn, "SELECT * FROM brani WHERE Titolo NOT IN (SELECT TitoloP FROM Prestiti) AND Artista NOT IN (SELECT ArtistaP FROM Prestiti) AND (Titolo LIKE '%".$search."%' OR Artista  LIKE '%".$search."%')");
                    } else {
                        $brani_disponibili = mysqli_query($conn, "SELECT * FROM brani WHERE Titolo NOT IN (SELECT TitoloP FROM Prestiti) AND Artista NOT IN (SELECT ArtistaP FROM Prestiti)");
                    }
                    while($riga = mysqli_fetch_array($brani_disponibili)) {
                        print("<tr>");
                        print("<td>".$riga["Titolo"]."</td>");
                        print("<td>".$riga["Artista"]."</td>");
                        /* nome e cognome di caricatoDa */
                        $nome_caricatoDa = mysqli_query($conn, "SELECT nomeCognome FROM Utenti WHERE username = '".$riga["caricatoDa"]."'");
                        print("<td>".mysqli_fetch_assoc($nome_caricatoDa)["nomeCognome"]."</td>");
                        print("</tr>");
                    }
                ?>
            </table>
            
            <div class="mi">
                <table id="nonDisponibili" class="tabellaBrani">
                    <?php
                        /* brani non disponibili */
                        if(isset($_GET['search'])){
                            $search = mysqli_real_escape_string($conn, $_GET['search']);
                            $brani_nonDisponibili = mysqli_query($conn, "SELECT * FROM Brani WHERE Titolo IN (SELECT TitoloP FROM Prestiti) AND Artista IN (SELECT ArtistaP FROM Prestiti) AND (Titolo LIKE '%".$search."%' OR Artista  LIKE '%".$search."%')");
                        }else {
                            $brani_nonDisponibili = mysqli_query($conn, "SELECT * FROM Brani WHERE Titolo IN (SELECT TitoloP FROM Prestiti) AND Artista IN (SELECT ArtistaP FROM Prestiti)");
                        }
                        while($riga = mysqli_fetch_array($brani_nonDisponibili)) {
                            print("<tr class='nonDisponibile'>");
                            print("<td>".$riga["Titolo"]."</td>");
                            print("<td>".$riga["Artista"]."</td>");
                            /* nome e cognome di caricatoDa */
                            $nome_caricatoDa = mysqli_query($conn, "SELECT nomeCognome FROM Utenti WHERE username = '".$riga["caricatoDa"]."'");
                            print("<td>".mysqli_fetch_assoc($nome_caricatoDa)["nomeCognome"]."</td>");
                            print("</tr>");
                        }
                    ?>
                </table>
            </div>

        </div>
        <?php
            mysqli_close($conn);
        ?>
        
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
