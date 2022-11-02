/************/
Test Functions
/************/
<style type="text/css">
.title{
    font-size: 15px;
}
</style>

main content
Image gallery :- Fancybox js
Wordpress Contact Form 7 - Safest version 5.3.2(cf7 redirect issue solved this version)
Acceptance 
Get post category by id : - <?php echo get_the_category($id)[0]->name ?>
WP RollBack - any plugin downgrade.
Radiobutton section show hide :- get_field('footer_section_showhide',get_queried_object_id()) == "yes"
Phone no.acf filed :- <?php echo str_replace(" ", "", $phone); ?>

Htaccess redirect

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP:X-Forwarded-Proto} !https
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

Http to https + www to nonwww redirect 
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

RewriteCond %{HTTP_HOST} ^www.adelaidehillsskiphire.com.au$
RewriteRule ^(.*) http://adelaidehillsskiphire.com.au/$1  [QSA,L,R=301]
</IfModule>



<div class="title">Shortcode</div>/*shortcode*/

function movie_shortcode(){

	$result="";
	$args = array(
			'post_type' => 'movies',
			'publish_status' => 'published',
			'posts_per_page' 	=> 5 ,
	);
	$query = new WP_Query($args);
    if($query->have_posts()) :
        while($query->have_posts()) :
            $query->the_post() ;
                  
        $result .= '<div class="movie-item">';
        $result .= '<div class="movie-name">' .  get_the_title() . '</a></div>';
        $result .= '<div class="movie-desc">' . get_the_content() . '</div>'; 
        $result .= '<div class="movie-desc"><label>Hero Name : </label> ' . get_post_meta( get_the_ID(),'hero_name',true) . '</div>'; 
        $result .= '<div class="movie-desc"><label>Heroine Name : </label> ' . get_post_meta( get_the_ID(),'heroine_name',true) . '</div>';
        $result .= '</div>';
 
        endwhile;
 
        wp_reset_postdata();
 
    endif;    
 
    return $result;            
}

add_shortcode('movies','movie_shortcode');


/* create custom post meta */

  add_action( 'add_meta_boxes', 'create_metabox' );
  function create_metabox(){
add_meta_box('hero','Hero Name','meta_box_html','movies');
}

function meta_box_html($post){ 
?>
<label for="hero-name"> Hero Name </label>
<input type="text" name="hero-name" value="<?php echo get_post_meta( get_the_ID(),'hero_name',true);  ?>">
<br>
<br>	
<label for="heroine-name"> Heroine Name </label>
<input type="text" name="heroine-name" value="<?php echo get_post_meta( get_the_ID(),'heroine_name',true);  ?>">

<?php
}
function wporg_save_postdata( $post_id ) {
    if ( array_key_exists( 'design-name', $_POST ) ) {
        update_post_meta( $post_id,  'design'  , $_POST['design-name'] );
    }
    if ( array_key_exists( 'develop-name', $_POST ) ) {
        update_post_meta( $post_id, 'develop' , $_POST['develop-name'] );
    }
}
add_action( 'save_post', 'wporg_save_postdata' );


add_post_meta(id,title,callback,screen, context, priority)
delete_post_meta(id,key,value)
update_post_meta(id,key,value,previous_value)
get_post_meta( int $post_id, string $key = '', bool $single = false )

/*display single or template page*/
echo "Hero Name :";  echo "&nbsp;"; echo get_post_meta( get_the_ID(),'hero_name',true);  echo "<br>";
echo "Heroine Name :";   echo "&nbsp;"; echo get_post_meta( get_the_ID(),'heroine_name',true);   echo "<br>";





/* This theme uses wp_nav_menu() in two locations.  */
Navigation menu 
Reg_nav_menu
nav_nav_menu(theme_location->’second’);

(function.php)register_nav_menus( array(  
  'first' => __( 'Primary Navigation', 'storefront' ),  
  'second' => __('Secondary Navigation', 'storefront')  
) );

(footer.php)<div class="bottomMenu">
              <?php wp_nav_menu( array( 'theme_location' => 'second' ) ); ?>  
    </div>
    

