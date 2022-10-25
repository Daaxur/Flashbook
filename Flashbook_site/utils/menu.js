const ctn = $("<div></div>");
ctn.addClass("text-center");

let userpicture = null;
if (user.user_picture) userpicture = $('<img>').attr("src", user.user_picture);

const username = $("<h1></h1>").text(user.user_pseudo);
username.attr("id", "username", "gold");

const home = $("<a></a>");
home.text("Home");
home.attr("href", "home.html");
home.addClass("btn btn-outline-warning gold mt-5");

const profil = $("<a></a>");
profil.text("Modify user information");
profil.attr("href", "profil.html");
profil.addClass("btn btn-outline-warning gold mt-5");

const logout = $("<a></a>");
logout.text("Logout");
logout.attr("href", "#");
logout.addClass("btn btn-outline-warning gold mt-5");

const chat = $("<a></a>");
chat.text("Start a chat group");
chat.attr("href", "chat.html");
chat.addClass("btn btn-outline-warning gold mt-5");

ctn.append(userpicture, username, home, profil, logout, chat);
$("body").prepend(ctn);

logout.click(() => {
    $.ajax({
        url: 'php/logout.php',
		type: 'GET',
		dataType: 'json',
		success: (res) => {
			if (res.success) {
                localStorage.removeItem("user");
                window.location.replace('index.html')
            } else alert('Logout failed');
		}
    });
});