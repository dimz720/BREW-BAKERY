<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth-check.php';

checkCustomerAuth();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$per_page = 6;
$offset = ($page - 1) * $per_page;

// Build query
$where = "WHERE 1=1";
$params = [];
$types = "";

if ($search) {
    $where .= " AND (a.judul LIKE ? OR a.isi LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

// Get total articles
$count_query = "SELECT COUNT(*) as total FROM articles a $where";
if ($params) {
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
} else {
    $total = $conn->query($count_query)->fetch_assoc()['total'];
}

$total_pages = ceil($total / $per_page);

// Get articles - FIX: LIMIT OFFSET urutan benar
$article_query = "SELECT a.*, ad.nama FROM articles a 
                  JOIN admins ad ON a.created_by = ad.id
                  $where 
                  ORDER BY a.created_at DESC 
                  LIMIT ? OFFSET ?";

// Add LIMIT dan OFFSET ke params (urutan benar)
$article_params = $params;
$article_params[] = $per_page;
$article_params[] = $offset;
$article_types = $types . "ii";

$stmt = $conn->prepare($article_query);
if ($article_params) {
    $stmt->bind_param($article_types, ...$article_params);
}
$stmt->execute();
$articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Blog - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .articles-layout {
            min-height: calc(100vh - 70px);
            background-color: var(--light);
            padding: 2rem 0;
        }
        .articles-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .articles-header {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .articles-header h1 {
            margin-bottom: 1rem;
            color: var(--primary);
        }
        .articles-header p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        .search-form {
            display: flex;
            gap: 1rem;
        }
        .search-form input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.3rem;
        }
        .search-form button {
            padding: 0.75rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.3rem;
            cursor: pointer;
            font-weight: 600;
        }
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .article-card {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .article-image {
            width: 100%;
            height: 200px;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .article-content {
            padding: 1.5rem;
        }
        .article-category {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .article-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            min-height: 3em;
        }
        .article-excerpt {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 1rem;
        }
        .article-author {
            color: #666;
            font-weight: 500;
        }
        .article-date {
            color: #999;
        }
        .btn-read {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 0.3rem;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .btn-read:hover {
            background-color: var(--dark);
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.3rem;
            text-decoration: none;
            color: var(--primary);
        }
        .pagination a:hover {
            background-color: var(--primary);
            color: white;
        }
        .pagination .active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    
    <div class="articles-layout">
        <div class="articles-container">
            <div class="articles-header">
                <h1>📰 Artikel & Blog</h1>
                <p>Baca artikel menarik tentang roti, tips & trik, dan berita terbaru dari Brew Bakery</p>
                
                <form method="GET" action="" class="search-form">
                    <input type="text" name="search" placeholder="Cari artikel..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Cari</button>
                </form>
            </div>

            <?php if (count($articles) > 0): ?>
                <div class="articles-grid">
                    <?php foreach ($articles as $article): ?>
                    <div class="article-card">
                        <div class="article-image">
                            <?php if ($article['foto']): ?>
                                <img src="<?php echo ARTICLE_IMG_URL  . $article['foto']; ?>" alt="<?php echo htmlspecialchars($article['judul']); ?>">
                            <?php else: ?>
                                <span style="font-size: 3rem;">📝</span>
                            <?php endif; ?>
                        </div>
                        <div class="article-content">
                            <span class="article-category">📝 Artikel</span>
                            <h3 class="article-title"><?php echo htmlspecialchars($article['judul']); ?></h3>
                            <div class="article-excerpt"><?php echo htmlspecialchars(substr(strip_tags($article['isi']), 0, 150)); ?>...</div>
                            <div class="article-meta">
                                <span class="article-author">Oleh: <?php echo htmlspecialchars($article['nama']); ?></span>
                                <span class="article-date"><?php echo formatDate($article['created_at']); ?></span>
                            </div>
                            <a href="<?php echo CUSTOMER_URL; ?>article-detail.php?id=<?php echo $article['id']; ?>" class="btn-read">Baca Selengkapnya →</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=1<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">« Pertama</a>
                        <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">‹ Sebelumnya</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Selanjutnya ›</a>
                        <a href="?page=<?php echo $total_pages; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Terakhir »</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-state">
                    <p style="font-size: 3rem; margin-bottom: 1rem;">📝</p>
                    <p>Tidak ada artikel yang ditemukan.</p>
                    <?php if ($search): ?>
                        <p><a href="<?php echo CUSTOMER_URL; ?>articles.php">Lihat semua artikel</a></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
