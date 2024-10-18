<?php

// dynamic parameters
define("db_host", "localhost");
define("db_user", "u257601826_jigsr");
define("db_pass", "q4ECeMC4D&");
define("db_name", "u257601826_jigsr");

define("IS_DEVELOPMENT", false);
define("IS_PRODUCTION", (!IS_DEVELOPMENT));
if (IS_PRODUCTION) {
    define("API_SERVICE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/api_services/");
    define("ADMIN_PANEL_URL", "http://" . $_SERVER['HTTP_HOST'] . "/admin_panel/");
    define("ROOT_URL", "http://" . $_SERVER['HTTP_HOST'] . "/");
    define("ALLOW_EXTERNAL_SCRIPT", "1");
    define("ALLOW_MIXPANEL_SCRIPT", "1");
} else {
    define("API_SERVICE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/product/HTML/flipkart/api_services/");
    define("ADMIN_PANEL_URL", "http://" . $_SERVER['HTTP_HOST'] . "/product/HTML/flipkart/admin_panel/");
    define("ROOT_URL", "http://" . $_SERVER['HTTP_HOST'] . "/product/HTML/flipkart/");
    define("ALLOW_EXTERNAL_SCRIPT", "0");
    define("ALLOW_MIXPANEL_SCRIPT", "0");
}
// dynamic end
