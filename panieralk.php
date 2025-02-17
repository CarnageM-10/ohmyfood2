<?php
    session_start();
    include_once "con_dbb.php";

    // initialiser la variable de session 'panier' en tant que tableau vide
    if(!isset($_SESSION['panier'])) { $_SESSION['panier'] = array(); }

    //supprimer les produits
    //si la variable del existe
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="panier.css">
</head>
<body class="panier">
    <a href="detail.php" class="link">Restaurant</a>
    <section>
    <a href="resultalk.php" class="link">Montant total</a>
        <table>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
            <?php
                $total1 = 0;
                //liste des produits
                //recupéer les clés du tableau session
                $ids = array_keys($_SESSION['panier']);
                // s'il n'y a aucune clé dans le tableau
                if(empty($ids)){
                    echo "Votre panier est vide";
                }else{
                    //si oui
                    $produits = mysqli_query($con, "SELECT * FROM products WHERE id IN (".implode(',', $ids).")");
                    
                    //liste des produits avec la boucle foreach
                    foreach($produits as $produit):
                        //calculer le total (Prix Unitaire * quantité)
                        // et additionner chaque résultat a chaque tour de boucle
                        $total1 = $total1 + $produit['price'] * $_SESSION['panier'][$produit['id']];
                    ?>
                 <td><img src="Alkimia/<?=$produit['img']?>"></td>
                    <td><?=$produit['name']?></td>
                    <td><?=$produit['price']?> FCFA</td>
                    <td><?= $_SESSION['panier'][$produit['id']] ?></td> <!-- Ligne corrigée -->
                    <td><a href="panieralk.php?del=<?=$produit['id']?>"><img src="delete.jpg"></a></td>
                </tr>
            
            <?php endforeach ;} ?>            

            <tr class="total">
                <th>Total : <?=$total1?> FCFA </th>
            </tr>
        </table>
        <?php $_SESSION['total1'] = $total1; ?>
    </section>
    <a href="panieralk2.php"><img src="fleche-droite-dans-un-cercle.png"></a></td>
    
    
</body>
</html>