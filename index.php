<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require the autoload file
require_once('vendor/autoload.php');

//Instantiate the F3 Base class
$f3 = Base::instance();

//Default route
$f3->route('GET /', function () {

    $view = new Template();
    echo $view->render('views/home.html');
});

//Personal Information route
$f3->route('GET|POST /personal-information', function ($f3) {

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);

        //Validate the data
        if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['age']) || !isset($_POST['sex']) || empty($_POST['phone'])) {
            echo "<p>Please fill out all blanks</p>";
        } elseif (!is_numeric($_POST['age'])) {
            echo "<p>Please enter a valid number for age</p>";
        } //Data is valid
        else {
            //Store the data in the session array
            $_SESSION['fname'] = $_POST['fname'];
            $_SESSION['lname'] = $_POST['lname'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['sex'] = $_POST['sex'];
            $_SESSION['phone'] = $_POST['phone'];

            //Redirect to profile page
            $f3->reroute('profile');
            session_destroy();
        }
    }

    $view = new Template();
    echo $view->render('views/pinfo.html');
});

//Profile route
$f3->route('GET /profile', function () {

    $view = new Template();
    echo $view->render('views/profile.html');

});

//Interests route
$f3->route('GET /interests', function () {

    $view = new Template();
    echo $view->render('views/interests.html');

});

//Summary route
$f3->route('GET /summary', function () {

    $view = new Template();
    echo $view->render('views/summary.html');

});

//Run fat free
$f3->run();