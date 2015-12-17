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

  $.extend($.FroalaEditor.DEFAULTS, {
    fontFamily: {
      'Arial,Helvetica,sans-serif': 'Arial',
      'Georgia,serif': 'Georgia',
      'Impact,Charcoal,sans-serif': 'Impact',
      'Tahoma,Geneva,sans-serif': 'Tahoma',
      '\'Times New Roman\',Times,serif': 'Times New Roman',
      'Verdana,Geneva,sans-serif': 'Verdana'
    },
    fontFamilySelection: false,
    fontFamilyDefaultSelection: 'Time New Roman'
  })

  $.FroalaEditor.PLUGINS.fontFamily = function (editor) {
    function apply (val) {
      editor.commands.applyProperty('font-family', val);
    }

    function refreshOnShow($btn, $dropdown) {
      var val = $(editor.selection.element()).css('font-family').replace(/"/g, '\'').replace(/, /g, ',');
      $dropdown.find('.fr-command.fr-active').removeClass('fr-active');
      $dropdown.find('.fr-command[data-param1="' + val + '"]').addClass('fr-active');

      var $list = $dropdown.find('.fr-dropdown-list');
      var $active = $dropdown.find('.fr-active').parent();
      if ($active.length) {
        $list.parent().scrollTop($active.offset().top - $list.offset().top - ($list.parent().outerHeight() / 2 - $active.outerHeight() / 2));
      }
      else {
        $list.parent().scrollTop(0);
      }
    }

    function refresh($btn, $dropdown) {
      var val = $(editor.selection.element()).css('font-family').replace(/"/g,'\'').replace(/, /g, ',');

      $btn.find('> span').text($dropdown.find('.fr-command[data-param1="' + val + '"]').text() || editor.opts.fontFamilyDefaultSelection);
    }

    return {
      apply: apply,
      refreshOnShow: refreshOnShow,
      refresh: refresh
    }
  }

  // Register the font size command.
  $.FroalaEditor.RegisterCommand('fontFamily', {
    type: 'dropdown',
    displaySelection: function (editor) {
      return editor.opts.fontFamilySelection;
    },
    defaultSelection: function (editor) {
      return editor.opts.fontFamilyDefaultSelection;
    },
    displaySelectionWidth: 120,
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options = this.opts.fontFamily;
      for (var val in options) {
        c += '<li><a class="fr-command" data-cmd="fontFamily" data-param1="' + val + '" style="font-family: ' + val + '" title="' + options[val] + '">' + options[val] + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    title: 'Font Family',
    callback: function (cmd, val) {
      this.fontFamily.apply(val);
    },
    refresh: function ($btn, $dropdown) {
      this.fontFamily.refresh($btn, $dropdown);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.fontFamily.refreshOnShow($btn, $dropdown);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('fontFamily', {
    NAME: 'font'
  });

}));
