// qrcodemanipulate.js
function validateCheckout(event) {
    var total = parseFloat(document.getElementById('totalValue').innerText);
    var customerPaid = parseFloat(document.getElementById('customer_paid').value);

    if (total === 0) {
        event.preventDefault();
        alert('Please select at least one product.');
        return;
    }

    if (customerPaid < total) {
        event.preventDefault();
        alert('Please enter an exact total amount.');
        return;
    }
}

function toggleQRCode() {
    var paymentMethod = document.getElementById('payment_method').value;
    var qrCodeSection = document.getElementById('qrCodeSection');

    if (paymentMethod === 'gcash') {
        qrCodeSection.style.display = 'block';
    } else {
        qrCodeSection.style.display = 'none';
    }
}

