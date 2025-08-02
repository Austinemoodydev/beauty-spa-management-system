document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('a[href*="delete.php"]').forEach(link => {
        link.addEventListener("click", e => {
            const confirmed = confirm("Are you sure you want to delete this appointment?");
            if (!confirmed) e.preventDefault();
        });
    });
});
