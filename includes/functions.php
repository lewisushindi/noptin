<?php
/**
 * Admin section
 *
 * Simple WordPress optin form
 *
 * @since             1.0.0
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    die;
}

/**
 * Returns a reference to the main Noptin instance.
 *
 * @return  object An object containing a reference to Noptin.
 */
function noptin() {
    return Noptin::instance();
}

/**
 * Retrieve subscriber meta field for a subscriber.
 *
 * @param   int    $subscriber_id  Subscriber ID.
 * @param   string $meta_key      The meta key to retrieve. By default, returns data for all keys.
 * @param   bool   $single        If true, returns only the first value for the specified meta key. This parameter has no effect if $key is not specified.
 * @return  mixed                 Will be an array if $single is false. Will be value of meta data field if $single is true.
 * @access  public
 * @since   1.5
 */
function get_noptin_subscriber_meta( $subscriber_id = 0, $meta_key = '', $single = false ) {
	return get_metadata( 'noptin_subscriber', $subscriber_id, $meta_key, $single );
}

/**
 * Adds subscriber meta field for a subscriber.
 *
 *
 * @param   int    $subscriber_id  Subscriber ID.
 * @param   string $meta_key      The meta key to update.
 * @param   mixed   $meta_value   Metadata value. Must be serializable if non-scalar.
 * @param   mixed   $unique   Whether the same key should not be added.
 * @return  int|false         Meta ID on success, false on failure.
 * @access  public
 * @since   1.5
 */
function add_noptin_subscriber_meta( $subscriber_id, $meta_key, $meta_value, $unique = false ) {
	return add_metadata( 'noptin_subscriber', $subscriber_id, $meta_key, $meta_value, $unique );
}

/**
 * Updates subscriber meta field for a subscriber.
 *
 * Use the $prev_value parameter to differentiate between meta fields with the same key and subscriber ID.
 *
 * If the meta field for the subscriber does not exist, it will be added and its ID returned.
 *
 * @param   int    $subscriber_id  Subscriber ID.
 * @param   string $meta_key      The meta key to update.
 * @param   mixed   $meta_value   Metadata value. Must be serializable if non-scalar.
 * @param   mixed   $prev_value   Previous value to check before updating.
 * @return  mixed                 The new meta field ID if a field with the given key didn't exist and was therefore added, true on successful update, false on failure.
 * @access  public
 * @since   1.5
 */
function update_noptin_subscriber_meta( $subscriber_id, $meta_key, $meta_value, $prev_value = '' ) {
	return update_metadata( 'noptin_subscriber', $subscriber_id, $meta_key, $meta_value, $prev_value );
}

/**
 * Deletes a subscriber meta field for the given subscriber ID.
 *
 * You can match based on the key, or key and value. Removing based on key and value, will keep from removing duplicate metadata with the same key. It also allows removing all metadata matching the key, if needed.
 *
 *
 * @param   int    $subscriber_id  Subscriber ID.
 * @param   string $meta_key      The meta key to delete.
 * @param   mixed   $meta_value   Metadata value. Must be serializable if non-scalar.
 * @return  bool                 True on success, false on failure.
 * @access  public
 * @since   1.5
 */
function delete_noptin_subscriber_meta( $subscriber_id, $meta_key, $meta_value = '' ) {
	return delete_metadata( 'noptin_subscriber', $subscriber_id, $meta_key, $meta_value );
}

/**
 * Retrieves all default noptin options
 *
 * @return  array   options
 * @access  public
 * @since   1.6
 */
function get_default_noptin_options() {

	$options = array(
		'notify_new_post' => 0,
		'from_email' 	  => get_option('admin_email'),
		'from_name' 	  => get_option('blogname'),
		'company' 	  	  => get_option('blogname'),
	);
	return $options;

}

/**
 * Retrieves all noptin options
 *
 * @return  array   options
 * @access  public
 * @since   1.6
 */
function get_noptin_options() {
	global $noptin_options;

	if( empty( $noptin_options ) ) {
		$noptin_options = get_option( 'noptin_options', array() );
	}

	if(! is_array( $noptin_options ) || empty( $noptin_options ) ) {
		$noptin_options = get_default_noptin_options();
	}
	return $noptin_options;
}

