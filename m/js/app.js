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
$(document).ajaxStop(function () {
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
    //$('#wrap-loading').fadeIn(100, function () {
    simpleSpaRedirect(url);
    //});
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

var simpleSpaEnable = window.history && window.history.pushState,
    simpleSpaLimit = 10,
    simpleSpaCount = 0;

/**
 * Simple SPA
 *  - 글쓰기 페이지에서는 페이지 나가기 이벤트를 고려하여 SPA 제외
 *  - # 로 시작하는 해시 주소는 제외
 *  - javascript:, mailto: 제외
 */
function simpleSpa() {
    if (simpleSpaIsAllowUrl(location.pathname)) {
        $('a').each(function () {
            var $a = $(this),
                url = $a.attr('href');
            if (
                url
                && url.indexOf('#') !== 0
                && url.indexOf('javascript:') !== 0
                && url.indexOf('mailto:') !== 0
                && url.indexOf('intent:') !== 0
            ) {
                // 웹에서는 새창일 경우, 이벤트 생략
                if (!in_app && $a.attr('target') === '_blank') {
                    //
                } else {
                    $a.on('click', function (e) {
                        simpleSpaRedirect(url, $a.attr('target'), $a.text());
                        e.preventDefault();
                    });
                }
            }
        });
    }
}

/**
 * Simple SPA 내용 가져오기
 *  - head 단을 제외한 body 만 가져와서 교체.
 *  - pushState 지원 브라우저에서만 사용.  IE 10, Android 4.3
 *    - http://caniuse.com/#search=pushstate
 *  - 메모리 누수 가능성을 고려하여 simpleSpaLimit 초과시엔 ajax 대신 페이지 이동하여 초기화 시킴.
 *  - 로딩 이미지 보여주도록 비동기식 처리
 *  - 외부 도메인일 경우 ajax 가 아닌  이동 처리
 *
 * @param url
 * @param target
 * @param title
 * @param popState
 */
function simpleSpaRedirect(url, target, title, popState) {
    title = title || "";
    popState = popState || false;
    var isInternalUrl = simpleSpaIsInternalUrl(url);
    if (isInternalUrl) {
        if (simpleSpaEnable && simpleSpaCount < simpleSpaLimit && simpleSpaIsAllowUrl(url)) {
            url = simpleSpaAbsoluteUrl(url);
            //console.log('simpleSpaRedirect: ' + url);

            // 사이드바가 클릭된 상태에서 이동될 경우를 감안해, 사이드바 닫기 이벤트
            if (slideLeft) {
                slideLeft.close();
            }

            $.ajax(url, {
                //async: false
                cache: false,
                timeout: 10000
            }).done(function (data) {
                // 내용 교체
                var $body = $('body');

                // alert, window.close 만으로 응답하는 페이지는 경고창만 보여주고, 페이지 이동하지 않음.
                try {
                    var dataScript = $(data).filter('script');
                    if (dataScript && dataScript.length === 2) {
                        if (
                            dataScript[0].text
                            && dataScript[0].text.trim().indexOf('alert(') === 0
                            && dataScript[1].text
                            && dataScript[1].text.trim().indexOf('window.close()') === 0
                        ) {
                            var alertText = dataScript[0].text.trim().substr(7, dataScript[0].text.trim().length - 10).replace(/\\/g, '');
                            if (alertText !== '') {
                                simpleSpaToast(alertText);
                                return;
                            }
                        }
                    }
                } catch (e) {
                    console.log('simpleSpaRedirect script parse error: ' + e.toString());
                }

                if ($body && data) {
                    // ajax history 지원
                    if (!popState) {
                        // 이전 URL과 동일할 경우 추가 생략
                        if (!window.history.state || !window.history.state.url || window.history.state.url !== url) {
                            window.history.pushState({url: url}, title, url);
                        }
                    }

                    // history 저장후 로딩해야 js 에러 안남.
                    $body.html(data);

                    // 페이지 이동후엔 새로고침처럼 탑으로 스크롤 이동
                    $body.scrollTop(0);

                    // 새로운 내용에 이벤트 적용
                    appEvents();

                    simpleSpaCount++;
                } else {
                    simpleSpaPopup(url, target);
                }
            }).fail(function () {
                simpleSpaPopup(url, target);
            });
        } else {
            simpleSpaPopup(url, target);
        }
    } else {
        if (in_app) {
            window.open(url, '_system');
        } else {
            simpleSpaPopup(url, target);
        }
    }
}

/**
 * 토스트 메시지
 *  - 앱에서는 토스트 메시지로 잠시 보여졌다 사라지게 함.
 *
 * @param message
 */
function simpleSpaToast(message) {
    if (in_app) {
        try {
            window.plugins.toast.showShortTop(message);
        } catch (e) {
            alert(message);
            console.log('simpleSpaToast error: ' + e.toString());
        }
    } else {
        alert(message);
    }
}

/**
 * 팝업 처리
 *  - 웹과 앱을 구분하여 처리
 *
 * @param url
 * @param target
 */
function simpleSpaPopup(url, target) {
    if (typeof target === "undefined" || target === "") {
        window.location.href = url;
    } else {
        if (in_app) {
            window.open(url, '_system');
        } else {
            window.open(url, target);
        }
    }
}

/**
 * 내부 링크 구분
 *   - 도메인 기준
 *
 * @param url
 * @returns {boolean}
 */
function simpleSpaIsInternalUrl(url) {
    var a = document.createElement('a');
    a.href = url;
    var findDomain = g4_cookie_domain;
    if (findDomain.indexOf('.') === 0) {
        findDomain = findDomain.substr(1);
    }

    return (a.hostname.indexOf(findDomain) !== -1);
}

/**
 * SPA 허용 주소 확인
 *  - 게시판 글쓰기 처럼 웹에디터, 페이지 나가기 이벤트 걸린 페이지들은 제외해야 함
 *
 * @param url
 * @returns {boolean}
 */
function simpleSpaIsAllowUrl(url) {
    var a = document.createElement('a');
    a.href = url;
    var disallowUrls = [
        '/bbs/write.php',
        '/bbs/link.php',
        '/bbs/banner_link.php',
        '/plugin/attendance/attendance.php',
        '/bbs/memo.php'
    ];

    return ($.inArray(a.pathname, disallowUrls) === -1);
}

/**
 * 절대 경로 변환
 *   - ../bbs/memo.php 형태의 상대 주소는 history 에서 문제가 발생하므로, 절대 경로로 변환하여 저장해야 함
 * @param url
 * @returns {string}
 */
function simpleSpaAbsoluteUrl(url) {
    var a = document.createElement('a');
    a.href = url;

    return a.protocol + '//' + a.host + a.pathname + a.search + a.hash;
}

/**
 * Simple SPA 뒤로가기 이벤트
 *  - 주의) 최초 페이지 로딩시 1회만 실행되어야 함.  즉 app.js 중복 로딩되면 안됨.
 */
if (simpleSpaEnable) {
    window.history.replaceState({url: window.location.href}, "", window.location.href);
    $(window).bind('popstate', function (event) {
        var state = event.originalEvent.state;
        //console.log('popstate: ' + state.url);
        if (state && state.url) {
            simpleSpaRedirect(state.url, "", "", true);
        }
    });
}

/**
 * Side Menu
 */
var slideLeft;
function sideMenu() {
    if (document.getElementById('o-wrapper')) {
        slideLeft = new Menu({
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
}

/**
 * 앱 이벤트들
 */
function appEvents() {
    sideMenu();

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

    simpleSpa();
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
});
