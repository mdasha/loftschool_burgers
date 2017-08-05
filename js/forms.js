$('.order__form-button').on('click', function (e) {
    $error = false;
    e.preventDefault();

    var name1 = $('input[name=name]');
    var name = name1.val();
    var phone = $('input[name=phone]').val();
    var email = $('input[name=email]').val();
    var street = $('input[name=street]').val();
    var home = $('input[name=home]').val();
    var part = $('input[name=part]').val();
    var appt = $('input[name=appt]').val();
    var floor = $('input[name=floor]').val();
    var comment = $('textarea[name=comment]').val();
    var payment = $('input:radio[name=payment]:checked').val();
    var card = $('input:radio[name=card]:checked').val();
    var callback = $('input:checkbox[name=callback]:checked').val();

    // Вывод блока с ошибкой в случае неправильного ввода данных (не заполнены поля Имя, телефон, email, улица, дом
    function errorBlock() {
        // Клонируем блок с ID=error, добавляем класс successful, добавляем скопированный код сразу после формы
        $('#error').clone().addClass('error').appendTo('.order__form');
        //Делаем скопированный блок видимым, стилизуем его
        $('.error').css('display', 'block').css('width', '1158px').css('background', '#ffffff')
            .css('z-index', '1000').css('position', 'absolute').css('height', '300px');
        // При нажатии кнопки "Закрыть" на появившемся блоке об успешной отправке скрываем данный блок
        $('.status-popup__close').on('click', function (e) {
            e.preventDefault();
            $('.popup, .error').css('display', 'none')
        });
    }

    //Вывод блока при успешном заполнении формы
    function successBlock() {
        // Клонируем блок с ID=success, добавляем класс successful, добавляем скопированный код сразу после формы
        $('#success').clone().addClass('successful').appendTo('.order__form');
        //Делаем скопированный блок видимым, стилизуем его
        $('.successful').css('display', 'block').css('width', '1158px').css('background', '#ffffff')
            .css('z-index', '1000').css('position', 'absolute').css('height', '300px');
        // При нажатии кнопки "Закрыть" на появившемся блоке об успешной отправке скрываем данный блок
        $('.status-popup__close').on('click', function (e) {
            e.preventDefault();
            $('.popup, .successful').css('display', 'none')
        });
    }

    //Функция, которая окрашивает border в красный цвет, если одно из полей не заполнено или поле email невалидно
    function borderRed () {
        var $array = ['input[name=name]', 'input[name=phone]', 'input[name=street]', 'input[name=home]','input[name=email]'];
        $.each($array, function (index, value) {
            if ($(value).val() === '') {
                $(value).css('border', '3px solid red');
            } else {
                $(value).css('border', '.0625rem solid #d1cfcb');
            }
        });
        if(!validateEmail(email) || email === '') {
            $($array[4]).css('border', '3px solid red');
        } else {
            $($array[4]).css('border', '.0625rem solid #d1cfcb');
        }
    }

    // Валидация email
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }

    // Если одно из полей (имя, телефон, email, улица, дом) или email не валиден не заполнено, то выводим popup с ошибкой,
    // при закрытии popup незаполненные поля обводятся красным цветом
    // Если ошибка исправлена, то обводка меняется на исходную
    if (name === '' || phone === '' || street === '' || email === '' ||  home === '' || !validateEmail(email)) {
        $error = true;
        borderRed ();
        errorBlock();
    }


    // Если ошибок в заполнении формы нет, то отправляем данные на сервер и выводим popup с успешным завершением операции
    if (!$error) {
        $.ajax({

            url: './form-handler.php',
            method: 'POST',
            data: {
                name: name,
                phone: phone,
                email: email,
                street: street,
                home: home,
                part: part,
                flat: appt,
                floor: floor,
                comment: comment,
                payment: payment,
                card: card,
                callback: callback
            },
            success: function () { // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
                // eсли всe прoшлo oк
                successBlock();
                borderRed ();
            }
        });
    }
});


