<?php
session_start();

if (!isset($_SESSION['receipt'])) {
    echo "No receipt found. Please make a purchase first.";
    exit;
}

// Retrieve data from session
$receipt = $_SESSION['receipt'];
$paymentMethod = $receipt['payment_method'];
$amountPaid = $receipt['amount_paid'];
$grandTotal = $receipt['grand_total'];
$change = $receipt['change'];
$products = $receipt['products'];

// Clear session after displaying the receipt
unset($_SESSION['receipt']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=General+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'General Sans', sans-serif;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .receipt-header {
            text-align: center;
        }
        .receipt-details {
            margin-top: 20px;
        }
        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-details table th, .receipt-details table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .total-summary {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Receipt</h1>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($paymentMethod); ?></p>
            <p><strong>Amount Paid:</strong> <?php echo number_format($amountPaid, 2); ?></p>
            <p><strong>Grand Total:</strong> <?php echo number_format($grandTotal, 2); ?></p>
            <p><strong>Change:</strong> <?php echo number_format($change, 2); ?></p>
        </div>

        <div class="receipt-details">
            <h3>Product Details</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo number_format($product['total'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total-summary">
            <h3>Total: <?php echo number_format($grandTotal, 2); ?></h3>
            <h3>Change: <?php echo number_format($change, 2); ?></h3>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()">Print Receipt</button>
            <br><br>
            <button onclick="window.location.href='index.php'">Go Back to Home</button>
        </div>
    </div>
</body>
</html>