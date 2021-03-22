<div class="clearfix">
    <table id="table-product-input" class="table table-bordered table-striped table-hover table-responsive dataTable"
        role="grid">
        <thead>
            <tr role="row">
                <th style="width: 23%">Product Category</th>
                <th>Product Name</th>
                <th style="width: 7%"></th>
            </tr>
        </thead>
        <tbody>
            <tr role="row">
                <td style="width: 365px !important; vertical-align: top;">
                    <select name="category_name" class="form-control show-tick category_name varInput"
                        data-live-search="true" data-size="3">
                        @foreach($productCategory as $getDataProductCategory)
                        <option value="{{ $getDataProductCategory->id }}">
                            {{ $getDataProductCategory->category_name }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="width: 566px !important">
                    <input type="text" id="product_name" name="product_name" class="form-control varInput"
                        data-role="tagsinput" style="width: 100% !important; text-transform:uppercase"
                        placeholder="press enter">
                </td>
                <td class="text-center" style="width: 148px !important; vertical-align: top;">
                    <div class="btn-group btn-group-sm save-confirm-cancel">
                        <button type="button" class="btn btn-success waves-effect saveBtn" style="float: none;"
                            onclick="saveButtonNewProduct(this,'{{ route('product.store') }}')">
                            <i class="material-icons">save</i>
                        </button>
                        <button type="button" class="btn bg-red waves-effect cancelBtn" style="float: none;"
                            onclick="cancelButton(this,'insert')">
                            <i class="material-icons">refresh</i>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <table id="table-products" class="table table-bordered table-striped table-hover table-responsive dataTable"
        role="grid" style="width: 100%">
        <thead>
            <tr role="row">
                <th>Product Category</th>
                <th>Product Name</th>
                <th>Active Ingredients</th>
                <th>How to Use</th>
                <th>Usability</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th style="width: 10%">Product Category</th>
                <th style="width: 25%">Product Name</th>
                <th>Active Ingredients</th>
                <th>How to Use</th>
                <th>Usability</th>
                <th style="width: 7%"></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>