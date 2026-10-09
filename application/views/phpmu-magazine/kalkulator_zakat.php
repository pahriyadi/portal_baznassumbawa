    <div class="main-page left" style="margin-top: 5px !important;">
        <div class="double-block">
            <div class="content-block main left">
                <div class="block">
                    <div class="block-title" style="background: #006937;">
                        <a href="<?php echo base_url(); ?>" class="right">Kembali ke Beranda</a>
                        <h2>Kalkulator Zakat Komprehensif</h2>
                    </div>
                    
                    <div class="block-content" style="padding-top: 5px;">
                        <div class="calc-inner-wrapper">
                            <div class="gold-price-bar">
                                <div class="gold-info">
                                    <strong>Nisab Emas: 85 Gram</strong>
                                    <p style="font-size: 11px; margin: 0; color: #666;">Harga emas per gram hari ini:</p>
                                </div>
                                <div class="gold-input-group">
                                    <span style="font-weight: 700; font-size: 13px;">Rp</span>
                                    <input type="number" id="global_gold_price" value="1200000" oninput="calculateAll()" style="width: 120px; padding: 6px 10px; border-radius: 6px; border: 1px solid #ddd;">
                                </div>
                            </div>

                            <div class="calc-tabs-modern">
                                <button class="tab-btn active" onclick="openTab(event, 'profesi')">Profesi</button>
                                <button class="tab-btn" onclick="openTab(event, 'maal')">Maal</button>
                                <button class="tab-btn" onclick="openTab(event, 'emas')">Emas</button>
                                <button class="tab-btn" onclick="openTab(event, 'dagang')">Dagang</button>
                                <button class="tab-btn" onclick="openTab(event, 'tani')">Tani</button>
                                <button class="tab-btn" onclick="openTab(event, 'ternak')">Ternak</button>
                            </div>

                            <div class="calc-content-area">
                                <!-- TAB: PROFESI -->
                                <div id="profesi" class="tab-pane active">
                                    <div class="form-group">
                                        <label>Penghasilan Per Bulan</label>
                                        <div class="input-wrapper">
                                            <span class="prefix">Rp</span>
                                            <input type="number" id="profesi_income" placeholder="0" oninput="calculateProfesi()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Bonus / Pendapatan Lainnya</label>
                                        <div class="input-wrapper">
                                            <span class="prefix">Rp</span>
                                            <input type="number" id="profesi_bonus" placeholder="0" oninput="calculateProfesi()">
                                        </div>
                                    </div>
                                    <div class="result-card">
                                        <h3>Zakat Profesi</h3>
                                        <div class="result-amount" id="val_profesi">Rp 0</div>
                                        <div class="nisab-info" id="nis_profesi">Nisab setara 85gr Emas/thn</div>
                                    </div>
                                </div>

                                <!-- TAB: MAAL -->
                                <div id="maal" class="tab-pane">
                                    <div class="form-group">
                                        <label>Tabungan / Deposito / Uang Tunai</label>
                                        <div class="input-wrapper">
                                            <span class="prefix">Rp</span>
                                            <input type="number" id="maal_wealth" placeholder="0" oninput="calculateMaal()">
                                        </div>
                                    </div>
                                    <div class="result-card">
                                        <h3>Zakat Maal</h3>
                                        <div class="result-amount" id="val_maal">Rp 0</div>
                                        <div class="nisab-info" id="nis_maal">Wajib dizakatkan jika sudah 1 tahun</div>
                                    </div>
                                </div>

                                <!-- TAB: EMAS -->
                                <div id="emas" class="tab-pane">
                                    <div class="form-group">
                                        <label>Berat Emas (Gram)</label>
                                        <div class="input-wrapper">
                                            <input type="number" id="emas_weight" placeholder="0" oninput="calculateEmas()" style="padding-left: 15px;">
                                            <span style="margin-left: 10px; font-weight: bold;">gr</span>
                                        </div>
                                    </div>
                                    <div class="result-card">
                                        <h3>Zakat Emas</h3>
                                        <div class="result-amount" id="val_emas">Rp 0</div>
                                        <div class="nisab-info" id="nis_emas">Nisab: 85 Gram</div>
                                    </div>
                                </div>

                                <!-- TAB: DAGANG -->
                                <div id="dagang" class="tab-pane">
                                    <div class="form-group">
                                        <label>Modal Lancar (Tunai + Stok + Piutang)</label>
                                        <div class="input-wrapper">
                                            <span class="prefix">Rp</span>
                                            <input type="number" id="dagang_assets" placeholder="0" oninput="calculateDagang()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Hutang Jatuh Tempo</label>
                                        <div class="input-wrapper">
                                            <span class="prefix">Rp</span>
                                            <input type="number" id="dagang_debt" placeholder="0" oninput="calculateDagang()">
                                        </div>
                                    </div>
                                    <div class="result-card">
                                        <h3>Zakat Dagang</h3>
                                        <div class="result-amount" id="val_dagang">Rp 0</div>
                                        <div class="nisab-info" id="nis_dagang">Nisab setara 85gr Emas</div>
                                    </div>
                                </div>

                                <!-- TAB: TANI -->
                                <div id="tani" class="tab-pane">
                                    <div class="form-group">
                                        <label>Hasil Panen (Kilogram)</label>
                                        <div class="input-wrapper">
                                            <input type="number" id="tani_yield" placeholder="0" oninput="calculateTani()" style="padding-left: 15px;">
                                            <span style="margin-left: 10px; font-weight: bold;">kg</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Sistem Pengairan</label>
                                        <div class="input-wrapper">
                                            <select id="tani_type" onchange="calculateTani()" style="padding-left: 15px; width: 100%; border: 1px solid #ddd; padding: 10px;">
                                                <option value="0.05">Irigasi Berbayar (5%)</option>
                                                <option value="0.1">Tadah Hujan (10%)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="result-card">
                                        <h3>Zakat Pertanian</h3>
                                        <div class="result-amount" id="val_tani">0 kg</div>
                                        <div class="nisab-info" id="nis_tani">Nisab: 653 kg gabah</div>
                                    </div>
                                </div>

                                <!-- TAB: TERNAK -->
                                <div id="ternak" class="tab-pane">
                                    <div class="form-group">
                                        <label>Jenis Hewan Ternak</label>
                                        <select id="ternak_type" onchange="calculateTernak()" style="width: 100%; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                                            <option value="kambing">Kambing / Domba</option>
                                            <option value="sapi">Sapi / Kerbau</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah Hewan (Ekor)</label>
                                        <input type="number" id="ternak_qty" placeholder="0" oninput="calculateTernak()" style="width: 100%; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                                    </div>
                                    <div class="result-card">
                                        <h3>Zakat Peternakan</h3>
                                        <div class="result-amount" id="val_ternak">-</div>
                                        <div class="nisab-info" id="nis_ternak">Ketentuan sesuai jumlah ekor</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-block right">
                <?php include "sidebar_kiri.php"; ?>
            </div>
        </div>
    </div>

    <div class="main-sidebar right">
        <?php include "sidebar_kanan.php"; ?>
    </div>

