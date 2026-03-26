<?php
require_once '../../app/core/Auth.php';
if (!Auth::check() || Auth::user()['role'] !== 'admin') {
  header("Location: /perpustakaan/public/login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Verifikasi Invoice</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body{background:#0b1220;color:#e5e7eb;font-family:Inter;padding:40px}
.card{background:#111827;padding:24px;border-radius:12px;max-width:480px}
input,button{width:100%;padding:12px;margin-top:12px}
button{background:#3b82f6;border:none;color:#fff;font-weight:600;cursor:pointer}
</style>
</head>
<body>

<h2>Verifikasi Invoice</h2>

<div class="card">
  <input type="text" id="code" placeholder="Masukkan Kode Invoice">
  <button onclick="checkInvoice()">CEK INVOICE</button>
</div>

<script>
function checkInvoice(){
  const code = document.getElementById('code').value;
  fetch(`/perpustakaan/api/getInvoiceByCode.php?code=${code}`)
  .then(r=>r.json())
  .then(res=>{
    if(res.status!=='success'){
      Swal.fire('Gagal',res.message,'error');
      return;
    }

    const d = res.data;
    Swal.fire({
  title: 'Konfirmasi Transaksi',
  html: `
    <b>${d.judul}</b><br>
    Nama: ${d.nama}<br>
    ID: ${d.user_id}<br>
    Jatuh Tempo: ${d.due_date}<br>
    Status: ${d.status}
  `,
  icon: 'question',
  showCancelButton: true,
  showDenyButton: true,               // ⬅️ DI SINI
  confirmButtonText: 'Serahkan Buku',
  denyButtonText: 'Kembalikan Buku',   // ⬅️ DI SINI
  cancelButtonText: 'Batal'
}).then((r) => {

  // ✅ SERAHKAN BUKU
  if (r.isConfirmed) {
    confirmBorrow(d.borrow_id, d.book_id);
  }

  // 🔁 KEMBALIKAN BUKU
  if (r.isDenied) {                   // ⬅️ DI SINI
    returnBook(d.borrow_id, d.book_id);
  }

});

  });
}

function confirmBorrow(borrowId,bookId){
  fetch('/perpustakaan/api/confirmBorrow.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`borrow_id=${borrowId}&book_id=${bookId}`
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.status==='success'){
      Swal.fire('Sukses','Buku diserahkan ke siswa','success');
    } else {
      Swal.fire('Gagal',res.message,'error');
    }
  });
}
</script>

<script>
    function returnBook(borrowId, bookId){
  fetch('/perpustakaan/api/returnBook.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`borrow_id=${borrowId}&book_id=${bookId}`
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.status==='success'){
      const msg = res.late_days > 0
        ? `Terlambat ${res.late_days} hari`
        : 'Dikembalikan tepat waktu';

      Swal.fire('Pengembalian Sukses', msg, 'success');
    } else {
      Swal.fire('Gagal', res.message, 'error');
    }
  });
}

</script>
</body>
</html>
