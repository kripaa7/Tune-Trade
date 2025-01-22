<?php

// Set the URL of the website
$url = 'https://guitarshopnepal.com/collections/all';

// Initialize cURL
$ch = curl_init();

// Set the URL and options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

// Execute the cURL request
$html = curl_exec($ch);

// Check for errors
if(curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}

// Close the cURL session
curl_close($ch);

// Check if the HTML was fetched successfully
if ($html === false) {
    echo "Error fetching the page.";
    exit;
}

// Create a DOMDocument instance to parse the HTML
$doc = new DOMDocument();
libxml_use_internal_errors(true); // Suppress warnings about malformed HTML
$doc->loadHTML($html);

// Extract product information using DOMXPath
$xpath = new DOMXPath($doc);

// Modify this query to match the HTML structure of the website
$products = $xpath->query('//div[contains(@class, "product")]'); // Adjust this XPath query based on actual HTML

// Set the folder where images will be saved
$imageFolder = 'images/scrapped';
if (!file_exists($imageFolder)) {
    mkdir($imageFolder, 0777, true); // Create folder if it doesn't exist
}

foreach ($products as $product) {
    // Fetch product name, price, image, and description with null checks
    $nameNode = $xpath->query('.//h2[@class="product-title"]', $product)->item(0);
    $priceNode = $xpath->query('.//span[@class="price"]', $product)->item(0);
    $imageNode = $xpath->query('.//img', $product)->item(0);
    $descriptionNode = $xpath->query('.//p[@class="product-description"]', $product)->item(0);

    // Check if the nodes are found and handle null values
    $name = $nameNode ? $nameNode->nodeValue : 'No name found';
    $price = $priceNode ? $priceNode->nodeValue : 'No price found';
    $imageUrl = $imageNode ? $imageNode->getAttribute('src') : '';
    $description = $descriptionNode ? $descriptionNode->nodeValue : 'No description found';

    // Print product details (optional)
    echo "Product Name: $name<br>";
    echo "Price: $price<br>";
    echo "Image URL: $imageUrl<br>";
    echo "Description: $description<br><br>";

    // Ensure the image URL is complete (prepend https: if it starts with //)
    if (strpos($imageUrl, '//') === 0) {
        $imageUrl = 'https:' . $imageUrl;
    }

    // Remove query string from the image URL (if any)
    $imageUrlWithoutQuery = strtok($imageUrl, '?');

    // Download the image if the URL is valid
    if ($imageUrlWithoutQuery) {
        // Extract image file name
        $imageName = basename($imageUrlWithoutQuery);

        // Set the path to save the image
        $imagePath = $imageFolder . '/' . $imageName;

        // Download and save the image
        $imageData = file_get_contents($imageUrlWithoutQuery); // Or use cURL for larger images

        if ($imageData !== false) {
            file_put_contents($imagePath, $imageData);
            echo "Image saved: $imagePath<br><br>";
        } else {
            echo "Failed to download image: $imageUrl<br><br>";
        }
    }
}

?>