/*Add a Custom Sidebar to a WordPress Theme*/
(function.php)
function my_custom_sidebar() {
    register_sidebar(
        array (
            'name' => __( 'Custom', 'your-theme-domain' ),
            'id' => 'custom-side-bar',
            'description' => __( 'Custom Sidebar', 'your-theme-domain' ),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'my_custom_sidebar' );

(custom Template)
<?php if ( is_active_sidebar( 'custom-side-bar' ) ) : ?>
    <?php dynamic_sidebar( 'custom-side-bar' ); ?>
<?php endif; ?>


/*Custom Post Type */

// Our custom post type function
function create_posttype() {
 
    register_post_type( 'jobs',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Jobs' ),
                'singular_name' => __( 'Job' )
            ),
            'public' => true,
            'show_in_menu' => true,
            'show_in_nav_menus'   => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'jobs'),
            'show_in_rest' => true,
            'taxonomies'  => array( 'category' ),
            'taxonomies'          => array('topics', 'category' ),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );
//hook into the init action and call create_book_taxonomies when it fires
 
add_action( 'init', 'create_subjects_hierarchical_taxonomy', 0 );
 
//create a custom taxonomy name it subjects for your posts
 
function create_subjects_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Subjects', 'taxonomy general name' ),
    'singular_name' => _x( 'Subject', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Subjects' ),
    'all_items' => __( 'All Subjects' ),
    'parent_item' => __( 'Parent Subject' ),
    'parent_item_colon' => __( 'Parent Subject:' ),
    'edit_item' => __( 'Edit Subject' ), 
    'update_item' => __( 'Update Subject' ),
    'add_new_item' => __( 'Add New Subject' ),
    'new_item_name' => __( 'New Subject Name' ),
    'menu_name' => __( 'Subjects' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('subjects',array('jobs'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'subject' ),
  ));
 
}
add_action( 'pre_get_posts', 'add_my_post_types_to_query' );
 
function add_my_post_types_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'jobs' ) );
    return $query;
} 	

1.	/* load More Post with ajax post and cpt */(Custom Template)

<?php 
$args = array(
    'post_type' => 'service',
    'paged' => 1,
);
$the_query = new WP_Query( $args ); ?>
<div id="posts">
<?php if ( $the_query->have_posts() ) : ?>
 
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <h2><li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li></h2>
        <?php the_excerpt(__('(more…)')); ?>
    <?php endwhile; ?>
   
    <?php wp_reset_postdata(); ?>
 
<?php else : ?>
    <p><?php echo( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>
</div>
<button id="load_more" style="margin-bottom: 50px; margin-top: 30px;" >Load More</button>

1.2 /* load More Post with ajax post and cpt */ (Function.php)


//loadmore button
add_action( 'wp_footer', 'my_action_javascript' ); 
function my_action_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var page = 2;
		var ajaxurl = "<?php  echo admin_url('admin-ajax.php');  ?>";
		jQuery('#load_more').click(function(){
			var data = {
				'action': 'my_action',
				'page' : page
			};			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#posts').append(response);
				page++;
			});
		});	
	});
	</script> 
<?php
}
add_action( 'wp_ajax_my_action', 'my_action' );
		function my_action() {
			$args = array(
		    'post_type' => 'service',
		    'paged' => $_POST['page'],
		);
		$the_query = new WP_Query( $args ); ?>
			<?php if ( $the_query->have_posts() ) : ?>
			    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			        <h2><li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li></h2>
			        <?php the_excerpt(__('(more…)')); ?>
			    <?php endwhile; ?>  
			    <?php wp_reset_postdata(); ?>
			<?php else : ?>
			    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
			<?php endif; 
	wp_die(); 
}

/* Archive Post custom template */

<?php 
$loop = new WP_Query( array( 'post_type' => 'service', 'posts_per_page' => 10 ) ); 

while ( $loop->have_posts() ) : $loop->the_post();

the_title( '<h1><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h1>' ); 
?>

    <div class="entry-content">
        <?php the_content(); ?>
    </div>

<?php endwhile; ?>

/* ajax live search for post title */

(search label file)button per onclick=”fetch()”
<label>Search </label>
<input type="text" name="keyword" id="keyword" onkeyup="fetch()"></input>
<div id="datafetch">Search results will appear here</div>

 (function file)
