<?php
### ФУНКЦИИ
##

//Подключение файлов шаблона
function get_template_part($template_name) {
    global $title, $description, $keywords, $main_menu, $side_menu, $page_slug, $page_parent;
    $located = '';
    if ( file_exists( CONTENT_PATH . '/' . $template_name ) ) {
        $located = CONTENT_PATH . '/' . $template_name;
    } elseif ( file_exists( ABSPATH . $template_name ) ) {
        $located = ABSPATH . $template_name;
    }
    require_once( $located );
}

//Хлебные крошки
function breadcrumbs() {
    global $page_parent, $page_slug, $title; ?>
    <div id="breadcrumbs">
        <a href="/" class="breadcrumb-link">Главная</a>
        <?php if ( $page_parent ) { ?>
            <a href="/<?php echo $page_parent ?>" class="breadcrumb-link"><?php echo get_page_title($page_parent) ?></a>
        <?php } ?>
        <span class="breadcrumb-current"><?php echo $title; ?></span>
    </div><!-- #breadcrumbs -->
<?php }

//Родительские страницы
function get_parent_page( $slug ) {
    global $parent_pages;
    if ( array_key_exists( $slug, $parent_pages ) ) {
        return $parent_pages[$slug];
    } else {
        return false;
    }
}

//Заголовок страницы
function get_page_title( $slug ) {
    global $page_titles;
    if ( array_key_exists( $slug, $page_titles ) ) {
        return $page_titles[$slug];
    } else {
        return false;
    }
}

//Активный раздел меню
function active_menu( $menu, $parent = '' ) {
    global $page_slug;
	foreach ( $menu as $menu_item ) {
		!empty( $menu_item['parent'] ) ? $menu_item['parent'] : $menu_item['parent'] = '';
		if ( ( !$parent && $menu_item['parent'] ) || ( $parent != $menu_item['parent'] ) ) {
			continue;
		}
		$class = '';
		$menu_slug = trim( $menu_item['slug'], '\/\\');
		if ( $menu_slug == $page_slug || ( strstr( $menu_slug, 'delivery' ) &&  $page_slug == 'delivery' ) ) {
			$class = 'class="active"';
		} ?>
		<li>
			<a href="<?php echo $menu_item['slug']; ?>" title="<?php echo $menu_item['title']; ?>" <?php echo $class; ?>>
				<?php echo $menu_item['text']; ?>
			</a>
			<?php if ($menu_item['slug']) { ?>
				<ul>
					<?php active_menu( $menu, $menu_item['slug'] ); ?>
				</ul>
			<?php } ?>
		</li>
	<?php } ?>
<?php }

//Обработка заявки
function process_requests() {
    //$referer = $_SERVER['HTTP_REFERER'];
    //$referer = $_SERVER['HTTP_REFERER'];
    //проверка реферера
    //if ( !strpos( $_SERVER['SERVER_NAME'], $referer ) ) {
    //        echo "Вы что-то делаете неправильно...\n";
    //        exit;
    //}
    //error_reporting(E_ALL ^ E_NOTICE);
    global $form_fields, $errors;
    $input_error = '';

    foreach( $form_fields as $field ) {
        $f_name = $field['name'];
        $f_name = substr( $f_name, 2 );
        $$f_name = $_POST[$field['name']];
        if ( $input_error = input_check( $$f_name, 'string', $field['maxlength'] ) ) {
            $errors['r-' . $f_name] = $input_error . ' <strong>' . $field['text'] . '</strong>';
        }
    }
    if ( empty( $mail ) && empty( $tel ) ) {
        $errors['r-mail'] = 'Пожалуйста, введите <strong>e-mail</strong> или <strong>телефон</strong> для связи';
    } elseif ( !empty ( $mail ) ) {
        if ( !( $valid_mail = email_check( $mail ) ) ) {
            $errors['r-mail'] = 'Вы ввели неправильный <strong>e-mail</strong>';
        }
    }
    if ( $errors ) {
        return $errors;
    } else {
        //Cоставление письма
        if ( !empty( $mail ) ) {
            $from = $mail;
        } else {
            $from = 'order@parcels.su';
        }
        if ( !empty( $name ) ) {
            $fromname = $name;
        } else {
            $fromname = 'Клиент с Parcels.su';
        }

        //Текст письма
        $message  = "От: " . $fromname ." <" . $from ."> \r\n";
        $message .= $tel;
        $message .= " \r\n \r\n";
        $message .= email_line( 'description', $description );
        $message .= email_line( 'price', $price );
        $message .= email_line( 'quantity', $quantity );
        $message .= email_line( 'weight', $weight );
        $message .= email_line( 'size', $size );
        $message .= email_line( 'origin', $origin );
        $message .= email_line( 'destination', $destination );
        $message .= email_line( 'services', $services );
        $message .= " \r\n";
        $message .= "--Отправлено через форму запроса parcels.su";

        //Заголовки письма
	$addr  = mb_encode_mimeheader($fromname, 'UTF-8', 'Q') . " <$from>";
        $headers =
	    "MIME-Version: 1.0\r\n" .
	    "Content-Type: text/plain; charset=utf-8\r\n" .
	    'From: ' . $addr . "\r\n" .
	    "Reply-To: " . $from . "\r\n" .
	    "X-Mailer: PHP/" . phpversion();

        //Отправка почты
        $to = EMAIL;
        $file = '';
	$subject = mb_encode_mimeheader(EMAIL_SUBJECT, 'UTF-8', 'Q');
        if ( empty( $_SERVER['HTTP_REFERER'] ) ) {
            $referer = 'НЕИЗВЕСТНО';
        } else {
            $referer = $_SERVER['HTTP_REFERER'];
        }
        if ( $result = mail( $to, EMAIL_SUBJECT, $message, $headers ) ) {
            //Записываем данные в лог
            $log_message = date("d-m-Y, H:i:s") . ' письмо отправлено на ' . $to . " \n";
            $log_message .= 'С IP-адреса ' . $_SERVER['REMOTE_ADDR'] . ' со страницы ' . $referer . " \n";
            $log_message .= "Текст письма: \n";
            $log_message .= $message . " \n \n \n";
            log_events( $log_message, MAIL_LOG );
            return false;
        } else {
            //Ошибка отправки
            $errors[] = 'Ошибка отправки запроса, пожалуйста, попробуйте позже';
            //Записываем данные в лог
            $log_message = date("d-m-Y, H:i:s") . ' ОШИБКА отправки письма на ' . $to . " \n";
            $log_message .= 'С IP-адреса ' . $_SERVER['REMOTE_ADDR'] . ' со страницы ' . $referer . " \n";
            $log_message .= "Текст письма: \n";
            $log_message .= $message . " \n \n \n";
            log_events( $log_message, ERROR_LOG );
            return $errors;
        }
    }
}