/**
 * Retrieves an option from the db
 *
 * @return  mixed|null   option or null
 * @access  public
 * @since   1.5
 */
function get_noptin_option( $key, $default = null ) {

	$options = get_noptin_options();
	$value   = $default;
	if( isset( $options[ $key ] ) ) {
		$value   = $options[ $key ];
	}

	if( 'false' == $value ) {
		$value = false;
	}

	if( 'true' == $value ) {
		$value = true;
	}

	return apply_filters( 'noptin_get_option', $value, $key );

}

/**
 * Updates noptin options
 *
 * @return  array
 * @access  public
 * @since   1.5
 */
function update_noptin_options( $options ) {
	global $noptin_options;

	$noptin_options = $options;
	update_option( 'noptin_options', $options );

}

/**
 * Updates a single option
 *
 * @return  array
 * @access  public
 * @since   1.5
 */
function update_noptin_option( $key, $value ) {

	$options       = get_noptin_options();
	$options[$key] = $value;
	update_noptin_options( $options );

}

/**
 * Prepare noptin email body
 *
 * @return  sting
 * @access  public
 * @since   1.6
 */
function prepare_noptin_email( $email, $subscriber ) {
	$noptin = noptin();

	//Unsubscribe url
	$email = str_ireplace( "[[unsubscribe_url]]", get_noptin_action_url( 'unsubscribe', $subscriber->confirm_key ), $email);

	//footer
	$email = str_ireplace( "[[noptin_company]]", get_noptin_option( 'company', ''), $email);
	$email = str_ireplace( "[[noptin_address]]", get_noptin_option( 'address', ''), $email);
	$email = str_ireplace( "[[noptin_city]]", get_noptin_option( 'city', ''), $email);
	$email = str_ireplace( "[[noptin_state]]", get_noptin_option( 'state', ''), $email);
	$email = str_ireplace( "[[noptin_country]]", get_noptin_option( 'country', ''), $email);

	//homeurl
	$email = str_ireplace( "[[home_url]]", get_home_url(), $email);

	//logo url
	$url = $noptin->admin->assets_url . '/images/square-48.png';
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if( $custom_logo_id ) {
		$logo_url = wp_get_attachment_image_src( $custom_logo_id );
		if( is_array( $logo_url ) && !empty( $logo_url[0] ) ) {
			$url = $logo_url[0];
		}
	}
	$company_logo = get_noptin_option( 'company_logo', '');
	if( $company_log ) {
		$logo_url = esc_url( $company_logo );
	}

	$email = str_ireplace( "[[logo_url]]", $url, $email);

	return $email;

}

/**
 * Returns the noptin action url
 *
 * @return  sting
 * @access  public
 * @since   1.6
 */
function get_noptin_action_url( $action, $value ) {

	$content = '
		<!-- wp:shortcode -->
		[noptin_action_page]
		<!-- /wp:shortcode -->';

	$page = get_option('noptin_actions_page');
	if( empty( $page ) ) {
		$page = wp_insert_post(
			array(
				'post_content' => $content,
				'post_title'   => __( 'Noptin Subsciber Action', 'noptin' ),
				'post_status'  => 'publish',
				'post_type'	   => 'page',
			)
		);
		update_option('noptin_actions_page', $page);
	}

	if( empty( $page ) ) {
		return get_home_url();
	}

	$url = get_the_permalink( $page );

	if( $url ) {
		return add_query_arg( array(
			'noptin_action' => $action,
			'noptin_value'  => $value,
		), $url );
	}

	return get_home_url();

}

/**
 * Retrieves the URL to the subscribers page
 *
 * @return  string   The subscribers page url
 * @access  public
 * @since   1.5
 */
function get_noptin_subscribers_overview_url() {
	$url = admin_url('admin.php?page=noptin-subscribers');
	return $url;
}

/**
 * Retrieves the URL to the forms creation page
 *
 *
 *
 * @return  string   The forms page url
 * @access  public
 * @since   1.5
 */
function get_noptin_new_form_url() {
	$url = admin_url('admin.php?page=noptin-forms');
	return add_query_arg( 'action', 'new', $url );
}


