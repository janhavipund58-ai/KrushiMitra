<?php
include "db.php";

$message = "";
$bill = null;

/* Generate Bill */
if (isset($_POST['generate_bill'])) {

    $customer_id = $_POST['CustomerID'];
    $product_id = $_POST['ProductID'];
    $quantity = (int)$_POST['Quantity'];

    /* Get customer */
    $customer_query = mysqli_query(
        $conn,
        "SELECT * FROM Customers
         WHERE CustomerID='$customer_id'"
    );

    /* Get product */
    $product_query = mysqli_query(
        $conn,
        "SELECT * FROM Products
         WHERE ProductID='$product_id'"
    );

    if (mysqli_num_rows($customer_query) == 1 &&
        mysqli_num_rows($product_query) == 1) {

        $customer = mysqli_fetch_assoc($customer_query);
        $product = mysqli_fetch_assoc($product_query);

        if ($quantity <= 0) {

            $message = "❌ Quantity must be greater than 0.";

        } elseif ($quantity > $product['Stock']) {

            $message = "❌ Not enough stock available.";

        } else {

            $price = $product['Price'];
            $total = $price * $quantity;
            $date = date("Y-m-d");

            /* Insert Sale */
            $sale_sql = "INSERT INTO Sales
                        (CustomerID, SaleDate, TotalAmount)
                        VALUES
                        ('$customer_id', '$date', '$total')";

            if (mysqli_query($conn, $sale_sql)) {

                $sale_id = mysqli_insert_id($conn);

                /* Insert Sale Details */
                $detail_sql = "INSERT INTO Sale_Details
                              (SaleID, ProductID, Quantity, Price, Amount)
                              VALUES
                              ('$sale_id',
                               '$product_id',
                               '$quantity',
                               '$price',
                               '$total')";

                if (mysqli_query($conn, $detail_sql)) {

                    /* Reduce Stock */
                    mysqli_query(
                        $conn,
                        "UPDATE Products
                         SET Stock = Stock - $quantity
                         WHERE ProductID='$product_id'"
                    );

                    $bill = [
                        'SaleID' => $sale_id,
                        'Date' => $date,
                        'CustomerName' => $customer['CustomerName'],
                        'Phone' => $customer['Phone'],
                        'Address' => $customer['Address'],
                        'Village' => $customer['Village'],
                        'ProductName' => $product['ProductName'],
                        'Quantity' => $quantity,
                        'Price' => $price,
                        'Total' => $total
                    ];

                } else {

                    $message = "❌ Could not save sale details.";
                }

            } else {

                $message = "❌ Could not create sale.";
            }
        }

    } else {

        $message = "❌ Customer or Product not found.";
    }
}

/* Customers */
$customers = mysqli_query(
    $conn,
    "SELECT * FROM Customers ORDER BY CustomerName"
);

