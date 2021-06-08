/**
 * Created by dmkcv on 19.07.2017.
 */
$(document).ready(function($) {
    $(document).on('click', '#testconnect', function () {
        $.ajax({
            url: 'scheduleupdate/test_dynamic',
            type: "POST",
            data: {
                url: $("#url").val(),
                login: $("#login").val(),
                password: $("#password").val(),
                filepath: $("#filepath").val(),
                strategy: $('input[id=strategy]:checked').val()
            },
            beforeSend: function() {
                $('#ajaxBusy').show();
            },
            complete: function() {
                $('#ajaxBusy').hide();
            },
            success: function (data) {
                //data.result ? $("#savesettings").removeAttr("disabled") : null; - собственно можно сохранять в любом случае
                data.result ? $("#updatenow").removeAttr("disabled") : null; // а обновлять сейчас только после проверки
                data.result ? $(".setting-box").attr("readonly","1") : null;
                var code = data.result ? 'great' : 'warning';
                var filename = (typeof data["filename"] !== 'undefined') ? 'Имя файла: ' + data.filename + '.' : '';
                var message = data.result ? '<p><b>Параметры подключения успешно проверены</b></p><p>Получен файл размером ' + data.filesize + ' байт, ' +
                    'дата изменения на сервере: ' + data.mtime + ', на клиенте: ' + data.localmtime + '. ' + filename + '</p>' : data.msg;
                var string = '<message class="'+code+'">' + message + '</message>';
                $('#testresult').html(string);
                console.log(data);
            },
            dataType: "json"
        });
    });

    $(document).on('click', '#updatenow', function () {
        $.ajax({
            url: 'scheduleupdate/update_dynamic',
            type: "GET",
            beforeSend: function() {
                $('#ajaxBusy').show();
            },
            complete: function() {
                $('#ajaxBusy').hide();
            },
            success: function (data) {
                var code = data.result ? 'great' : 'warning';
                var message = data.result ? '<p><i class="fa fa-cogs" aria-hidden="true"></i> Запрос на запуск процесса обновления отправлен</p>' : data.msg;
                var string = '<message class="'+code+'">' + message + '</message>';
                $('#testresult').html(string);
                console.log(data);
            },
            dataType: "json"
        });
    });
});

document.getElementById("savesettings").addEventListener("click", function () {
    document.getElementById("settings").submit();
});