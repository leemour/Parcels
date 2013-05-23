<?php
/**
 * Проверка формы и отправка заявки на email
 *
 */
namespace Parcels;

class Validation {
    private $fields = array();
    public $locale = NULL;

    public function __construct($app) {
        $this->locale = $app['locale'];
        $this->fields = $app['main_form'];
        $this->email_subject = EMAIL_SUBJECT;
        $this->email = EMAIL;
    }

    //Обработка заявки
    public function processRequest() {
        $errors = $this->validate($this->fields);
        if ($errors) {
            return $errors;
        } else {
            //Cоставление письма
            $this->makeEmail();

            //Отправка почты
            $this->sendMail();
        }
    }

    //Валидация полей заявки
    public function validate($fields) {
        foreach( $fields as $field ) {
            $f_name = $field['name'];
            $f_name = substr( $f_name, 2 );
            $$f_name = $_POST[$field['name']];
            if ( $input_error = $this->inputCheck( $$f_name, 'string', $field['maxlength'] ) ) {
                $errors['r-' . $f_name] = $input_error . ' <strong>' . $field['text'] . '</strong>';
            }
        }
        if ( empty( $mail ) && empty( $tel ) ) {
            $errors['r-mail'] = 'Пожалуйста, введите <strong>e-mail</strong> или <strong>телефон</strong> для связи';
        } elseif ( !empty ( $mail ) ) {
            if ( !( $valid_mail = $this->emailCheck( $mail ) ) ) {
                $errors['r-mail'] = 'Вы ввели неправильный <strong>e-mail</strong>';
            }
        }
        if ( $errors ) {
            return $errors;
        } else {
            return false;
        }
    }

    //Создаем заголовки и тело письма
    private function makeEmail() {
        $f = $this->$fields;
        if ( !empty( $f['mail'] ) ) {
            $from = $f['mail'];
        } else {
            $from = 'order@parcels.su';
        }
        if ( !empty( $f['name'] ) ) {
            $fromname = $f['name'];
        } else {
            $fromname = 'Клиент с Parcels.su';
        }

        //Текст письма
        $message  = "От: " . $fromname ." <" . $from ."> \r\n";
        $message .= $f['tel'];
        $message .= " \r\n \r\n";
        $message .= $this->line( 'description', $f['description']);
        $message .= $this->line( 'price', $f['price']);
        $message .= $this->line( 'quantity', $f['quantity']);
        $message .= $this->line( 'weight', $f['weight']);
        $message .= $this->line( 'size', $f['size']);
        $message .= $this->line( 'origin', $f['origin']);
        $message .= $this->line( 'destination', $f['destination']);
        $message .= $this->line( 'services', $f['services']);
        $message .= " \r\n";
        $message .= "--Отправлено через форму запроса parcels.su";

        //Заголовки письма
        $addr  = mb_encode_mimeheader($fromname, 'UTF-8', 'Q') . " <$from>";
        $headers =
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/plain; charset=utf-8\r\n" .
            'From: ' . $f['addr'] . "\r\n" .
            "Reply-To: " . $from . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        $this->sendMail($addr, $headers, $message);
    }

    //Отправка письма
    private function sendMail($addr, $headers, $message) {
        $to = $this->email;
        $subject = mb_encode_mimeheader($this->email_subject, 'UTF-8', 'Q');
        if ( empty( $_SERVER['HTTP_REFERER'] ) ) {
            $referer = 'НЕИЗВЕСТНО';
        } else {
            $referer = $_SERVER['HTTP_REFERER'];
        }
        if ( $result = mail( $to, $this->email_subject, $message, $headers ) ) {
            //Записываем данные в лог
            $log_message = date("d-m-Y, H:i:s") . ' письмо отправлено на ' . $to . " \n";
            $log_message .= 'С IP-адреса ' . $_SERVER['REMOTE_ADDR'] . ' со страницы ' . $referer . " \n";
            $log_message .= "Текст письма: \n";
            $log_message .= $message . " \n \n \n";
            $this->log( $log_message, MAIL_LOG );
            return false;
        } else {
            //Ошибка отправки
            $errors[] = 'Ошибка отправки запроса, пожалуйста, попробуйте позже';
            //Записываем данные в лог
            $log_message = date("d-m-Y, H:i:s") . ' ОШИБКА отправки письма на ' . $to . " \n";
            $log_message .= 'С IP-адреса ' . $_SERVER['REMOTE_ADDR'] . ' со страницы ' . $referer . " \n";
            $log_message .= "Текст письма: \n";
            $log_message .= $message . " \n \n \n";
            $this->log( $log_message, ERROR_LOG );
            return $errors;
        }
    }

    //Логгер
    private function log( $message, $log ) {
        $file = fopen( $log, 'a');
        fwrite( $file, $message );
        fclose( $file);
    }

    //Строка письма
    private function line( $field_name, $value ) {
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
    private function inputCheck($string, $type, $length, $mandatory = false) {
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
    private function emailCheck($email) {
      return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
    }
}