//search filter -> add the ajax fetch js
add_action( 'wp_footer', 'ajax_fetch' );
function ajax_fetch() {
?>
<script type="text/javascript">
function fetch(){
    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'post',
        data: { action: 'data_fetch', keyword: jQuery('#keyword').val() },
        success: function(data) {
            jQuery('#datafetch').html( data );
        }
    });
}
</script>
<?php
}
// Search filter->the ajax function
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){

    $the_query = new WP_Query( array( 'posts_per_page' => -1, 's' => esc_attr( $_POST['keyword'] ), 'post_type' => 'service' ) );
    if(!empty($_POST['keyword']) && $the_query->have_posts() ) :
        while( $the_query->have_posts() ): $the_query->the_post(); ?>
            
            <h2><a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a></h2>

        <?php endwhile;

        wp_reset_postdata();  
    endif;

    die();
}

<!-- contactform7 multiple validation message disable jquery -->
<script type="text/javascript">
  var disableSubmit = false;
  jQuery('input.wpcf7-submit[type="submit"]').click(function() {
    jQuery(':input[type="submit"]')
    if (disableSubmit == true) {
        return false;
    }
    disableSubmit = true;
    return true;
    })  
var wpcf7Elm = document.querySelector( '.wpcf7' );
if(wpcf7Elm){
    wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
        jQuery(':input[type="submit"]')
        disableSubmit = false;
    }, false );
}
</script>

/* make an equal height all div jquery */
var maxHeight = 0;
$("divsamename1").each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});
$("divsamename1").height(maxHeight);





/*------------------ Create Custom Post Type for Project-Type --------------------*/

add_action( 'init', 'create_post_type_project_type' );
function create_post_type_project_type() {
register_post_type( 'project-type',
array(
'labels' => array(
'name' => _x(
 'Project Type', 'taxonomy general name' ),
'singular_name' => _x(
 'Project Type', 'taxonomy singular name' ),
'search_items' => __( 'Search Project Type' ),
'all_items' => __( 'All Project Types' ),
'edit_item' => __( 'Edit Project Type' ),
'update_item' => __( 'Update Project Type' ),
'add_new_item' => __( 'Add New Project Type' ),
'new_item_name' => __( 'New Project Type' ),
'menu_name' => __( 'Project Types' )
),
'public' => true,
'has_archive' => true,
'publicly_queryable' => true,
'menu_icon' => 'dashicons-portfolio',
'supports' => array( 'title', 'thumbnail','editor')
)
);
}

add_action( 'init', 'create_project_type_hierarchical_taxonomy', 0 );

function create_project_type_hierarchical_taxonomy() {

$labels = array(
'name' => _x(
 'Project Type Category', 'taxonomy general name' ),
'singular_name' => _x(
 'Project Type Category', 'taxonomy singular name' ),
'search_items' => __( 'Search Project Type Category' ),
'all_items' => __( 'All Project Type Category' ),
'parent_item' => __( 'Parent Project Type Category' ),
'parent_item_colon' => __( 'Parent Project Type Category:' ),
'edit_item' => __( 'Edit Project Type Category' ),
'update_item' => __( 'Update Project Type Category' ),
'add_new_item' => __( 'Add New Category' ),
'new_item_name' => __( 'New Category Name' ),
'menu_name' => __( 'Category' ),
);

// Now register the taxonomy

register_taxonomy('project-typecategory',array('project-type'), array(
'hierarchical' => true,
'labels' => $labels,
'show_ui' => true,
'show_admin_column' => true,
'query_var' => true,
'rewrite' => array( 'slug' => 'project-type-category' ),
));


/* CF7 Validation */

add_action('wp_footer', 'function_name');


Script validate:-
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js?ver=5.8.3' id='sage/jquery.validate.min.js-js'></script>

Cf7 form admin :----> 
<button class="wpcf7-form-control wpcf7-submit" type="button" id="form_submit">Submit</button>
[submit id:contact_form_submit class:d-none "Submit"]

Script :-> 
// [START] Service page : Form Validation
jQuery(document).ready(function () {

    // [START] email_extend
    jQuery.validator.addMethod(
        "email_extend",
        function (value, element) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                return true;
            } else {
                return false;
            }
        },
        "Email are not valid email address"
    );
    // [END] email_extend

    // [START] Alphabets Only
    jQuery.validator.addMethod(
        "lettersonly",
        function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        },
        "Only alphabets are allowed"
    );
    // [END] Alphabets Only
    var page_id = '<?php echo get_the_ID(); ?>';
    jQuery("#wpcf7-f1473-p"+page_id+"-o1 form").validate({
        normalizer: function (value) {
            return jQuery.trim(value);
        },
        rules: {
            firstname:{
                required: true,
                lettersonly: true,
                maxlength: 25
            },
            lastname: {
                required: true,
                lettersonly: true,
                maxlength: 25
            },
            phone: {
                required: true,
                number: true,
                maxlength: 13,
                minlength: 7
            },
            youremail: {
                required: true,
                email_extend: true
            },
            SelectServices: {
                required: true
            },
        },
        messages: {
            firstname: {
                required: "First name is required"
            },
            lastname: {
                required: "Last name is required"
            },
            phone: {
                required: "Phone Number is required"
            },
            youremail: {
                required: "Email address is required"
            },
            SelectServices: {
                required: "Please Select Services option"
            },

        },
        errorElement: 'span',
        errorClass: 'wpcf7-not-valid-tip'
    });

    jQuery('#form1_submit').on('click', function () {
        var form = jQuery('#wpcf7-f1473-p'+page_id+'-o1 form');
        if (!form.valid()) {
            return;
        } else {
            jQuery('#wpcf7-f1473-p'+page_id+'-o1 input[type="submit"]').trigger('click');
        }
    });

});
// [END] Service page : Form Validation