/**
 * Retrieves the URL to the forms overview page
 *
 * @return  string   The forms page url
 * @access  public
 * @since   1.5
 */
function get_noptin_forms_overview_url() {
	$url = admin_url('admin.php?page=noptin-forms');
	return $url;
}

/**
 * Returns opt-in forms field types
 *
 * @return  array
 * @access  public
 * @since   1.0.8
 */
function get_noptin_optin_field_types() {
	return apply_filters( 'noptin_field_types', array() );
}

/**
 * Retrieves the subscriber count
 *
 * @return  int   $where Restriction string
 * @access  public
 * @since   1.5
 */
function get_noptin_subscribers_count( $where = '' ) {
	global $wpdb;

	$table = $wpdb->prefix . 'noptin_subscribers';

	if(! empty( $where ) ) {
		$where = "WHERE $where";
	}

	return $wpdb->get_var("SELECT COUNT(`id`) FROM $table $where;");
}

/**
 * Retrieves the subscriber growth
 *
 * @access  public
 * @since   1.5
 */
function get_noptin_subscribers_growth() {
	global $wpdb;

	$table = $wpdb->prefix . 'noptin_subscribers';
	$sql   = "SELECT COUNT(`id`)/count(distinct `date_created`) as avg FROM `$table`;";

	return (float) $wpdb->get_var($sql );

}

/**
 * Inserts a new subscriber into the database
 *
 * @access  public
 * @since   1.5
 */
function add_noptin_subscriber( $fields ) {
	global $wpdb;

	$table = $wpdb->prefix . 'noptin_subscribers';

	//Ensure an email address is provided and it doesn't exist already
	if( empty( $fields['email'] ) || !is_email( $fields['email'] ) ) {
		return "Please provide a valid email address";
	}

	if( noptin_email_exists( $fields['email'] ) ) {
		return true;
	}

	//Maybe split name into first and last
	if( isset( $fields['name'] ) ) {
		$names = noptin_split_subscriber_name( $fields['name'] );

		$fields['first_name']  = empty( $fields['first_name'] ) ? $names[0] : trim( $fields['first_name'] );
		$fields['last_name'] = empty( $fields['last_name'] ) ? $names[1] : trim( $fields['last_name'] );
	}

	$database_fields = array(
		'email' 		=> $fields['email'],
		'first_name'	=> empty( $fields['first_name'] ) ? '' : $fields['first_name'],
		'second_name'	=> empty( $fields['last_name'] ) ? '' : $fields['last_name'],
		'confirm_key'	=> md5($email) . wp_generate_password(4, false),
		'date_created'	=> date("Y-m-d"),
	);

	if(! $wpdb->insert( $table, $database_fields, '%s' ) ) {
		return "An error occurred. Try again.";
	}

	$id = $wpdb->insert_id;

	//Insert additional meta data
	foreach( $fields as $field=>$value ){

		if( isset( $database_fields[ $field ] ) || 'name' == $field ) {
			continue;
		}

		update_noptin_subscriber_meta( $id, $field, $value );
	}

	setcookie( 'noptin_email_subscribed', '1', time() + (86400 * 30), COOKIEPATH, COOKIE_DOMAIN);

	return $id;

}

/**
 * Converts a name field into the first and last name
 *
 * Simple Function, Using Regex (word char and hyphens)
 * It makes the assumption the last name will be a single word.
 * Makes no assumption about middle names, that all just gets grouped into first name.
 * You could use it again, on the "first name" result to get the first and middle though.
 *
 * @access  public
 * @since   1.5
 */
function noptin_split_subscriber_name( $name ) {

	$name       = trim($name);
    $last_name  = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
    return array($first_name, $last_name);

}

/**
 * Checks whether the subscriber with a given email exists.
 *
 * @param string The email to check for
 * @return bool
 */
function noptin_email_exists( $email ){
	global $wpdb;
	$table = $wpdb->prefix . 'noptin_subscribers';
	$sql   = $wpdb->prepare( "SELECT COUNT(id) FROM $table WHERE email =%s;", $email );

	return 0 < $wpdb->get_var( $sql );
}

/**
 * Checks whether the subscribers table exists
 *
 * @return bool
 */
