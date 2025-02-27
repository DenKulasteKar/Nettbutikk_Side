<?php
session_start(); // Startar økta

if (isset($_GET['produkt_id'])) { // Sjekkar om produkt_id er sendt via URL
    $produkt_id = $_GET['produkt_id'];

    // Hentar eksisterande handlekorg frå cookie
    $handlekurv = isset($_COOKIE['handlekurv']) ? json_decode($_COOKIE['handlekurv'], true) : [];

    // Fjernar produktet frå handlekorga om det finst
    if (isset($handlekurv[$produkt_id])) {
        unset($handlekurv[$produkt_id]);
    }

    // Dersom handlekorga er tom, slettar me cookien heilt
    if (empty($handlekurv)) {
        setcookie('handlekurv', '', time() - 3600, "/"); // Set cookien til å utløpe i fortida
        unset($_COOKIE['handlekurv']); // Fjernar cookien frå PHP-miljøet
    } else {
        // Dersom det framleis er produkt igjen, oppdaterer me cookien
        $ny_handlekurv = json_encode($handlekurv, JSON_UNESCAPED_UNICODE);
        setcookie('handlekurv', $ny_handlekurv, time() + (86400 * 7), "/"); // Lagre for 7 dagar
        $_COOKIE['handlekurv'] = $ny_handlekurv; // Oppdaterar PHP-miljøet med den nye verdien
    }

    

}

// Hindrar caching slik at nettlesaren lastar inn oppdatert informasjon
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Location: handlekurv_vis.php"); // Sender brukaren tilbake til handlekorga
exit;
?>
