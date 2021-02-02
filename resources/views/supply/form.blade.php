<form>
    {{ csrf_field() }}
    <div class="form-group">
        <div class="form-line">
            <input name="input_date" id="input_date" type="text" class="form-control date mask_date"
                placeholder="Ex: 14/10/1991" value="{{ date('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group">
        <select id="distributor_id" name="distributor_id"
            class="form-control show-tick editInput distributor_id varInput" data-live-search="true" data-size="3">
            <option value="">--SELECT DISTRIBUTOR--</option>
            @foreach($distributor as $getDataDistributor)
            <option value="{{ $getDataDistributor->id }}">
                {{ $getDataDistributor->distributor_name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="row clearfix">
        <div class="align-right m-r-10">
            <button type="button" class="btn btn-warning waves-effect" onclick="addRow()">
                <i class="material-icons">add</i>
            </button>
        </div>
        <div class="clearfix">
            <table class="table" id="product_table">
                <thead>
                    <tr>
                        <th class="text-center">PRODUCT</th>
                        <th class="text-center" style="width: 60px">QTY</th>
                        <th class="text-center">PRICE</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr id="1">
                        <td class="row-index text-center">
                            <input type="checkbox" class="filled-in" id="ig_checkbox_1" name="ig_checkbox_1"
                                style="float: right; width:5%">
                            <label for="ig_checkbox_1"></label>
                            <input type="text" class="form-control form_product_code" id="form_product_code_1"
                                style="float: right; width:85%">
                            <input type="hidden" class="form-control form_product_id" id="form_product_id_1">
                        </td>
                        <td class="row-index text-center">
                            <input type="text" class="form-control qty text-center" id="qty_1">
                        </td>
                        <td class="row-index text-right">
                            <input type="text" class="form-control price text-center" id="price_1" size="5"
                                onkeyup="priceRow(this)">
                            <input type="hidden" class="form-control price_ori text-center" id="price_1_ori" value="0">
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger remove" type="button">
                                <i class="material-icons">delete</i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-block btn-lg btn-primary waves-effect" onclick="save()">
                <i class="material-icons">save</i>
                SAVE
            </button>
        </div>
    </div>
</form>