
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel = "preconnect" href = "https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="OIP.jpg">
    <link rel="stylesheet" href="styleContact.css">
    <title>Contact</title>
</head>
<body>
    
    
    <section class="contact" id="contact">
        
        <nav class="navbar">
        <a href="#"><img src="images/logo/ohmyfood.png" alt="" height="40" width="200"></a>
        <div class ="nav-list">
            <ul>
                <li><a href="index.php"><strong>Acceuil</strong></a></li> 
                <li><a href="detail.php"><strong>Menu</strong></a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Paiement :</a><br>
                    <span><button class="beau-bouton"><a href="Paiement/Espèce.php">Espèce</a></button>
                    <button class="beau-bouton"><a href="Paiement/Carte.php">Carte de crédit</a></button></span>
                </li>
            </ul>
        </div>
    </nav>>
        </div>
        <?php
$username = "root";
$servername = "127.0.0.1";
$password = "";
$dbname = "panier";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de donnée : ". $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $tel = $_POST['tel'];
    $adresse = $_POST['adresse'];
    $zone = $_POST['zone'];

    // Improve security by using prepared statements
    $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, mail, tel, adresse, Zone) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $nom, $prenom, $mail, $tel, $adresse, $zone);

    if ($stmt->execute()) {
        header("Location:Contact.php?message=yes");
        exit();
    } else {
        echo "Error: ". $sql. "<br>". $conn->error;
        header("Location:Contact.php?message=no");
    }
    $stmt->close();
}
$conn->close();
?>
        <div class="contactform">
            <?php 
                if(isset($_GET["message"]) && $_GET["message"] == "yes") { 
            ?> 
                <div class="succes">
                    <span class="closebtn" onclick="this.parentElement.style.display='none'; "><img src="croix.jpg" alt="">
                    <strong>Succès !</strong> Nous avons enregistré vos informations !😉</span>
                </div>
            <?php
            }
            ?>  
            <form action="" method="POST">
                <h2 class="titre-text"><span>C</span>ontact</h2>
                <div class="inputboite">
                    <input type="text" name="nom" placeholder="Entrez votre nom" required="nom">
                </div>
                <div class="inputboite">
                    <input type="text" name="prenom" placeholder="Entrez votre prénom" required="prenom"></span>
                </div>
                <div class="inputboite">
                    <input type="email" name="mail" placeholder="Entrez votre @mail" required="mail">
                </div>
                <div class="inputboite">
                    <input type="tel" id="tel" name="tel" pattern="[0-9]{2}-[0-9]{3}-[0-9]{3}"  placeholder="Entrez votre télephone" required="tel">
                    <small>Format: 77-456-789</small>
                </div>
                <div class="inputboite">
                    <input type="text" name="adresse" placeholder="Entrez votre adresse" required="Adresse">
                </div>
                <div class="inputboite">
                    <label for="">Dans quel secteur vous trouvez vous ?</label><br>
                    <select name="zone" >
                        <option id="Liberté 6">Liberté 6</option>
                        <option id="Dakar">Dakar</option>
                        <option id="Dieuppeul">Dieuppeul</option>
                        <option id="Pikine">Pikine</option>
                        <option id="Fann">Fann</option>
                    </select>
                </div>
                <div class="inputboite">
                    <input type="submit" value="envoyer" name="envoyer">
                </div>
                
            </form>
        </div>
    </section>
    </div>

</body>
</html>