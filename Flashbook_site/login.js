$('#submit').click((event) => {
	event.preventDefault();
	
	$.ajax({
		url: 'php/login.php',
		type: 'POST',
		dataType: 'json',
		data: {
			pseudo: $('#pseudo').val(),
			pwd: $('#pwd').val(),
		},
		success: (res) => {
			if (res.success) {
				localStorage.setItem("user", JSON.stringify(res.user));
				window.location.replace('home.html');
			} else alert(res.error);
		}
	});
});