<?php
/**
 * НАСТРОЙКИ
 *
 */
define( 'SRCPATH', __DIR__ );
define( 'ABSPATH', dirname(__FILE__) . '/../web' );
define( 'CONTENT_PATH', __DIR__ . '/../content');
define( 'IMAGE_PATH', '/images/content');
define( 'ERROR_LOG', __DIR__ . '/../log/error.log' );
define( 'MAIL_LOG', __DIR__ . '/../log/mail.log' );
define( 'EMAIL', 'info@parcels.su');
define( 'EMAIL_SUBJECT', 'Заказ с Parcels.SU');
define( 'MAIN_TITLE', 'Parcels.su - Международные перевозки, Доставка коммерческих посылок');

date_default_timezone_set('Europe/Moscow');
mb_internal_encoding("UTF-8");