<?php

    define('APP_PATH', dirname(__FILE__) . "/");

    require 'vendor/autoload.php';

    add_theme_support('nav-menus');

    function menus() {
		register_nav_menu('sidebar-menu', __('Menu Lateral'));
	}
	
	add_action('init', 'menus');


	function active_nav_class($classes, $item){
    	if(in_array('current-menu-item', $classes))
            $classes[] = 'active ';
	    
	    return $classes;
	}

	add_filter('nav_menu_css_class' , 'active_nav_class' , 10 , 2);

    // Pedidos
	function create_pedidos() {

		register_post_type( 'pedidos',
			array(
				'labels' => array(
					'name' => __( 'Pedidos' ),
					'singular_name' => __( 'Pedido' ),
				),
				'public' => true,
				'menu_icon' => 'dashicons-clipboard',
				'has_archive' => true,
				'rewrite' => array('slug' => 'pedidos'),
				'supports'            => array( 'title'),
				'menu_position'       => 5,
			)
		);
	}

	function pedidos_meta_boxes() {
    	add_meta_box( 'detalhes-pedidos', 'Detalhes do Pedido', 'pedidos_output_meta_box', 'pedidos', 'normal', 'high' );   
	}

	function pedidos_output_meta_box($post) {
	    $id_produto = get_post_meta( $post->ID, '_pedido_id_produto', true );
	    $id_cliente = get_post_meta( $post->ID, '_pedido_id_cliente', true );

	    wp_nonce_field( 'save_pedido', 'pedidos_nonce' );

    	echo '<table>';
    	echo '<tr>';
	    echo '<td><label for="pedido_id_produto">' . __('Produto') . ': </label></td>';
	    echo '<td><select name="pedido_id_produto" id="pedido_id_produto">';

		$query = new WP_Query(array(
		    'post_type' => 'produtos',
		    'post_status' => 'publish',
		    'posts_per_page' => -1,
		));

		while ($query->have_posts()) {
		    $query->the_post();
		    $post_id = get_the_ID();
		    $post_title = get_the_title();
		    $selected = ($post_id == $id_produto) ? ' selected="selected"' : '';
		    echo '<option value="'.$post_id.'"'.$selected.'>'.$post_title.'</option>';
		}

		wp_reset_query();

	    echo '</select></td>';
	    echo '</tr>';
    	echo '<tr>';
	    echo '<td><label for="pedido_id_cliente">' . __('Cliente') . ': </label></td>';
	    echo '<td><select name="pedido_id_cliente" id="pedido_id_cliente">';

		$query = new WP_Query(array(
		    'post_type' => 'clientes',
		    'post_status' => 'publish',
		    'posts_per_page' => -1,
		));

		while ($query->have_posts()) {
		    $query->the_post();
		    $post_id = get_the_ID();
		    $post_title = get_the_title();
		    $selected = ($post_id == $id_cliente) ? ' selected="selected"' : '';
		    echo '<option value="'.$post_id.'"'.$selected.'>'.$post_title.'</option>';
		}

		wp_reset_query();

	    echo '</select></td>';
	    echo '</tr>';
    	echo '</table>';	    
	}

	function pedidos_save_meta_boxes($post_id) {
	    if (!isset($_POST['pedidos_nonce']))
	        return $post_id;
	 
	    if (!wp_verify_nonce($_POST['pedidos_nonce'], 'save_pedido'))
	        return $post_id;
	 
	    if ('pedidos' != $_POST['post_type'])
	        return $post_id;
	 
	    if (!current_user_can('edit_post', $post_id))
	        return $post_id;
	 
	    $id_produto = sanitize_text_field($_POST['pedido_id_produto']);
	    $id_cliente = sanitize_text_field($_POST['pedido_id_cliente']);

	    update_post_meta($post_id, '_pedido_id_produto', $id_produto);
	    update_post_meta($post_id, '_pedido_id_cliente', $id_cliente);
	}

	add_action('init', 'create_pedidos');
	add_action('add_meta_boxes', 'pedidos_meta_boxes');
	add_action( 'save_post', 'pedidos_save_meta_boxes');

    // Produtos
	function create_produtos() {

		register_post_type( 'produtos',
			array(
				'labels' => array(
					'name' => __( 'Produtos' ),
					'singular_name' => __( 'Produto' ),
				),
				'public' => true,
				'menu_icon' => 'dashicons-cart',
				'has_archive' => true,
				'rewrite' => array('slug' => 'produtos'),
				'supports'            => array('title'),
				'menu_position'       => 5,
			)
		);
	}

	function produtos_meta_boxes() {
    	add_meta_box( 'detalhes-produtos', 'Detalhes do Produto', 'produtos_output_meta_box', 'produtos', 'normal', 'high' );   
	}

	function produtos_output_meta_box($post) {
	    $preco = get_post_meta( $post->ID, '_produto_preco', true );
	    $descricao = get_post_meta( $post->ID, '_produto_descricao', true );

	    wp_nonce_field( 'save_produto', 'produtos_nonce' );

    	echo '<table>';
    	echo '<tr>';
	    echo '<td><label for="produto_preco">' . __('Preço') . ': </label></td>';
	    echo '<td><input type="number" name="produto_preco" id="produto_preco" value="' . esc_attr($preco) . '" /></td>';
	    echo '</tr>';
    	echo '<tr>';
	    echo '<td><label for="produto_descricao">' . __('Descrição') . ': </label></td>';
	    echo '<td><textarea rows="4" cols="50" name="produto_descricao" id="produto_descricao">' . esc_attr($descricao) . '</textarea></td>';
	    echo '</tr>';
    	echo '</table>';	    
	}

	function produtos_save_meta_boxes($post_id) {
	    if (!isset($_POST['produtos_nonce']))
	        return $post_id;
	 
	    if (!wp_verify_nonce($_POST['produtos_nonce'], 'save_produto'))
	        return $post_id;
	 
	    if ('produtos' != $_POST['post_type'])
	        return $post_id;
	 
	    if (!current_user_can('edit_post', $post_id))
	        return $post_id;
	 
	    $preco     = sanitize_text_field($_POST['produto_preco']);
	    $descricao = sanitize_text_field($_POST['produto_descricao']);

	    update_post_meta($post_id, '_produto_preco', $preco);
	    update_post_meta($post_id, '_produto_descricao', $descricao);
	}

	add_action('init', 'create_produtos');
	add_action('add_meta_boxes', 'produtos_meta_boxes');
	add_action( 'save_post', 'produtos_save_meta_boxes');

	// Clientes
	function create_clientes() {

		register_post_type( 'clientes',
			array(
				'labels' => array(
					'name' => __( 'Clientes' ),
					'singular_name' => __( 'Cliente' ),
				),
				'public' => true,
				'menu_icon' => 'dashicons-groups',
				'has_archive' => true,
				'rewrite' => array('slug' => 'clientes'),
				'supports'            => array('title'),
				'menu_position'       => 5,
			)
		);
	}

	function clientes_meta_boxes() {
    	add_meta_box( 'detalhes-clientes', 'Detalhes do Cliente', 'clientes_output_meta_box', 'clientes', 'normal', 'high' );   
	}

	function clientes_output_meta_box($post) {
	    $email = get_post_meta( $post->ID, '_cliente_email', true );
	    $telefone = get_post_meta( $post->ID, '_cliente_telefone', true );

	    wp_nonce_field( 'save_cliente', 'clientes_nonce' );

    	echo '<table>';
    	echo '<tr>';
	    echo '<td><label for="cliente_email">' . __('Email') . ': </label></td>';
	    echo '<td><input type="text" name="cliente_email" id="cliente_email" value="' . esc_attr($email) . '" /></td>';
	    echo '</tr>';
    	echo '<tr>';
	    echo '<td><label for="cliente_telefone">' . __('Telefone') . ': </label></td>';
	    echo '<td><input type="text" name="cliente_telefone" id="cliente_telefone" value="' . esc_attr($telefone) . '" /></td>';
	    echo '</tr>';
    	echo '</table>';	    
	}

	function clientes_save_meta_boxes($post_id) {
	    if (!isset($_POST['clientes_nonce']))
	        return $post_id;
	 
	    if (!wp_verify_nonce($_POST['clientes_nonce'], 'save_cliente'))
	        return $post_id;
	 
	    if ('clientes' != $_POST['post_type'])
	        return $post_id;
	 
	    if (!current_user_can('edit_post', $post_id))
	        return $post_id;
	 
	    $email    = sanitize_text_field($_POST['cliente_email']);
	    $telefone = sanitize_text_field($_POST['cliente_telefone']);

	    update_post_meta($post_id, '_cliente_email', $email);
	    update_post_meta($post_id, '_cliente_telefone', $telefone);
	}

	add_action('init', 'create_clientes');
	add_action('add_meta_boxes', 'clientes_meta_boxes');
	add_action( 'save_post', 'clientes_save_meta_boxes');
?>