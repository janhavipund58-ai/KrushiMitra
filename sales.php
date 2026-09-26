<?php
include "db.php";
session_start();

$message = "";

/* ============================
   ADD NEW SALE
============================ */

if (isset($_POST['add_sale'])) {

    $customer_id = intval($_POST['customer_id']);
    $total_amount = floatval($_POST['total_amount']);
    $sale_date = $_POST['sale_date'];

    if ($total_amount > 0 && !empty($sale_date)) {

        $stmt = $conn->prepare(
            "INSERT INTO Sales (CustomerID, TotalAmount, SaleDate)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "ids",
            $customer_id,
            $total_amount,
            $sale_date
        );

        if ($stmt->execute()) {
            $message = "Sale added successfully!";
        } else {
            $message = "Error adding sale: " . $conn->error;
        }

        $stmt->close();

    } else {
        $message = "Please enter valid sale details.";
    }
}


/* ============================
   DELETE SALE
============================ */

if (isset($_GET['delete'])) {

    $sale_id = intval($_GET['delete']);

    $stmt = $conn->prepare(
        "DELETE FROM Sales WHERE SaleID = ?"
    );

    $stmt->bind_param("i", $sale_id);

    if ($stmt->execute()) {
        $message = "Sale deleted successfully!";
    } else {
        $message = "Error deleting sale.";
    }

    $stmt->close();
}


/* ============================
   TOTAL SALES
============================ */

$total_sales = 0;

$result = $conn->query(
    "SELECT COALESCE(SUM(TotalAmount),0) AS total
     FROM Sales"
);

if ($result) {
    $row = $result->fetch_assoc();
    $total_sales = $row['total'];
}


/* ============================
   TODAY'S SALES
============================ */

$today_sales = 0;

$result = $conn->query(
    "SELECT COALESCE(SUM(TotalAmount),0) AS total
     FROM Sales
     WHERE DATE(SaleDate) = CURDATE()"
);

if ($result) {
    $row = $result->fetch_assoc();
    $today_sales = $row['total'];
}


/* ============================
   TOTAL TRANSACTIONS
============================ */

$total_transactions = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM Sales"
);

if ($result) {
    $row = $result->fetch_assoc();
    $total_transactions = $row['total'];
}


/* ============================
   AVERAGE SALE
============================ */

$average_sale = 0;

$result = $conn->query(
    "SELECT COALESCE(AVG(TotalAmount),0) AS average_sale
     FROM Sales"
);

if ($result) {
    $row = $result->fetch_assoc();
    $average_sale = $row['average_sale'];
}


/* ============================
   FETCH CUSTOMERS
============================ */

$customers = $conn->query(
    "SELECT CustomerID, CustomerName
     FROM Customers
     ORDER BY CustomerName ASC"
);


/* ============================
   FETCH SALES
============================ */

$sales = $conn->query(
    "SELECT 
        s.SaleID,
        s.CustomerID,
        s.TotalAmount,
        s.SaleDate,
        c.CustomerName
     FROM Sales s
     LEFT JOIN Customers c
     ON s.CustomerID = c.CustomerID
     ORDER BY s.SaleDate DESC, s.SaleID DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Sales - KrushiMitra</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: #f4f8f4;
    color: #333;
}

/* SIDEBAR */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 240px;
    height: 100vh;
    background: #14532d;
    color: white;
    padding: 25px 15px;
}

.logo {
    text-align: center;
    margin-bottom: 30px;
}

.logo h2 {
    color: #a7f3d0;
}

.logo p {
    font-size: 12px;
    color: #d1fae5;
}

