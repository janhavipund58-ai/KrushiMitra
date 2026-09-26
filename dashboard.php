<?php
/* ============================================================
   KRUSHIMITRA - ATTRACTIVE AGRICULTURE DASHBOARD
   ============================================================ */

include "db.php";

/* ------------------------------------------------------------
   DISPLAY NAME
   If login/session is added later, this can become dynamic.
   ------------------------------------------------------------ */

session_start();

$username = $_SESSION['username'] ?? 'Janhavi';


/* ============================================================
   DASHBOARD STATISTICS
   ============================================================ */

/* Total Customers */
$total_customers = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Customers"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_customers = (int)$row['total'];
}


/* Total Products */
$total_products = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Products"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_products = (int)$row['total'];
}


/* Total Stock */
$total_stock = 0;

$result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(Stock),0) AS total FROM Products"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_stock = (int)$row['total'];
}


/* Today's Sales */
$today_sales = 0;

$result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(TotalAmount),0) AS total
     FROM Sales
     WHERE DATE(SaleDate) = CURDATE()"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $today_sales = (float)$row['total'];
}


/* Total Sales */
$total_sales = 0;

$result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(TotalAmount),0) AS total
     FROM Sales"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_sales = (float)$row['total'];
}


/* Total Transactions */
$total_transactions = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Sales"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_transactions = (int)$row['total'];
}


/* Average Sale */
$average_sale = 0;

$result = mysqli_query(
    $conn,
    "SELECT COALESCE(AVG(TotalAmount),0) AS average_sale
     FROM Sales"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $average_sale = (float)$row['average_sale'];
}


/* Low Stock Count */
$low_stock_count = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM Products
     WHERE Stock <= 5"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $low_stock_count = (int)$row['total'];
}


/* ============================================================
   SALES CHART
   ============================================================ */

$sales_labels = [];
$sales_values = [];

$result = mysqli_query(
    $conn,
    "SELECT
        DATE(SaleDate) AS sale_day,
        COALESCE(SUM(TotalAmount),0) AS total
     FROM Sales
     WHERE SaleDate >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
     GROUP BY DATE(SaleDate)
     ORDER BY sale_day ASC"
);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $sales_labels[] = date(
            "d M",
            strtotime($row['sale_day'])
        );

        $sales_values[] = (float)$row['total'];
    }
}


/* ============================================================
   CATEGORY CHART
   ============================================================ */

$category_labels = [];
$category_values = [];

$result = mysqli_query(
    $conn,
    "SELECT
        Category,
        COUNT(*) AS total
     FROM Products
     GROUP BY Category
     ORDER BY total DESC"
);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $category_labels[] = $row['Category'];
        $category_values[] = (int)$row['total'];
    }
}


/* ============================================================
   STOCK CHART
   ============================================================ */

$stock_labels = [];
$stock_values = [];

$result = mysqli_query(
    $conn,
    "SELECT
        ProductName,
        Stock
     FROM Products
     ORDER BY Stock DESC
     LIMIT 8"
);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $stock_labels[] = $row['ProductName'];
        $stock_values[] = (int)$row['Stock'];
    }
}


/* ============================================================
   STOCK STATUS
   ============================================================ */

$stock_status_labels = [];
$stock_status_values = [];

$result = mysqli_query(
    $conn,
    "SELECT
        CASE
            WHEN Stock <= 2 THEN 'Critical'
            WHEN Stock <= 5 THEN 'Low'
            ELSE 'Normal'
        END AS stock_status,
        COUNT(*) AS total
     FROM Products
     GROUP BY stock_status"
);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $stock_status_labels[] =
            $row['stock_status'];

        $stock_status_values[] =
            (int)$row['total'];
    }
}


/* ============================================================
   RECENT PRODUCTS
   ============================================================ */

$recent_products = mysqli_query(
    $conn,
    "SELECT
        ProductID,
        ProductName,
        Category,
        Price,
        Stock
     FROM Products
     ORDER BY ProductID DESC
     LIMIT 6"
);


