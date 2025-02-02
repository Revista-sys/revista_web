<!-- resources/views/payment.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
    <h1>Payment Page</h1>
    
    <form action="{{ route('create.payment') }}" method="POST">
        @csrf
        <label for="amount">Amount (KWD):</label>
        <input type="number" id="amount" name="amount" step="0.01" value="10.00" required>
        <br><br>

        <label for="invoiceId">Invoice ID:</label>
        <input type="text" id="invoiceId" name="invoiceId" value="INV123456" required>
        <br><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="Test Payment" required>
        <br><br>

        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
