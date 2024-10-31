/*-------------------------------------------------------------*/
jQuery(document).ready(function($){			
	$('#form-create-table-pricing').addClass('active');
	$('#add_to_table').attr('disabled','disabled');
	$('input[name="save-table"]').attr('disabled','disabled');
	$('input[name="table-name-pricing"]').val($('p.table-name').html());
	$('input[name="shortcode-name"]').val($('p.shortcode-name').html());	
	$('input[name="save-table"].save-table').on('click',function(){		
	   $(window).scrollTop(0);
	   $('.checkbox').find('input#chk-id-pr').each(function(){
		if(this.checked==true)
			{$(this).prev().val('1');}
		 if(this.checked==false)
			{$(this).prev().val('0');}
	  });
	});
	$('a.delete-table-pricing').on('click',function(){		
	   $('html, body').animate({scrollTop: 0},slow);
	});
	
	$( ".column" ).sortable({
      connectWith: ".column",
      handle: ".portlet-header",
      cancel: ".portlet-toggle",
      placeholder: "portlet-placeholder ui-corner-all"
    });
 
    $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" ).find( ".portlet-header" ).addClass( "ui-widget-header ui-corner-all" ).prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");
 
    $( ".portlet-toggle" ).click(function() {
      var icon = $( this );
      icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
      icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
    });
	
	$('div.portlet-header.ui-sortable-handle.ui-widget-header').live('click',function(){
		  //alert('hj');  
		  var status_show=$(this).children('span').hasClass('ui-icon-minusthick');  
		  if(status_show==true)
		  {
			  $(this).children('span').addClass('ui-icon-plusthick').removeClass('ui-icon-minusthick');
					$(this).next('.portlet-content').addClass('hidden');
					$(this).next('.portlet-content').removeClass('active');
		  }
		  else
		  {
			  $(this).children('span').addClass('ui-icon-minusthick').removeClass('ui-icon-plusthick');
					$(this).next('.portlet-content').addClass('active');
					$(this).next('.portlet-content').removeClass('hidden');
		  }	  
	});
	
	$('button#add_to_table').on('click',function(){		
		$('.checkbox').find('input[name="chk-description"]:checked').each(function(){
			get_id_product_checked = $(this).val();				
			var title_product = $('#product_'+get_id_product_checked + ' label').html();			
			$html = '';
			$html += '<div class="portlet col-xs-12 ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">';
			$html += '<div class="portlet-header ui-sortable-handle ui-widget-header ui-corner-all"><span class="ui-icon ui-icon-minusthick portlet-toggle ct"></span>'+title_product+'</div>';
			$html += '<div class="portlet-content">';
			$html += '<label>Row Label</label>';
			$html += '<input type="text" name="row-label-pricing[]" class="form-control">';
			$html +='<input type="hidden" name="product-id[]" value="'+get_id_product_checked+'">';
			$html += '<label>Original Label</label>';
			$html += '<input type="hidden" name="Original-label-pricing[]" value="'+title_product+'">';
			$html += '<input type="text" name="Original-label-pricing[]" value="'+title_product+'" class="form-control Original" disabled>';
			$html += '<div class="checkbox">';
			$html += '<a href="#delete" class="delete-product-pricing" >Delete</a>';
			$html += '<label>Include Short Description</label>';	
			$html += '<input type="hidden" name="chk-description-detail[]" id="add-chk">';
			$html += '<input id="chk-id-pr" type="checkbox">';
			$html += '</div></div></div>';
			$('.resutlt_add').append($html);
			$('input[name="save-table"]').removeAttr('disabled');
			$(this).attr('checked', false); // Unchecks it
		});
		
	});
	
//select data_table_pricing	

	$('button.select-table-pricing').on('click',function(){
	
		var key=$('select.form-control').find('option:selected').val();
		$.ajax({
					method:"POST",				
					url:ajaxurl,
					data:{
							action:'show_table_pricing',
							datas : key
						},
					success:function(data){						
						$('.resutlt_add').html(data);
						//$('input[name="save-table"]').attr('disabled','disabled');
						$('input[name="table-name-pricing"]').val($('p.show-table-name-pricing-'+key+'').html());
						$('input[name="shortcode-name"]').val($('p.show-table-shortcode-pricing-'+key+'').html());
						var check_shortcode_name = $('input[name="shortcode-name"]').val();						
						$('.lb-show-shortcode').html('[pricing_table_product name="'+check_shortcode_name+'" title="" ]');
						if(check_shortcode_name === undefined)
						{
							$('#add_to_table').attr('disabled','disabled');
						}
						else{
							if(check_shortcode_name.length > 0){
								$('input[name="save-table"]').removeAttr('disabled');
								$('.title-table').addClass('active');
								$('button#add_to_table').removeAttr('disabled');
								$('#form-create-table-pricing').removeClass('active');	
								$('.product-right-pricing').removeClass('hidden');								
							}
						}												
					}, 
					error: function( jqXHR, textStatus, errorThrown ){
						console.log( 'The following error occured: ' + textStatus, errorThrown );   
					},
	 
					complete: function( jqXHR, textStatus ){
					}
					
				});
		
	});
				
	//create table pricing
	$(function() {
	
		$("#form-create-table-pricing").on("submit", function(event) {
			event.preventDefault();
			$.ajax({
				url: ajaxurl,
				datatype : 'html',
				type: "post",
				data:{action:'create_table_pricing', fromdata: $(this).serialize()},
				success: function(data) {
					$('.hidden-show-table-name').html(data);
					//alert('Create Success');
					$('input[name="table-name-pricing"]').val($('p.table-name').html());
					$('input[name="shortcode-name"]').val($('p.shortcode-name').html());
					var check_shortcode_name = $('p.shortcode-name').html();
					$('.lb-show-shortcode').html('[pricing_table_product name="'+check_shortcode_name+'"]');
					if(check_shortcode_name === undefined)
					{
						$('#add_to_table').attr('disabled','disabled');
					}
					else{
						if(check_shortcode_name.length > 0){
							$('.title-table').addClass('active');
							$('button#add_to_table').removeAttr('disabled');
							$('#form-create-table-pricing').removeClass('active');
						}
					}
				},
				error: function( jqXHR, textStatus, errorThrown ){
					console.log( 'The following error occured: ' + textStatus, errorThrown );   
				},
 
					complete: function( jqXHR, textStatus ){
				}
			});
		});
	});
	
	//save table pricing
	
	$(function() {
	
		$("#form-save-table-pricing").on("submit", function(event) {
			event.preventDefault();		
			ID_name =$('input[name="id-table-pricing"]').val();
			$.ajax({
				url: ajaxurl,
				datatype : 'html',
				type: "post",
				data:{action:'pricing_tables_data', fromdata: $(this).serialize(), id_name:ID_name},
				success: function(datas) {
					//console.log(data);
					$('.messagebox-flag').addClass('active');
					$('div#messagebox-flag').html(datas);					
					//alert('Saves Success');
					//$('input[name="save-table"]').attr('disabled','disabled');
				},
				error: function( jqXHR, textStatus, errorThrown ){
					console.log( 'The following error occured: ' + textStatus, errorThrown );   
				},
 
					complete: function( jqXHR, textStatus ){
				}
			});
		});
	});
	
	
	//delete product show detail
	
	$('a.delete-product-pricing').live('click',function(){
		//alert('hj');
		$(this).parent().parent().prev().parent().remove();	
	});
	
	/*$('a.delete-product-pricing').on('click',function(){
	  var id=$(this).attr('id');
	  console.log(id);
	  $('#show-table_product-pricing-'+id+'').remove();
	  //$(this).parent().parent().parent().parent().remove();
	});*/
	
	//delete table name pricing
	$('a.delete-table-pricing').on('click',function(){
		var ID_table=$('input[name="id-table-pricing"]').val();
		//alert(ID_table);
		$.ajax({
			url: ajaxurl,
			datatype : 'html',
			type: "post",
			data:{action:'delete_tables_data', ID:ID_table},
			success: function(datas) {
				//console.log(data);				
				$('.messagebox-flag').addClass('active');
				$('div#messagebox-flag').html(datas);
				$('.product-right-pricing').addClass('hidden');
				$('select.form-control .option-table-name-'+ID_table+'').remove();
				$('#add_to_table').attr('disabled','disabled');
				//alert('Saves Success');
				//$('input[name="save-table"]').attr('disabled','disabled');
			},
			error: function( jqXHR, textStatus, errorThrown ){
				console.log( 'The following error occured: ' + textStatus, errorThrown );   
			},

				complete: function( jqXHR, textStatus ){
			}
		});
	});	
});