/* ============================================================
   LOW STOCK PRODUCTS
   ============================================================ */

$low_stock_products = mysqli_query(
    $conn,
    "SELECT
        ProductName,
        Category,
        Stock
     FROM Products
     WHERE Stock <= 5
     ORDER BY Stock ASC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>KrushiMitra Dashboard</title>


<!-- Font Awesome -->

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
>


<!-- Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<style>

/* ============================================================
   RESET
   ============================================================ */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}


body {

    background:
        linear-gradient(
            135deg,
            #f0fdf4,
            #f8fafc
        );

    color: #1f2937;

}


/* ============================================================
   SIDEBAR
   ============================================================ */

.sidebar {

    position: fixed;

    left: 0;
    top: 0;

    width: 260px;
    height: 100vh;

    padding: 25px 16px;

    background:
        linear-gradient(
            180deg,
            #064e3b,
            #047857,
            #059669
        );

    color: white;

    box-shadow:
        5px 0 25px
        rgba(0,0,0,0.12);

    overflow-y: auto;

    z-index: 1000;

}


/* LOGO */

.logo {

    text-align: center;

    padding-bottom: 22px;

    border-bottom:
        1px solid
        rgba(255,255,255,0.15);

}


.logo-icon {

    font-size: 50px;

    margin-bottom: 5px;

}


.logo h2 {

    font-size: 24px;

    letter-spacing: 0.5px;

}


.logo p {

    font-size: 11px;

    opacity: 0.75;

    margin-top: 5px;

}


/* USER */

.user-card {

    margin: 22px 0;

    padding: 14px;

    border-radius: 16px;

    background:
        rgba(255,255,255,0.12);

    backdrop-filter: blur(10px);

    display: flex;

    align-items: center;

    gap: 12px;

}


.user-avatar {

    width: 45px;
    height: 45px;

    border-radius: 50%;

    background: white;

    color: #047857;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 20px;

    font-weight: bold;

}


.user-info small {

    display: block;

    font-size: 10px;

    opacity: 0.7;

}


.user-info strong {

    display: block;

    font-size: 15px;

    margin-top: 2px;

}


/* MENU */

.menu-label {

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: 1px;

    opacity: 0.55;

    padding: 10px 12px;

}


.sidebar a {

    display: flex;

    align-items: center;

    gap: 13px;

    color: white;

    text-decoration: none;

    padding: 12px 14px;

    border-radius: 11px;

    margin-bottom: 4px;

    font-size: 14px;

    transition: 0.25s;

}


.sidebar a i {

    width: 20px;

    text-align: center;

}


.sidebar a:hover,
.sidebar a.active {

    background:
        rgba(255,255,255,0.16);

    transform: translateX(3px);

}


.logout {

    margin-top: 20px;

}


/* ============================================================
   MAIN
   ============================================================ */

.main {

    margin-left: 260px;

    padding: 25px;

}


/* ============================================================
   TOPBAR
   ============================================================ */

.topbar {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 24px 28px;

    border-radius: 22px;

    margin-bottom: 24px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #065f46,
            #059669
        );

    box-shadow:
        0 12px 30px
        rgba(5,150,105,0.22);

    position: relative;

    overflow: hidden;

}


.topbar::after {

    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    right: -70px;
    top: -100px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

}


.topbar h1 {

    font-size: 28px;

    margin-bottom: 7px;

}


.topbar p {

    font-size: 14px;

    opacity: 0.82;

}


.top-user {

    display: flex;

    align-items: center;

    gap: 10px;

    background:
        rgba(255,255,255,0.14);

    padding: 10px 15px;

    border-radius: 30px;

    z-index: 2;

}


.top-user i {

    font-size: 22px;

}


/* ============================================================
   STAT CARDS
   ============================================================ */

.stats {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 18px;

    margin-bottom: 24px;

}


