document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".sidebar");
    const toggleBtn = document.querySelector(".toggle-btn");

    // Toggle sidebar when hamburger button is clicked
    toggleBtn.addEventListener("click", function (event) {
        sidebar.classList.toggle("hidden");
        event.stopPropagation(); // Prevent click event from bubbling up
    });

    // Hide sidebar when clicking anywhere on the page
    document.addEventListener("click", function () {
        sidebar.classList.add("hidden");
    });

    // Prevent sidebar from hiding when clicking inside it
    sidebar.addEventListener("click", function (event) {
        event.stopPropagation();
    });

    // Show sidebar when hovering over its area
    sidebar.addEventListener("mouseenter", function () {
        sidebar.classList.remove("hidden");
    });

    // Hide sidebar when the mouse leaves it
    sidebar.addEventListener("mouseleave", function () {
        sidebar.classList.add("hidden");
    });
});
