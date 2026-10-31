
<?php
session_start();
include "db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Clear cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

// Add product, respecting available stock
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $result = mysqli_query($conn, "SELECT stock FROM products WHERE id = $id");
    $product = $result ? mysqli_fetch_assoc($result) : null;

    if ($product && (int)$product['stock'] > 0) {
        $currentQty = (int)($_SESSION['cart'][$id] ?? 0);

        if ($currentQty < (int)$product['stock']) {
            $_SESSION['cart'][$id] = $currentQty + 1;
        }
    }

    header("Location: cart.php");
    exit;
}

// Increase quantity
if (isset($_GET['plus'])) {
    $id = (int)$_GET['plus'];

    if (isset($_SESSION['cart'][$id])) {
        $result = mysqli_query($conn, "SELECT stock FROM products WHERE id = $id");
        $product = $result ? mysqli_fetch_assoc($result) : null;

        if ($product && (int)$_SESSION['cart'][$id] < (int)$product['stock']) {
            $_SESSION['cart'][$id]++;
        }
    }

    header("Location: cart.php");
    exit;
}

// Decrease quantity
if (isset($_GET['minus'])) {
    $id = (int)$_GET['minus'];

    if (isset($_SESSION['cart'][$id])) {
        if ($_SESSION['cart'][$id] > 1) {
            $_SESSION['cart'][$id]--;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }

    header("Location: cart.php");
    exit;
}

// Remove product
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);

    header("Location: cart.php");
    exit;
}

// Fetch cart products
$items = [];
$total = 0;

