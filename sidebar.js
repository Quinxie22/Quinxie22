
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('expanded'); // Toggle expanded class
}

// Add hover effect for description cards
document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function () {
            const card = item.querySelector('.description-card');
            card.style.display = 'block';
            card.style.opacity = '1';
        });

        item.addEventListener('mouseleave', function () {
            const card = item.querySelector('.description-card');
            card.style.display = 'none';
            card.style.opacity = '0';
        });
    });
});

// Function to keep the session alive
function keepSessionAlive() {
    fetch('keep_session_alive.php', { method: 'POST' })
        .then(response => {
            if (!response.ok) {
                console.error('Failed to keep session alive.');
            }
        });
}

// Call keepSessionAlive every 5 minutes (300000 milliseconds)
setInterval(keepSessionAlive, 300000); // 5 minutes
function toggleDropdown() {
    var dropdown = document.getElementById("coursesDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.selected')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = "none";
        }
    }
}