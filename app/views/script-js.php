<?php
use NS\String;
?>
jQuery(document).ready(function() {
	var datatable = null;

	if(jQuery('.checkall').length > 0) {
		jQuery('.checkall').click(function(){
			var parentTable = jQuery(this).parents('table');										   
			var ch = parentTable.find('tbody input[type=checkbox]');										 

			if(jQuery(this).is(':checked')) {
				ch.each(function(){
					if(!jQuery(this).is(':checked')) {
						jQuery(this).trigger('click');
					}
				});
			} else {
				ch.each(function(){
					if(jQuery(this).is(':checked')) {
						jQuery(this).trigger('click');
					}
				});	
			}
		});
	}

	jQuery('tbody .NS-Checkbox').on('click', function() {
		var isChecked = jQuery(this).is(':checked');

		if(isChecked) {
			jQuery(this).parents('tr').addClass('selected');
		} else {
			jQuery(this).parents('tr').removeClass('selected');
		}
	});
	
	datatable = jQuery('#<?php echo String::toLowerCase($controller); ?>-table').dataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: jQuery('#<?php echo String::toLowerCase($controller); ?>-table').attr('data-service-url'),
			type: "POST"
		},
		columns: [
			{ data: '<?php echo $pk ?>' },
<?php foreach($columns as $column => $value): ?>
			{ data: '<?php echo $column ?>' },
<?php endforeach; ?>
			{ data: '<?php echo $pk ?>' }
		],
		columnDefs: [{
			targets: 0,
			orderable: false,
			render: function(data) {
				return '<input id="<?php echo $pk ?>-' + data + '" class="NS-Checkbox <?php echo $pk ?>" type="checkbox" value="' + data + '" name="<?php echo $pk ?>[]">';
			},
			className: 'align-center'
		},
		{
			targets: -1,
			orderable: false,
			render: function(data) {
				return '\
					<div class="btn-group" id="actions">\n\
						<a class="btn" href="'+ jQuery('#<?php echo String::toLowerCase($controller); ?>-table').attr('data-url') + '/detail/' + data + '">\n\
							<span class="icon-tasks"></span> Detail\n\
						</a>\n\
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">\n\
							<span class="caret">\n\
						</a>\n\
						<ul class="dropdown-menu">\n\
							<li>\n\
								<a href="' + jQuery('#<?php echo String::toLowerCase($controller); ?>-table').attr('data-url') + '/edit/' + data + '"><span class="icon-edit"></span> Edit</a>\n\
							</li>\n\
						</ul>\n\
					</div>';
			},
			className: 'align-center action-button'
		}],
		drawCallback: function() {
			jQuery('.<?php echo $pk ?>').uniform();
		}
	});
	
	jQuery("#button-save").click(function() {
		jQuery('.alert').fadeOut('fast');

		jQuery("#button-save").attr('disabled', 'disabled');
		jQuery("#button-back").attr('disabled', 'disabled');

		jQuery.ajax({
			type: 'POST',
			url: jQuery('form').attr('action'),
			data: jQuery('form').serialize(),
			dataType: 'json',
		}).done(function(response) {
			jQuery('.alert').removeClass('alert-danger').addClass('alert-success').html(response.message).fadeIn(function() {
				window.setTimeout(function() {
					jQuery('.alert').fadeOut();
				}, 2000);
			});
		}).fail(function(response) {
			var ex = JSON.parse(response.responseText);

			jQuery('.alert-danger').removeClass('alert-success').addClass('alert-danger').html(ex.message).fadeIn(function() {
				window.setTimeout(function() {
					jQuery('.alert').fadeOut();
				}, 2000);
			});
		}).always(function() {
			jQuery("#button-save").removeAttr('disabled');
			jQuery("#button-back").removeAttr('disabled');
		});
	});

	jQuery("#button-delete").click(function() {
		if(jQuery('.<?php echo $pk ?>:checked').length === 0) {
			jQuery('#popup-delete').text(jQuery('#popup-delete').attr('data-message-select')).dialog({
				title: 'Message',
				resizable: false,
				modal: true,
				buttons: {
					'OK': function() {
						jQuery(this).dialog('close');
					}	
				}
			});
		} else {
			jQuery('#popup-delete').text(jQuery('#popup-delete').attr('data-message-delete').format(jQuery('.<?php echo $pk ?>:checked').length)).dialog({
				title: 'Confirmation',
				resizable: false,
				modal: true,
				buttons: {
					'Delete': function() {
						jQuery('button').attr('disabled', 'disabled');

						jQuery.ajax({
							type: 'POST',
							url: jQuery('form').attr('action'),
							data: jQuery('form').serialize(),
							dataType: 'json',
						}).done(function() {

						}).fail(function() {
							
						}).always(function() {
							jQuery('#popup-delete').dialog('close');
							jQuery('button').removeAttr('disabled');

							if(jQuery('.checkall').is(':checked')) {
								jQuery('.checkall').trigger('click');
							}

							datatable.DataTable().draw();
						});
					},
					'Cancel': function() {
						jQuery(this).dialog( 'close' );
					}	
				}
			});
		}
	});
});

if (!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
	    
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}