/* Products */
$products = mysqli_query(
    $conn,
    "SELECT * FROM Products
     WHERE Stock > 0
     ORDER BY ProductName"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Billing</title>

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

/* BILL FORM */

.bill-form {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.bill-form h2 {
    color: #075c3b;
    margin-bottom: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

select,
input {
    width: 100%;
    padding: 13px;
    border: 1px solid #ddd;
    border-radius: 9px;
}

button {
    padding: 13px 20px;
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

/* MESSAGE */

.message {
    background: #ffecec;
    color: #b00020;
    padding: 13px;
    border-radius: 9px;
    margin-bottom: 20px;
}

/* BILL */

.invoice {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.10);
}

.shop {
    text-align: center;
    border-bottom: 2px solid #075c3b;
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.shop h1 {
    color: #075c3b;
}

.shop p {
    color: #777;
    margin-top: 5px;
}

.bill-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.bill-info p {
    margin: 6px 0;
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

.total {
    text-align: right;
    font-size: 22px;
    font-weight: bold;
    color: #075c3b;
    margin-top: 20px;
}

.thank {
    text-align: center;
    margin-top: 30px;
    color: #075c3b;
    font-weight: bold;
}

.print {
    display: block;
    margin: 25px auto 0;
}

@media(max-width: 850px) {

    .form-grid {
        grid-template-columns: 1fr 1fr;
    }

}

@media print {

    .sidebar,
    .header,
    .bill-form,
    .print {
        display: none;
    }

    .main {
        margin: 0;
        padding: 0;
    }

    .invoice {
        box-shadow: none;
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

        <li><a href="dashboard.php">🏠 Dashboard</a></li>

        <li><a href="products.php">💊 Products</a></li>

        <li><a href="crops.php">🌾 Crops</a></li>

        <li><a href="problems.php">🐛 Plant Problems</a></li>

        <li><a href="find_product.php">🔍 Find Product</a></li>

        <li><a href="customers.php">👨‍🌾 Customers</a></li>

        <li>
            <a href="billing.php" class="active">
                🧾 Billing
            </a>
        </li>

        <li><a href="stock.php">📦 Stock</a></li>

        <li><a href="reports.php">📊 Reports</a></li>

    </ul>

</div>


<!-- MAIN -->

<div class="main">

    <div class="header">

        <div>

            <h1>🧾 Generate Billing</h1>

            <p>Create a customer purchase bill</p>

        </div>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>


    <!-- FORM -->

    <div class="bill-form">

        <h2>🛒 New Sale</h2>

        <form method="POST">

            <div class="form-grid">

                <div>

                    <label>👨‍🌾 Customer</label>

                    <select name="CustomerID" required>

                        <option value="">
                            Select Customer
                        </option>

                        <?php while($customer = mysqli_fetch_assoc($customers)) { ?>

                            <option value="<?php echo $customer['CustomerID']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $customer['CustomerName']
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div>

                    <label>💊 Product</label>

                    <select name="ProductID" required>

                        <option value="">
                            Select Product
                        </option>

                        <?php while($product = mysqli_fetch_assoc($products)) { ?>

                            <option value="<?php echo $product['ProductID']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $product['ProductName']
                                );
                                ?>

                                -
                                ₹<?php echo $product['Price']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div>

                    <label>🔢 Quantity</label>

                    <input
                        type="number"
                        name="Quantity"
                        min="1"
                        required
                        placeholder="Enter quantity"
                    >

                </div>


                <div>

                    <button
                        type="submit"
                        name="generate_bill">

                        🧾 Generate Bill

                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- GENERATED BILL -->

    <?php if ($bill) { ?>

    <div class="invoice">

        <div class="shop">

            <h1>🌱 KRUSHIMITRA</h1>

            <p>Agriculture Shop</p>

            <p>Quality Products for Better Farming</p>

        </div>


        <div class="bill-info">

            <div>

                <p>
                    <b>Bill No:</b>
                    #<?php echo $bill['SaleID']; ?>
                </p>

                <p>
                    <b>Date:</b>
                    <?php echo $bill['Date']; ?>
                </p>

            </div>


            <div>

                <p>
                    <b>Customer:</b>
                    <?php echo htmlspecialchars($bill['CustomerName']); ?>
                </p>

                <p>
                    <b>Phone:</b>
                    <?php echo htmlspecialchars($bill['Phone']); ?>
                </p>

                <p>
                    <b>Village:</b>
                    <?php echo htmlspecialchars($bill['Village']); ?>
                </p>

            </div>

        </div>


        <table>

            <tr>

                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Amount</th>

            </tr>

            <tr>

                <td>
                    💊 <?php echo htmlspecialchars($bill['ProductName']); ?>
                </td>

                <td>
                    <?php echo $bill['Quantity']; ?>
                </td>

                <td>
                    ₹<?php echo number_format($bill['Price'], 2); ?>
                </td>

                <td>
                    ₹<?php echo number_format($bill['Total'], 2); ?>
                </td>

            </tr>

        </table>


        <div class="total">

            Total Amount:
            ₹<?php echo number_format($bill['Total'], 2); ?>

        </div>


        <div class="thank">

            🌱 Thank You for Shopping with KrushiMitra!

        </div>


        <button
            class="print"
            onclick="window.print()">

            🖨️ Print Bill

        </button>

    </div>

    <?php } ?>

</div>

</body>

</html>