<?php
include_once "../php/utils/guard_auth.php";
include_once "../php/admin/admin_logged.php";
include_once "./common/header.php";
?>

<title></title>
<!-- FIle css e js che ti servono -->
<link rel="stylesheet" href="../css/pages/book.css">
<link rel="stylesheet" href="../css/Cards/card-common.css" />
<link rel="stylesheet" href="../css/Cards/card-desktop.css" />
<link rel="stylesheet" href="../css/Cards/card-mobile.css" />
<link rel="stylesheet" href="../css/Reviews/review-common.css" />
<link rel="stylesheet" href="../css/Reviews/review-desktop.css" />
<link rel="stylesheet" href="../css/Reviews/review-mobile.css" />
<link rel="stylesheet" href="../css/pages/user-profile.css">
<script type="module" src="../js/navbar/navbar.js"></script>
<script type="module" src="../js/utils/book-card.js"></script>
<script type="module" src="../js/utils/book-review.js"></script>
<script type="module" src="../js/utils/message.js"></script>
<script type="module" src="../js/utils/report.js"></script>
<script type="module" src="../js/utils/vote.js"></script>
<script type="module" src="../js/pages/user-profile.js"></script>
<script type="module" src="../js/utils/password-check.js"></script>
</head>

<body>
    <div class="grid-container">
        <?php
        include_once "./common/navbar.php";
        include_once "./common/main_section.php"; ?>
        <div class="main-content">
            <div class="main-content-header">
                <h1></h1>
            </div>
            <div class="main-content-cards">
                <div class="stats">
                    <div class="reviewed-books-number">
                        <h2><strong></strong></h2>
                        <h3>libri recensiti</h3>
                    </div>
                    <div class="upvote-number">
                        <h2><strong></strong></h2>
                        <h3>upvote ricevuti</h3>
                    </div>
                </div>
                <div class="profile-settings">
                    <div class="change-password">
                        <button id="change-password">Cambia password</button>
                        <form class="change-password-form">
                            Vecchia password:
                            <input type="password" name="old-pw" id="old-password">
                            Nuova password:
                            <input type="password" name="new-pw" id="new-password">
                            Conferma nuova password:
                            <input type="password" name="new-pw-confirmation" id="new-password-confirmation">
                            <div class="pw-requirements">
                                <p>La password deve:</p>
                                <ul>
                                    <li>Essere lunga almeno 8 caratteri</li>
                                    <li>Contenere almeno un carattere speciale</li>
                                </ul>
                            </div>
                            <div class="pw-not-matching">
                                <p>Le due password non corrispondono</p>
                            </div>
                            <button id="submit-pw-change">Conferma</button>
                        </form>
                    </div>
                    <div class="delete-profile">
                        <button id="delete-profile">Elimina il profilo</button>
                        <div class="consequences">
                            <p>Ne sei veramente sicuro?</p>
                            <ul>
                                <li>L'azione è <strong>irreversibile</strong></li>
                                <li>Tutte le recensioni che hai scritto andranno perse</li>
                            </ul>
                            <div class="button-area">
                                <button id="safe">Annulla</button>
                                <button id="nuke">Conferma</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
        <?php include_once "./common/footer.php"; ?>
    </div>
</body>

</html>