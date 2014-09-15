;( function( $ ) 
{
	$('#post').submit(function( e )
	{
		if( !$('#title').val().length || !$('#icon_url').val().length || ( $('#icon_url').val() == 'Enter your url' ) )
		{
			alert( 'Both the title and URL fields are required to save.' );

			$('.spinner').hide();
			$('input[type=submit]')
			.removeClass('button-disabled')
			.removeClass('button-primary-disabled');

			e.preventDefault();
			return false;
		}
	});

	$('.icon-image').click(function()
	{
		var $this = $(this),
		src = $(this).attr('src');

		$('#custom_icon_url').val( src );
		$('.icon-image').removeClass('active');
		$this.addClass('active');
	});

	$('table.posts tbody').sortable({
		cursor: 'move',
		stop: function( event, ui )
		{
			var order = {'action' : 'sf_icons_menu_sort'};
			$('table.posts tbody').fadeTo(200, .50);

			$('table.posts tr').each(function(e, i)
			{
				var index = $(this).index(),
				id = $(this).attr('id');

				if( id != undefined )
				{
					id = id.replace('post-', '');

					order[index] = { 'menu_order' : index, 'post_id' : id };

					$(this).find('input[type=text]').val(index);
				}
			});

			$.post(ajaxurl, order, function( data )
			{
				$('table.posts tbody').fadeTo(200, 1);
			})
		}
	});

	$('.menu-order-set').on('keydown blur', function( e )
	{
		if( ( e.type == 'keydown' && e.keyCode == 13 ) || e.type == 'blur' )
		{
			if( ( e.type == 'keydown' && e.keyCode == 13 ) )
				e.preventDefault();	

			var order = {'action' : 'sf_icons_menu_sort'},
			id = $(this).parents('tr').attr('id').replace('post-', '')
			index = $(this).val()
			spinner = $(this).next('.spinner');

			spinner.show();

			order[1] = { 'menu_order' : index, 'post_id' : id };

			$.post(ajaxurl, order, function( data )
			{
				$('table.posts tbody').fadeTo(200, 1);

				spinner.hide();
			})
		}
	});

	$('#sf_settings_size').change(function()
	{
		if( $(this).val() == 'custom' )
		{
			$('.custom-settings').slideDown(500);
		}
		else
		{
			$('.custom-settings').slideUp(500);
		}
	});

	$('input[name="sf_settings[direction]"]').change(function()
	{
		console.log('hello');
		$(this).val() == 'vertical' ? $('#sf_settings_rows').removeAttr('disabled') : $('#sf_settings_rows').attr('disabled', 'disabled');
	})

	var sf_extras = $('#sf_list_extras');
	$('#wpbody-content').append( sf_extras.remove().show() );	

	var sf_step_one = $('#sf_step_one');
	$('h2:first').after( sf_step_one.remove().show() );

	$('.set-icons').click(function()
	{
		if( !jQuery('input[name="choose-default"]:checked').length )
		{
			alert( "You must select an icon set to proceed" );
		}
		else
		{
			// if(confirm( "Are you sure you want to set this as the default icon set? All current and future icons created will use this set, icons containing a custom image will also be reset.\n\nIf the selected set does not contain an icon for one of the items it will not be changed." ) )
			// {
				document.location.href = ajaxurl+'?action=sf_set_default_icon&icon_set='+jQuery('input[name="choose-default"]:checked').val();
			// }
		}
	})

})( jQuery );