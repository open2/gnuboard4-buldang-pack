/*!
 * froala_editor v2.0.5 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms
 * Copyright 2014-2015 Froala Labs
 */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = function( root, jQuery ) {
            if ( jQuery === undefined ) {
                // require('jQuery') returns a factory that requires window to
                // build a jQuery instance, we normalize how we use modules
                // that require this pattern but the window provided is a noop
                // if it's defined (how jquery works)
                if ( typeof window !== 'undefined' ) {
                    jQuery = require('jquery');
                }
                else {
                    jQuery = require('jquery')(root);
                }
            }
            factory(jQuery);
            return jQuery;
        };
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

  'use strict';

  $.extend($.FroalaEditor.POPUP_TEMPLATES, {
    emoticons: '[_BUTTONS_][_EMOTICONS_]'
  })

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    emoticonsStep: 8,
    emoticonsSet: [
      { code: '&#x1f600;', desc: 'Grinning face' },
      { code: '&#x1f601;', desc: 'Grinning face with smiling eyes' },
      { code: '&#x1f602;', desc: 'Face with tears of joy' },
      { code: '&#x1f603;', desc: 'Smiling face with open mouth' },
      { code: '&#x1f604;', desc: 'Smiling face with open mouth and smiling eyes' },
      { code: '&#x1f605;', desc: 'Smiling face with open mouth and cold sweat' },
      { code: '&#x1f606;', desc: 'Smiling face with open mouth and tightly-closed eyes' },
      { code: '&#x1f607;', desc: 'Smiling face with halo' },

      { code: '&#x1f608;', desc: 'Smiling face with horns' },
      { code: '&#x1f609;', desc: 'Winking face' },
      { code: '&#x1f60a;', desc: 'Smiling face with smiling eyes' },
      { code: '&#x1f60b;', desc: 'Face savoring delicious food' },
      { code: '&#x1f60c;', desc: 'Relieved face' },
      { code: '&#x1f60d;', desc: 'Smiling face with heart-shaped eyes' },
      { code: '&#x1f60e;', desc: 'Smiling face with sunglasses' },
      { code: '&#x1f60f;', desc: 'Smirking face' },

      { code: '&#x1f610;', desc: 'Neutral face' },
      { code: '&#x1f611;', desc: 'Expressionless face' },
      { code: '&#x1f612;', desc: 'Unamused face' },
      { code: '&#x1f613;', desc: 'Face with cold sweat' },
      { code: '&#x1f614;', desc: 'Pensive face' },
      { code: '&#x1f615;', desc: 'Confused face' },
      { code: '&#x1f616;', desc: 'Confounded face' },
      { code: '&#x1f617;', desc: 'Kissing face' },

      { code: '&#x1f618;', desc: 'Face throwing a kiss' },
      { code: '&#x1f619;', desc: 'Kissing face with smiling eyes' },
      { code: '&#x1f61a;', desc: 'Kissing face with closed eyes' },
      { code: '&#x1f61b;', desc: 'Face with stuck out tongue' },
      { code: '&#x1f61c;', desc: 'Face with stuck out tongue and winking eye' },
      { code: '&#x1f61d;', desc: 'Face with stuck out tongue and tightly-closed eyes' },
      { code: '&#x1f61e;', desc: 'Disappointed face' },
      { code: '&#x1f61f;', desc: 'Worried face' },

      { code: '&#x1f620;', desc: 'Angry face' },
      { code: '&#x1f621;', desc: 'Pouting face' },
      { code: '&#x1f622;', desc: 'Crying face' },
      { code: '&#x1f623;', desc: 'Persevering face' },
      { code: '&#x1f624;', desc: 'Face with look of triumph' },
      { code: '&#x1f625;', desc: 'Disappointed but relieved face' },
      { code: '&#x1f626;', desc: 'Frowning face with open mouth' },
      { code: '&#x1f627;', desc: 'Anguished face' },

      { code: '&#x1f628;', desc: 'Fearful face' },
      { code: '&#x1f629;', desc: 'Weary face' },
      { code: '&#x1f62a;', desc: 'Sleepy face' },
      { code: '&#x1f62b;', desc: 'Tired face' },
      { code: '&#x1f62c;', desc: 'Grimacing face' },
      { code: '&#x1f62d;', desc: 'Loudly crying face' },
      { code: '&#x1f62e;', desc: 'Face with open mouth' },
      { code: '&#x1f62f;', desc: 'Hushed face' },

      { code: '&#x1f630;', desc: 'Face with open mouth and cold sweat' },
      { code: '&#x1f631;', desc: 'Face screaming in fear' },
      { code: '&#x1f632;', desc: 'Astonished face' },
      { code: '&#x1f633;', desc: 'Flushed face' },
      { code: '&#x1f634;', desc: 'Sleeping face' },
      { code: '&#x1f635;', desc: 'Dizzy face' },
      { code: '&#x1f636;', desc: 'Face without mouth' },
      { code: '&#x1f637;', desc: 'Face with medical mask' }
    ],
    emoticonsButtons: ['emoticonsBack', '|']
  });

  $.FroalaEditor.PLUGINS.emoticons = function (editor) {
    /*
     * Show the emoticons popup.
     */
    function _showEmoticonsPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="emoticons"]');

      var $popup = editor.popups.get('emoticons');
      if (!$popup) $popup = _initEmoticonsPopup();

      if (!$popup.hasClass('fr-active')) {
        // Colors popup
        editor.popups.refresh('emoticons');
        editor.popups.setContainer('emoticons', editor.$tb);

        // Colors popup left and top position.
        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);

        editor.popups.show('emoticons', left, top, $btn.outerHeight());
      }
    }

    /*
     * Hide emoticons popup.
     */
    function _hideEmoticonsPopup () {
      // Hide popup.
      editor.popups.hide('emoticons');
    }

    /**
     * Init the emoticons popup.
     */
    function _initEmoticonsPopup () {
      var emoticons_buttons = '';

      if (editor.opts.toolbarInline) {
        // Colors buttons.
        if (editor.opts.emoticonsButtons.length > 0) {
          emoticons_buttons = '<div class="fr-buttons fr-emoticons-buttons">' + editor.button.buildList(editor.opts.emoticonsButtons) + '</div>';
        }
      }

      var template = {
        buttons: emoticons_buttons,
        emoticons: _emoticonsHTML()
      };

      // Create popup.
      var $popup = editor.popups.create('emoticons', template);

      // Assing tooltips to buttons.
      editor.tooltip.bind($popup, '.fr-emoticon');

      return $popup;
    }

    /*
     * HTML for the emoticons popup.
     */
    function _emoticonsHTML () {
      // Create emoticons html.
      var emoticons_html = '<div>';

      // Add emoticons.
      for (var i = 0; i < editor.opts.emoticonsSet.length; i++) {
        if (i !== 0 && i % editor.opts.emoticonsStep === 0) {
          emoticons_html += '<br>';
        }

        emoticons_html += '<span class="fr-command fr-emoticon" data-cmd="insertEmoticon" title="' + editor.language.translate(editor.opts.emoticonsSet[i].desc) + '" data-param1="' + editor.opts.emoticonsSet[i].code + '">' + editor.opts.emoticonsSet[i].code + '</span>';
      }

      emoticons_html += '</div>';

      return emoticons_html;
    }

    /*
     * Insert emoticon.
     */
    function insert (emoticon) {
      // Insert emoticon.
      editor.html.insert('<span class="fr-emoticon">' + emoticon + '</span>' + $.FroalaEditor.MARKERS, true);
    }

    /*
     * Go back to the inline editor.
     */
    function back () {
      editor.popups.hide('emoticons');
      editor.toolbar.showInline();
    }

    /*
     * Init emoticons.
     */
    function _init () {
      // Replace emoticons with unicode.
      editor.events.on('html.get', function (html) {
        for (var i = 0; i < editor.opts.emoticonsSet.length; i++) {
          var em = editor.opts.emoticonsSet[i];
          var text = $('<div>').html(em.code).text();
          html = html.split(text).join(em.code);
        }

        return html;
      });

      var inEmoticon = function () {
        if (!editor.selection.isCollapsed()) return false;

        var s_el = editor.selection.element();
        var e_el = editor.selection.endElement();

        if ($(s_el).hasClass('fr-emoticon')) return s_el;
        if ($(e_el).hasClass('fr-emoticon')) return e_el;

        var range = editor.selection.ranges(0);
        var container = range.startContainer;
        if (container.nodeType == Node.ELEMENT_NODE) {
          if (container.childNodes.length > 0 && range.startOffset > 0) {
            var node = container.childNodes[range.startOffset - 1];
            if ($(node).hasClass('fr-emoticon')) {
              return node;
            }
          }
        }

        return false;
      }

      editor.events.on('keydown', function (e) {
        if (editor.keys.isCharacter(e.which) && editor.selection.inEditor()) {
          var range = editor.selection.ranges(0);
          var el = inEmoticon();
          if (el) {
            if (range.startOffset === 0) {
              $(el).before($.FroalaEditor.MARKERS + $.FroalaEditor.INVISIBLE_SPACE);
            }
            else {
              $(el).after($.FroalaEditor.INVISIBLE_SPACE + $.FroalaEditor.MARKERS);
            }
            editor.selection.restore();
          }
        }
      });

      editor.events.on('keyup', function () {
        var emtcs = editor.$el.get(0).querySelectorAll('.fr-emoticon');

        for (var i = 0; i < emtcs.length; i++) {
          if (typeof emtcs[i].textContent != 'undefined' && emtcs[i].textContent.replace(/\u200B/gi, '').length === 0) {
            $(emtcs[i]).remove();
          }
        }
      });
    }

    return {
      _init: _init,
      insert: insert,
      showEmoticonsPopup: _showEmoticonsPopup,
      hideEmoticonsPopup: _hideEmoticonsPopup,
      back: back
    }
  }

  // Toolbar emoticons button.
  $.FroalaEditor.DefineIcon('emoticons', { NAME: 'smile-o' });
  $.FroalaEditor.RegisterCommand('emoticons', {
    title: 'Emoticons',
    undo: false,
    focus: true,
    refreshOnCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('emoticons')) {
        this.emoticons.showEmoticonsPopup();
      }
      else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('emoticons');
      }
    }
  });

  // Insert emoticon command.
  $.FroalaEditor.RegisterCommand('insertEmoticon', {
    callback: function (cmd, val) {
      // Insert emoticon.
      this.emoticons.insert(val);

      // Hide emoticons popup.
      this.emoticons.hideEmoticonsPopup();
    }
  });

  // Emoticons back.
  $.FroalaEditor.DefineIcon('emoticonsBack', { NAME: 'arrow-left' });
  $.FroalaEditor.RegisterCommand('emoticonsBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.emoticons.back();
    }
  });

}));
