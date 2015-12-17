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

  $.FroalaEditor.PLUGINS.fullscreen = function (editor) {
    var old_scroll;

    /**
     * Check if fullscreen mode is active.
     */
    function isActive () {
      return editor.$box.hasClass('fr-fullscreen');
    }

    /**
     * Turn fullscreen on.
     */
    var $placeholder;
    function _on () {
      old_scroll = $(editor.original_window).scrollTop();
      editor.$box.toggleClass('fr-fullscreen');
      $('body').toggleClass('fr-fullscreen');
      $placeholder = $('<div style="display: none;"></div>');
      editor.$box.after($placeholder).appendTo($('body'));

      if (editor.helpers.isMobile()) {
        editor.$tb.data('parent', editor.$tb.parent());
        editor.$tb.prependTo(editor.$box);
        if (editor.$tb.data('sticky-dummy')) {
          editor.$tb.after(editor.$tb.data('sticky-dummy'));
        }
      }

      editor.$wp.css('max-height', '');
      editor.$wp.css('height', editor.original_window.innerHeight - (editor.opts.toolbarInline ? 0 : editor.$tb.outerHeight()));

      if (editor.opts.toolbarInline) editor.toolbar.showInline();

      editor.events.trigger('charCounter.update');
      editor.$window.trigger('scroll.sticky' + editor.id);
    }

    /**
     * Turn fullscreen off.
     */
    function _off () {
      $placeholder.replaceWith(editor.$box);
      editor.$box.toggleClass('fr-fullscreen');
      $('body').toggleClass('fr-fullscreen');

      editor.$tb.prependTo(editor.$tb.data('parent'));
      if (editor.$tb.data('sticky-dummy')) {
        editor.$tb.after(editor.$tb.data('sticky-dummy'));
      }

      editor.$wp.css('height', '');
      editor.size.refresh();

      $(editor.original_window).scrollTop(old_scroll)

      if (editor.opts.toolbarInline) editor.toolbar.showInline();

      editor.events.trigger('charCounter.update');

      if (editor.opts.toolbarSticky) {
        if (editor.opts.toolbarStickyOffset) {
          if (editor.opts.toolbarBottom) {
            editor.$tb
              .css('bottom', editor.opts.toolbarStickyOffset)
              .data('bottom', editor.opts.toolbarStickyOffset);
          }
          else {
            editor.$tb
              .css('top', editor.opts.toolbarStickyOffset)
              .data('top', editor.opts.toolbarStickyOffset);
          }
        }
      }

      editor.$window.trigger('scroll.sticky' + editor.id);
    }

    /**
     * Exec fullscreen.
     */
    function toggle () {
      if (!isActive()) {
        _on();
      }
      else {
        _off();
      }

      refresh(editor.$tb.find('.fr-command[data-cmd="fullscreen"]'));
    }

    function refresh ($btn) {
      var active = isActive();

      $btn.toggleClass('fr-active', active);
      $btn.find('i')
        .toggleClass('fa-expand', !active)
        .toggleClass('fa-compress', active);
    }

    function _init () {
      if (!editor.$wp) return false;

      $(editor.original_window).on('resize.fullscreen' + editor.id, function () {
        if (isActive()) {
          _off();
          _on();
        }
      });

      editor.events.on('toolbar.hide', function () {
        if (isActive() && editor.helpers.isMobile()) return false;
      })

      editor.events.on('destroy', function () {
        $(editor.original_window).off('resize.fullscreen' + editor.id);
      });
    }

    return {
      _init: _init,
      toggle: toggle,
      refresh: refresh,
      isActive: isActive
    }
  }

  // Register the font size command.
  $.FroalaEditor.RegisterCommand('fullscreen', {
    title: 'Fullscreen',
    undo: false,
    focus: false,
    forcedRefresh: true,
    callback: function () {
      this.fullscreen.toggle();
    },
    refresh: function ($btn) {
      this.fullscreen.refresh($btn);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('fullscreen', {
    NAME: 'expand'
  });

}));
