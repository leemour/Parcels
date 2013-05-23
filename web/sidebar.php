<div id="sidebar">
    <?php if ( $page_slug != 'main' ) { ?>
        <div id="services-widget" class="widget">
            <h3 class="widgettitle" title="Перечень всех услуг">Услуги</h3>
            <div class="widget-content">
				<ul class="services-list">
					<?php active_menu($side_menu); ?>
				</ul><!-- #services-list -->
            </div><!-- .widget-content -->
        </div><!-- #services-widget -->
    <?php } ?>
    
    <?php if ( ( $page_slug != 'contacts' ) && ( $page_slug != 'request' ) ) { ?>
        <div id="request-widget" class="widget">
            <h3 class="widgettitle">Запросите нас</h3>
            <div class="widget-content">
                <form id="request-form" action="/request" method="post">
                    <?php request_form() ?>
                    <input id="r-submit" class="button" type="submit" value="Отправить запрос" name="r-submit" />
                </form>
            </div><!-- .widget-content -->
        </div><!-- #request-widget -->
    <?php } ?>
    
    <div id="partners-widget" class="widget">
        <h3 class="widgettitle">Наши партнеры</h3>
        <div class="widget-content">
            <div class="partners-img">&nbsp;</div>
        </div>
    </div><!-- #partners-widget -->
</div><!-- #sidebar -->