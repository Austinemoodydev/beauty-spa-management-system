</main>
<footer>
    <p>&copy; <?php echo date("Y"); ?> The Loft Spa. All rights reserved.</p>
</footer>

<!-- JavaScript Enhancements -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }

    // Highlight active link in sidebar
    document.querySelectorAll('.nav-link').forEach(link => {
        const currentUrl = window.location.href.split(/[?#]/)[0];
        const linkUrl = link.href.split(/[?#]/)[0];
        if (linkUrl === currentUrl) {
            link.classList.add('active');
        }
    });
</script>

</body>

</html>