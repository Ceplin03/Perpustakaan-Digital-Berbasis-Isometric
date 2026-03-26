<?php
$data = json_decode(file_get_contents('php://input'), true);
$inv = $data['invoice'];

$invoiceCode = htmlspecialchars($inv['code']);
$verifyUrl = "http://localhost/perpustakaan/public/invoice.php?code=" . urlencode($invoiceCode);
?>

<style>
/* ================= RESET MODAL ================= */
.modal-overlay.active #modalTitle,
.modal-overlay.active .close-trigger {
    display: none !important;
}

.modal-overlay.active .modal-content {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    max-height: 100vh !important;
    overflow-y: auto !important;
}

/* ================= SCREEN LAYOUT ================= */
.invoice-container {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 24px 12px;
}

.invoice-card {
    background: #0f172a;
    border-radius: 24px;
    padding: 28px;
    width: 100%;
    max-width: 420px;
    color: #f8fafc;
    font-family: Inter, system-ui, sans-serif;
    box-shadow: 0 40px 80px -20px rgba(0,0,0,.7);
}

/* ================= HEADER ================= */
.invoice-header {
    text-align: center;
    margin-bottom: 18px;
}

.invoice-header small {
    font-size: 9px;
    letter-spacing: 2px;
    color: #60a5fa;
    text-transform: uppercase;
}

.invoice-header h2 {
    margin: 6px 0 0;
    font-size: 20px;
    font-weight: 800;
}

/* ================= QR ================= */
.qr-box {
    text-align: center;
    background: rgba(255,255,255,.03);
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 20px;
}

.qr-box img {
    background: #fff;
    padding: 8px;
    border-radius: 10px;
    width: 120px;
}

.qr-box code {
    display: block;
    margin-top: 10px;
    font-size: 12px;
    font-weight: 700;
    color: #3b82f6;
}

/* ================= DATA ================= */
.data-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 16px;
}

.data-full {
    grid-column: span 2;
    border-bottom: 1px solid rgba(255,255,255,.1);
    padding-bottom: 10px;
}

.label {
    font-size: 9px;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
}

.value {
    font-size: 13px;
    font-weight: 600;
    margin-top: 4px;
}

/* ================= STATUS ================= */
.status-box {
    text-align: center;
    background: rgba(34,197,94,.1);
    border: 1px solid rgba(34,197,94,.25);
    border-radius: 14px;
    padding: 10px;
    margin-bottom: 16px;
    font-size: 12px;
    font-weight: 800;
    color: #4ade80;
}

/* ================= BUTTON ================= */
.no-print {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.no-print button {
    padding: 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    border: none;
}

.print-btn { background: #3b82f6; color: #fff; }
.close-btn { background: #1e293b; color: #cbd5f5; }

/* ================= PRINT (CENTER FIX) ================= */
@media print {
    @page {
        size: A4;
        margin: 0;
    }

    body {
        background: white !important;
    }

    header, footer, nav, .no-print {
        display: none !important;
    }

    /* 🔥 KUNCI UTAMA */
    .invoice-container {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        min-height: 100vh !important;
        padding: 0 !important;
    }

    .invoice-card {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        width: 100% !important;
        max-width: 480px !important;
        page-break-inside: avoid;
    }

    .invoice-card * {
        color: black !important;
    }

    .qr-box {
        background: white !important;
        border: 1px solid #e5e7eb !important;
    }
}
</style>

<div class="invoice-container">
    <div class="invoice-card">

        <div class="invoice-header">
            <small>Official Digital Receipt</small>
            <h2>INVOICE PEMINJAMAN</h2>
        </div>

        <div class="qr-box">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode($verifyUrl) ?>">
            <code><?= $invoiceCode ?></code>
            <small>Verifikasi via QR Code</small>
        </div>

        <div class="data-grid">
            <div class="data-full">
                <div class="label">Judul Buku</div>
                <div class="value"><?= htmlspecialchars($inv['judul']) ?></div>
            </div>

            <div>
                <div class="label">Nama Siswa</div>
                <div class="value"><?= htmlspecialchars($inv['nama']) ?></div>
            </div>

            <div>
                <div class="label">NIS / ID</div>
                <div class="value"><?= htmlspecialchars($inv['user_id']) ?></div>
            </div>

            <div>
                <div class="label">Tanggal Pinjam</div>
                <div class="value"><?= $inv['borrow_date'] ?></div>
            </div>

            <div>
                <div class="label">Jatuh Tempo</div>
                <div class="value"><?= $inv['due_date'] ?></div>
            </div>
        </div>

        <div class="status-box">
            STATUS: Buku harus diambil sebelum 24 jam.
        </div>

        <div class="no-print">
            <button class="print-btn" onclick="window.print()">Cetak PDF</button>
            <button class="close-btn" onclick="closeModal()">Tutup</button>
        </div>

        <div style="text-align:center;font-size:9px;color:#64748b;">
            © 2026 Smart Library System
        </div>

    </div>
</div>
