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

whatIsHappening();

// TODO: provide some products (you may overwrite the example)
$products = [
    ['name' => 'BlazeBass Audio Drum Kit', 'price' => 11.99],
    ['name' => 'BlazeBass Audio FX Effects', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Cinematic Pads', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Vocal Leads and Hooks', 'price' => 11.99],
];

$totalValue = 0;

function validate()
{
    // TODO: This function will send a list of invalid fields back
    return [];
}

if(isset($_POST["order"])) {
    handleForm();
}

function handleForm()
{
    // TODO: form related tasks (step 1)
    
    // Validation (step 2)
    $invalidFields = validate();
    if (!empty($invalidFields)) {
        // TODO: handle errors
    } else {
// TODO: provide some products (you may overwrite the example)
$products = [
    ['name' => 'BlazeBass Audio Drum Kit', 'price' => 11.99],
    ['name' => 'BlazeBass Audio FX Effects', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Cinematic Pads', 'price' => 11.99],
    ['name' => 'BlazeBass Audio Vocal Leads and Hooks', 'price' => 11.99],
];

        // foreach ($products as $i => $product):

            //  var_dump($products);
               // <?= is equal to <?php echo
                // <input type="checkbox" value="1" name="products[<?php echo $i echo $product['name'] 
                //  &euro; <?= number_format($product['price'], 2)
        // endforeach;

        print_r('Thanks for placing your order, you can find the order details below.');
        
        // $_SESSION["productName"] = $products[0]['name'] . ' ' . $products[0]['price'] ;
        // print_r($_SESSION["productName"]);
        var_dump($_POST["products"][2]);

        // $test = $_POST["products[0]"];
        // $test = array_keys($_POST,'5');

        // echo $arraylist["attribute_name"];
        // print_r($test);
        // TODO: handle successful submission
    }
}

// TODO: replace this if by an actual check
$formSubmitted = false;
if ($formSubmitted) {
    handleForm();
}

require 'form-view.php';