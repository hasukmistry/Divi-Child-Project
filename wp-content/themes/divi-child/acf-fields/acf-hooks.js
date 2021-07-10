acf.addAction('ready_field/key=field_5ee4555911297', function(field) {
    // all lead assigned_to dropdowns from add ticket page
    let dropdown1 = acf.getField('field_5ee45819c587a');
    let dropdown2 = acf.getField('field_5ee458d0c587b');
    let dropdown3 = acf.getField('field_5ee458fbc587c');
    let dropdown4 = acf.getField('field_5ee45929c587d');

    // all other assigned_to dropdowns from add ticket page
    let dropdown1_1 = acf.getField('field_5ee49921f1fd7');
    let dropdown2_1 = acf.getField('field_5ee4a3b5e0ffd');
    let dropdown3_1 = acf.getField('field_5ee4a3ba7bac8');
    let dropdown4_1 = acf.getField('field_5ee4a3e8d273d');
    
    function assigned_to_dropdowns(value) {
        // hides all the dropdowns
        dropdown1.$el.hide();
        dropdown1_1.$el.hide();
        
        dropdown2.$el.hide();
        dropdown2_1.$el.hide();

        dropdown3.$el.hide();
        dropdown3_1.$el.hide();

        dropdown4.$el.hide();
        dropdown4_1.$el.hide();

        if ('graphic_design' === value) {
            dropdown1.$el.show();
            dropdown1_1.$el.show();

            // empty other fields
            dropdown2.$input().empty();
            dropdown2_1.$input().empty();

            dropdown3.$input().empty();
            dropdown3_1.$input().empty();

            dropdown4.$input().empty();
            dropdown4_1.$input().empty();

        } else if ('website_update' === value) {
            dropdown2.$el.show();
            dropdown2_1.$el.show();

            // empty other fields
            dropdown1.$input().empty();
            dropdown1_1.$input().empty();

            dropdown3.$input().empty();
            dropdown3_1.$input().empty();

            dropdown4.$input().empty();
            dropdown4_1.$input().empty();

        } else if ('virtual_tours' === value) {
            dropdown3.$el.show();
            dropdown3_1.$el.show();

            // empty other fields
            dropdown1.$input().empty();
            dropdown1_1.$input().empty();

            dropdown2.$input().empty();
            dropdown2_1.$input().empty();

            dropdown4.$input().empty();
            dropdown4_1.$input().empty();

        } else if ('support' === value) {
            dropdown4.$el.show();
            dropdown4_1.$el.show();

            // empty other fields
            dropdown1.$input().empty();
            dropdown1_1.$input().empty();

            dropdown2.$input().empty();
            dropdown2_1.$input().empty();

            dropdown3.$input().empty();
            dropdown3_1.$input().empty();
            
        }
    }

    // on ready event
    assigned_to_dropdowns(field.val());

    // on change event of select dropdown
    field.on('change', function( e ){
        assigned_to_dropdowns(field.val());
    });
}); 

// issue status dropdown
acf.addAction('ready_field/key=field_5f65e0c1c6946', function(field) {
    let revision_notes = acf.getField('field_5f65e56653283');

    function toggleRevisionNotes(value) {
        if ('requesting_submissions' === value) {
            revision_notes.$el.hide();
            // jQuery('#acf-group_5f65e55db7f48').hide();
        } else {
            revision_notes.$el.show();
            // jQuery('#acf-group_5f65e55db7f48').show();
        }
    }

    // on ready event
    toggleRevisionNotes(field.val());

    field.on('change', function( e ){
        toggleRevisionNotes(field.val());
    });
}); 