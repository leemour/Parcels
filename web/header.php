<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="ru-RU">
<head profile="http://gmpg.org/xfn/11">
    <title><?php echo $title ? $title . ' - ': ''; ?>Parcels.su - Международные перевозки, Доставка коммерческих посылок</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="ru" />
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="keywords" content="<?php echo $keywords; ?>" />
	<meta name="author" content="riabit.ru" />
	<link rel="stylesheet" href="/css/styles.css" type="text/css" media="screen" />
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon"  />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"  />
	<script src="/js/cufon-yui.js" type="text/javascript"></script>
	<script src="/js/Play_700.font.js" type="text/javascript"></script>
	<script type="text/javascript">
		Cufon.replace('#navigation-main li a', {textShadow: '#12577e 1px 1px'});
		Cufon.replace('#services li a', {hover: {color: '#AC1E25'}});
		Cufon.replace('#services-widget li a', {hover: true});
		Cufon.replace('h1'); 
		Cufon.replace('h2');
		Cufon.replace('h3', {hover: true});
		Cufon.replace('#services-widget h3', {textShadow: '#12577e 1px 1px'});
		Cufon.replace('#stages h3', {textShadow: '#5396af 1px 1px'});
		Cufon.replace('#geography h4');
	</script>
    <!--[if IE 9]>
        <style>
            #features li a {
                opacity: 0.7;
            }
            #geography .continent {
                padding-right: 25px;
            }
            #main-content #request-form .form-block {
                padding-bottom: 12px;
            }
        </style>
    <![endif]-->
    <noscript>
		<style type="text/css">
			#navigation-main li a {
				font-weight: bold;
				height: 50px;
				line-height: 48px;
				padding: 0 13px;
			}
			#services .services-list li a span {
				font-weight: bold;
			}
			#services-widget li a {
				height: 24px;
				padding-top: 4px;
			}
		</style>
	</noscript>
</head>

<body>
<div id="header">
    <div class="wide">
		<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter14201347 = new Ya.Metrika({id:14201347, enableAll: true, trackHash:true, webvisor:true}); } catch(e) {} }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/14201347" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
        <h1>
            <a href="/" id="logo" class="text-image" title="На главную">Parcels.su - служба доставка коммерческих посылок</a>
        </h1>
        <img src="/images/delivery.png" alt="Растаможка, импорт, экспорт бизнес посылок и грузов любого размера" class="delivery" />
        <div class="header-contacts">
            <a href="#" class="lang lang-rus active">Русский</a>
            <a href="#" class="lang lang-eng">English</a>
            <div class="contact-box">
                <a href="mailto:<?php echo EMAIL; ?>"><?php echo EMAIL; ?></a>
            </div>
        </div><!-- .header-contacts -->
        <ul id="navigation-main" class="navigation">
            <?php active_menu($main_menu); ?>
        </ul><!-- #navigation-main-->
    </div><!-- .wide -->
</div><!-- #header -->

<div id="features" class="wide">
    <div class="border border-left"></div>
    <div class="border border-right"></div>
    <ul class="features-list navigation">
        <li>
            <a href="/delivery#size">
                Груз не бывает<br />
                слишком маленьким!
            </a>
        </li>
        <li>
            <a href="/payment">
                Гибкие схемы<br />
                доставки и оплаты
            </a>
        </li>
        <li>
            <a href="/delivery#warranty">
                Гарантии 100%<br />
                инвойсной стоимости
            </a>
        </li>
        <li>
            <a href="/distribution">
                Автоматическая отправка<br />
                повторяющихся посылок
            </a>
        </li>
    </ul><!-- .features-list -->
</div><!-- #features -->