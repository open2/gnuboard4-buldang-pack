/**
 * Copyright 2016 Been Kyung-yoon.
 */
// 개발자용 디버깅 활성화여부
var is_debug = app_debug || false;

// 로딩중 이미지 표시
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

// 페이지 이동 시작
try {
    window.addEventListener("beforeunload", function (e) {
        showLoading();
    });
} catch (e) {
    console.log(e);
}

// ajax 로딩 시작
$(document).ajaxStart(function () {
    $('#wrap-loading').fadeIn(200);
});

// ajax 로딩 종료
$(document).ajaxComplete(function () {
    if (wrapLoading !== null) {
        window.clearTimeout(wrapLoading);
    }
    $('#wrap-loading').fadeOut(500);
});

// 로딩중 이미가 계속 표시될 경우, 다른 기능을 누를 수 없는 단점을 보완하기 위해 클릭시 로딩중 닫기
$('#wrap-loading').on('click', function () {
    $('#wrap-loading').hide();
});

// Admin LTE options
var AdminLTEOptions = {
    animationSpeed: 100
};

/**
 * 숫자 콤마 표시
 * @param x
 * @returns {string}
 */
function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * 주소 이동
 * @param url
 */
function redirect(url) {
    $('#wrap-loading').fadeIn(100, function () {
        location.href = url;
    });
}

/**
 * 상단 새로고침
 */
function windowReload() {
    $('#wrap-loading').fadeIn(200);
    window.location.reload();
}

/**
 * 뒤로 가기
 *
 * TODO: 뒤로 가기가 불가할 경우, 메인으로 이동?
 */
function windowBack() {
    window.history.back();
}

/**
 * 앱 이벤트들
 */
function appEvents() {
    // 날짜 선택
    // if ($('.datepicker').not('.app-event').length) {
    //     $('.datepicker').not('.app-event')
    //         .datepicker()
    //         .addClass('app-event');
    // }

    // 플래시 메시지 자동 숨기기
    if ($(".flash-message")) {
        $(".flash-message").delay(5000).fadeOut();
    }
}

/**
 * 모달 폼
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
            alert('존재하지 않는 항목입니다.');
        } else {
            alert('서버 장애로 요청하신 작업이 실패하였습니다.\n'
                + jqXHR.status + ' ' + jqXHR.statusText + '\n'
                + jqXHR.responseText);

            if (is_debug) {
                console.error(data);
            }
        }
    });
}

/**
 * 모달폼 닫기
 */
function modalClose() {
    $('#modal-edit').modal('hide');
}

/**
 * 파일 다운로드
 */
function fileDownload(uri, fileName) {
    if (in_app) {
        window.plugins.toast.showLongTop("파일 다운로드를 시작합니다.");

        var fileTransfer = new FileTransfer();

        var fileSave = cordova.file.externalRootDirectory + 'Download/2cpu/' + fileName;
        fileTransfer.download(
            encodeURI(uri),
            fileSave,
            function (entry) {
                window.plugins.toast.hide();
                window.plugins.toast.showShortTop("파일 다운로드가 완료되었습니다.");
                window.open(entry.toURL(), "_system");
                //console.log("download complete: " + entry.toURL());
            },
            function (error) {
                window.plugins.toast.showShortTop("파일 다운로드가 실패하였습니다.");
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
 * 로딩시 이벤트
 */
$(function () {
    // 모든 이벤트 적용
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
