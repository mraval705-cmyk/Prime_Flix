document.querySelectorAll(".profile").forEach(profile => {
    profile.addEventListener("click", () => {
        alert("Profile selected!");
        // window.location.href = "home.html";
    });
});
