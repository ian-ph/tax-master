/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(function(){
    $('form.data-form').submit(function(e){
        e.preventDefault();

        var patch = $(this).data('patch') ? true : false
        formHelper.submitForm(this, patch);
        return false;
    });

    $('.data-paginator').click(function(e){
        e.preventDefault();
        if($(this).attr('href') == "" || $(this).attr('href') == undefined || $(this).attr('href') == null){
            return;
        }
        formHelper.getIndex($(this).attr('href'));
    })

    $('form.data-form-delete').submit(function(e){
        e.preventDefault();
        formHelper.submitDeleteRequest(this);
    });

    $('select.toggle_state').change(function(){
        formHelper.toggleState($(this).val());
    });

    if($('select.toggle_state').length && $('select.toggle_state').val() != ""){
        formHelper.toggleState($('select.toggle_state').val());
    }
});

window.formHelper = {

    tableData       : $('.table-data'),
    tableFooter     : $('.table-footer'),

    toggleState: function(country_code){
        var select = $('select.dynamic_state');

        select.find('option').remove();
        select.append('<option value="">Choose a state</option>');

        var select_value = select.data('value') != undefined && select.data('value') != "" ? select.data('value') : '';

        $.ajax({
            url : '/api/state/list_by_country_code/' + country_code,
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            type: 'get',
            success: function(result){
                console.log(select_value);
                $.each(result, function(){
                    selected = select_value == this.state_code ? 'selected' : '';
                    select.append('<option value="'+ this.state_code +'" '+selected+'>'+ this.state_name +'</option>');
                });
            }
        });
    },

    getIndex : function(url) {
        $.ajax({
            url: url,
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            beforeSend: function(){
                $('.table-loading').removeClass('d-none');
                $('.table-empty').addClass('d-none');
                formHelper.tableData.addClass('d-none');
                formHelper.tableFooter.addClass('d-none');
            },
            success: function(result){
                $('.table-loading').addClass('d-none');
                if(result.data == undefined || result.data.length === 0) {
                    $('.table-empty').removeClass('d-none');
                } else {
                    formHelper.tableData.removeClass('d-none');
                    formHelper.dataToTable(result);
                }
            }
        })
    },

    dataToTable : function(result) {

        var table = $('<table class="table"></table>').append('<thead class="text-capitalize bg-light text-dark"><tr></tr></thead>');

        $.each(result.data, function(){
            $.each(this, function(key, value){
                if(key != 'id' && key != 'uuid'){
                    table.find('tr').append('<th>'+ key.replace(/_/g, ' ') +'</th>');
                }
            });
            return false;
        });
        table.find('tr').append('<th class="text-right">Actions</th>');

        table.append('<tbody></tbody>');
        $.each(result.data, function(){
            table.find('tbody').append('<tr></tr>');
            $.each(this, function(key, value){
                if(key != 'id' && key != 'uuid'){
                    table.find('tr:last').append('<td>'+ value +'</td>')
                }
            });
            table.find('tr:last').append('<td class="text-right"><a href="'+ formHelper.tableData.data('edit_route') + '/' + this.uuid + '"  class="btn btn-sm btn-primary">Edit</a> <a href="'+ formHelper.tableData.data('delete_route') + '/' + this.uuid + '" class="btn btn-sm btn-danger">Delete</a></td>')
        });

        formHelper.tableData.html(table);
        formHelper.tableFooter.removeClass('d-none').find('.data-message').text('Displaying ' + result.data.length + ' out of ' + result.meta.total + ' records.');
        formHelper.tableFooter.find('.data-previous-link').attr('href', result.links.prev);
        formHelper.tableFooter.find('.data-next-link').attr('href', result.links.next);
        formHelper.tableFooter.find('.data-first-link').attr('href', result.links.first);
        formHelper.tableFooter.find('.data-last-link').attr('href', result.links.last);
    },

    submitForm : function(form, patch) {
        var method = patch ? 'patch' : 'post';

        $.ajax({
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            method: method,
            url: $(form).attr('action'),
            data : $(form).serialize(),
            beforeSend: function(){
                $('.alert').addClass('d-none');
                $('input.form-control, select.form-control', form).prop('disabled', true).removeClass('is-invalid');
                $('button[type=submit]', form).prop('disabled', true);
                $('.invalid-feedback').remove();
            },
            success: function(result){
                $('.alert-success').removeClass('d-none');
            },
            error: function(result){
                $('.alert-danger').removeClass('d-none');
                $.each(result.responseJSON.errors, function(k, v){
                    $('input[name="'+k+'"], select[name="'+k+'"]').addClass('is-invalid').parent().append('<div class="invalid-feedback">'+v[0]+'</div>');
                });
            },
            complete: function(){
                $('input.form-control, select.form-control', form).prop('disabled', false);
                $('button[type=submit]', form).prop('disabled', false);
            }
        });
    },

    submitDeleteRequest : function(form) {

        $.ajax({
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            method: 'delete',
            url: $(form).attr('action'),
            beforeSend: function(){
                $('button[type=submit]', form).prop('disabled', true);
            },
            success: function(result){
                window.location.href = $('.back-button').attr('href');
            }
        });
    }


}

