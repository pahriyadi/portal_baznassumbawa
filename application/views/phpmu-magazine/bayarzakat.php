<div class="full-width">
	<div class="block">
		<div class="block-title" style="border-bottom: 3px solid var(--baznas-yellow); padding-bottom: 10px; margin-bottom: 30px;">
			<a href="<?php echo base_url(); ?>" class="right" style="color: var(--baznas-green); font-weight: 700; text-decoration: none;">&larr; Kembali Beranda</a>
			<h2 style="color: var(--baznas-green); font-weight: 800; font-size: 24px;">Layanan Pembayaran Zakat & ZIS</h2>
		</div>
		<div class="block-content">
            <style>
                .zakat-header { text-align: center; margin-bottom: 40px; }
                .zakat-header h3 { color: var(--baznas-green); font-weight: 800; margin-bottom: 10px; font-size: 32px; }
                .zakat-header p { font-size: 18px; color: #555; max-width: 600px; margin: 0 auto; line-height: 1.6; }
                
                .bank-grid { 
                    display: grid; 
                    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
                    gap: 25px; 
                    margin-bottom: 50px; 
                }
                
                .bank-card { 
                    border-radius: 16px;
                    padding: 25px;
                    background: #fff;
                    border: 1px solid #f0f0f0;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
                    transition: all 0.3s ease;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
                
                .bank-card:hover { 
                    transform: translateY(-10px); 
                    box-shadow: 0 20px 40px rgba(0,0,50,0.08);
                    border-color: var(--baznas-green);
                }
                
                .bank-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 20px;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 15px;
                }
                
                .bank-icon {
                    width: 50px;
                    height: 50px;
                    background: #f4f8f4;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 15px;
                    color: var(--baznas-green);
                    font-size: 24px;
                }

                .bank-name { 
                    font-size: 20px; 
                    font-weight: 800; 
                    color: var(--baznas-dark); 
                }
                
                .rek-list {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }

                .rek-item { 
                    background: #fcfcfc; 
                    padding: 15px; 
                    border-radius: 12px; 
                    border: 1px solid #f0f0f0;
                    position: relative;
                }
                
                .rek-item.rek-zakat { border-left: 4px solid var(--baznas-yellow); }
                .rek-item.rek-infaq { border-left: 4px solid var(--baznas-green); }
                
                .rek-type { 
                    font-weight: 700; 
                    display: block; 
                    font-size: 12px; 
                    color: #888;
                    text-transform: uppercase;
                    margin-bottom: 4px;
                }
                
                .rek-number { 
                    display: block; 
                    font-size: 18px; 
                    font-weight: 800; 
                    color: var(--baznas-dark); 
                    letter-spacing: 1px;
                }

                .btn-copy {
                    background: #f4f8f4;
                    border: 1px solid #e2efe3;
                    color: var(--baznas-green);
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
                    background: var(--baznas-green);
                    color: #fff;
                    border-color: var(--baznas-green);
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

                .qris-section { 
                    background: #fff; 
                    border-radius: 20px; 
                    padding: 40px; 
                    text-align: center;
                    margin-bottom: 50px;
                    box-shadow: 0 15px 50px rgba(0,0,0,0.05);
                    border: 2px dashed #e0e0e0;
                }
                
                .qris-image-wrap {
                    display: inline-block;
                    padding: 15px;
                    background: #fff;
                    border-radius: 15px;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                    margin-bottom: 20px;
                }
                
                .qris-image-wrap img { 
                    max-width: 280px; 
                    height: auto;
                    display: block;
                }
                
                .qris-title { 
                    font-size: 24px; 
                    font-weight: 800; 
                    color: var(--baznas-green); 
                    margin-bottom: 10px; 
                }
                
                .wa-card {
                    background: var(--baznas-green);
                    border-radius: 20px;
                    padding: 40px;
                    text-align: center;
                    color: #fff;
                    box-shadow: 0 15px 35px rgba(0, 105, 55, 0.3);
                }
                
                .wa-button { 
                    display: inline-flex; 
                    align-items: center; 
                    background: #25D366; 
                    color: white; 
                    padding: 18px 35px; 
                    font-size: 18px; 
                    font-weight: 800;
                    border-radius: 50px; 
                    text-decoration: none; 
                    transition: all 0.3s;
                    margin-top: 20px;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                }
                
                .wa-button:hover { 
                    background: #fff; 
                    color: #25D366 !important; 
                    transform: translateY(-5px); 
                }

                @media (max-width: 768px) {
                    .zakat-header h3 { font-size: 26px; }
                    .qris-section { padding: 30px 15px; }
                    .bank-grid { grid-template-columns: 1fr; }
                }
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
            <div class="wa-button-container" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 30px;">
                <a href="<?php echo base_url('bayarzakat/konfirmasi'); ?>" class="wa-button" style="background: var(--baznas-green); margin-top: 0;">
                    <i class="fa fa-check-circle" style="font-size: 18px; margin-right: 8px;"></i> Konfirmasi ZIS Online (Web)
                </a>
                <a href="https://api.whatsapp.com/send?phone=<?php echo $telp; ?>&text=Assalamu'alaikum%20BAZNAS,%20saya%20ingin%20mengkonfirmasi%20pembayaran%20Zakat/Infaq%20saya" target="_blank" class="wa-button" style="background: #25D366; margin-top: 0;">
                    <i class="fa fa-whatsapp wa-icon" style="font-size: 18px; margin-right: 8px;"></i> Konfirmasi ZIS via WhatsApp
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
    element.style.background = 'var(--baznas-green)';
    element.style.color = '#fff';
    element.style.borderColor = 'var(--baznas-green)';
    
    setTimeout(function() {
        element.innerHTML = originalHTML;
        element.style.background = '';
        element.style.color = '';
        element.style.borderColor = '';
    }, 2000);
}
</script>
