<?php
include "db.php";

$results = [];
$selected_crop = "";
$selected_problem = "";

/* Get all crops */
$crops = mysqli_query(
    $conn,
    "SELECT * FROM Crops ORDER BY CropName"
);

/* Search Product */
if (isset($_POST['find_product'])) {

    $selected_crop = $_POST['CropID'];
    $selected_problem = $_POST['ProblemID'];

    $sql = "
        SELECT
            Products.ProductID,
            Products.ProductName,
            Products.Category,
            Products.Crop,
            Products.Price,
            Products.Stock,
            Problems.ProblemName,
            Crops.CropName
        FROM Product_Recommendation

        INNER JOIN Products
        ON Product_Recommendation.ProductID = Products.ProductID

        INNER JOIN Problems
        ON Product_Recommendation.ProblemID = Problems.ProblemID

        INNER JOIN Crops
        ON Problems.CropID = Crops.CropID

        WHERE Problems.ProblemID = '$selected_problem'
        AND Problems.CropID = '$selected_crop'
    ";

    $query = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
}

/* Get all problems */
$problems = mysqli_query(
    $conn,
    "SELECT Problems.ProblemID,
            Problems.ProblemName,
            Problems.CropID,
            Crops.CropName
     FROM Problems
     INNER JOIN Crops
     ON Problems.CropID = Crops.CropID
     ORDER BY Problems.ProblemName"
);
?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Find Product</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f3f7f4;
}

/* SIDEBAR */

.sidebar {
    width: 240px;
    height: 100vh;
    background: #075c3b;
    position: fixed;
    left: 0;
    top: 0;
    color: white;
    padding: 25px 15px;
}

.logo {
    text-align: center;
    margin-bottom: 30px;
}

.logo-icon {
    font-size: 40px;
}

.logo h2 {
    margin-top: 5px;
}

.logo p {
    font-size: 12px;
    color: #ccebdd;
}

.menu {
    list-style: none;
}

.menu li {
    margin: 8px 0;
}

.menu a {
    display: block;
    padding: 12px;
    text-decoration: none;
    color: white;
    border-radius: 9px;
}

.menu a:hover,
.menu .active {
    background: #0b8f5a;
}

/* MAIN */

.main {
    margin-left: 240px;
    padding: 30px;
}

/* HEADER */

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.header h1 {
    color: #075c3b;
}

.header p {
    color: #777;
    margin-top: 5px;
}

.admin {
    background: white;
    padding: 10px 18px;
    border-radius: 10px;
}

/* SEARCH BOX */

