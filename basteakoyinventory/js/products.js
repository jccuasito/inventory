document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("productModal");
    const closeBtn = document.querySelector(".close");
    const addProductBtn = document.getElementById("addProductBtn");
    const productForm = document.getElementById("productForm");
    const modalTitle = document.getElementById("modalTitle");
    const productIdInput = document.getElementById("productId");
    const nameInput = document.getElementById("productName");
    const categoryInput = document.getElementById("category");
    const priceInput = document.getElementById("price");
    const stockInput = document.getElementById("stock");
    const sizeInput = document.getElementById("size");
    const cupsInput = document.getElementById("cups");
    const statusInput = document.getElementById("status");

    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    addProductBtn.onclick = function() {
        modalTitle.textContent = "Add Product";
        productForm.reset();
        productIdInput.value = "";
        modal.style.display = "block";
    };

    document.querySelectorAll(".edit-btn").forEach(function(btn) {
        btn.onclick = function() {
            modalTitle.textContent = "Edit Product";
            productIdInput.value = btn.getAttribute("data-id");
            nameInput.value = btn.getAttribute("data-name");
            categoryInput.value = btn.getAttribute("data-category");
            priceInput.value = btn.getAttribute("data-price");
            stockInput.value = btn.getAttribute("data-stock");
            sizeInput.value = btn.getAttribute("data-size");
            cupsInput.value = btn.getAttribute("data-cups");
            statusInput.value = btn.getAttribute("data-status");
            modal.style.display = "block";
        };
    });

    document.querySelectorAll(".delete-btn").forEach(function(btn) {
        btn.onclick = function() {
            const productId = btn.getAttribute("data-id");
            if (confirm("Are you sure you want to delete this product?")) {
                fetch(`delete_product.php?id=${productId}`, {
                    method: 'GET'
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes("successfully")) {
                        location.reload();
                    }
                });
            }
        };
    });
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
