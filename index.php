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

$products = [
    ['image' => './images/image.png', 'name' => 'Name', 'price' => 0.00, 'type' => 'cat1 or cat2'], // Example -> Don't update this field
    ['image' => './images/pizza-pepperoni.png', 'name' => 'Pizza Pepperoni', 'price' => 12.99, 'type' => 'cat1'],
    ['image' => './images/pizza-parma.png', 'name' => 'Pizza Parma', 'price' => 12.99, 'type' => 'cat1'],
    ['image' => './images/pizza-bbq-chicken.png', 'name' => 'Pizza BBQ Chicken', 'price' => 12.99, 'type' => 'cat1'],
    ['image' => './images/coca-cola.png', 'name' => 'Coca Cola', 'price' => 1.50, 'type' => 'cat2'],
    ['image' => './images/ice-tea.png','name' => 'Ice-Tea', 'price' => 1.50, 'type' => 'cat2'],
];

$activeProductList = 'cat1';

if(isset($_GET['food'])) {
    global $activeProductList;
    $activeProductList = 'cat1';
} elseif(isset($_GET['drinks'])) {
    global $activeProductList;
    $activeProductList = 'cat2';
}

if (empty($_SESSION['email'])) { $_SESSION["email"] = ''; } 
if (empty($_SESSION['name'])) { $_SESSION["name"] = ''; } 
if (empty($_SESSION['street'])) { $_SESSION["street"] = ''; } 
if (empty($_SESSION['streetnumber'])) {$_SESSION["streetnumber"] = ''; } 
if (empty($_SESSION['city'])) { $_SESSION["city"] = ''; } 
if (empty($_SESSION['zipcode'])) { $_SESSION["zipcode"] = ''; } 
if (empty($_SESSION['ordercart'])) { $_SESSION["ordercart"] = []; } 

function validate()
{
    global $products;
    $validateArray = [];
    $fields = ['name', 'email', 'street', 'streetnumber', 'city', 'zipcode'];

    ///////////////////////////////////////////////////////////////////////////////: BASILE
    // foreach($_POST as $key => $inputValue) {
    //     if($key === "email" && !filter_var($inputValue, FILTER_VALIDATE_EMAIL)) {
    //         $result[] = ["name" => $key, "error" => "E-mail address is invalid !"];
    //     } else if($key === "zipcode" && !is_numeric($inputValue)) {
    //         $result[] = ["name" => $key, "error" => "Zipcode can only be numbers"];
    //     } else if($inputValue === "") {
    //         $result[] = ["name" => $key, "error" => "Empty field spotted"];
    //     }
    // }
    ////////////////////////////////////////////////////////////////////////

    foreach ($fields as $i => $field) {
        if (empty($_POST[$field])) { // Check if field is empty
            array_push($validateArray, $field);
        } else {
            if($field == 'email') {
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION["email"] = $_POST['email'];
                } else {
                    $message = "Please enter a valid e-mail address!";
                    $fieldError = "Invalid E-mail !";
                    showError($message,$fieldError,'Email');
                }
            } else if($field == 'streetnumber') {
                if(is_numeric($_POST['streetnumber'])) {
                    $_SESSION["streetnumber"] = $_POST['streetnumber'];
                } else {
                    $message =  "Please enter a valid street number!";
                    $fieldError = "Invalid Streetnumber !";
                    showError($message,$fieldError,'Streetnumber');
                }
            } else if($field == 'zipcode') {
                if(is_numeric($_POST['zipcode'])) {
                    $_SESSION["zipcode"] = $_POST['zipcode'];
                } else {
                    $message =  "Please enter a valid zipcode!";
                    $fieldError = "Invalid Zipcode !";
                    showError($message,$fieldError,'Zipcode');
                }
            } else {
               $_SESSION[$field] = $_POST[$field];
            }
        }
    }

    if(empty($_SESSION['ordercart'])) {
        array_push($validateArray, 'products');
    } 
    
    if(!empty($validateArray)) {
        return $validateArray;
    }
}

