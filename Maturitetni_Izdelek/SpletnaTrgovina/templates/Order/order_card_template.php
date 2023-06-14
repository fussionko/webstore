<div class="container-order-process" id="container-order-card">
    <h1>Vnesite podatke o plačilu</h1>
    <div class="form"> 
        <div class="form-part" id="container-card-number">
            <label for="card-number">Vnesite številko kartice</label>
            <input type="text" id="card-number" name="card-number" minlength="16" maxlength="19">
        </div>
        <div class="form-part" id="container-cvv">
            <label for="cvv">Vnesite CVV kartice</label>
            <input type="text" id="cvv" name="cvv" pattern="^[0-9]{3}$" minlength="3" maxlength="3" required>
        </div>
        <div class="form-part" id="container-card-expires">
            <label for="card-expires">Vnesite datum veljavnosti</label>
            <input type="text" id="card-expires" name="card-expires" pattern="^[01]{1}[0-9]{1}\/[0-9]{2}$" minlength="5" maxlength="5" required>
        </div>
        <div class="form-part" id="container-cardholder-name">
            <label for="cardholder-name">Vnesite lastnika kartice</label>
            <input type="text" id="cardholder-name" name="cardholder-name" required>
        </div>
        <div class="form-part" id="container-description">
            <label for="description">Vnesite opis(neobvezno)</label>
            <input type="text" id="description" name="description" maxlength="45">
        </div>
        <div id="container-user-cards"></div>
        <div id="container-main"></div>
    </div>

    <div class="container-button">
        <div class="button" id="button-next"><span>Naprej</span></div>
        <div class="button" id="button-prev"><span>Nazaj</span></div>
    </div>
</div>