foreach ($_SESSION['cart'] as $productId => $quantity) {
    $productId = (int)$productId;
    $quantity = (int)$quantity;

    $result = mysqli_query(
        $conn,
        "SELECT * FROM products WHERE id = $productId"
    );

    $product = $result ? mysqli_fetch_assoc($result) : null;

    if (!$product) {
        unset($_SESSION['cart'][$productId]);
        continue;
    }

    $subtotal = (float)$product['price'] * $quantity;
    $total += $subtotal;

    $items[] = [
        'product' => $product,
        'quantity' => $quantity,
        'subtotal' => $subtotal
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart | ShopHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --gold: #d8a83e;
            --dark: #111;
            --cream: #faf8f2;
        }

        body {
            background: #fafafa;
            color: #222;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: var(--dark);
            padding: 15px 0;
        }

        .navbar-brand {
            color: var(--gold) !important;
            font-size: 25px;
            font-weight: 800;
        }

        .btn-gold {
            background: var(--gold);
            border: 1px solid var(--gold);
            color: #111;
            font-weight: bold;
        }

        .btn-gold:hover {
            background: #edc66c;
            border-color: #edc66c;
            color: #111;
        }

        .cart-heading {
            background: linear-gradient(110deg, #111, #303030);
            color: white;
            padding: 35px 0;
        }

        .gold-text {
            color: var(--gold);
        }

        .cart-item {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 16px;
        }

        .cart-product-image {
            width: 110px;
            height: 110px;
            object-fit: contain;
            background: var(--cream);
            border-radius: 10px;
            padding: 8px;
        }

        .product-name {
            color: #222;
            font-weight: 800;
            font-size: 18px;
            text-decoration: none;
        }

        .product-name:hover {
            color: #98701b;
        }

        .quantity-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }

        .quantity-control a {
            display: inline-block;
            padding: 5px 12px;
            text-decoration: none;
            color: #111;
            background: #f8f8f8;
        }

        .quantity-control a:hover {
            background: #f3e6c5;
        }

        .quantity-number {
            min-width: 35px;
            text-align: center;
            font-weight: bold;
        }

        .summary-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 24px;
            position: sticky;
            top: 90px;
        }

        .summary-total {
            font-size: 27px;
            font-weight: 800;
            color: #98701b;
        }

        .empty-cart {
            background: white;
            border: 1px solid #eee;
            border-radius: 15px;
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart i {
            font-size: 65px;
            color: var(--gold);
        }

        footer {
            background: #080808;
            color: #ccc;
            padding: 25px 0;
        }

        footer a {
            color: var(--gold);
            text-decoration: none;
        }

        @media (max-width: 576px) {
            .cart-item {
                padding: 12px;
            }

            .cart-product-image {
                width: 85px;
                height: 95px;
            }

            .product-name {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-bag-check-fill"></i> ShopHub
        </a>

        <a href="products.php?id=1" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Continue Shopping
        </a>
    </div>
</nav>

<!-- HEADING -->
<section class="cart-heading">
    <div class="container">
        <p class="gold-text fw-bold mb-2">YOUR SHOPPING BAG</p>
        <h1 class="fw-bold mb-2">
            <i class="bi bi-cart3"></i> My Cart
        </h1>
        <p class="text-white-50 mb-0">
            Review your items before you continue shopping.
        </p>
    </div>
</section>

<main class="container py-5">

    <?php if (empty($items)) { ?>

        <!-- EMPTY CART -->
        <div class="empty-cart">
            <i class="bi bi-cart-x"></i>
            <h2 class="fw-bold mt-3">Your cart is empty</h2>
            <p class="text-muted">
                Looks like you haven't added any products yet.
            </p>

            <a href="index.php" class="btn btn-gold btn-lg mt-2">
                <i class="bi bi-bag"></i> Start Shopping
            </a>
        </div>

    <?php } else { ?>

        <div class="row g-4">

            <!-- CART ITEMS -->
            <div class="col-lg-8">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        Cart Items (<?php echo count($items); ?>)
                    </h4>

                    <a href="cart.php?clear=1"
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Clear all products from your cart?');">
                        <i class="bi bi-trash"></i> Clear Cart
                    </a>
                </div>

                <?php foreach ($items as $item) {
                    $product = $item['product'];
                    $quantity = $item['quantity'];
                    $subtotal = $item['subtotal'];
                ?>

                    <div class="cart-item">
                        <div class="row align-items-center g-3">

                            <div class="col-4 col-sm-3">
                                
                                    <img
                                        src="<?php echo htmlspecialchars(getProductImage($product['image'] ?? '', $product['id'] ?? null)); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                        class="cart-product-image"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <div style="display:none; width:100%; height:95px;
                                                background:#faf8f2; border-radius:8px;
                                                align-items:center; justify-content:center;">
                                        <i class="bi bi-image fs-2 text-secondary"></i>
                                    </div>
                            </div>

                            <div class="col-8 col-sm-9">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <a class="product-name"
                                           href="product.php?id=<?php echo (int)$product['id']; ?>">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>

                                        <p class="text-muted small mb-2">
                                            Brand: <?php echo htmlspecialchars($product['brand']); ?>
                                        </p>

                                        <div class="fw-bold">
                                            ₹<?php echo number_format((float)$product['price'], 2); ?>
                                        </div>
                                    </div>

                                    <a href="cart.php?remove=<?php echo (int)$product['id']; ?>"
                                       class="text-danger"
                                       title="Remove product"
                                       aria-label="Remove product"
                                       onclick="return confirm('Remove this product from your cart?');">
                                        <i class="bi bi-trash3 fs-5"></i>
                                    </a>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between
                                            align-items-center gap-3 mt-3">

                                    <div class="quantity-control">
                                        <a href="cart.php?minus=<?php echo (int)$product['id']; ?>"
                                           aria-label="Decrease quantity">−</a>

                                        <span class="quantity-number">
                                            <?php echo $quantity; ?>
                                        </span>

                                        <?php if ($quantity < (int)$product['stock']) { ?>
                                            <a href="cart.php?plus=<?php echo (int)$product['id']; ?>"
                                               aria-label="Increase quantity">+</a>
                                        <?php } else { ?>
                                            <span class="px-3 text-muted"
                                                  title="Maximum stock reached">+</span>
                                        <?php } ?>
                                    </div>

                                    <div class="text-end">
                                        <small class="text-muted">Subtotal</small>
                                        <div class="fw-bold">
                                            ₹<?php echo number_format($subtotal, 2); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                <?php } ?>

            </div>

            <!-- ORDER SUMMARY -->
            <div class="col-lg-4">
                <div class="summary-card">

                    <h4 class="fw-bold mb-4">Order Summary</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Items</span>
                        <span><?php echo array_sum($_SESSION['cart']); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <strong>₹<?php echo number_format($total, 2); ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Delivery</span>
                        <span class="text-success fw-bold">Free</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <span class="fw-bold">Total</span>
                        <span class="summary-total">
                            ₹<?php echo number_format($total, 2); ?>
                        </span>
                    </div>

                    <p class="small text-muted mt-3">
                        Delivery is shown as free for this demo project.
                    </p>

                    <button type="button" class="btn btn-gold btn-lg w-100 mt-2"
                            onclick="alert('Checkout is not implemented yet. This is a demo cart.');">
                        Proceed to Checkout
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>

                    <a href="index.php"
                       class="btn btn-outline-dark w-100 mt-3">
                        Continue Shopping
                    </a>

                </div>
            </div>

        </div>

    <?php } ?>

</main>

<footer>
    <div class="container d-flex flex-wrap justify-content-between gap-2">
        <span>&copy; <?php echo date('Y'); ?> ShopHub</span>
        <a href="index.php">Back to Home</a>
    </div>
</footer>

</body>
</html>
