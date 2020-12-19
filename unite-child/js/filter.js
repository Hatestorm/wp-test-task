jQuery(function($){
	$('.agency-link').click(function(e){
		e.preventDefault()
		var id = $(this).attr('data-id')
		$.ajax({
			url: ajax_obj.ajaxurl,
			type: 'POST',
			data: {
				action: 'filter',
				id
			},
			beforeSend: function( xhr ) {
				$('.filtered-content').text('Загрузка, 5 сек...');	
			},
			success: function( data ) {
 				$('.filtered-content').html(data);	
			}
		});
	})
});