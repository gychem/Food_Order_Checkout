<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Ticket Sales</title>
</head>
<body class="bg-secondary bg-gradient pt-4">
<div class="container">

<?php 
        global $orderContent; 
        print_r($orderContent); 
    ?>

<div class="card mt-4">
  <div class="card-body">

    <?php 
        global $errorMessage;
        print_r($errorMessage); 
    ?>

    <form method="post" id="orderForm">
        <div class="form-row">
        <div class="form-group  col-md-6">
                <input type="text" placeholder="Name" aria-label="Name" id="name" name="name" class="form-control" value="<?= empty($_POST['name']) ? $_SESSION['name'] : $_POST['name'] ?>"/>
                <?php //echo $errorAlertName ?>
            </div>
            <div class="form-group  col-md-6">
                <input type="text" placeholder="E-mail" aria-label="E-mail" id="email" name="email" class="form-control <?= $errorActiveEmail == true ? 'is-invalid' : '' ?>" value="<?= empty($_POST['email']) ? $_SESSION['email'] : $_POST['email'] ?>"/> 
                <div class="invalid-feedback"> <?php global $errorAlertEmail; print_r($errorAlertEmail); ?></div>
            </div>
            <div></div>
        </div>

        <fieldset>
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

        <nav>
            <div class="btn-group mt-1" role="group" aria-label="Basic example">
                <a class="btn btn-secondary" onclick="document.getElementById('orderForm').submit();" href="?food">Food</a>
                <a class="btn btn-secondary" type="submit" href="?drinks">Drinks</a>
            </div>
        </nav>

        <fieldset>
            <ul class="list-group mt-2">
                    <?php foreach ($products as $i => $product): ?>
                   
                        <?php if($product['type'] == 'cat1' && $activeProductList == 'cat1') { ?>
                            <li class="list-group-item d-flex align-items-center">
                                <input type="checkbox" value="<?php echo $i ?>" <?= empty($_SESSION['productscat1'][$i]) ? '' : 'checked' ?> name="products[<?php echo $i ?>]"/> 
                                <div class="mx-2 align-self-center"><?php echo $product['name'] ?></div>
                                <div class="align-self-center">&euro;<?= number_format($product['price'], 2) ?></div>
                                <input type="number" id="quantity" name="quantity[<?php echo $i ?>]" class="form-control mx-2" style="width: 75px" value="1">
                            </li>
                        <?php }
                        else if($product['type'] == 'cat2' && $activeProductList == 'cat2') { ?>
                            <li class="list-group-item d-flex align-items-center">
                                <input type="checkbox" value="<?php echo $i ?>" <?= empty($_SESSION['productscat2'][$i]) ? '' : 'checked' ?> name="products[<?php echo $i ?>]"/> 
                                <div class="mx-2 align-self-center"><?php echo $product['name'] ?></div>
                                <div class="align-self-center">&euro;<?= number_format($product['price'], 2) ?></div>
                                <div class=""><input type="number" id="quantity" name="quantity[<?php echo $i ?>]" class="form-control mx-2"  style="width: 75px" value="1"></div>
                            </li>
                        <?php } ?>
                    </li>
                    <?php endforeach; ?>
                </div>
            </ul>

   
        </fieldset>
        </div>
        <button type="submit" name="order" class="btn btn-success w-100 mt-2 mb-4">Order</button>
     </div>
       
    </form>
</div>

</body>
</html>