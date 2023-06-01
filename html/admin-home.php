<?php
include_once "../php/utils/guard_auth.php";
include_once "../php/utils/guard_admin.php";
include_once "./common/header.php";
?>

<title>BookLoversItalia - Admin</title>
<link rel="stylesheet" href="../css/pages/home.css">
<link rel="stylesheet" href="../css/Cards/card-common.css">
<link rel="stylesheet" href="../css/Cards/card-desktop.css">
<link rel="stylesheet" href="../css/Cards/card-mobile.css">
<link rel="stylesheet" href="../css/editor/editor-template-desktop.css" />
<link rel="stylesheet" href="../css/editor/editor-template-mobile.css" />
<link rel="stylesheet" href="../css/editor/editor-template-common.css" />
<link rel="stylesheet" href="../css/admin/admin.css">
<script type="module" src="../js/pages/admin.js"></script>
<script type="module" src="../js/navbar/navbar.js"></script>
<script type="module" src="../js/utils/book-card.js"></script>
<script type="module" src="../js/utils/message.js"></script>
</head>

<body>
    <div class="grid-container">
        <?php
        include_once "./common/main_section.php"; ?>
        <div class="header">
            <h2 id="username">Ciao, </h2>
            <button id="logout">Logout</button>
        </div>
        <div class="card-area">

            <div class="admin-card" id="post-number">
                <strong>10</strong>
                <p>Libri totali</p>
            </div>
            <div class="admin-card" id="review-number">
                <strong>100</strong>
                <p>Recensioni totali</p>
            </div>
            <div class="admin-card" id="like-number">
                <strong>1000</strong>
                <p>Upvote totali</p>
            </div>
            <div class="admin-card" id="user-number">
                <strong>10</strong>
                <p>Utenti totali</p>
            </div>
            <div class="admin-card" id="book-reports">
                <strong>40</strong>
                <p>Libri segnalati</p>
            </div>
            <div class="admin-card" id="review-reports">
                <strong>20</strong>
                <p>Recensioni segnalate</p>
            </div>
        </div>
        </section>
        <?php include_once "./common/footer.php"; ?>
    </div>
</body>

</html>