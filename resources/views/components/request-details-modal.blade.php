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
                       @if(Auth::user()->role == "minAccount" || Auth::user()->role == "highAccount")
                        <p><strong>Category:</strong>
                                <span id="category"></span>
                                <select id="category_select" class="form-control mt-2" style="display: inline-block; width: auto; margin-left: 10px;">
                                    <!-- Options will be populated here via JavaScript -->
                                </select>
                            </p>
                       @endif

                        <p><strong>Request ID:</strong> <span id="requestId"></span></p>
                        <p><strong>Subcategory:</strong> <span id="subcategory"></span></p>
                        <p><strong>Supplier Name:</strong> <span id="supplier_name"></span></p>
                        <p><strong>Amount:</strong> <span id="amount"></span></p>
                        <p><strong>Total Paid:</strong> <span id="totalPaid"></span></p>
                        <p><strong>Due Amount:</strong> <span id="dueAmount"></span></p>
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
                @if(Auth::user()->role == "minAccount" || Auth::user()->role == "highAccount")
                <button type="button" class="btn btn-primary" id="updateRequestBtn">Update Request</button>
                @endif
                 
                {{-- <button type="button" class="btn btn-primary" onclick="printRequest()">Print Request</button> --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        fetchCategories();
    });

    // Function to fetch categories from backend
    function fetchCategories() {
        fetch('/categories') // Make sure this matches your route
            .then(response => response.json())
            .then(categories => {
                const categorySelect = document.getElementById('category_select');
                categorySelect.innerHTML = ''; // Clear existing options

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
