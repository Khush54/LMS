function show(id, direction) {
    const forms = document.querySelectorAll(".box");
    const box = document.getElementById("flip-box");

    forms.forEach(form => form.classList.remove("active"));
    document.getElementById(id).classList.add("active");

    box.classList.remove("flip-horizontal", "flip-vertical");
    if (direction === "horizontal") box.classList.add("flip-horizontal");
    else if (direction === "vertical") box.classList.add("flip-vertical");
}
