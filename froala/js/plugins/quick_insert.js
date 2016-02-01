/*!
 * froala_editor v2.1.0 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms
 * Copyright 2014-2016 Froala Labs
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

  // Extend defaults.
  $.extend($.FroalaEditor.DEFAULTS, {
    quickInsertOffset: 10,
    quickInsertButtons: ['image', 'table', 'ul', 'ol', 'hr', 'paragraph']
  });

  $.FroalaEditor.QUICK_INSERT_BUTTONS = {
    image: {
      icon: 'insertImage',
      callback: function (btn) {
        $(btn).after('<input accept="image/*" name="quickInsertImage' + this.id + '" style="display: none;" type="file">');
        var editor = this;
        $(btn).next().on('change', function () {
          if (this.files) {
            var helper = btn.parentNode;
            var helperParent = helper.parentNode;
            if (helperParent.tagName == 'OL' || helperParent.tagName == 'UL') {
              $(helper).replaceWith('<li>' + $.FroalaEditor.MARKERS + '<br></li>')
            }
            else {
              $(helper).replaceWith('<p>' + $.FroalaEditor.MARKERS + '<br></p>');
            }

            editor.quickInsert.hide(true);
            editor.selection.restore();
            editor.image.showInsertPopup();
            var $popup = editor.popups.get('image.insert');
            editor.position.forSelection($popup);

            editor.image.upload(this.files);
          }
        })
        $(btn).next().trigger('click');
      },
      requiredPlugin: 'image',
      title: 'Insert Image'
    },
    table: {
      icon: 'insertTable',
      callback: function (btn) {
        var helper = btn.parentNode;
        var helperParent = helper.parentNode;
        if (helperParent.tagName == 'OL' || helperParent.tagName == 'UL') {
          $(helper).replaceWith('<li>' + $.FroalaEditor.MARKERS + '<br></li>')
        }
        else {
          $(helper).replaceWith('<p>' + $.FroalaEditor.MARKERS + '<br></p>');
        }

        this.quickInsert.hide(true);
        this.selection.restore();

        this.table.insert(2, 2);
        this.undo.saveStep();
      },
      requiredPlugin: 'table',
      title: 'Insert Table'
    },
    ol: {
      icon: 'formatOL',
      callback: function (btn) {
        var helper = btn.parentNode;
        var helperParent = helper.parentNode;
        if (helperParent.tagName == 'OL' || helperParent.tagName == 'UL') {
          $(helper).replaceWith('<li>' + $.FroalaEditor.MARKERS + '<br></li>')
        }
        else {
          $(helper).replaceWith('<p>' + $.FroalaEditor.MARKERS + '<br></p>');
        }

        this.quickInsert.hide(true);
        this.selection.restore();

        if (helperParent.tagName == 'UL') {
          this.lists.format(helperParent.tagName);
        }

        if (helperParent.tagName != 'OL') {
          this.lists.format('OL');
        }

        this.undo.saveStep();
      },
      requiredPlugin: 'lists',
      title: 'Ordered List'
    },
    ul: {
      icon: 'formatUL',
      callback: function (btn) {
        var helper = btn.parentNode;
        var helperParent = helper.parentNode;
        if (helperParent.tagName == 'OL' || helperParent.tagName == 'UL') {
          $(helper).replaceWith('<li>' + $.FroalaEditor.MARKERS + '<br></li>')
        }
        else {
          $(helper).replaceWith('<p>' + $.FroalaEditor.MARKERS + '<br></p>');
        }

        this.quickInsert.hide(true);
        this.selection.restore();

        if (helperParent.tagName == 'OL') {
          this.lists.format(helperParent.tagName);
        }

        if (helperParent.tagName != 'UL') {
          this.lists.format('UL');
        }

        this.undo.saveStep();
      },
      requiredPlugin: 'lists',
      title: 'Unordered List'
    },
    hr: {
      icon: 'insertHR',
      callback: function (btn) {
        var helper = btn.parentNode;
        var helperParent = helper.parentNode;
        if (helperParent.tagName == 'OL' || helperParent.tagName == 'UL') {
          $(helper).replaceWith('<li>' + $.FroalaEditor.MARKERS + '<br></li>')
        }
        else {
          $(helper).replaceWith('<p>' + $.FroalaEditor.MARKERS + '<br></p>');
        }

        this.quickInsert.hide(true);
        this.selection.restore();

        this.commands.insertHR();
        this.undo.saveStep();
      },
      title: 'Insert Horizontal Line'
    },
    paragraph: {
      icon: 'paragraphFormat',
      callback: function (btn) {
        var helper = btn.parentNode;
        var helperParent = helper.parentNode;
        if (helperParent.tagName == 'OL' || helperParent.tagName == 'UL') {
          $(helper).replaceWith('<li>' + $.FroalaEditor.MARKERS + '<br></li>')
        }
        else {
          var default_tag = this.html.defaultTag();

          if (default_tag) {
            $(helper).replaceWith('<' + default_tag + '>' + $.FroalaEditor.MARKERS + '<br></' + default_tag + '>');
          }
          else {
            $(helper).replaceWith($.FroalaEditor.MARKERS + '<br>');
          }
        }

        this.quickInsert.hide(true);
        this.selection.restore();
        this.undo.saveStep();
      },
      title: 'Break'
    }
  }

  $.FroalaEditor.RegisterQuickInsertCommand = function (name, data) {
    $.FroalaEditor.QUICK_INSERT_BUTTONS[name] = data;
  }

  $.FroalaEditor.PLUGINS.quickInsert = function (editor) {
    var $quick_insert;
    var mouseDownFlag;
    var mouseMoveTimer;

    /*
     * Show quick insert.
     * Compute top, left, width and show the quick insert.
     */
    function _show ($tag, force) {
      if (helper_visible && !force && $quick_insert.hasClass('fr-visible')) return false;

      // quick insert's possition and width.
      var breakerTop;
      var breakerLeft;
      var breakerWidth;
      var $parent_tag;

      // Compute quick insert's possition and width.
      $parent_tag = $tag.parent();

      breakerTop = $tag.offset().top;
      breakerWidth = $parent_tag.outerWidth();
      breakerLeft = editor.$box.offset().left;

      if (editor.opts.iframe) {
        breakerTop += editor.$iframe.offset().top - $(editor.original_window).scrollTop();
      }

      breakerTop = breakerTop - editor.window.pageYOffset;
      breakerLeft = breakerLeft - editor.window.pageXOffset - $quick_insert.find('> a').outerWidth();

      // Set quick insert's top, left and width.
      $quick_insert.css('top', breakerTop);
      $quick_insert.css('left', breakerLeft);
      $quick_insert.css('width', $tag.offset().left - breakerLeft);
      $quick_insert.css('height', Math.ceil(parseFloat($tag.outerHeight()) / 2) * 2);

      if ($quick_insert.find('> a.fr-floating-btn').outerHeight() > $tag.outerHeight() || $tag.hasClass('fr-qi-helper')) {
        var c_height = $quick_insert.outerHeight();
        $quick_insert.height($quick_insert.find('> a.fr-floating-btn').outerHeight());
        $quick_insert.css('top', breakerTop - ($quick_insert.find('> a.fr-floating-btn').outerHeight() - c_height) / 2);
      }

      $quick_insert.data('tag', $tag);

      // Show the quick insert.
      $quick_insert.addClass('fr-visible');
    }

    /*
     * Check if tag is in the quick insert list and in the editor as well.
     * Returns the tag from the quick insert list or false if the tag is not in the list.
     */
    function _validateTag (tag) {
      if (tag) {
        var $tag = $(tag);

        // Make sure tag is inside the editor.
        if (editor.$el.find($tag).length === 0) {
          hide();
          return null;
        }

        // Tag is in the quick insert tags list.
        if (tag.nodeType != Node.TEXT_NODE && editor.node.isBlock(tag) && ['TR', 'TH', 'TD', 'TBODY', 'THEAD', 'TABLE'].indexOf(tag.tagName) < 0) {
          return $tag;
        }

        // Tag's parent is in the quick insert tags list.
        else if (editor.node.blockParent($tag.get(0))) {
          tag = editor.node.blockParent($tag.get(0));

          if (['TR', 'TH', 'TD', 'TBODY', 'THEAD', 'TABLE'].indexOf(tag.tagName) >= 0) {
            hide();
            return false;
          }

          return $(tag);
        }
      }

      hide();
      return null;
    }

    /*
     * Get the tag under the mouse cursor.
     */
    function _tagUnder (e) {
      mouseMoveTimer = null;

      // Inline toolbar is visible.
      if (editor.$tb.hasClass('fr-inline') && editor.$tb.is(':visible')) return false;

      // Popup is visible.
      if (editor.popups.areVisible()) return false;

      // The tag for which the quick insert should be showed.
      var $tag = null;

      // The tag under the mouse cursor.
      var tag_under = editor.document.elementFromPoint(e.pageX - editor.window.pageXOffset, e.pageY - editor.window.pageYOffset);
      var i;
      var tag_above;
      var tag_below;

      // Tag is the editor element. Look for closest tag above and bellow.
      if (editor.node.isElement(tag_under)) {
        // Look 1px up and 1 down until a tag is found or the quick insert offset is reached.
        for (i = 1; i <= editor.opts.quickInsertOffset; i++) {
          // Look for tag above.
          tag_above = editor.document.elementFromPoint(e.pageX - editor.window.pageXOffset, e.pageY - editor.window.pageYOffset - i);

          // We found a tag above.
          if (tag_above && !editor.node.isElement(tag_above) && tag_above != editor.$wp.get(0) && $(tag_above).parents(editor.$wp).length) {
            $tag = _validateTag(tag_above);
            break;
          }

          // Look for tag below.
          tag_below = editor.document.elementFromPoint(e.pageX - editor.window.pageXOffset, e.pageY - editor.window.pageYOffset + i);

          // We found a tag bellow.
          if (tag_below && !editor.node.isElement(tag_below) && tag_below != editor.$wp.get(0) && $(tag_below).parents(editor.$wp).length) {
            $tag = _validateTag(tag_below);
            break;
          }
        }

      // Tag is not the editor element.
      } else {
        // Check if the tag is in the quick insert list.
        $tag = _validateTag(tag_under);
      }

      // Check tag siblings.
      if ($tag) {
        var parent = $tag.parent().get(0);
        if (['UL', 'OL'].indexOf(parent.tagName) >= 0) parent = parent.parentNode;

        if (e.pageX - editor.window.pageXOffset - $tag.offset().left < $tag.outerWidth() / 2 && parent == editor.$el.get(0)) {
          _show($tag);
        }
        else {
          hide();
        }
      }
    }

    /*
     * Set mouse timer to improve performance.
     */
    function _mouseTimer (e) {
      if (mouseDownFlag === false) {
        if (mouseMoveTimer) {
          clearTimeout(mouseMoveTimer);
        }

        mouseMoveTimer = setTimeout(_tagUnder, 30, e);
      }
    }

    /*
     * Hide quick insert and prevent timer from showing it again.
     */
    function hide (force) {
      if (force) helper_visible = false;
      if (helper_visible) return false;

      if (mouseMoveTimer) {
        clearTimeout(mouseMoveTimer);
      }

      editor.html.checkIfEmpty();

      $quick_insert.removeClass('fr-visible fr-on');
    }

    /*
     * Notify that mouse is down and prevent quick insert from showing.
     * This may happen either for selection or for drag.
     */
    function _mouseDown () {
      mouseDownFlag = true;
      hide();
    }

    /*
     * Notify that mouse is no longer pressed.
     */
    function _mouseUp () {
      mouseDownFlag = false;
    }

    /*
     * Add new line between the tags.
     */
    var helper_visible = false;
    function _doLineBreak (e) {
      e.preventDefault();

      if (helper_visible) {
        _hideHelper();
      }
      else {
        // Tags between which that line break needs to be done.
        var $tag = $quick_insert.data('tag');

        var btns = editor.opts.quickInsertButtons;

        var empty_node = (editor.node.isEmpty($tag.get(0)) && ['DIV', 'P'].indexOf($tag.get(0).tagName) >= 0);
        var btns_html = '<div class="fr-qi-helper"' + (empty_node ? ' data-fr-empty="' + $tag.get(0).outerHTML + '"' : '') + '>';
        var idx = 0;
        for (var i = 0; i < btns.length; i++) {
          var info = $.FroalaEditor.QUICK_INSERT_BUTTONS[btns[i]];
          if (info) {
            if (!info.requiredPlugin || ($.FroalaEditor.PLUGINS[info.requiredPlugin] && editor.opts.pluginsEnabled.indexOf(info.requiredPlugin) >= 0)) {
              btns_html += '<a class="fr-btn" role="button" title="' + editor.language.translate(info.title) + '" tabindex="-1" data-cmd="' + btns[i] + '" style="transition-delay: ' + (0.025 * (idx++)) + 's;">' + editor.icon.create(info.icon) + '</a>';
            }
          }
        }
        btns_html += '</div>';

        if (editor.node.isEmpty($tag.get(0)) && ['DIV', 'P'].indexOf($tag.get(0).tagName) >= 0) {
          $tag.replaceWith(btns_html);
        }
        else {
          $tag.before(btns_html);
        }
        setTimeout(function () {
          editor.$el.find('.fr-qi-helper > a').addClass('fr-size-1');
          _show(editor.$el.find('.fr-qi-helper'));
          $quick_insert.addClass('fr-on');
          helper_visible = true;
        }, 10);
      }
    }

    function _hideHelper () {
      var $helper = editor.$el.find('.fr-qi-helper');
      if ($helper.data('fr-empty')) {
        $helper.replaceWith($helper.data('fr-empty'));
      }
      else {
        $helper.remove();
      }

      helper_visible = false;
      hide();
    }

    /*
     * Initialize the quick insert.
     */
    function _initquickInsert () {
      // Append quick insert HTML to editor wrapper.
      $quick_insert = $('<div class="fr-quick-insert"><a class="fr-floating-btn" role="button" tabindex="-1" title="' + editor.language.translate('Break') + '"><svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M22,16.75 L16.75,16.75 L16.75,22 L15.25,22.000 L15.25,16.75 L10,16.75 L10,15.25 L15.25,15.25 L15.25,10 L16.75,10 L16.75,15.25 L22,15.25 L22,16.75 Z"/></svg></a></div>');
      editor.$box.append($quick_insert);

      // Editor destroy.
      editor.events.on('destroy', function () {
        $quick_insert.html('').removeData().remove();
      }, true);

      editor.events.on('focus', _hideHelper, true);
      editor.events.on('window.mousedown', _hideHelper, true);
      editor.events.on('commands.before', _hideHelper, true);

      // $quick_insert.on('mouseleave.quickInsert' + editor.id, _hide);

      $quick_insert.on('mousemove', function (e) {
        e.stopPropagation();
      })

      // Add new line break.
      $quick_insert.on('mousedown', 'a', function (e) {
        e.stopPropagation();
      });
      $quick_insert.on('click', 'a', _doLineBreak);

      editor.events.bindClick(editor.$el, '.fr-qi-helper > a.fr-btn', function (e) {
        var cmd = $(e.currentTarget).data('cmd');

        $.FroalaEditor.QUICK_INSERT_BUTTONS[cmd].callback.apply(editor, [e.currentTarget]);
      });

      editor.tooltip.bind(editor.$el, '.fr-qi-helper > a.fr-btn');

      // Editor destroy.
      editor.events.on('destroy', function () {
        $quick_insert.off('mouseleave.quickInsert');
        $quick_insert.off('mousedown');
        $quick_insert.off('mousedown', 'a');
        $quick_insert.off('click', 'a');
      }, true);
    }

    /*
     * Tear up.
     */
    function _init () {
      if (!editor.$wp) return false;

      if (editor.opts.iframe) {
        editor.$el.parent('html').find('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">');
      }

      _initquickInsert();

      // Remember if mouse is clicked so the quick insert does not appear.
      mouseDownFlag = false;

      // Check tags under the mouse to see if the quick insert needs to be shown.
      editor.$window.on('mousemove.quickInsert' + editor.id, _mouseTimer);

      // Hide the quick insert if the page is scrolled.
      $(editor.window).on('scroll.quickInsert' + editor.id, function () {
        if (helper_visible) {
          _show(editor.$el.find('.fr-qi-helper'), true);
        }
        else {
          hide();
        }
      });

      // Prevent quick insert from showing while selecting text or dragging images.
      $(editor.window).on('mousedown.quickInsert' + editor.id, _mouseDown);

      // Mouse is not pressed anymore, quick insert may be shown.
      $(editor.window).on('mouseup.quickInsert' + editor.id, _mouseUp);

      // Clean getting the HTML.
      editor.events.on('html.get', function (html) {
        html = html.replace(/<(div)((?:[\w\W]*?))class="([\w\W]*?)fr-qi-helper([\w\W]*?)"((?:[\w\W]*?))>((?:[\w\W]*?))<\/(div)>/g, '');

        return html;
      });

      // Editor destroy.
      editor.events.on('destroy', function () {
        editor.$window.off('mousemove.quickInsert' + editor.id);
        $(editor.window).off('scroll.quickInsert' + editor.id);
        $(editor.window).off('mousedown.quickInsert' + editor.id);
        $(editor.window).off('mouseup.quickInsert' + editor.id);
      }, true);
    }

    return {
      _init: _init,
      hide: hide
    }
  };

}));
