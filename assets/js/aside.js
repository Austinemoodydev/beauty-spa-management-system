// aside.js
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
}

document.addEventListener('DOMContentLoaded', function () {
    const submenuParents = document.querySelectorAll('.has-submenu');

    submenuParents.forEach(item => {
        item.querySelector('.nav-link').addEventListener('click', function (e) {
            e.preventDefault();
            item.classList.toggle('open');
        });
    });

    // Optional: Set active link based on URL
    const links = document.querySelectorAll('.nav-link');
    links.forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
});

// Optional Dark Mode Toggle
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}
