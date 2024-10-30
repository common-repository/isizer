jQuery(document).ready(function ($) {

	// $('#isizer_send_new_size').click(function (e) {
	// 	e.preventDefault();
	// 	var data = {
	// 		action: 'isize_add_size',
	// 		size_name: $('#isize_new_name').val(),
	// 		size_width: $('#isize_size_width').val(),
	// 		size_height: $('#isize_size_height').val(),
	// 		size_crop: $('#isize_size_crop').val(),
	// 	};
	//
	// 	jQuery.post(ajaxurl, data, function (response) {
	// 		alert('Получено с сервера: ' + response);
	// 	});
	// });

	$('.isizer-remove').click(function (e) {
		e.preventDefault();
		var data = {
			action: 'isize_remove_size',
			size_name: $(this).attr('data-name'),
		};

		jQuery.post(ajaxurl, data, function (response) {
			alert('Получено с сервера: ' + response);
		});
	});

});
