
<?php
include "db.php";

$id = (int)($_GET['id'] ?? 0);

$result = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE id = $id"
);

$product = mysqli_fetch_assoc($result);

if (!$product) {
    exit("Product not found!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($product['name']); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🛍️ My Shop</a>
        <a href="cart.php" class="btn btn-light">🛒 Cart</a>
    </div>
</nav>

<div class="container py-5">

    <div class="card shadow border-0 p-4 mx-auto"
         style="max-width: 850px;">

        <div class="row align-items-center g-4">

            <div class="col-md-6">
                <img
                    src="<?php echo htmlspecialchars(getProductImage($product['image'] ?? '', $product['id'] ?? null)); ?>"
                    class="img-fluid rounded"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>

            <div class="col-md-6">

                <h1 class="fw-bold">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>

                <p class="text-muted">
                    Brand: <?php echo htmlspecialchars($product['brand']); ?>
                </p>

                <h3 class="text-primary fw-bold">
                    ₹<?php echo $product['price']; ?>
                </h3>

                <?php if ($product['stock'] > 0) { ?>

                    <p class="text-success fw-semibold">
                        🟢 In Stock
                    </p>

                    <a href="cart.php?id=<?php echo $product['id']; ?>"
                       class="btn btn-primary btn-lg w-100">
                        🛒 Add to Cart
                    </a>

                <?php } else { ?>

                    <p class="text-danger fw-semibold">
                        🔴 Out of Stock
                    </p>

                    <button class="btn btn-secondary w-100" disabled>
                        Out of Stock
                    </button>

                <?php } ?>

                <a href="javascript:history.back()"
                   class="btn btn-outline-dark w-100 mt-3">
                    Continue Shopping
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>
```
