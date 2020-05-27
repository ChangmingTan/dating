<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require the autoload file
require_once('vendor/autoload.php');
require_once("model/data-layer.php");
require_once("model/validate.php");

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
        if (!validName($_POST['fname'])) {
            //Set an error variable in the F3 hive
            $f3->set('errors["fname"]', "Invalid first name");
        }

        if (!validName($_POST['lname'])) {
            //Set an error variable in the F3 hive
            $f3->set('errors["lname"]', "Invalid last name");
        }

        if (!validAge($_POST['age'])) {
            //Set an error variable in the F3 hive
            $f3->set('errors["age"]', "Invalid age range, should be between 18 and 118");
        }

        if (!validPhone($_POST['phone'])) {
            //Set an error variable in the F3 hive
            $f3->set('errors["phone"]', "A valid phone number contains numbers between 0 and 9, 10 digits, with no punctuation");
        }
        //Data is valid
        if (empty($f3->get('errors'))) {

            //Store the data in the session array
            $_SESSION['fname'] = $_POST['fname'];
            $_SESSION['lname'] = $_POST['lname'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['gender'] = $_POST['gender'];
            $_SESSION['phone'] = $_POST['phone'];

            //Redirect to profile page
            $f3->reroute('profile');
        }
    }

    $f3->set('genders', getGender());
    $f3->set('fname', $_POST['fname']);
    $f3->set('lname', $_POST['lname']);
    $f3->set('age', $_POST['age']);
    $f3->set('selectedGender', $_POST['gender']);
    $f3->set('phone', $_POST['phone']);

    $view = new Template();
    echo $view->render('views/pinfo.html');
});

//Profile route
$f3->route('GET|POST /profile', function ($f3) {

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);

        //Validate the data
        if (!validEmail($_POST['email'])) {
            //Set an error variable in the F3 hive
            $f3->set('errors["email"]', "Invalid email address");
        }

        if ($_POST['state'] == '---Please Select---') {
            //Set an error variable in the F3 hive
            $f3->set('errors["state"]', "Please select your state");
        }

        //Data is valid
        if (empty($f3->get('errors'))) {

            //Store the data in the session array
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['state'] = $_POST['state'];
            $_SESSION['sex'] = $_POST['sex'];
            $_SESSION['biography'] = $_POST['biography'];

            //Redirect to Interests page
            $f3->reroute('interests');
        }
    }

    $f3->set('email', $_POST['email']);
    $f3->set('states', getStates());
    $f3->set('selectedState', $_POST['state']);
    $f3->set('sexes', getSex());
    $f3->set('selectedSex', $_POST['sex']);
    $f3->set('biography', $_POST['biography']);

    $view = new Template();
    echo $view->render('views/profile.html');
});

//Interests route
$f3->route('GET|POST /interests', function ($f3) {

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST);

        $indoors = $_POST['indoor'];
        $outdoors = $_POST['outdoor'];

        //Validate the data
        if (isset($indoors)) {
            foreach ($indoors as $indoor) {

                $f3->set('selectedIndoor', $indoor);

                if (!validIndoor($indoor)) {

                    //Set an error variable in the F3 hive
                    $f3->set('errors["indoor"]', "Invalid indoor interest");
                }
            }
        }

        if (isset($outdoors)) {
            foreach ($outdoors as $outdoor) {

                $f3->set('selectedOutdoor', $outdoor);

                if (!validOutdoor($outdoor)) {

                    //Set an error variable in the F3 hive
                    $f3->set('errors["outdoor"]', "Invalid outdoor interest");
                }
            }
        }

        //Data is valid
        if (empty($f3->get('errors'))) {

            //Store the data in the session array
            $_SESSION['indoor'] = $indoors;
            $_SESSION['outdoor'] = $outdoors;

            //Redirect to Summary page
            $f3->reroute('summary');
        }
    }

    $f3->set('indoors', getIndoor());
    $f3->set('outdoors', getOutdoor());

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