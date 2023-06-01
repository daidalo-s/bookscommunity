<?php
include_once "../php/utils/guard_auth.php";
include_once "../php/admin/admin_logged.php";
include_once "./common/header.php";
?>

<title>BookLoversItalia - Home</title>
<link rel="stylesheet" href="../css/pages/home.css">
<link rel="stylesheet" href="../css/Cards/card-common.css">
<link rel="stylesheet" href="../css/Cards/card-desktop.css">
<link rel="stylesheet" href="../css/Cards/card-mobile.css">
<link rel="stylesheet" href="../css/editor/editor-template-desktop.css" />
<link rel="stylesheet" href="../css/editor/editor-template-mobile.css" />
<link rel="stylesheet" href="../css/editor/editor-template-common.css" />
<script type="module" src="../js/pages/home.js"></script>
<script type="module" src="../js/navbar/navbar.js"></script>
<script type="module" src="../js/utils/book-card.js"></script>
<script type="module" src="../js/utils/message.js"></script>
</head>

<body>
    <div class="grid-container">
        <?php
        include_once "./common/navbar.php";
        include_once "./common/main_section.php"; ?>
        </section>
        <?php include_once "./common/footer.php"; ?>
    </div>
</body>

</html>