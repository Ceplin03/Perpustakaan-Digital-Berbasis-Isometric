<?php
require_once '../app/config/database.php';

$rack = $_GET['rack'] ?? '';
$db = (new Database())->connect();

/* =====================================
   BUKU PILIHAN
   (PALING SERING DIPINJAM)
   ===================================== */
if ($rack === 'REKOMENDASI') {

    $stmt = $db->query("
        SELECT 
            b.id,
            b.judul,
            b.kategori,
            b.cover,
            COUNT(br.id) AS total_pinjam
        FROM books b
        JOIN borrows br ON br.book_id = b.id
        GROUP BY b.id
        ORDER BY total_pinjam DESC
        LIMIT 12
    ");

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$books) {
        echo "<p style='color:#aaa'>Belum ada data peminjaman.</p>";
        exit;
    }

    echo "<div class='book-grid'>";
    foreach ($books as $book) {

        $cover = !empty($book['cover'])
            ? $book['cover']
            : 'https://via.placeholder.com/200x300?text=No+Cover';

        echo "
        <div class='book-card' onclick='openBookDetail({$book['id']})'>
            <div class='book-cover-wrapper'>
                <img src='{$cover}' alt='{$book['judul']}'>
            </div>
            <div class='book-info'>
                <h4>{$book['judul']}</h4>
                <p>{$book['total_pinjam']}× dipinjam</p>
            </div>
        </div>
        ";
    }
    echo "</div>";
    exit;
}

/* =====================================
   PEMBACA TERBANYAK
   ===================================== */
if ($rack === 'Top-Readers') {

    $stmt = $db->query("
        SELECT 
            u.nama,
            COUNT(br.id) AS total_pinjam
        FROM users u
        JOIN borrows br ON br.user_id = u.id
        GROUP BY u.id
        ORDER BY total_pinjam DESC
        LIMIT 5
    ");

    $readers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$readers) {
        echo "<p style='color:var(--text-dim); text-align:center; padding:20px;'>Belum ada aktivitas membaca.</p>";
        exit;
    }

    // Ambil total tertinggi untuk kalkulasi lebar progress bar
    $max_pinjam = $readers[0]['total_pinjam'] ?: 1;

    echo "<div style='display: flex; flex-direction: column; gap: 16px;'>";
    
    foreach ($readers as $i => $r) {
        $rank = $i + 1;
        
        // Logika Warna & Badge
        $badgeBg = 'rgba(255,255,255,0.05)';
        $badgeColor = '#94a3b8';
        $borderStyle = '1px solid rgba(255,255,255,0.08)';

        if ($rank === 1) {
            $badgeBg = 'linear-gradient(135deg, #ffd700, #b8860b)';
            $badgeColor = '#000';
            $borderStyle = '1px solid rgba(255,215,0,0.3)';
        } elseif ($rank === 2) {
            $badgeBg = 'linear-gradient(135deg, #e2e8f0, #94a3b8)';
            $badgeColor = '#000';
        } elseif ($rank === 3) {
            $badgeBg = 'linear-gradient(135deg, #cd7f32, #8b4513)';
            $badgeColor = '#fff';
        }

        // Kalkulasi Progress Bar (%)
        $percent = ($r['total_pinjam'] / $max_pinjam) * 100;

        echo "
        <div style='background: rgba(255,255,255,0.02); border: {$borderStyle}; border-radius: 12px; padding: 12px 16px; transition: transform 0.2s;'>
            <div style='display: flex; align-items: center; gap: 15px;'>
                
                <div style='width: 32px; height: 32px; background: {$badgeBg}; color: {$badgeColor}; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;'>
                    {$rank}
                </div>

                <div style='flex-grow: 1;'>
                    <div style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;'>
                        <span style='font-weight: 600; font-size: 0.9rem; color: #f8fafc;'>".htmlspecialchars($r['nama'])."</span>
                        <span style='font-family: var(--font-mono); font-size: 0.8rem; color: var(--primary); font-weight: 700;'>{$r['total_pinjam']} <span style='font-size: 0.65rem; color: var(--text-dim);'>BUKU</span></span>
                    </div>
                    
                    <div style='width: 100%; height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;'>
                        <div style='width: {$percent}%; height: 100%; background: var(--primary); box-shadow: 0 0 8px var(--primary-glow); border-radius: 10px;'></div>
                    </div>
                </div>

            </div>
        </div>
        ";
    }
    
    echo "</div>";
    exit;
}

/* =====================================
   RAK FISIK (A, B, C, DLL)
   ===================================== */

$stmt = $db->prepare("SELECT * FROM books WHERE rack_code = ?");
$stmt->execute([$rack]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$books) {
    echo "<p style='color:#aaa'>Tidak ada buku di rak ini.</p>";
    exit;
}

echo "<div class='book-grid'>";
foreach ($books as $book) {

    $cover = !empty($book['cover'])
        ? $book['cover']
        : 'https://via.placeholder.com/200x300?text=No+Cover';

    echo "
    <div class='book-card' onclick='openBookDetail({$book['id']})'>
        <div class='book-cover-wrapper'>
            <img src='{$cover}' alt='{$book['judul']}'>
        </div>
        <div class='book-info'>
            <h4>{$book['judul']}</h4>
            <p>{$book['kategori']}</p>
        </div>
    </div>
    ";
}
echo "</div>";