//Логгинг событий
function log_events( $message, $log ) {
    $file = fopen( $log, 'a');
    fwrite( $file, $message );
    fclose( $file);
}

//Строка письма
function email_line( $field_name, $value ) {
    if ( $value ) {
        global $form_fields;
        $field_name = 'r-' . $field_name;
        $text = $form_fields[$field_name]['text'];
        //Чистка названия полей
        if ( $field_name == 'r-price' ) {
            $text = $form_fields[$field_name]['text2'];
        }
        return html_entity_decode( $text ) . ":   " . $spaces . $value . " \r\n";
    } else {
        return '';
    }
}

//Валидация значений
function input_check($string, $type, $length, $mandatory = false) {
    // assign the type
    $type = 'is_'.$type;

    if ( empty( $string ) && $mandatory ) {
        return 'Не заполнено поле';
    // now we see if there is anything in the string
    } elseif ( !empty( $string ) && !$type( $string ) ) {
        return 'Неправильно заполнено поле';
    // then we check how long the string is
    } elseif ( mb_strlen( $string ) > $length ) {
        return "Превышена длина ({$length}) поля";
    } else {
        $string = trim( strip_tags( $string ) );
        // if all is well, we return FALSE
        return FALSE;
    }
}

//Проверка e-mail
function email_check($email) {
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

//Форма заявки - боковая панель
function request_form( $group = '' ) {
    global $form_fields; ?>
    <ul>
        <?php foreach( $form_fields as $field ) {
            if ( $field['name'] != 'r-size' ) { ?>
                <li>
            <?php }
                if ( $field['name'] == 'r-size' || $field['name'] == 'r-weight' ) { ?>
                    <div class="left">
                <?php }
                if ( $field['name'] == 'r-price' ) {
                    $field['text'] = $field['text2'];
                } ?>
                        <label for="<?php echo $field['name'] ?>"><?php echo $field['text'] ?>:</label>
                        <input name="<?php echo $field['name'] ?>" id="<?php echo $field['name'] ?>" type="text" value="" maxlength="<?php echo $field['maxlength'] ?>" />
                <?php if ( ($field['name'] == 'r-size') || ($field['name'] == 'r-weight') ) { ?>
                    </div>
                <?php } ?>
            <?php if ( $field['name'] != 'r-weight' ) { ?>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
<?php }

//Форма заявки - большая
function request_form2( $group = '' ) {
    global $form_fields, $errors; ?>
    <ul>
        <?php foreach( $form_fields as $field ) {
            $value = '';
            //Если поле не в группе
            if ( $group && ( $group != $field['group'] ) )
                continue;
            $class = '';
            //Добавляем класс ошибки
            if ( !empty( $errors[$field['name']] ) )
                $class = 'error';
            //Показываем введенные данные
            if ( !empty( $_POST[$field['name']] ) )
                $value = strip_tags( trim( $_POST[$field['name']] ) );
            //Если textarea
            if ( $field['type'] == 'textarea' ) {
                $class = " class='" . $field['class'] . " " . $class . "'";
                $rows = 'rows="' . substr( $field['class'], -1 ) . '" '; ?>
                <li<?php echo $class ?>>
                    <label for="<?php echo $field['name'] ?>"><?php echo $field['text'] ?>:</label>
                    <textarea name="<?php echo $field['name'] ?>" id="<?php echo $field['name'] ?>" type="<?php echo $field['type'] ?>" maxlength="<?php echo $field['maxlength'] ?>" <?php echo $rows ?>/><?php echo $value ?></textarea>
                </li>
            <?php //Если текстовое поле (обычное)
            } else {
                if ( $class )
                    $class = " class='" . $class . "'"; ?>
                <li<?php echo $class ?>>
                    <label for="<?php echo $field['name'] ?>"><?php echo $field['text'] ?>:</label>
                    <input name="<?php echo $field['name'] ?>" id="<?php echo $field['name'] ?>" type="<?php echo $field['type'] ?>" value="<?php echo $value ?>" maxlength="<?php echo $field['maxlength'] ?>" />
                </li>
            <?php }
        } ?>
    </ul>
<?php }
?>