// darkmode.js

const settingsModal = document.getElementById("settingsModal");
const settingsIcon = document.getElementById("settings-icon");
const closeButtons = document.querySelectorAll(".close");
const darkModeToggle = document.getElementById("darkModeToggle");

// Open settings modal
settingsIcon.onclick = () => {
    settingsModal.style.display = "flex";
    darkModeToggle.checked = localStorage.getItem("dark_mode") === "true"; // Set toggle based on stored value
};

// Close settings modal
closeButtons.forEach(btn => {
    btn.onclick = () => settingsModal.style.display = "none";
});

// Close modal when clicking outside of it
window.onclick = event => {
    if (event.target === settingsModal) {
        settingsModal.style.display = "none";
    }
};

// Dark mode toggle functionality
darkModeToggle.onchange = () => {
    const isChecked = darkModeToggle.checked;
    document.body.classList.toggle("dark-mode", isChecked);
    
    // Store preference in local storage
    localStorage.setItem("dark_mode", isChecked);
};

// Apply dark mode on page load
window.onload = () => {
    const darkMode = localStorage.getItem("dark_mode");
    if (darkMode === "true") {
        document.body.classList.add("dark-mode");
        darkModeToggle.checked = true;
    }
};