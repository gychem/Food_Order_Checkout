<?php

declare(strict_types=1); // This line makes PHP behave in a more strict way

// We are going to use session variables so we need to enable sessions
session_start();

// Use this function when you need to need an overview of these variables
function whatIsHappening() {
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
//print_r('<br>-------------------------------------------------');

// TODO: provide some products (you may overwrite the example)
$products = [
    ['name' => 'BlazeBass Audio Drum Kit', 'price' => 11.99],
    ['name' => 'BlazeBass Audio FX Effects', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Cinematic Pads', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Vocal Leads and Hooks', 'price' => 11.99],
];

$totalValue = 0;
print_r('<br><b>[ERROR]</b>');
function validate()
{
    // TODO: This function will send a list of invalid fields back
    $validateArray = [];
    print_r('<br>');
    if (empty($_POST['email'])) {
        array_push($validateArray, 'Email');
    } else {
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        } else {
            echo "- Email address is invalid ! Enter a valid email address, for example: yourname@website.com<br>";
        }
    }

    if (empty($_POST['street'])) {
        array_push($validateArray, 'Street');
    }
    if (empty($_POST['streetnumber'])) {
        array_push($validateArray, 'Street number'); 
    } else {
        if(is_numeric($_POST['streetnumber'])) {
        } else {
            echo "- Street number is invalid !<br>";
        }
    }
    if (empty($_POST['city'])) {
        array_push($validateArray, 'City'); 
    }
    if (empty($_POST['zipcode'])) {
        array_push($validateArray, 'Zipcode'); 
    } else {
        if(is_numeric($_POST['zipcode'])) {
        } else {
            echo "- Zipcode is invalid !<br>";
        }
    }
    if (empty($_POST['products'])) {
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
    $_SESSION["productName"] = $products[0]['name'] . ' ' . $products[0]['price'];
    print_r('<br><br><b><u>Thanks for placing your order, you can find the order details below.</u></b><br><br>');

    print_r('<b>Email</b> ' . $_POST['email']);
    print_r('<br><b>Street & Housenumber</b> ' . $_POST['street'] . ' ' . $_POST['streetnumber']);
    print_r('<br><b>Zipcode & City</b> ' . $_POST['zipcode'] . ' ' . $_POST['city']);

    $orderedProducts = $_POST['products'];
    $totalPrice = 0;
    print_r('<br><br><b>Products:</b><br>');
    foreach ($orderedProducts as $i => $product): 
        $orderedProduct = $products[$i]['name'] . ' ' . $products[$i]['price'];
        print_r($orderedProduct);
        print_r('<br>');
        $totalPrice = $totalPrice + $products[$i]['price']; 
    endforeach; 

    print_r('<br><b>Total Price</b> ' . $totalPrice);
}

// TODO: replace this if by an actual check
$formSubmitted = false;
if ($formSubmitted) {
    handleForm();
}

require 'form-view.php';