<?php
include "db.php";

$message = "";

/* Add Product */
if (isset($_POST['add_product'])) {

    $id = $_POST['ProductID'];
    $name = $_POST['ProductName'];
    $category = $_POST['Category'];
    $crop = $_POST['Crop'];
    $price = $_POST['Price'];
    $stock = $_POST['Stock'];
    $expiry = $_POST['ExpiryDate'];

    $sql = "INSERT INTO Products
            (ProductID, ProductName, Category, Crop, Price, Stock, ExpiryDate)
            VALUES
            ('$id', '$name', '$category', '$crop', '$price', '$stock', '$expiry')";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Product added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

/* Delete Product */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $sql = "DELETE FROM Products WHERE ProductID='$id'";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Product deleted successfully!";
    } else {
        $message = "❌ Cannot delete this product because it is used in another table.";
    }
}

/* Search */
$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM Products
     WHERE ProductName LIKE '%$search%'
     OR Category LIKE '%$search%'
     OR Crop LIKE '%$search%'
     ORDER BY ProductID DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Products</title>

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

/* Sidebar */

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

/* Main */

.main {
    margin-left: 240px;
    padding: 30px;
}

/* Header */

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.header h1 {
    color: #075c3b;
}

.admin {
    background: white;
    padding: 10px 18px;
    border-radius: 10px;
}

/* Add Product */

.add-box {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.add-box h2 {
    color: #075c3b;
    margin-bottom: 20px;
}

.form {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

input, select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

button {
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #075c3b;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #0b8f5a;
}

/* Search */

.search {
    background: white;
    padding: 18px;
    border-radius: 14px;
    margin-bottom: 20px;
}

.search form {
    display: flex;
    gap: 10px;
}

.search input {
    flex: 1;
}

/* Table */

.table-box {
    background: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #e7f5ed;
    color: #075c3b;
    padding: 13px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.delete {
    background: #d62828;
    color: white;
    padding: 7px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
}

.delete:hover {
    background: #a91d1d;
}

.message {
    background: #e8f7ed;
    color: #075c3b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* Responsive */

@media(max-width: 900px) {

    .form {
        grid-template-columns: repeat(2, 1fr);
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

    .form {
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
            <a href="products.php" class="active">
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
            <a href="find_product.php">
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

            <h1>💊 Product Management</h1>

            <p>Manage agriculture medicines and products</p>

        </div>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <!-- ADD PRODUCT -->

    <div class="add-box">

        <h2>➕ Add New Product</h2>

        <form method="POST">

            <div class="form">

                <input
                    type="number"
                    name="ProductID"
                    placeholder="Product ID"
                    required
                >

                <input
                    type="text"
                    name="ProductName"
                    placeholder="Product Name"
                    required
                >

                <select name="Category" required>

                    <option value="">Select Category</option>

                    <option>Fungicide</option>
                    <option>Insecticide</option>
                    <option>Herbicide</option>
                    <option>Fertilizer</option>
                    <option>Growth Promoter</option>
                    <option>Seed Treatment</option>

                </select>

                <input
                    type="text"
                    name="Crop"
                    placeholder="Suitable Crop"
                    required
                >

                <input
                    type="number"
                    step="0.01"
                    name="Price"
                    placeholder="Price"
                    required
                >

                <input
                    type="number"
                    name="Stock"
                    placeholder="Stock"
                    required
                >

                <input
                    type="date"
                    name="ExpiryDate"
                    required
                >

                <button
                    type="submit"
                    name="add_product">
                    ➕ Add Product
                </button>

            </div>

        </form>

    </div>


    <!-- SEARCH -->

    <div class="search">

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="🔍 Search product, category or crop..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">
                Search
            </button>

        </form>

    </div>


    <!-- PRODUCT TABLE -->

    <div class="table-box">

        <h2 style="color:#075c3b; margin-bottom:15px;">
            🌱 Available Products
        </h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Product</th>
                <th>Category</th>
                <th>Crop</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Expiry</th>
                <th>Action</th>

            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $row['ProductID']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['ProductName']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['Category']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['Crop']); ?>
                </td>

                <td>
                    ₹<?php echo number_format($row['Price'], 2); ?>
                </td>

                <td>
                    <?php echo $row['Stock']; ?>
                </td>

                <td>
                    <?php echo $row['ExpiryDate']; ?>
                </td>

                <td>

                    <a
                        class="delete"
                        href="products.php?delete=<?php echo $row['ProductID']; ?>"
                        onclick="return confirm('Delete this product?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>