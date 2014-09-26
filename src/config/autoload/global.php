<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    // ...
    'cf-item'=>'global',
    'js' => array(
        'base_url'=>''
    ),
    'view_manager' => array(
        'base_path' => 'http://localhost:8089',
    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),
);
