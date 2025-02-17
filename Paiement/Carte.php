
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="StyleCarte.css">
</head>
<body>
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
    <form  method="POST">
      <div class="f">
        <label for="card-name">Nom:</label>
        <input type="text" name="Nom">
      </div>
      <div class="f">
        <label for="card-number">Numéro de Carte:</label>
        <input type="text"  name="Numero">
      </div>
      <div class="f">
        <label for="cvv">CVV:</label>
        <input type="text"  name="CVV">
      </div>
      <div class="f">
        <label for="amount">Montant a retirée:</label>
        <input type="text" name="Solde">
      </div>
      <div class="beau-bouton">
        <button type="submit" value="Valider" name="Valider">Valider</button>
      </div>
  </form>
  <?php
// Connexion à la base de données
try {
  $bdd = new PDO('mysql:host=localhost;dbname=panier', 'root', '');
} catch (Exception $e) {
  die('Erreur : ' . $e->getMessage());
}

// Vérification de la soumission du formulaire
if (isset($_POST['Valider'])) {
  // Récupération des données du formulaire
  $Nom = $_POST['Nom'];
  $Numero = $_POST['Numero'];
  $CVV = $_POST['CVV'];
  $Solde = $_POST['Solde'];

  // Requête SQL pour sélectionner les données de la table `compte`
  $req = $bdd->prepare('SELECT * FROM compte');
  $req->execute();

  // Tableau pour stocker les données de la table `compte`
  $comptes = [];

  // Parcours des résultats de la requête SQL et stockage des données dans le tableau `comptes`
  while ($data = $req->fetch()) {
      $comptes[] = $data;
  }

  // Boucle pour comparer les données entrées dans le formulaire avec les données de la table `compte`
$erreur = true;
foreach ($comptes as $compte) {
    if ($compte['Nom'] === $Nom && $compte['Numero'] === $Numero && $compte['CVV'] === $CVV && $compte['Solde'] >= $Solde) {
        echo '<p style="color: green;">Les données sont correctes. La transaction a bien été effectuée.</p>';
        // Mettre à jour le solde de la carte après la transaction
        $nouveauSolde = $compte['Solde'] - $Solde;
        $updateSolde = $bdd->prepare('UPDATE compte SET Solde = :nouveauSolde WHERE Numero = :Numero');
        $updateSolde->execute([
            'nouveauSolde' => $nouveauSolde,
            'Numero' => $Numero
        ]);
        $erreur = false;
        break;
    }
} 

  // Affichage d'un message d'erreur si une des données ne correspond pas
  if ($erreur) {
      echo '<p style="color: red;">Une des données entrées est incorrecte.</p>';
      if ($Nom !== $compte['Nom']) {
          echo '<p style="color: red;">Le nom est incorrect.</p>';
      }
      if ($Numero !== $compte['Numero']) {
          echo '<p style="color: red;">Le numéro de carte est incorrect.</p>';
      }
      if ($CVV !== $compte['CVV']) {
          echo '<p style="color: red;">Le CVV est incorrect.</p>';
      }
      if ($Solde > $compte['Solde']) {
          echo '<p style="color: red;">Le solde est insuffisant.</p>';
      }
  }
}
?>
  

  <style>
    .beau-bouton {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 20px;
      width: 20px;
      background-color: #cb508b;
      border-radius: 5px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
      cursor: pointer;
    }
    
    .beau-bouton button {
      background-color: transparent;
      border: none;
      color: white;
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }
    
    .beau-bouton:hover {
      background-color: #3e8e41;
    }
    
    .beau-bouton:hover button {
      transform: scale(1.05);
    }
    </style>
</body>
</html>