function noptin_subscribers_table_exists(){
	global $wpdb;
	$table = $wpdb->prefix . 'noptin_subscribers';

	return $table == $wpdb->get_var("SHOW TABLES LIKE '$table'" );
}

/**
 * Checks whether the subscribers meta table exists
 *
 * @return bool
 */
function noptin_subscribers_meta_table_exists(){
	global $wpdb;
	$table = $wpdb->prefix . 'noptin_subscriber_meta';

	return $table == $wpdb->get_var("SHOW TABLES LIKE '$table'" );
}

/**
 * Retrieves an optin form.
 *
 * @param int|Noptin_Form The id or Noptin_Form object of the optin to retrieve
 * @return Noptin_Form
 */
function noptin_get_optin_form( $id ){
    return new Noptin_Form( $id );
}

/**
 * Retrieves the total opt-in forms count.
 *
 * @param string Optionally filter by opt-in type
 * @return int
 */
function noptin_count_optin_forms( $type = '' ){
	global $wpdb;

	$sql   = "SELECT COUNT(`ID`) FROM {$wpdb->posts} as forms";
	$where = "WHERE `post_type`='noptin-form'";

	if(! empty( $type ) ) {
		$sql = "$sql LEFT JOIN {$wpdb->postmeta} as meta
			ON meta.post_id = forms.ID
			AND meta.meta_key = '_noptin_optin_type'
			AND meta.meta_value = %s";

		$sql   = $wpdb->prepare( $sql, $type );
		$where .= " AND meta.meta_key='_noptin_optin_type'";
	}

    return $wpdb->get_var("$sql $where;");
}

/**
 * Creates an optin form
 */
function noptin_create_optin_form( $data = false ){
    $form    = new Noptin_Form( $data );
    $created = $form->save();

    if( is_wp_error( $created ) ) {
        return $created;
    }

    return $form->id;
}


/**
 * Deletes an optin form
 */
function noptin_delete_optin_form( $id ){
    return wp_delete_post( $id, true );
}

/**
 * Duplicates an optin form
 */
function noptin_duplicate_optin_form( $id ){
    $form = noptin_get_optin_form( $id );
    $form->duplicate();
    return $form->id;
}

/**
 * Returns all optin forms
 */
function noptin_get_optin_forms( $meta_key = '', $meta_value = '', $compare = '='){
    $args   = array(
        'numberposts'      => -1,
        'post_type'        => 'noptin-form',
        'post_status'      => array( 'draft', 'publish' )
    );

    if( $meta_key ) {
        $args['meta_query'] = array(
                array(
                    'key'       => $meta_key,
                    'value'     => $meta_value,
                    'compare'   => $compare,
                )
            );

    }
    return get_posts( $args );
}

/**
 * Returns optin post type details
 */
function noptin_get_optin_form_post_type_details(){
	return apply_filters(
		'noptin_optin_form_post_type_details',
		array(
			'labels'              => array(),
			'description'         => '',
			'public'              => false,
			'show_ui'             => false,
			'map_meta_cap'        => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'query_var'           => false,
			'supports'            => array(),
			'has_archive'         => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'menu_icon'   		  => ''
		));
}

/**
 * Returns post types
 */
function noptin_get_post_types(){
    $return = array();
    $args   = array(
        'public'    => true,
        'show_ui'   => true
    );
    $post_types = get_post_types( $args, 'objects' );

    foreach( $post_types as $obj ){
        $return[$obj->name] = $obj->label;
    }
    unset( $return['attachment'] );

    return $return;

}

/**
 * Checks whether an optin form should be displayed
 */
function noptin_should_show_optins(){

	if(! empty( $_COOKIE['noptin_email_subscribed'] ) && get_noptin_option( 'hide_from_subscribers' ) ){
		return false;
	}

	if(! empty( $_REQUEST['noptin_hide'] ) ) {
		return false;
	}

	return true;

}

/**
 * Returns color themess
 */
