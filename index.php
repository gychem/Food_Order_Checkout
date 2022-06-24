<?php
declare(strict_types=1); // This line makes PHP behave in a more strict way
session_start();

function whatIsHappening() { // Use this function when you need to need an overview of these variables
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}
//whatIsHappening();

$products = [
    ['name' => 'Product Name', 'price' => 29.99, 'type' => 'cat1 or cat2'], // Example -> Don't update this field
    ['name' => 'Saturday only ticket', 'price' => 29.99, 'type' => 'cat1'],
    ['name' => 'Sunday only ticket', 'price' => 29.99, 'type' => 'cat1'],
    ['name' => 'Saturday & sunday full weekend ticket', 'price' => 49.99, 'type' => 'cat1'],
    ['name' => 'Camping ticket', 'price' => 11.99, 'type' => 'cat2'],
    ['name' => 'Parking ticket', 'price' => 1.99, 'type' => 'cat2'],
];
$activeProductList = 'cat1';

if(isset($_GET['daytickets'])) {
    $activeProductList = 'cat1';
} elseif(isset($_GET['optionaltickets'])) {
    $activeProductList = 'cat2';
}


if (empty($_SESSION['email'])) {
    $_SESSION["email"] = '';
} 
if (empty($_SESSION['street'])) {
    $_SESSION["street"] = '';
} 
if (empty($_SESSION['streetnumber'])) {
    $_SESSION["streetnumber"] = '';
} 
if (empty($_SESSION['city'])) {
    $_SESSION["city"] = '';
} 
if (empty($_SESSION['zipcode'])) {
    $_SESSION["zipcode"] = '';
} 

$totalValue = 0;

function formError() 
{
    print_r('<br><b>[ERROR]</b>');
    print_r('<br>');
}


function validate()
{
    $products = [
        ['name' => 'Product Name', 'price' => 29.99, 'type' => 'cat1 or cat2'], // Example -> Don't update this field
        ['name' => 'Saturday only ticket', 'price' => 29.99, 'type' => 'cat1'],
        ['name' => 'Sunday only ticket', 'price' => 29.99, 'type' => 'cat1'],
        ['name' => 'Saturday & sunday full weekend ticket', 'price' => 49.99, 'type' => 'cat1'],
        ['name' => 'Camping ticket', 'price' => 11.99, 'type' => 'cat2'],
        ['name' => 'Parking ticket', 'price' => 1.99, 'type' => 'cat2'],
    ];

    $validateArray = [];
    $formError = false;

    if (empty($_POST['email'])) { // Email Validation
        array_push($validateArray, 'Email');
        $formError = true;
    } else {
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["email"] = $_POST['email'];
        } else {
            echo "- Email address is invalid ! Enter a valid email address, for example: yourname@website.com<br>";
            $formError = true;
        }
    }

    if (empty($_POST['street'])) { // Street Validation
        array_push($validateArray, 'Street');
        $formError = true;
    } else {
        $_SESSION["street"] = $_POST['street'];
    }

    if (empty($_POST['streetnumber'])) { // Streetnumber Validation
        array_push($validateArray, 'Street number'); 
        $formError = true;
    } else {
        if(is_numeric($_POST['streetnumber'])) {
            $_SESSION["streetnumber"] = $_POST['streetnumber'];
        } else {
            echo "- Street number is invalid !<br>";
            $formError = true;
        }
    }

    if (empty($_POST['city'])) { // City Validation
        array_push($validateArray, 'City'); 
        $formError = true;
    } else {
        $_SESSION["city"] = $_POST['city'];
    }

    if (empty($_POST['zipcode'])) { // Zipcode Validation
        array_push($validateArray, 'Zipcode'); 
        $formError = true;
    } else {
        if(is_numeric($_POST['zipcode'])) {
            $_SESSION["zipcode"] = $_POST['zipcode'];
        } else {
            echo "- Zipcode is invalid !<br>";
            $formError = true;
        }
    }

    if (empty($_POST['products'])) { // Products Validation
        array_push($validateArray, 'Products'); 
        $formError = true;
    } else {
        $firstIndex = reset($_POST['products']);
        $cat =  $products[$firstIndex]['type'];
        $sessionName = 'products'. $cat;
        $_SESSION[$sessionName] = $_POST['products'];  
    }

    if($formError == true) {
        formError();
    }

    if(!empty($validateArray)) {
        return $validateArray;
    }
}

function handleForm()
{
    $invalidFields = validate(); 

    if (!empty($invalidFields)) // TODO: handle errors
    {
        $emptyFields = '';
        $max = max(array_keys($invalidFields));

        for ($i=0; $i <= $max ; $i++) { 
            $emptyFields .= $invalidFields[$i] . ', ';
        }
        print_r('- Form is not complete! Missing: '. $emptyFields);
    } 
    else 
    {
        $products = [
            ['name' => 'Product Name', 'price' => 29.99, 'type' => 'cat1 or cat2'], // Example -> Don't update this field
            ['name' => 'Saturday only ticket', 'price' => 29.99, 'type' => 'cat1'],
            ['name' => 'Sunday only ticket', 'price' => 29.99, 'type' => 'cat1'],
            ['name' => 'Saturday & sunday full weekend ticket', 'price' => 49.99, 'type' => 'cat1'],
            ['name' => 'Camping ticket', 'price' => 11.99, 'type' => 'cat2'],
            ['name' => 'Parking ticket', 'price' => 1.99, 'type' => 'cat2'],
        ];
        
        loadOrder($products);
    }
}

function loadOrder($products) {
    print_r('<br><br><b><u>Thanks for placing your order, you can find the order details below.</u></b><br><br>');
    print_r('<b>Email</b> ' . $_POST['email']);
    print_r('<br><b>Street & Housenumber</b> ' . $_POST['street'] . ' ' . $_POST['streetnumber']);
    print_r('<br><b>Zipcode & City</b> ' . $_POST['zipcode'] . ' ' . $_POST['city']);

    $orderedProducts = $_POST['products'];

    $totalPrice = 0;
    print_r('<br><br><b>Products:</b><br>');
    foreach ($orderedProducts as $i => $product): 
        $productTotalPrice = $products[$i]['price'] * $_POST['quantity'][$i];
        $orderedProduct = $products[$i]['name'] . ' €' . $products[$i]['price'] . ' [Quantity ' . $_POST['quantity'][$i] . '] [Total Price: ' . $productTotalPrice . ']';
        print_r($orderedProduct);
        print_r('<br>');
        $totalPrice = $totalPrice + $productTotalPrice; 
    endforeach; 

    print_r('<br><b>Total Price</b> ' . $totalPrice);
}

$formSubmitted = false;
if (isset($_POST["order"])) {
    handleForm();
}

require 'form-view.php';