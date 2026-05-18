<div class="container my-5 py-4">
        <div class="row align-items-center p-4 p-md-5 shadow-sm rounded-4 text-white" style="background: linear-gradient(135deg, #1B4938 0%, #25664E 100%);">
            <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge mb-3 px-3 py-2 text-darkfw-bold" style="background-color: #F2CC8F; color: #1B4938;">#AksiNyataLiterasi</span>
                <h3 class="fw-bold mb-2">Dukung Penyebaran Virus Literasi Bersama Pancaloka</h3>
                <p class="mb-0 opacity-75 small lh-lg">Setiap kontribusi yang Anda berikan akan dialokasikan sepenuhnya untuk pengadaan kitab/buku bacaan baru, perawatan fasilitas pustaka bergerak, serta pembiayaan operasional ruang diskusi inklusif bagi masyarakat desa.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <button class="btn btn-lg px-4 py-2 fw-bold text-white shadow-sm border-2" data-bs-toggle="modal" data-bs-target="#donationModal" style="background-color: #E07A5F; border-color: #E07A5F; border-radius: 30px;">
                    <i class="bi bi-heart-fill me-2"></i> Donasi Sekarang
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; background-color: #FAF6F0;">
                <div class="modal-header border-0 text-white p-4" style="background-color: #1B4938; border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold" id="donationModalLabel"><i class="bi bi-wallet2 me-2 text-warning"></i> Saluran Donasi Pancaloka</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-dark">
                    <p class="text-muted small text-center mb-4">Silakan pilih salah satu metode transfer resmi di bawah ini. Terima kasih atas kebaikan nalar dan hati Anda.</p>
                    
                    <div class="p-3 bg-white rounded-3 mb-3 shadow-sm border-start border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-bold uppercase" style="font-size: 0.7rem;">BANK SYARIAH INDONESIA (BSI)</small>
                                <strong class="fs-5 text-success" id="norek-bsi">7123456789</strong>
                                <small class="d-block text-secondary">a.n Komunitas Pancaloka Kolektif</small>
                            </div>
                            <button class="btn btn-sm btn-light border text-primary fw-bold" onclick="navigator.clipboard.writeText('7123456789'); alert('Nomor rekening BSI berhasil disalin!');">Salin</button>
                        </div>
                    </div>

                    <div class="p-3 bg-white rounded-3 mb-4 shadow-sm border-start border-4 border-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-bold uppercase" style="font-size: 0.7rem;">BANK MANDIRI</small>
                                <strong class="fs-5 text-primary" id="norek-mandiri">1300012345678</strong>
                                <small class="d-block text-secondary">a.n Pancaloka Literasi</small>
                            </div>
                            <button class="btn btn-sm btn-light border text-primary fw-bold" onclick="navigator.clipboard.writeText('1300012345678'); alert('Nomor rekening Mandiri berhasil disalin!');">Salin</button>
                        </div>
                    </div>

                    <div class="text-center p-3 bg-white rounded-3 shadow-sm border">
                        <small class="text-muted d-block fw-bold mb-2" style="font-size: 0.7rem;">QRIS ALL E-WALLET (GOPAY, OVO, DANA, LINKAJA)</small>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=PancalokaDonationMockup" class="img-fluid border p-2 bg-light mb-2" alt="QRIS Pancaloka" style="max-height: 180px;">
                        <small class="d-block text-muted fst-italic">Pindai kode QR di atas langsung melalui aplikasi keuangan Anda</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 justify-content-center">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>