$('#submit').click((event) => {
	event.preventDefault();

	$.ajax({
		url: 'php/register.php',
		type: 'POST',
		dataType: 'json',
		data: {
			pseudo: $('#pseudo').val(),
			email: $('#email').val(),
			pwd: $('#pwd').val(),
		},
		success: (res) => {
			res.success ? window.location.replace('index.html') : alert(res.error);
		}
	});
});