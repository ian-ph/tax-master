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
        formHelper.toggleState($(this).val(), function(){
            taxSummary.summarizeTaxLocation();
            taxSummary.summarizeTaxRate();
            taxSummary.summarizeTaxBracket();
        });
    });

    $('select.toggle_county').change(function(){
        formHelper.toggleCounty($(this).val(), function(){
            taxSummary.summarizeTaxLocation();
        });
    });

    $('select[name=county_code]').change(function(){
        taxSummary.summarizeTaxLocation();
    });

    if($('select.toggle_state').length && $('select.toggle_state').val() != ""){
        //formHelper.toggleState($('select.toggle_state').val());
    }

    if($('select.toggle_county').length && $('select.toggle_county').val() != ""){
        //formHelper.toggleCounty($('select.toggle_county').val());
    }

    $('.trigger-summarize-tax-value').on('input', function() {
        taxSummary.summarizeTaxRate();
    });

    $('.trigger-summarize-tax-bracket').on('input', function() {
        taxSummary.summarizeTaxBracket();
    });

    $('input[name=implementation_date]').change('input', function() {
        $('.tax-date-text').text($(this).val());
    });

    $('input[name=note]').on('input', function() {
        $('.tax-note-value').text($(this).val());
    });

    $('select[name=tax_type]').change(function(){
        $('.tax-rate .tax-rate-type').text($('option:selected', this).text());
    });

    $('.tax-rate .tax-rate-type').text($('option:selected', 'select[name=tax_type]').text());

    $('.dashboard-country-chooser select').change(function(){
        $('.dashboard-country-chooser form').submit();
    });
});

window.taxSummary = {
    country : $('select[name=country_code]'),
    state : $('select[name=state_code]'),
    county : $('select[name=county_code]'),
    rateFixed : $('input[name=rate_fixed]'),
    ratePercentage : $('input[name=rate_percentage]'),
    bracketMinimum : $('input[name=bracket_minimum]'),
    bracketMaximum : $('input[name=bracket_maximum]'),

    valueText : $('.tax-location .value-text'),
    countryText : $('.tax-location .country-text'),
    stateText : $('.tax-location .state-text'),
    taxRateText : $('.tax-rate .tax-rate-value'),
    taxBracketText : $('.tax-bracket .tax-bracket-value'),

    summarizeTaxLocation: function() {
        //none mode
        if((this.county.val() == undefined || this.county.val() == '') && (this.state.val() == undefined || this.state.val() == '') && (this.country.val() == undefined || this.country.val() == '')){
            this.valueText.text('Not set');
            this.countryText.hide();
            this.stateText.hide();
            return;
        }

        //country only tax
        if((this.county.val() == undefined || this.county.val() == '') && (this.state.val() == undefined || this.state.val() == '')){
            this.valueText.text('Country of ' + $('option:selected', this.country).text())
            this.countryText.hide();
            this.stateText.hide();
            return;
        }

        //state only tax
        if((this.county.val() == undefined || this.county.val() == '')){
            this.valueText.text('State of ' +  $('option:selected', this.state).text())
            this.countryText.show().text('Country of ' + $('option:selected', this.country).text());
            this.stateText.hide();
            return;
        }

        this.valueText.text('State of ' +  $('option:selected', this.county).text())
        this.countryText.show().text('Country of ' + $('option:selected', this.country).text());
        this.stateText.show().text('State of ' + $('option:selected', this.state).text());
        return;
    },

    summarizeTaxRate: function(){
        var taxRateText = this.taxRateText;

        $.ajax({
            url : '/api/tax-rate/rate-preview',
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            dataType: 'json',
            data : {
                country_code : this.country.val(),
                rate_fixed : this.rateFixed.val(),
                rate_percentage : this.ratePercentage.val()
            },
            type: 'post',
            beforeSend: function(){
                $('.tax-amount-form .invalid-feedback').remove();
                $('.tax-amount-form .is-invalid').removeClass('is-invalid');
                taxRateText.text('Not Set');
            },
            success: function(result){
                //alert(this.taxRateText.length);
                taxRateText.text(result.result);
                //this.taxRateText.hide();
            },
            error : function(result){
                $.each(result.responseJSON.errors, function(k, v){
                    $('.tax-amount-form input[name="'+k+'"]').addClass('is-invalid').parent().append('<div class="invalid-feedback">'+v[0]+'</div>');
                });
            }
        });
    },

    summarizeTaxBracket: function(){
        var taxBracketText = this.taxBracketText;

        $.ajax({
            url : '/api/tax-rate/bracket-preview',
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            dataType: 'json',
            data : {
                country_code : this.country.val(),
                bracket_minimum : this.bracketMinimum.val(),
                bracket_maximum : this.bracketMaximum.val()
            },
            type: 'post',
            beforeSend: function(){
                $('.tax-bracket-form .invalid-feedback').remove();
                $('.tax-bracket-form .is-invalid').removeClass('is-invalid');
                taxBracketText.text('Not Set');
            },
            success: function(result){
                //alert(this.taxRateText.length);
                taxBracketText.text(result.result);
                //this.taxRateText.hide();
            },
            error : function(result){
                $.each(result.responseJSON.errors, function(k, v){
                    $('.tax-bracket-form input[name="'+k+'"]').addClass('is-invalid').parent().append('<div class="invalid-feedback">'+v[0]+'</div>');
                });
            }
        });
    }
}

window.formHelper = {

    tableData       : $('.table-data'),
    tableFooter     : $('.table-footer'),

    toggleState: function(country_code, callback){

        var select = $('select.dynamic_state');
        var countySelect = $('select.dynamic_county');

        select.find('option').remove();
        select.append('<option value="">Choose a state</option>');

        countySelect.find('option').remove();
        countySelect.append('<option value="">Choose a county</option>');

        if(country_code == undefined || country_code == ''){
            if( typeof callback == "function" ){
                callback();
            }
            return;
        }

        var select_value = select.data('value') != undefined && select.data('value') != "" ? select.data('value') : '';

        $.ajax({
            url : '/api/state/list_by_country_code/' + country_code,
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            type: 'get',
            success: function(result){

                $.each(result, function(){
                    selected = select_value == this.state_code ? 'selected' : '';
                    select.append('<option value="'+ this.state_code +'" '+selected+'>'+ this.state_name +'</option>');
                });

                if( typeof callback == "function" ){
                    callback();
                }
            }
        });
    },

    toggleCounty: function(state_code, callback){
        var select = $('select.dynamic_county');

        select.find('option').remove();
        select.append('<option value="">Choose a county</option>');

        if(state_code == undefined || state_code == ''){
            if( typeof callback == "function" ){
                callback();
            }
            return;
        }

        var select_value = select.data('value') != undefined && select.data('value') != "" ? select.data('value') : '';

        $.ajax({
            url : '/api/county/list_by_state_code/' + state_code,
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            type: 'get',
            success: function(result){
                $.each(result, function(){
                    selected = select_value == this.county_code ? 'selected' : '';
                    select.append('<option value="'+ this.county_code +'" '+selected+'>'+ this.county_name +'</option>');
                });

                if( typeof callback == "function" ){
                    callback();
                }
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

