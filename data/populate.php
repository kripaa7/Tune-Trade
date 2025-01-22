<?php
// Database connection details
$db_name = 'mysql:host=localhost;dbname=tunetrade';
$user_name = 'root';
$user_password = '';

try {
    // Create PDO connection
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully<br>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Function to generate a unique ID (20 characters)
function unique_id(){
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($chars);
    $randomString = '';
    for ($i = 0; $i < 20; $i++) { 
        $randomString .= $chars[mt_rand(0, $charLength - 1)];
    } 
    return $randomString;
}

// Path to the CSV file
$csvFile = 'data.csv'; // Change this to the actual path of your CSV file

// Open the CSV file
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Skip the first row (header row)
    fgetcsv($handle);

    // Prepare SQL statement for inserting data
    $stmt = $conn->prepare("INSERT INTO products (id, seller_id, name, price, stock, product_detail, status, image) 
                            VALUES (:id, :seller_id, :name, :price, :stock, :product_detail, :status, :image)");

    // Loop through each row in the CSV
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $id = unique_id(); // Generate a unique ID for each product
        $seller_id = $data[0];
        $name = $data[1];
        $price = $data[2];
        $stock = $data[3];
        $product_detail = $data[4];
        $status = $data[5];
        $image = "image_filename.jpg";  // Replace this with actual logic for image mapping if needed

        // Bind parameters to the SQL statement
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':product_detail', $product_detail);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':image', $image);

        // Execute the SQL statement
        if ($stmt->execute()) {
            echo "Inserted: $name<br>";
        } else {
            echo "Error inserting $name<br>";
        }
    }
    fclose($handle); // Close the CSV file after reading
} else {
    echo "Error opening the file.";
}

// Close the database connection
$conn = null;
?>
