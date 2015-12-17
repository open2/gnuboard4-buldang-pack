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
    inlineStyles: {
      'Big Red': 'font-size: 20px; color: red;',
      'Small Blue': 'font-size: 14px; color: blue;'
    }
  })

  $.FroalaEditor.PLUGINS.inlineStyle = function (editor) {
    function apply (val) {
      if (editor.selection.text() !== '') {
        editor.html.insert($.FroalaEditor.START_MARKER + '<span style="' + val + '">' + editor.selection.text() + '</span>' + $.FroalaEditor.END_MARKER);
      }
      else {
        editor.html.insert('<span style="' + val + '">' + $.FroalaEditor.INVISIBLE_SPACE + $.FroalaEditor.MARKERS + '</span>');
      }
    }

    return {
      apply: apply
    }
  }

  // Register the inline style command.
  $.FroalaEditor.RegisterCommand('inlineStyle', {
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.inlineStyles;
      for (var val in options) {
        c += '<li><span style="' + options[val] + '"><a class="fr-command" data-cmd="inlineStyle" data-param1="' + options[val] + '" title="' + this.language.translate(val) + '">' + this.language.translate(val) + '</a></span></li>';
      }
      c += '</ul>';

      return c;
    },
    title: 'Inline Style',
    callback: function (cmd, val) {
      this.inlineStyle.apply(val);
    }
  })

  // Add the font size icon.
  $.FroalaEditor.DefineIcon('inlineStyle', {
    NAME: 'paint-brush'
  });

}));
