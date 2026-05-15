<?php
declare(strict_types=1);

$dsn      = 'mysql:host=mysql;port=3306;dbname=test;charset=utf8mb4';
$username = 'user';
$password = 'pass';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<pre>Connection failed: ' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}

// テーブル一覧を取得
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

$selectedTable = $_GET['table'] ?? ($tables[0] ?? '');
$page          = max(1, (int)($_GET['page'] ?? 1));
$perPage       = 100;
$offset        = ($page - 1) * $perPage;

// テーブル名をホワイトリストで検証
if (!in_array($selectedTable, $tables, true)) {
    $selectedTable = $tables[0] ?? '';
}

$total = 0;
$rows  = [];
$columns = [];

if ($selectedTable !== '') {
    $quotedTable = '`' . $selectedTable . '`';
    $total   = (int)$pdo->query("SELECT COUNT(*) FROM {$quotedTable}")->fetchColumn();
    $stmt    = $pdo->prepare("SELECT * FROM {$quotedTable} LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $rows    = $stmt->fetchAll();
    $columns = $rows ? array_keys($rows[0]) : [];
}

$totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;

function h(mixed $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>DB Dump — test</title>
<style>
  body { font-family: monospace; font-size: 13px; margin: 16px; background: #1e1e1e; color: #d4d4d4; }
  h1 { font-size: 16px; color: #9cdcfe; }
  nav { margin-bottom: 12px; }
  nav a { color: #4ec9b0; margin-right: 8px; text-decoration: none; }
  nav a.active { color: #dcdcaa; font-weight: bold; }
  .meta { color: #6a9955; margin-bottom: 8px; }
  table { border-collapse: collapse; width: 100%; }
  th { background: #252526; color: #9cdcfe; padding: 4px 8px; border: 1px solid #3c3c3c; text-align: left; }
  td { padding: 3px 8px; border: 1px solid #3c3c3c; white-space: nowrap; max-width: 300px; overflow: hidden; text-overflow: ellipsis; }
  tr:nth-child(even) { background: #252526; }
  .pagination { margin-top: 12px; }
  .pagination a { color: #569cd6; margin-right: 6px; }
  .pagination span { color: #6a9955; margin-right: 6px; }
</style>
</head>
<body>
<h1>DB Dump — test</h1>

<nav>
<?php foreach ($tables as $t): ?>
  <a href="?table=<?= h(urlencode($t)) ?>" class="<?= $t === $selectedTable ? 'active' : '' ?>"><?= h($t) ?></a>
<?php endforeach; ?>
</nav>

<?php if ($selectedTable !== ''): ?>
<p class="meta">
  テーブル: <strong><?= h($selectedTable) ?></strong>
  &nbsp;|&nbsp; 総件数: <?= number_format($total) ?>
  &nbsp;|&nbsp; ページ: <?= $page ?> / <?= $totalPages ?>
  &nbsp;|&nbsp; 表示: <?= number_format($offset + 1) ?>–<?= number_format(min($offset + $perPage, $total)) ?>
</p>

<?php if ($rows): ?>
<table>
  <thead>
    <tr><?php foreach ($columns as $col): ?><th><?= h($col) ?></th><?php endforeach; ?></tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $row): ?>
    <tr><?php foreach ($row as $val): ?><td title="<?= h($val) ?>"><?= h($val) ?></td><?php endforeach; ?></tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?table=<?= h(urlencode($selectedTable)) ?>&page=<?= $page - 1 ?>">« 前</a>
  <?php endif; ?>
  <?php
  $start = max(1, $page - 2);
  $end   = min($totalPages, $page + 2);
  for ($i = $start; $i <= $end; $i++):
  ?>
    <?php if ($i === $page): ?>
      <span>[<?= $i ?>]</span>
    <?php else: ?>
      <a href="?table=<?= h(urlencode($selectedTable)) ?>&page=<?= $i ?>"><?= $i ?></a>
    <?php endif; ?>
  <?php endfor; ?>
  <?php if ($page < $totalPages): ?>
    <a href="?table=<?= h(urlencode($selectedTable)) ?>&page=<?= $page + 1 ?>">次 »</a>
  <?php endif; ?>
</div>
<?php else: ?>
<p class="meta">データなし</p>
<?php endif; ?>
<?php endif; ?>
</body>
</html>
