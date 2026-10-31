<?php
$conn=mysqli_connect("localhost","root","","shoppingsite-1");
if(!$conn){
    die("database  connects failed");
}
// Find a saved product image, even if its extension is hidden
function getProductImage($imageName, $productId = null)
{
    $imageName = trim((string)$imageName);

    if ($imageName === '') {
        $imageName = '';
    }

    // Prevent directory traversal and use only the filename
    $imageName = basename(str_replace('\\', '/', $imageName));

    $imageFolder = __DIR__ . DIRECTORY_SEPARATOR . 'images'
                 . DIRECTORY_SEPARATOR;

    $webFolder = 'images/';

    // Try the exact filename first
    $exactPath = $imageFolder . $imageName;

    if (is_file($exactPath)) {
        return $webFolder . rawurlencode($imageName);
    }

    // If the database stores "1", try common extensions
    $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];

    foreach ($extensions as $extension) {
        $candidate = $imageName . '.' . $extension;

        if (is_file($imageFolder . $candidate)) {
            return $webFolder . rawurlencode($candidate);
        }
    }

    // Try uppercase extensions too
    foreach ($extensions as $extension) {
        $candidate = $imageName . '.' . strtoupper($extension);

        if (is_file($imageFolder . $candidate)) {
            return $webFolder . rawurlencode($candidate);
        }
    }

    if ($productId !== null) {
        foreach ($extensions as $extension) {
            $candidate = (int)$productId . "." . $extension;
            if (is_file($imageFolder . $candidate)) {
                return $webFolder . rawurlencode($candidate);
            }
        }
    }

    $availableImages = glob($imageFolder . '*');
    $availableImages = array_values(array_filter($availableImages, function ($path) {
        return is_file($path) && preg_match('/\.(jpe?g|png|webp|gif|avif)$/i', $path);
    }));

    if (count($availableImages) > 0) {
        sort($availableImages, SORT_NATURAL | SORT_FLAG_CASE);
        $imageIndex = $productId !== null ? ((int)$productId - 1) % count($availableImages) : 0;
        $fallbackImage = basename($availableImages[$imageIndex]);
        return $webFolder . rawurlencode($fallbackImage);
    }
    return 'https://placehold.co/500x500/f5f5f5/555?text=Image+Not+Found';
}
?>
