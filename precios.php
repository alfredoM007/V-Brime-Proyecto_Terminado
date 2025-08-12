<?php
require 'conexion.php';
try {
  $stmt = $pdo->query("SELECT id, nombre, precio, imagen FROM productos ORDER BY creado_en DESC");
  $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $productos = [];
}
?>
<section class="content price">
  <article class="contain">
    <h2 class="title">Consultar Precios</h2>
    <p>Explora nuestra lista de productos disponibles.</p>

    <div style="display:flex;justify-content:center;gap:20px;flex-wrap:wrap;margin-top:40px;">

      <?php if (!$productos): ?>
        <p style="color:#f8f8d9;">No hay productos por ahora.</p>
      <?php else: foreach ($productos as $p): ?>
        <?php

          $nombre = $p['nombre'];

          $precio = number_format($p['precio']);
        ?>
        <a href="#modulo-venta" class="product-card" data-producto="<?= $nombre ?>" onclick="seleccionarProducto(this)"
           style="text-decoration:none;color:inherit;width:220px;background:#7d2181;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.2);text-align:center;">
          <div style="width:100%;height:200px;background:#fff;">
            <img src="<?= $p['imagen'] ?>" alt="<?= $nombre ?>" style="width:100%;height:100%;object-fit:contain;">
          </div>
          <div style="padding:15px;">
            <h4 style="color:#000;"><?= $nombre ?></h4>
            <strong style="color:#f8f8d9;">$<?= $precio ?> MXN</strong>
        </a>
      <?php endforeach; endif; ?>
    </div>
  </article>
</section>

