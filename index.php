<?php
$produtos = [
    ["id" => 1, "nome" => "Teclado Mecânico", "preco" => 250],
    ["id" => 2, "nome" => "Mouse Gamer", "preco" => 150],
    ["id" => 3, "nome" => "Monitor Full HD", "preco" => 900],
    ["id" => 4, "nome" => "Headset com Microfone", "preco" => 300],
];
if (isset($_GET['adicionar'])) {
    $id = (int) $_GET['adicionar'];
    $produto = array_filter($produtos, fn($p) => $p['id'] === $id);
    if (!empty($produto)) {
        $produto = array_values($produto)[0];
        file_put_contents("cart.txt", $produto['nome'] . "," . $produto['preco'] . "\n", FILE_APPEND);
    }
    header("Location: index.php");
    exit;
}
if (isset($_GET['remover'])) {
    $indexToRemove = (int) $_GET['remover'];
    if (file_exists("cart.txt")) {
        $itensCarrinho = file("cart.txt", FILE_IGNORE_NEW_LINES);
        if (isset($itensCarrinho[$indexToRemove])) {
            unset($itensCarrinho[$indexToRemove]);
            file_put_contents("cart.txt", implode("\n", $itensCarrinho) . "\n");
        }
    }
    header("Location: index.php");
    exit;
}
$itensCarrinho = [];
if (file_exists("cart.txt")) {
    $itensCarrinho = file("cart.txt", FILE_IGNORE_NEW_LINES);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Loja Simples</h1>
        <h2>Produtos</h2>
        <?php foreach ($produtos as $produto): ?>
            <div class="produto">
                <span><?= htmlspecialchars($produto['nome']) ?> - R$
                    <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                <a href="?adicionar=<?= $produto['id'] ?>"><button>Adicionar ao Carrinho</button></a>
            </div>
        <?php endforeach; ?>
        <hr>
        <h2>Carrinho de Compras</h2>
        <?php if (!empty($itensCarrinho)): ?>
            <?php
            $total = 0;
            $temItensValidos = false;
            foreach ($itensCarrinho as $index => $item):
                if (!empty($item)) {
                    $partes = explode(",", $item);
                    if (count($partes) === 2) {
                        list($nome, $preco) = $partes;
                        $total += (float) $preco;
                        $temItensValidos = true;
                    }
                }
                ?>
                <?php if (isset($nome) && isset($preco)): ?>
                    <div class="item-carrinho">
                        <span><?= htmlspecialchars($nome) ?> - R$ <?= number_format((float) $preco, 2, ',', '.') ?></span>
                        <a href="?remover=<?= $index ?>"><button class="remover">Remover</button></a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($temItensValidos): ?>
                <div class="total">Total: R$ <?= number_format((float) $total, 2, ',', '.') ?></div>
            <?php else: ?>
                <p>Seu carrinho está vazio!</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Seu carrinho está vazio!</p>
        <?php endif; ?>
    </div>
</body>

</html>