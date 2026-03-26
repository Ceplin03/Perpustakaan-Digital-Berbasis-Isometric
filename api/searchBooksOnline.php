<?php
$q = urlencode($_GET['q'] ?? '');

if (!$q) {
    echo json_encode([]);
    exit;
}

$url = "https://openlibrary.org/search.json?q={$q}&limit=10";

$context = stream_context_create([
    "http" => [
        "timeout" => 5
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo json_encode([]);
    exit;
}

$data = json_decode($response, true);
$result = [];

foreach ($data['docs'] as $b) {
    $result[] = [
        'title' => $b['title'] ?? '-',
        'author' => $b['author_name'][0] ?? 'Unknown',
        'year' => $b['first_publish_year'] ?? '-',

        // 📌 OPSIONAL (AMAN)
        'category' => $b['subject'][0] ?? '',
        'description' =>
            is_array($b['first_sentence'] ?? null)
                ? ($b['first_sentence'][0] ?? '')
                : ($b['first_sentence'] ?? ''),

        'cover' => isset($b['cover_i'])
            ? "https://covers.openlibrary.org/b/id/{$b['cover_i']}-M.jpg"
            : null
    ];
}

echo json_encode($result);
exit;
