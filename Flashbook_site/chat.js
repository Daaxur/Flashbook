const urlParams = new URLSearchParams(window.location.search);
let id = urlParams.get('id');
let group_name = urlParams.get('name');

function loadChat() {
    $.ajax({
        url: 'php/chat.php',
        type: 'GET',
        dataType: 'json',
        data: {
            choice: "select_chats",
            group_id: id
        },
        success: (res) => {
            if (res.success) {
                res.messages?.forEach(message => {
                    const mctn = $("<div></div>");

                    let userpicture = null;
                    if (message.user_picture) userpicture = $('<img>').attr("src", message.user_picture);

                    const u = $("<p></p>").text(message.user_pseudo);

                    const m = $("<p></p>").text(message.message_content);

                    const date = $("<small></small>").text(message.message_date);

                    message.users_id == user.user_id ? mctn.addClass("gold right") : mctn.addClass("white left");

                    mctn.append(userpicture, u, m, date);
                    $("#messages_ctn").append(mctn);
                });
            } else alert(res.error);
        }
    });
}

function loadGroup() {
    $("title").text(group_name);
}

function loadUsers() {
    $.ajax({
        url: 'php/user.php',
        type: 'GET',
        dataType: 'json',
        data: {
            choice: "select_isnotingroup",
            group_id: id
        },
        success: (res) => {
            if (res.success) {
                res.users?.forEach(user => {
                    const option = $("<option></option>");
                    option.val(user.user_id);
                    option.text(user.user_pseudo);
    
                    $("#pseudo").append(option);
                });
            } else alert(res.error);
        }
    });
}

function newOrNot() {
    if (id) {
        $("#newchat").hide();
        $("#messages").show();
        loadGroup();
        loadChat();
        loadUsers();
    } else {
        $("#messages").hide();
        $("#newchat").show();
    }
}
newOrNot();

$("#submit").click((e) => {
    e.preventDefault();

    $.ajax({
        url: 'php/group.php',
        type: 'POST',
        dataType: 'json',
        data: {
            choice: "create_group",
            name: $("#group_name").val()
        },
        success: (res) => {
            if (res.success) {
                id = res.group_id;
                group_name = $("#group_name").val();
                window.history.replaceState(null, null, "?id=" + id);
                newOrNot();
            } else alert(res.error);
        }
    });
});

$("#add_chat").click((e) => {
    e.preventDefault();

    $.ajax({
        url: 'php/chat.php',
        type: 'POST',
        dataType: 'json',
        data: {
            choice: "insert_chat",
            content: $("#content").val(),
            group_id: id
        },
        success: (res) => {
            if (res.success) {
                const mctn = $("<div></div>");
                mctn.addClass("gold right");

                const u = $("<p></p>").text(user.user_pseudo);

                const m = $("<p></p>").text($("#content").val());

                const date = $("<small></small>").text(new Date().toLocaleString());

                mctn.append(u, m, date);
                $("#messages_ctn").append(mctn);

                document.getElementById("formchat").reset();
            } else alert(res.error);
        }
    });
});

$("#add_user").click((e) => {
    e.preventDefault();

    $.ajax({
        url: 'php/group.php',
        type: 'POST',
        dataType: 'json',
        data: {
            choice: "add_user",
            user_id: $("#pseudo").val(),
            group_id: id
        },
        success: (res) => {
            if (res.success) {
                alert("Utilisateur ajouté");
                $('#pseudo option[value="' + $("#pseudo").val() + '"]').remove();
            } else alert(res.error);
        }
    });
});

// $("#delete_chat").click((e) => {
    //e.preventDefault();

    //$.ajax({
        //url: 'php/chat.php',
        //type: 'POST',
        //dataType: 'json',
        //data: {
            //choice: "delete_chat",
            //content: $("#content").val(),
            //group_id: id
        //},
        //success: (res) => {
            //if (res.success) {
                //alert("Message supprimé ajouté");
           // } else alert(res.error);
//}
   // });
//});