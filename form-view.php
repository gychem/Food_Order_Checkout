<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>

    <link rel="stylesheet" href="style.css">
    <title>Ticket Sales</title>
</head>
<body class="bg-secondary bg-gradient pt-4">
    <div class="container">
        <?php 
                global $orderMessage; print_r($orderMessage); 
                global $orderDetails; print_r($orderDetails); 
                global $orderProducts; print_r($orderProducts);
                global $orderDelivery; print_r($orderDelivery);  
                global $orderTotalPrice; print_r($orderTotalPrice);  
                global $orderPay; print_r($orderPay);  
        ?>

        <div class="card mt-4">
            <div class="card-body">

                <nav class="d-flex align-items-center justify-content-between">
                    <div class="btn-group mt-1 w-25" role="group" aria-label="Basic example">
                        <a class="btn btn-secondary" onclick="document.getElementById('orderForm').submit();" href="?food">Food</a>
                        <a class="btn btn-secondary" type="submit" href="?drinks">Drinks</a>
                    </div>
                    <div class="w-75 d-flex justify-content-end">
                        <div class="float-end text-end ms-auto" style="margin-right: 50px">Shopping Cart</div>             
                    </div>
                </nav>

                <form method="post" id="orderForm">
                <fieldset class="d-flex justify-content-between productsContainer">
                    <ul class="list-group mt-2 w-75 products">
                            <?php foreach ($products as $i => $product): ?>
                            
                                <?php if($product['type'] == 'cat1' && $activeProductList == 'cat1') { ?>
                                    <li class="list-group-item d-flex align-items-center justify-content-between"> 
                                        <img src="<?= $product['image'] ?>" width="25px"></img>
                                            <div class="mx-2 align-self-center w-75"><?php echo $product['name'] ?></div>
                                            <span class="badge rounded-pill mx-1 bg-success text-white">&euro;<?= number_format($product['price'], 2) ?></span>
                                            <button type="submit" name="addToCart" value="<?php echo $i ?>" class="btn btn-success mx-2">+</button>
                                    </li>
                                <?php }
                                else if($product['type'] == 'cat2' && $activeProductList == 'cat2') { ?>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <img src="<?= $product['image'] ?>" width="25px"></img>
                                        <div class="mx-2 align-self-center w-75"><?php echo $product['name'] ?></div>
                                        <span class="badge rounded-pill mx-1 bg-success text-white">&euro;<?= number_format($product['price'], 2) ?></span>
                                        <button type="submit" name="addToCart" value="<?php echo $i ?>" class="btn btn-success mx-2">+</button> </li>
                                <?php } ?>
                            </li>
                            <?php endforeach; ?>
                   
                    </ul>
                    <div class=" mt-2 w-50 orderCart">
                        <?php 
                            $cartTotalPrice = 0;

                             foreach ($_SESSION['ordercart'] as $index => $product) {
                                $productToCart = ' <li class="list-group-item text-light bg-secondary d-flex justify-content-between align-items-center cartItem"><div class="d-flex w-75">
                                ' . $product['quantity'] .' x 
                                ' . $product['name'] . ' 
                                </div>
                                <span class="badge rounded-pill bg-success text-white">€' . number_format($product['price'] * $product['quantity'], 2) . '</span>
                                <button type="submit" name="quantityMinus" value="' . $index . '" class="btn btn-warning">-</button>
                                <button type="submit" name="quantityPlus" value="' . $index . '" class="btn btn-success">+</button>
                                <button type="submit" name="deleteFromCart" value="' . $index . '" class="btn btn-danger">X</button>
                                </li>';

                                $cartTotalPrice += number_format($product['price'] * $product['quantity'], 2);

                                print_r($productToCart);
                            }

                            
                            $cartTotalPrice = '<li class="list-group-item text-light bg-dark d-flex justify-content-between align-items-center"><div class="d-flex w-75">  
                                                € '. $cartTotalPrice .'
                                                </li>';


                            print_r($cartTotalPrice);
                        ?>
                    </div>
                </fieldset>

                <?php 
                    global $errorMessage;
                    print_r($errorMessage); 
                ?>
                
                    <fieldset class="mt-2">
                        <div class="form-row">
                            <div class="form-group  col-md-6">
                                <input type="text" placeholder="Name" aria-label="Name" id="name" name="name" class="form-control" value="<?= empty($_POST['name']) ? $_SESSION['name'] : $_POST['name'] ?>"/>
                            </div>
                            <div class="form-group  col-md-6">
                                <input type="text" placeholder="E-mail" aria-label="E-mail" id="email" name="email" class="form-control <?= $errorActiveEmail == true ? 'is-invalid' : '' ?>" value="<?= empty($_POST['email']) ? $_SESSION['email'] : $_POST['email'] ?>"/> 
                                <div class="invalid-feedback"> <?php global $errorAlertEmail; print_r($errorAlertEmail); ?></div>
                            </div>
                        </div>  
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" placeholder="Street" aria-label="Street" name="street" id="street"  class="form-control" value="<?= empty($_POST['street']) ? $_SESSION['street'] : $_POST['street'] ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="number" placeholder="Street number" aria-label="Street number" id="streetnumber" name="streetnumber" class="form-control <?= $errorActiveStreetnumber == true ? 'is-invalid' : '' ?>" value="<?= empty($_POST['streetnumber']) ? $_SESSION['streetnumber']  : $_POST['streetnumber'] ?>">
                                <div class="invalid-feedback"> <?php global $errorAlertStreetnumber; print_r($errorAlertStreetnumber); ?></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" id="city" placeholder="City" aria-label="City" name="city" class="form-control" value="<?= empty($_POST['city']) ? $_SESSION['city']  : $_POST['city'] ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="number" id="zipcode" placeholder="Zipcode" aria-label="Zipcode" name="zipcode" class="form-control <?= $errorActiveZipcode == true ? 'is-invalid' : '' ?>" value="<?= empty($_POST['zipcode']) ? $_SESSION['zipcode']  : $_POST['zipcode'] ?>">
                                <div class="invalid-feedback"> <?php global $errorAlertZipcode; print_r($errorAlertZipcode); ?></div>
                            </div>
                        </div>
                    </fieldset>
            </div>
            </div> 
        <button type="submit" name="order" class="btn btn-success w-100 mt-2">Order</button></form>
        
    </div>
</body>
</html>