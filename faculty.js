const sidebar = document.getElementById("sidebar");
const toggleButton = document.querySelector(".toggle-btn");

function toggleSidebar() {
    sidebar.classList.toggle("expanded");
}

document.addEventListener("click", function(event) {
    if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
        sidebar.classList.remove("expanded");
    }
});

sidebar.addEventListener("mouseenter", function() {
    sidebar.classList.add("expanded");
});

sidebar.addEventListener("mouseleave", function() {
    sidebar.classList.remove("expanded");
});

// JavaScript to handle tooltip visibility
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
        const tooltip = item.querySelector('.tooltip');
        if (tooltip) {
            tooltip.style.visibility = 'visible';
            tooltip.style.opacity = '1';
        }
    });

    item.addEventListener('mouseleave', () => {
        const tooltip = item.querySelector('.tooltip');
        if (tooltip) {
            tooltip.style.visibility = 'hidden';
            tooltip.style.opacity = '0';
        }
    });
});