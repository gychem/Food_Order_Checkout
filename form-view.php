
<?php // This file is mostly containing things for your view / html ?>

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
<body>
<div class="container">
    <h1>Place your order</h1>

    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?daytickets=1">Order Daytickets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?optionaltickets=1">Order Optional Tickets</a>
            </li>
        </ul>
    </nav>

    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= empty($_POST['email']) ? $_SESSION['email'] : $_POST['email'] ?>"/>
            </div>
            <div></div>
        </div>

        <fieldset>
            <legend>Address</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street"  class="form-control" value="<?= empty($_POST['street']) ? $_SESSION['street'] : $_POST['street'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="streetnumber">Street number:</label>
                    <input type="number" id="streetnumber" name="streetnumber" class="form-control" value="<?= empty($_POST['streetnumber']) ? $_SESSION['streetnumber']  : $_POST['streetnumber'] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" class="form-control" value="<?= empty($_POST['city']) ? $_SESSION['city']  : $_POST['city'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" id="zipcode" name="zipcode" class="form-control" value="<?= empty($_POST['zipcode']) ? $_SESSION['zipcode']  : $_POST['zipcode'] ?>">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Products</legend>
            <?php foreach ($products as $i => $product): ?>
                <?php if($product['type'] == 'cat1' && $activeProductList == 'cat1') { ?>
                    <label class="d-flex">
                        <input type="checkbox" value="<?php echo $i ?>" <?= empty($_SESSION['productscat1'][$i]) ? '' : 'checked' ?> name="products[<?php echo $i ?>]"/> <?php echo $product['name'] ?> -
                        &euro; <?= number_format($product['price'], 2) ?>
                        <input type="number" id="quantity" name="quantity[<?php echo $i ?>]" class="form-control w-25"  value="1">
                    </label><br />    
                <?php }
                else if($product['type'] == 'cat2' && $activeProductList == 'cat2') { ?>
                    <label class="d-flex">
                        <input type="checkbox" value="<?php echo $i ?>" <?= empty($_SESSION['productscat2'][$i]) ? '' : 'checked' ?> name="products[<?php echo $i ?>]"/> <?php echo $product['name'] ?> -
                        &euro; <?= number_format($product['price'], 2) ?>
                        <input type="number" id="quantity" name="quantity[<?php echo $i ?>]" class="form-control w-25"  value="1">
                    </label><br />    
                <?php }
            endforeach; ?>
        </fieldset>

        <button type="submit" name="order" class="btn btn-primary">Order!</button>
    </form>

    <footer>You already ordered <strong>&euro; <?php echo $totalValue ?></strong> in food and drinks.</footer>
</div>

<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>
