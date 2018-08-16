// JavaScript Document
jQuery(document).ready(function($) {

    'use strict';

    var $form = $('#GeneSearch');
    var $GeneDropdown = $('#GeneDropdown');
	var $GeneInput = $('#GeneInput');
	var dJax = null;
    // First do some normalization and load Tissue Type
	$GeneDropdown.prop('disabled', true).children('option:gt(0)').remove();
	$GeneDropdown.children('option:first-child').text('Loading Gene...');
	
	$GeneInput.val('');
	dJax = $.ajax({
		url : base_url('lib/get_gene.php', false, false, false, {}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		_.each(data, function(name, id) {
			 $GeneDropdown.append('<option value="' + id + '">' + name + '</option>');
		});
		$GeneDropdown.prop('disabled', false).children('option:first-child').text('Select Gene');
	});
    var gene_name = null;

    // Setup form-level event listeners
    $form.on('submit', function(event) {
        event.stopPropagation();
        event.preventDefault();

        if (gene_name !== null) {
            var uri = 'search.php';
            var params = {};
			params['gene'] = gene_name;
            window.location = base_url(uri, false, false, false, params);
        }
    });
	
	// Populate Tumor Dropdown based on Tissue Dropdown choice
    $GeneInput.on('change', function(event) {

        if (dJax !== null) {
            dJax.abort();
        }

        var val = $(this).val();

        if (val !== '') {
            gene_name = null;

            event.stopPropagation();
            event.preventDefault();

            // Remove any results
           	$GeneDropdown.prop('disabled', true).children('option:gt(0)').remove();
			$GeneDropdown.children('option:first-child').text('Select Gene');
			gene_name = $GeneInput.val();
            //return false
        }
    });

    // Populate Gene Dropdown based on Tissue & Tumor selection
    $GeneDropdown.on('change', function(event) {

        if (dJax !== null){
            dJax.abort();
        }

        var val = $(this).val();

        if (val === 'Select Gene') {
            gene_name = null;

            event.stopPropagation();
            event.preventDefault();
			$GeneInput.prop('disabled', false);
            $GeneInput.val('');
            // Remove any results
            return false
        }
		$GeneInput.prop('disabled', true);
        $GeneInput.val('');
        gene_name = $(this).children('option:selected').val();
    });


	var $form1 = $('#DrugSearch');
    var $DrugDropdown = $('#DrugDropdown');
	var $DrugInput = $('#DrugInput');
	var gJax = null;
    // First do some normalization and load Tissue Type
	$DrugDropdown.prop('disabled', true).children('option:gt(0)').remove();
	$DrugDropdown.children('option:first-child').text('Loading Drug...');
	
	$DrugInput.val('');
	gJax = $.ajax({
		url : base_url('lib/get_drug.php', false, false, false, {}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		_.each(data, function(name, id) {
			 $DrugDropdown.append('<option value="' + id + '">' + name + '</option>');
		});
		$DrugDropdown.prop('disabled', false).children('option:first-child').text('Select Drug');
	});
    var drug_name = null;

    // Setup form-level event listeners
    $form1.on('submit', function(event) {
        event.stopPropagation();
        event.preventDefault();

        if (drug_name !== null) {
            var uri = 'search.php';
            var params = {};
			params['drug'] = drug_name;
            window.location = base_url(uri, false, false, false, params);
        }
    });
	
	// Populate Tumor Dropdown based on Tissue Dropdown choice
    $DrugInput.on('change', function(event) {

        if (gJax !== null) {
            gJax.abort();
        }

        var val = $(this).val();

        if (val !== '') {
            drug_name = null;

            event.stopPropagation();
            event.preventDefault();

            // Remove any results
           	$DrugDropdown.prop('disabled', true).children('option:gt(0)').remove();
			$DrugDropdown.children('option:first-child').text('Select Drug');
			drug_name = $DrugInput.val();
            //return false
        }
    });

    // Populate Gene Dropdown based on Tissue & Tumor selection
    $DrugDropdown.on('change', function(event) {

        if (gJax !== null){
            gJax.abort();
        }

        var val = $(this).val();

        if (val === 'Select Drug') {
            drug_name = null;

            event.stopPropagation();
            event.preventDefault();
			$DrugInput.prop('disabled', false);
            $DrugInput.val('');
            // Remove any results
            return false
        }
		$DrugInput.prop('disabled', true);
        $DrugInput.val('');
        drug_name = $(this).children('option:selected').val();
    });
});