//formid
var formID = jQuery(' .wpcf7').attr("id");
  // console.log('#'+formID+' '+'form');
  jQuery('#'+formID+' '+'form').validate({
    normalizer: function(value) {
      return jQuery.trim(value);
    },
    rules: {
      yourname: {
        required: true,
        lettersonly: true,
        maxlength: 50
      },
      youremail: {
        required: true,
        email_extend:true
      }
    }, 
    messages: {
       yourname: {
        required: "Name is required",
        lettersonly: "Only alphabets are allowed",
        maxlength: "Allow only 50 character"
      },
      youremail: {
        required: "Email address is required",
      }
    },
    errorElement : 'span',
    errorClass : 'wpcf7-not-valid-tip'
  });
  jQuery('#form_submit2').on('click', function () {
      var form = jQuery('#'+formID+' '+'form');
      // console.log(form);
      if (!form.valid()) {
          return;
      } else {
          jQuery('#'+formID+' '+'input[type="submit"]').trigger('click');
      }
  });

//One Page multiple form each function is used

<script>
// [START] Service page : Form Validation
jQuery(document).ready(function () {
    // [START] email_extend
    jQuery.validator.addMethod(
        "email_extend",
        function (value, element) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                return true;
            } else {
                return false;
            }
        },
        "Email are not valid email address"
    );
    // [END] email_extend

    // [START] Alphabets Only
    jQuery.validator.addMethod(
        "lettersonly",
        function (value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
        },
        "Only alphabets are allowed"
    );

    jQuery( ".wpcf7" ).each(function() {
        var formID = jQuery(this).attr("id");
        jQuery('#'+formID+' '+'form').validate({
            normalizer: function(value) {
              return jQuery.trim(value);
            },
            rules:{
                fname:{
                    required: true,
                    lettersonly: true,
                    maxlength: 25
                },
                lname:{
                    required: true,
                    lettersonly: true,
                    maxlength: 25
                },
                vin:{
                    required: true,
                },
                email:{
                    required: true,
                    email_extend: true
                },
            },
            messages:{
                fname:{
                    required: "First Name is required"
                },
                lname:{
                    required: "Last Name is required"
                },
                vin:{
                    required: "vin is required"
                },
                email:{
                    required: "Email address is required"
                },
            },
            errorElement : 'span',
            errorClass : 'wpcf7-not-valid-tip'
        });
    });

    jQuery(document).on('click', '#57_form_submit', function(){
        var formID = jQuery(this).closest('form').parent().attr("id");
        var form = jQuery('#'+formID+' '+'form');
        if (!form.valid()) {
            return;
        } else {
            jQuery('#'+formID+' '+'input[type="submit"]').trigger('click');
        }
    });
    
});
// [END] Service page : Form Validation
</script>




Header woocomerce cart counter 
<div class="cart-item">
        <?php
            $items_count = WC()->cart->get_cart_contents_count(); 
            ?>
        <div id="mini-cart-count"><i class="fa fa-shopping-cart"></i><?php echo $items_count ? $items_count : '0'; ?>
        </div>
    </div>

