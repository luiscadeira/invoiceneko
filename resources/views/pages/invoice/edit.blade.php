@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>
        .selectize-control {
            margin-top: 12px;
        }
        .selectize-dropdown, .selectize-input, .selectize-input input {
            font-size: 1rem;
        }

        .selectize-control.single .selectize-input {
            border: none;
            box-shadow: none;
            border-bottom: 1px solid #9e9e9e;
            border-radius: 0;
        }
        .selectize-control.single .selectize-input input {
            height: auto;
        }
        .selectize-dropdown {
            border: none;
            border-radius: 0;
            box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 1px 5px 0 rgba(0,0,0,0.12), 0 3px 1px -2px rgba(0,0,0,0.2);
        }

        .selectize-dropdown [data-selectable], .selectize-dropdown .optgroup-header {
            min-height: 50px;
            line-height: 50px;
            padding: 0 8px;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Create Invoice</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="update-invoice" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="invoiceid" name="invoiceid" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $invoice->invoiceid }}" disabled>
                                <label for="invoiceid" class="label-validation">Invoice ID</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="date" name="date" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $invoice->date  }}">
                                <label for="date" class="label-validation">Date</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="netdays" name="netdays" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $invoice->netdays }}">
                                <label for="netdays" class="label-validation">Net Days</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="client_id" name="client_id" data-parsley-required="true" data-parsley-trigger="change" disabled>
                                    <option disabled="" selected="selected" value="">Pick a Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($invoice->client_id == $client->id) selected @endif>{{ $client->companyname }}</option>
                                    @endforeach
                                </select>
                                <label for="client_id" class="label-validation">Client</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <h3>Items</h3>
                        </div>
                        <div class="col s6">
                            <a id="invoice-item-add" class="btn-floating btn-large waves-effect waves-light red right"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                    <div id="invoice-items-container">
                        @foreach($invoice->items as $key => $item)
                            <div id="invoice_item_{{ $key }}" class="card-panel">
                                <div class="row">
                                    <div class="input-field col s8">
                                        <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $item->name }}">
                                        <label for="item_name" class="label-validation">Name</label>
                                    </div>
                                    <div class="input-field col s2">
                                        <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ $item->quantity }}">
                                        <label for="item_quantity" class="label-validation">Quantity</label>
                                    </div>
                                    <div class="input-field col s2">
                                        <input id="item_price" name="item_price[]" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ $item->price }}">
                                        <label for="item_price" class="label-validation">Price</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <textarea id="item_description" name="item_description[]" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ $item->description }}</textarea>
                                        <label for="item_description" class="label-validation">Description</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <button data-id="{{ $item->id }}"  data-count="{{ $key }}" class="invoice-item-delete-btn btn waves-effect waves-light col s2 offset-s10 red">Delete</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s2 offset-s10" type="submit" name="action">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Invoice Item?</p>
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class=" modal-action waves-effect black-text waves-green btn-flat btn-deletemodal invoice-item-confirm-delete-btn">Delete</a>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            var invoiceitemcount = 0;

            var picker = $('#date').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year,
                today: 'Today',
                clear: 'Clear',
                close: 'Ok',
                closeOnSelect: true // Close upon selecting a date,
            }).pickadate('picker');
            picker.set('select', '{{ $invoice->date }}', { format: 'yyyy-mm-dd' });

            $('#client_id').selectize();
            $('.modal').modal();

            $('#invoice-item-add').on('click', function() {
                initInvoiceItem(++invoiceitemcount, 'invoice-items-container');
            });

            function initInvoiceItem(count, elementid) {
                var invoiceitem = '<div id="invoice_item_' + count + '" class="card-panel"> <div class="row"> <div class="input-field col s8"> <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_name" class="label-validation">Name</label> </div> <div class="input-field col s2"> <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_quantity" class="label-validation">Quantity</label> </div> <div class="input-field col s2"> <input id="item_price" name="item_price[]" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_price" class="label-validation">Price</label> </div> <div class="input-field col s12"> <textarea id="item_description" name="item_description[]" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description"></textarea> <label for="item_description" class="label-validation">Description</label> </div> </div> </div>';
                $('#' + elementid).append(invoiceitem);
            }

            $('#invoice-items-container').on('click', '.invoice-item-delete-btn', function (event) {
                event.preventDefault();
                $('#delete-confirmation').modal('open');

                var itemid = $(this).attr('data-id');
                var count = $(this).attr('data-count');

                $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-id', itemid);
                $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-count', count);
            });

            $('#delete-confirmation').on('click', '.invoice-item-confirm-delete-btn', function (event) {
                event.preventDefault();

                var itemid = $(this).attr('data-id');
                var count = $(this).attr('data-count');

                if (typeof itemid !== typeof undefined && itemid !== false) {
                    var deleteinvoiceitemreq = $.ajax({
                        type: "DELETE",
                        url: "/invoice/item/" + itemid + "/destroy",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                        .done(function (data) {
                            console.log(data);
                        })
                        .fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                            console.log(textStatus);
                        })
                        .always(function () {
                            console.log("finish");
                        });
                }
                else {
                    $('#invoice_item_' + count).remove();
                }

                $.when(deleteinvoiceitemreq).done(function () {
                    $('#invoice_item_' + count).remove();
                    $('#delete-confirmation').modal('close');
                    $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-id', '');
                    $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-count', '');
                });
            });
        });
    </script>
@stop