<style>
    .calc-inner-wrapper { font-family: 'Inter', sans-serif; }
    .gold-price-bar { background: #f9f9f9; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #eee; }
    
    .calc-tabs-modern { display: flex; gap: 5px; overflow-x: auto; margin-bottom: 20px; padding-bottom: 5px; border-bottom: 2px solid #f0f0f0; }
    .calc-tabs-modern .tab-btn { padding: 10px 18px; border: none; background: #f5f5f5; border-radius: 8px 8px 0 0; cursor: pointer; font-size: 13px; font-weight: 700; color: #777; transition: all 0.2s; white-space: nowrap; }
    .calc-tabs-modern .tab-btn.active { background: #006937; color: white; }

    .calc-content-area { background: #fff; }
    .tab-pane { display: none; animation: fadeIn 0.3s ease; }
    .tab-pane.active { display: block; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: #444; }
    
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-wrapper .prefix { position: absolute; left: 12px; font-weight: 700; color: #aaa; font-size: 14px; }
    .input-wrapper input { width: 100%; padding: 10px 12px 10px 40px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
    .input-wrapper input:focus { border-color: #006937; outline: none; box-shadow: 0 0 0 3px rgba(0,105,55,0.05); }

    .result-card { background: #f4fcf7; border: 1px solid #e0f0e5; border-radius: 12px; padding: 20px; text-align: center; margin-top: 25px; }
    .result-card h3 { font-size: 12px; text-transform: uppercase; color: #777; letter-spacing: 1px; margin-bottom: 8px; }
    .result-amount { font-size: 24px; font-weight: 800; color: #006937; margin-bottom: 5px; }
    .nisab-info { font-size: 11px; padding: 4px 12px; border-radius: 20px; display: inline-block; font-weight: 600; }
    .nisab-true { background: #d4edda; color: #155724; }
    .nisab-false { background: #fff3cd; color: #856404; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<script>
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-pane");
        for (i = 0; i < tabcontent.length; i++) { tabcontent[i].classList.remove("active"); }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].classList.remove("active"); }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    function calculateAll() {
        calculateProfesi(); calculateMaal(); calculateEmas(); calculateDagang(); calculateTani(); calculateTernak();
    }

    function calculateProfesi() {
        const income = parseFloat(document.getElementById('profesi_income').value) || 0;
        const bonus = parseFloat(document.getElementById('profesi_bonus').value) || 0;
        const total = income + bonus;
        const goldPrice = parseFloat(document.getElementById('global_gold_price').value) || 0;
        const nisabPerMonth = (85 * goldPrice) / 12;
        const nisabInfo = document.getElementById('nis_profesi');
        if (total >= nisabPerMonth) {
            document.getElementById('val_profesi').innerText = formatRupiah(total * 0.025);
            nisabInfo.innerText = "Sudah Wajib Zakat";
            nisabInfo.className = "nisab-info nisab-true";
        } else {
            document.getElementById('val_profesi').innerText = "Rp 0";
            nisabInfo.innerText = "Belum Nisab (Min: " + formatRupiah(nisabPerMonth) + ")";
            nisabInfo.className = "nisab-info nisab-false";
        }
    }

    function calculateMaal() {
        const wealth = parseFloat(document.getElementById('maal_wealth').value) || 0;
        const goldPrice = parseFloat(document.getElementById('global_gold_price').value) || 0;
        const nisab = 85 * goldPrice;
        const nisabInfo = document.getElementById('nis_maal');
        if (wealth >= nisab) {
            document.getElementById('val_maal').innerText = formatRupiah(wealth * 0.025);
            nisabInfo.innerText = "Sudah Wajib Zakat";
            nisabInfo.className = "nisab-info nisab-true";
        } else {
            document.getElementById('val_maal').innerText = "Rp 0";
            nisabInfo.innerText = "Belum Nisab (Min: " + formatRupiah(nisab) + ")";
            nisabInfo.className = "nisab-info nisab-false";
        }
    }

    function calculateEmas() {
        const weight = parseFloat(document.getElementById('emas_weight').value) || 0;
        const goldPrice = parseFloat(document.getElementById('global_gold_price').value) || 0;
        const nisabInfo = document.getElementById('nis_emas');
        if (weight >= 85) {
            document.getElementById('val_emas').innerText = formatRupiah((weight * goldPrice) * 0.025);
            nisabInfo.className = "nisab-info nisab-true";
        } else {
            document.getElementById('val_emas').innerText = "Rp 0";
            nisabInfo.className = "nisab-info nisab-false";
        }
    }

    function calculateDagang() {
        const assets = parseFloat(document.getElementById('dagang_assets').value) || 0;
        const debt = parseFloat(document.getElementById('dagang_debt').value) || 0;
        const total = assets - debt;
        const goldPrice = parseFloat(document.getElementById('global_gold_price').value) || 0;
        const nisab = 85 * goldPrice;
        const nisabInfo = document.getElementById('nis_dagang');
        if (total >= nisab) {
            document.getElementById('val_dagang').innerText = formatRupiah(total * 0.025);
            nisabInfo.innerText = "Wajib Zakat";
            nisabInfo.className = "nisab-info nisab-true";
        } else {
            document.getElementById('val_dagang').innerText = "Rp 0";
            nisabInfo.className = "nisab-info nisab-false";
        }
    }

    function calculateTani() {
        const yield = parseFloat(document.getElementById('tani_yield').value) || 0;
        const rate = parseFloat(document.getElementById('tani_type').value);
        const nisabInfo = document.getElementById('nis_tani');
        if (yield >= 653) {
            document.getElementById('val_tani').innerText = (yield * rate).toFixed(1) + " kg";
            nisabInfo.className = "nisab-info nisab-true";
        } else {
            document.getElementById('val_tani').innerText = "0 kg";
            nisabInfo.className = "nisab-info nisab-false";
        }
    }

    function calculateTernak() {
        const qty = parseInt(document.getElementById('ternak_qty').value) || 0;
        const type = document.getElementById('ternak_type').value;
        const display = document.getElementById('val_ternak');
        const nisabInfo = document.getElementById('nis_ternak');
        if (type === 'kambing') {
            if (qty < 40) { display.innerText = "-"; nisabInfo.className = "nisab-info nisab-false"; }
            else if (qty >= 40 && qty <= 120) { display.innerText = "1 Ekor Kambing"; nisabInfo.className = "nisab-info nisab-true"; }
            else { display.innerText = ">= 2 Ekor Kambing"; nisabInfo.className = "nisab-info nisab-true"; }
        } else {
            if (qty < 30) { display.innerText = "-"; nisabInfo.className = "nisab-info nisab-false"; }
            else { display.innerText = "1 Ekor Sapi (Dst)"; nisabInfo.className = "nisab-info nisab-true"; }
        }
    }
</script>
