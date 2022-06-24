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
    ['name' => 'BlazeBass Audio Drum Kit', 'price' => 11.99],
    ['name' => 'BlazeBass Audio FX Effects', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Cinematic Pads', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Vocal Leads and Hooks', 'price' => 11.99],
];

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

function validate()
{
    $validateArray = [];
    print_r('<br><b>[ERROR]</b>');
    print_r('<br>');

    if (empty($_POST['email'])) { // Email Validation
        array_push($validateArray, 'Email');
    } else {
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["email"] = $_POST['email'];
        } else {
            echo "- Email address is invalid ! Enter a valid email address, for example: yourname@website.com<br>";
        }
    }

    if (empty($_POST['street'])) { // Street Validation
        array_push($validateArray, 'Street');
    } else {
        $_SESSION["street"] = $_POST['street'];
    }

    if (empty($_POST['streetnumber'])) { // Streetnumber Validation
        array_push($validateArray, 'Street number'); 
    } else {
        if(is_numeric($_POST['streetnumber'])) {
            $_SESSION["streetnumber"] = $_POST['streetnumber'];
        } else {
            echo "- Street number is invalid !<br>";
        }
    }
    if (empty($_POST['city'])) { // City Validation
        array_push($validateArray, 'City'); 
    } else {
        $_SESSION["city"] = $_POST['city'];
    }

    if (empty($_POST['zipcode'])) { // Zipcode Validation
        array_push($validateArray, 'Zipcode'); 
    } else {
        if(is_numeric($_POST['zipcode'])) {
            $_SESSION["zipcode"] = $_POST['zipcode'];
        } else {
            echo "- Zipcode is invalid !<br>";
        }
    }

    if (empty($_POST['products'])) { // Products Validation
        array_push($validateArray, 'Products'); 
    }
    if(!empty($validateArray)) {
        return $validateArray;
    }
    
}

if(isset($_POST["order"])) {
    handleForm();
}

function handleForm()
{
    $invalidFields = validate(); 

    if (!empty($invalidFields)) // TODO: handle errors
    {
        $emptyFields = '';
        $max = max(array_keys($invalidFields));

        for ($i=0; $i < $max ; $i++) { 
            $emptyFields .= $invalidFields[$i] . ', ';
        }
        print_r('- Form is not complete! Missing: '. $emptyFields);
    } 
    else 
    {
        $products = [
            ['name' => 'BlazeBass Audio Drum Kit', 'price' => 11.99],
            ['name' => 'BlazeBass Audio FX Effects', 'price' => 11.99],
            ['name' => 'BlazeBass Audio Cinematic Pads', 'price' => 11.99],
            ['name' => 'BlazeBass Audio Vocal Leads and Hooks', 'price' => 11.99],
        ];
        loadOrder($products);
    }
}

function loadOrder($products) {


    //$_SESSION["productName"] = $products[0]['name'] . ' ' . $products[0]['price'];
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

// TODO: replace this if by an actual check
$formSubmitted = false;
if ($formSubmitted) {
    handleForm();
}

require 'form-view.php';