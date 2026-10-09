<div class="full-width">
	<div class="block">
		<div class="block-title">
			<a href="<?php echo base_url(); ?>" class="right">Kembali Beranda</a>
			<h2>Konfirmasi Pembayaran Zakat</h2>
		</div>
		<div class="block-content">
            <style>
                .zakat-header { text-align: center; margin-bottom: 30px; }
                .zakat-header h3 { color: #2e7d32; font-weight: 700; margin-bottom: 5px; font-size: 28px; }
                .zakat-header p { font-size: 16px; color: #555; }
                
                .bank-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-bottom: 40px; }
                .bank-card { 
                    flex: 1 1 200px;
                    max-width: 250px;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    padding: 20px;
                    text-align: center;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                    background: #fff;
                    transition: transform 0.2s, box-shadow 0.2s;
                }
                .bank-card:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
                .bank-name { font-size: 18px; font-weight: bold; margin-bottom: 15px; display: block; color: #2e7d32; }
                
                .rek-item { margin-bottom: 10px; font-size: 14px; text-align: left; background: #f9f9f9; padding: 10px; border-radius: 5px; border-left: 3px solid #ccc; }
                .rek-item.rek-zakat { border-left-color: #f39c12; }
                .rek-item.rek-infaq { border-left-color: #27ae60; }
                .rek-type { font-weight: bold; display: block; font-size: 13px; color:#555;}
                .rek-number { display: block; font-size: 16px; font-weight: 600; color: #111; letter-spacing: 0.5px; margin-top:2px; }

                .btn-copy {
                    background: #f4f8f4;
                    border: 1px solid #e2efe3;
                    color: #2e7d32;
                    border-radius: 6px;
                    padding: 4px 10px;
                    font-size: 11px;
                    font-weight: 700;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    transition: all 0.2s ease;
                    outline: none;
                    margin-left: auto;
                }
                
                .btn-copy:hover {
                    background: #2e7d32;
                    color: #fff;
                    border-color: #2e7d32;
                }

                .btn-copy i {
                    font-size: 12px;
                }
                
                .rek-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    margin-top: 4px;
                }

                .qris-container { text-align: center; margin-bottom: 40px; padding: 30px; background: #f4f8f4; border-radius: 10px; border: 1px dashed #4caf50; }
                .qris-container img { max-width: 250px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .qris-title { font-size: 20px; font-weight: bold; color: #2e7d32; margin-bottom: 15px; }
                
                .wa-button-container { text-align: center; margin-top: 30px; margin-bottom: 30px; }
                .wa-button { 
                    display: inline-flex; align-items: center; justify-content: center;
                    background: #25D366; color: white; padding: 15px 30px; font-size: 18px; font-weight: bold;
                    border-radius: 50px; text-decoration: none; box-shadow: 0 4px 10px rgba(37, 211, 102, 0.4);
                    transition: all 0.3s;
                }
                .wa-button:hover { background: #1ebe57; color: white; transform: scale(1.05); }
                .wa-icon { font-size: 24px; margin-right: 10px; }
            </style>
            
            <div class="zakat-header">
                <h3>REKENING ZIS BAZNAS</h3>
                <p>A.n BAZNAS Kabupaten Sumbawa<br>Mari tunaikan zakat Anda Ke BAZNAS Melalui Transfer Ke rekening di bawah ini:</p>
            </div>

            <div class="bank-grid">
                <?php foreach ($rekening as $row) { ?>
                <div class="bank-card">
                    <span class="bank-name"><?php echo $row['nama_bank']; ?></span>
                    <?php if (!empty($row['rek_zakat'])) { ?>
                    <div class="rek-item rek-zakat">
                        <span class="rek-type">Rekening Zakat</span>
                        <div class="rek-row">
                            <span class="rek-number"><?php echo $row['rek_zakat']; ?></span>
                            <button onclick="copyToClipboard('<?php echo $row['rek_zakat']; ?>', this)" class="btn-copy" title="Salin Nomor Rekening">
                                <i class="fa fa-copy"></i> Salin
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if (!empty($row['rek_infaq'])) { ?>
                    <div class="rek-item rek-infaq">
                        <span class="rek-type">Rekening Infaq</span>
                        <div class="rek-row">
                            <span class="rek-number"><?php echo $row['rek_infaq']; ?></span>
                            <button onclick="copyToClipboard('<?php echo $row['rek_infaq']; ?>', this)" class="btn-copy" title="Salin Nomor Rekening">
                                <i class="fa fa-copy"></i> Salin
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>

            <?php if (!empty($iden['qris_image'])) { ?>
            <div class="qris-container">
                <div class="qris-title">Atau Bayar Cepat Pakai QRIS BAZNAS</div>
                <img src="<?php echo base_url(); ?>asset/images/<?php echo $iden['qris_image']; ?>" alt="QRIS BAZNAS">
                <p style="margin-top:15px; color:#555;">Scan barcode di atas menggunakan aplikasi e-Wallet <br>atau saluran <i>Mobile Banking</i> kesayangan Anda.</p>
            </div>
            <?php } ?>

            <?php 
                $telp = str_replace(array('-', ' ', '+'), '', $iden['no_telp']);
                if (substr($telp, 0, 1) == '0') { $telp = '62' . substr($telp, 1); }
            ?>
            <div class="wa-button-container">
                <a href="https://api.whatsapp.com/send?phone=<?php echo $telp; ?>&text=Assalamu'alaikum%20BAZNAS,%20saya%20ingin%20mengkonfirmasi%20pembayaran%20Zakat/Infaq%20saya" target="_blank" class="wa-button">
                    <i class="fa fa-whatsapp wa-icon"></i> Konfirmasi Zakat via WhatsApp
                </a>
            </div>

		</div>
	</div>
</div>

<script>
function copyToClipboard(text, element) {
    const exactText = text.trim();
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(exactText).then(function() {
            showCopiedFeedback(element);
        }).catch(function() {
            fallbackCopy(exactText, element);
        });
    } else {
        fallbackCopy(exactText, element);
    }
}

function fallbackCopy(text, element) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    tempInput.style.position = 'absolute';
    tempInput.style.left = '-9999px';
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
        document.execCommand('copy');
        showCopiedFeedback(element);
    } catch (err) {
        console.error('Gagal menyalin teks', err);
    }
    document.body.removeChild(tempInput);
}

function showCopiedFeedback(element) {
    const originalHTML = element.innerHTML;
    element.innerHTML = '<i class="fa fa-check"></i> Tersalin!';
    element.style.background = '#2e7d32';
    element.style.color = '#fff';
    element.style.borderColor = '#2e7d32';
    
    setTimeout(function() {
        element.innerHTML = originalHTML;
        element.style.background = '';
        element.style.color = '';
        element.style.borderColor = '';
    }, 2000);
}
</script>