.sidebar a {
    display: block;
    padding: 13px 15px;
    margin: 5px 0;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

.sidebar a:hover,
.sidebar a.active {
    background: #22c55e;
}

.logout {
    margin-top: 25px;
    background: #dc2626 !important;
}

/* MAIN */

.main {
    margin-left: 240px;
    padding: 30px;
}

/* TOPBAR */

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.topbar h1 {
    color: #14532d;
}

.user {
    background: white;
    padding: 10px 18px;
    border-radius: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* MESSAGE */

.message {
    background: #dcfce7;
    color: #166534;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* STATS */

.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-card h3 {
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
}

.stat-card .value {
    font-size: 28px;
    font-weight: bold;
    color: #15803d;
}

/* ADD SALE */

.form-card {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.form-card h2 {
    color: #14532d;
    margin-bottom: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 7px;
}

.btn {
    margin-top: 25px;
    padding: 11px 20px;
    background: #16a34a;
    border: none;
    color: white;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
}

.btn:hover {
    background: #15803d;
}

/* TABLE */

.table-card {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.table-card h2 {
    color: #14532d;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #14532d;
    color: white;
    padding: 13px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background: #f0fdf4;
}

.amount {
    color: #15803d;
    font-weight: bold;
}

.delete {
    background: #dc2626;
    color: white;
    padding: 7px 12px;
    text-decoration: none;
    border-radius: 5px;
}

.delete:hover {
    background: #b91c1c;
}

/* RESPONSIVE */

@media(max-width: 1000px) {

    .stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

}

@media(max-width: 700px) {

    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .main {
        margin-left: 0;
    }

    .stats {
        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>


<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <div class="logo">
        <h2>🌱 KrushiMitra</h2>
        <p>Agriculture Management</p>
    </div>

    <a href="dashboard.php">🏠 Dashboard</a>

    <a href="products.php">📦 Products</a>

    <a href="crops.php">🌾 Crops</a>

    <a href="problems.php">⚠️ Crop Problems</a>

    <a href="find_product.php">🔍 Find Product</a>

    <a href="customers.php">👥 Customers</a>

    <a href="sales.php" class="active">💰 Sales</a>

    <a href="stock.php">📊 Stock</a>

    <a href="reports.php">📈 Reports</a>

    <a href="logout.php" class="logout">🚪 Logout</a>

</div>


<!-- ================= MAIN ================= -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>
            <h1>💰 Sales Management</h1>
            <p>Manage and monitor your KrushiMitra sales</p>
        </div>

        <div class="user">
            👤 <?php echo htmlspecialchars($username); ?>
        </div>

    </div>


    <!-- MESSAGE -->

    <?php if (!empty($message)) { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>


    <!-- ================= STATS ================= -->

    <div class="stats">

        <div class="stat-card">
            <h3>💰 Total Sales</h3>
            <div class="value">
                ₹<?php echo number_format($total_sales, 2); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>📅 Today's Sales</h3>
            <div class="value">
                ₹<?php echo number_format($today_sales, 2); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>🧾 Transactions</h3>
            <div class="value">
                <?php echo $total_transactions; ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>📊 Average Sale</h3>
            <div class="value">
                ₹<?php echo number_format($average_sale, 2); ?>
            </div>
        </div>

    </div>


    <!-- ================= ADD SALE ================= -->

    <div class="form-card">

        <h2>➕ Add New Sale</h2>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Customer</label>

                    <select name="customer_id" required>

                        <option value="">
                            Select Customer
                        </option>

                        <?php

                        if ($customers) {

                            while ($customer = $customers->fetch_assoc()) {

                        ?>

                            <option value="<?php echo $customer['CustomerID']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $customer['CustomerName']
                                );
                                ?>

                            </option>

                        <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Total Amount</label>

                    <input
                        type="number"
                        name="total_amount"
                        step="0.01"
                        min="0"
                        placeholder="Enter amount"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Sale Date</label>

                    <input
                        type="date"
                        name="sale_date"
                        value="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                name="add_sale"
                class="btn"
            >
                ➕ Add Sale
            </button>

        </form>

    </div>


    <!-- ================= SALES TABLE ================= -->

    <div class="table-card">

        <h2>📋 Sales History</h2>

        <table>

            <thead>

                <tr>

                    <th>Sale ID</th>

                    <th>Customer</th>

                    <th>Total Amount</th>

                    <th>Sale Date</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            <?php

            if ($sales && $sales->num_rows > 0) {

                while ($sale = $sales->fetch_assoc()) {

            ?>

                <tr>

                    <td>
                        <?php echo $sale['SaleID']; ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $sale['CustomerName'] ?? 'Unknown Customer'
                        );
                        ?>
                    </td>

                    <td class="amount">
                        ₹<?php
                        echo number_format(
                            $sale['TotalAmount'],
                            2
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo date(
                            'd-m-Y',
                            strtotime($sale['SaleDate'])
                        );
                        ?>
                    </td>

                    <td>

                        <a
                            href="sales.php?delete=<?php echo $sale['SaleID']; ?>"
                            class="delete"
                            onclick="return confirm('Are you sure you want to delete this sale?');"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="5" style="text-align:center;">
                        No sales records found.
                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>