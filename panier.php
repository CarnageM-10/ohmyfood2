<?php
    session_start();
    include_once "db.php";

    //supprimer les produits
    //si la valiable del existe
    if(isset($_GET['del'])){
        $id_del = $_GET['del'];
        //suppression
        unset($_SESSION['panier'][$id_del]);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel = "preconnect" href = "https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Panier</title>
</head>
<header>
    <a href="#" class="logo">Brazza<span>C</span>ongo</a>
    <div class="menuToggle" onclick="toggleMenu();"></div>
    <ul class="navbar">
        <li><a href="index.php" onclick="toggleMenu();">Accueil</a></li>
        <li><a href="Menu.php" onclick="toggleMenu();">Menu</a></li>
    </ul>
</header>
<body class="panier">
    <a href="Contact.php" class="link">Valider</a>
    <section>
        <table>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
            <?php
                $total = 0;
                //liste des produits
                //recupéer les clés du tableau session
                $ids = array_keys($_SESSION['panier']);
                // s'il n'y a aucune clé dans le tableau
                if(empty($ids)){
                    echo "Votre panier est vide";
                }else{
                    //si oui
                    $produits = mysqli_query($con, "SELECT * FROM produits WHERE id IN (".implode(',', $ids).")");
                    
                    //liste des produits avec la boucle foreach
                    foreach($produits as $produit):
                        //calculer le total (Prix Unitaire * quantité)
                        // et additionner chaque résultat a chaque tour de boucle
                        $total = $total + $produit['prix'] * $_SESSION['panier'][$produit['id']];
                    ?>
                <tr>
                    <td><img src="Resto Congolais/<?=$produit['img']?>"></td>
                    <td><?=$produit['nom']?></td>
                    <td><?=$produit['prix']?>FCFA</td>
                    <td><?=$_SESSION['panier'][$produit['id']] //quantité?></td>
                    <td><a href="panier.php?del=<?=$produit['id']?>"><img src="delete.png"></a></td>
                </tr>
            
            <?php endforeach ;} ?>            

            <tr class="total">
                <th>Total : <?=$total?>FCFA</th>
            </tr>
        </table>

    </section>
</body>
</html>