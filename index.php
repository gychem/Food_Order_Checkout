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
    ['name' => 'Pizza Hawai', 'price' => 12.99, 'type' => 'cat1 or cat2'], // Example -> Don't update this field
    ['name' => 'Pizza Pepperoni', 'price' => 12.99, 'type' => 'cat1'],
    ['name' => 'Pizza Parma', 'price' => 12.99, 'type' => 'cat1'],
    ['name' => 'Pizza BBQ Chicken', 'price' => 12.99, 'type' => 'cat1'],
    ['name' => 'Coca Cola', 'price' => 1.50, 'type' => 'cat2'],
    ['name' => 'Ice-Tea', 'price' => 1.50, 'type' => 'cat2'],
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

$activeProductList = 'cat1';

if(isset($_GET['food'])) {
    global $activeProductList;
    $activeProductList = 'cat1';
} elseif(isset($_GET['drinks'])) {
    global $activeProductList;
    $activeProductList = 'cat2';
}



if (empty($_SESSION['email'])) {
    $_SESSION["email"] = '';
} 
if (empty($_SESSION['name'])) {
    $_SESSION["name"] = '';
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
    global $formError;
    global $products;
    $validateArray = [];

    $fields = ['name', 'email', 'street', 'streetnumber', 'city', 'zipcode', 'products'];
    
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
            } else if($field == 'products') {
                $firstIndex = reset($_POST['products']);
                $cat =  $products[$firstIndex]['type'];
                $sessionName = 'products'. $cat;
                $_SESSION[$sessionName] = $_POST['products'];  
            }
            else {
               $_SESSION[$field] = $_POST[$field];
            }
        }
    }
    if(!empty($validateArray) ) { return $validateArray; }
}

function showError($message, $fieldError, $fieldName) {
    $formError = true; $errorActive = 'errorActive'; $errorAlert = 'errorAlert';
    global $errorMessage; $errorMessage = '<div class="alert alert-danger" role="alert">' . $message . '</div>';  
    global ${$errorActive . $fieldName}; ${$errorActive . $fieldName} = true;
    global ${$errorAlert . $fieldName}; ${$errorAlert . $fieldName} = $fieldError;
}

function handleForm()
{
    $invalidFields = validate(); 
    global $formError;
    
    if (!empty($invalidFields)) // TODO: handle errors
    {
        $emptyFields = '';
        $max = max(array_keys($invalidFields));

        for ($i=0; $i <= $max ; $i++) { 
            $emptyFields .= $invalidFields[$i] . ', ';
        }
        $message = 'Form is incomplete! Missing Field(s): '. $emptyFields;  
        showError($message);
    } 
    else if($formError == false)
    {
        global $products;  
        loadOrder($products);
    }
}

function loadOrder($products) {
    $thankMessage = '<div class="alert alert-warning" role="alert">Order In Progress</div>';
    $detailsName = '<span class="lead">Name</span> ' . $_POST['name'];
    $detailsEmail = ' <span class="lead">Email</span> ' . $_POST['email'];
    $detailsAdres = '<br><span class="lead">Street</span> ' . $_POST['street'] . ' <span class="lead">Nr</span> ' . $_POST['streetnumber'] . '<br><span class="lead">Zipcode</span> ' . $_POST['zipcode'] . ' <span class="lead">City</span> ' . $_POST['city'];
    $orderedProducts = $_POST['products'];
    $totalPrice = 0;
    $productsList = '';
 
    foreach ($orderedProducts as $i => $product) {
        $productTotalPrice = $products[$i]['price'] * $_POST['quantity'][$i];
        
        $productTotalPriceFormat = number_format($productTotalPrice, 2, ',', ' ');
  
        $orderedProduct = '
        <li class="list-group-item d-flex justify-content-between align-items-center">'
        . $_POST['quantity'][$i] . ' x '. $products[$i]['name'] . ' €' . $products[$i]['price'] . '<span class="badge rounded-pill bg-success text-white">€'. $productTotalPriceFormat .'</span>
        </li>';
       
        $productsList .= $orderedProduct;
        global $totalPrice;
        $totalPrice = $totalPrice + $productTotalPrice; 
       
        $totalPrice =  number_format($totalPrice, 2);

    }

    $deliveryTime = '
                    <div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                    <div><b>Delivery Time</b> 1 Hour - Regular Delivery FREE<br></div>
                    <form method="post"><button type="submit" name="expressdelivery" class="btn btn-success">Add express delivery in 30 min for €5,00</button></form>
                    </div></div>';

    $detailsTotalPrice = '
    <ul class="list-group mt-1"><li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary">
    Total Price <span class="badge rounded-pill bg-success text-white">€'. $totalPrice .'</span>
    </li></ul>
    <form method="post"><button type="submit" name="payorder" class="btn btn-success w-100 mt-1 mb-4">Pay Order</button></form>
    ';

    $orderDetails = 
    '<div class="card mt-2"><div class="card-body">'. $detailsName .  $detailsEmail . $detailsAdres . '</div></div>' .
    '<ul class="list-group mt-1">' . $productsList . '</ul>';
        
    global $orderContent;
    $orderContent =  $thankMessage . $orderDetails . $deliveryTime . $detailsTotalPrice;

    $_SESSION['totalPrice'] = $totalPrice;
    $_SESSION['orderDetails'] = $orderDetails;
git }