function showError($message, $fieldError, $fieldName) {
    $formError = true; $errorActive = 'errorActive'; $errorAlert = 'errorAlert';
    global $errorMessage; $errorMessage = '<div class="alert alert-danger mt-2" role="alert">' . $message . '</div>';  
    global ${$errorActive . $fieldName}; ${$errorActive . $fieldName} = true;
    global ${$errorAlert . $fieldName}; ${$errorAlert . $fieldName} = $fieldError;
    global $formError; $formError = true;
}

function handleForm()
{
    $invalidFields = validate(); 
    global $formError; global $products; 

    if (!empty($invalidFields)) // TODO: handle errors
    {
        $emptyFields = '';
        $max = max(array_keys($invalidFields));

        for ($i=0; $i <= $max ; $i++) { $emptyFields .= $invalidFields[$i] . ', '; }
        
        $message = 'Form is incomplete! Missing Field(s): '. $emptyFields;  
        showError($message, 'none', 'none');
    } 
    else if($formError == false)
    {
        loadOrder($products);
    }
}

function updateOrder($orderMessage, $orderDelivery, $totalPrice, $orderPayed) 
{
    $orderMessage = $orderMessage;
    global $orderDetails; $orderDetails = $_SESSION['orderDetails'];
    global $orderProducts; $orderProducts = '<ul class="list-group mt-1">' . $_SESSION['productsList'] . '</ul>';
    $orderDelivery; $orderDelivery = $orderDelivery;
    global $orderTotalPrice;  $orderTotalPrice = '<ul class="list-group mt-1"><li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary">
    Total Price <span class="badge rounded-pill bg-success text-white">€'. $totalPrice .'</span></li></ul>';
    
    if($orderPayed == true) { 
        session_destroy(); 
    } else {
        global $orderPay; $orderPay = '<form method="post"><button type="submit" name="payorder" class="btn btn-success w-100 mt-1 mb-4">Pay Order</button></form>';
    }

    ?><script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href ); }</script><?php
}

function loadOrder($products) {
    $detailsName = '<span class="lead">Name</span> ' . $_POST['name'];
    $detailsEmail = ' <span class="lead">Email</span> ' . $_POST['email'];
    $detailsAdres = '<br><span class="lead">Street</span> ' . $_POST['street'] . ' <span class="lead">Nr</span> ' . $_POST['streetnumber'] . '<br><span class="lead">Zipcode</span> ' . $_POST['zipcode'] . ' <span class="lead">City</span> ' . $_POST['city'];
    $orderDetails = '<div class="card mt-2"><div class="card-body">'. $detailsName .  $detailsEmail . $detailsAdres . '</div></div>';

    $totalPrice = 0;
    $productsList = '';

    foreach ($_SESSION['ordercart'] as $i => $product) {
        $productTotalPrice = $product['price'] * $product['quantity'];
        $productTotalPriceFormat = number_format($productTotalPrice, 2);
  
        $orderedProduct = '
        <li class="list-group-item d-flex justify-content-between align-items-center">'
        . $product['quantity'] . ' x '. $product['name'] . ' €' . $product['price'] . '<span class="badge rounded-pill bg-success text-white">€'. $productTotalPriceFormat .'</span>
        </li>';
       
        $productsList .= $orderedProduct;
        $totalPrice; $totalPrice = (float)$totalPrice + (float)$productTotalPrice; 
        $totalPrice = number_format($totalPrice, 2);
    }


    $_SESSION['totalPrice'] = $totalPrice;
    $_SESSION['orderDetails'] = $orderDetails;
    $_SESSION['productsList'] = $productsList;

    global $orderMessage; $orderMessage = '<div class="alert alert-warning" role="alert">Order In Progress</div>';
    $deliveryMethod = '30 Minutes - Express Delivery €5,00';
    $deliveryButton = '<form method="post"><button type="submit" name="expressdelivery" class="btn btn-success mt-1">Add express delivery in 30 min for €5,00</button></form>';
    global $orderDelivery; $orderDelivery = '<div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                    <div><b>Delivery</b> '.$deliveryMethod.'</div>'.$deliveryButton.'</div></div>';
                    
    updateOrder($orderMessage, $orderDelivery, $totalPrice, false);
}

