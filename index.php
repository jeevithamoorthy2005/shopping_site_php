
<?php
include "db.php";

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM category");

// Fetch featured products
$featured = mysqli_query(
    $conn,
    "SELECT * FROM products ORDER BY id DESC LIMIT 8"
);

// Category images from the internet
$categoryImages = [
    'shoe' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=700&q=80',
    'cloth' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=700&q=80',
    'mobile' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=700&q=80',
    'watch' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=700&q=80'
];

// Select a category image
function getCategoryImage($categoryName, $categoryImages)
{
    $name = strtolower($categoryName);

    if (strpos($name, 'cloth') !== false) {
        return $categoryImages['cloth'];
    }

    if (
        strpos($name, 'mobile') !== false ||
        strpos($name, 'phone') !== false
    ) {
        return $categoryImages['mobile'];
    }

    if (strpos($name, 'watch') !== false) {
        return $categoryImages['watch'];
    }

    return $categoryImages['shoe'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ShopHub | Everyday Shopping</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --gold: #c99b45;
            --black: #111;
            --light: #f7f7f7;
            --muted: #777;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--black);
            background: white;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .navbar {
            background: white;
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .brand {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand span {
            color: var(--gold);
        }

        .nav-link {
            font-size: 14px;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: var(--gold);
        }

        .search-form {
            display: flex;
            align-items: center;
            border-radius: 30px;
            background: #f4f4f4;
            padding: 4px;
        }

        .search-form input {
            width: 145px;
            border: 0;
            outline: 0;
            background: transparent;
            padding: 9px 12px;
            font-size: 12px;
        }

        .search-form button {
            border: 0;
            border-radius: 25px;
            background: var(--black);
            color: white;
            padding: 9px 14px;
            font-size: 12px;
        }

        .hero {
            min-height: 500px;
            display: flex;
            align-items: center;
            color: white;
            background:
                linear-gradient(90deg, rgba(0,0,0,.82), rgba(0,0,0,.2)),
                url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1800&q=85')
                center/cover no-repeat;
        }

        .hero-content {
            max-width: 620px;
            padding: 65px 0;
        }

        .eyebrow {
            color: var(--gold);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: clamp(38px, 5vw, 66px);
            font-weight: 800;
            line-height: 1.15;
            margin: 18px 0;
        }

        .hero p {
            max-width: 500px;
            color: #eee;
            font-size: 14px;
            line-height: 1.9;
        }

        .btn-gold {
            display: inline-block;
            background: var(--gold);
            color: #111;
            border: 1px solid var(--gold);
            padding: 14px 24px;
            font-size: 12px;
            font-weight: 700;
            transition: .25s;
        }

        .btn-gold:hover {
            background: transparent;
            color: white;
        }

        .section {
            padding: 75px 0;
        }

        .section-light {
            background: var(--light);
        }

        .section-heading {
            margin-bottom: 35px;
        }

        .section-heading h2 {
            font-size: clamp(27px, 3vw, 36px);
            font-weight: 800;
            margin: 8px 0;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 13px;
        }

        .category-card {
            position: relative;
            display: block;
            height: 270px;
            overflow: hidden;
            background: #eee;
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .category-card:hover img {
            transform: scale(1.06);
        }

        .category-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            padding: 22px;
            color: white;
            background: linear-gradient(transparent 30%, rgba(0,0,0,.8));
        }

        .category-overlay h3 {
            font-size: 21px;
            font-weight: 700;
            margin: 0;
        }

        .category-overlay span {
            display: block;
            margin-top: 6px;
            font-size: 11px;
        }

        .product-card {
            height: 100%;
            background: white;
            border: 1px solid #e9e9e9;
            transition: .25s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        .product-image {
            width: 100%;
            height: 230px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f3f3f3;
        }

        .product-image img {
            display: block;
            width: 100%;
            height: 100%;
            padding: 12px;
            object-fit: contain;
        }

        .product-info {
            padding: 17px;
        }

        .product-brand {
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 11px;
        }

        .product-name {
            margin-bottom: 9px;
            font-size: 15px;
            font-weight: 600;
        }

        .product-price {
            margin-bottom: 16px;
            font-size: 16px;
            font-weight: 700;
        }

        .view-product {
            display: block;
            padding: 11px 8px;
            border: 1px solid #222;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            transition: .2s;
        }

        .view-product:hover {
            background: #111;
            color: white;
        }

        .promo {
            padding: 45px;
            background: #111;
            color: white;
        }

        .promo h2 {
            font-size: clamp(25px, 3vw, 36px);
            font-weight: 800;
        }

        .promo p {
            color: #ccc;
            font-size: 13px;
            line-height: 1.8;
        }

        .empty-message {
            padding: 25px;
            border: 1px solid #eee;
            background: white;
            color: var(--muted);
            text-align: center;
        }

        footer {
            padding: 35px 0;
            background: #0c0c0c;
            color: white;
        }

        footer p {
            margin: 8px 0 0;
            color: #aaa;
            font-size: 12px;
        }

        @media (max-width: 991px) {
            .nav-link {
                margin: 0;
            }

            .search-form {
                max-width: 280px;
                margin-top: 12px;
            }

            .hero {
                min-height: 430px;
            }

            .category-card {
                height: 230px;
            }
        }

        @media (max-width: 575px) {
            .navbar {
                padding: 14px 0;
            }

            .brand {
                font-size: 24px;
            }

            .hero {
                min-height: 400px;
            }

            .section {
                padding: 50px 0;
            }

            .category-card {
                height: 190px;
            }

            .product-image {
                height: 175px;
            }

            .product-info {
                padding: 12px;
            }

            .product-name {
                font-size: 13px;
            }

            .product-price {
                font-size: 14px;
            }

            .promo {
                padding: 28px 20px;
            }
        }
    </style>
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">

        <a class="brand" href="index.php">
            ShopHub<span>.</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#categories">Categories</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#featured">Featured</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
            </ul>

            <form class="search-form" action="products.php" method="GET">
                <input type="text" name="search"
                       placeholder="Search products..."
                       aria-label="Search products">
                <button type="submit">Search</button>
            </form>

        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="eyebrow">Discover Your Style</div>

            <h1>Find Your Next Favourite Thing.</h1>

            <p>
                Explore everyday essentials, timeless styles and
                must-have products — all in one place.
            </p>

            <a href="#categories" class="btn-gold mt-3">
                EXPLORE COLLECTION
            </a>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="section" id="categories">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">Browse Collections</div>
            <h2>Shop By Category</h2>
            <p>Discover something you'll love.</p>
        </div>

        <div class="row g-4">

            <?php if ($categories && mysqli_num_rows($categories) > 0): ?>

                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>

                    <?php
                    $categoryName = $cat['name']
                        ?? $cat['category_name']
                        ?? 'Collection';

                    $categoryId = (int)($cat['id'] ?? 0);

                    $categoryImage = getCategoryImage(
                        $categoryName,
                        $categoryImages
                    );
                    ?>

                    <div class="col-6 col-lg-3">
                        <a class="category-card"
                           href="products.php?id=<?php echo $categoryId; ?>">

                            <img
                                src="<?php echo htmlspecialchars($categoryImage); ?>"
                                alt="<?php echo htmlspecialchars($categoryName); ?>"
                                loading="lazy">

                            <div class="category-overlay">
                                <div>
                                    <h3><?php echo htmlspecialchars($categoryName); ?></h3>
                                    <span>EXPLORE COLLECTION →</span>
                                </div>
                            </div>

                        </a>
                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="col-12">
                    <div class="empty-message">
                        No categories found. Check your category table.
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section section-light" id="featured">
    <div class="container">

        <div class="section-heading">
            <div class="eyebrow">Selected For You</div>
            <h2>Featured Products</h2>
            <p>Find your next everyday favourite.</p>
        </div>

        <div class="row g-4">

            <?php if ($featured && mysqli_num_rows($featured) > 0): ?>

                <?php while ($product = mysqli_fetch_assoc($featured)): ?>

                    <?php
                    $productId = (int)($product['id'] ?? 0);
                    $productName = $product['name'] ?? 'Product';
                    $productBrand = $product['brand'] ?? '';
                    $productPrice = (float)($product['price'] ?? 0);

                    // The image column should contain a filename such as 1 or 1.jpg
                    $productImage = getProductImage($product['image'] ?? '', $productId);
                    ?>

                    <div class="col-6 col-lg-3">
                        <div class="product-card">

                            <a href="product.php?id=<?php echo $productId; ?>">
                                <div class="product-image">
                                    <img
                                        src="<?php echo htmlspecialchars($productImage); ?>"
                                        alt="<?php echo htmlspecialchars($productName); ?>"
                                        loading="lazy">
                                </div>
                            </a>

                            <div class="product-info">

                                <div class="product-brand">
                                    <?php echo htmlspecialchars($productBrand); ?>
                                </div>

                                <div class="product-name">
                                    <?php echo htmlspecialchars($productName); ?>
                                </div>

                                <div class="product-price">
                                    ₹<?php echo number_format($productPrice, 2); ?>
                                </div>

                                <a class="view-product"
                                   href="product.php?id=<?php echo $productId; ?>">
                                    VIEW PRODUCT
                                </a>

                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="col-12">
                    <div class="empty-message">
                        No products found. Check your products table.
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <div class="text-center mt-5">
            <a href="products.php" class="btn btn-outline-dark">
                VIEW ALL PRODUCTS
            </a>
        </div>

    </div>
</section>

<!-- PROMO -->
<section class="section">
    <div class="container">
        <div class="promo">
            <div class="row align-items-center g-4">

                <div class="col-lg-8">
                    <div class="eyebrow">Your Shopping Destination</div>

                    <h2 class="mt-3">Good Finds. Great Style.</h2>

                    <p class="mb-0">
                        Explore our collections and discover products
                        that fit your everyday needs.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a href="products.php" class="btn-gold">
                        START SHOPPING →
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row align-items-center g-3">

            <div class="col-md-6">
                <a class="brand text-white" href="index.php">
                    ShopHub<span>.</span>
                </a>
                <p>Your everyday shopping destination.</p>
            </div>

            <div class="col-md-6 text-md-end">
                <p>
                    &copy; <?php echo date('Y'); ?> ShopHub.
                    All rights reserved.
                </p>
            </div>

        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
