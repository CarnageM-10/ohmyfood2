<?php
//inclure la page de connexion
 include_once "con_dbb4.php";
 //verifier si une session existe
 if(!isset($_SESSION)){
    //si non demarer la session
    session_start();
 }
 //creer la session
 if(!isset($_SESSION['panier4'])){
    //s'il n'existe pas une session on créer une et on mets un tableau a l'intérieur
    $_SESSION['panier4'] = array();
 }
 //récupération de l'id dans le lien
 if(isset($_GET['id'])){// si un id est envoyé alors:
    $id = $_GET['id'];
    //verifier grace a l'id si le produit existe dans la bd
    $produit =  mysqli_query($con, "SELECT * FROM dessert WHERE id = $id");
    if(empty(mysqli_fetch_assoc($produit))){
        //si ce produit n'existe pas
        die("Ce produit n'existe pas");
    }
    //ajouter le produit dans le panier (tableau)

    if(isset($_SESSION['panier4'][$id])){//si le produit est dans le panier
        $_SESSION['panier4'][$id]++; //représente la quantité
    }else {
        //si non on ajoute le panier
        $_SESSION['panier4'][$id]=1;   
    }
//redirection vers la page detail.php
    
 }
 header("Location:lagon.php");
?>