$formSubmitted = false;
if (isset($_POST["order"])) {
    handleForm();
}

if (isset($_POST["expressdelivery"])) {
    $totalPrice = (float)$_SESSION['totalPrice'] + 5;
    $_SESSION['totalPrice'] = $totalPrice;
    $totalPrice = number_format($totalPrice, 2);

    $orderMessage = '<div class="alert alert-warning" role="alert">Order In Progress</div>';
    $deliveryMethod = '<b>Delivery</b> 30 Minutes - Express Delivery €5,00';
    $deliveryButton = '<form method="post"><button type="submit" name="freedelivery" class="btn btn-warning">Remove Express Delivery</button></form>';
    $orderDelivery = '<div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                      <div>'.$deliveryMethod.'</div>'.$deliveryButton.'</div></div>';

    updateOrder($orderMessage, $orderDelivery, $totalPrice, false);
}

if (isset($_POST["freedelivery"])) {
    $totalPrice = (float)$_SESSION['totalPrice'] - 5;
    $_SESSION['totalPrice'] = $totalPrice;
    $totalPrice = number_format($totalPrice, 2);
    
    $orderMessage = '<div class="alert alert-warning" role="alert">Order In Progress</div>';
    $deliveryMethod = '<b>Delivery</b> 1 Hour - Regular Delivery FREE';
    $deliveryButton = '<form method="post"><button type="submit" name="expressdelivery" class="btn btn-success mt-1">Add express delivery in 30 min for €5,00</button></form>';
    $orderDelivery = '<div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                    <div>'.$deliveryMethod.'</div>'.$deliveryButton.'</div></div>';

    updateOrder($orderMessage, $orderDelivery, $totalPrice, false);
}

if (isset($_POST["payorder"])) {
    $totalPrice = number_format((float)$_SESSION['totalPrice'], 2);
    $orderMessage = '<div class="alert alert-success" role="alert">Order Has Been Placed</div>';
    $deliveryMethod = '<b>Delivery</b> 1 Hour - Regular Delivery FREE';
    $deliveryButton = '';
    $orderDelivery = '<div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                      <div>'.$deliveryMethod.'</div>'.$deliveryButton.'</div></div>';

    updateOrder($orderMessage, $orderDelivery, $totalPrice, true);
}

if (isset($_POST["addToCart"])) {
    $index = $_POST['addToCart'];
    addToCart($index);  
}

function addToCart($index) {

    global $products; 
    $duplicate = false;

    foreach($_SESSION['ordercart'] as $i => $product) {
        if($products[$index]['name'] == $product['name']) { 
            $duplicate = true;
            $_SESSION['ordercart'][$i]['quantity'] += 1;
        } 
    }

    if($duplicate == false) {
        $products[$index]['quantity'] = 1;
        $productAddCart = ['name' => $products[$index]['name'], 'price' => $products[$index]['price'], 'quantity' => $products[$index]['quantity']];
        array_push($_SESSION['ordercart'], $productAddCart);
    }
}


if (isset($_POST["deleteFromCart"])) {
    $index = $_POST['deleteFromCart'];
    unset($_SESSION['ordercart'][$index]);  
}

if (isset($_POST["quantityMinus"])) {
    $index = $_POST['quantityMinus'];

    if(!$_SESSION['ordercart'][$index]['quantity'] <= 0) {
        $_SESSION['ordercart'][$index]['quantity'] -= 1;
    }
}

if (isset($_POST["quantityPlus"])) {
    $index = $_POST['quantityPlus'];

    $_SESSION['ordercart'][$index]['quantity'] += 1;
}

require 'form-view.php';