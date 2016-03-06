<?php
/**
 * Template Name: Produtos
 */
?>

<?php get_header(); ?>

<h2 class="sub-header">Produtos</h2> 

<?php
    $query = new WP_Query(array(
        'post_type' => 'produtos',
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
          <th>Nome</th>
          <th>Preço</th>
          <th>Descrição</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($query->have_posts()) : 
          $query->the_post(); ?>
          <tr>
            <td><?php the_ID(); ?></td>
            <td><?php the_title(); ?></td>
            <td><?= get_post_meta(get_the_ID(), '_produto_preco', true); ?></td>
            <td><?= get_post_meta(get_the_ID(), '_produto_descricao', true); ?></td>
          </tr>
        <?php endwhile;
          wp_reset_query(); ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php get_footer(); ?>