<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";
$imagePath = "";
$disease = "";
$product = "";
$price = "";
$stock = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Upload image
    if (isset($_FILES["plant_image"]) &&
        $_FILES["plant_image"]["error"] == 0) {

        $fileName = $_FILES["plant_image"]["name"];
        $tmpName = $_FILES["plant_image"]["tmp_name"];

        $extension = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );

        $allowed = ["jpg", "jpeg", "png"];

        if (!in_array($extension, $allowed)) {

            $message = "❌ Please upload JPG, JPEG or PNG image.";

        } else {

            $uploadFolder = __DIR__ . "/uploads/";

            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777, true);
            }

            $newName =
                "plant_" .
                time() .
                "." .
                $extension;

            $destination =
                $uploadFolder . $newName;

            if (move_uploaded_file(
                $tmpName,
                $destination
            )) {

                $imagePath =
                    "uploads/" . $newName;

                $message =
                    "✅ Plant image uploaded successfully.";

            } else {

                $message =
                    "❌ Image upload failed.";
            }
        }
    }

    // Disease selection
    if (isset($_POST["disease"])) {

        $disease = $_POST["disease"];

        // Example recommendations
        $recommendations = [

            "Tomato Early Blight" => [
                "product" => "Mancozeb Fungicide",
                "price" => "250",
                "stock" => "Available"
            ],

            "Tomato Late Blight" => [
                "product" => "Copper Oxychloride",
                "price" => "300",
                "stock" => "Available"
            ],

            "Potato Early Blight" => [
                "product" => "Chlorothalonil",
                "price" => "280",
                "stock" => "Available"
            ],

            "Potato Late Blight" => [
                "product" => "Metalaxyl Fungicide",
                "price" => "350",
                "stock" => "Available"
            ],

            "Corn Common Rust" => [
                "product" => "Propiconazole Fungicide",
                "price" => "320",
                "stock" => "Available"
            ],

            "Corn Healthy" => [
                "product" => "No medicine required",
                "price" => "0",
                "stock" => "Healthy Plant"
            ]
        ];

        if (isset($recommendations[$disease])) {

            $product =
                $recommendations[$disease]["product"];

            $price =
                $recommendations[$disease]["price"];

            $stock =
                $recommendations[$disease]["stock"];
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Plant Disease Detection</title>

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #eef8ee;
}

.header {
    background: #176b35;
    color: white;
    padding: 20px 40px;
    font-size: 26px;
    font-weight: bold;
}

.container {
    width: 90%;
    max-width: 900px;
    margin: 40px auto;
}

.card {
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.12);
    text-align: center;
}

h1 {
    color: #176b35;
}

.upload-box {
    border: 2px dashed #3c9b52;
    padding: 30px;
    border-radius: 15px;
    margin: 25px 0;
}

input[type=file] {
    margin: 15px;
}

select {
    padding: 12px;
    width: 80%;
    max-width: 400px;
    border-radius: 8px;
    border: 1px solid #aaa;
}

button {
    background: #238b45;
    color: white;
    border: none;
    padding: 13px 30px;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #145c2b;
}

.message {
    margin: 20px;
    padding: 12px;
    background: #e8f5e9;
    color: #176b35;
    font-weight: bold;
    border-radius: 10px;
}

.plant-image {
    width: 300px;
    max-width: 100%;
    border-radius: 15px;
    margin: 20px;
}

.result {
    margin-top: 30px;
    padding: 25px;
    background: #f1f8e9;
    border-radius: 15px;
    text-align: left;
}

.result h2 {
    color: #176b35;
}

.product {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-top: 15px;
}

.available {
    color: green;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="header">

🌱 KrushiMitra

</div>

<div class="container">

<div class="card">

<h1>🌿 Plant Disease Detection</h1>

<p>
Upload a plant image to identify the disease
and find a suitable product from our shop.
</p>


<!-- Upload -->

<form method="POST"
      enctype="multipart/form-data">

<div class="upload-box">

<h3>📷 Upload Plant Image</h3>

<input
type="file"
name="plant_image"
accept=".jpg,.jpeg,.png"
required>

<br><br>

<button type="submit">
📤 Upload Image
</button>

</div>

</form>


<?php if ($message != "") { ?>

<div class="message">

<?php echo htmlspecialchars($message); ?>

</div>

<?php } ?>


<!-- Display Image -->

<?php if ($imagePath != "") { ?>

<h2>🖼️ Uploaded Plant Image</h2>

<img
src="<?php echo htmlspecialchars($imagePath); ?>"
class="plant-image">

<!-- Disease -->

<form method="POST">

<h3>🌿 Select Disease</h3>

<select name="disease" required>

<option value="">
-- Select Disease --
</option>

<option value="Tomato Early Blight">
Tomato Early Blight
</option>

<option value="Tomato Late Blight">
Tomato Late Blight
</option>

<option value="Potato Early Blight">
Potato Early Blight
</option>

<option value="Potato Late Blight">
Potato Late Blight
</option>

<option value="Corn Common Rust">
Corn Common Rust
</option>

<option value="Corn Healthy">
Corn Healthy
</option>

</select>

<br><br>

<button type="submit">
🔍 Get Disease Result
</button>

</form>

<?php } ?>


<!-- Result -->

<?php if ($disease != "") { ?>

<div class="result">

<h2>🌿 Disease Result</h2>

<h3>
Disease:
<?php echo htmlspecialchars($disease); ?>
</h3>

<div class="product">

<h3>💊 Recommended Product</h3>

<p>
<strong>
<?php echo htmlspecialchars($product); ?>
</strong>
</p>

<p>
💰 Price:
₹<?php echo htmlspecialchars($price); ?>
</p>

<p class="available">
📦 <?php echo htmlspecialchars($stock); ?>
</p>

</div>

</div>

<?php } ?>

</div>

</div>

</body>

</html>