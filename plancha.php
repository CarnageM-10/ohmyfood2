<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>plancha</title>
    <link rel="stylesheet" href="detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
 
<body>
    <div class="high">
        <div class="log"> <a href="index.php"><i class="fa-solid fa-arrow-left fa-xl" style="color: #000000;"></i> </a></div> <img src="images/logo/ohmyfood.png" alt="" height="40" width="200">
        <a href="panierCha.php" class="link">panier<span><?=array_sum($_SESSION['panier2'])?></span></a>
    </div>   
    <div class="haut"> <img src="Plancha/resto.jpeg" alt=""></div>
    <main>
        <div class="list">
            <div class="palette"><em>La Plancha</em>  <i class="fa-regular fa-heart"></i> </div>
            <h3 class="titre">ENTREE</h3>
            <div class="sec">
            <?php 
        //inclure la page de connexion
        include_once "con_dbb2.php";
        //afficher la liste des produits
         $req = mysqli_query($con, "SELECT * FROM product");
         while( $row =mysqli_fetch_assoc($req)){
        ?>
            <button> 
                <img src="Plancha/<?=$row['img'] ?>" alt="">
                <h3>
                    <p class="menu"> <?=$row['name'] ?></p>
                </h3>
                <p class="Acc"></p>
                <p class="Prix"><b><?=$row['price'] ?></b></p>
                <a href="ajouter_panierCha.php?id=<?=$row['id']?>" class="id_product">Ajouter au panier</a>
                </p>
                 
            </button>
            <?php }?>
        </div>
            <h3 class="titre">PLATS</h3>
            <div class="sec">
            <?php 
        //inclure la page de connexion
        include_once "con_dbb2.php";
        //afficher la liste des produits
         $req = mysqli_query($con, "SELECT * FROM plat");
         while( $row =mysqli_fetch_assoc($req)){
        ?>
            <button> 
                <img src="Plancha/<?=$row['img'] ?>" alt="">
                <h3>
                    <p class="menu"> <?=$row['name'] ?></p>
                </h3>
                <p class="Acc"></p>
                <p class="Prix"><b><?=$row['price'] ?></b></p>
                <a href="ajouter_panierCha2.php?id=<?=$row['id']?>" class="id_product">Ajouter au panier</a>
                </p>
                 
            </button>
            <?php }?>
         </div>
            <h3 class="titre">DESSERT</h3>
            <div class="sec">
            <?php 
        //inclure la page de connexion
        include_once "con_dbb2.php";
        //afficher la liste des produits
         $req = mysqli_query($con, "SELECT * FROM dessert");
         while( $row =mysqli_fetch_assoc($req)){
        ?>
            <button> 
                <img src="Plancha/<?=$row['img'] ?>" alt="">
                <h3>
                    <p class="menu"> <?=$row['name'] ?></p>
                </h3>
                <p class="Acc"></p>
                <p class="Prix"><b><?=$row['price'] ?></b></p>
                <a href="ajouter_panierCha3.php?id=<?=$row['id']?>" class="id_product">Ajouter au panier</a>
                </p>
                 
            </button>
            <?php }?>
        </div>

        </div>

    </main>
    <div class="end">
        <p><button class="commander"><b>Commander</b></button></p>
    </div>
    <footer>
        <div class="foot">

            <div class="pu"><i class="fa-solid fa-utensils"></i> Proposez un restaurant</div>
            <div class="pu"><i class="fa-solid fa-handshake-angle"></i> Devenir un partenaire</div>
            <div class="pu">Mentions légales</div>
            <div class="pu">Contact</div>
            <img src="images/logo/ohmyfood.png" alt="" width="100" height="20">

        </div>
    </footer>
</body>

</html>