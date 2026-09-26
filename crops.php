<?php
include "db.php";

$message = "";

/* Add Crop */
if (isset($_POST['add_crop'])) {

    $id = $_POST['CropID'];
    $name = $_POST['CropName'];
    $season = $_POST['Season'];

    $sql = "INSERT INTO Crops (CropID, CropName, Season)
            VALUES ('$id', '$name', '$season')";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Crop added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

/* Delete Crop */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $sql = "DELETE FROM Crops WHERE CropID='$id'";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Crop deleted successfully!";
    } else {
        $message = "❌ Cannot delete this crop because it is being used by Plant Problems.";
    }
}

/* Get Crops */
$result = mysqli_query(
    $conn,
    "SELECT * FROM Crops ORDER BY CropID DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Crops</title>

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

.header p {
    color: #777;
    margin-top: 5px;
}

.admin {
    background: white;
    padding: 10px 18px;
    border-radius: 10px;
}

/* Message */

.message {
    background: #e8f7ed;
    color: #075c3b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* Add Box */

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
    grid-template-columns: 1fr 2fr 2fr auto;
    gap: 15px;
}

input,
select {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

button {
    padding: 12px 20px;
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

/* Table */

.table-box {
    background: white;
    padding: 22px;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    overflow-x: auto;
}

.table-box h2 {
    color: #075c3b;
    margin-bottom: 18px;
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
    padding: 13px;
    border-bottom: 1px solid #eee;
}

.delete {
    background: #d62828;
    color: white;
    padding: 7px 11px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
}

.delete:hover {
    background: #a91d1d;
}

/* Responsive */

@media(max-width: 850px) {

    .form {
        grid-template-columns: 1fr 1fr;
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
            <a href="products.php">
                💊 Products
            </a>
        </li>

        <li>
            <a href="crops.php" class="active">
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

            <h1>🌾 Crop Management</h1>

            <p>Manage crops available in your agriculture system</p>

        </div>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <!-- MESSAGE -->

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <!-- ADD CROP -->

    <div class="add-box">

        <h2>➕ Add New Crop</h2>

        <form method="POST">

            <div class="form">

                <input
                    type="number"
                    name="CropID"
                    placeholder="Crop ID"
                    required
                >

                <input
                    type="text"
                    name="CropName"
                    placeholder="Crop Name"
                    required
                >

                <select name="Season" required>

                    <option value="">
                        Select Season
                    </option>

                    <option>Kharif</option>
                    <option>Rabi</option>
                    <option>Zaid</option>
                    <option>All Season</option>

                </select>

                <button
                    type="submit"
                    name="add_crop">
                    ➕ Add Crop
                </button>

            </div>

        </form>

    </div>


    <!-- CROPS TABLE -->

    <div class="table-box">

        <h2>🌱 Available Crops</h2>

        <table>

            <tr>

                <th>Crop ID</th>
                <th>Crop Name</th>
                <th>Season</th>
                <th>Action</th>

            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $row['CropID']; ?>
                </td>

                <td>
                    🌿 <?php echo htmlspecialchars($row['CropName']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['Season']); ?>
                </td>

                <td>

                    <a
                        class="delete"
                        href="crops.php?delete=<?php echo $row['CropID']; ?>"
                        onclick="return confirm('Delete this crop?');"
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