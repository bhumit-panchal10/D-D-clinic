@extends('layouts.app')

@section('title', 'Products List')

@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">Add Product</h5>
                        </div>


                        <div class="card-body">
                            <form action="{{ route('product.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label>Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="product_name" maxlength="100" placeholder="Enter Product Name" required>
                                </div>

                                <div class="mb-3">
                                    <label>Quantity (Instock) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="quantity" id="quantity" minlength="1" maxlength="10" placeholder="Enter Quantity" required>
                                </div>

                                <div class="mb-3">
                                    <label>Product Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="product_location" maxlength="150" placeholder="Enter Location" required>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="reset" class="btn btn-primary">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Product List</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $key + 1 }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->product_location }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit-product" 
                                                    data-id="{{ $product->id }}" 
                                                    data-name="{{ $product->product_name }}" 
                                                    data-quantity="{{ $product->quantity }}" 
                                                    data-location="{{ $product->product_location }}" 
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal">
                                                    Edit
                                                </button>
                                                <button class="btn btn-sm btn-primary delete-product" data-id="{{ $product->id }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                                                        Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="product_id" id="editProductId">

                    <div class="mb-3">
                        <label>Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="product_name" id="editProductName" required>
                    </div>

                    <div class="mb-3">
                        <label>Quantity (Instock) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="quantity" id="editQuantity" minlength="1" maxlength="10" required>
                    </div>

                    <div class="mb-3">
                        <label>Product Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="product_location" id="editProductLocation" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal Start -->
<div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width : 100px; height : 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this product?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="product_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Get current page from URL
    let currentPage = new URLSearchParams(window.location.search).get('page') || 1;

    $(".edit-product").on("click", function () {
        let id = $(this).data("id");
        $("#editProductId").val(id);
        $("#editProductName").val($(this).data("name"));
        $("#editQuantity").val($(this).data("quantity"));
        $("#editProductLocation").val($(this).data("location"));

        let actionUrl = "{{ route('product.update', ':id') }}".replace(':id', id)+ "?page=" + currentPage;
        $("#editProductForm").attr("action", actionUrl);
    });
    
    $(".delete-product").on("click", function () {
        let id = $(this).data("id");
        $("#deleteid").val(id);
    });

    // Confirm Delete Button Click
    $("#confirmDelete").on("click", function () {
        let id = $("#deleteid").val();
        let actionUrl = "{{ route('product.destroy', ':id') }}".replace(':id', id)+ "?page=" + currentPage;
        $("#deleteForm").attr("action", actionUrl);
        $("#deleteForm").submit();
    });

    // Restrict input to numbers only and enforce min/max length
    $("#quantity").on("input", function() {
        let val = $(this).val();
        
        // Remove non-numeric characters
        val = val.replace(/[^0-9]/g, '');

        // Ensure value does not exceed 10 characters
        if (val.length > 10) {
            val = val.slice(0, 10); // Restrict to max 10 characters
        }

        $(this).val(val);
    });
});
</script>
@endsection
