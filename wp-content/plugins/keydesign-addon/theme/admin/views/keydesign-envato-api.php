<form action="" method="POST">
    <input type="text" name="purchase-code" placeholder="Purchase Code Here">
    <input type="submit" value="Activate"/>
</form>

<?php

if (!function_exists('is_valid_envato_purchase_code')) {
    function is_valid_envato_purchase_code($code) {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $code
        );
    }
}

if (isset($_POST['purchase-code'])) {
    $product_code = sanitize_text_field($_POST['purchase-code']);
    update_option('envato_purchase_code_23714045', $product_code);

    $url = "http://api.leadengine-wp.com/activate/3ca58589-9289-11e9-a87b-00163e6818fb?code=" . rawurlencode($product_code);

    if (!extension_loaded('curl')) { ?>
        <span class='kdadmin-code-error'><?php echo "cURL module is disabled on your server. Please enable cURL."; ?></span>
        <?php exit;
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Referer: ' . get_site_url()));

    $envatoResRaw = curl_exec($curl);
    curl_close($curl);

    $envatoRes = json_decode($envatoResRaw);

    if (isset($envatoRes->activated) && ($envatoRes->activated == true)) {
        update_option('keydesign-verify', 'yes');
        ?>
        <script>window.location.reload(true);</script>
        <?php
        exit;

    } else if (
        (isset($envatoRes->activated) && ($envatoRes->activated == false))
        || isset($envatoRes->statusCode)
        || isset($envatoRes->error)
    ) {
        $data = "Your purchase code is not valid. Please provide a valid purchase code";
        update_option('keydesign-verify', 'no');

    } else if (isset($_POST['purchase-code'])) {
    $product_code = sanitize_text_field($_POST['purchase-code']);

    if (!is_valid_envato_purchase_code($product_code)) {
        $data = "Error connecting to Envato API or purchase code is not valid";
    } else {
        update_option('keydesign-verify', 'yes');
        ?>
        <script>window.location.reload(true);</script>
        <?php
        exit;
    }
}
    ?>
    <span class='kdadmin-code-error'><?php echo $data; ?></span>
    <?php
}
?>