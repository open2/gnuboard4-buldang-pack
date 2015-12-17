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

  $.FroalaEditor.PLUGINS.align = function (editor) {
    function apply (val) {
      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true, true);
      editor.selection.restore();

      var blocks = editor.selection.blocks();

      for (var i = 0; i < blocks.length; i++) {
        $(blocks[i]).css('text-align', val).removeClass('fr-temp-div');
        if ($(blocks[i]).attr('class') === '') $(blocks[i]).removeAttr('class');
      }

      editor.selection.save();
      editor.html.unwrap();
      editor.selection.restore();
    }

    function refresh ($btn) {
      var blocks = editor.selection.blocks();
      if (blocks.length) {
        var alignment = editor.helpers.getAlignment($(blocks[0]));

        $btn.find('> *:first').replaceWith(editor.icon.create('align-' + alignment));
      }
    }

    function refreshOnShow($btn, $dropdown) {
      var blocks = editor.selection.blocks();
      if (blocks.length) {
        var alignment = editor.helpers.getAlignment($(blocks[0]));

        $dropdown.find('a.fr-command[data-param1="' + alignment + '"]').addClass('fr-active');
      }
    }

    return {
      apply: apply,
      refresh: refresh,
      refreshOnShow: refreshOnShow
    }
  }

  $.FroalaEditor.DefineIcon('align', { NAME: 'align-left' });
  $.FroalaEditor.DefineIcon('align-left', { NAME: 'align-left' });
  $.FroalaEditor.DefineIcon('align-right', { NAME: 'align-right' });
  $.FroalaEditor.DefineIcon('align-center', { NAME: 'align-center' });
  $.FroalaEditor.DefineIcon('align-justify', { NAME: 'align-justify' });
  $.FroalaEditor.RegisterCommand('align', {
    type: 'dropdown',
    title: 'Align',
    options: {
      left: 'Align Left',
      center: 'Align Center',
      right: 'Align Right',
      justify: 'Align Justify'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FroalaEditor.COMMANDS.align.options;
      for (var val in options) {
        c += '<li><a class="fr-command fr-title" data-cmd="align" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.icon.create('align-' + val) + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.align.apply(val);
    },
    refresh: function ($btn) {
      this.align.refresh($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.align.refreshOnShow($btn, $dropdown);
    }
  })

}));
