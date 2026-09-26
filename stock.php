<?php
include "db.php";

$result = mysqli_query($conn, "SELECT * FROM Products ORDER BY Stock ASC");

$total_products = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM Products")
);

$total_stock = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(Stock) AS total FROM Products")
)['total'];

$low_stock = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM Products WHERE Stock <= 5")
);
?>

<!DOCTYPE html>
<html>
<head>

<title>KrushiMitra - Stock</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f8f5;
}

.sidebar {
    position: fixed;
    width: 240px;
    height: 100vh;
    background: #075c3b;
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
    color: white;
    text-decoration: none;
    border-radius: 9px;
}

.menu a:hover,
.menu .active {
    background: #0b8f5a;
}

.main {
    margin-left: 240px;
    padding: 30px;
}

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

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.07);
}

.card-icon {
    font-size: 30px;
}

.card h2 {
    margin-top: 10px;
    color: #075c3b;
}

.card p {
    color: #777;
}

.stock-box {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.07);
}

.stock-box h2 {
    color: #075c3b;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #e7f5ed;
    color: #075c3b;
    padding: 14px;
    text-align: left;
}

td {
    padding: 14px;
    border-bottom: 1px solid #eee;
}

.low {
    color: #d00000;
    font-weight: bold;
}

.available {
    color: #078347;
    font-weight: bold;
}

.badge {
    padding: 6px 10px;
    border-radius: 15px;
    font-size: 12px;
}

.badge-low {
    background: #ffe1e1;
    color: #c00000;
}

.badge-ok {
    background: #dff7e8;
    color: #078347;
}

@media(max-width: 800px) {
    .cards {
        grid-template-columns: 1fr;
    }
}

</style>

</head>

<body>

<div class="sidebar">

    <div class="logo">

        <div class="logo-icon">🌱</div>

        <h2>KRUSHIMITRA</h2>

        <p>Agriculture Shop</p>

    </div>

    <ul class="menu">

        <li><a href="dashboard.php">🏠 Dashboard</a></li>

        <li><a href="products.php">💊 Products</a></li>

        <li><a href="crops.php">🌾 Crops</a></li>

        <li><a href="problems.php">🐛 Plant Problems</a></li>

        <li><a href="find_product.php">🔍 Find Product</a></li>

        <li><a href="customers.php">👨‍🌾 Customers</a></li>

        <li><a href="billing.php">🧾 Sales & Billing</a></li>

        <li>
            <a href="stock.php" class="active">
                📦 Stock
            </a>
        </li>

        <li><a href="reports.php">📊 Reports</a></li>

    </ul>

</div>


<div class="main">

    <div class="header">

        <div>

            <h1>📦 Stock Management</h1>

            <p>Monitor agriculture product inventory</p>

        </div>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <div class="cards">

        <div class="card">

            <div class="card-icon">💊</div>

            <p>Total Products</p>

            <h2>
                <?php echo $total_products; ?>
            </h2>

        </div>


        <div class="card">

            <div class="card-icon">📦</div>

            <p>Total Stock</p>

            <h2>
                <?php echo $total_stock ?? 0; ?>
            </h2>

        </div>


        <div class="card">

            <div class="card-icon">⚠️</div>

            <p>Low Stock</p>

            <h2>
                <?php echo $low_stock; ?>
            </h2>

        </div>

    </div>


    <div class="stock-box">

        <h2>🌱 Available Products</h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Product</th>
                <th>Category</th>
                <th>Crop</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Expiry</th>

            </tr>


            <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $row['ProductID']; ?>
                </td>

                <td>
                    💊 <?php echo htmlspecialchars($row['ProductName']); ?>
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

                    <span class="<?php echo ($row['Stock'] <= 5) ? 'low' : 'available'; ?>">

                        <?php echo $row['Stock']; ?>

                    </span>

                </td>

                <td>

                    <?php if($row['Stock'] <= 5) { ?>

                        <span class="badge badge-low">
                            ⚠️ Low Stock
                        </span>

                    <?php } else { ?>

                        <span class="badge badge-ok">
                            ✓ Available
                        </span>

                    <?php } ?>

                </td>

                <td>
                    <?php echo $row['ExpiryDate']; ?>
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>