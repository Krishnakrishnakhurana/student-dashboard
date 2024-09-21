<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Set body and html height to 100% */
    html, body {
        height: 100%;
    }

    /* Flexbox to push the footer down */
    .content-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Full height of the viewport */
    }

    main {
        flex: 1;
    }

    footer {
        text-align: center;
        padding: 20px;
        background-color: #07294d;
        color: white;
    }
</style>

<!-- Wrap content and footer in content-wrapper -->
<div class="content-wrapper">
    <main>
        <!-- Your page content goes here -->
    </main>

    <footer>
        <p class="text-center">&copy; 2024 College Dashboard. All rights reserved.</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
