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
$(document).ajaxStop(function () {
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
    //$('#wrap-loading').fadeIn(100, function () {
    simpleSpaRedirect(url);
    //});
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

var simpleSpaEnable = window.history && window.history.pushState,
    simpleSpaLimit = 10,
    simpleSpaCount = 0;

/**
 * Simple SPA
 *  - �۾��� ������������ ������ ������ �̺�Ʈ�� ����Ͽ� SPA ����
 *  - # �� �����ϴ� �ؽ� �ּҴ� ����
 *  - javascript:, mailto: ����
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
                // �������� ��â�� ���, �̺�Ʈ ����
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
 * Simple SPA ���� ��������
 *  - head ���� ������ body �� �����ͼ� ��ü.
 *  - pushState ���� ������������ ���.  IE 10, Android 4.3
 *    - http://caniuse.com/#search=pushstate
 *  - �޸� ���� ���ɼ��� ����Ͽ� simpleSpaLimit �ʰ��ÿ� ajax ��� ������ �̵��Ͽ� �ʱ�ȭ ��Ŵ.
 *  - �ε� �̹��� �����ֵ��� �񵿱�� ó��
 *  - �ܺ� �������� ��� ajax �� �ƴ�  �̵� ó��
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

            // ���̵�ٰ� Ŭ���� ���¿��� �̵��� ��츦 ������, ���̵�� �ݱ� �̺�Ʈ
            if (slideLeft) {
                slideLeft.close();
            }

            $.ajax(url, {
                //async: false
                cache: false,
                timeout: 10000
            }).done(function (data) {
                // ���� ��ü
                var $body = $('body');

                // alert, window.close ������ �����ϴ� �������� ���â�� �����ְ�, ������ �̵����� ����.
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
                    // ajax history ����
                    if (!popState) {
                        // ���� URL�� ������ ��� �߰� ����
                        if (!window.history.state || !window.history.state.url || window.history.state.url !== url) {
                            window.history.pushState({url: url}, title, url);
                        }
                    }

                    // history ������ �ε��ؾ� js ���� �ȳ�.
                    $body.html(data);

                    // ������ �̵��Ŀ� ���ΰ�ħó�� ž���� ��ũ�� �̵�
                    $body.scrollTop(0);

                    // ���ο� ���뿡 �̺�Ʈ ����
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
 * �佺Ʈ �޽���
 *  - �ۿ����� �佺Ʈ �޽����� ��� �������� ������� ��.
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
 * �˾� ó��
 *  - ���� ���� �����Ͽ� ó��
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
 * ���� ��ũ ����
 *   - ������ ����
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
 * SPA ��� �ּ� Ȯ��
 *  - �Խ��� �۾��� ó�� ��������, ������ ������ �̺�Ʈ �ɸ� ���������� �����ؾ� ��
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
 * ���� ��� ��ȯ
 *   - ../bbs/memo.php ������ ��� �ּҴ� history ���� ������ �߻��ϹǷ�, ���� ��η� ��ȯ�Ͽ� �����ؾ� ��
 * @param url
 * @returns {string}
 */
function simpleSpaAbsoluteUrl(url) {
    var a = document.createElement('a');
    a.href = url;

    return a.protocol + '//' + a.host + a.pathname + a.search + a.hash;
}

/**
 * Simple SPA �ڷΰ��� �̺�Ʈ
 *  - ����) ���� ������ �ε��� 1ȸ�� ����Ǿ�� ��.  �� app.js �ߺ� �ε��Ǹ� �ȵ�.
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
 * �� �̺�Ʈ��
 */
function appEvents() {
    sideMenu();

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

    simpleSpa();
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
});