.stat {

    background: white;

    padding: 20px;

    border-radius: 18px;

    display: flex;

    align-items: center;

    gap: 15px;

    box-shadow:
        0 7px 25px
        rgba(0,0,0,0.06);

    border:
        1px solid
        #ecfdf5;

    transition: 0.3s;

}


.stat:hover {

    transform: translateY(-5px);

    box-shadow:
        0 12px 30px
        rgba(0,0,0,0.1);

}


.stat-icon {

    width: 55px;
    height: 55px;

    border-radius: 15px;

    display: flex;

    justify-content: center;

    align-items: center;

    font-size: 22px;

}


.green {

    background: #dcfce7;
    color: #15803d;

}


.blue {

    background: #dbeafe;
    color: #2563eb;

}


.orange {

    background: #ffedd5;
    color: #ea580c;

}


.purple {

    background: #f3e8ff;
    color: #9333ea;

}


.stat h2 {

    font-size: 25px;

}


.stat p {

    font-size: 12px;

    color: #94a3b8;

    margin-top: 3px;

}


/* ============================================================
   SECTION HEADING
   ============================================================ */

.section-heading {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin: 25px 0 15px;

}


.section-heading h2 {

    font-size: 21px;

    color: #064e3b;

}


.section-heading span {

    font-size: 12px;

    color: #94a3b8;

}


/* ============================================================
   ANALYTICS CARDS
   ============================================================ */

.analytics {

    display: grid;

    grid-template-columns:
        repeat(4,1fr);

    gap: 16px;

}


.analytics-card {

    background: white;

    border-radius: 16px;

    padding: 18px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.05);

}


.analytics-card .label {

    font-size: 11px;

    color: #94a3b8;

    text-transform: uppercase;

}


.analytics-card .value {

    font-size: 24px;

    font-weight: 700;

    color: #047857;

    margin-top: 8px;

}


/* ============================================================
   CHART GRID
   ============================================================ */

.chart-grid {

    display: grid;

    grid-template-columns:
        repeat(2,1fr);

    gap: 20px;

}


.chart-card {

    background: white;

    border-radius: 20px;

    padding: 20px;

    box-shadow:
        0 7px 25px
        rgba(0,0,0,0.055);

}


.chart-card.large {

    grid-column:
        1 / -1;

}


.chart-title {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 15px;

}


.chart-title h3 {

    color: #064e3b;

    font-size: 16px;

}


.chart-title i {

    color: #059669;

}


.chart-box {

    height: 290px;

    position: relative;

}


/* ============================================================
   PLANT AI CARD
   ============================================================ */

.ai-card {

    margin: 25px 0;

    padding: 28px;

    border-radius: 22px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #064e3b,
            #059669,
            #10b981
        );

    display: flex;

    justify-content: space-between;

    align-items: center;

    box-shadow:
        0 12px 30px
        rgba(5,150,105,0.2);

}


.ai-content h2 {

    font-size: 22px;

    margin-bottom: 8px;

}


.ai-content p {

    font-size: 13px;

    line-height: 1.6;

    opacity: 0.85;

    max-width: 600px;

}


.ai-btn {

    background: white;

    color: #047857;

    text-decoration: none;

    padding: 13px 20px;

    border-radius: 12px;

    font-weight: bold;

    white-space: nowrap;

}


.ai-btn:hover {

    background: #ecfdf5;

}


/* ============================================================
   BOTTOM GRID
   ============================================================ */

.bottom-grid {

    display: grid;

    grid-template-columns:
        1.5fr 1fr;

    gap: 20px;

}


/* ============================================================
   TABLE
   ============================================================ */

.card {

    background: white;

    border-radius: 20px;

    padding: 20px;

    box-shadow:
        0 7px 25px
        rgba(0,0,0,0.055);

}


.card-title {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 16px;

}


.card-title h3 {

    color: #064e3b;

    font-size: 18px;

}


.card-title a {

    color: #059669;

    text-decoration: none;

    font-size: 12px;

    font-weight: bold;

}


.table-wrap {

    overflow-x: auto;

}


table {

    width: 100%;

    border-collapse: collapse;

}