function noptin_get_color_themes(){
    return apply_filters(
		'noptin_form_color_themes',
		array(
            'Red'           => '#e51c23 #fafafa #c62828', //Base color, Secondary color, border color
            'Pink'          => '#e91e63 #fafafa #ad1457',
            'Purple'        => '#9c27b0 #fafafa #6a1b9a',
            'Deep Purple'   => '#673ab7 #fafafa #4527a0',
            'Purple'        => '#9c27b0 #fafafa #4527a0',
            'Indigo'        => '#3f51b5 #fafafa #283593',
            'Blue'          => '#2196F3 #fafafa #1565c0',
            'Light Blue'    => '#03a9f4 #fafafa #0277bd',
            'Cyan'          => '#00bcd4 #fafafa #00838f',
            'Teal'          => '#009688 #fafafa #00695c',
            'Green'         => '#4CAF50 #fafafa #2e7d32',
            'Light Green'   => '#8bc34a #191919 #558b2f',
            'Lime'          => '#cddc39 #191919 #9e9d24',
            'Yellow'        => '#ffeb3b #191919 #f9a825',
            'Amber'         => '#ffc107 #191919 #ff6f00',
            'Orange'        => '#ff9800 #fafafa #e65100',
            'Deep Orange'   => '#ff5722 #fafafa #bf360c',
            'Brown'         => '#795548 #fafafa #3e2723',
            'Blue Grey'     => '#607d8b #fafafa #263238',
            'Black'         => '#313131 #fafafa #607d8b',
            'White'         => '#ffffff #191919 #191919',
            'Grey'          => '#aaaaaa #191919 #191919',
        ));

}

/**
 * Returns optin templates
 */
function noptin_get_optin_templates(){
    $custom_templates = get_option( 'noptin_templates' );
	$inbuilt_templates = include 'admin/templates/templates.php';

    if(! is_array( $custom_templates ) ) {
        $custom_templates = array();
	}

	$templates = array_replace( $custom_templates, $inbuilt_templates );

    return apply_filters( 'noptin_form_templates', $templates );

}

/**
 * Returns color themess
 */
function noptin_get_form_design_props(){
    return apply_filters(
		'noptin_form_design_props',
		array(
			'hideCloseButton', 'closeButtonPos', 'singleLine', 'formRadius', 'formWidth',
			'formHeight', 'noptinFormBg', 'fields', 'imageMain',
            'noptinFormBorderColor', 'image', 'imagePos', 'noptinButtonLabel', 'buttonPosition',
            'noptinButtonBg', 'noptinButtonColor', 'hideTitle', 'title', 'titleColor',
            'hideDescription', 'description', 'descriptionColor', 'hideNote', 'hideOnNoteClick',
            'note', 'noteColor', 'CSS', 'optinType'
        ));

}

/**
 * Function noptin editor localize
 */
function noptin_localize_optin_editor( $state ){
	$props   = noptin_get_form_design_props();
	$props[] = 'DisplayOncePerSession';
	$props[] = 'timeDelayDuration';
	$props[] = 'scrollDepthPercentage';
	$props[] = 'cssClassOfClick';
	$props[] = 'triggerPopup';

    $params = array(
        'ajaxurl'      => admin_url('admin-ajax.php'),
        'api_url'      => get_home_url( null, 'wp-json/wp/v2/'),
        'nonce'        => wp_create_nonce('noptin_admin_nonce'),
        'data'         => $state,
        'templates'    => noptin_get_optin_templates(),
        'color_themes' => noptin_get_color_themes(),
        'design_props' => $props,
    );
    wp_localize_script('noptin', 'noptinEditor', $params);
}

/**
 * Function noptin editor localize
 */
function noptin_form_template_form_props(){

	$class = "singleLine ? 'noptin-form-single-line' : 'noptin-form-new-line'";

	return " @submit.prevent :class=\"$class\"";
}

/**
 * Function noptin editor localize
 */
function noptin_form_template_wrapper_props(){

    $props = array(
		':data-trigger="triggerPopup"',
		':data-after-click="cssClassOfClick"',
		':data-on-scroll="scrollDepthPercentage"',
		':data-after-delay="timeDelayDuration"',
		'class="noptin-optin-form-wrapper"',
		':data-once-per-session="DisplayOncePerSession"',
		':style="{
			borderColor: noptinFormBorderColor,
			backgroundColor: noptinFormBg,
			borderRadius: formRadius,
			width: formWidth,
			minHeight: formHeight
		}"'
	);

	return implode( ' ', $props );
}
