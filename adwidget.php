<?php
/*
Plugin Name: M_Publicidad Widgets
Plugin URI: https://github.com/ronaldpatino
Description: Widgets para publicidad con HTML/Javascript e Imagenes
Version: 1.0
Author: Ronald Patino
Author URI: https://github.com/ronaldpatino
*/

add_action('admin_init', array('ADSWidget_Core', 'registerScripts'));
add_action('widgets_init', array('ADSWidget_Core', 'registerWidgets'));
add_action('admin_menu', array('ADSWidget_Core', 'registerAdmin'));

/**
 * This class is the core of Ad Widget
 */
class ADSWidget_Core
{
    CONST VERSION = '2.1.6';
    
    /**
     * The callback used to register the scripts
     */
    static function registerScripts()
    {
        # Include thickbox on widgets page
        if($GLOBALS['pagenow'] == 'widgets.php')
        {
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
            wp_enqueue_script('adwidget-main',  self::getBaseURL().'assets/widgets.js');
        }
    }
    
    /**
     * The callback used to register the widget
     */
    static function registerWidgets()
    {
        register_widget('IMGAdd_Widget');
        register_widget('HTMLAdd_Widget');
    }
    
    /**
     * Get the base URL of the plugin installation
     * @return string the base URL
     */
    public static function getBaseURL()
    {   
        return (get_bloginfo('url') . '/wp-content/plugins/ad-widget/');
    }
    
    /**
     * Register the admin settings page
     */
    static function registerAdmin()
    {
        add_options_page('Ad Widget', 'Ad Widget', 'edit_pages', 'adwidget.php', array(__CLASS__, 'adminMenuCallback'));
    }

    /**
     * The function used by WP to print the admin settings page
     */
    static function adminMenuCallback()
    {
        self::sendInstallReportIfNew();
        include dirname(__FILE__) . '/views/admin.php';
    }
    
    /**
     * Makes a call to the Broadstreet service to collect information information
     *  on the blog in case of errors and other needs.
     */
    public static function sendReport($message = 'General')
    {
        

    }

    /**
     * If this is a new installation and we've never sent a report to the
     * Broadstreet server, send a packet of basic info about this blog in case
     * issues should arise in the future.
     */
    public static function sendInstallReportIfNew()
    {

    }
    
    /**
     * Sets a Wordpress option
     * @param string $name The name of the option to set
     * @param string $value The value of the option to set
     */
    public static function setOption($name, $value)
    {
        if (get_option($name) !== FALSE)
        {
            update_option($name, $value);
        }
        else
        {
            $deprecated = ' ';
            $autoload   = 'yes';
            add_option($name, $value, $deprecated, $autoload);
        }
    }

    /**
     * Gets a Wordpress option
     * @param string    $name The name of the option
     * @param mixed     $default The default value to return if one doesn't exist
     * @return string   The value if the option does exist
     */
    public static function getOption($name, $default = FALSE)
    {
        $value = get_option($name);
        if( $value !== FALSE ) return $value;
        return $default;
    }
}

 /**
 * El widget de la imagen
 */
class IMGAdd_Widget extends WP_Widget
{
    /**
     * Set the widget options
     */
    public function __construct()
     {

         parent::__construct(
             'imgadd_widget', // Base ID
             'M_Publicidad : Banner Imagen', // Name
             array('description' => __('Bloque para publicidad con una Imagen', 'text_domain'),) // Args
         );
     }




     /**
      * Display the widget on the sidebar
      * @param array $args
      * @param array $instance
      */
     function widget($args, $instance)
     {
        extract($args);

        $link   = @$instance['w_link'];
        $img    = @$instance['w_img'];
        $resize = @$instance['w_resize'];

        if($resize == 'yes')
        {
            $resize = "style='width: 100%;'";
        }

        echo $before_widget;

        if(!$img)
        {
            $img  = ADSWidget_Core::getBaseURL() . 'assets/sample-ad.png';
            $link = 'http://adsofthefuture.com';
        }

        echo "<a target='_blank' href='$link' alt='Ad'><img $resize src='$img' alt='Ad' /></a>";

        echo $after_widget;
     }

