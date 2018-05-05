<?php
require_once 'models/IPGeolocation.php';
$currentLocation = IPLocation::getLocation();

session_start();

if(isset($_REQUEST['logout'])) {
    session_unset();
    session_destroy();
    header('Location:' . $redirectURL);
}

?>
<!DOCTYPE html>
<html ng-app="eventToronto">
    <head>
        <title>Events in Toronto</title>
        <link rel="icon" type="image/png" href="favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="google-signin-client_id" content="433300294368-sp6f150psm0akdkomhn80laqqmd5fukh.apps.googleusercontent.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css" type="text/css" />
        <link rel="stylesheet" href="../style.css" type="text/css" />
    </head>
    <body>
        <nav ng-controller="userController">
            <div class="nav-wrapper">
                <a href="/" class="brand-logo">Toronto Events</a>
                <ul id="nav-mobile" class="right">
                    <li><a href="/">All Events</a></li>
                    <li><a class="g-signin2" data-onsuccess="onSignIn"></a>
                    <!--<li><a href="#!/calendar"></a></li>-->
                    <li><a href="#" onclick="signOut();">Sign out</a></li>
                </ul>
            </div>
        </nav>
        <div class="container">