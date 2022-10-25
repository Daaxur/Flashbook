// echo "<a href='php/chat.php?id=$idgroup'><input type='button' value='$nomgroup'></a>";

$.ajax({
    url: 'php/group.php',
    type: 'GET',
    dataType: 'json',
    data: {
        choice: "select_groups"
    },
    success: (res) => {
        if (res.success) {
            res.groups?.forEach(group => { 
                const a = $("<a></a>");
                a.text(group.group_name);
                a.attr("href", "chat.html?id=" + group.group_id + "&name=" + group.group_name);

                $("#chats").append(a);
            });
        } else alert(res.error);
    }
});