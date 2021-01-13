<div class="row clearfix">
    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    MULTI-SELECT
                </h2>
            </div>
            <div class="body">
                <div>
                    <select id="distributor_id" name="distributor_id" class="form-control show-tick"
                        data-live-search="true" data-size="3">
                        @foreach($distributor as $getDataDistributor)
                        <option value="{{ $getDataDistributor->id }}">
                            {{ strtoupper($getDataDistributor->distributor_name) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-block btn-lg btn-primary waves-effect"
                        onclick="saveButton()">SAVE</button>
                </div>
                <select id="optgroup" class="ms" multiple="multiple">
                    @foreach ($dataProductCategory['category'] as $dataCategory)
                    @php
                    $arrProduct =
                    empty($dataProductCategory["product"][$dataCategory->id])?NULL:$dataProductCategory["product"][$dataCategory->id]
                    @endphp
                    <optgroup label="{{ $dataCategory->category_name }}">
                        @if (!empty($arrProduct))
                        @foreach ($arrProduct as $dataProduct)
                        <option value="{{ $dataProduct->id }}">{{ $dataProduct->product_name }}
                        </option>
                        @endforeach
                        @endif
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>LIST</h2>
            </div>
            <div class="body">
                <table id="table-distribtor-products"
                    class="table table-bordered table-striped table-hover dataTable table-responsive" role="grid">
                    <thead>
                        <tr role="row">
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Distributor Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Distributor Name</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>