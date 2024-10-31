	
jQuery(document).ready(function($){	

	$("input[name='Quantity-pricing']").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			//
            alert('Please only input number..!!!');
			e.preventDefault();
        }
    });	
	$('div#message-mobile div.link-href a.to-cart').on('click',function(){
		$('div#message-mobile').removeClass('active');
		$('div#message-mobile').addClass('hidden');
	});	
	
	$('div#message-mobile div.link-href p.shooping').on('click',function(){
		$('div#message-mobile').removeClass('active');
		$('div#message-mobile').addClass('hidden');
	});
	
	$('p.except-quantity').on('click',function(){
	  var id=$(this).attr('quantity');
	  var id_input=$(this).next().attr('id');	 
	  var value_input=parseInt($(this).next().val());	  
	  if(value_input>0)
	  {
		ex=value_input-1;
		$(this).next().val(ex);
	  }  
	  
	});
	$('p.plus-quantity').on('click',function(){
	  var id=$(this).attr('quantity');
	  var id_input=$(this).prev().attr('id');	
	  var value_input=parseInt($(this).prev().val());	
	  $(this).prev().val('1');	
	if(value_input>=1)
    {
      var pl=value_input + 1;
      $(this).prev().val(pl);	
    }
	});
	
	//add to cart pricing table
	$('button[name="addtocart-pricing"]').on('click',function(){		
		id_product=$(this).val();
		 var data_name=$(this).attr('data-name');
		quantity=$(this).parent().prev().children('input:text').val();		
		//console.log(quantity);
		//console.log(id_product);
		$.ajax({			
			url:ajaxurl,
			data:{action:'add_to_cart_pricing_table',id_product:id_product,quantity:quantity},
			datatype : 'html',
			type: "post",
			success:function(datas){				
				if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					// some code..
					var data_s=datas;										
					$('div#message-mobile').addClass('active');
					$('div#message-mobile').removeClass('hidden');
					$('div.content-popup .msg-mobile').html(data_s);
					var link_cart=$('input.viewcart-pricing-hidden').val();
					console.log(link_cart);
					$('div.link-href a.to-cart').attr('href',link_cart);
					
				}
				else
				{
					$('div.message-addtocart.'+data_name+'').show();	
					$('div.message-addtocart.'+data_name+'').html(datas);
					$('div.message-addtocart.'+data_name+'').find('a').on('click',function(){				  
						$('div.message-addtocart.'+data_name+'').hide();
					});
					$('html, body').animate({scrollTop: $('div#message-addtocart').offset().top-200},1000);
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