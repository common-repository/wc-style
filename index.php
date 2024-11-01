<?php
/*
  Plugin Name: Change Cart button Colors WooCommerce
  Description: WooCommerce Change Add to Cart button Color, size and styles
  Version: 1.0
  Author: ThemeLocation
  Author URI: https://www.themelocation.com
 */


if(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
add_action('admin_enqueue_scripts', 'WcsEnqueueStyleScript');


/**
 * Returns a select list of Google fonts
 * Feel free to edit this, update the fallbacks, etc.
 */

function WcsOptionsTypographyGetGoogleFonts() {
    // Google Font Defaults
    $google_faces = array(
        'Arvo, serif' => 'Arvo',
        'Copse, sans-serif' => 'Copse',
        'Droid Sans, sans-serif' => 'Droid Sans',
        'Droid Serif, serif' => 'Droid Serif',
        'Lobster, cursive' => 'Lobster',
        'Nobile, sans-serif' => 'Nobile',
        'Open Sans, sans-serif' => 'Open Sans',
        'Oswald, sans-serif' => 'Oswald',
        'Pacifico, cursive' => 'Pacifico',
        'Rokkitt, serif' => 'Rokkit',
        'PT Sans, sans-serif' => 'PT Sans',
        'Quattrocento, serif' => 'Quattrocento',
        'Raleway, cursive' => 'Raleway',
        'Ubuntu, sans-serif' => 'Ubuntu',
        'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
    );
    return $google_faces;
}




function WcsEnqueueStyleScript($hook_suffix) {

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('jquery-ui-tabs');
   wp_enqueue_script('wc-style-js', plugin_dir_url( __FILE__ ) . 'js/wc-style.js', array('wp-color-picker', 'jquery-ui-tabs'), false, true);

    

    wp_enqueue_style('wc-style-css', plugin_dir_url( __FILE__ ) . 'css/wc-style.css');
}


 add_action('wp_enqueue_scripts', 'wcsEnqueueStyle');

  function wcsEnqueueStyle() {
    if (is_shop()) {
        wp_enqueue_style('wcs-shop', plugin_dir_url(__FILE__) . 'templates/wcs-shop.css');
    }
    if (is_product()) {
        wp_enqueue_style('wcs-product', plugin_dir_url(__FILE__) . 'templates/wcs-product.css');
    }
    if (is_cart()) {
        wp_enqueue_style('wcs-cart', plugin_dir_url(__FILE__) . 'templates/wcs-cart.css');
    }
    if (is_checkout()) {
        wp_enqueue_style('wcs-cart', plugin_dir_url(__FILE__) . 'templates/wcs-checkout.css');
    }
    }





function wcsSettingsHtml($post) {
    $postid = $post->ID;
    ?>
    <table style="width: 100%">
        <tr>
            <td><div class="wcsTabs">
                    <ul class="wcsTabsUl">
                        <li><a href="#tabs-1">Shop Page</a></li>
                        <li><a href="#tabs-2">Product Page</a></li>
                        <li><a href="#tabs-3">cart Page</a></li>
                        <li><a href="#tabs-4">checkout Page</a></li>
                        <li><a href="#tabs-5">Enable/Disable</a></li>
                    </ul>
                    <div class="wcsTab" id="tabs-1">
                        <div class="wcsTabInner">
                            <?php
                            $shopStyle = (array) json_decode(get_post_meta($postid, 'shop', true));
                            ?>
                            <table>
                                <tr>
                                    <td>Text Color</td>
                                    <td><input type="text" value="<?php echo $shopStyle['shopBtn_color'] ?>" class="wcsColorPicker" name="shopBtn_color" /></td>
                                </tr>
                                <tr>
                                    <td>Text Size</td>
                                    <td><input type="text" value="<?php echo $shopStyle['shoptxt_size'] ?>" name="shoptxt_size" /></td>
                                </tr>
                                <tr>
                                    <td>Button Color</td>
                                    <td><input type="text" value="<?php echo $shopStyle['shopBtn_background=color'] ?>" class="wcsColorPicker" name="shopBtn_background=color" /></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="wcsTab" id="tabs-2">
                        <div class="wcsTabInner">
                            Product Page
                        </div>
                    </div>
                    <div class="wcsTab" id="tabs-3">
                        <div class="wcsTabInner">
                            cart Page
                        </div>
                    </div>
                    <div class="wcsTab" id="tabs-4">
                        <div class="wcsTabInner">
                            checkout Page
                        </div>
                    </div>
                    <div class="wcsTab" id="tabs-5">
                        <div class="wcsTabInner">
                            <?php
                            $wcsonoff = get_post_meta($postid, 'wcsonoff', true);
                            $on;
                            $off;
                            if ($wcsonoff == 'on') {
                                $on = 'checked="checked"';
                            } else {
                                $off = 'checked="checked"';
                            }
                            ?>
                            <label class="wcsOnOffLabel"><input type="radio" <?php echo $on ?> class="wcsonoff" name="wcsonoff" value="on">On</label>
                            <label class="wcsOnOffLabel"><input type="radio" <?php echo $off ?> class="wcsonoff" name="wcsonoff" value="off">Off</label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <?php
}

add_action('save_post', 'wcsSaveProfile');

function wcsSaveProfile($id) {
    if ($_POST) {
        $shopArr = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'shop') !== FALSE) {
                $shopArr[$key] = $value;
            }
        }
        update_post_meta($id, 'shop', sanitize_text_field(json_encode($shopArr)));
        update_post_meta($id, 'wcsonoff', sanitize_text_field($_POST['wcsonoff']));
    }
}

