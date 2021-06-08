// Обновление окна раз в 18 секунд
setInterval(function(){window.location = window.location.pathname},180000);

// Кнопка загрузки и фокус на окно выбора файлов
$(".upload").click(function() {
    $("input[id='xml']").focus().click();
});

// Проверка состояния процесса
$(document).on('click', '.revcheck', function(event) {
    var rev = event.target.dataset.rev;
    if (rev.length === 32) $.ajax({
        url: 'actions/task/check_revision',
        dataType: 'json',
        data: {rev: rev},
        beforeSend: function () { // Анимация шестерни
            $('.status-icon' + rev).removeClass('fa-question');
            $('.status-icon' + rev).addClass('fa-cog fa-spin');
        },
        complete: function () {
            $('.status-icon' + rev).removeClass('fa-cog fa-spin');
            $('.status-icon' + rev).addClass('fa-question');
        },
        success: function (data) {
            var color = data.result ? 'green' : 'red';
            var icon = data.result ? '<i class="fa fa-thumbs-o-up" aria-hidden="true">' : '<i class="fa fa-exclamation-triangle" aria-hidden="true">';
            var text = data.result ? icon + '</i> Процесс обработки активен' : icon + '</i> Процесс обработки <br> не найден, перезапустите задачу';
            var message = '<span style="color:' + color + '"><i>' + text + '</i></span>';
            $('#status' + rev).empty();
            $('#status' + rev).html(message);
        }
    });
});

// Перезапуск обработки ревизии
$(document).on('click', '.revrestart', function(event) {
    var rev = event.target.dataset.rev;
    if (rev.length === 32) $.ajax({
        url: 'actions/task/restart_revision',
        dataType: 'json',
        data: {rev: rev},
        beforeSend: function () { // Анимация стрелочек
            $('.restart-icon' + rev).addClass('fa-spin');
        },
        complete: function () {
            $('.restart-icon' + rev).removeClass('fa-spin');
        },
        success: function (data) {
            var color = data.result ? 'green' : 'red';
            var icon = data.result ? '<i class="fa fa-thumbs-o-up" aria-hidden="true">' : '<i class="fa fa-exclamation-triangle" aria-hidden="true">';
            var text = data.result ? icon + '</i> Команда на перезапуск <br>отправлена' : icon + '</i> Возникла ошибка, <br> повторите попытку';
            var message = '<span style="color:' + color + '"><i>' + text + '</i></span>';
            $('#status' + rev).empty();
            $('#status' + rev).html(message);
        }
    });
});


