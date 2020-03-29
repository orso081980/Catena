<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              http://example.com
* @since             1.0.0
* @package           top-list
*
* @wordpress-plugin
* Plugin Name:       Top List Plugin
* Description:       A plugin to sort brands ordering them by Rating
* Version:           1.0.0
* Author:            Marco Maffei
* Author URI:        marcointhemiddle.com
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'WPINC' ) ) die;
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'TopListMarco' ) ) {

    class TopListMarco {

        protected $brand_table;
        protected $rating_table;
        protected $wpdb;

        public function __construct() {

            include 'style.php';
            global $wpdb;
            define('ROOTDIR', plugin_dir_path(__FILE__));

            register_activation_hook(__FILE__, array($this ,'TopListMarco_install') );
            register_deactivation_hook( __FILE__, array($this ,'TopListMarco_remove') );
            add_action('admin_enqueue_scripts', array( $this,'TopListScript') );
            add_action('admin_menu', array( $this,'top_list_menu') );
            add_shortcode('top_list_render', array( $this,'top_list_render') );
            add_action( 'rest_api_init',  array( $this,'my_register_route' ) );
            
            $this->wpdb = $wpdb;
            $this->brand_table = $this->wpdb->prefix . "top_list_brand";
            $this->rating_table = $this->wpdb->prefix . "top_list_rating";

        }


        public function TopListScript() {

            wp_register_script('javascript-file', plugins_url('js/main.js' , __FILE__ ), '', '', true );
            wp_enqueue_script('javascript-file');

        }

        public function TopListMarco_install() {

            $sql = [];
            $sql[] = "CREATE TABLE $this->brand_table (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;";
            $sql[] = "CREATE TABLE $this->rating_table (
            `brand_id` int(11) NOT NULL AUTO_INCREMENT,
            `rating` int(11) NOT NULL,
            PRIMARY KEY (`brand_id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;";

            $sql[] = "INSERT INTO $this->brand_table (`id`, `name`) VALUES
            (1, 'casumo'),
            (2, 'leovegas'),
            (3, 'casinoeuro');";

            $sql[] = "INSERT INTO $this->rating_table (`brand_id`, `rating`) VALUES
            (1, 3),
            (2, 5),
            (3, 4);";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);

        }

        public function TopListMarco_remove() {

            $sql = "DROP TABLE IF EXISTS $this->brand_table, $this->rating_table";
            $this->wpdb->query($sql);

        }

        public function top_list_menu() {

            add_menu_page('Top List',
                'Top List Crud',
                'manage_options',
                'top_list_list',
                array($this, 'top_list_list')
            );

            add_submenu_page('top_list_list',
                'Add New School',
                'Add New',
                'manage_options',
                'top_list_create',
                array($this, 'top_list_create'));

            add_submenu_page(null,
                'Update School',
                'Update',
                'manage_options',
                'top_list_update',
                array($this, 'top_list_update'));

        }

        public function top_list_list() {

            $id = $_POST["delete"];
            $rows = $this->wpdb->get_results("SELECT *
                FROM $this->brand_table
                INNER JOIN $this->rating_table ON $this->brand_table.`id` = $this->rating_table.`brand_id`");
            if (isset($_POST['delete'])) {
                $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->brand_table WHERE id = %d", $id));
                $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->rating_table WHERE brand_id = %d", $id));
                require_once(ROOTDIR . 'top_list_list.php');
            }
            require_once(ROOTDIR . 'top_list_list.php');

        }

        public function top_list_create() {

            $name = isset($_POST["name"]) ? $name = $_POST["name"] : '';
            $rating = isset($_POST["rating"]) ? $rating = $_POST["rating"] : '';

            if (isset($_POST['insert'])) {

                if ($rating > 5 || !is_numeric($rating) || is_numeric($name)) {
                    $wrong = true;
                } 
                $exists = $this->wpdb->get_var( $this->wpdb->prepare(
                    "SELECT COUNT(*) FROM $this->brand_table WHERE name = %s", $name
                ) );

                if ( ! $exists && ! $wrong ) {

                    $this->wpdb->insert(
                        $this->brand_table,
                        array(
                            'name' => $name,
                        ),
                        array('%s')
                    );

                    $this->wpdb->insert(
                        $this->rating_table,
                        array(
                            'rating'  => $rating,
                        ),
                        array('%d')
                    );

                    $message = "Record inserted";

                }

            }
            require_once(ROOTDIR . 'top_list_create.php');

        }

        public function top_list_update() {

            $id = $_GET["id"];
            $name = isset($_POST["name"]) ? $name = $_POST["name"] : '';
            $rating = isset($_POST["rating"]) ? $rating = $_POST["rating"] : '';

            if (isset($_POST['update']) && $rating <= 5 && is_numeric($rating) && !is_numeric($name)) {
                $this->wpdb->update(
                    $this->brand_table,
                    array(
                        'name' => $name,
                    ),
                    array('id' => $id)
                );

                $this->wpdb->update(
                    $this->rating_table,
                    array(
                        'rating'  => $rating,
                    ),
                    array('brand_id' => $id)
                );

            } else if (isset($_POST['update']) && $rating > 5 ) {
                $wrong = true;
            } else if (isset($_POST['update']) && !is_numeric($rating) ) {
                $wrong = true;
            } else if (isset($_POST['update']) && is_numeric($name) ) {
                $wrong = true;
            } else if (isset($_POST['delete'])) {
                $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->brand_table WHERE id = %d", $id));
                $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->rating_table WHERE brand_id = %d", $id));
            } else {
                $locations = $this->wpdb->get_results($this->wpdb->prepare("SELECT *
                    FROM $this->brand_table
                    INNER JOIN $this->rating_table ON $this->brand_table.`id` = $this->rating_table.`brand_id` where id=%d", $id));

                foreach ($locations as $s) {
                    $name = $s->name;
                    $rating = $s->rating;
                }

            }

            require_once(ROOTDIR . 'top_list_update.php');
        }


        public function top_list_render() {

            wp_enqueue_style( 'bootstrap', plugins_url('css/bootstrap.min.css', __FILE__ ) );
            wp_enqueue_style( 'style', plugins_url('css/style.css', __FILE__ ) );

            $rows = $this->wpdb->get_results("SELECT *
                FROM $this->brand_table
                INNER JOIN $this->rating_table ON $this->brand_table.`id` = $this->rating_table.`brand_id` ORDER BY `rating` DESC");

            $table = [];

            foreach ($rows as $value ) {

                $table[] = array(
                    'name' => $value->name,
                    'rating' => $value->rating
                );


            }

            $atts = shortcode_atts( array(
                'data'=>'0'
            ) , $atts);

            $content =  (empty($content))? " " : $content;

            extract($atts);
            ob_start();

            if ( is_page( array( 'freespins', 'casinobonuses' ) ) ) {
                include( dirname(__FILE__) . '/top_list_render.php' );
            }

            return ob_get_clean();
        }


        public function my_top_list_get( $request ) {

            $rows = $this->wpdb->get_results("SELECT *
                FROM $this->brand_table
                INNER JOIN $this->rating_table ON $this->brand_table.`id` = $this->rating_table.`brand_id` ORDER BY `rating` DESC");
            $post_data = array();

            foreach ($rows as $value ) {

                $post_data[] = array(
                    'id' => $value->id,
                    'name' => $value->name,
                    'rating' => $value->rating,
                    'brand_id' => $value->brand_id
                );

            }

            return rest_ensure_response( $post_data );
        }


        public function my_top_list_post( $request ) {

            $response['id'] = $request['id'];
            $response['name'] = $request['name'];
            $response['rating'] = $request['rating'];
            $response['brand_id'] = $request['brand_id'];

            if ($response['rating'] > 5 || !is_numeric($response['rating']) || is_numeric($response['name'])) {
                $wrong = true;
            }

            $exists = $this->wpdb->get_var( $this->wpdb->prepare(
                "SELECT COUNT(*) FROM $this->brand_table WHERE name = %s", $response['name']
            ) );

            if ( ! $exists && ! $wrong ) {

                $this->wpdb->insert(
                    $this->brand_table,
                    array(
                        'name' => $response['name'],
                    ),
                    array('%s')
                );

                $this->wpdb->insert(
                    $this->rating_table,
                    array(
                        'rating'  => $response['rating'],
                    ),
                    array('%d')
                );
                $res = new WP_REST_Response($response, 200);
                return [$res];
            } else {
                $res = new WP_REST_Response('something get wrong');
                return [$res];
            }  
            
        }

        public function my_top_list_delete( $request ) {

            $response['id'] = $request['id'];
            $response['name'] = $request['name'];
            $response['rating'] = $request['rating'];
            $response['brand_id'] = $request['brand_id'];

            $this->wpdb->delete(
                $this->brand_table,
                array( 'id' => $response['id'] ),
                array('%d')
            );

            $this->wpdb->delete(
                $this->rating_table,
                array( 'brand_id' => $response['brand_id'] ),
                array('%d')
            );

            $res = new WP_REST_Response($response, 200);
            return [$res];
        }

        public function my_register_route() {

            register_rest_route( 'top-list-route', 'my-top-list-get', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => array($this, 'my_top_list_get'),
                    'permission_callback' => function() {
                        return current_user_can( 'edit_posts' );
                    },
                ),
                array(
                    'methods'  => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'my_top_list_post'),
                    'permission_callback' => function() {
                        return current_user_can( 'edit_posts' );
                    },
                ),
                array(
                    'methods'  => WP_REST_Server::DELETABLE,
                    'callback' => array($this, 'my_top_list_delete'),
                    'permission_callback' => function() {
                        return current_user_can( 'edit_posts' );
                    },
                ),
            ) );
        }

    }

}

global $TopListMarco;
$TopListMarco = new TopListMarco();
