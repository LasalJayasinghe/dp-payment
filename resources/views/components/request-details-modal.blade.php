<!-- Modal for Viewing Request Details -->
<div class="modal fade" id="viewRequestModal" tabindex="-1" role="dialog" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRequestModalLabel">Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Request ID:</strong> <span id="requestId"></span></p>

                        <p><strong>Category:</strong> 
                            <span id="category"></span>
                            <select id="category_select" class="form-control mt-2" style="display: inline-block; width: auto; margin-left: 10px;">
                                <!-- Options will be populated here via JavaScript -->
                            </select>
                        </p>
                        
                        <p><strong>Subcategory:</strong> <span id="subcategory"></span></p>
                        <p><strong>Supplier Name:</strong> <span id="supplier_name"></span></p>
                        <p><strong>Amount:</strong> <span id="amount"></span></p>
                        <p><strong>Status:</strong> <span id="status"></span></p>
                        <p><strong>Requested Date:</strong> <span id="requested_date"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Requested By:</strong> <span id="requested_by"></span></p>
                        <p><strong>Due Date:</strong> <span id="due_date"></span></p>
                        <p><strong>Payment Type:</strong> <span id="payment_type"></span></p>
                        <p><strong>Account Name:</strong> <span id="account_name"></span></p>
                        <p><strong>Account Number:</strong> <span id="account_number"></span></p>
                        <p><strong>Bank Name:</strong> <span id="bank_name"></span></p>
                        <p><strong>Note:</strong> <span id="note"></span></p>
                        {{-- <p><strong>Document Link:</strong> <a id="document_link" href="" target="_blank">View Document</a></p> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateRequestBtn">Update Request</button>
                <button type="button" class="btn btn-primary" onclick="printRequest()">Print Request</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    function printRequest() {
        // Initialize jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Get the content from the modal
        const requestId = document.getElementById('requestId').innerText;
        const category = document.getElementById('category').innerText;
        const subcategory = document.getElementById('subcategory').innerText;
        const supplierName = document.getElementById('supplier_name').innerText;
        const amount = document.getElementById('amount').innerText;
        const status = document.getElementById('status').innerText;
        const requestedDate = document.getElementById('requested_date').innerText;
        const requestedBy = document.getElementById('requested_by').innerText;
        const dueDate = document.getElementById('due_date').innerText;
        const paymentType = document.getElementById('payment_type').innerText;
        const accountName = document.getElementById('account_name').innerText;
        const accountNumber = document.getElementById('account_number').innerText;
        const bankName = document.getElementById('bank_name').innerText;
        const note = document.getElementById('note').innerText;
        const documentLink = document.getElementById('document_link').href;

        // Set document title and text
        doc.setFontSize(12);
        doc.text("Request Details", 20, 20);
        
        // Add content to PDF (adjust positions accordingly)
        doc.text(`Request ID: ${requestId}`, 20, 30);
        doc.text(`Category: ${category}`, 20, 40);
        doc.text(`Subcategory: ${subcategory}`, 20, 50);
        doc.text(`Supplier Name: ${supplierName}`, 20, 60);
        doc.text(`Amount: ${amount}`, 20, 70);
        doc.text(`Status: ${status}`, 20, 80);
        doc.text(`Requested Date: ${requestedDate}`, 20, 90);
        doc.text(`Requested By: ${requestedBy}`, 20, 100);
        doc.text(`Due Date: ${dueDate}`, 20, 110);
        doc.text(`Payment Type: ${paymentType}`, 20, 120);
        doc.text(`Account Name: ${accountName}`, 20, 130);
        doc.text(`Account Number: ${accountNumber}`, 20, 140);
        doc.text(`Bank Name: ${bankName}`, 20, 150);
        doc.text(`Note: ${note}`, 20, 160);
        doc.text(`Document Link: ${documentLink}`, 20, 170);

        // Save the PDF
        doc.save("request-details.pdf");
    }


    document.addEventListener('DOMContentLoaded', function () {
    fetchCategories();
});

function fetchCategories() {
    fetch('/categories') // Ensure this matches the route name
        .then(response => response.json())
        .then(categories => {
            const categorySelect = document.getElementById('category_select');
            categorySelect.innerHTML = ''; // Clear existing options

            // Populate the dropdown with categories
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id; // Use `id` for the value
                option.textContent = category.name; // Display the category name
                categorySelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
        });
}

</script>
