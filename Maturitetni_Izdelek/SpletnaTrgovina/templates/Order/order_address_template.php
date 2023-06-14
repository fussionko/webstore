<div class="container-order-process" id="container-order-address">
    <h1>Vnesite naslov</h1>
    <div class="form"> 
        <div class="form-part" id="container-country">
            <label for="country" class="stay">Izberite državo</label>
            <select id="country" name="country"></select>
        </div>
        <div class="form-part" id="container-city">
            <label for="city">Vnesite ime mesta</label>
            <input type="text" id="city" name="city" required>
        </div>
        <div class="form-part" id="container-postal-code">
            <label for="postal-code">Vnesite postno številko</label>
            <input type="text" id="postal-code" name="postal-code" minlength="4" maxlength="4" required>
        </div>
        <div class="form-part" id="container-address">
            <label for="address">Vnesite naslov</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="form-part" id="container-telephone-number">
            <label for="telephone-number">Vnesite telefonsko številko</label>
            <input type="tel" id="telephone-number" name="telephone-number" minlength="9" maxlength="11" required>
        </div>
        <div class="form-part" id="container-email">
            <label for="email">Vnesite email naslov</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div id="container-user-addresses"></div>
        <div id="container-main"></div>
    </div>
    <div class="container-button">
        <div class="button" id="button-next"><span>Naprej</span></div>
        <div class="button" id="button-prev"><span>Nazaj</span></div>
    </div>
</div>
