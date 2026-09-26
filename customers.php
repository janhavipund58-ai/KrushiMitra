<?php
include "db.php";

$message = "";

/* Add Customer */
if (isset($_POST['add_customer'])) {

    $id = $_POST['CustomerID'];
    $name = $_POST['CustomerName'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];
    $village = $_POST['Village'];

    $sql = "INSERT INTO Customers
            (CustomerID, CustomerName, Phone, Address, Village)
            VALUES
            ('$id', '$name', '$phone', '$address', '$village')";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Customer added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

/* Delete Customer */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    if (mysqli_query($conn,
        "DELETE FROM Customers WHERE CustomerID='$id'")) {

        $message = "✅ Customer deleted successfully!";

    } else {

        $message = "❌ Customer cannot be deleted.";
    }
}

/* Display Customers */
$result = mysqli_query(
    $conn,
    "SELECT * FROM Customers ORDER BY CustomerID DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

<title>KrushiMitra - Customers</title>

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

/* MESSAGE */

.message {
    background: #e8f7ed;
    color: #075c3b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* ADD CUSTOMER */

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
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

input {
    padding: 12px;
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

/* TABLE */

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

/* RESPONSIVE */

@media(max-width: 800px) {

    .form {
        grid-template-columns: 1fr 1fr;
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

        <li>
            <a href="customers.php" class="active">
                👨‍🌾 Customers
            </a>
        </li>

        <li><a href="sales.php">💰 Sales & Billing</a></li>

        <li><a href="stock.php">📦 Stock</a></li>

        <li><a href="reports.php">📊 Reports</a></li>

    </ul>

</div>


<!-- MAIN -->

<div class="main">

    <div class="header">

        <div>

            <h1>👨‍🌾 Customer Management</h1>

            <p>Manage farmers and customers</p>

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


    <!-- ADD CUSTOMER -->

    <div class="add-box">

        <h2>➕ Add New Customer</h2>

        <form method="POST">

            <div class="form">

                <input
                    type="number"
                    name="CustomerID"
                    placeholder="Customer ID"
                    required
                >

                <input
                    type="text"
                    name="CustomerName"
                    placeholder="Farmer / Customer Name"
                    required
                >

                <input
                    type="text"
                    name="Phone"
                    placeholder="Phone Number"
                    required
                >

                <input
                    type="text"
                    name="Address"
                    placeholder="Address"
                    required
                >

                <input
                    type="text"
                    name="Village"
                    placeholder="Village"
                    required
                >

                <button
                    type="submit"
                    name="add_customer">

                    ➕ Add Customer

                </button>

            </div>

        </form>

    </div>


    <!-- CUSTOMER TABLE -->

    <div class="table-box">

        <h2>👨‍🌾 Registered Customers</h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Village</th>
                <th>Action</th>

            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $row['CustomerID']; ?>
                </td>

                <td>
                    👨‍🌾
                    <?php echo htmlspecialchars($row['CustomerName']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['Phone']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['Address']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['Village']); ?>
                </td>

                <td>

                    <a
                        class="delete"
                        href="customers.php?delete=<?php echo $row['CustomerID']; ?>"
                        onclick="return confirm('Delete this customer?');">

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