$formSubmitted = false;
if (isset($_POST["order"])) {
    handleForm();
}

if (isset($_POST["expressdelivery"])) {
    $thankMessage = '<div class="alert alert-warning" role="alert">Order In Progress</div>';
    $totalPrice = $_SESSION['totalPrice'] + 5;
    $_SESSION['totalPrice'] = $totalPrice;
    $totalPrice = number_format($totalPrice, 2);

    $detailsTotalPrice = '
    <ul class="list-group mt-1"><li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary">
    Total Price <span class="badge rounded-pill bg-success text-white">€'. $totalPrice .'</span>
    </li></ul>
    <form method="post"><button type="submit" name="payorder" class="btn btn-success w-100 mt-1 mb-4">Pay Order</button></form>
    ';

    $deliveryTime = '
    <div class="card mt-2"><div class="card-body d-flex justify-content-between">
    <div class="align-self-center"><b>Delivery Time</b> 30 Minutes - Express Delivery €5,00</div>
    <form method="post"><button type="submit" name="freedelivery" class="btn btn-warning">Remove Express Delivery</button></form>
    </div></div>';

    global $thankMessage;
    $orderContent = $thankMessage . $_SESSION['orderDetails'] . '' . $deliveryTime . $detailsTotalPrice;

    ?>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
<?php
}

if (isset($_POST["freedelivery"])) {
    $thankMessage = '<div class="alert alert-warning" role="alert">Order In Progress</div>';
    $totalPrice = $_SESSION['totalPrice'] - 5;
    $_SESSION['totalPrice'] = $totalPrice;
    $totalPrice = number_format($totalPrice, 2);
    
    $detailsTotalPrice = '
    <ul class="list-group mt-1"><li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary">
    Total Price <span class="badge rounded-pill bg-success text-white">€'. $totalPrice .'</span>
    </li></ul>
    <form method="post"><button type="submit" name="payorder" class="btn btn-success w-100 mt-1 mb-4">Pay Order</button></form>
    ';

    $deliveryTime = '
                    <div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                    <div><b>Delivery Time</b> 1 Hour - Regular Delivery FREE<br></div>
                    <form method="post">
                    <button type="submit" name="expressdelivery" class="btn btn-success mt-1">Add express delivery in 30 min for €5,00</button>
                    </form>
                    </div></div>';

    $orderContent = $thankMessage . $_SESSION['orderDetails'] . '' . $deliveryTime . $detailsTotalPrice;

    ?>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
<?php
}

if (isset($_POST["payorder"])) {
    $totalPrice = $_SESSION['totalPrice'];
    $totalPrice = (float)$totalPrice;
    $totalPrice = number_format($totalPrice, 2);
    $thankMessage = '<div class="alert alert-success" role="alert">Order Has Been Placed</div>';
    $detailsTotalPrice = '
    <ul class="list-group mt-1"><li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary">
    Total Price <span class="badge rounded-pill bg-success text-white">€'. $totalPrice .'</span>
    </li></ul>
    ';

    $deliveryTime = '
                    <div class="card mt-2"><div class="card-body d-flex justify-content-between align-items-center">
                    <div><b>Delivery Time</b> 1 Hour - Regular Delivery FREE<br></div>
                    </div></div>';

    $orderContent = $thankMessage . $_SESSION['orderDetails'] . '' . $deliveryTime . $detailsTotalPrice;

    ?>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
<?php
}


require 'form-view.php';