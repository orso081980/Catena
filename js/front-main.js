console.log(wpApiSettings.nonce);

$( document ).ready(function() {

	const name = $('#post input[name=name]');
	const rating = $('#post input[name=rating]');
	const postinputs = $('#form-submit');
	const table = document.querySelector('#list');
	let lastitem;
	let suggest;

	$.ajax({

		method: 'GET',
		url: wpApiSettings.root+'top-list-route/my-top-list-get',
		contentType: 'application/json; charset=utf-8',
		beforeSend: function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
		},
		dataType: 'json',
		success: ajaxResponse

	});

	function ajaxResponse(data) {

		let str = [];
		suggest = data;
		lastitem = data.slice(-1)[0];

		for ( let i = 0; i < suggest.length; i ++ ) {
			str += `<tr data-id="${suggest[i].id}">`;
			str += `<td><input name="name" type="text" value=${suggest[i].name}></td>`;
			str += `<td><input name="rating" type="text" value=${suggest[i].rating}></td>`;
			str += `<td><button class="update">Update</button><input name="update[]" type="hidden" value=${suggest[i].id}></td>`;
			str += `<td><button class="delete">Delete</button><input name="delete[]" type="hidden" value=${suggest[i].id}></td>`;
			str += `<input name="brand_id" type="hidden" value=${suggest[i].brand_id}>`;
			str += '</tr>';
		}

		table.innerHTML = str;
	}
	
	postinputs.on('submit', function (e) {

		e.preventDefault();

		let data = {
			name: name[0].value,
			rating: rating[0].value,
		};

		$.ajax({
			method: 'POST',
			url: wpApiSettings.root+'top-list-route/my-top-list-get',
			data: JSON.stringify( data ),
			contentType: 'application/json; charset=utf-8',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			},
			dataType: 'json',
			success: function ( data ) {
				suggest = data;

				let name = suggest[0].data.name;
				let rating = suggest[0].data.rating;

				if (lastitem == null) {
					lastitemid = 1;
				} else {
					lastitemid = (parseInt(lastitem.id) + 1);
				}
				
				let str = [];

				str += '<tr>';
				str += `<td><input type="text" value=${name}></td>`;
				str += `<td><input type="text" value=${rating}></td>`;
				str += `<td><button type="button">Update</button></td>`;
				str += `<td><button class="delete">Delete</button><input name="delete[]" type="hidden" value=${lastitemid}></td>`;
				str += '</tr>';
				$(table).append(str);

			},
			error: function( e ) {
			}
		});

	});


	$(document).on('click', '.delete', function() {

		var log = $(this).siblings('input[name="delete[]"]').val();
		let data = {
			id: log
		};

		$.ajax({

			method: 'DELETE',
			url: wpApiSettings.root+'top-list-route/my-top-list-get',
			data: JSON.stringify( data ),
			contentType: 'application/json; charset=utf-8',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			},
			dataType: 'json',
			success: function ( data ) {
				$("tr[data-id='" + data[0].data.id + "']").remove();
			},
			error: function( e ) {
			}

		});

	});

	$(document).on('click', '.update', function() {

		var log = $(this).siblings('input[name="update[]"]').val();
		var rating = $(this).parent().parent().find('input[name="rating"]').val();
		var name = $(this).parent().parent().find('input[name="name"]').val();

		let data = {
			id: log,
			rating: rating,
			name: name
		};

		$.ajax({

			method: 'PUT',
			url: wpApiSettings.root+'top-list-route/my-top-list-get',
			data: JSON.stringify( data ),
			contentType: 'application/json; charset=utf-8',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			},
			dataType: 'json',
			success: function ( data ) {
				console.log(data);
			},
			error: function( e ) {
			}

		});

	});

});
