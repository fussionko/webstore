<div class="container-account-process" id="container-save-build">
    <h1>Podatki o konfiguraciji</h1>
   
    <div class="form"> 
        <div class="form-part" id="container-build-name">
            <label for="build-name">Vnesite ime konfiguracije</label>
            <input type="text" id="build-name" name="build-name" maxlength="60" required>
        </div>
        <div class="form-part" id="container-build-description">
            <label for="build-description">Vnesite opis konfiguracije (neobvezno)</label>
            <input type="text" id="build-description" name="build-description" maxlength="500">
        </div>
        <div class="form-part" id="container-build-email">
            <label for="build-email">Vpišite email naslov</label>
            <input type="email" id="build-email" name="build-email" required>
        </div>
        <div class="form-part" id="container-build-checkbox-public">
            <label class="stay" for="build-checkbox-public">Javno</label>
            <input type="checkbox" id="build-checkbox-public" name="build-checkbox-public" checked>
        </div>
        <div id="container-main"></div>
    </div>
    <div class="container-button">
        <div class="button" id="button-build-save"><span>Shrani konfiguracijo</span></div>
        <div class="button" id="button-prev"><span>Nazaj na konfiguracijo</span></div>
    </div>
</div>