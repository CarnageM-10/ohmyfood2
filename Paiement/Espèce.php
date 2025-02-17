<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paiement espèce</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <section class="contact" id="contact">
        
    <nav class="navbar">
    <a href="#"><img src="../images/logo/ohmyfood.png" alt="" height="40" width="200"></a>
    <div class ="nav-list">
        <ul>
            <li><a href="../detail.php"><strong>Acceuil</strong></a></li> 
            <li><a href="detail.php"><strong>Menu</strong></a></li>
            <li><a href="../contact.php">Contact</a></li>
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
    $Prenom = $_POST['firstName'];
    $Nom = $_POST['lastName'];
    $Mode_de_Paiement = $_POST['paymentMethod'];

    // Improve security by using prepared statements
    $stmt = $conn->prepare("INSERT INTO paiement (Prenom, Nom, Mode_de_Paiement) VALUES (?,?,?)");
    $stmt->bind_param("sss", $Prenom, $Nom, $Mode_de_Paiement);
    
    $stmt->execute();

    $message = "Succès ! Nous avons enregistré vos informations !😉";
    $stmt->close(); 
}
$conn->close();
?>

<div class="container">
  <h2>Formulaire de Paiement</h2>
  
  <form id="paymentForm" method="POST">
    <div class="form-group">
      <label for="firstName">Prenom:</label>
      <input type="text" id="Prenom" name="firstName" required>
    </div>
    <div class="form-group">
      <label for="lastName">Nom:</label>
      <input type="text" id="Nom" name="lastName" required>
    </div>
    <div class="form-group">
      <label for="paymentMethod">Mode de Paiement:</label>
      <select id="paymentMethod" name="paymentMethod">
        <option value="Espèces" id="Espèces">Espèces</option>
      </select>
    </div>
    <button type="submit">Payer</button>
  </form>
</div>
<div class="message">
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

</div>
</body>
</html>
