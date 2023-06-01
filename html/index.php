<?php include_once "../php/utils/already_logged.php"; ?>
<?php include_once "./common/header-index.php"; ?>

<title>BookLoversItalia</title>
<link rel="stylesheet" href="../css/pages/index.css">
<link rel="stylesheet" href="../css/global-common.css">
<script type="module" src="../js/pages/index.js"></script>
<script type="module" src="../js/utils/password-check.js"></script>
</head>

<body>
    <div class="grid-container">
        <header>
            <button><img src="../img/logo.svg" alt="logo"></button>
            <a href="#" id="title">BookLoversItalia</a>
        </header>
        <section class="info">
            <p>
                <span class="bold">BookLoversItalia</span> è la più grande community in italia
                per gli appassionati del mondo dei libri.
            </p>
            <p>Registrandoti avrai accesso a tutte le recensioni dei libri scritte dai
                nostri utenti e potrai a tua volta contribuire a far crescere l'elenco
                dei libri di cui è presente una recensione.
            </p>
            <p>Registrati adesso e aiuta la community a scoprire nuove letture!</p>
        </section>
        <section class="login">

            <form id="register-form">
                <div id="register-form-div" class="">
                    <button type="button" id="register-button" class="register-form-toggle section-toggled section-button-hover">Registrati</button>
                    <button type="button" id="login-button" class="login-form-toggle section-button-hover">Accedi</button>
                    <label for="username-register">Nome utente:</label>
                    <input type="text" name="username" id="username-register" required="on">
                    <label for="password-register">Password:</label>
                    <input type="password" name="password" id="password-register" autocomplete="off">
                    <div id="pw-requirements">
                        <p>La password deve:</p>
                        <ul>
                            <li>Essere lunga almeno 8 caratteri</li>
                            <li>Contenere almeno un carattere speciale</li>
                        </ul>
                    </div>
                    <label for="pw-confirmation-register">Conferma la tua password:</label>
                    <input type="password" name="pwconfirmation" id="pw-confirmation-register">
                    <div class="password-error hidden">Le due password non corrispondono</div>
                    <button type="button" id="register" class="button section-button-hover">Registrati!</button>
                </div>
            </form>
            <form id="login-form">
                <div id="login-form-div" class="hidden">
                    <button type="button" id="register-button2" class="register-form-toggle section-button-hover">Registrati</button>
                    <button type="button" id="login-button2" class="login-form-toggle section-toggled section-button-hover">Accedi</button>
                    <label for="username-login">Nome utente:</label>
                    <input type="text" name="username" id="username-login">
                    <label for="password-login">Password:</label>
                    <input type="password" name="password" id="password-login">
                    <div class="login-error hidden"></div>
                    <button type="button" id="login" class="button section-button-hover">Accedi</button>

                </div>
            </form>
        </section>
        <?php include "./common/footer.php"; ?>