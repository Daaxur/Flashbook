const user = JSON.parse(localStorage.getItem("user"));

if (!user) window.location.replace("../index.html");