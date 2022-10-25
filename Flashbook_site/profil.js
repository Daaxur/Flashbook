const isimg = user.users_picture ? $("<img>").attr("src", user.users_picture) : $("<p></p>").text("Pas d'image de profil");

$("#imgctn").html(isimg);

function uploadImg(files, pseudo) {
    const fd = new FormData();
    fd.append('file', files[0]);

    $.ajax({
        url: "php/upload.php",
        type: "POST",
        dataType: "json",
        data: fd,
        contentType: false,
        processData: false,
        cache: false,
        success: (upload) => {
            upload.success ? updateUser(pseudo, upload.picture) : alert(upload.error);
        }
    });
}

function updateUser(pseudo, picture = null) {
    if (!pseudo) pseudo = user.users_pseudo;
    $.ajax({
        url: 'php/user.php',
        type: 'POST',
        dataType: 'json',
        data: {
            choice: 'update',
            pseudo,
            picture
        },
        success: (res) => {
            if (res.success) {
                $("#username").text(pseudo);
                $("#imgctn").html($("<img>").attr("src", picture));
                user.users_picture = picture;
                localStorage.setItem("user", JSON.stringify(user));
            } else alert(res.error);
        }
    });
}

$("#submit").click((e) => {
    e.preventDefault();

    const files = $('#file')[0].files;
    const pseudo = $("#pseudo").val();

    files.length > 0 ? uploadImg(files, pseudo) : pseudo ? updateUser(pseudo) : alert("Aucune modification");
});

$("#delete").click((e) => {

    $.ajax({
		url: 'php/user.php',
		type: 'POST',
		dataType: 'json',
		data: { 
            choice: 'delete'
		},
		success: (res) => {
			res.success ? window.location.replace('index.html') : alert(res.error);
		}
	});

});