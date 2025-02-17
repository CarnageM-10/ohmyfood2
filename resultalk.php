<?php
session_start();
include_once "con_dbb.php";

// Récupérer la valeur de la variable de session 'total'
if(isset($_SESSION['total1'])) {
    $total1 = $_SESSION['total1'];
} else {
    $total1 = 0;
}
if(isset($_SESSION['total2'])) {
    $total2 = $_SESSION['total2'];
} else {
    $total2 = 0;
}
if(isset($_SESSION['total3'])) {
    $total3 = $_SESSION['total3'];
} else {
    $total3 = 0;
}
$totalSum = $total1 + $total2 + $total3 ;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat</title>
    <link rel="stylesheet" href="panier.css">
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
</head>
<body>
    <h2>Facture</h2>
    <form>
        <label for="plat">Entrée:</label> <?php echo $total1; ?> FCFA
        
        <label for="dessert">Plat:</label> <?php echo $total2; ?> FCFA
        <br>
        <label for="boisson">Dessert:</label> <?php echo $total3; ?> FCFA
        <br>
        <label for="total">Montant total:</label> <?php echo $totalSum; ?> FCFA
        <a href="panieralk.php" class="link">Retourner au panier</a>
    </form>
    
    <style>
        body {
    font-family: Arial, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    color: #333;
    padding: 20px;
    background-color: #f9f9f9;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    width: 500px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    
}

input[type="text"] {
    width: 100%;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 3px;
    box-sizing: border-box;
    background-color: #f9f9f9;
}

.link {
    display: block;
    margin: 20px auto; 
    width: fit-content;
    text-decoration: none;
    background-color: palevioletred;
    color: #fff;
    padding: 10px 25px;
    border-radius: 6px;
    font-size: 14px;
}

@media print {
    body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-width: 100%;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }

    form {
        width: 100%;
        margin: 0;
        padding: 0;
        border: none;
        box-shadow: none;
    }

    label {
        display: inline-block;
        margin-right: 20px;
        margin-bottom: 5px;
        font-weight: normal;
    }

    input[type="text"] {
        display: inline-block;
        width: auto;
        border: none;
        border-radius: 0;
        padding: 0;
        background-color: transparent;
        box-shadow: none;
    }

    .link {
        display: none;
    }
}
    </style>
</body>
</html>
</body>
</html>
