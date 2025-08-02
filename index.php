<?php include('./includes/header.php'); ?>
<?php include('./includes/aside.php'); ?>

<style>
    section {
        padding: 2rem;
        text-align: center;
        background-color: #f9f9f9;
        border-radius: 12px;
        margin: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.5s ease-in-out;
    }

    h2 {
        font-size: 2.5rem;
        color: #e75480;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.8s ease;
    }

    p {
        font-size: 1.2rem;
        color: #333;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s ease;
    }

    .visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
</style>

<section>
    <h2 id="welcome-title">Welcome to The Loft Spa Management System</h2>
    <p id="welcome-text">Use the sidebar to manage appointments, clients, staff, and more.</p>
</section>

<script>
    window.addEventListener("DOMContentLoaded", () => {
        const title = document.getElementById("welcome-title");
        const text = document.getElementById("welcome-text");

        // Delay animation slightly for better UX
        setTimeout(() => {
            title.classList.add("visible");
        }, 200);

        setTimeout(() => {
            text.classList.add("visible");
        }, 600);
    });
</script>

<?php include('./includes/footer.php'); ?>