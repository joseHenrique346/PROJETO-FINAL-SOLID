<?php
$service = require __DIR__ . '/bootstrap.php';


$action = $_GET['action'] ?? 'entry';
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($action === 'entry') {
            $plate = $_POST['plate'] ?? '';
            $type = $_POST['type'] ?? 'carro';
            $session = $service->registerEntry($plate, \App\Domain\VehicleType::from($type));
            $message = 'Entrada registrada: ' . $session->plate . ' (' . $session->vehicleType->label() . ')';
        } elseif ($action === 'exit') {
            $plate = $_POST['plate'] ?? '';
            $session = $service->registerExit($plate);
            $message = 'Saída registrada: ' . $session->plate . ' | Horas: ' . $session->totalHours . ' | Valor: R$ ' . number_format($session->amount ?? 0, 2, ',', '.');
        }
    } catch (\Throwable $e) {
        $error = $e->getMessage();
    }
}

if ($action === 'report') {
    $report = $service->report();
}

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Estacionamento Inteligente</title>
    <style>
        :root { --primary:#0d6efd; --border:#e5e7eb; --bg:#f8fafc; --text:#111827; }
        * { box-sizing:border-box; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; max-width: 900px; margin: 2rem auto; color: var(--text); background: var(--bg); }
        header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
        h1 { font-size:1.8rem; margin:0; }
        nav { display:flex; gap:.75rem; }
        nav a { display:inline-block; padding:.5rem .75rem; border:1px solid var(--border); border-radius:8px; text-decoration:none; color:var(--text); background:#fff; }
        nav a.active { border-color:var(--primary); color:#fff; background:var(--primary); }
        .container { background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem; }
        form { display:grid; gap:.75rem; margin:0; }
        label { display:flex; flex-direction:column; gap:.25rem; font-size:.95rem; }
        input, select { padding:.5rem .6rem; border:1px solid var(--border); border-radius:8px; font-size:1rem; }
        button { padding:.6rem .9rem; border:none; border-radius:8px; background:var(--primary); color:#fff; font-weight:600; cursor:pointer; }
        .msg { color: #16a34a; margin:.75rem 0; }
        .err { color: #dc2626; margin:.75rem 0; }
        table { border-collapse: collapse; width: 100%; margin-top:.5rem; }
        th, td { border: 1px solid var(--border); padding: 0.5rem; text-align: left; }
    </style>
</head>
<body>
<header>
  <h1>Estacionamento Inteligente</h1>
  <nav>
    <a href="?action=entry" class="<?= $action==='entry'?'active':'' ?>">Registrar Entrada</a>
    <a href="?action=exit" class="<?= $action==='exit'?'active':'' ?>">Registrar Saída</a>
    <a href="?action=report" class="<?= $action==='report'?'active':'' ?>">Relatório</a>
  </nav>
</header>
<?php if ($message): ?><p class="mensage"><?= ($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="erro"><?= ($error) ?></p><?php endif; ?>

<?php if ($action === 'entry'): ?>
    <div class="container">
    <form method="post">
        <label>Placa: <input name="plate" required></label>
        <label>Tipo:
            <select name="type">
                <option value="carro">Carro</option>
                <option value="moto">Moto</option>
                <option value="caminhao">Caminhão</option>
            </select>
        </label>
        <button type="submit">Registrar Entrada</button>
    </form>
    </div>
<?php elseif ($action === 'exit'): ?>
    <div class="container">
    <form method="post">
        <label>Placa: <input name="plate" required></label>
        <button type="submit">Registrar Saída</button>
    </form>
    </div>
<?php elseif ($action === 'report'): ?>
    <h2>Relatório</h2>
    <div class="container">
    <table>
        <thead><tr><th>Tipo</th><th>Total Veículos</th><th>Horas</th><th>Faturamento</th></tr></thead>
        <tbody>
        <?php foreach ($report as $type => $data): ?>
            <tr>
                <td><?= (ucfirst($type)) ?></td>
                <td><?= ((string)$data['count']) ?></td>
                <td><?= ((string)($data['hours'] ?? 0)) ?></td>
                <td>R$ <?= (number_format($data['amount'], 2, ',', '.')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php endif; ?>
</body>
</html>