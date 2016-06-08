<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/tutorial/');
define('CART_COOKIE','SBwi72UCklwiqzz2');
define('CART_COOKIE_EXPIRE',time() + (86400 *30));
define('TAXRATE',0.087);//impuesto sobre la compra realizada, colocar 0 si no se grega impuesto

define('CURRENCY', 'usd');
define('CHECKOUTMODE','TEST'); //Cambiar prueba a real cuando se este listo de ir a real

if(CHECKOUTMODE == 'TEST'){
    define('STRIPE_PRIVATE','sk_test_uPwJJw2FT21JtQ0aIdNuJg7Q');
    define('STRIPE_PUBLIC','pk_test_EKtkDdveifBqr43Fpc9DtQdV');
}

if(CHECKOUTMODE == 'LIVE'){
    define('STRIPE_PRIVATE','sk_live_d5YC523auRuPGFhhrS9O5Twm');
    define('STRIPE_PUBLIC','pk_live_xn5i4a1UkcMzF2Euz3DiyJNy');
}