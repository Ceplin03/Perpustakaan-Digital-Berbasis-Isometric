<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #05070a; /* Sesuai tema dashboard Anda */
        }
        /* Custom styling agar SweetAlert menggunakan Poppins */
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
            border-radius: 20px !important;
            background: #161b22 !important;
            color: white !important;
        }
    </style>
</head>
<body>

<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Keluar',
        text: 'Anda telah keluar dari sistem. Sampai jumpa kembali!',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        background: '#161b22',
        color: '#fff',
        confirmButtonColor: '#3b82f6'
    }).then(() => {
        // Alihkan ke halaman login setelah SweetAlert selesai
        window.location.href = "/perpustakaan/public/login.php";
    });
</script>

</body>
</html>