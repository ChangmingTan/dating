<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require the autoload file
require_once('vendor/autoload.php');
require_once("model/data-layer.php");

//Instantiate the F3 Base class
$f3 = Base::instance();

//Default route
$f3->route('GET /', function () {

    $view = new Template();
    echo $view->render('views/home.html');
});

//Personal Information route
$f3->route('GET|POST /personal-information', function ($f3) {

    $genders = array("male", "female");

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);

        //Validate the data
        if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['age']) || !in_array($_POST['sex'], $genders) || empty($_POST['phone'])) {
            echo "<p>Please fill out all blanks</p>";
        } elseif (!is_numeric($_POST['age'])) {
            echo "<p>Please enter a valid number for age</p>";
        } //Data is valid
        else {
            //Store the data in the session array
            $_SESSION['fname'] = $_POST['fname'];
            $_SESSION['lname'] = $_POST['lname'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['gender'] = $genders;
            $_SESSION['phone'] = $_POST['phone'];

            //Redirect to profile page
            $f3->reroute('profile');
        }
    }

    $f3->set('genders', $genders);
    $view = new Template();
    echo $view->render('views/pinfo.html');
});

//Profile route
$f3->route('GET|POST /profile', function ($f3) {

    $states = getStates();
    $sexes = array("male", "female");

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);

        //Validate the data
        if (empty($_POST['email']) || !in_array($_POST['state'], $states) || !in_array($_POST['sex'], $sexes) || empty($_POST['biography'])) {
            echo "<p>Please fill out all blanks</p>";
        } //Data is valid
        else {
            //Store the data in the session array
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['state'] = $states;
            $_SESSION['sex'] = $sexes;
            $_SESSION['biography'] = $_POST['biography'];

            //Redirect to Interests page
            $f3->reroute('interests');
        }
    }

    $f3->set('states', $states);
    $f3->set('sexes', $sexes);

    $view = new Template();
    echo $view->render('views/profile.html');
});

//Interests route
$f3->route('GET|POST /interests', function ($f3) {

    $interests = array("tv", "movies", "playing toys", "tug-of-war", "treat hunt", "shell game", "count numbers", "help with chores", "hiking", "swimming", "jogging", "walking", "geocaching", "diving");
    $interestsHalf = array_chunk($interests, 8, true);

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);

        //Store the data in the session array
        $_SESSION['interests'] = $interests;

        //Redirect to Summary page
        $f3->reroute('summary');
    }

    $f3->set('interests', $interests);
    $f3->set('indoors', $interestsHalf[0]);
    $f3->set('outdoors', $interestsHalf[1]);

    $view = new Template();
    echo $view->render('views/interests.html');

});

//Summary route
$f3->route('GET /summary', function () {

    $view = new Template();
    echo $view->render('views/summary.html');

    session_destroy();
});

//Run fat free
$f3->run();