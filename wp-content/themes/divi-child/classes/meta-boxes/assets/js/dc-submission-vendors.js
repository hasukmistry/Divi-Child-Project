jQuery(document).ready(function($) {
    $categories = $('.dc-select-dropdown.vendor-category');
    
    $vendors = $('.dc-select-dropdown.vendors');

    $vendors_table = $('.vendors-datatable');

    let vendors_tbl;
    
    // lets readback serialize data from input field.
    let serialize_val = $('#related_vendors').val();
    let relations = new Map();
    if ( serialize_val !== '' ) {
        relations = JSON.parse(serialize_val).reduce((relations, [key, val])=> relations.set(key, val) , new Map());
    }

    // $() will work as an alias for jQuery() inside of this function
    vendors_tbl = $vendors_table.DataTable({
        "order": [],
        "bLengthChange": false, // length dropdown
        "bFilter": false, // search box
        "bInfo": false, // records information
        "columnDefs": [ {
            "targets" : 'no-searching-and-sorting',
            "searchable": false,
            "orderable": false,
        }, {
            "targets" : [0],
            "className" : "dc-check-column check-column reset-margin-left"
        }, {
            "targets" : [1],
            "className" : "title column-title has-row-actions column-primary page-title dc-column"
        }, {
            "targets" : [2],
            "className" : "dc-column"
        } ]
    } );
    
    // on change event.
    $categories.on("change", function (e) { 
        $vendors.val('').trigger('change');

        $('.vendors-from-categories').removeClass('hidden');

        $('.dc-button.save_vendor').prop('disabled',true);
        
        let cat = $(this).val();

        // fetch data using ajax.
        $vendors.select2({
            ajax:{
                url: script_params.ajax_url, // AJAX URL is predefined in WordPress admin
                dataType: 'json',
                delay: 250, // delay in ms while typing when to perform a AJAX search
                data: function (params) {
                    return {
                        q: params.term, // search query
                        category: cat, // selected category
                        action: 'get_vendors', // AJAX action for admin-ajax.php
                        token: script_params.nonce
                    };
                },
                processResults: function( data ) {
                    var options = [];
                    if ( data ) {
    
                        // data is the array of arrays, and each of them contains ID and the Label of the option
                        $.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                            options.push( { id: text['vendor_id'], text: text['vendor_name']  } );
                        });
    
                    }
                    return {
                        results: options
                    };
                },
                cache: true,
            },                            
        });
    });

    // on change event.
    $vendors.on("change", function (e) { 
        //dc-button save_vendor
        $('.dc-button.save_vendor').prop('disabled',false);
    });

    // on click of add vendor enable the block for saving.
    $(document).on('click', '.dc-button.add_vendors',function() {
        $('.dc-block.new-vendor-block').removeClass('hidden');

        // initialize it with select2
        $categories.select2();
    });

    // after save disable the block again.
    $(document).on('click', '.dc-button.save_vendor',function() {
        let vendor_latest_count = $("#vendor_latest_count").val();

        // increase the counter.
        vendor_latest_count = Number(vendor_latest_count) + 1;

        let checkbox = '<label class="screen-reader-text" for="cb-select-1">Select vendor</label><input type="checkbox" class="dc-row-checkbox" value="' + vendor_latest_count + '">';
        let category_text = '<strong>' + $('.dc-select-dropdown.vendor-category option:selected').text() + '</strong><div class="row-actions"><span class="trash"><a href="javascript:void(0);" class="submitdelete trash-relation-row" aria-label="Move this to the Trash" data-row="' + vendor_latest_count + '">Trash</a></span></div>';
        let vendor_text = '<span>' + $('.dc-select-dropdown.vendors option:selected').text() + '</span>';

        // lets add a row in table.
        vendors_tbl.row.add( [
            checkbox,
            category_text,
            vendor_text
        ] ).draw().nodes().to$().addClass( 'hentry' );

        let category_val = $categories.val();
        let vendor_val = $vendors.val();

        // lets add this to hashmap
        relations.set(vendor_latest_count, { category: category_val, vendor : vendor_val });

        let serialize_val = JSON.stringify([...relations.entries()]);
        $('#related_vendors').val(serialize_val);

        $("#vendor_latest_count").val( vendor_latest_count );

        // change value to default
        $categories.val('').trigger('change');

        $vendors.val('').trigger('change');

        $('.dc-block.new-vendor-block').addClass('hidden');

        $('.vendors-from-categories').addClass('hidden');

        $('.dc-button.save_vendor').prop('disabled',true);
    });

    $(document).on('click','.trash-relation-row', function() {
        let current = Number($(this).data('row'));

        if ( relations.has(current) ) {
            relations.delete(current);
        }

        // lets remove this record.
        vendors_tbl.row( $(this).parents('tr') )
        .remove()
        .draw();

        // lets serialize and save it again.
        let serialize_val = JSON.stringify([...relations.entries()]);
        $('#related_vendors').val(serialize_val);
    });

    $(document).on('click','.dc-action', function(event) {
        event.preventDefault();

        let action = $('.bulk-action-selector-bottom').val();
        
        if ( 'trash' === action ) {
            // retrieves all the selected checkboxes
            var searchIDs = $("input:checkbox.dc-row-checkbox:checked").map(function(){
                let current = Number($(this).val());
                if ( relations.has(current) ) {
                    relations.delete(current);
                }

                // lets remove this record.
                vendors_tbl.row( $(this).parents('tr') )
                .remove()
                .draw();

                // lets serialize and save it again.
                let serialize_val = JSON.stringify([...relations.entries()]);
                $('#related_vendors').val(serialize_val);
                return current;
            }).get(); 
        }
    });
});