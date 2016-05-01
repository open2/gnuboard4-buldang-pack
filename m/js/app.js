/**
 * Copyright 2016 Been Kyung-yoon.
 */
// �����ڿ� ����� Ȱ��ȭ����
var is_debug = app_debug || false;

// �ε��� �̹��� ǥ��
var wrapLoading = null;
function showLoading(delay, fade) {
    if (typeof delay === "undefined") {
        delay = 200;
    }

    if (typeof fade === "undefined") {
        fade = 500;
    }

    wrapLoading = setTimeout(function () {
        $('#wrap-loading').fadeIn(fade);
    }, delay);
}

// ������ �̵� ����
try {
    window.addEventListener("beforeunload", function (e) {
        showLoading();
    });
} catch (e) {
    console.log(e);
}

// ajax �ε� ����
$(document).ajaxStart(function () {
    $('#wrap-loading').fadeIn(200);
});

// ajax �ε� ����
$(document).ajaxComplete(function () {
    if (wrapLoading !== null) {
        window.clearTimeout(wrapLoading);
    }
    $('#wrap-loading').fadeOut(500);
});

// �ε��� �̹̰� ��� ǥ�õ� ���, �ٸ� ����� ���� �� ���� ������ �����ϱ� ���� Ŭ���� �ε��� �ݱ�
$('#wrap-loading').on('click', function () {
    $('#wrap-loading').hide();
});

// Admin LTE options
var AdminLTEOptions = {
    animationSpeed: 100
};

/**
 * ���� �޸� ǥ��
 * @param x
 * @returns {string}
 */
function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * �ּ� �̵�
 * @param url
 */
function redirect(url) {
    $('#wrap-loading').fadeIn(100, function () {
        location.href = url;
    });
}

/**
 * ��� ���ΰ�ħ
 */
function windowReload() {
    $('#wrap-loading').fadeIn(200);
    window.location.reload();
}

/**
 * �ڷ� ����
 *
 * TODO: �ڷ� ���Ⱑ �Ұ��� ���, �������� �̵�?
 */
function windowBack() {
    window.history.back();
}

/**
 * �� �̺�Ʈ��
 */
function appEvents() {
    // ��¥ ����
    // if ($('.datepicker').not('.app-event').length) {
    //     $('.datepicker').not('.app-event')
    //         .datepicker()
    //         .addClass('app-event');
    // }

    // �÷��� �޽��� �ڵ� �����
    if ($(".flash-message")) {
        $(".flash-message").delay(5000).fadeOut();
    }
}

/**
 * ��� ��
 * @param url
 */
function modalForm(url) {
    $.ajax(url, {
        async: false
    }).done(function (data) {
        $('#modal-edit-label').html($(data).find('form').data('title'));
        $('#modal-edit .modal-body').html(data);
        $('#modal-edit').modal();
        appEvents();
    }).fail(function (jqXHR) {
        if (jqXHR.status === 404) {
            alert('�������� �ʴ� �׸��Դϴ�.');
        } else {
            alert('���� ��ַ� ��û�Ͻ� �۾��� �����Ͽ����ϴ�.\n'
                + jqXHR.status + ' ' + jqXHR.statusText + '\n'
                + jqXHR.responseText);

            if (is_debug) {
                console.error(data);
            }
        }
    });
}

/**
 * ����� �ݱ�
 */
function modalClose() {
    $('#modal-edit').modal('hide');
}

/**
 * ���� �ٿ�ε�
 */
function fileDownload(uri, fileName) {
    if (in_app) {
        window.plugins.toast.showLongTop("���� �ٿ�ε带 �����մϴ�.");

        var fileTransfer = new FileTransfer();

        var fileSave = cordova.file.externalRootDirectory + 'Download/2cpu/' + fileName;
        fileTransfer.download(
            encodeURI(uri),
            fileSave,
            function (entry) {
                window.plugins.toast.hide();
                window.plugins.toast.showShortTop("���� �ٿ�ε尡 �Ϸ�Ǿ����ϴ�.");
                window.open(entry.toURL(), "_system");
                //console.log("download complete: " + entry.toURL());
            },
            function (error) {
                window.plugins.toast.showShortTop("���� �ٿ�ε尡 �����Ͽ����ϴ�.");
                console.log("download error source " + error.source);
                console.log("download error target " + error.target);
                console.log("upload error code" + error.code);
            },
            false,
            {}
        );
    } else {
        window.location.href = uri;
    }
}

/**
 * �ε��� �̺�Ʈ
 */
$(function () {
    // ��� �̺�Ʈ ����
    appEvents();

    // Side Menu
    if (document.getElementById('o-wrapper')) {
        var slideLeft = new Menu({
            wrapper: '#o-wrapper',
            type: 'slide-left',
            menuOpenerClass: '.c-button',
            maskId: '#c-mask'
        });

        var slideLeftBtn = document.querySelector('#c-button--slide-left');
        if (slideLeftBtn) {
            slideLeftBtn.addEventListener('click', function (e) {
                e.preventDefault();
                slideLeft.open();
            });
        }

        //slideLeftBtn.click();
    }
});
