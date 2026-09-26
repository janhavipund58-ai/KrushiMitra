<?php
include "db.php";

/* Total Sales */
$sales_result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(TotalAmount),0) AS total FROM Sales"
);
$total_sales = mysqli_fetch_assoc($sales_result)['total'];

/* Total Bills */
$bills_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Sales"
);
$total_bills = mysqli_fetch_assoc($bills_result)['total'];

/* Total Customers */
$customer_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Customers"
);
$total_customers = mysqli_fetch_assoc($customer_result)['total'];

/* Total Products */
$product_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Products"
);
$total_products = mysqli_fetch_assoc($product_result)['total'];

/* Recent Sales */
$recent_sales = mysqli_query(
    $conn,
    "SELECT 
        s.SaleID,
        s.SaleDate,
        s.TotalAmount,
        c.CustomerName
     FROM Sales s
     LEFT JOIN Customers c
     ON s.CustomerID = c.CustomerID
     ORDER BY s.SaleID DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Reports</title>

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

/* SIDEBAR */

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

/* MAIN */

.main {
    margin-left: 240px;
    padding: 30px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
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

/* CARDS */

.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.07);
}

.card-icon {
    font-size: 30px;
}

.card p {
    color: #777;
    margin-top: 10px;
}

.card h2 {
    color: #075c3b;
    margin-top: 7px;
}

/* REPORT */

.report-box {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.07);
}

.report-box h2 {
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

.amount {
    color: #075c3b;
    font-weight: bold;
}

.print {
    margin-top: 20px;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    background: #075c3b;
    color: white;
    cursor: pointer;
}

.print:hover {
    background: #0b8f5a;
}

/* RESPONSIVE */

@media(max-width: 900px) {

    .cards {
        grid-template-columns: 1fr 1fr;
    }

}

@media print {

    .sidebar,
    .header,
    .print {
        display: none;
    }

    .main {
        margin-left: 0;
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
            <a href="dashboard.php">🏠 Dashboard</a>
        </li>

        <li>
            <a href="products.php">💊 Products</a>
        </li>

        <li>
            <a href="crops.php">🌾 Crops</a>
        </li>

        <li>
            <a href="problems.php">🐛 Plant Problems</a>
        </li>

        <li>
            <a href="find_product.php">🔍 Find Product</a>
        </li>

        <li>
            <a href="customers.php">👨‍🌾 Customers</a>
        </li>

        <li>
            <a href="billing.php">🧾 Sales & Billing</a>
        </li>

        <li>
            <a href="stock.php">📦 Stock</a>
        </li>

        <li>
            <a href="reports.php" class="active">
                📊 Reports
            </a>
        </li>

    </ul>

</div>


<!-- MAIN -->

<div class="main">

    <div class="header">

        <div>

            <h1>📊 Reports</h1>

            <p>KrushiMitra business and sales report</p>

        </div>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <!-- SUMMARY CARDS -->

    <div class="cards">

        <div class="card">

            <div class="card-icon">💰</div>

            <p>Total Sales</p>

            <h2>
                ₹<?php echo number_format($total_sales, 2); ?>
            </h2>

        </div>


        <div class="card">

            <div class="card-icon">🧾</div>

            <p>Total Bills</p>

            <h2>
                <?php echo $total_bills; ?>
            </h2>

        </div>


        <div class="card">

            <div class="card-icon">👨‍🌾</div>

            <p>Total Customers</p>

            <h2>
                <?php echo $total_customers; ?>
            </h2>

        </div>


        <div class="card">

            <div class="card-icon">💊</div>

            <p>Total Products</p>

            <h2>
                <?php echo $total_products; ?>
            </h2>

        </div>

    </div>


    <!-- SALES TABLE -->

    <div class="report-box">

        <h2>🧾 Sales History</h2>

        <table>

            <tr>

                <th>Bill No.</th>

                <th>Date</th>

                <th>Customer</th>

                <th>Total Amount</th>

            </tr>


            <?php

            if (mysqli_num_rows($recent_sales) > 0) {

                while($row = mysqli_fetch_assoc($recent_sales)) {

            ?>

            <tr>

                <td>
                    #<?php echo $row['SaleID']; ?>
                </td>

                <td>
                    <?php echo $row['SaleDate']; ?>
                </td>

                <td>
                    👨‍🌾
                    <?php
                    echo htmlspecialchars(
                        $row['CustomerName'] ?? 'Unknown'
                    );
                    ?>
                </td>

                <td class="amount">
                    ₹<?php
                    echo number_format(
                        $row['TotalAmount'],
                        2
                    );
                    ?>
                </td>

            </tr>

            <?php

                }

            } else {

            ?>

            <tr>

                <td colspan="4" style="text-align:center;">
                    No sales records found.
                </td>

            </tr>

            <?php } ?>

        </table>


        <button
            class="print"
            onclick="window.print()">

            🖨️ Print Report

        </button>

    </div>

</div>

</body>

</html>