/* * ***************************************************************************** */

add_action('admin_menu', 'WcsMenu');

function WcsMenu() {
    add_menu_page('WC Style', 'WC Style', 'manage_options', 'wc-style', 'WcsStyle', plugin_dir_url(__FILE__) . '/images/icon.png');
}




function WcsStyle() {
    
    ?>
    <form action="<?php echo admin_url( 'admin.php' ); ?>" method="post">
    <input type="hidden" name="action" value="wcs10500" />
        <table class="wcsSettingsWrap">
            <tr>
                <td>
                    <div class="wcsTabs">
                        <ul class="wcsTabsUl">
                            <li><a href="#tabs-1">Shop Page</a></li>
                            <li><a href="#tabs-2">Product Page</a></li>
                            <li><a href="#tabs-3">cart Page</a></li>
                            <li><a href="#tabs-4">checkout Page</a></li>
                            <li style="display: none"><a href="#tabs-5">Enable/Disable</a></li>
                        </ul>
                        <div class="wcsTab" id="tabs-1">
                            <div class="wcsTabInner">
    <?php
    $shopStyle = (array) json_decode(get_option('shop'));
    ?>
                                <table>
                                    <tr>
                                        <td>Text Color</td>
                                        <td><input type="text" value="<?php echo $shopStyle['shopBtn_color'] ?>" class="wcsColorPicker" name="shopBtn_color" /></td>
                                    </tr>
                                    <tr>
                                    <td>Text Size</td>
                                    <td><input type="text" value="<?php echo $size = ($shopStyle['shoptxt_size'] == "" ? 14 : $shopStyle['shoptxt_size']); ?>" name="shoptxt_size" class="wcs_txt_size" /><span class="wcs_txt_px">px</span></td>
                                    </tr>
                                    <tr>
                                    <td>Text Style</td>
                                    <td>
                                        <select name="shop_google_font" id="shop_google_font">

                                        <?php 
                                        if($shopStyle['shop_google_font'] == ''){
                                            ?>
                                            <option value="">Choose Style</option>

                                            <?php
                                            }
                                            else
                                            {

                                            ?>

                                            <option value="<?php echo $shopStyle['shop_google_font']; ?>"><?php echo $shopStyle['shop_google_font']; ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                       
                                         foreach (WcsOptionsTypographyGetGoogleFonts() as $key => $val)  {
                                        ?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                        <?php
                                        
                                        }
                                        ?>
                                        </select>

                                    </td>
                                        
                                    
                                </tr>
                                    <tr>
                                        <td>Button Color</td>
                                        <td><input type="text" value="<?php echo $shopStyle['shopBtn_background=color'] ?>" class="wcsColorPicker" name="shopBtn_background=color" /></td>
                                    </tr>
                                </table>
                            </div>
                            <p><b>Note: These settings will be implemented All WooCommerce Pages and Buttons.</b></p>
                        </div>
                        <div class="wcsTab" id="tabs-2">
                            <div class="wcsTabInner">
    <?php
    $productStyle = (array) json_decode(get_option('product'));
    ?>
                                <table>
                                    <tr>
                                        <td>Text Color</td>
                                        <td><input type="text" value="<?php echo $productStyle['productBtn_color'] ?>" class="wcsColorPicker" name="productBtn_color" /></td>
                                    </tr>
                                    <tr>
                                    <td>Text Size</td>
                                    <td><input type="text" value="<?php echo $size = ($productStyle['producttxt_size'] == "" ? 14 : $productStyle['producttxt_size']); ?>" name="producttxt_size" class="wcs_txt_size" /><span class="wcs_txt_px">px</span></td>
                                </tr>

                                <tr>
                                    <td>Text Style</td>
                                    <td>
                                        <select name="product_google_font" id="product_google_font">

                                        <?php 
                                        if($productStyle['product_google_font'] == ''){
                                            ?>
                                            <option value="">Choose Style</option>

                                            <?php
                                            }
                                            else
                                            {

                                            ?>

                                            <option value="<?php echo $productStyle['product_google_font']; ?>"><?php echo $productStyle['product_google_font']; ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        
                                         foreach (WcsOptionsTypographyGetGoogleFonts() as $key => $val)  {
                                        ?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                        <?php
                                         
                                        }
                                        ?>
                                        </select>

                                    </td>
                                        
                                    
                                </tr>
                                    <tr>
                                        <td>Button Color</td>
                                        <td><input type="text" value="<?php echo $productStyle['productBtn_background=color'] ?>" class="wcsColorPicker" name="productBtn_background=color" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="wcsTab" id="tabs-3">
                            <div class="wcsTabInner">
    <?php
    $caartStyle = (array) json_decode(get_option('cart'));
    ?>
                                <table>
                                    <tr>
                                        <td>Text Color</td>
                                        <td><input type="text" value="<?php echo $caartStyle['cartBtn_color'] ?>" class="wcsColorPicker" name="cartBtn_color" /></td>
                                    </tr>
                                    <tr>
                                    <td>Text Size</td>
                                    <td><input type="text" value="<?php echo $size = ($caartStyle['carttxt_size'] == "" ? 14 : $caartStyle['carttxt_size']);?>" name="carttxt_size" class="wcs_txt_size" /><span class="wcs_txt_px">px</span></td>
                                </tr>

                                <tr>
                                    <td>Text Style</td>
                                    <td>
                                        <select name="cart_google_font" id="cart_google_font">

                                        <?php 
                                        if($caartStyle['cart_google_font'] == ''){
                                            ?>
                                            <option value="">Choose Style</option>

                                            <?php
                                            }
                                            else
                                            {

                                            ?>

                                            <option value="<?php echo $caartStyle['cart_google_font']; ?>"><?php echo $caartStyle['cart_google_font']; ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                       
                                         foreach (WcsOptionsTypographyGetGoogleFonts() as $key => $val)  {
                                        ?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                        <?php
                                         
                                        }
                                        ?>
                                        </select>

                                    </td>
                                        
                                    
                                </tr>

                                    <tr>
                                        <td>Button Color</td>
                                        <td><input type="text" value="<?php echo $caartStyle['cartBtn_background=color'] ?>" class="wcsColorPicker" name="cartBtn_background=color" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="wcsTab" id="tabs-4">
                           <div class="wcsTabInner">
    <?php
    $checkoutStyle = (array) json_decode(get_option('checkout'));
    ?>
                                <table>
                                    <tr>
                                        <td>Text Color</td>
                                        <td><input type="text" value="<?php echo $checkoutStyle['checkoutBtn_color'] ?>" class="wcsColorPicker" name="checkoutBtn_color" /></td>
                                    </tr>
                                    <tr>
                                    <td>Text Size</td>
                                    <td><input type="text" value="<?php echo $size = ($checkoutStyle['checkouttxt_size'] == "" ? 14 : $checkoutStyle['checkouttxt_size']); ?>" name="checkouttxt_size" class="wcs_txt_size" /><span class="wcs_txt_px">px</span></td>
                                </tr>

                                <tr>
                                    <td>Text Style</td>
                                    <td>
                                        <select name="checkout_google_font" id="checkout_google_font">

                                        <?php 
                                        if($checkoutStyle['checkout_google_font'] == ''){
                                            ?>
                                            <option value="">Choose Style</option>

                                            <?php
                                            }
                                            else
                                            {

                                            ?>

                                            <option value="<?php echo $checkoutStyle['checkout_google_font']; ?>"><?php echo $checkoutStyle['checkout_google_font']; ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        
                                         foreach (WcsOptionsTypographyGetGoogleFonts() as $key => $val)  {
                                        ?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                        <?php
                                        
                                        }
                                        ?>
                                        </select>

                                    </td>
                                        
                                    
                                </tr>

                                    <tr>
                                        <td>Button Color</td>
                                        <td><input type="text" value="<?php echo $checkoutStyle['checkoutBtn_background=color'] ?>" class="wcsColorPicker" name="checkoutBtn_background=color" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="wcsTab" id="tabs-5" style="display: none">
                            <div class="wcsTabInner">
    <?php
    $wcsonoff = get_post_meta($postid, 'wcsonoff', true);
    $on;
    $off;
    if ($wcsonoff == 'on') {
        $on = 'checked="checked"';
    } else {
        $off = 'checked="checked"';
    }
    ?>
                                <label class="wcsOnOffLabel"><input type="radio" <?php echo $on ?> class="wcsonoff" name="wcsonoff" value="on">On</label>
                                <label class="wcsOnOffLabel"><input type="radio" <?php echo $off ?> class="wcsonoff" name="wcsonoff" value="off">Off</label>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr style="text-align: right">
                <td>

                    <input type="submit" value="Save" name="submit" class="button-primary"/>
                </td>
            </tr>
        </table>
    </form>
    <?php
}

//add_action('wp_loaded', 'wcsWriteCSS');


add_action( 'admin_action_wcs10500', 'wcs10500AdminAction' );


function wcs10500AdminAction() {


    if ($_POST) {
 $shopArr = array();

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'shop') !== FALSE) {
                $shopArr[$key] = $value;
            }
        }
        
        update_option('shop', sanitize_text_field(json_encode($shopArr)));
        /**/
        $productArr = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'product') !== FALSE) {
                $productArr[$key] = $value;
            }
        }
        update_option('product', sanitize_text_field(json_encode($productArr)));
        /**/
         $cartArr = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'cart') !== FALSE) {
                $cartArr[$key] = $value;
            }
        }
        update_option('cart', sanitize_text_field(json_encode($cartArr)));
        
         /**/
         $checkoutArr = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'checkout') !== FALSE) {
                $checkoutArr[$key] = $value;
            }
        }
        update_option('checkout', sanitize_text_field(json_encode($checkoutArr)));
        /**/
        update_option('wcsonoff', $_POST['wcsonoff']);
    }


    /*     * *****Shop Page****** */
    $shopStyle = (array) json_decode(get_option('shop'));

    
    



     $style = '';

    if(!empty($shopStyle['shop_google_font'])){

    $font = explode(',', $shopStyle['shop_google_font']);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);
    
    $style .= "@import url('https://fonts.googleapis.com/css?family=$font');";

    $style .='.woocommerce a.button{';
    
    $style .='font-family:' . $shopStyle['shop_google_font'] . ' !important;';

    }
    else{

        $style .='.woocommerce a.button{';
    }
    

    
    $style .='color:' . $shopStyle["shopBtn_color"] . ' !important;';
    $style .='background-color:' . $shopStyle["shopBtn_background=color"] . ' !important;';
 
    


    if($shopStyle["shoptxt_size"] == ""){
        $style .='font-size: 14px !important;';
    }
    else{
        $style .='font-size:' . $shopStyle["shoptxt_size"] . 'px !important;';
    }

    $style .='}';



    if(!empty($shopStyle['shop_google_font'])){

    $font = explode(',', $shopStyle['shop_google_font']);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);
    
    $style .= "@import url('https://fonts.googleapis.com/css?family=$font');";

    $style .='.woocommerce a.added_to_cart{';
    
    $style .='font-family:' . $shopStyle['shop_google_font'] . ' !important;';

    }
    else{

        $style .='.woocommerce a.added_to_cart{';
    }
    

    
    $style .='color:' . $shopStyle["shopBtn_color"] . ' !important;';
    $style .='background-color:' . $shopStyle["shopBtn_background=color"] . ' !important;';
 
    


    if($shopStyle["shoptxt_size"] == ""){
        $style .='font-size: 14px !important;';
    }
    else{
        $style .='font-size:' . $shopStyle["shoptxt_size"] . 'px !important;';
    }

    $style .='}';

  

    $shopfilePath = plugin_dir_path( __FILE__ ) . '/templates/wcs-shop.css';
    $myfileshop = fopen($shopfilePath, "w") or die("Unable to open file!");
    fwrite($myfileshop, $style);
    fclose($myfileshop);

    /* Product Page */

    $prorductStyle = (array) json_decode(get_option('product'));
    $style = '';

     if(!empty($prorductStyle['product_google_font']) || !empty($shopStyle['shop_google_font'])){

     	if(!empty($prorductStyle['product_google_font'])){
     		$font_family = $prorductStyle['product_google_font'];
     	}
     	else{
     		$font_family = $shopStyle['shop_google_font'];
     	}
     	

    $font = explode(',', $font_family);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);
    
    $style .= "@import url('https://fonts.googleapis.com/css?family=$font');";

    $style .='.woocommerce button.button.alt{';
    
    $style .='font-family:' . $font_family . ' !important;';

    }
    else{
        $style .='.woocommerce button.button.alt{';
    }

    
    if(!empty($shopStyle["shopBtn_color"]) || !empty($prorductStyle["productBtn_color"])){

    	if(!empty($prorductStyle["productBtn_color"])){
    		$color = $prorductStyle["productBtn_color"];
    	}
    	else{
    		$color = $shopStyle["shopBtn_color"];
    	}
    		
    	 $style .='color:' . $color . ' !important;';
    }


    if(!empty($shopStyle["shopBtn_background=color"]) || !empty($prorductStyle["productBtn_background=color"])){

    	if(!empty($prorductStyle["productBtn_background=color"])){
    		$bgcolor = $prorductStyle["productBtn_background=color"];
    	}
    	else{
    		$bgcolor = $shopStyle["shopBtn_background=color"];
    	}
    		
    	 $style .='background-color:' . $bgcolor . ' !important;';
    }
   
    

    if($prorductStyle["producttxt_size"] == "" && $shopStyle["shoptxt_size"] == "" ){
        $style .='font-size: 14px !important;';
    }
    else{
    	if($prorductStyle["producttxt_size"] == ""){
    		 $style .='font-size:' . $shopStyle["shoptxt_size"] . 'px !important;';
    	}
    	else{
    		 $style .='font-size:' . $prorductStyle["producttxt_size"] . 'px !important;';
    	}
       
    }

    $style .='}';

    

    $productfilePath = plugin_dir_path( __FILE__ ) . '/templates/wcs-product.css';

    $myfileproduct = fopen($productfilePath, "w") or die("Unable to open file!");
    fwrite($myfileproduct, $style);
    fclose($myfileproduct);
    
    
    /* cart Page */

    $cartStyle = (array) json_decode(get_option('cart'));
    $style = '';


    if(!empty($cartStyle['cart_google_font']) || !empty($shopStyle['shop_google_font'])){

    	if(!empty($cartStyle['cart_google_font'])){
     		$font_family = $cartStyle['cart_google_font'];
     	}
     	else{
     		$font_family = $shopStyle['shop_google_font'];
     	}


    $font = explode(',', $font_family);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);
    
    $style .= "@import url('https://fonts.googleapis.com/css?family=$font');";

   $style .='.woocommerce a.button.alt{';
    
    $style .='font-family:' . $font_family . ' !important;';

    }
    else{
        $style .='.woocommerce a.button.alt{';
    }

    

    if(!empty($shopStyle["shopBtn_color"]) || !empty($cartStyle["cartBtn_color"])){
    	if(!empty($cartStyle["cartBtn_color"])){
    		$style .='color:' . $cartStyle["cartBtn_color"] . ' !important;';
    	}
    	else{
    		$style .='color:' . $shopStyle["shopBtn_color"] . ' !important;';
    	}
    	
    }
    
    if(!empty($cartStyle["cartBtn_background=color"]) || !empty($shopStyle["shopBtn_background=color"])){
    	if(!empty($cartStyle["cartBtn_background=color"])){
    		$style .='background-color:' . $cartStyle["cartBtn_background=color"] . ' !important;';
    	}
    	else{
    		$style .='background-color:' . $shopStyle["shopBtn_background=color"] . ' !important;';
    	}
    	
    }
    
    
    if($cartStyle["carttxt_size"] == ""){
        $style .='font-size: 14px !important;';
    }
    else{
        $style .='font-size:' . $cartStyle["carttxt_size"] . 'px !important;';
    }
    $style .='}';

    

    if(!empty($cartStyle['cart_google_font']) || !empty($shopStyle['shop_google_font'])){

        if(!empty($cartStyle['cart_google_font'])){
            $font_family = $cartStyle['cart_google_font'];
        }
        else{
            $font_family = $shopStyle['shop_google_font'];
        }


    $font = explode(',', $font_family);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);

    $style .= 'input[name="update_cart"] {';
    
    $style .='font-family:' . $font_family . ' !important;';

    }
    else{
        $style .='input[name="update_cart"] {';
    }


    if(!empty($shopStyle["shopBtn_color"]) || !empty($cartStyle["cartBtn_color"])){
        if(!empty($cartStyle["cartBtn_color"])){
            $style .='color:' . $cartStyle["cartBtn_color"] . ' !important;';
        }
        else{
            $style .='color:' . $shopStyle["shopBtn_color"] . ' !important;';
        }
        
    }
    
    if(!empty($cartStyle["cartBtn_background=color"]) || !empty($shopStyle["shopBtn_background=color"])){
        if(!empty($cartStyle["cartBtn_background=color"])){
            $style .='background-color:' . $cartStyle["cartBtn_background=color"] . ' !important;';
        }
        else{
            $style .='background-color:' . $shopStyle["shopBtn_background=color"] . ' !important;';
        }
        
    }
    
    
    if($cartStyle["carttxt_size"] == ""){
        $style .='font-size: 14px !important;';
    }
    else{
        $style .='font-size:' . $cartStyle["carttxt_size"] . 'px !important;';
    }
    $style .='}';


    if(!empty($cartStyle['cart_google_font']) || !empty($shopStyle['shop_google_font'])){

        if(!empty($cartStyle['cart_google_font'])){
            $font_family = $cartStyle['cart_google_font'];
        }
        else{
            $font_family = $shopStyle['shop_google_font'];
        }


    $font = explode(',', $font_family);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);

    $style .= 'input[name="apply_coupon"] {';
    
    $style .='font-family:' . $font_family . ' !important;';

    }
    else{
        $style .='input[name="apply_coupon"] {';
    }


    if(!empty($shopStyle["shopBtn_color"]) || !empty($cartStyle["cartBtn_color"])){
        if(!empty($cartStyle["cartBtn_color"])){
            $style .='color:' . $cartStyle["cartBtn_color"] . ' !important;';
        }
        else{
            $style .='color:' . $shopStyle["shopBtn_color"] . ' !important;';
        }
        
    }
    
    if(!empty($cartStyle["cartBtn_background=color"]) || !empty($shopStyle["shopBtn_background=color"])){
        if(!empty($cartStyle["cartBtn_background=color"])){
            $style .='background-color:' . $cartStyle["cartBtn_background=color"] . ' !important;';
        }
        else{
            $style .='background-color:' . $shopStyle["shopBtn_background=color"] . ' !important;';
        }
        
    }
    
    
    if($cartStyle["carttxt_size"] == ""){
        $style .='font-size: 14px !important;';
    }
    else{
        $style .='font-size:' . $cartStyle["carttxt_size"] . 'px !important;';
    }
    $style .='}';



    $cartfilePath = plugin_dir_path( __FILE__ ) . '/templates/wcs-cart.css';

    $cartmyfile = fopen($cartfilePath, "w") or die("Unable to open file!");
    fwrite($cartmyfile, $style);
    fclose($cartmyfile);
    
    /* checkout Page */

    $checkoutStyle = (array) json_decode(get_option('checkout'));
    
    $style = '';

     if(!empty($checkoutStyle['checkout_google_font']) || !empty($shopStyle['shop_google_font'])){
        if(!empty($checkoutStyle['checkout_google_font'])){
            $font_family = $checkoutStyle['checkout_google_font'];
        }else {
            $font_family = $shopStyle['shop_google_font'];
        }
    $font = explode(',', $font_family);
    $font = $font[0];
    $font = str_replace(" ", "+", $font);
    
    $style .= "@import url('https://fonts.googleapis.com/css?family=$font');";

    $style .='.woocommerce input.button.alt{';
    
    $style .='font-family:' . $font_family . ' !important;';

    }
    else{
        $style .='.woocommerce input.button.alt{';
    
    }

    

    if(!empty($shopStyle["shopBtn_color"]) || !empty($checkoutStyle["checkoutBtn_color"])){
        if(!empty($checkoutStyle["checkoutBtn_color"])){
            $style .='color:' . $checkoutStyle["checkoutBtn_color"] . ' !important;';
        }
        else{
            $style .='color:' . $shopStyle["shopBtn_color"] . ' !important;';
        }
        
    }


if(!empty($checkoutStyle["checkoutBtn_background=color"]) || !empty($shopStyle["shopBtn_background=color"])){
        if(!empty($checkoutStyle["cartBtn_background=color"])){
            $style .='background-color:' . $checkoutStyle["checkoutBtn_background=color"] . ' !important;';
        }
        else{
            $style .='background-color:' . $shopStyle["shopBtn_background=color"] . ' !important;';
        }
        
    }

    
 
    if($checkoutStyle["checkouttxt_size"] == ""){
        $style .='font-size: 14px !important;';
    }
    else{
        $style .='font-size:' . $checkoutStyle["checkouttxt_size"] . 'px !important;';
    }
    $style .='}';


    $checkoutfilePath = plugin_dir_path( __FILE__ ) . '/templates/wcs-checkout.css';
    $chkoutmyfile = fopen($checkoutfilePath, "w") or die("Unable to open file!");
    fwrite($chkoutmyfile, $style);
    fclose($chkoutmyfile);


     // Handle request then generate response using echo or leaving PHP and using HTML
        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit();

}


}