.search-box {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.search-box h2 {
    color: #075c3b;
    margin-bottom: 8px;
}

.search-box p {
    color: #777;
    margin-bottom: 25px;
}

.form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

label {
    display: block;
    margin-bottom: 7px;
    color: #333;
    font-weight: bold;
}

select {
    width: 100%;
    padding: 13px;
    border: 1px solid #ddd;
    border-radius: 9px;
    background: white;
}

button {
    padding: 13px 25px;
    border: none;
    border-radius: 9px;
    background: #075c3b;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #0b8f5a;
}

/* RESULT */

.results {
    background: white;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
}

.results h2 {
    color: #075c3b;
    margin-bottom: 20px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.product-card {
    border: 1px solid #e1eee6;
    border-radius: 15px;
    padding: 22px;
    background: #fbfefc;
    transition: 0.3s;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.product-icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.product-card h3 {
    color: #075c3b;
    margin-bottom: 12px;
}

.product-card p {
    margin: 8px 0;
    color: #555;
}

.price {
    color: #075c3b;
    font-size: 22px;
    font-weight: bold;
}

.stock {
    color: #39804f;
    font-weight: bold;
}

.no-result {
    background: #fff7e6;
    padding: 20px;
    border-radius: 10px;
    color: #8a6500;
}

/* RESPONSIVE */

@media(max-width: 900px) {

    .product-grid {
        grid-template-columns: 1fr 1fr;
    }

    .form {
        grid-template-columns: 1fr;
    }

}

@media(max-width: 650px) {

    .sidebar {
        width: 180px;
    }

    .main {
        margin-left: 180px;
        padding: 15px;
    }

    .product-grid {
        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">

        <div class="logo-icon">🌱</div>

        <h2>KRUSHIMITRA</h2>

        <p>Agriculture Shop</p>

    </div>

    <ul class="menu">

        <li>
            <a href="dashboard.php">
                🏠 Dashboard
            </a>
        </li>

        <li>
            <a href="products.php">
                💊 Products
            </a>
        </li>

        <li>
            <a href="crops.php">
                🌾 Crops
            </a>
        </li>

        <li>
            <a href="problems.php">
                🐛 Plant Problems
            </a>
        </li>

        <li>
            <a href="find_product.php" class="active">
                🔍 Find Product
            </a>
        </li>

        <li>
            <a href="customers.php">
                👨‍🌾 Customers
            </a>
        </li>

        <li>
            <a href="sales.php">
                💰 Sales & Billing
            </a>
        </li>

        <li>
            <a href="stock.php">
                📦 Stock
            </a>
        </li>

        <li>
            <a href="reports.php">
                📊 Reports
            </a>
        </li>

    </ul>

</div>


<!-- MAIN -->

<div class="main">

    <div class="header">

        <div>

            <h1>🔍 Find Product</h1>

            <p>
                Find agriculture products by crop and plant problem
            </p>

        </div>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <!-- SEARCH -->

    <div class="search-box">

        <h2>🌱 Find a Suitable Product</h2>

        <p>
            Select a crop and plant problem to search the product database.
        </p>

        <form method="POST">

            <div class="form">

                <div>

                    <label>Select Crop</label>

                    <select name="CropID" id="crop" required>

                        <option value="">
                            Select Crop
                        </option>

                        <?php while($crop = mysqli_fetch_assoc($crops)) { ?>

                            <option
                                value="<?php echo $crop['CropID']; ?>"
                            >

                                <?php echo htmlspecialchars($crop['CropName']); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div>

                    <label>Select Plant Problem</label>

                    <select name="ProblemID" required>

                        <option value="">
                            Select Problem
                        </option>

                        <?php while($problem = mysqli_fetch_assoc($problems)) { ?>

                            <option
                                value="<?php echo $problem['ProblemID']; ?>"
                            >

                                <?php
                                echo htmlspecialchars($problem['ProblemName']);
                                echo " - ";
                                echo htmlspecialchars($problem['CropName']);
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div>

                    <button
                        type="submit"
                        name="find_product">

                        🔍 Find Product

                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- RESULTS -->

    <?php if (isset($_POST['find_product'])) { ?>

    <div class="results">

        <h2>🌿 Recommended Products</h2>

        <?php if (count($results) > 0) { ?>

            <div class="product-grid">

                <?php foreach($results as $row) { ?>

                    <div class="product-card">

                        <div class="product-icon">
                            💊
                        </div>

                        <h3>
                            <?php echo htmlspecialchars($row['ProductName']); ?>
                        </h3>

                        <p>
                            <b>Category:</b>
                            <?php echo htmlspecialchars($row['Category']); ?>
                        </p>

                        <p>
                            <b>Crop:</b>
                            <?php echo htmlspecialchars($row['CropName']); ?>
                        </p>

                        <p>
                            <b>Problem:</b>
                            <?php echo htmlspecialchars($row['ProblemName']); ?>
                        </p>

                        <p class="price">
                            ₹<?php echo number_format($row['Price'], 2); ?>
                        </p>

                        <p class="stock">
                            📦 Stock:
                            <?php echo $row['Stock']; ?>
                        </p>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="no-result">

                ⚠️ No product found for the selected
                crop and plant problem.

            </div>

        <?php } ?>

    </div>

    <?php } ?>

</div>

</body>

</html>