     /**
      * Update the widget info from the admin panel
      * @param array $new_instance
      * @param array $old_instance
      * @return array
      */
     function update($new_instance, $old_instance)
     {
        $instance = $old_instance;

        $instance['w_link']    = $new_instance['w_link'];
        $instance['w_img']     = $new_instance['w_img'];
        $instance['w_resize']  = $new_instance['w_resize'];

        return $instance;
     }

     /**
      * Display the widget update form
      * @param array $instance
      */
     function form($instance)
     {
        $link_id = $this->get_field_id('w_link');
        $img_id = $this->get_field_id('w_img');

        $defaults = array('w_link' => get_bloginfo('url'), 'w_img' => '', 'w_resize' => 'no');

		$instance = wp_parse_args((array) $instance, $defaults);

        $img = $instance['w_img'];
        $link = $instance['w_link'];

       ?>
        <div class="widget-content">
       <p style="text-align: center;" class="bs-proof">
           <?php if($instance['w_img']): ?>
                Your ad is ready.
                <br/><br/><strong>Scaled Visual:</strong><br/>
                <div class="bs-proof"><img style="width:100%;" src="<?php echo $instance['w_img'] ?>" alt="Ad" /></div>
           <?php else: ?>
                <a href="#" class="upload-button" rel="<?php echo $img_id ?>">Subir una nueva imagen.</a>
           <?php endif; ?>
       </p>
        <label for="<?php echo $this->get_field_id('w_img'); ?>">URL de la imagen:</label><br/>
        <input class="widefat tag" placeholder="URL de la imagen" type="text" id="<?php echo $img_id; ?>" name="<?php echo $this->get_field_name('w_img'); ?>" value="<?php echo htmlentities($instance['w_img']); ?>" />
       <br/><br/>
       <p>
            <label for="<?php echo $this->get_field_id('w_link'); ?>">Enlace del Click:</label><br/>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('w_link'); ?>" name="<?php echo $this->get_field_name('w_link'); ?>" value="<?php echo $instance['w_link']; ?>" />
        </p>
       <p>
           <label for="<?php echo $this->get_field_id('w_resize'); ?>">Ancho M&aacute;ximo por defecto? </label>
           <input type="checkbox" name="<?php echo $this->get_field_name('w_resize'); ?>" value="yes"  <?php if($instance['w_resize'] == 'yes') echo 'checked'; ?> />
       </p>

        </div>
       <?php
     }
}


/***
 * Widget con html
 */
class HTMLAdd_Widget extends WP_Widget
{
    /**
     * Set the widget options
     */
    public function __construct()
    {
        parent::__construct(
            'htmladd_widget', // Base ID
            'M_Publicidad: HTML o Javascript', // Name
            array('description' => __('Bloque para publicidad HTML o Javascript', 'text_domain'),) // Args
        );

    }

    /**
     * Display the widget on the sidebar
     * @param array $args
     * @param array $instance
     */
    function widget($args, $instance)
    {
        extract($args);

        echo $before_widget;

        echo "<div style='text-align: center;'>{$instance['w_adcode']}</div>";

        echo $after_widget;
    }

    /**
     * Update the widget info from the admin panel
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['w_adcode'] = $new_instance['w_adcode'];

        return $instance;
    }

    /**
     * Display the widget update form
     * @param array $instance
     */
    function form($instance)
    {

        $defaults = array('w_adcode' => '');
        $instance = wp_parse_args((array)$instance, $defaults);

        $form  = '<div class="widget-content">';
        $form .= '<p>Coloque su c&oacute;digo HTML O JS .</p>';
        $form .= '<p>';
        $form .= '<label for="' . $this->get_field_id('w_adcode') . '">C&oacute;digo de Publicidad</label>';
        $form .= '<textarea style="height: 100px;" class="widefat" id="' . $this->get_field_id('w_adcode') . '"';
        $form .= 'name="' . $this->get_field_name('w_adcode') . '">' . $instance['w_adcode'] . '</textarea>';
        $form .= '</p>';
        $form .= '</div>';
        echo $form;
    }
}