th {

    background: #f0fdf4;

    color: #047857;

    font-size: 11px;

    padding: 12px;

    text-align: left;

}


td {

    padding: 12px;

    font-size: 12px;

    border-bottom:
        1px solid
        #f1f5f9;

}


td strong {

    color: #334155;

}


.badge {

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;

    font-weight: bold;

}


.badge-normal {

    background: #dcfce7;

    color: #15803d;

}


.badge-low {

    background: #ffedd5;

    color: #c2410c;

}


.badge-critical {

    background: #fee2e2;

    color: #b91c1c;

}


/* ============================================================
   LOW STOCK
   ============================================================ */

.low-item {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 13px 0;

    border-bottom:
        1px solid
        #f1f5f9;

}


.low-item:last-child {

    border-bottom: none;

}


.low-name {

    font-weight: 600;

    font-size: 13px;

}


.low-category {

    font-size: 10px;

    color: #94a3b8;

    margin-top: 3px;

}


.stock-number {

    font-weight: bold;

    color: #dc2626;

}


/* ============================================================
   QUICK ACTIONS
   ============================================================ */

.actions {

    display: grid;

    grid-template-columns:
        repeat(2,1fr);

    gap: 10px;

}


.action {

    text-decoration: none;

    padding: 13px;

    border-radius: 12px;

    background: #f0fdf4;

    color: #047857;

    font-size: 12px;

    font-weight: 600;

    display: flex;

    align-items: center;

    gap: 9px;

    transition: 0.2s;

}


.action:hover {

    background: #dcfce7;

    transform: translateY(-2px);

}


/* ============================================================
   FOOTER
   ============================================================ */

footer {

    text-align: center;

    padding: 25px;

    color: #94a3b8;

    font-size: 12px;

}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media(max-width:1100px) {

    .stats,
    .analytics {

        grid-template-columns:
            repeat(2,1fr);

    }

}


@media(max-width:850px) {

    .sidebar {

        width: 220px;

    }

    .main {

        margin-left: 220px;

    }

    .chart-grid,
    .bottom-grid {

        grid-template-columns: 1fr;

    }

}


@media(max-width:650px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

    }

    .main {

        margin-left: 0;

        padding: 15px;

    }

    .stats,
    .analytics {

        grid-template-columns: 1fr;

    }

    .topbar {

        flex-direction: column;

        align-items: flex-start;

        gap: 15px;

    }

    .ai-card {

        flex-direction: column;

        align-items: flex-start;

        gap: 20px;

    }

}

</style>

</head>


<body>


<!-- =========================================================
     SIDEBAR
     ========================================================= -->

<aside class="sidebar">


    <div class="logo">

        <div class="logo-icon">
            🌱
        </div>

        <h2>KrushiMitra</h2>

        <p>
            Smart Agriculture Management
        </p>

    </div>


    <!-- USER -->

    <div class="user-card">

        <div class="user-avatar">

            <?php
            echo strtoupper(
                substr($username,0,1)
            );
            ?>

        </div>

        <div class="user-info">

            <small>
                Welcome
            </small>

            <strong>
                <?php
                echo htmlspecialchars($username);
                ?>
            </strong>

        </div>

    </div>


    <div class="menu-label">
        Main Menu
    </div>


    <a
        href="dashboard.php"
        class="active"
    >

        <i class="fas fa-chart-pie"></i>

        Dashboard

    </a>


    <a href="products.php">

        <i class="fas fa-box"></i>

        Products

    </a>


    <a href="crops.php">

        <i class="fas fa-seedling"></i>

        Crops

    </a>


    <a href="problems.php">

        <i class="fas fa-bug"></i>

        Crop Problems

    </a>


    <a href="find_product.php">

        <i class="fas fa-search"></i>

        Find Product

    </a>


    <a href="customers.php">

        <i class="fas fa-users"></i>

        Customers

    </a>


    <a href="sales.php">

        <i class="fas fa-cart-shopping"></i>

        Sales

    </a>


    <a href="stock.php">

        <i class="fas fa-warehouse"></i>

        Stock

    </a>


    <a href="reports.php">

        <i class="fas fa-file-lines"></i>

        Reports

    </a>


    <div class="menu-label">
        Account
    </div>


    <a href="logout.php" class="logout">

        <i class="fas fa-right-from-bracket"></i>

        Logout

    </a>


