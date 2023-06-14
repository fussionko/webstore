
<div class="container-account-process" id="container-registration">
    <h1>Registracija</h1>
    <div class="form">
        <div class="form-part" id="container-username">
            <label for="username">Uporabniško ime</label>
            <input type="text" id="username" name="username" maxlength="30" minlength="6" required>
        </div>
        <div class="form-part" id="container-email">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-part" id="container-password">
            <label for="password">Geslo</label>
            <input type="password" id="password" name="password" maxlength="30" minlength="6" required>
        </div>
        <div class="form-part" id="container-password-repeat">
            <label for="password-repeat">Ponovite geslo</label>
            <input type="password" id="password-repeat" name="password-repeat" maxlength="30" minlength="6" required>
        </div>
        <div class="form-part" id="container-name">
            <label for="name">Ime</label>
            <input type="text" id="name" name="name" minlength="2" maxlength="40" required>
        </div>
        <div class="form-part" id="container-lastname">
            <label for="lastname">Priimek</label>
            <input type="text" id="lastname" name="lastname" minlength="2" maxlength="40" required>
        </div>
        <div class="form-part" id="container-gender">
            <div class="gender-type">
                <input type="radio" id="man" name="gender-type" checked>
                <label for="man">Moški</label>
            </div>
            <div class="gender-type">
                <input type="radio" id="woman" name="gender-type">
                <label for="woman">Ženska</label>
            </div>
            <div class="gender-type">
                <input type="radio" id="unknown" name="gender-type">
                <label for="unknown">Neznano</label>
            </div>
        </div>
        <div id="container-main"></div>
    </div>
    <div class="container-button">
        <div class="button" id="button-send"><span>Registracija</span></div>
        <div class="button" id="button-login"><span>Prijava</span></div>
    </div>
</div>
