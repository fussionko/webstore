<div id="container-build-data">
    <div id="build-name"></div>
    <div id="build-creator"></div>
</div>

<div class="container-header">
    <span class="header-text">KOMPONENTA</span> 
    <span class="header-text">IME</span> 
    <span class="header-text">CENA</span>
</div>

<div class="container-component">
    <div class="config-item"><span class="config-item-text">Procesor</span><span class="config-item-id">cpu</span></div>
    <div class="config-item"><span class="config-item-text">Matična plošča</span><span class="config-item-id">moab</span></div>
    <div class="config-item"><span class="config-item-text">RAM</span><span class="config-item-id">ram</span></div>
    <div class="config-item"><span class="config-item-text">Grafična kartica</span><span class="config-item-id">gpu</span></div>
    <div class="config-item"><span class="config-item-text">Shramba</span><span class="config-item-id">storage</span></div>
    <div class="config-item"><span class="config-item-text">Procesorsko hlajenje</span><span class="config-item-id">cpu_cool</span></div>
    <div class="config-item"><span class="config-item-text">Sistemsko hlajenje</span><span class="config-item-id">sys_cool</span></div>
    <div class="config-item"><span class="config-item-text">Napajalnik</span><span class="config-item-id">psu</span></div>
    <div class="config-item"><span class="config-item-text">Ohišje</span><span class="config-item-id">case</span></div>
</div>

<div id="container-config-item-data">
    <div id="container-config-item-data-left">
        <div id="heat">
            <span>Toplota:</span>
            <span class="add"></span>
        </div>
        <div id="power-usage">
            <span>Poraba:</span>
            <span class="add"></span>
        </div>
    </div>
    <div id="container-config-item-data-middle">
        <div class="compatibility">
        </div>
    </div>
    <div id="container-config-item-data-right">
        <div id="price">
            <span>Cena:</span>
            <span class="add"></span>
        </div>
        <div id="psu-watt">
            <span>Presežek napajalnikove moči:</span>
            <!-- <span class="add"></span> -->
            <input type="number" id="watt-offset" min="0" max="40" step="1" value="10">%
        </div>
    </div>
</div>

<div class="container-button">
    <div class="button" id="button-save"><span>Shrani konfiguracijo</span></div>
</div>
