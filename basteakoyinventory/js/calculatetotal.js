function calculateTotal(element) {
    const row = element.closest('tr');
    const price = parseFloat(row.querySelector('.price').textContent);
    const qty = parseInt(row.querySelector('.quantity').value);
    const totalCell = row.querySelector('.product-total');
    const rowTotal = price * qty;
    totalCell.textContent = rowTotal.toFixed(2);
    calculateOverallTotal();
}

function calculateOverallTotal() {
    let overallTotal = 0;
    document.querySelectorAll('.product-total').forEach(totalElement => {
        overallTotal += parseFloat(totalElement.textContent);
    });
    document.getElementById('totalValue').textContent = overallTotal.toFixed(2);
}

function validateCheckout(event) {
    const quantities = document.querySelectorAll('.quantity');
    let hasQuantity = false;
    quantities.forEach(select => {
        if (parseInt(select.value) > 0) {
            hasQuantity = true;
        }
    });
    if (!hasQuantity) {
        alert('No quantity and product inserted');
        event.preventDefault(); // Prevent form submission
    }
}