</aside>


<!-- =========================================================
     MAIN CONTENT
     ========================================================= -->

<main class="main">


    <!-- TOP BAR -->

    <section class="topbar">

        <div>

            <h1>
                Good Morning, <?php
                echo htmlspecialchars($username);
                ?> 👋
            </h1>

            <p>
                Welcome to your KrushiMitra smart agriculture dashboard.
            </p>

        </div>


        <div class="top-user">

            <i class="fas fa-circle-user"></i>

            <?php
            echo htmlspecialchars($username);
            ?>

        </div>

    </section>


    <!-- =====================================================
         STATISTICS
         ===================================================== -->

    <div class="stats">


        <div class="stat">

            <div class="stat-icon green">

                <i class="fas fa-users"></i>

            </div>

            <div>

                <h2>
                    <?php
                    echo number_format($total_customers);
                    ?>
                </h2>

                <p>
                    Total Customers
                </p>

            </div>

        </div>


        <div class="stat">

            <div class="stat-icon blue">

                <i class="fas fa-box-open"></i>

            </div>

            <div>

                <h2>
                    <?php
                    echo number_format($total_products);
                    ?>
                </h2>

                <p>
                    Total Products
                </p>

            </div>

        </div>


        <div class="stat">

            <div class="stat-icon orange">

                <i class="fas fa-layer-group"></i>

            </div>

            <div>

                <h2>
                    <?php
                    echo number_format($total_stock);
                    ?>
                </h2>

                <p>
                    Total Stock
                </p>

            </div>

        </div>


        <div class="stat">

            <div class="stat-icon purple">

                <i class="fas fa-indian-rupee-sign"></i>

            </div>

            <div>

                <h2>
                    ₹<?php
                    echo number_format(
                        $today_sales,
                        0
                    );
                    ?>
                </h2>

                <p>
                    Today's Sales
                </p>

            </div>

        </div>


    </div>


    <!-- =====================================================
         ANALYTICS
         ===================================================== -->

    <div class="section-heading">

        <h2>
            📊 Business Analytics
        </h2>

        <span>
            Live database statistics
        </span>

    </div>


    <div class="analytics">


        <div class="analytics-card">

            <div class="label">
                Total Sales
            </div>

            <div class="value">
                ₹<?php
                echo number_format(
                    $total_sales,
                    2
                );
                ?>
            </div>

        </div>


        <div class="analytics-card">

            <div class="label">
                Average Sale
            </div>

            <div class="value">
                ₹<?php
                echo number_format(
                    $average_sale,
                    2
                );
                ?>
            </div>

        </div>


        <div class="analytics-card">

            <div class="label">
                Transactions
            </div>

            <div class="value">
                <?php
                echo number_format(
                    $total_transactions
                );
                ?>
            </div>

        </div>


        <div class="analytics-card">

            <div class="label">
                Low Stock Items
            </div>

            <div class="value">
                <?php
                echo number_format(
                    $low_stock_count
                );
                ?>
            </div>

        </div>


    </div>


    <!-- =====================================================
         CHARTS
         ===================================================== -->

    <div class="section-heading">

        <h2>
            📈 Data Visualization
        </h2>

        <span>
            Sales & inventory insights
        </span>

    </div>


    <div class="chart-grid">


        <!-- SALES -->

        <div class="chart-card large">

            <div class="chart-title">

                <h3>
                    <i class="fas fa-chart-line"></i>
                    Sales Trend - Last 7 Days
                </h3>

                <i class="fas fa-calendar-days"></i>

            </div>

            <div class="chart-box">

                <canvas
                    id="salesChart"
                ></canvas>

            </div>

        </div>


        <!-- CATEGORY -->

        <div class="chart-card">

            <div class="chart-title">

                <h3>
                    <i class="fas fa-chart-pie"></i>
                    Products by Category
                </h3>

            </div>

            <div class="chart-box">

                <canvas
                    id="categoryChart"
                ></canvas>

            </div>

        </div>


        <!-- STOCK -->

        <div class="chart-card">

            <div class="chart-title">

                <h3>
                    <i class="fas fa-chart-column"></i>
                    Product Stock
                </h3>

            </div>

            <div class="chart-box">

                <canvas
                    id="stockChart"
                ></canvas>

            </div>

        </div>


        <!-- STOCK STATUS -->

        <div class="chart-card large">

            <div class="chart-title">

                <h3>
                    <i class="fas fa-boxes-stacked"></i>
                    Inventory Health
                </h3>

            </div>

            <div class="chart-box">

                <canvas
                    id="stockStatusChart"
                ></canvas>

            </div>

        </div>


    </div>


    <!-- =====================================================
         AI PLANT DETECTION
         ===================================================== -->

    <section class="ai-card">

        <div class="ai-content">

            <h2>
                🌿 AI Plant Disease Detection
            </h2>

            <p>
                Upload a crop or plant image and use the
                KrushiMitra plant detection feature to identify
                possible plant diseases and find suitable
                agricultural treatment information.
            </p>

        </div>


        <a
            href="plant_detection.php"
            class="ai-btn"
        >

            <i class="fas fa-camera"></i>

            Detect Plant

        </a>

    </section>


    <!-- =====================================================
         RECENT PRODUCTS + LOW STOCK
         ===================================================== -->

    <div class="bottom-grid">


        <!-- RECENT PRODUCTS -->

        <div class="card">

            <div class="card-title">

                <h3>
                    📦 Recent Products
                </h3>

                <a href="products.php">
                    View All →
                </a>

            </div>


            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Product
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Stock
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if (
                        $recent_products &&
                        mysqli_num_rows(
                            $recent_products
                        ) > 0
                    ):

                        while (
                            $product =
                            mysqli_fetch_assoc(
                                $recent_products
                            )
                        ):

                            $stock =
                                (int)$product['Stock'];

                            if ($stock <= 2) {

                                $badge =
                                    "badge-critical";

                            } elseif ($stock <= 5) {

                                $badge =
                                    "badge-low";

                            } else {

                                $badge =
                                    "badge-normal";

                            }

                    ?>

                        <tr>

                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $product['ProductName']
                                    );
                                    ?>

                                </strong>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $product['Category']
                                );
                                ?>

                            </td>


                            <td>

                                ₹<?php
                                echo number_format(
                                    (float)$product['Price'],
                                    2
                                );
                                ?>

                            </td>


                            <td>

                                <span class="badge <?php
                                    echo $badge;
                                ?>">

                                    <?php
                                    echo $stock;
                                    ?>

                                </span>

                            </td>

                        </tr>

                    <?php

                        endwhile;

                    else:

                    ?>

                        <tr>

                            <td
                                colspan="4"
                                style="text-align:center;"
                            >
                                No products available.
                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- LOW STOCK -->

        <div class="card">

            <div class="card-title">

                <h3>
                    ⚠️ Low Stock Alert
                </h3>

                <a href="stock.php">
                    Manage
                </a>

            </div>


            <?php

            if (
                $low_stock_products &&
                mysqli_num_rows(
                    $low_stock_products
                ) > 0
            ):

                while (
                    $product =
                    mysqli_fetch_assoc(
                        $low_stock_products
                    )
                ):

            ?>

                <div class="low-item">

                    <div>

                        <div class="low-name">

                            <?php
                            echo htmlspecialchars(
                                $product['ProductName']
                            );
                            ?>

                        </div>

                        <div class="low-category">

                            <?php
                            echo htmlspecialchars(
                                $product['Category']
                            );
                            ?>

                        </div>

                    </div>


                    <div class="stock-number">

                        <?php
                        echo (int)$product['Stock'];
                        ?>

                        left

                    </div>

                </div>

            <?php

                endwhile;

            else:

            ?>

                <div
                    style="
                    text-align:center;
                    padding:30px;
                    color:#64748b;
                    "
                >

                    <div
                        style="
                        font-size:35px;
                        margin-bottom:10px;
                        "
                    >
                        ✅
                    </div>

                    All products have sufficient stock.

                </div>

            <?php endif; ?>

        </div>


    </div>


    <!-- =====================================================
         QUICK ACTIONS
         ===================================================== -->

    <div class="section-heading">

        <h2>
            ⚡ Quick Actions
        </h2>

    </div>


    <div class="actions">

        <a
            href="products.php"
            class="action"
        >

            <i class="fas fa-plus"></i>

            Add Product

        </a>


        <a
            href="customers.php"
            class="action"
        >

            <i class="fas fa-user-plus"></i>

            Add Customer

        </a>


        <a
            href="sales.php"
            class="action"
        >

            <i class="fas fa-cart-plus"></i>

            New Sale

        </a>


        <a
            href="stock.php"
            class="action"
        >

            <i class="fas fa-boxes-stacked"></i>

            Manage Stock

        </a>


        <a
            href="reports.php"
            class="action"
        >

            <i class="fas fa-file-lines"></i>

            Reports

        </a>


        <a
            href="find_product.php"
            class="action"
        >

            <i class="fas fa-search"></i>

            Find Product

        </a>

    </div>


    <footer>

        🌱 KrushiMitra Agriculture Management System
        © 2026

    </footer>