//hushhydro.com.au
<a href="<?php echo wc_get_cart_url(); ?>">
    <span>My Cart</span>
    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
    <span class="cart_count"><?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
    </span>
</a>

//click add to cart then update wc cart count
add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_total');
function wc_refresh_mini_cart_total($fragments){
    ob_start();
    $items_total = WC()->cart->get_cart_contents_count();
    ?>
    <span class="cart_count"><?php echo $items_total ? $items_total : '&nbsp;'; ?></span>
    <?php
        $fragments['.cart_count'] = ob_get_clean();
    return $fragments;
}


/*Woocom product in add taxonomy*/
function ess_custom_taxonomy_Item()  {
$labels = array(
    'name'                       => 'Brands',
    'singular_name'              => 'Brand',
    'menu_name'                  => 'Brands',
    'all_items'                  => 'All Brands',
    'parent_item'                => 'Parent Brand',
    'parent_item_colon'          => 'Parent Brand:',
    'new_item_name'              => 'New Brand Name',
    'add_new_item'               => 'Add New Brand',
    'edit_item'                  => 'Edit Brand',
    'update_item'                => 'Update Brand',
    'separate_items_with_commas' => 'Separate Brand with commas',
    'search_items'               => 'Search Brands',
    'add_or_remove_items'        => 'Add or remove Brands',
    'choose_from_most_used'      => 'Choose from the most used Brands',
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
);
register_taxonomy( 'brand', 'product', $args );
}    
add_action( 'init', 'ess_custom_taxonomy_item', 0 );

/*******  set redirect to disable category pages   *******/
add_action('template_redirect', 'wpse69948_archive_disabler');
function wpse69948_archive_disabler()
{
    if(is_tag() || is_category() || is_date() || is_author()){
        wp_redirect( home_url('/media/') );
    }
    if(is_archive('project-category')){
        wp_redirect( home_url('/projects/') );
    }
}

/*******  smooth scroll jQuery   *******/
<script> port,western,northmelbourneroofing 
    jQuery(".guaranteemenu a").on('click', function(event) {
    let href = location.href; // find with href current url(Path)
    let origin = location.origin;
    console.log(origin);
    if (href == origin+"/about-us/" || href == origin+"/about-us/#guarantee" ) {
        if (this.hash !== "") {
          event.preventDefault();
          var hash = this.hash;
          jQuery('html, body').animate({
            scrollTop: jQuery(hash).offset().top
          }, 800, function(){
            window.location.hash = hash;
          });
        }
    } 
   });
</script>
//Purchasing solution on load direct section reach
<script>
jQuery(document).on('click', '.second ul li.partners_menu a', function() { 
    // target element id 
    var id = jQuery(this).attr('href'); 
    id = id.replace("/our-supply-partners/", "");    
    var $id = jQuery(id);  
    if ($id.length === 0) {
        return; 
    } 
    var pos = $id.offset().top - 320;
    jQuery('body, html').animate({ 
        scrollTop: pos 
    }, 100); 
}); 
</script>

<script>
jQuery(window).on('load',function(){
  // Prevent default anchor click behavior on page load 
  <?php if (is_page('8691')){ ?>
    var url = jQuery(location).attr('href');
    var loc_id = url.split('/').pop();
    jQuery('html, body').animate({
      scrollTop: jQuery(loc_id).offset().top - 320
    }, 100);   
  <?php } ?> 
  <?php if (is_page('8703')){ ?>
    var url = jQuery(location).attr('href');
    var loc_id = url.split('/').pop();
    jQuery('html, body').animate({
      scrollTop: jQuery(loc_id).offset().top - 100
    }, 100);   
  <?php } ?> 
});
</script>

//veracityfinance.com.au
jQuery(document).ready(function() { 
    jQuery(document).on('click', 'a.approved', function(e) { 
        var id = jQuery(this).attr('href');  
        var mainid = id.replace("https://veracityfinancial.com.au/contact-us/", "")
        console.log(mainid);
        // target element 
        var $id = jQuery(mainid); 
        if ($id.length === 0) { 
            return; 
        } 
        //console.log($id); 
        // prevent standard hash navigation (avoid blinking in IE) 
        e.preventDefault(); 
        // top position relative to the document 
        var pos = $id.offset().top-150; 
        // animated top scrolling 
        jQuery('body, html').animate({ 
            scrollTop: pos 
        }, 2000); 
    }); 
});



