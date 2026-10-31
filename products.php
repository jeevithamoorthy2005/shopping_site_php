<?php

include "db.php";



$category_id = (int)($_GET['id'] ?? 1);
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? '';

$conditions = "category_id = $category_id";

if ($search !== '') {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $conditions .= " AND (name LIKE '%$search_safe%' OR brand LIKE '%$search_safe%')";
}

$order = "";

if ($sort === "low") {
    $order = " ORDER BY price ASC";
} elseif ($sort === "high") {
    $order = " ORDER BY price DESC";
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE $conditions $order"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        .product {
    width: 100%;
    border: 0;
    padding: 20px;
    display: block;
    vertical-align: top;
    transition: 0.3s;
}
        .product img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .product:hover {
                transform: translateY(-8px);
                  }

    </style>

</head>

<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🛍️ My Shop</a>

        <a href="cart.php" class="btn btn-light">🛒 Cart</a>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center fw-bold mb-2">Our Products</h1>
    
<form action="products.php" method="GET" class="mb-4">
    <input type="hidden" name="id"
           value="<?php echo htmlspecialchars($_GET['id'] ?? '1'); ?>">

    <div class="input-group">
        <input type="text" name="search"
               class="form-control"
               placeholder="Search products or brands...">

        <button type="submit" class="btn btn-primary">
            Search
        </button>
    </div>
</form>

<div class="text-end mb-4">
    <a href="products.php?id=<?php echo (int)($_GET['id'] ?? 1); ?>"
       class="btn btn-outline-secondary">
        Reset Search & Sort
    </a>
</div>

<p class="text-center text-muted mb-5">
    Explore our latest products
</p>
<div class="text-center mb-4">
    <a href="index.php" class="btn btn-outline-dark">
        ← Back to Categories
    </a>
</div>

<div class="row g-4">

<form method="GET" action="products.php" class="mb-4">
    <input type="hidden" name="id"
           value="<?php echo htmlspecialchars($_GET['id'] ?? '1'); ?>">

    <input type="hidden" name="search"
           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">

    <div class="row">
        <div class="col-md-4 ms-auto">
            
<select name="sort" class="form-select"
        onchange="this.form.submit()">

    <option value=""
        <?php echo $sort === '' ? 'selected' : ''; ?>>
        Sort by Price
    </option>

    <option value="low"
        <?php echo $sort === 'low' ? 'selected' : ''; ?>>
        Price: Low to High
    </option>

    <option value="high"
        <?php echo $sort === 'high' ? 'selected' : ''; ?>>
        Price: High to Low
    </option>

</select>
        </div>
    </div>
</form>
<?php
if (mysqli_num_rows($result) == 0) {
    echo '<div class="col-12 text-center text-muted">
            <h4>No products found 😕</h4>
            <p>Try searching for another product or brand.</p>
          </div>';
}

while ($row = mysqli_fetch_assoc($result)) {
?>

    <div class="col-md-3">
        <div class="product shadow-sm rounded-3 h-100">
        <img src="<?php echo htmlspecialchars(getProductImage($row['image'] ?? '', $row['id'] ?? null)); ?>" alt="<?php echo htmlspecialchars($row['name'] ?? 'Product'); ?>">

        <h5 class="mt-3 fw-bold">
    <?php echo $row['name']; ?>
</h5>

        <p>
            Brand: <?php echo $row['brand']; ?>
        </p>

        <h5 class="fw-bold text-primary">
    ₹<?php echo $row['price']; ?>
</h5>

       <?php if ($row['stock'] > 0) { ?>

    <p class="text-success fw-semibold">
        🟢 In Stock
    </p>

<?php } else { ?>

    <p class="text-danger fw-semibold">
        🔴 Out of Stock
    </p>

<?php } ?>

        <a class="btn btn-primary w-100"
   href="product.php?id=<?php echo $row['id']; ?>">
    View Product
</a>

            </div>
    </div>

<?php } ?>

</div>
</body>

</html>