</main>


<!-- =========================================================
     CHART JAVASCRIPT
     ========================================================= -->

<script>

/* PHP DATA */

const salesLabels =
<?php echo json_encode($sales_labels); ?>;

const salesValues =
<?php echo json_encode($sales_values); ?>;


const categoryLabels =
<?php echo json_encode($category_labels); ?>;

const categoryValues =
<?php echo json_encode($category_values); ?>;


const stockLabels =
<?php echo json_encode($stock_labels); ?>;

const stockValues =
<?php echo json_encode($stock_values); ?>;


const stockStatusLabels =
<?php echo json_encode($stock_status_labels); ?>;

const stockStatusValues =
<?php echo json_encode($stock_status_values); ?>;


/* ============================================================
   SALES LINE CHART
   ============================================================ */

new Chart(

    document.getElementById(
        "salesChart"
    ),

    {

        type: "line",

        data: {

            labels: salesLabels,

            datasets: [{

                label: "Sales",

                data: salesValues,

                borderWidth: 3,

                tension: 0.4,

                fill: true,

                pointRadius: 5

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: true

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback:
                        function(value) {

                            return "₹" + value;

                        }

                    }

                }

            }

        }

    }

);


/* ============================================================
   CATEGORY DOUGHNUT
   ============================================================ */

new Chart(

    document.getElementById(
        "categoryChart"
    ),

    {

        type: "doughnut",

        data: {

            labels: categoryLabels,

            datasets: [{

                data: categoryValues,

                borderWidth: 2

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: "bottom"

                }

            }

        }

    }

);


/* ============================================================
   STOCK BAR CHART
   ============================================================ */

new Chart(

    document.getElementById(
        "stockChart"
    ),

    {

        type: "bar",

        data: {

            labels: stockLabels,

            datasets: [{

                label: "Stock",

                data: stockValues,

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true

                }

            },

            plugins: {

                legend: {

                    display: false

                }

            }

        }

    }

);


/* ============================================================
   STOCK STATUS PIE
   ============================================================ */

new Chart(

    document.getElementById(
        "stockStatusChart"
    ),

    {

        type: "pie",

        data: {

            labels: stockStatusLabels,

            datasets: [{

                data: stockStatusValues,

                borderWidth: 2

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: "bottom"

                }

            }

        }

    }

);

</script>


</body>

</html>