(ACS Tooltip https://www.australiancarsubscriptions.com.au/car/gl-navigator/)
//tooltip for Disclaimer
jQuery(".dis_title_d1").hover(
  function () {
    jQuery(this).parent().parent().find(".dis_tooltip_d1").addClass("active");
  },
  function () {
    jQuery(this)
      .parent()
      .parent()
      .find(".dis_tooltip_d1")
      .removeClass("active");
  }
);

jQuery(".dis_title_d2").hover(
  function () {
    jQuery(this).parent().parent().find(".dis_tooltip_d2").addClass("active");
  },
  function () {
    jQuery(this)
      .parent()
      .parent()
      .find(".dis_tooltip_d2")
      .removeClass("active");
  }
);
/*get-the-children-of-the-parent-category*/
$term = get_queried_object();
$childcat = get_terms( $term->taxonomy, array(
    'parent'    => $term->term_id,
    'hide_empty' => false
) );
if ( $childcat ) { ?>
        <section class="sec_spacing">
            <div class="row container_row">
            <div class="project-listing">
            <div class="project-item" >
                <div class="project-inner ">
                <?php foreach( $childcat as $subcat ){
                $catIMG = get_field('category_image', 'project-category_' . $subcat->term_id); ?>
                <a class="image-nav-block" href="<?php echo get_term_link($subcat->slug, $term->taxonomy);?>">
                    <div class="nav-block-overlay">
                    <div class="image-category">
                        <?php echo wp_get_attachment_image($catIMG, 'medium'); ?>
                    </div>
                    <div class="title-overlay">
                      <?php echo $subcat->name; ?>
                    </div>
                  </div>
                </a>
                <?php } ?>
                </div>   
            </div>
            </div>
            </div>
        </section>        
<?php } 

//woocom shop page add body class-bloominhydro
add_filter( 'body_class', 'woo_shop_class' );
// Add WooCommerce Shop Page CSS Class
function woo_shop_class( $classes ) {
  if ( is_shop() )  // Set conditional
    $classes[] = 'shop-by-cat-page'; // Add Class
return $classes;
}





Only shop page add class
add_filter( 'body_class', 'woo_shop_class' );
// Add WooCommerce Shop Page CSS Class
function woo_shop_class( $classes ) {
  if ( is_shop() )  // Set conditional
    $classes[] = 'shop-by-cat-page'; // Add Class
return $classes;
}

Back to Top
<a id="back-to-top" class="back-to-top" href="#">
    <span class="back-to-top-icon">   
    <?php superfood_elated_icon_collections()->getBackToTopIcon('font_awesome');?>
    </span>
</a>
<script>
var btn = jQuery('#back-to-top');
jQuery(window).scroll(function() {
  if (jQuery(window).scrollTop() > 300) {
    btn.addClass('sticky-back-top');
  } else {
    btn.removeClass('sticky-back-top');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  jQuery('html, body').animate({scrollTop:0}, '100');
});
</script>

Right click disable
<script type = "text/javascript">
//right click disable
jQuery(document).bind('contextmenu', function (e) {
  e.preventDefault();
});
</script>

//Load More Post Button Hide

/*******single/shortcode/listing page*******/
add_action('genesis_loop', 'my_custom_loop');
function my_custom_loop() {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $query_args = array(
        'post_type' => 'post', 
        'posts_per_page' => 5, 
        'paged' => 1
    );
    $the_query = new WP_Query($query_args);
    $published_posts = $the_query->found_posts; 
    if ($the_query->have_posts()) :?>
        <div class="1blog-sec 1blog-list-sec">
        <input type="hidden" id="allcountevent" value="<?php echo $published_posts; ?>"> 
        <input type="hidden" id="eventid" value="5"> 
        <div class="blog-post-listing">
        <?php
       $acount = 1;
       while ($the_query->have_posts()) : $the_query->the_post(); 
          $content = get_the_content();
          $content = preg_replace("/<img[^>]+\>/i", "", $content);          
          $content = apply_filters('the_content', $content);
          $content = str_replace(']]>', ']]>', $content);
          $content = apply_filters('the_content', $content);
          $content = wp_filter_nohtml_kses($content);
          $short_content = (strlen($content) > 150)?substr($content,0,150)."...":$content; 
          $post_title = get_the_title(); 
          $post_title = (strlen($post_title) > 32)?substr($post_title,0,32)."...":$post_title; 
          $post_image_title = get_the_title(); 
          $feat_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
          $image_url = $feat_image[0];          
          if($image_url == "")
            $image_url = get_stylesheet_directory_uri()."/images/placeholder-blog.png";
          if($acount != 1){
            $image_url = bfi_thumb($image_url, array('width' => 300, 'height' => 200, 'crop' => true ));
          }
          else{
            $image_url = bfi_thumb($image_url, array('width' => 600, 'height' => 300, 'crop' => true ));
          }
        ?>
        <div class="item">
            <div class="bloglist_item"> 
                <div class="blog_image_outer">
                    <a href="<?= get_permalink(); ?>" title="<?= get_the_title(); ?>">
                        <div class="blog_img_warp">
                            <img class="blog_image" src="<?php echo $image_url; ?>" title="<?php echo ucfirst(strtolower($post_image_title)); ?>" alt="<?php echo ucfirst(strtolower($post_image_title)); ?>">
                        </div>
                        <div class="bloglist_date">
                            <div class="detail_date"><?php echo get_the_date('F j Y'); ?></div>
                        </div>
                    </a>
                </div>
                <div class="bloglist_header">
                    <div class="bloglist_title">
                        <a href="<?= get_permalink(); ?>" title="<?= get_the_title(); ?>">
                            <?php echo ucfirst(strtolower($post_title)); ?> 
                        </a>
                    </div>
                </div> 
                <?php /*?><div class="bloglist_content"><?php echo $short_content; ?></div><?php */?>
                <div class="cat-name">
                    <?php 
                        $cats = get_the_category( get_the_ID() );
                        foreach($cats as $cat){
                            echo '<span>'.$cat->name.'</span>';
                        }
                    ?>
                </div>
                <div class="author">
                    <?php /*$authorname = get_the_author_meta('display_name', $author_id); 
                    echo $authorname;*/?>
                </div>
                <div class="bloglist_link btn-style-2">
                    <a href="<?php  the_permalink(); ?>" title="Read More" class="bloglist_readmore vc_btn3">Read more</a>
                </div>
            </div>
        </div>
       <?php $acount++; endwhile; ?>
       </div>
       </div>
        <?php if($published_posts > 5){?>
            <button id="load_more">Load More</button>
        <?php }?>
     <?php else: ?>
        <p class="no-post-data"><?php _e('Sorry, no posts matched your criteria.'); ?></p>
    <?php endif; ?>
<?php 
}

1.2 script ajax
</script>
/********* Loadmore Blog Section Start *********/
add_action( 'wp_footer', 'my_action_javascript' ); 
function my_action_javascript() { ?>
    <script type="text/javascript">
    var ajaxurl = "<?php echo home_url(); ?>/wp-admin/admin-ajax.php"; 
    jQuery(document).on('click','#load_more',function() { 
        // jQuery('#response').append( 
        //     '<div class="loadingdata11"><img src="/wp-content/uploads/2021/10/preview.gif" style="width:100px;height:100px;"></div>' 
        // ); 
        jQuery("#load_more").hide(); 
        var allcountevent = jQuery('#allcountevent').val(); 
        var eventid = jQuery('#eventid').val(); 
        var page = 2;
        jQuery.ajax({ 
            url: ajaxurl, 
            method: 'post', 
            data: { 
                'action': 'my_action', 
                'page' : page 
            }, 
            success: function(data) { 
                //jQuery(".loadingdata11").hide(); 
                jQuery("#load_more").show(); 
                jQuery('.blog-post-listing').append(data); 
                var newval = parseInt(eventid) + parseInt(5); 
                if (newval >= allcountevent) { 
                    jQuery("#load_more").hide(); 
                } 
                jQuery('#eventid').val(newval); 
            } 
        }); 
        return false; 
    });
</script>
<?php }

add_action( 'wp_ajax_my_action', 'my_action' );
function my_action() {
    $query_args = array(
    'post_type' => 'post',
    'paged' => $_POST['page'],
    'posts_per_page' => 5
);
$the_query = new WP_Query( $query_args ); 
     if ( $the_query->have_posts() ) : 
        $acount = 1;
         while ( $the_query->have_posts() ) : $the_query->the_post(); 
	<h2><li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li></h2>
	//copy of while inner code
          <?php $acount++; endwhile; 
         wp_reset_postdata(); 
     else : ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php endif; 
wp_die(); 
}
/********* Loadmore Blog Section End *********/



DREAM COURT TABBING AND OUR WORK PAGE 

Ourwork.php

<?php
/* Template Name: Our Works **/
get_header();
?>
<div class="ourwork_list_filter row page_or_work">
	<div class="col-md-12 wrap">
		<div class="Filter_DV">
			<ul>
				<li data-id="0" class="filter_li active">
					<div class="sec_title">All</div>
				</li>
				<?php
				$terms = get_terms(array(

					'taxonomy' => 'ourwork_category',

					'hide_empty' => false,

				));
				foreach ($terms as $cat) {
				?>
					<li data-id="<?php echo $cat->term_id; ?>" class="filter_li <?php echo $cat->slug; ?>">
						<div class="sec_title"><?php echo $cat->name; ?></div>
					</li>
				<?php
				}
				?>
			</ul>
		</div>
	</div>
</div>

<div class="ourwork_list page_or_work">
	<div class="ourwork-main-blog">
		<div class="result_response ourwork-main row">
			<?php
			$args = array(
				'post_type' => 'ourworks',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'DESC',
			);
			$posts = get_posts($args);
			foreach ($posts as $post) {
				$feature_image =  wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
			?>
				<div class="col-md-4">
					<div class="post-box" style="background-image: url('<?php echo $feature_image[0] ?>')">
						<div class="hover_div">
							<h4 class="ourwork-title"><?php echo $post->post_title ?></h4>
							<div class="hover_btn btn_style1">
								<a class="vc_btn3" href="<?php echo get_permalink($post->ID); ?>">View Project</a>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>

<div class="content">
	<?php
	$content = the_content(get_the_ID());
	$content = preg_replace("/<img[^>]+\>/i", "", $content);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]>', $content);
	$content = apply_filters('the_content', $content);
	?>
</div>

<script type="text/javascript">
	jQuery(document).on('click', "li.filter_li", function() {
		jQuery(document).find('.Filter_DV ul li').each(function() {
			jQuery(this).removeClass('active');
		});
		var cat_id = jQuery(this).attr('data-id');
		jQuery(this).addClass('active');
		var img_src = '<?php echo site_url() . '/wp-content/uploads/2021/07/Eclipse-0.6s-211px.gif'; ?>';
		var html_obj = '<div class="loadingdata11 sec-head"><img src="' + img_src + '" /></div>';
		jQuery('.result_response').html(html_obj);
		jQuery.ajax({
			type: "POST",
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: {
				action: "search_result",
				cat_id: cat_id,
			},
			//type: filter.attr("method"),
			success: function(data) {
				jQuery('.result_response').html(data); // insert data
			}
		});
		return false;
	});
</script>

<?php
get_footer();

Function.php

add_action('wp_ajax_search_result', 'search_result');
add_action('wp_ajax_nopriv_search_result', 'search_result');
function search_result(){

    if ($_POST['cat_id'] == 0) {
        $args = array(
          'post_type' => 'ourworks',
          'post_status' => 'publish',
          'posts_per_page' => 9,
          'order' => 'DESC',
        );
    }else{
        $tax_array = array(
          array(
              'taxonomy' => 'ourwork_category',
              'field' => 'term_id',
              'terms' => $_POST['cat_id'],
          )
        );  

        $args = array(
            'post_type' => 'ourworks',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'order' => 'DESC',
            'tax_query' => $tax_array,           
        );  
    }

    $posts = get_posts($args);   
    foreach ($posts as $post){
        $feature_image =  wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' ); ?>

        <div class="col-md-4">
            <div class="post-box" style="background-image: url('<?php echo $feature_image[0] ?>')">
            <div class="hover_div">
                <h4 class="ourwork-title"><?php echo $post->post_title ?></h4>
                <div class="hover_btn btn_style1">
                    <a class="vc_btn3" href="<?php echo get_permalink($post->ID); ?>">View Project</a></div>   
                </div>
            </div>
        </div> 
<?php       
    }

    wp_reset_postdata();

    die;

}


