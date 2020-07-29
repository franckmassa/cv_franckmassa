<?php
// On déclare les variables à vide dans un tableau associatif
$array = array("firstname" => "", "name" => "", "email" => "", "phone" => "", "message" => "", 
"firstnameError" => "", "nameError" => "", "emailError" => "", "phoneError" => "", "messageError" => "", $isSuccess => false);

// Déclaration de l'email d'envoi
$emailTo = "massafranck@gmail.com";


// Si on soumet le formulaire, on récupère les données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $array["firstname"] = verifyInput($_POST["firstname"]);
    $array["name"] = verifyInput($_POST["name"]);
    $array["email"] = verifyInput($_POST["email"]);
    $array["phone"] = verifyInput($_POST["phone"]);
    $array["message"] = verifyInput($_POST["message"]);
    $array["isSuccess"] = true;
    // construction de l'email
    $emailText = "";

    // Test pour les champs vides
    if (empty($array["firstname"])) {
        $array["firstnameError"] = "Merci d'indiquer votre prénom";
        $array["isSuccess"] = false;
    } 
    else {
        $emailText .= "Firstname: {$array["firstname"]}\n";
    }

    if (empty($array["name"])) {
        $array["nameError"] = "Merci d'indiquer votre nom";
        $array["isSuccess"] = false;
    }
    else {
        $emailText .= "Name: {$array["name"]}\n";
    }
    if (!isEmail($array["email"])) {
        $array["emailError"] = "Votre adresse email n'est pas valide";
        $array["isSuccess"] = false;
    }
    else {
        $emailText .= "Email: {$array["email"]}\n";
    }
    if (!isPhone($array["phone"])) {
        $array["phoneError"] = "Que des chiffres et des espaces, svp...";
        $array["isSuccess"] = false;
    }
    else {
        $emailText .= "Phone: {$array["phone"]}\n";
    }
    if (empty($array["message"])) {
        $array["messageError"] = "Merci de rédiger votre message ?";
        $array["isSuccess"] = false;
    }
    else {
        $emailText .= "Message: {$array["message"]}\n";
    }
    if($array["isSuccess"]){
        $headers = "From: {$array["firstname"]} {$array["name"]} <{$array["email"]}>\r\<nReply-To: {$array["email"]}";
        mail($emailTo, "Un message de votre site", $emailText, $headers);
    }
    
    // encodage au format json
    echo json_encode($array);
}

// Fonction qui vérifie le numéro de téléphone (chiffres et espaces admis)
function isPhone($var)
{
    return preg_match("/^[0-9 ]*$/", $var);
}


// Fonction qui vérifie si l'email est valide
function isEmail($var)
{
    return filter_var($var, FILTER_VALIDATE_EMAIL);
}

// Fonction qui protège contre les failles XSS
function verifyInput($var)
{
    // trim enlève tous les espaces non nécessaires
    $var = trim($var);
    // stripslashes enlève tous les antislah
    $var = stripslashes($var);
    // htmlspecialchars n'autorise pas l'utilisation des caractères spéciaux d'HTML 
    $var = htmlspecialchars($var);
    return $var;
}
