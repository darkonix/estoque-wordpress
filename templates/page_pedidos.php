<?php
/**
 * Template Name: Pedidos
 */
?>

<?php get_header(); ?>

<h2 class="sub-header">Pedidos</h2>

<?php
    $query = new WP_Query(array(
        'post_type' => 'pedidos',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));
?>

<?php if ($query->have_posts()) : ?>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Produto</th>
          <th>Cliente</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($query->have_posts()) : 
          $query->the_post(); ?>
          <tr>
            <td><?php the_ID(); ?></td>
            <td><?= get_the_title(get_post_meta(get_the_ID(), '_pedido_id_produto', true)); ?></td>
            <td><?= get_the_title(get_post_meta(get_the_ID(), '_pedido_id_cliente', true)); ?></td>
          </tr>
        <?php endwhile;
          wp_reset_query(); ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php get_footer(); ?>