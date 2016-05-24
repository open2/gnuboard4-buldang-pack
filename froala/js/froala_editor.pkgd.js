/*!
 * froala_editor v2.3.0 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms/
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
  /*jslint browser: true, debug: true, vars: true, devel: true, expr: true, jQuery: true */
  // EDITABLE CLASS DEFINITION
  // =========================

  'use strict';

  var FE = function (element, options) {
    this.id = ++$.FE.ID;

    this.opts = $.extend(true, {}, $.extend({}, FE.DEFAULTS, typeof options == 'object' && options));
    var opts_string = JSON.stringify(this.opts);

    $.FE.OPTS_MAPPING[opts_string] = $.FE.OPTS_MAPPING[opts_string] || this.id;
    this.sid = $.FE.OPTS_MAPPING[opts_string];
    $.FE.SHARED[this.sid] = $.FE.SHARED[this.sid] || {};
    this.shared = $.FE.SHARED[this.sid];
    this.shared.count = (this.shared.count || 0) + 1;

    this.$oel = $(element);
    this.$oel.data('froala.editor', this);

    this.o_doc = element.ownerDocument;
    this.o_win = 'defaultView' in this.o_doc ? this.o_doc.defaultView :   this.o_doc.parentWindow;
    var c_scroll = $(this.o_win).scrollTop();

    this.$oel.on('froala.doInit', $.proxy(function () {
      this.$oel.off('froala.doInit');
      this.doc = this.$el.get(0).ownerDocument;
      this.win = 'defaultView' in this.doc ? this.doc.defaultView : this.doc.parentWindow;
      this.$doc = $(this.doc);
      this.$win = $(this.win);

      if (!this.opts.pluginsEnabled) this.opts.pluginsEnabled = Object.keys($.FE.PLUGINS);

      if (this.opts.initOnClick) {
        this.load($.FE.MODULES);

        // https://github.com/froala/wysiwyg-editor/issues/1207.
        this.$el.on('touchstart.init', function () {
          $(this).data('touched', true);
        });

        this.$el.on('touchmove.init', function () {
          $(this).removeData('touched');
        })

        this.$el.on('mousedown.init touchend.init dragenter.init focus.init', $.proxy(function (e) {
          if (e.type == 'touchend' && !this.$el.data('touched')) {
            return true;
          }

          if (e.which === 1 || e.which === 0) {
            this.$el.off('mousedown.init touchstart.init touchmove.init touchend.init dragenter.init focus.init');

            this.load($.FE.MODULES);
            this.load($.FE.PLUGINS);

            var target = e.originalEvent && e.originalEvent.originalTarget;
            if (target && target.tagName == 'IMG') $(target).trigger('mousedown');

            if (typeof this.ul == 'undefined') this.destroy();

            if (e.type == 'touchend' && this.image && e.originalEvent && e.originalEvent.target && $(e.originalEvent.target).is('img')) {
              setTimeout($.proxy(function () {
                this.image.edit($(e.originalEvent.target));
              }, this), 100);
            }

            this.events.trigger('initialized');
          }
        }, this));
      }
      else {
        this.load($.FE.MODULES);
        this.load($.FE.PLUGINS);

        $(this.o_win).scrollTop(c_scroll);

        if (typeof this.ul == 'undefined') this.destroy();

        this.events.trigger('initialized');
      }
    }, this));

    this._init();
  };

  FE.DEFAULTS = {
    initOnClick: false,
    pluginsEnabled: null
  };

  FE.MODULES = {};

  FE.PLUGINS = {};

  FE.VERSION = '2.3.0';

  FE.INSTANCES = [];

  FE.OPTS_MAPPING = {};

  FE.SHARED = {};

  FE.ID = 0;

  FE.prototype._init = function () {
    // Get the tag name of the original element.
    var tag_name = this.$oel.prop('tagName');

    // Initialize on anything else.
    var initOnDefault = $.proxy(function () {
      this._original_html = (this._original_html || this.$oel.html());
      this.$box = this.$box || this.$oel;

      // Turn on iframe if fullPage is on.
      if (this.opts.fullPage) this.opts.iframe = true;

      if (!this.opts.iframe) {
        this.$el = $('<div></div>');
        this.$wp = $('<div></div>').append(this.$el);
        this.$box.html(this.$wp);
        this.$oel.trigger('froala.doInit');
      }
      else {
        this.$iframe = $('<iframe src="about:blank" frameBorder="0">');
        this.$wp = $('<div></div>');
        this.$box.html(this.$wp);
        this.$wp.append(this.$iframe);
        this.$iframe.get(0).contentWindow.document.open();
        this.$iframe.get(0).contentWindow.document.write('<!DOCTYPE html>');
        this.$iframe.get(0).contentWindow.document.write('<html><head></head><body></body></html>');
        this.$iframe.get(0).contentWindow.document.close();

        this.$el = this.$iframe.contents().find('body');
        this.$head = this.$iframe.contents().find('head');
        this.$html = this.$iframe.contents().find('html');
        this.iframe_document = this.$iframe.get(0).contentWindow.document;

        this.$oel.trigger('froala.doInit');
      }
    }, this);

    // Initialize on a TEXTAREA.
    var initOnTextarea = $.proxy(function () {
      this.$box = $('<div>');
      this.$oel.before(this.$box).hide();

      this._original_html = this.$oel.val();

      // Before submit textarea do a sync.
      this.$oel.parents('form').on('submit.' + this.id, $.proxy(function () {
        this.events.trigger('form.submit');
      }, this));

      this.$oel.parents('form').on('reset.' + this.id, $.proxy(function () {
        this.events.trigger('form.reset');
      }, this));

      initOnDefault();
    }, this);

    // Initialize on a Link.
    var initOnA = $.proxy(function () {
      this.$el = this.$oel;
      this.$el.attr('contenteditable', true).css('outline', 'none').css('display', 'inline-block');
      this.opts.multiLine = false;
      this.opts.toolbarInline = false;

      this.$oel.trigger('froala.doInit');
    }, this)

    // Initialize on an Image.
    var initOnImg = $.proxy(function () {
      this.$el = this.$oel;
      this.opts.toolbarInline = false;

      this.$oel.trigger('froala.doInit');
    }, this)

    var editInPopup = $.proxy(function () {
      this.$el = this.$oel;
      this.opts.toolbarInline = false;

      this.$oel.on('click.popup', function (e) {
        e.preventDefault();
      })
      this.$oel.trigger('froala.doInit');
    }, this);

    // Check on what element it was initialized.
    if (this.opts.editInPopup) editInPopup();
    else if (tag_name == 'TEXTAREA') initOnTextarea();
    else if (tag_name == 'A') initOnA();
    else if (tag_name == 'IMG') initOnImg();
    else if (tag_name == 'BUTTON' || tag_name == 'INPUT') {
      this.opts.editInPopup = true;
      this.opts.toolbarInline = false;
      editInPopup();
    }
    else {
      initOnDefault();
    }
  }

  FE.prototype.load = function (module_list) {
    // Bind modules to the current instance and tear them up.
    for (var m_name in module_list) {
      if (module_list.hasOwnProperty(m_name)) {
        if (this[m_name]) continue;

        // Do not include plugin.
        if ($.FE.PLUGINS[m_name] && this.opts.pluginsEnabled.indexOf(m_name) < 0) continue;

        this[m_name] = new module_list[m_name](this);
        if (this[m_name]._init) {
          this[m_name]._init();
          if (this.opts.initOnClick && m_name == 'core') {
            return false;
          }
        }
      }
    }
  }

  // Do destroy.
  FE.prototype.destroy = function () {
    this.shared.count--;

    this.events.$off();

    // HTML.
    var html = this.html.get();

    this.events.trigger('destroy', [], true);
    this.events.trigger('shared.destroy', undefined, true);

    // Remove shared.
    if (this.shared.count === 0) {
      for (var k in this.shared) {
        if (this.shared.hasOwnProperty(k)) {
          delete this.shared[k];
        }
      }
    }

    this.$oel.parents('form').off('.' + this.id);
    this.$oel.off('click.popup');
    this.$oel.removeData('froala.editor');

    // Destroy editor basic elements.
    this.core.destroy(html);

    $.FE.INSTANCES.splice($.FE.INSTANCES.indexOf(this), 1);
  }

  // FROALA EDITOR PLUGIN DEFINITION
  // ==========================
  $.fn.froalaEditor = function (option) {
    var arg_list = [];
    for (var i = 0; i < arguments.length; i++) {
      arg_list.push(arguments[i]);
    }

    if (typeof option == 'string') {
      var returns = [];

      this.each(function () {
        var $this = $(this);
        var editor = $this.data('froala.editor');

        if (!editor) {
          return console.warn('Editor should be initialized before calling the ' + option + ' method.');
        }

        var context;
        var nm;

        // Might do a module call.
        if (option.indexOf('.') > 0 && editor[option.split('.')[0]]) {
          if (editor[option.split('.')[0]]) {
            context = editor[option.split('.')[0]];
          }
          nm = option.split('.')[1];
        }
        else {
          context = editor;
          nm = option.split('.')[0]
        }

        if (context[nm]) {
          var returned_value = context[nm].apply(editor, arg_list.slice(1));
          if (returned_value === undefined) {
            returns.push(this);
          } else if (returns.length === 0) {
            returns.push(returned_value);
          }
        }
        else {
          return $.error('Method ' +  option + ' does not exist in Froala Editor.');
        }
      });

      return (returns.length == 1) ? returns[0] : returns;
    }
    else if (typeof option === 'object' || !option) {
      return this.each(function () {
        var editor = $(this).data('froala.editor');

        if (!editor) {
          var that = this;
          new FE(that, option);
        }
      });
    }
  }

  $.fn.froalaEditor.Constructor = FE;
  $.FroalaEditor = FE;
  $.FE = FE;


  $.FE.MODULES.node = function (editor) {
    function getContents(node) {
      if (!node || node.tagName == 'IFRAME') return [];
      return $(node).contents();
    }

    /**
     * Determine if the node is a block tag.
     */
    function isBlock (node) {
      if (!node) return false;
      if (node.nodeType != Node.ELEMENT_NODE) return false;

      return $.FE.BLOCK_TAGS.indexOf(node.tagName.toLowerCase()) >= 0;
    }

    /**
     * Check if a DOM element is empty.
     */
    function isEmpty (el, ignore_markers) {
      if ($(el).find('table').length > 0) return false;

      // Look for void nodes.
      if (el.querySelectorAll($.FE.VOID_ELEMENTS.join(',')).length - el.querySelectorAll('br').length) return false;

      // Look for empty allowed tags.
      if (el.querySelectorAll(editor.opts.htmlAllowedEmptyTags.join(':not(.fr-marker),') + ':not(.fr-marker)').length) return false;

      // Look for block tags.
      if (el.querySelectorAll($.FE.BLOCK_TAGS.join(',')).length > 1) return false;

      // Look for do not wrap tags.
      if (el.querySelectorAll(editor.opts.htmlDoNotWrapTags.join(':not(.fr-marker),') + ':not(.fr-marker)').length) return false;

      // Get element contents.
      var contents = getContents(el);

      // Check if there is a block tag.
      if (contents.length == 1 && isBlock(contents[0])) {
        contents = getContents(contents[0]);
      }

      var has_br = false;
      for (var i = 0; i < contents.length; i++) {
        var node = contents[i];

        if (ignore_markers && $(node).hasClass('fr-marker')) continue;

        if (node.nodeType == Node.TEXT_NODE && node.textContent.length == 0) continue;

        if (node.tagName != 'BR' && (node.textContent || '').replace(/\u200B/gi, '').replace(/\n/g, '').length > 0) return false;

        if (has_br) {
          return false;
        }
        else if (node.tagName == 'BR') {
          has_br = true;
        }
      }

      return true;
    }

    /**
     * Get the block parent.
     */
    function blockParent (node) {
      while (node && node.parentNode !== editor.$el.get(0) && !(node.parentNode && $(node.parentNode).hasClass('fr-inner'))) {
        node = node.parentNode;
        if (isBlock(node)) {
          return node;
        }
      }

      return null;
    }

    /**
     * Get deepest parent till the element.
     */
    function deepestParent (node, until, simple_enter) {
      if (typeof until == 'undefined') until = [];
      if (typeof simple_enter == 'undefined') simple_enter = true;
      until.push(editor.$el.get(0));

      if (until.indexOf(node.parentNode) >= 0 || (node.parentNode && $(node.parentNode).hasClass('fr-inner')) || (node.parentNode && $.FE.SIMPLE_ENTER_TAGS.indexOf(node.parentNode.tagName) >= 0 && simple_enter)) {
        return null;
      }

      // 1. Before until.
      // 2. Parent node doesn't has class fr-inner.
      // 3. Parent node is not a simple enter tag or quote.
      // 4. Parent node is not a block tag
      while (until.indexOf(node.parentNode) < 0 && node.parentNode && !$(node.parentNode).hasClass('fr-inner') && ($.FE.SIMPLE_ENTER_TAGS.indexOf(node.parentNode.tagName) < 0 || !simple_enter) && (!(isBlock(node) && isBlock(node.parentNode)) || !simple_enter)) {
        node = node.parentNode;
      }

      return node;
    }

    function rawAttributes (node) {
      var attrs = {};

      var atts = node.attributes;
      if (atts) {
        for (var i = 0; i < atts.length; i++) {
          var att = atts[i];
          attrs[att.nodeName] = att.value;
        }
      }

      return attrs;
    }

    /**
     * Get attributes for a node as a string.
     */
    function attributes (node) {
      var str = '';
      var atts = rawAttributes(node);

      var keys = Object.keys(atts).sort();
      for (var i = 0; i < keys.length; i++) {
        var nodeName = keys[i];
        var value = atts[nodeName];

        // Make sure we don't break any HTML.
        if (value.indexOf('"') < 0) {
          str += ' ' + nodeName + '="' + value + '"';
        }
        else {
          str += ' ' + nodeName + '=\'' + value + '\'';
        }
      }

      return str;
    }

    function clearAttributes (node) {
      var atts = node.attributes;
      for (var i = 0; i < atts.length; i++) {
        var att = atts[i];
        node.removeAttribute(att.nodeName);
      }
    }

    /**
     * Open string for a node.
     */
    function openTagString (node) {
      return '<' + node.tagName.toLowerCase() + attributes(node) + '>';
    }

    /**
     * Close string for a node.
     */
    function closeTagString (node) {
      return '</' + node.tagName.toLowerCase() + '>';
    }

    /**
     * Determine if the node has any left sibling.
     */
    function isFirstSibling (node, ignore_markers) {
      if (typeof ignore_markers == 'undefined') ignore_markers = true;
      var sibling = node.previousSibling;

      while (sibling && ignore_markers && $(sibling).hasClass('fr-marker')) {
        sibling = sibling.previousSibling;
      }

      if (!sibling) return true;
      if (sibling.nodeType == Node.TEXT_NODE && sibling.textContent === '') return isFirstSibling(sibling);
      return false;
    }

    function isVoid(node) {
      return node && node.nodeType == Node.ELEMENT_NODE && $.FE.VOID_ELEMENTS.indexOf((node.tagName || '').toLowerCase()) >= 0
    }

    /**
     * Check if the node is a list.
     */
    function isList (node) {
      if (!node) return false;
      return ['UL', 'OL'].indexOf(node.tagName) >= 0;
    }

    /**
     * Check if the node is the editable element.
     */
    function isElement (node) {
      return node === editor.$el.get(0);
    }

    /**
     * Check if the node is the editable element.
     */
    function isDeletable (node) {
      return node && node.className && (node.className || '').indexOf('fr-deletable') >= 0;
    }

    /**
     * Check if the node has focus.
     */
    function hasFocus (node) {
      return node === editor.doc.activeElement && (!editor.doc.hasFocus || editor.doc.hasFocus()) && !!(isElement(node) || node.type || node.href || ~node.tabIndex);;
    }

    function isEditable (node) {
      return (!node.getAttribute || node.getAttribute('contenteditable') != 'false')
                && ['STYLE', 'SCRIPT'].indexOf(node.tagName) < 0;
    }

    return {
      isBlock: isBlock,
      isEmpty: isEmpty,
      blockParent: blockParent,
      deepestParent: deepestParent,
      rawAttributes: rawAttributes,
      attributes: attributes,
      clearAttributes: clearAttributes,
      openTagString: openTagString,
      closeTagString: closeTagString,
      isFirstSibling: isFirstSibling,
      isList: isList,
      isElement: isElement,
      contents: getContents,
      isVoid: isVoid,
      hasFocus: hasFocus,
      isEditable: isEditable,
      isDeletable: isDeletable
    }
  };


  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    // Tags that describe head from HEAD http://www.w3schools.com/html/html_head.asp.
    htmlAllowedTags: ['a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 'b', 'base', 'bdi', 'bdo', 'blockquote', 'br', 'button', 'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'hgroup', 'hr', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'menu', 'menuitem', 'meter', 'nav', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'pre', 'progress', 'queue', 'rp', 'rt', 'ruby', 's', 'samp', 'script', 'style', 'section', 'select', 'small', 'source', 'span', 'strike', 'strong', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'time', 'tr', 'track', 'u', 'ul', 'var', 'video', 'wbr'],
    htmlRemoveTags: ['script', 'style'],
    htmlAllowedAttrs: ['accept', 'accept-charset', 'accesskey', 'action', 'align', 'allowfullscreen', 'allowtransparency', 'alt', 'async', 'autocomplete', 'autofocus', 'autoplay', 'autosave', 'background', 'bgcolor', 'border', 'charset', 'cellpadding', 'cellspacing', 'checked', 'cite', 'class', 'color', 'cols', 'colspan', 'content', 'contenteditable', 'contextmenu', 'controls', 'coords', 'data', 'data-.*', 'datetime', 'default', 'defer', 'dir', 'dirname', 'disabled', 'download', 'draggable', 'dropzone', 'enctype', 'for', 'form', 'formaction', 'frameborder', 'headers', 'height', 'hidden', 'high', 'href', 'hreflang', 'http-equiv', 'icon', 'id', 'ismap', 'itemprop', 'keytype', 'kind', 'label', 'lang', 'language', 'list', 'loop', 'low', 'max', 'maxlength', 'media', 'method', 'min', 'mozallowfullscreen', 'multiple', 'name', 'novalidate', 'open', 'optimum', 'pattern', 'ping', 'placeholder', 'poster', 'preload', 'pubdate', 'radiogroup', 'readonly', 'rel', 'required', 'reversed', 'rows', 'rowspan', 'sandbox', 'scope', 'scoped', 'scrolling', 'seamless', 'selected', 'shape', 'size', 'sizes', 'span', 'src', 'srcdoc', 'srclang', 'srcset', 'start', 'step', 'summary', 'spellcheck', 'style', 'tabindex', 'target', 'title', 'type', 'translate', 'usemap', 'value', 'valign', 'webkitallowfullscreen', 'width', 'wrap'],
    htmlAllowComments: true,
    fullPage: false // Will also turn iframe on.
  });

  $.FE.HTML5Map = {
    'B': 'STRONG',
    'I': 'EM',
    'STRIKE': 'S'
  },

  $.FE.MODULES.clean = function (editor) {
    var $iframe, body;
    var allowedTagsRE, removeTagsRE, allowedAttrsRE;

    function _removeInvisible (node) {
      if (node.className && node.className.indexOf('fr-marker') >= 0) return false;

      // Get node contents.
      var contents = editor.node.contents(node);
      var markers = [];
      var i;

      // Loop through contents.
      for (i = 0; i < contents.length; i++) {
        // If node is not void.
        if (contents[i].nodeType == Node.ELEMENT_NODE && !editor.node.isVoid(contents[i])) {
          // There are invisible spaces.
          if (contents[i].textContent.replace(/\u200b/g, '').length != contents[i].textContent.length) {
            // Do remove invisible spaces.
            _removeInvisible(contents[i]);
          }
        }

        // If node is text node, replace invisible spaces.
        else if (contents[i].nodeType == Node.TEXT_NODE) {
          contents[i].textContent = contents[i].textContent.replace(/\u200b/g, '');
        }
      }

      // Reasess contents after cleaning invisible spaces.
      if (node.nodeType == Node.ELEMENT_NODE && !editor.node.isVoid(node)) {
        node.normalize();
        contents = editor.node.contents(node);
        markers = node.querySelectorAll('.fr-marker');

        // All we have left are markers.
        if (contents.length - markers.length == 0) {
          // Make sure contents are all markers.
          for (i = 0; i < contents.length; i++) {
            if ((contents[i].className || '').indexOf('fr-marker') < 0) {
              return false;
            }
          }

          for (i = 0; i < markers.length; i++) {
            node.parentNode.insertBefore(markers[i].cloneNode(true), node);
          }
          node.parentNode.removeChild(node);
          return false;
        }
      }
    }

    function _toHTML (el) {
      if (el.nodeType == Node.COMMENT_NODE) return '<!--' + el.nodeValue + '-->';
      if (el.nodeType == Node.TEXT_NODE) return el.textContent.replace(/\</g, '&lt;').replace(/\>/g, '&gt;').replace(/\u00A0/g, '&nbsp;');
      if (el.nodeType != Node.ELEMENT_NODE) return el.outerHTML;
      if (el.nodeType == Node.ELEMENT_NODE && ['STYLE', 'SCRIPT'].indexOf(el.tagName) >= 0) return el.outerHTML;
      if (el.tagName == 'IFRAME') return el.outerHTML;

      var contents = el.childNodes;

      if (contents.length === 0) return el.outerHTML;

      var str = '';
      for (var i = 0; i < contents.length; i++) {
        str += _toHTML(contents[i]);
      }

      return editor.node.openTagString(el) + str + editor.node.closeTagString(el);
    }

    var scripts = [];
    function _encode (dirty_html) {
      // Replace script tag with comments.
      scripts = [];
      dirty_html = dirty_html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, function (str) {
        scripts.push(str);
        return '[FROALA.EDITOR.SCRIPT ' + (scripts.length - 1) + ']';
      });

      dirty_html = dirty_html.replace(/<img((?:[\w\W]*?)) src="/g, '<img$1 data-fr-src="');

      return dirty_html;
    }

    function _decode (dirty_html) {
      // Replace script comments with the original script.
      dirty_html = dirty_html.replace(/\[FROALA\.EDITOR\.SCRIPT ([\d]*)\]/gi, function (str, a1) {
        if (editor.opts.htmlRemoveTags.indexOf('script') >= 0) {
          return '';
        }
        else {
          return scripts[parseInt(a1, 10)];
        }
      });

      dirty_html = dirty_html.replace(/<img((?:[\w\W]*?)) data-fr-src="/g, '<img$1 src="');

      return dirty_html;
    }

    function _cleanAttrs (attrs) {
      var nm;

      for (nm in attrs) {
        if (attrs.hasOwnProperty(nm)) {
          if (!nm.match(allowedAttrsRE)) {
            delete attrs[nm];
          }
        }
      }

      var str = '';
      var keys = Object.keys(attrs).sort();
      for (var i = 0; i < keys.length; i++) {
        nm = keys[i];

        // Make sure we don't break any HTML.
        if (attrs[nm].indexOf('"') < 0) {
          str += ' ' + nm + '="' + attrs[nm] + '"';
        }
        else {
          str += ' ' + nm + '=\'' + attrs[nm] + '\'';
        }
      }

      return str;
    }

    function _rebuild (body_html, head_html, original_html) {
      if (editor.opts.fullPage) {
        // Get DOCTYPE.
        var doctype = editor.html.extractDoctype(original_html);

        // Get HTML attributes.
        var html_attrs = _cleanAttrs(editor.html.extractNodeAttrs(original_html, 'html'));

        // Get HEAD data.
        head_html = head_html == null ? editor.html.extractNode(original_html, 'head') || '<title></title>' : head_html;
        var head_attrs = _cleanAttrs(editor.html.extractNodeAttrs(original_html, 'head'));

        // Get BODY attributes.
        var body_attrs = _cleanAttrs(editor.html.extractNodeAttrs(original_html, 'body'));

        return doctype + '<html' + html_attrs + '><head' + head_attrs + '>' + head_html + '</head><body' + body_attrs + '>' + body_html + '</body></html>';
      }

      return body_html;
    }

    function _process (html, func) {
      var $el = $('<div>' + html + '</div>');

      var new_html = '';
      if ($el) {
        var els = editor.node.contents($el.get(0));
        for (var i = 0; i < els.length; i++) {
          func(els[i]);
        }

        els = editor.node.contents($el.get(0));
        for (var i = 0; i < els.length; i++) {
          new_html += _toHTML(els[i]);
        }
      }

      return new_html;
    }

    function exec (html, func, parse_head) {
      html = _encode(html);

      var b_html = html;
      var h_html = null;
      if (editor.opts.fullPage) {
        // Get BODY data.
        var b_html = (editor.html.extractNode(html, 'body') || (html.indexOf('<body') >= 0 ? '' : html));

        if (parse_head) {
          h_html = (editor.html.extractNode(html, 'head') || '');
        }
      }

      b_html = _process(b_html, func);
      if (h_html) h_html = _process(h_html, func);

      var new_html = _rebuild(b_html, h_html, html);

      return _decode(new_html);
    }

    function invisibleSpaces (dirty_html) {
      if (dirty_html.replace(/\u200b/g, '').length == dirty_html.length) return dirty_html;

      return editor.clean.exec(dirty_html, _removeInvisible);
    }

    function toHTML5 () {
      var els = editor.$el.get(0).querySelectorAll(Object.keys($.FE.HTML5Map).join(','));
      if (els.length) {
        editor.selection.save();
        for (var i = 0; i < els.length; i++) {
          if (editor.node.attributes(els[i]) === '') {
             $(els[i]).replaceWith('<' + $.FE.HTML5Map[els[i].tagName] + '>' + els[i].innerHTML + '</' + $.FE.HTML5Map[els[i].tagName] + '>');
          }
        }
        editor.selection.restore();
      }
    }

    function _node (node) {
      if (node.tagName == 'PRE') _cleanPre(node);

      if (node.nodeType == Node.ELEMENT_NODE) {
        if (node.getAttribute('data-fr-src')) node.setAttribute('data-fr-src', editor.helpers.sanitizeURL(node.getAttribute('data-fr-src')));
        if (node.getAttribute('href')) node.setAttribute('href', editor.helpers.sanitizeURL(node.getAttribute('href')));

        if (['TABLE', 'TBODY', 'TFOOT', 'TR'].indexOf(node.tagName) >= 0) {
          node.innerHTML = node.innerHTML.trim();
        }
      }

      // Remove local images if option they are not allowed.
      if (!editor.opts.pasteAllowLocalImages && node.nodeType == Node.ELEMENT_NODE && node.tagName == 'IMG' && node.getAttribute('data-fr-src') && node.getAttribute('data-fr-src').indexOf('file://') == 0) {
        node.parentNode.removeChild(node);
        return false;
      }

      if (node.nodeType == Node.ELEMENT_NODE && $.FE.HTML5Map[node.tagName] && editor.node.attributes(node) === '') {
        var tg = $.FE.HTML5Map[node.tagName];
        var new_node = '<' + tg + '>' + node.innerHTML + '</' + tg + '>';
        node.insertAdjacentHTML('beforebegin', new_node);
        node = node.previousSibling;
        node.parentNode.removeChild(node.nextSibling);
      }

      if (!editor.opts.htmlAllowComments && node.nodeType == Node.COMMENT_NODE) {
        // Do not remove FROALA.EDITOR comments.
        if (node.data.indexOf('[FROALA.EDITOR') !== 0) {
          node.parentNode.removeChild(node);
        }
      }

      // Remove completely tags in denied tags.
      else if (node.tagName && node.tagName.match(removeTagsRE)) {
        node.parentNode.removeChild(node);
      }

      // Unwrap tags not in allowed tags.
      else if (node.tagName && !node.tagName.match(allowedTagsRE)) {
        node.outerHTML = node.innerHTML;
      }

      // Check denied attributes.
      else {
        var attrs = node.attributes;
        if (attrs) {
          for (var i = attrs.length - 1; i >= 0; i--) {
            var attr = attrs[i];
            if (!attr.nodeName.match(allowedAttrsRE)) {
              node.removeAttribute(attr.nodeName);
            }
          }
        }
      }
    }

    function _run (node) {
      var contents = editor.node.contents(node);
      for (var i = 0; i < contents.length; i++) {
        if (contents[i].nodeType != Node.TEXT_NODE) {
          _run(contents[i]);
        }
      }

      _node(node);
    }

    /**
     * Clean pre.
     */
    function _cleanPre (pre) {
      var content = pre.innerHTML;
      if (content.indexOf('\n') >= 0) {
        pre.innerHTML = content.replace(/\n/g, '<br>');
      }
    }

    /**
     * Clean the html input.
     */
    var scripts = [];
    function html (dirty_html, denied_tags, denied_attrs, full_page) {
      if (typeof denied_tags == 'undefined') denied_tags = [];
      if (typeof denied_attrs == 'undefined') denied_attrs = [];
      if (typeof full_page == 'undefined') full_page = false;

      // Strip tabs.
      dirty_html = dirty_html.replace(/\u0009/g, '');

      // Build the allowed tags array.
      var allowed_tags = $.merge([], editor.opts.htmlAllowedTags);
      var i;
      for (i = 0; i < denied_tags.length; i++) {
        if (allowed_tags.indexOf(denied_tags[i]) >= 0) {
          allowed_tags.splice(allowed_tags.indexOf(denied_tags[i]), 1);
        }
      }

      // Build the allowed attrs array.
      var allowed_attrs = $.merge([], editor.opts.htmlAllowedAttrs);
      for (i = 0; i < denied_attrs.length; i++) {
        if (allowed_attrs.indexOf(denied_attrs[i]) >= 0) {
          allowed_attrs.splice(allowed_attrs.indexOf(denied_attrs[i]), 1);
        }
      }

      // We should allow data-fr.
      allowed_attrs.push('data-fr-.*');
      allowed_attrs.push('fr-.*');

      // Generate cleaning RegEx.
      allowedTagsRE = new RegExp('^' + allowed_tags.join('$|^') + '$', 'gi');
      allowedAttrsRE = new RegExp('^' + allowed_attrs.join('$|^') + '$', 'gi');
      removeTagsRE = new RegExp('^' + editor.opts.htmlRemoveTags.join('$|^') + '$', 'gi');

      dirty_html = exec(dirty_html, _run, true);

      return dirty_html;
    }

    /**
     * Clean quotes.
     */
    function quotes () {
      // Join quotes.
      var sibling_quotes = editor.$el.get(0).querySelectorAll('blockquote + blockquote');
      for (var k = 0; k < sibling_quotes.length; k++) {
        var quote = sibling_quotes[k];
        if (editor.node.attributes(quote) == editor.node.attributes(quote.previousSibling)) {
          $(quote).prev().append($(quote).html());
          $(quote).remove();
        }
      }
    }

    function _tablesWrapTHEAD () {
      var trs = editor.$el.get(0).querySelectorAll('tr');

      // Make sure the TH lives inside thead.
      for (var i = 0; i < trs.length; i++) {
        // Search for th inside tr.
        var children = trs[i].children;
        var ok = true;
        for (var j = 0; j < children.length; j++) {
          if (children[j].tagName != 'TH') {
            ok = false;
            break;
          }
        }

        // If there is something else than TH.
        if (ok == false || children.length == 0) continue;

        var tr = trs[i];

        while (tr && tr.tagName != 'TABLE' && tr.tagName != 'THEAD') {
          tr = tr.parentNode;
        }

        var thead = tr;
        if (thead.tagName != 'THEAD') {
          thead = editor.doc.createElement('THEAD');
          tr.insertBefore(thead, tr.firstChild);
        }

        thead.appendChild(trs[i]);
      }
    }

    function _tablesBRBefore () {
      // Make sure we have a br before tables.
      var tbls = editor.$el.get(0).querySelectorAll('table');
      for (var i = 0; i < tbls.length; i++) {
        var prev_node = tbls[i].previousSibling;

        // Get previous sibling.
        while (prev_node && prev_node.nodeType == Node.TEXT_NODE && prev_node.textContent.length == 0) {
          prev_node = prev_node.previousSibling;
        }

        if (prev_node && !editor.node.isBlock(prev_node) && prev_node.tagName != 'BR' && (prev_node.nodeType == Node.TEXT_NODE || prev_node.nodeType == Node.ELEMENT_NODE) && !$(prev_node).is(editor.opts.htmlDoNotWrapTags.join(','))) {
          tbls[i].parentNode.insertBefore(editor.doc.createElement('br'), tbls[i]);
        }
      }
    }

    function _tablesRemovePFromCell () {
      // Remove P from TH and TH.
      var default_tag = editor.html.defaultTag();
      if (default_tag) {
        var nodes = editor.$el.get(0).querySelectorAll('td > ' + default_tag + ', th > ' + default_tag);
        for (var i = 0; i < nodes.length; i++) {
          if (editor.node.attributes(nodes[i]) === '') {
            $(nodes[i]).replaceWith(nodes[i].innerHTML + '<br>');
          }
        }
      }
    }

    /**
     * Clean tables.
     */
    function tables () {
      _tablesWrapTHEAD();

      _tablesBRBefore();

      _tablesRemovePFromCell();
    }

    function _listsWrapMissplacedLI () {
      // Find missplaced list items.
      var lis = [];
      var filterListItem = function (li) {
        return !editor.node.isList(li.parentNode);
      };

      do {
        if (lis.length) {
          var li = lis[0];
          var ul = editor.doc.createElement('ul');
          li.parentNode.insertBefore(ul, li);
          do {
            var tmp = li;
            li = li.nextSibling;
            ul.appendChild(tmp);
          } while (li && li.tagName == 'LI');
        }

        lis = [];
        var li_sel = editor.$el.get(0).querySelectorAll('li');
        for (var i = 0; i < li_sel.length; i++) {
          if (filterListItem(li_sel[i])) lis.push(li_sel[i]);
        }
      } while (lis.length > 0);
    }

    function _listsJoinSiblings () {
      // Join lists.
      var sibling_lists = editor.$el.get(0).querySelectorAll('ol + ol, ul + ul');
      for (var k = 0; k < sibling_lists.length; k++) {
        var list = sibling_lists[k];
        if (editor.node.attributes(list) == editor.node.attributes(list.previousSibling)) {
          var childs = editor.node.contents(list);
          for (var i = 0; i < childs.length; i++) {
            list.previousSibling.appendChild(childs[i]);
          }
          list.parentNode.removeChild(list);
        }
      }
    }

    function _listsRemoveEmpty () {
      // Remove empty lists.
      var do_remove;
      var removeEmptyList = function (lst) {
        if (lst.querySelectorAll('LI').length === 0) {
          do_remove = true;
          lst.parentNode.removeChild(lst);
        }
      };

      do {
        do_remove = false;

        // Remove empty li.
        var empty_lis = editor.$el.get(0).querySelectorAll('li:empty');
        for (var i = 0; i < empty_lis.length; i++) {
          empty_lis[i].parentNode.removeChild(empty_lis[i]);
        }

        // Remove empty ul and ol.
        var remaining_lists = editor.$el.get(0).querySelectorAll('ul, ol');
        for (var i = 0; i < remaining_lists.length; i++) {
          removeEmptyList(remaining_lists[i]);
        }
      } while (do_remove === true);
    }

    function _listsWrapLists () {
      // Do not allow list directly inside another list.
      var direct_lists = editor.$el.get(0).querySelectorAll('ul > ul, ol > ol, ul > ol, ol > ul');
      for (var i = 0; i < direct_lists.length; i++) {
        var list = direct_lists[i];
        var prev_li = list.previousSibling;
        if (prev_li) {
          if (prev_li.tagName == 'LI') {
            prev_li.appendChild(list);
          }
          else {
            $(list).wrap('<li></li>');
          }
        }
      }
    }

    function _listsNoTagAfterNested () {
      // Check if nested lists don't have HTML after them.
      var nested_lists = editor.$el.get(0).querySelectorAll('li > ul, li > ol');
      for (var i = 0; i < nested_lists.length; i++) {
        var lst = nested_lists[i];

        if (lst.nextSibling) {
          var node = lst.nextSibling;
          var $new_li = $('<li>');
          $(lst.parentNode).after($new_li);
          do {
            var tmp = node;
            node = node.nextSibling;
            $new_li.append(tmp);
          } while (node);
        }
      }
    }

    function _listsTypeInNested () {
      // Make sure we can type in nested list.
      var nested_lists = editor.$el.get(0).querySelectorAll('li > ul, li > ol');
      for (var i = 0; i < nested_lists.length; i++) {
        var lst = nested_lists[i];

        // List is the first in the LI.
        if (editor.node.isFirstSibling(lst)) {
          $(lst).before('<br/>');
        }

        // Make sure we don't leave BR before list.
        else if (lst.previousSibling && lst.previousSibling.tagName == 'BR') {
          var prev_node = lst.previousSibling.previousSibling;

          // Skip markers.
          while (prev_node && $(prev_node).hasClass('fr-marker')) {
            prev_node = prev_node.previousSibling;
          }

          // Remove BR only if there is something else than BR.
          if (prev_node && prev_node.tagName != 'BR') {
            $(lst.previousSibling).remove();
          }
        }
      }
    }

    function _listsRemoveEmptyLI () {
      // Remove empty li.
      var empty_lis = editor.$el.get(0).querySelectorAll('li:empty');
      for (var i = 0; i < empty_lis.length; i++) {
        $(empty_lis[i]).remove();
      }
    }

    /**
     * Clean lists.
     */
    function lists () {
      _listsWrapMissplacedLI();

      _listsJoinSiblings();

      _listsRemoveEmpty();

      _listsWrapLists();

      _listsNoTagAfterNested();

      _listsTypeInNested();

      _listsRemoveEmptyLI();
    }

    /**
     * Initialize
     */
    function _init () {
      // If fullPage is on allow head and title.
      if (editor.opts.fullPage) {
        $.merge(editor.opts.htmlAllowedTags, ['head', 'title', 'style', 'link', 'base', 'body', 'html']);
      }
    }

    return {
      _init: _init,
      html: html,
      toHTML5: toHTML5,
      tables: tables,
      lists: lists,
      quotes: quotes,
      invisibleSpaces: invisibleSpaces,
      exec: exec
    }
  };


  $.FE.XS = 0;
  $.FE.SM = 1;
  $.FE.MD = 2;
  $.FE.LG = 3;

  $.FE.MODULES.helpers = function (editor) {
    /**
     * Get the IE version.
     */
    function _ieVersion () {
      /*global navigator */
      var rv = -1;
      var ua;
      var re;

      if (navigator.appName == 'Microsoft Internet Explorer') {
        ua = navigator.userAgent;
        re = new RegExp('MSIE ([0-9]{1,}[\\.0-9]{0,})');
        if (re.exec(ua) !== null)
          rv = parseFloat(RegExp.$1);
      } else if (navigator.appName == 'Netscape') {
        ua = navigator.userAgent;
        re = new RegExp('Trident/.*rv:([0-9]{1,}[\\.0-9]{0,})');
        if (re.exec(ua) !== null)
          rv = parseFloat(RegExp.$1);
      }
      return rv;
    }

    /**
     * Determine the browser.
     */
    function _browser () {
      var browser = {};

      var ie_version = _ieVersion();
      if (ie_version > 0) {
        browser.msie = true;
      } else {
        var ua = navigator.userAgent.toLowerCase();

        var match =
          /(edge)[ \/]([\w.]+)/.exec(ua) ||
          /(chrome)[ \/]([\w.]+)/.exec(ua) ||
          /(webkit)[ \/]([\w.]+)/.exec(ua) ||
          /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
          /(msie) ([\w.]+)/.exec(ua) ||
          ua.indexOf('compatible') < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
          [];

        var matched = {
          browser: match[1] || '',
          version: match[2] || '0'
        };

        if (match[1]) browser[matched.browser] = true;

        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
          browser.webkit = true;
        } else if (browser.webkit) {
          browser.safari = true;
        }
      }

      if (browser.msie) browser.version = ie_version;

      return browser;
    }

    function isIOS () {
      return /(iPad|iPhone|iPod)/g.test(navigator.userAgent) && !isWindowsPhone();
    }

    function isAndroid () {
      return /(Android)/g.test(navigator.userAgent) && !isWindowsPhone();
    }

    function isBlackberry () {
      return /(Blackberry)/g.test(navigator.userAgent);
    }

    function isWindowsPhone () {
      return /(Windows Phone)/gi.test(navigator.userAgent);
    }

    function isMobile () {
      return isAndroid() || isIOS() || isBlackberry();
    }

    function requestAnimationFrame () {
      return window.requestAnimationFrame ||
              window.webkitRequestAnimationFrame ||
              window.mozRequestAnimationFrame    ||
              function (callback) {
                window.setTimeout(callback, 1000 / 60);
              };
    }

    function getPX (val) {
      return parseInt(val, 10) || 0;
    }

    function screenSize () {
      var $test = $('<div class="fr-visibility-helper"></div>').appendTo('body');
      var size = getPX($test.css('margin-left'));
      $test.remove();
      return size;
    }

    function isTouch () {
      return ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch;
    }

    function isURL (url) {
      if (!/^(https?:|ftps?:|)\/\//i.test(url)) return false;

      url = String(url)
              .replace(/</g, '%3C')
              .replace(/>/g, '%3E')
              .replace(/"/g, '%22')
              .replace(/ /g, '%20');


      var test_reg = /(http|ftp|https):\/\/[a-z\u00a1-\uffff0-9]+(\.[a-z\u00a1-\uffff0-9]*)*([a-z\u00a1-\uffff0-9.,@?^=%&amp;:\/~+#-]*[a-z\u00a1-\uffff0-9@?^=%&amp;\/~+#-])?/gi;

      return test_reg.test(url);
    }

    // Sanitize URL.
    function sanitizeURL (url) {
      if (/^(https?:|ftps?:|)\/\//i.test(url)) {
        if (!isURL(url) && !isURL('http:' + url)) {
          return '';
        }
      }
      else {
        url = encodeURIComponent(url)
                  .replace(/%23/g, '#')
                  .replace(/%2F/g, '/')
                  .replace(/%25/g, '%')
                  .replace(/mailto%3A/gi, 'mailto:')
                  .replace(/file%3A/gi, 'file:')
                  .replace(/sms%3A/gi, 'sms:')
                  .replace(/tel%3A/gi, 'tel:')
                  .replace(/notes%3A/gi, 'notes:')
                  .replace(/data%3Aimage/gi, 'data:image')
                  .replace(/webkit-fake-url%3A/gi, 'webkit-fake-url:')
                  .replace(/%3F/g, '?')
                  .replace(/%3D/g, '=')
                  .replace(/%26/g, '&')
                  .replace(/&amp;/g, '&')
                  .replace(/%2C/g, ',')
                  .replace(/%3B/g, ';')
                  .replace(/%2B/g, '+')
                  .replace(/%40/g, '@')
                  .replace(/%5B/g, '[')
                  .replace(/%5D/g, ']')
                  .replace(/%7B/g, '{')
                  .replace(/%7D/g, '}');
      }

      return url;
    }

    function isArray (obj) {
      return obj && !(obj.propertyIsEnumerable('length')) &&
              typeof obj === 'object' && typeof obj.length === 'number';
    }

    /*
     * Transform RGB color to hex value.
     */
    function RGBToHex (rgb) {
      function hex(x) {
        return ('0' + parseInt(x, 10).toString(16)).slice(-2);
      }

      try {
        if (!rgb || rgb === 'transparent') return '';

        if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

        return ('#' + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3])).toUpperCase();
      }
      catch (ex) {
        return null;
      }
    }

    function HEXtoRGB (hex) {
      // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
      var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
      hex = hex.replace(shorthandRegex, function (m, r, g, b) {
        return r + r + g + g + b + b;
      });

      var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
      return result ? 'rgb(' + parseInt(result[1], 16) + ', ' + parseInt(result[2], 16) + ', ' + parseInt(result[3], 16) + ')' : '';
    }

    /*
     * Get block alignment.
     */
    var default_alignment;
    function getAlignment ($block) {
      var alignment = ($block.css('text-align') || '').replace(/-(.*)-/g, '');

      // Detect rtl.
      if (['left', 'right', 'justify', 'center'].indexOf(alignment) < 0) {
        if (!default_alignment) {
          var $div = $('<div dir="auto" style="text-align: initial; position: fixed; left: -3000px;"><span id="s1">.</span><span id="s2">.</span></div>');
          $('body').append($div);

          var l1 = $div.find('#s1').get(0).getBoundingClientRect().left;
          var l2 = $div.find('#s2').get(0).getBoundingClientRect().left;

          $div.remove();

          default_alignment = l1 < l2 ? 'left' : 'right';
        }

        alignment = default_alignment;
      }

      return alignment;
    }

    /**
     * Check if is mac.
     */
    var is_mac = null;
    function isMac () {
      if (is_mac == null) {
        is_mac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;;
      }

      return is_mac;
    }

    /**
     * Tear up.
     */
    function _init () {
      editor.browser = _browser();
    }

    return {
      _init: _init,
      isIOS: isIOS,
      isMac: isMac,
      isAndroid: isAndroid,
      isBlackberry: isBlackberry,
      isWindowsPhone: isWindowsPhone,
      isMobile: isMobile,
      requestAnimationFrame: requestAnimationFrame,
      getPX: getPX,
      screenSize: screenSize,
      isTouch: isTouch,
      sanitizeURL: sanitizeURL,
      isArray: isArray,
      RGBToHex: RGBToHex,
      HEXtoRGB: HEXtoRGB,
      isURL: isURL,
      getAlignment: getAlignment
    }
  }


  $.FE.MODULES.events = function (editor) {
    var _events = {};
    var _do_blur;

    function _assignEvent($el, evs, handler) {
      $on($el, evs, handler);
    }

    function _forPaste () {
      _assignEvent(editor.$el, 'cut copy paste beforepaste', function (e) {
        trigger(e.type, [e]);
      });
    }

    function _forElement() {
      _assignEvent(editor.$el, 'click mouseup mousedown touchstart touchend dragenter dragover dragleave dragend drop dragstart', function (e) {
        trigger(e.type, [e]);
      });

      on('mousedown', function () {
        for (var i = 0; i < $.FE.INSTANCES.length; i++) {
          if ($.FE.INSTANCES[i] != editor && $.FE.INSTANCES[i].popups && $.FE.INSTANCES[i].popups.areVisible()) {
            $.FE.INSTANCES[i].$el.find('.fr-marker').remove();
          }
        }
      })
    }

    function _forKeys () {
      // Map events.
      _assignEvent(editor.$el, 'keydown keypress keyup input', function (e) {
        trigger(e.type, [e]);
      });
    }

    function _forWindow () {
      _assignEvent(editor.$win, editor._mousedown, function (e) {
        trigger('window.mousedown', [e]);
        enableBlur();
      });

      _assignEvent(editor.$win, editor._mouseup, function (e) {
        trigger('window.mouseup', [e]);
      });

      _assignEvent(editor.$win, 'keydown keyup touchmove touchend', function (e) {
        trigger('window.' + e.type, [e]);
      });
    }

    function _forDocument() {
      _assignEvent(editor.$doc, 'dragend drop', function (e) {
        trigger('document.' + e.type, [e]);
      })
    }

    function focus (do_focus) {
      if (typeof do_focus == 'undefined') do_focus = true;
      if (!editor.$wp) return false;

      // Focus the editor window.
      if (editor.helpers.isIOS()) {
        editor.$win.get(0).focus();
      }

      // If there is no focus, then force focus.
      if (!editor.core.hasFocus() && do_focus) {
        var st = editor.$win.scrollTop();
        editor.$el.focus();
        if (st != editor.$win.scrollTop()) {
          editor.$win.scrollTop(st);
        }
        return false;
      }

      // Don't go further if we haven't focused or there are markers.
      if (!editor.core.hasFocus() || editor.$el.find('.fr-marker').length > 0) {
        return false;
      }

      var info = editor.selection.info(editor.$el.get(0));

      if (info.atStart && editor.selection.isCollapsed()) {
        if (editor.html.defaultTag() != null) {
          var marker = editor.markers.insert();
          if (marker && !editor.node.blockParent(marker)) {
            $(marker).remove();

            var element = editor.$el.find(editor.html.blockTagsQuery()).get(0);
            if (element) {
              $(element).prepend($.FE.MARKERS);
              editor.selection.restore();
            }
          }
          else if (marker) {
            $(marker).remove();
          }
        }
      }
    }

    var focused = false;

    function _forFocus () {
      _assignEvent(editor.$el, 'focus', function (e) {
        if (blurActive()) {
          focus(false);
          if (focused === false) {
            trigger(e.type, [e]);
          }
        }
      });

      _assignEvent(editor.$el, 'blur', function (e) {
        if (blurActive() /* && document.activeElement != this */) {
          if (focused === true) {
            trigger(e.type, [e]);
          }
        }
      });

      on('focus', function () {
        focused = true;
      });

      on('blur', function () {
        focused = false;
      });
    }

    function _forMouse () {
      if (editor.helpers.isMobile()) {
        editor._mousedown = 'touchstart';
        editor._mouseup = 'touchend';
        editor._move = 'touchmove';
        editor._mousemove = 'touchmove';
      }
      else {
        editor._mousedown = 'mousedown';
        editor._mouseup = 'mouseup';
        editor._move = '';
        editor._mousemove = 'mousemove';
      }
    }

    function _buttonMouseDown (e) {
      var $btn = $(e.currentTarget);
      if (editor.edit.isDisabled() || $btn.hasClass('fr-disabled')) {
        e.preventDefault();
        return false;
      }

      // Not click button.
      if (e.type === 'mousedown' && e.which !== 1) return true;

      // Scroll in list.
      if (!editor.helpers.isMobile()) {
        e.preventDefault();
      }

      if ((editor.helpers.isAndroid() || editor.helpers.isWindowsPhone()) && $btn.parents('.fr-dropdown-menu').length === 0) {
        e.preventDefault();
        e.stopPropagation();
      }

      // Simulate click.
      $btn.addClass('fr-selected');

      editor.events.trigger('commands.mousedown', [$btn]);
    }

    function _buttonMouseUp (e, handler) {
      var $btn = $(e.currentTarget);

      if (editor.edit.isDisabled() || $btn.hasClass('fr-disabled')) {
        e.preventDefault();
        return false;
      }

      if (e.type === 'mouseup' && e.which !== 1) return true;
      if (!$btn.hasClass('fr-selected')) return true;

      if (e.type != 'touchmove') {
        e.stopPropagation();
        e.stopImmediatePropagation();
        e.preventDefault();

        // Simulate click.
        if (!$btn.hasClass('fr-selected')) {
          $('.fr-selected').removeClass('fr-selected');
          return false;
        }
        $('.fr-selected').removeClass('fr-selected');

        if ($btn.data('dragging') || $btn.attr('disabled')) {
          $btn.removeData('dragging');
          return false;
        }

        var timeout = $btn.data('timeout');
        if (timeout) {
          clearTimeout(timeout);
          $btn.removeData('timeout');
        }

        handler.apply(editor, [e]);
      }
      else {
        if (!$btn.data('timeout')) {
          $btn.data('timeout', setTimeout(function () {
            $btn.data('dragging', true);
          }, 100));
        }
      }
    }

    function enableBlur () {
      _do_blur = true;
    }

    function disableBlur () {
      _do_blur = false;
    }

    function blurActive () {
      return _do_blur;
    }

    /**
     * Bind click on an element.
     */
    function bindClick ($element, selector, handler) {
      $on($element, editor._mousedown, selector, function (e) {
        if (!editor.edit.isDisabled()) _buttonMouseDown(e);
      }, true);

      $on($element, editor._mouseup + ' ' + editor._move, selector, function (e) {
        if (!editor.edit.isDisabled()) _buttonMouseUp(e, handler);
      }, true);

      $on($element, 'mousedown click mouseup', selector, function (e) {
        if (!editor.edit.isDisabled()) e.stopPropagation();
      }, true);

      on('window.mouseup', function () {
        if (!editor.edit.isDisabled()) {
          $element.find(selector).removeClass('fr-selected');
          enableBlur();
        }
      });
    }

    /**
     * Add event.
     */
    function on (name, callback, first) {
      var names = name.split(' ');
      if (names.length > 1) {
        for (var i = 0; i < names.length; i++) {
          on(names[i], callback, first);
        }

        return true;
      }

      if (typeof first == 'undefined') first = false;

      var callbacks;
      if (name.indexOf('shared.') != 0) {
        callbacks = (_events[name] = _events[name] || []);
      }
      else {
        callbacks = (editor.shared._events[name] = editor.shared._events[name] || []);
      }

      if (first) {
        callbacks.unshift(callback);
      }
      else {
        callbacks.push(callback);
      }
    }

		var $_events = [];
		function $on ($el, evs, selector, callback, shared) {
      if (typeof selector == 'function') {
        shared = callback;
        callback = selector;
        selector = false;
      }

      var ary = (!shared ? $_events : editor.shared.$_events);
      var id = (!shared ? editor.id : editor.sid);

      if (!selector) {
        $el.on(evs.split(' ').join('.ed' + id + ' ') + '.ed' + id, callback);
      }
      else {
        $el.on(evs.split(' ').join('.ed' + id + ' ') + '.ed' + id, selector, callback);
      }

      if (ary.indexOf($el.get(0)) < 0) ary.push($el.get(0));
		}

    function _$off (evs, id) {
			for (var i = 0; i < evs.length; i++) {
				$(evs[i]).off('.ed' + id);
			}
    }

		function $off () {
      _$off($_events, editor.id);
      $_events = [];

      if (editor.shared.count == 0) {
  			_$off(editor.shared.$_events, editor.sid);
        editor.shared.$_events = null;
      }
		}

    /**
     * Trigger an event.
     */
    function trigger (name, args, force) {
      if (!editor.edit.isDisabled() || force) {
        var callbacks;

        if (name.indexOf('shared.') != 0) {
          callbacks = _events[name];
        }
        else {
          if (editor.shared.count > 0) return false;
          callbacks = editor.shared._events[name];
        }

        var val;

        if (callbacks) {
          for (var i = 0; i < callbacks.length; i++) {
            val = callbacks[i].apply(editor, args);
            if (val === false) return false;
          }
        }

        // Trigger event outside.
        val = editor.$oel.triggerHandler('froalaEditor.' + name, $.merge([editor], (args || [])));
        if (val === false) return false;

        return val;
      }
    }

    function chainTrigger (name, param, force) {
      if (!editor.edit.isDisabled() || force) {
        var callbacks;

        if (name.indexOf('shared.') != 0) {
          callbacks = _events[name];
        }
        else {
          if (editor.shared.count > 0) return false;
          callbacks = editor.shared._events[name];
        }

        var resp;

        if (callbacks) {
          for (var i = 0; i < callbacks.length; i++) {
            // Get the callback response.
            resp = callbacks[i].apply(editor, [param]);

            // If callback response is defined then assign it to param.
            if (typeof resp !== 'undefined') param = resp;
          }
        }

        // Trigger event outside.
        resp = editor.$oel.triggerHandler('froalaEditor.' + name, $.merge([editor], [param]));

        // If callback response is defined then assign it to param.
        if (typeof resp !== 'undefined') param = resp;

        return param;
      }
    }

    /**
     * Destroy
     */
    function _destroy () {
      // Clear the events list.
      for (var k in _events) {
        if (_events.hasOwnProperty(k)) {
          delete _events[k];
        }
      }
    }

    function _sharedDestroy () {
      for (var k in editor.shared._events) {
        if (editor.shared._events.hasOwnProperty(k)) {
          delete editor.shared._events[k];
        }
      }
    }

    /**
     * Tear up.
     */
    function _init () {
      editor.shared.$_events = editor.shared.$_events || [];
      editor.shared._events = {};

      _forMouse();

      _forElement();
      _forWindow();
      _forDocument();
      _forKeys();

      _forFocus();
      enableBlur();

      _forPaste();

      on('destroy', _destroy);
      on('shared.destroy', _sharedDestroy);
    }

    return {
      _init: _init,
      on: on,
      trigger: trigger,
      bindClick: bindClick,
      disableBlur: disableBlur,
      enableBlur: enableBlur,
      blurActive: blurActive,
      focus: focus,
      chainTrigger: chainTrigger,
			$on: $on,
			$off: $off
    }
  };


  $.FE.INVISIBLE_SPACE = '&#8203;';
  $.FE.START_MARKER = '<span class="fr-marker" data-id="0" data-type="true" style="display: none; line-height: 0;">' + $.FE.INVISIBLE_SPACE + '</span>';
  $.FE.END_MARKER = '<span class="fr-marker" data-id="0" data-type="false" style="display: none; line-height: 0;">'  + $.FE.INVISIBLE_SPACE + '</span>';
  $.FE.MARKERS = $.FE.START_MARKER + $.FE.END_MARKER;

  $.FE.MODULES.markers = function (editor) {
    /**
     * Build marker element.
     */
    function _build (marker, id) {
      return $('<span class="fr-marker" data-id="' + id + '" data-type="' + marker + '" style="display: ' + (editor.browser.safari ? 'none' : 'inline-block') + '; line-height: 0;">' + $.FE.INVISIBLE_SPACE + '</span>', editor.doc)[0];
    }

    /**
     * Place marker.
     */
    function place (range, marker, id) {
      try {
        var boundary = range.cloneRange();
        boundary.collapse(marker);

        boundary.insertNode(_build(marker, id));

        if (marker === true && range.collapsed) {
          var mk = editor.$el.find('span.fr-marker[data-type="true"][data-id="' + id + '"]');
          var sibling = mk.get(0).nextSibling;
          while (sibling && sibling.nodeType === Node.TEXT_NODE && sibling.textContent.length === 0) {
            $(sibling).remove();
            sibling = mk.nextSibling;
          }
        }

        if (marker === true && !range.collapsed) {
          var mk = editor.$el.find('span.fr-marker[data-type="true"][data-id="' + id + '"]').get(0);
          var sibling = mk.nextSibling;
          if (sibling && sibling.nodeType === Node.ELEMENT_NODE && editor.node.isBlock(sibling)) {
            // Place the marker deep inside the block tags.
            var contents = [sibling];
            do {
              sibling = contents[0];
              contents = editor.node.contents(sibling);
            } while (contents[0] && editor.node.isBlock(contents[0]));

            $(sibling).prepend($(mk));
          }
        }

        if (marker === false && !range.collapsed) {
          var mk = editor.$el.find('span.fr-marker[data-type="false"][data-id="' + id + '"]').get(0);
          var sibling = mk.previousSibling;
          if (sibling && sibling.nodeType === Node.ELEMENT_NODE && editor.node.isBlock(sibling)) {
            // Place the marker deep inside the block tags.
            var contents = [sibling];
            do {
              sibling = contents[contents.length - 1];
              contents = editor.node.contents(sibling);
            } while (contents[contents.length - 1] && editor.node.isBlock(contents[contents.length - 1]));

            $(sibling).append($(mk));
          }

          // https://github.com/froala/wysiwyg-editor/issues/705
          if (mk.parentNode && ['TD', 'TH'].indexOf(mk.parentNode.tagName) >= 0) {
            if (mk.parentNode.previousSibling && !mk.previousSibling) {
              $(mk.parentNode.previousSibling).append(mk);
            }
          }
        }

        var dom_marker = editor.$el.find('span.fr-marker[data-type="' + marker + '"][data-id="' + id + '"]').get(0);

        // If image is at the top of the editor in an empty P
        // and floated to right, the text will be pushed down
        // when trying to insert an image.
        if (dom_marker) dom_marker.style.display = 'none';
        return dom_marker;
      }
      catch (ex) {
        return null;
      }
    }

    /**
     * Insert a single marker.
     */
    function insert () {
      if (!editor.$wp) return null;

      try {
        var range = editor.selection.ranges(0);
        var containter = range.commonAncestorContainer;

        // Check if selection is inside editor.
        if (containter != editor.$el.get(0) && editor.$el.find(containter).length == 0) return null;

        var boundary = range.cloneRange();
        var original_range = range.cloneRange();
        boundary.collapse(true);

        var mk = $('<span class="fr-marker" style="display: none; line-height: 0;">' + $.FE.INVISIBLE_SPACE + '</span>', editor.doc)[0];

        boundary.insertNode(mk);

        mk = editor.$el.find('span.fr-marker').get(0);

        if (mk) {
          var sibling = mk.nextSibling;
          while (sibling && sibling.nodeType === Node.TEXT_NODE && sibling.textContent.length === 0) {
            $(sibling).remove();
            sibling = editor.$el.find('span.fr-marker').get(0).nextSibling;
          }

          // Keep original selection.
          editor.selection.clear();
          editor.selection.get().addRange(original_range);

          return mk;
        }
        else {
          return null;
        }
      }
      catch (ex) {
        console.warn ('MARKER', ex)
      }
    }

    /**
     * Split HTML at the marker position.
     */
    function split () {
      if (!editor.selection.isCollapsed()) {
        editor.selection.remove();
      }

      var marker = editor.$el.find('.fr-marker').get(0);
      if (marker == null) marker = insert();
      if (marker == null) return null;

      var deep_parent;
      if ((deep_parent = editor.node.deepestParent(marker))) {
        if (editor.node.isBlock(deep_parent) && editor.node.isEmpty(deep_parent)) {
          $(deep_parent).replaceWith('<span class="fr-marker"></span>');
        }
        else {
          var node = marker;
          var close_str = '';
          var open_str = '';
          do {
            node = node.parentNode;
            close_str = close_str + editor.node.closeTagString(node);
            open_str = editor.node.openTagString(node) + open_str;
          } while (node != deep_parent);

          $(marker).replaceWith('<span id="fr-break"></span>');
          var h = editor.node.openTagString(deep_parent) + $(deep_parent).html() + editor.node.closeTagString(deep_parent);
          h = h.replace(/<span id="fr-break"><\/span>/g, close_str + '<span class="fr-marker"></span>' + open_str);

          $(deep_parent).replaceWith(h);
        }
      }

      return editor.$el.find('.fr-marker').get(0)
    }

    /**
     * Insert marker at point from event.
     *
     * http://stackoverflow.com/questions/11191136/set-a-selection-range-from-a-to-b-in-absolute-position
     * https://developer.mozilla.org/en-US/docs/Web/API/this.document.caretPositionFromPoint
     */
    function insertAtPoint (e) {
      var x = e.clientX;
      var y = e.clientY;

      // Clear markers.
      remove();

      var start;
      var range = null;

      // Default.
      if (typeof editor.doc.caretPositionFromPoint != 'undefined') {
        start = editor.doc.caretPositionFromPoint(x, y);
        range = editor.doc.createRange();

        range.setStart(start.offsetNode, start.offset);
        range.setEnd(start.offsetNode, start.offset);
      }

      // Webkit.
      else if (typeof editor.doc.caretRangeFromPoint != 'undefined') {
        start = editor.doc.caretRangeFromPoint(x, y);
        range = editor.doc.createRange();

        range.setStart(start.startContainer, start.startOffset);
        range.setEnd(start.startContainer, start.startOffset);
      }

      // Set ranges.
      if (range !== null && typeof editor.win.getSelection != 'undefined') {
        var sel = editor.win.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
      }

      // MSIE.
      else if (typeof editor.doc.body.createTextRange != 'undefined') {
        try {
          range = editor.doc.body.createTextRange();
          range.moveToPoint(x, y);
          var end_range = range.duplicate();
          end_range.moveToPoint(x, y);
          range.setEndPoint('EndToEnd', end_range);
          range.select();
        }
        catch (ex) {
          return false;
        }
      }

      insert();
    }

    /**
     * Remove markers.
     */
    function remove () {
      editor.$el.find('.fr-marker').remove();
    }

    return {
      place: place,
      insert: insert,
      split: split,
      insertAtPoint: insertAtPoint,
      remove: remove
    }
  };


  $.FE.MODULES.selection = function (editor) {
    /**
     * Get selection text.
     */
    function text () {
      var text = '';

      if (editor.win.getSelection) {
        text = editor.win.getSelection();
      } else if (editor.doc.getSelection) {
        text = editor.doc.getSelection();
      } else if (editor.doc.selection) {
        text = editor.doc.selection.createRange().text;
      }

      return text.toString();
    }

    /**
     * Get the selection object.
     */
    function get () {
      var selection = '';
      if (editor.win.getSelection) {
        selection = editor.win.getSelection();
      } else if (editor.doc.getSelection) {
        selection = editor.doc.getSelection();
      } else {
        selection = editor.doc.selection.createRange();
      }

      return selection;
    }

    /**
     * Get the selection ranges or a single range at a specified index.
    */
    function ranges (index) {
      var sel = get();
      var ranges = [];

      // Get ranges.
      if (sel && sel.getRangeAt && sel.rangeCount) {
        var ranges = [];
        for (var i = 0; i < sel.rangeCount; i++) {
          ranges.push(sel.getRangeAt(i));
        }
      }
      else {
        if (editor.doc.createRange) {
          ranges = [editor.doc.createRange()];
        } else {
          ranges = [];
        }
      }

      return (typeof index != 'undefined' ? ranges[index] : ranges);
    }

    /**
     * Clear selection.
     */
    function clear () {
      var sel = get();

      try {
        if (sel.removeAllRanges) {
          sel.removeAllRanges();
        } else if (sel.empty) {  // IE?
          sel.empty();
        } else if (sel.clear) {
          sel.clear();
        }
      }
      catch (ex) {}
    }

    /**
     * Selection element.
    */
    function element () {
      var sel = get();

      try {
        if (sel.rangeCount) {
          var range = ranges(0);
          var node = range.startContainer;

          // Get parrent if node type is not DOM.
          if (node.nodeType == Node.ELEMENT_NODE) {
            var node_found = false;

            // Search for node deeper.
            if (node.childNodes.length > 0 && node.childNodes[range.startOffset]) {
              var child = node.childNodes[range.startOffset];
              while (child && child.nodeType == Node.TEXT_NODE && child.textContent.length == 0) {
                child = child.nextSibling;
              }

              if (child && child.textContent.replace(/\u200B/g, '') === text().replace(/\u200B/g, '')) {
                node = child;
                node_found = true;
              }
            }
            // Selection starts just at the end of the node.
            else if (!range.collapsed && node.nextSibling && node.nextSibling.nodeType == Node.ELEMENT_NODE) {
              var child = node.nextSibling;

              if (child && child.textContent.replace(/\u200B/g, '') === text().replace(/\u200B/g, '')) {
                node = child;
                node_found = true;
              }
            }

            if (!node_found && node.childNodes.length > 0 && $(node.childNodes[0]).text().replace(/\u200B/g, '') === text().replace(/\u200B/g, '') && ['BR', 'IMG', 'HR'].indexOf(node.childNodes[0].tagName) < 0) {
              node = node.childNodes[0];
            }
          }

          while (node.nodeType != Node.ELEMENT_NODE && node.parentNode) {
            node = node.parentNode;
          }

          // Make sure the node is in editor.
          var p = node;
          while (p && p.tagName != 'HTML') {
            if (p == editor.$el.get(0)) {
              return node;
            }

            p = $(p).parent()[0];
          }
        }
      }
      catch (ex) {

      }

      return editor.$el.get(0);
    }

    /**
     * Selection element.
    */
    function endElement () {
      var sel = get();

      try {
        if (sel.rangeCount) {
          var range = ranges(0);
          var node = range.endContainer;

          // Get parrent if node type is not DOM.
          if (node.nodeType == Node.ELEMENT_NODE) {
            var node_found = false;

            // Search for node deeper.
            if (node.childNodes.length > 0 && node.childNodes[range.endOffset] && $(node.childNodes[range.endOffset]).text() === text()) {
              node = node.childNodes[range.endOffset];
              node_found = true;
            }
            // Selection starts just at the end of the node.
            else if (!range.collapsed && node.previousSibling && node.previousSibling.nodeType == Node.ELEMENT_NODE) {
              var child = node.previousSibling;

              if (child && child.textContent.replace(/\u200B/g, '') === text().replace(/\u200B/g, '')) {
                node = child;
                node_found = true;
              }
            }
            // Browser sees selection at the beginning of the next node.
            else if (!range.collapsed && node.childNodes.length > 0 && node.childNodes[range.endOffset]) {
              var child = node.childNodes[range.endOffset].previousSibling;

              if (child.nodeType == Node.ELEMENT_NODE) {
                if (child && child.textContent.replace(/\u200B/g, '') === text().replace(/\u200B/g, '')) {
                  node = child;
                  node_found = true;
                }
              }
            }

            if (!node_found && node.childNodes.length > 0 && $(node.childNodes[node.childNodes.length - 1]).text() === text() && ['BR', 'IMG', 'HR'].indexOf(node.childNodes[node.childNodes.length - 1].tagName) < 0) {
              node = node.childNodes[node.childNodes.length - 1];
            }
          }

          if (node.nodeType == Node.TEXT_NODE && range.endOffset == 0 && node.previousSibling && node.previousSibling.nodeType == Node.ELEMENT_NODE) {
            node = node.previousSibling;
          }

          while (node.nodeType != Node.ELEMENT_NODE && node.parentNode) {
            node = node.parentNode;
          }

          // Make sure the node is in editor.
          var p = node;
          while (p && p.tagName != 'HTML') {
            if (p == editor.$el.get(0)) {
              return node;
            }

            p = $(p).parent()[0];
          }
        }
      }
      catch (ex) {
      }

      return editor.$el.get(0);
    }

    /**
     * Get the ELEMENTS node where the selection starts.
     * By default TEXT node might be selected.
     */
    function rangeElement(rangeContainer, offset) {
      var node = rangeContainer;
      if (node.nodeType == Node.ELEMENT_NODE) {
        // Search for node deeper.
        if (node.childNodes.length > 0 && node.childNodes[offset]) {
          node = node.childNodes[offset];
        }
      }

      if (node.nodeType == Node.TEXT_NODE) {
        node = node.parentNode;
      }

      return node;
    }

    /**
     * Search for the current selected blocks.
     */
    function blocks () {
      var blks = [];

      var sel = get();

      // Selection must be inside editor.
      if (inEditor() && sel.rangeCount) {

        // Loop through ranges.
        var rngs = ranges();
        for (var i = 0; i < rngs.length; i++) {
          var range = rngs[i];

          // Get start node and end node for range.
          var start_node = rangeElement(range.startContainer, range.startOffset);
          var end_node  = rangeElement(range.endContainer, range.endOffset);

          // Add the start node.
          if (editor.node.isBlock(start_node) && blks.indexOf(start_node) < 0) blks.push(start_node);

          // Check for the parent node of the start node.
          var block_parent = editor.node.blockParent(start_node);
          if (block_parent && blks.indexOf(block_parent) < 0) {
            blks.push(block_parent);
          }

          // Do not add nodes where we've been once.
          var was_into = [];

          // Loop until we reach end.
          var next_node = start_node;
          while (next_node !== end_node && next_node !== editor.$el.get(0)) {
            // Get deeper into the current node.
            if (was_into.indexOf(next_node) < 0 && next_node.children && next_node.children.length) {
              was_into.push(next_node);
              next_node = next_node.children[0];
            }

            // Get next sibling.
            else if (next_node.nextSibling) {
              next_node = next_node.nextSibling;
            }

            // Get parent node.
            else if (next_node.parentNode) {
              next_node = next_node.parentNode;
              was_into.push(next_node);
            }

            // Add node to the list.
            if (editor.node.isBlock(next_node) && was_into.indexOf(next_node) < 0 && blks.indexOf(next_node) < 0) {
              if (next_node !== end_node || range.endOffset > 0) {
                blks.push(next_node);
              }
            }
          }

          // Add the end node.
          if (editor.node.isBlock(end_node) && blks.indexOf(end_node) < 0 && range.endOffset > 0) blks.push(end_node);

          // Check for the parent node of the end node.
          var block_parent = editor.node.blockParent(end_node);
          if (block_parent && blks.indexOf(block_parent) < 0) {
            blks.push(block_parent);
          }
        }
      }

      // Remove blocks that we don't need.
      for (var i = blks.length - 1; i > 0; i--) {
        // Nodes that contain another node. Don't do it for LI.
        if ($(blks[i]).find(blks).length && blks[i].tagName != 'LI') blks.splice(i, 1);
      }

      return blks;
    }

    /**
     * Save selection.
     */
    function save () {
      if (editor.$wp) {
        editor.markers.remove();

        var rgs = ranges();
        var new_ranges = [];

        for (var i = 0; i < rgs.length; i++) {
          if (rgs[i].startContainer !== editor.doc) {
            var range = rgs[i];
            var collapsed = range.collapsed;
            var start_m = editor.markers.place(range, true, i); // Start.
            var end_m = editor.markers.place(range, false, i); // End.

            if (editor.browser.safari && !collapsed) {
              var range = editor.doc.createRange();
              range.setStartAfter(start_m);
              range.setEndBefore(end_m);
              new_ranges.push(range);
            }
          }
        }

        if (editor.browser.safari && new_ranges.length) {
          editor.selection.clear();
          for (var i = 0; i < new_ranges.length; i++) {
            editor.selection.get().addRange(new_ranges[i]);
          }
        }
      }
    }

    /**
     * Restore selection.
     */
    function restore () {
      // Get markers.
      var markers = editor.$el.get(0).querySelectorAll('.fr-marker[data-type="true"]');

      if (!editor.$wp) {
        editor.markers.remove();
        return false;
      }

      // No markers.
      if (markers.length === 0) {
        return false;
      }

      if (editor.browser.msie || editor.browser.edge) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].style.display = 'inline-block';
        }
      }

      // Focus.
      if (!editor.core.hasFocus() && !editor.browser.msie && !editor.browser.webkit) {
        editor.$el.focus();
      }

      clear();
      var sel = get();

      var parents = [];

      // Add ranges.
      for (var i = 0; i < markers.length; i++) {
        var id = $(markers[i]).data('id');
        var start_marker = markers[i];
        var range = editor.doc.createRange();
        var end_marker = editor.$el.find('.fr-marker[data-type="false"][data-id="' + id + '"]');

        if (editor.browser.msie || editor.browser.edge) end_marker.css('display', 'inline-block');

        var ghost = null;

        // Make sure there is an start marker.
        if (end_marker.length > 0) {
          end_marker = end_marker[0];

          try {
            // If we have markers one next to each other inside text, then we should normalize text by joining it.
            var special_case = false;

            // Clear empty text nodes.
            var s_node = start_marker.nextSibling;
            while (s_node && s_node.nodeType == Node.TEXT_NODE && s_node.textContent.length == 0) {
              var tmp = s_node;
              s_node = s_node.nextSibling;
              $(tmp).remove();
            }

            var e_node = end_marker.nextSibling;
            while (e_node && e_node.nodeType == Node.TEXT_NODE && e_node.textContent.length == 0) {
              var tmp = e_node;
              e_node = e_node.nextSibling;
              $(tmp).remove();
            }

            if (start_marker.nextSibling == end_marker || end_marker.nextSibling == start_marker) {
              // Decide which is first and which is last between markers.
              var first_node = (start_marker.nextSibling == end_marker) ? start_marker : end_marker;
              var last_node = (first_node == start_marker) ? end_marker : start_marker;

              // Previous node.
              var prev_node = first_node.previousSibling;
              while (prev_node && prev_node.nodeType == Node.TEXT_NODE && prev_node.length == 0) {
                var tmp = prev_node;
                prev_node = prev_node.previousSibling;
                $(tmp).remove();
              }

              // Normalize text before.
              if (prev_node && prev_node.nodeType == Node.TEXT_NODE) {
                while (prev_node && prev_node.previousSibling && prev_node.previousSibling.nodeType == Node.TEXT_NODE) {
                  prev_node.previousSibling.textContent = prev_node.previousSibling.textContent + prev_node.textContent;
                  prev_node = prev_node.previousSibling;
                  $(prev_node.nextSibling).remove();
                }
              }

              // Next node.
              var next_node = last_node.nextSibling;
              while (next_node && next_node.nodeType == Node.TEXT_NODE && next_node.length == 0) {
                var tmp = next_node;
                next_node = next_node.nextSibling;
                $(tmp).remove();
              }

              // Normalize text after.
              if (next_node && next_node.nodeType == Node.TEXT_NODE) {
                while (next_node && next_node.nextSibling && next_node.nextSibling.nodeType == Node.TEXT_NODE) {
                  next_node.nextSibling.textContent =  next_node.textContent + next_node.nextSibling.textContent;
                  next_node = next_node.nextSibling;
                  $(next_node.previousSibling).remove();
                }
              }

              if (prev_node && (editor.node.isVoid(prev_node) || editor.node.isBlock(prev_node))) prev_node = null;
              if (next_node && (editor.node.isVoid(next_node) || editor.node.isBlock(next_node))) next_node = null;

              // Previous node and next node are both text.
              if (prev_node && next_node && prev_node.nodeType == Node.TEXT_NODE && next_node.nodeType == Node.TEXT_NODE) {
                // Remove markers.
                $(start_marker).remove();
                $(end_marker).remove();

                // Save cursor position.
                var len = prev_node.textContent.length;
                prev_node.textContent = prev_node.textContent + next_node.textContent;
                $(next_node).remove();

                // Normalize spaces.
                editor.spaces.normalize(prev_node);

                // Restore position.
                range.setStart(prev_node, len);
                range.setEnd(prev_node, len);

                special_case = true;
              }
              else if (!prev_node && next_node && next_node.nodeType == Node.TEXT_NODE) {
                // Remove markers.
                $(start_marker).remove();
                $(end_marker).remove();

                // Normalize spaces.
                editor.spaces.normalize(next_node);

                ghost = $(editor.doc.createTextNode('\u200B'));
                $(next_node).before(ghost);

                // Restore position.
                range.setStart(next_node, 0);
                range.setEnd(next_node, 0);
                special_case = true;
              }
              else if (!next_node && prev_node && prev_node.nodeType == Node.TEXT_NODE) {
                // Remove markers.
                $(start_marker).remove();
                $(end_marker).remove();

                // Normalize spaces.
                editor.spaces.normalize(prev_node);

                ghost = $(editor.doc.createTextNode('\u200B'));
                $(prev_node).after(ghost);

                // Restore position.
                range.setStart(prev_node, prev_node.textContent.length);
                range.setEnd(prev_node, prev_node.textContent.length);

                special_case = true;
              }
            }

            if (!special_case) {
              var x, y;
              // DO NOT TOUCH THIS OR IT WILL BREAK!!!
              if (editor.browser.chrome && start_marker.nextSibling == end_marker) {
                x = _normalizedMarker(end_marker, range, true) || range.setStartAfter(end_marker);
                y = _normalizedMarker(start_marker, range, false) || range.setEndBefore(start_marker);
              }
              else {
                if (start_marker.previousSibling == end_marker) {
                  start_marker = end_marker;
                  end_marker = start_marker.nextSibling;
                }

                // https://github.com/froala/wysiwyg-editor/issues/759
                if (!(end_marker.nextSibling && end_marker.nextSibling.tagName === 'BR') &&
                    !(!end_marker.nextSibling && editor.node.isBlock(start_marker.previousSibling)) &&
                    !(start_marker.previousSibling && start_marker.previousSibling.tagName == 'BR')) {
                  start_marker.style.display = 'inline';
                  end_marker.style.display = 'inline';
                  ghost = $(editor.doc.createTextNode('\u200B'));
                }

                // https://github.com/froala/wysiwyg-editor/issues/1120
                var p_node = start_marker.previousSibling;
                if (p_node && p_node.style && editor.win.getComputedStyle(p_node).display == 'block' && !editor.opts.enter == $.FE.ENTER_BR) {
                  range.setEndAfter(p_node);
                  range.setStartAfter(p_node);
                }
                else {
                  x = _normalizedMarker(start_marker, range, true) || ($(start_marker).before(ghost) && range.setStartBefore(start_marker));
                  y = _normalizedMarker(end_marker, range, false) || ($(end_marker).after(ghost) && range.setEndAfter(end_marker));
                }
              }

              if (typeof x == 'function') x();
              if (typeof y == 'function') y();
            }
          } catch (ex) {
            console.warn ('RESTORE RANGE', ex);
          }
        }

        if (ghost) {
          ghost.remove();
        }

        try {
          sel.addRange(range);
        }
        catch (ex) {
          console.warn ('ADD RANGE', ex);
        }
      }

      // Remove used markers.
      editor.markers.remove();
    }

    /**
     * Normalize marker when restoring selection.
     */
    function _normalizedMarker(marker, range, start) {
      var prev_node = marker.previousSibling;
      var next_node = marker.nextSibling;

      // Prev and next node are both text nodes.
      if (prev_node && next_node && prev_node.nodeType == Node.TEXT_NODE && next_node.nodeType == Node.TEXT_NODE) {
        var len = prev_node.textContent.length;

        if (start) {
         next_node.textContent = prev_node.textContent + next_node.textContent;
         $(prev_node).remove();
         $(marker).remove();

         editor.spaces.normalize(next_node);

         return function () {
           range.setStart(next_node, len);
         }
        }
        else {
          prev_node.textContent = prev_node.textContent + next_node.textContent;
          $(next_node).remove();
          $(marker).remove();

          editor.spaces.normalize(prev_node);

          return function () {
            range.setEnd(prev_node, len);
          }
        }
      }
      // Prev node is text node.
      else if (prev_node && !next_node && prev_node.nodeType == Node.TEXT_NODE) {
        var len = prev_node.textContent.length;

        if (start) {
          editor.spaces.normalize(prev_node);
          return function () {
            range.setStart(prev_node, len);
          }
        }
        else {
          editor.spaces.normalize(prev_node);
          return function () {
            range.setEnd(prev_node, len);
          }
        }
      }

      // Next node is text node.
      else if (next_node && !prev_node && next_node.nodeType == Node.TEXT_NODE) {
        if (start) {
          editor.spaces.normalize(next_node);
          return function () {
            range.setStart(next_node, 0);
          }
        }
        else {
          editor.spaces.normalize(next_node);
          return function () {
            range.setEnd(next_node, 0);
          }
        }
      }

      return false;
    }

    /**
     * Determine if we can do delete.
     */
    function _canDelete () {
      return true;
    }

    /**
     * Check if selection is collapsed.
     */
    function isCollapsed () {
      var rgs = ranges();
      for (var i = 0; i < rgs.length; i++) {
        if (!rgs[i].collapsed) return false;
      }

      return true;
    }

    // From: http://www.coderexception.com/0B1B33z1NyQxUQSJ/contenteditable-div-how-can-i-determine-if-the-cursor-is-at-the-start-or-end-of-the-content
    function info (el) {
      var atStart = false;
      var atEnd = false;
      var selRange;
      var testRange;

      if (editor.win.getSelection) {
        var sel = editor.win.getSelection();
        if (sel.rangeCount) {
          selRange = sel.getRangeAt(0);
          testRange = selRange.cloneRange();

          testRange.selectNodeContents(el);
          testRange.setEnd(selRange.startContainer, selRange.startOffset);
          atStart = (testRange.toString() === '');

          testRange.selectNodeContents(el);
          testRange.setStart(selRange.endContainer, selRange.endOffset);
          atEnd = (testRange.toString() === '');
        }
      } else if (editor.doc.selection && editor.doc.selection.type != 'Control') {
        selRange = editor.doc.selection.createRange();
        testRange = selRange.duplicate();

        testRange.moveToElementText(el);
        testRange.setEndPoint('EndToStart', selRange);
        atStart = (testRange.text === '');

        testRange.moveToElementText(el);
        testRange.setEndPoint('StartToEnd', selRange);
        atEnd = (testRange.text === '');
      }

      return { atStart: atStart, atEnd: atEnd };
    }

    /**
     * Check if everything is selected inside the editor.
     */
    function isFull () {
      if (isCollapsed()) return false;

      // https://github.com/froala/wysiwyg-editor/issues/710
      editor.$el.find('td').prepend('<span class="fr-mk">' + $.FE.INVISIBLE_SPACE + '</span>');
      editor.$el.find('img').append('<span class="fr-mk">' + $.FE.INVISIBLE_SPACE + '</span>');

      var full = false;
      var inf = info(editor.$el.get(0));
      if (inf.atStart && inf.atEnd) full = true;

      // https://github.com/froala/wysiwyg-editor/issues/710
      editor.$el.find('.fr-mk').remove();

      return full;
    }

    /**
     * Remove HTML from inner nodes when we deal with keepFormatOnDelete option.
     */
    function _emptyInnerNodes (node, first) {
      if (typeof first == 'undefined') first = true;

      // Remove invisible spaces.
      var h = $(node).html();
      if (h && h.replace(/\u200b/g, '').length != h.length) $(node).html(h.replace(/\u200b/g, ''));

      // Loop contents.
      var contents = editor.node.contents(node);
      for (var j = 0; j < contents.length; j++) {
        // Remove text nodes.
        if (contents[j].nodeType != Node.ELEMENT_NODE) {
          $(contents[j]).remove();
        }

        // Empty inner nodes further.
        else {
          // j == 0 determines if the node is the first one and we should keep format.
          _emptyInnerNodes(contents[j], j == 0);

          // There are inner nodes, ignore the current one.
          if (j == 0) first = false;
        }
      }

      // First node is a text node, so replace it with a span.
      if (node.nodeType == Node.TEXT_NODE) {
        $(node).replaceWith('<span data-first="true" data-text="true"></span>');
      }

      // Add the first node marker so that we add selection in it later on.
      else if (first) {
        $(node).attr('data-first', true);
      }
    }

    /**
     * Process deleting nodes.
     */
    function _processNodeDelete ($node, should_delete) {
      var contents = editor.node.contents($node.get(0));

      // Node is TD or TH.
      if (['TD', 'TH'].indexOf($node.get(0).tagName) >= 0 && $node.find('.fr-marker').length == 1 && $(contents[0]).hasClass('fr-marker')) {
        $node.attr('data-del-cell', true);
      }

      for (var i = 0; i < contents.length; i++) {
        var node = contents[i];

        // We found a marker.
        if ($(node).hasClass('fr-marker')) {
          should_delete = (should_delete + 1) % 2;
        }
        else if (should_delete) {
          // Check if we have a marker inside it.
          if ($(node).find('.fr-marker').length > 0) {
            should_delete = _processNodeDelete($(node), should_delete);
          }
          else {
            // TD, TH or inner, then go further.
            if (['TD', 'TH'].indexOf(node.tagName) < 0 && !$(node).hasClass('fr-inner')) {
              if (!editor.opts.keepFormatOnDelete || editor.$el.find('[data-first]').length > 0) {
                $(node).remove();
              }
              else {
                _emptyInnerNodes(node);
              }
            }
            else if ($(node).hasClass('fr-inner')) {
              if ($(node).find('.fr-inner').length == 0) {
                $(node).html('<br>');
              }
              else {
                $(node).find('.fr-inner').filter(function () {
                  return $(this).find('fr-inner').length == 0;
                }).html('<br>');
              }
            }
            else {
              $(node).empty();
              $(node).attr('data-del-cell', true);
            }
          }
        }
        else {
          if ($(node).find('.fr-marker').length > 0) {
            should_delete = _processNodeDelete($(node), should_delete);
          }
        }
      }

      return should_delete;
    }

    /**
     * Determine if selection is inside the editor.
     */
    function inEditor () {
      try {
        if (!editor.$wp) return false;

        var range = ranges(0);
        var container = range.commonAncestorContainer;

        while (container && !editor.node.isElement(container)) {
          container = container.parentNode;
        }

        if (editor.node.isElement(container)) return true;
        return false;
      }
      catch (ex) {
        return false;
      }
    }

    /**
     * Remove the current selection html.
     */
    function remove () {
      if (isCollapsed()) return true;

      save();

      // Get the previous sibling normalized.
      var _prevSibling = function (node) {
        var prev_node = node.previousSibling;
        while (prev_node && prev_node.nodeType == Node.TEXT_NODE && prev_node.textContent.length == 0) {
          var tmp = prev_node;
          var prev_node = prev_node.previousSibling;
          $(tmp).remove();
        }

        return prev_node;
      }

      // Get the next sibling normalized.
      var _nextSibling = function (node) {
        var next_node = node.nextSibling;
        while (next_node && next_node.nodeType == Node.TEXT_NODE && next_node.textContent.length == 0) {
          var tmp = next_node;
          var next_node = next_node.nextSibling;
          $(tmp).remove();
        }

        return next_node;
      }

      // Normalize start markers.
      var start_markers = editor.$el.find('.fr-marker[data-type="true"]');
      for (var i = 0; i < start_markers.length; i++) {
        var sm = start_markers[i];
        while (!_prevSibling(sm) && !editor.node.isBlock(sm.parentNode) && !editor.$el.is(sm.parentNode)) {
          $(sm.parentNode).before(sm);
        }
      }

      // Normalize end markers.
      var end_markers = editor.$el.find('.fr-marker[data-type="false"]');
      for (var i = 0; i < end_markers.length; i++) {
        var em = end_markers[i];
        while (!_nextSibling(em) && !editor.node.isBlock(em.parentNode) && !editor.$el.is(em.parentNode)) {
          $(em.parentNode).after(em);
        }

        // Last node is empty and has a BR in it.
        if (em.parentNode && editor.node.isBlock(em.parentNode) && editor.node.isEmpty(em.parentNode) && !editor.$el.is(em.parentNode)) {
          $(em.parentNode).after(em);
        }
      }

      // Check if selection can be deleted.
      if (_canDelete()) {
        _processNodeDelete(editor.$el, 0);

        // Look for selection marker.
        var $first_node = editor.$el.find('[data-first="true"]');
        if ($first_node.length) {
          // Remove markers.
          editor.$el.find('.fr-marker').remove();

          // Add markers in the node that we marked as the first one.
          $first_node
            .append($.FE.INVISIBLE_SPACE + $.FE.MARKERS)
            .removeAttr('data-first');

          // Remove span with data-text if there is one.
          if ($first_node.attr('data-text')) {
            $first_node.replaceWith($first_node.html());
          }
        }
        else {
          // Remove tables.
          editor.$el.find('table').filter(function () {
            var ok = $(this).find('[data-del-cell]').length > 0 && $(this).find('[data-del-cell]').length == $(this).find('td, th').length;

            return ok;
          }).remove();
          editor.$el.find('[data-del-cell]').removeAttr('data-del-cell');

          // Merge contents between markers.
          var start_markers = editor.$el.find('.fr-marker[data-type="true"]');
          for (var i = 0; i < start_markers.length; i++) {
            // Get start marker.
            var start_marker = start_markers[i];

            // Get the next node after start marker.
            var next_node = start_marker.nextSibling;

            // Get the end node.
            var end_marker = editor.$el.find('.fr-marker[data-type="false"][data-id="' + $(start_marker).data('id') + '"]').get(0);

            if (end_marker) {
              // Markers are next to other.
              if (next_node && next_node == end_marker) {
                // Do nothing.
              }
              else if (start_marker) {
                // Get the parents of the nodes.
                var start_parent = editor.node.blockParent(start_marker);
                var end_parent = editor.node.blockParent(end_marker);

                // https://github.com/froala/wysiwyg-editor/issues/1233
                var list_start = false;
                var list_end = false;
                if (start_parent && ['UL', 'OL'].indexOf(start_parent.tagName) >= 0) {
                  start_parent = null;
                  list_start = true;
                }
                if (end_parent && ['UL', 'OL'].indexOf(end_parent.tagName) >= 0) {
                  end_parent = null;
                  list_end = true;
                }

                // Move end marker next to start marker.
                $(start_marker).after(end_marker);

                // We're in the same parent. Moving marker is enough.
                if (start_parent == end_parent) {
                }

                // The block parent of the start marker is the element itself.
                else if (start_parent == null && !list_start) {
                  var deep_parent = editor.node.deepestParent(start_marker);

                  // There is a parent for the marker. Move the end html to it.
                  if (deep_parent) {
                    $(deep_parent).after($(end_parent).html());
                    $(end_parent).remove();
                  }

                  // There is no parent for the marker.
                  else if ($(end_parent).parentsUntil(editor.$el, 'table').length == 0) {
                    $(start_marker).next().after($(end_parent).html());
                    $(end_parent).remove();
                  }
                }

                // End marker is inside element. We don't merge in table.
                else if (end_parent == null && !list_end && $(start_parent).parentsUntil(editor.$el, 'table').length == 0) {
                  // Get the node that has a next sibling.
                  var next_node = start_parent;
                  while (!next_node.nextSibling && next_node.parentNode != editor.$el.get(0)) {
                    next_node = next_node.parentNode;
                  }
                  next_node = next_node.nextSibling;

                  // Join HTML inside the start node.
                  while (next_node && next_node.tagName != 'BR') {
                    var tmp_node = next_node.nextSibling;
                    $(start_parent).append(next_node);
                    next_node = tmp_node;
                  }

                  if (next_node && next_node.tagName == 'BR') {
                    $(next_node).remove();
                  }
                }

                // Join end block with start block.
                else if (start_parent && end_parent && $(start_parent).parentsUntil(editor.$el, 'table').length == 0 && $(end_parent).parentsUntil(editor.$el, 'table').length == 0) {
                  $(start_parent).append($(end_parent).html());
                  $(end_parent).remove();
                }
              }
            }

            else {
              end_marker = $(start_marker).clone().attr('data-type', false);
              $(start_marker).after(end_marker);
            }
          }
        }
      }

      if (!editor.opts.keepFormatOnDelete) {
        editor.html.fillEmptyBlocks();
      }

      editor.html.cleanEmptyTags(true);
      editor.clean.lists();

      editor.spaces.normalize();
      restore();
    }

    function setAtStart (node) {
      if ($(node).find('.fr-marker').length > 0) return false;

      var contents = editor.node.contents(node);
      while (contents.length && editor.node.isBlock(contents[0])) {
        node = contents[0];
        contents = editor.node.contents(node);
      }

      $(node).prepend($.FE.MARKERS);
    }

    function setAtEnd (node) {
      if ($(node).find('.fr-marker').length > 0) return false;

      var contents = editor.node.contents(node);
      while (contents.length && editor.node.isBlock(contents[contents.length - 1])) {
        node = contents[contents.length - 1];
        contents = editor.node.contents(node);
      }

      $(node).append($.FE.MARKERS);
    }

    function setBefore (node) {
      var prev_node = node.previousSibling;
      while (prev_node && prev_node.nodeType == Node.TEXT_NODE && prev_node.textContent.length == 0) {
        prev_node = prev_node.previousSibling;
      }

      if (prev_node) {
        if (editor.node.isBlock(prev_node)) {
          setAtEnd(prev_node);
        }
        else if (prev_node.tagName == 'BR') {
          $(prev_node).before($.FE.MARKERS);
        }
        else {
          $(prev_node).after($.FE.MARKERS);
        }

        return true;
      }
      else {
        return false;
      }
    }

    function setAfter (node) {
      var next_node = node.nextSibling;
      while (next_node && next_node.nodeType == Node.TEXT_NODE && next_node.textContent.length == 0) {
        next_node = next_node.nextSibling;
      }

      if (next_node) {
        if (editor.node.isBlock(next_node)) {
          setAtStart(next_node);
        }
        else {
          $(next_node).before($.FE.MARKERS);
        }

        return true;
      }
      else {
        return false;
      }
    }

    return {
      text: text,
      get: get,
      ranges: ranges,
      clear: clear,
      element: element,
      endElement: endElement,
      save: save,
      restore: restore,
      isCollapsed: isCollapsed,
      isFull: isFull,
      inEditor: inEditor,
      remove: remove,
      blocks: blocks,
      info: info,
      setAtEnd: setAtEnd,
      setAtStart: setAtStart,
      setBefore: setBefore,
      setAfter: setAfter,
      rangeElement: rangeElement
    }
  };


  $.FE.MODULES.spaces = function (editor) {
    function _remove (node) {
      var next = node.nextSibling || node.parentNode;

      node.parentNode.removeChild(node);

      return next;
    }

    function _next (prev, current) {
      if ((prev && prev.parentNode === current) || current.nodeName === 'PRE') {
        return current.nextSibling || current.parentNode;
      }

      return current.firstChild || current.nextSibling || current.parentNode;
    }

    // Adaptation of MIT https://github.com/lucthev/collapse-whitespace.
    function collapse (elem) {
      if (!elem.firstChild || elem.nodeName === 'PRE') return;

      var prevText = null;

      var prev = null;
      var node = _next(prev, elem);

      // Go deep while current node is not elem.
      while (node !== elem) {
        // Current node is text node.
        if (node.nodeType === Node.TEXT_NODE) {
          // Collapse spaces.
          var text = node.data.replace(/[ \r\n\t]+/g, ' ');

          // No previous text or previous text ends with space.
          // No previous voide.
          // Current text starts with space.
          if ((!prevText || / $/.test(prevText.data)) && text[0] === ' ') {
            // Remove starting space.
            text = text.substr(1);
          }

          // `text` might be empty at this point.
          if (!text || text.length == 0) {
            node = _remove(node);
            continue;
          }

          // Set new text.
          node.data = text;

          // Set previous text node as current node.
          prevText = node;
        }

        // Current node is element node.
        else if (node.nodeType === Node.ELEMENT_NODE) {
          // Block or BR.
          if (editor.node.isBlock(node) || editor.node.isVoid(node)) {
            // If there was a previous text collapse ending spaces;
            if (prevText && prevText.data) {
              prevText.data = prevText.data.replace(/ $/, '');
            }

            prevText = null;
          }
          else if (node.textContent.length == 0) {
            prevText = node;
          }
        }

        var nextNode = _next(prev, node);
        prev = node;
        node = nextNode;
      }

      // There is previous text.
      if (prevText && prevText.data) {
        // Remove ending.
        prevText.data = prevText.data.replace(/ $/, '')

        // If text is left empty, remove it.
        if (!prevText.data) {
          _remove(prevText);
        }
      }
    }

    function normalize (node, browser_way) {
      if (typeof node == 'undefined' || !node) node = editor.$el.get(0);
      if (typeof browser_way == 'undefined') browser_way = false;

      if (browser_way) {
        collapse(node);
      }

      // Ignore contenteditable.
      if (node.getAttribute && node.getAttribute('contenteditable') == 'false') return;

      if (node.nodeType == Node.ELEMENT_NODE && ['STYLE', 'SCRIPT', 'HEAD'].indexOf(node.tagName) < 0) {
        var contents = editor.node.contents(node);
        for (var i = contents.length - 1; i >= 0 ; i--) {
          if (contents[i].tagName != Node.ELEMENT_NODE || (contents[i].className || '').indexOf('fr-marker') < 0) {
            normalize(contents[i]);
          }
        }
      }
      else if (node.nodeType == Node.TEXT_NODE && node.textContent.length > 0) {
        var prev_node = node.previousSibling;
        var next_node = node.nextSibling;
        var txt = node.textContent;

        // Convert all non breaking to breaking spaces.
        txt = txt.replace(new RegExp($.FE.UNICODE_NBSP, 'g'), ' ');

        var new_text = '';
        for (var t = 0; t < txt.length; t++) {
          if (txt.charCodeAt(t) == 32 && (t === 0 || new_text.charCodeAt(t - 1) == 32)) {
            new_text += $.FE.UNICODE_NBSP;
          }
          else {
            new_text += txt[t];
          }
        }

        // Ending spaces should be NBSP or spaces before block tags.
        if (!node.nextSibling || editor.node.isBlock(node.nextSibling) || (node.nextSibling.nodeType == Node.ELEMENT_NODE && editor.win.getComputedStyle(node.nextSibling).display == 'block')) {
          new_text = new_text.replace(/ $/, $.FE.UNICODE_NBSP);
        }

        // Previous sibling is not void or block.
        if (node.previousSibling && !editor.node.isVoid(node.previousSibling) && !editor.node.isBlock(node.previousSibling)) {
          new_text = new_text.replace(/^\u00A0([^ $])/, ' $1');
        }

        // Convert middle nbsp to spaces.
        new_text = new_text.replace(/([^ \u00A0])\u00A0([^ \u00A0])/g, '$1 $2');

        if (node.textContent != new_text) {
          node.textContent = new_text;
        }
      }
    }

    return {
      normalize: normalize
    }
  };


  $.FE.UNICODE_NBSP = String.fromCharCode(160);

  // Void Elements http://www.w3.org/html/wg/drafts/html/master/syntax.html#void-elements
  $.FE.VOID_ELEMENTS = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'];

  $.FE.BLOCK_TAGS = ['address', 'article', 'aside', 'audio', 'blockquote', 'canvas', 'dd', 'div', 'dl', 'dt', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'hgroup', 'hr', 'li', 'main', 'nav', 'noscript', 'ol', 'output', 'p', 'pre', 'section', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'ul', 'video'];

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    htmlAllowedEmptyTags: ['textarea', 'a', 'iframe', 'object', 'video', 'style', 'script', '.fa', '.fr-emoticon'],
    htmlDoNotWrapTags: ['script', 'style'],
    htmlSimpleAmpersand: false
  });

  $.FE.MODULES.html = function (editor) {
    /**
     * Determine the default block tag.
     */
    function defaultTag () {
      if (editor.opts.enter == $.FE.ENTER_P) return 'p';
      if (editor.opts.enter == $.FE.ENTER_DIV) return 'div';
      if (editor.opts.enter == $.FE.ENTER_BR) return null;
    }

    /**
     * Get the empty blocs.
     */
    function emptyBlocks () {
      var empty_blocks = [];

      // Block tag elements.
      var els = editor.$el.get(0).querySelectorAll(blockTagsQuery());

      // Check if there are empty block tags with markers.
      for (var i = 0; i < els.length; i++) {
        // There are void elements tags.
        if (els[i].querySelectorAll($.FE.VOID_ELEMENTS.join(',')).length > 0) continue;

        // There are other empty elements.
        if (els[i].querySelectorAll(editor.opts.htmlAllowedEmptyTags.join(':not(.fr-marker),') + ':not(.fr-marker)').length > 0) continue;

        // There are other block tags.
        if (els[i].querySelectorAll(blockTagsQuery()).length > 0) continue;

        // We're checking text from here on.
        var contents = editor.node.contents(els[i]);

        var found = false;
        for (var j = 0; j < contents.length; j++) {
          if (contents[j].nodeType == Node.COMMENT_NODE) continue;

          // Text node that is not empty.
          if (contents[j].textContent && contents[j].textContent.replace(/\u200B/g, '').replace(/\n/g, '').length > 0) {
            found = true;
            break;
          }
        }

        // Make sure we don't add TABLE and TD at the same time for instance.
        if (!found) empty_blocks.push(els[i]);
      }

      return empty_blocks;
    }

    /**
     * Create jQuery query for empty block tags.
     */
    function emptyBlockTagsQuery () {
      return $.FE.BLOCK_TAGS.join(':empty, ') + ':empty';
    }

    /**
     * Create jQuery query for selecting block tags.
     */
    function blockTagsQuery () {
      return $.FE.BLOCK_TAGS.join(', ');
    }

    /**
     * Remove empty elements that are not VOID elements.
     */
    function cleanEmptyTags (remove_blocks) {
      var els = $.merge([], $.FE.VOID_ELEMENTS);
      els = $.merge(els, editor.opts.htmlAllowedEmptyTags);

      if (typeof remove_blocks == 'undefined') els = $.merge(els, $.FE.BLOCK_TAGS);

      var elms;
      var ok;
      do {
        ok = false;
        elms = editor.$el.get(0).querySelectorAll('*:empty:not(' + els.join('):not(') + '):not(.fr-marker)');

        // Remove those elements that have no attributes.
        for (var i = 0; i < elms.length; i++) {
          if (elms[i].attributes.length === 0 || typeof elms[i].getAttribute('href') !== 'undefined') {
            $(elms[i]).remove();
            ok = true;
          }
        }

        elms = editor.$el.get(0).querySelectorAll('*:empty:not(' + els.join('):not(') + '):not(.fr-marker)');
      } while (elms.length && ok);

    }

    /**
     * Wrap the content inside the element passed as argument.
     */
    function _wrapElement($el, temp) {
      var default_tag = defaultTag();
      if (temp) default_tag = 'div class="fr-temp-div"';

      if (default_tag) {
        var contents = editor.node.contents($el.get(0));
        var $anchor = null;

        // Loop through contents.
        for (var i = 0; i < contents.length; i++) {

          // Current node.
          var node = contents[i];

          // Current node is a block node.
          if (node.nodeType == Node.ELEMENT_NODE && (editor.node.isBlock(node) || ($(node).is(editor.opts.htmlDoNotWrapTags.join(',')) && !$(node).hasClass('fr-marker')))) {
            $anchor = null;
          }

          // Other node types than element and text.
          else if (node.nodeType != Node.ELEMENT_NODE && node.nodeType != Node.TEXT_NODE) {
            $anchor = null;
          }

          // Current node is BR.
          else if (node.nodeType == Node.ELEMENT_NODE && node.tagName == 'BR') {
            // There is no anchor.
            if ($anchor == null) {
              if (temp) {
                $(node).replaceWith('<' + default_tag + ' data-empty="true"><br></div>');
              }
              else {
                $(node).replaceWith('<' + default_tag + '><br></' + default_tag + '>');
              }
            }

            // There is anchor. Just remove BR.
            else {
              $(node).remove();

              // Check if in anchor we have something else than markers.
              var cnts = editor.node.contents($anchor);
              var found = false;
              for (var j = 0; j < cnts.length; j++) {
                if (!$(cnts[j]).hasClass('fr-marker') && !(cnts[j].nodeType == Node.TEXT_NODE && cnts[j].textContent.replace(/ /g, '').length === 0)) {
                  found = true;
                  break;
                }
              }

              if (found === false) {
                $anchor.append('<br>');
                $anchor.data('empty', true);
              }

              $anchor = null;
            }
          }

          // Text node or other node type.
          else {
            if (node.nodeType == Node.TEXT_NODE && $(node).text().trim().length == 0) {
              $(node).remove();
            }
            else {
              if ($anchor == null) {
                $anchor = $('<' + default_tag + '>');
                $(node).before($anchor);
              }

              if (node.nodeType == Node.TEXT_NODE && $(node).text().trim().length > 0) {
                $anchor.append($(node).clone());
                $(node).remove();
              }
              else {
                $anchor.append($(node));
              }
            }
          }
        }
      }
    }

    /**
     * Wrap the direct content inside the default block tag.
     */
    function _wrap (temp, tables, blockquote, inner) {
      if (!editor.$wp) return false;

      if (typeof temp == 'undefined') temp = false;
      if (typeof tables == 'undefined') tables = false;
      if (typeof blockquote == 'undefined') blockquote = false;
      if (typeof inner == 'undefined') inner = false;

      // Wrap element.
      _wrapElement(editor.$el, temp);

      if (inner) {
        editor.$el.find('.fr-inner').each (function () {
          _wrapElement($(this), temp);
        })
      }

      // Wrap table contents.
      if (tables) {
        editor.$el.find('td, th').each (function () {
          _wrapElement($(this), temp);
        })
      }

      // Wrap table contents.
      if (blockquote) {
        editor.$el.find('blockquote').each (function () {
          _wrapElement($(this), temp);
        })
      }
    }

    /**
     * Unwrap temporary divs.
     */
    function unwrap () {
      editor.$el.find('div.fr-temp-div').each(function () {
        if ($(this).data('empty') || this.parentNode.tagName == 'LI') {
          $(this).replaceWith($(this).html());
        }
        else {
          $(this).replaceWith($(this).html() + '<br>');
        }
      });

      // Remove temp class from other blocks.
      editor.$el.find('.fr-temp-div').removeClass('fr-temp-div').filter(function () {
        return $(this).attr('class') == '';
      }).removeAttr('class');
    }

    /**
     * Add BR inside empty elements.
     */
    function fillEmptyBlocks () {
      var blocks = emptyBlocks();
      for (var i = 0; i < blocks.length; i++) {
        var block = blocks[i];
        if (block.getAttribute('contenteditable') != "false" &&
            block.querySelectorAll(editor.opts.htmlAllowedEmptyTags.join(':not(.fr-marker),') + ':not(.fr-marker)').length == 0 &&
            !editor.node.isVoid(block)) {
          if (block.tagName != 'TABLE') block.appendChild(editor.doc.createElement('br'));
        }
      }

      // Fix for https://github.com/froala/wysiwyg-editor/issues/1166#issuecomment-204549406.
      if (editor.browser.msie && editor.opts.enter == $.FE.ENTER_BR) {
        var contents = editor.node.contents(editor.$el.get(0));
        if (contents.length && contents[contents.length - 1].nodeType == Node.TEXT_NODE) {
          editor.$el.append('<br>');
        }
      }
    }

    /**
     * Get the blocks inside the editable area.
     */
    function blocks () {
      return editor.$el.find(blockTagsQuery());
    }

    /**
     * Clean the blank spaces between the block tags.
     */
    function cleanBlankSpaces (node) {
      if (typeof node == 'undefined') node = editor.$el.get(0);
      if (node && ['SCRIPT', 'STYLE', 'PRE'].indexOf(node.tagName) >= 0) return false;

      var contents = editor.node.contents(node);

      // Loop contents.
      for (var i = contents.length - 1; i >= 0; i--) {
        // Content is text and node is block.
        if (contents[i].nodeType == Node.TEXT_NODE) {
          var len = -1;

          // Remove middle spaces.
          contents[i].textContent = contents[i].textContent.replace(/(?!^)( ){2,}(?!$)/g, ' ');

          // Replace new lines with spaces.
          contents[i].textContent = contents[i].textContent.replace(/\n/g, ' ');

          // Replace begin/end spaces.
          contents[i].textContent = contents[i].textContent.replace(/^[ ]{2,}/g, ' ');
          contents[i].textContent = contents[i].textContent.replace(/[ ]{2,}$/g, ' ');

          if (editor.node.isBlock(node) || editor.node.isElement(node)) {
            // No previous siblings.
            if (!contents[i].previousSibling) {
              contents[i].textContent = contents[i].textContent.replace(/^ */,'');
            }

            // No next siblings.
            if (!contents[i].nextSibling) {
              contents[i].textContent = contents[i].textContent.replace(/ *$/,'');
            }

            if (contents[i].previousSibling && contents[i].nextSibling && contents[i].textContent == ' ') {
              if (contents[i].previousSibling && contents[i].nextSibling && editor.node.isBlock(contents[i].previousSibling) && editor.node.isBlock(contents[i].nextSibling)) {
                contents[i].textContent = '';
              }
              else {
                contents[i].textContent = "\n";
              }
            }
          }
        }
        else {
          cleanBlankSpaces(contents[i]);
        }
      }
    }

    function _isBlock (node) {
      return node && (editor.node.isBlock(node) || ['STYLE', 'SCRIPT', 'HEAD', 'BR', 'HR'].indexOf(node.tagName) >= 0 || node.nodeType == Node.COMMENT_NODE);
    }

    function doNormalize (node) {
      if (typeof node == 'undefined') node = editor.$el.get(0);

      if (node.nodeType == Node.ELEMENT_NODE && ['STYLE', 'SCRIPT', 'HEAD'].indexOf(node.tagName) < 0) {
        var contents = editor.node.contents(node);
        for (var i = contents.length - 1; i >= 0 ; i--) {
          if (!$(contents[i]).hasClass('fr-marker')) {
            var r = doNormalize(contents[i]);
            if (r == true) return true;
          }
        }
      }
      else if (node.nodeType == Node.TEXT_NODE && node.textContent.length > 0) {
        var prev_node = node.previousSibling;
        var next_node = node.nextSibling;

        if (_isBlock(prev_node) && _isBlock(next_node) && node.textContent.trim().length === 0) {
          return true;
        }
        else {
          var txt = node.textContent;
          txt = txt.replace(new RegExp($.FE.UNICODE_NBSP, 'g'), ' ');

          var new_text = ''
          for (var t = 0; t < txt.length; t++) {
            if (txt.charCodeAt(t) == 32 && (t === 0 || new_text.charCodeAt(t - 1) == 32)) {
              new_text += $.FE.UNICODE_NBSP;
            }
            else {
              new_text += txt[t];
            }
          }

          if (!node.nextSibling) new_text = new_text.replace(/ $/, $.FE.UNICODE_NBSP);
          if (node.previousSibling && !editor.node.isVoid(node.previousSibling)) new_text = new_text.replace(/^\u00A0([^ $])/, ' $1');

          new_text = new_text.replace(/([^ \u00A0])\u00A0([^ \u00A0])/g, '$1 $2');

          if (node.textContent != new_text) {
            return true;
          }
        }
      }

      return false;
    }

    /**
     * Extract a specific match for a RegEx.
     */
    function _extractMatch (html, re, id) {
      var reg_exp = new RegExp(re, 'gi');
      var matches = reg_exp.exec(html);

      if (matches) {
        return matches[id];
      }

      return null;
    }

    /**
     * Create new doctype.
     */
    function _newDoctype (string, doc) {
      var matches = string.match(/<!DOCTYPE ?([^ ]*) ?([^ ]*) ?"?([^"]*)"? ?"?([^"]*)"?>/i);
      if (matches) {
        return doc.implementation.createDocumentType(
          matches[1],
          matches[3],
          matches[4]
        )
      }
      else {
        return doc.implementation.createDocumentType('html');
      }
    }

    /**
     * Get string doctype of a document.
     */
    function getDoctype (doc) {
      var node = doc.doctype;
      var doctype = '<!DOCTYPE html>';
      if (node) {
        doctype = '<!DOCTYPE '
                  + node.name
                  + (node.publicId ? ' PUBLIC "' + node.publicId + '"' : '')
                  + (!node.publicId && node.systemId ? ' SYSTEM' : '')
                  + (node.systemId ? ' "' + node.systemId + '"' : '')
                  + '>';
      }

      return doctype;
    }

    /**
     * Normalize.
     */
    function _normalize () {
      // Wrap possible text.
      _wrap();

      // Clean blank spaces.
      cleanBlankSpaces();

      // Remove empty tags.
      cleanEmptyTags();

      // Normalize spaces.
      editor.spaces.normalize(null, true);

      // Add BR tag where it is necessary.
      editor.html.fillEmptyBlocks();

      // Clean quotes.
      editor.clean.quotes();

      // Clean lists.
      editor.clean.lists();

      // Clean tables.
      editor.clean.tables();

      // Convert to HTML5.
      editor.clean.toHTML5();

      // Restore selection.
      editor.selection.restore();

      // Check if editor is empty and add placeholder.
      checkIfEmpty();

      // Refresh placeholder.
      editor.placeholder.refresh();
    }

    function checkIfEmpty () {
      if (editor.core.isEmpty()) {
        if (defaultTag() != null) {
          // There is no block tag inside the editor.
          if (editor.$el.get(0).querySelectorAll(blockTagsQuery()).length === 0 &&
               editor.$el.get(0).querySelectorAll(editor.opts.htmlDoNotWrapTags.join(':not(.fr-marker),') + ':not(.fr-marker)').length === 0) {
            if (editor.core.hasFocus()) {
              editor.$el.html('<' + defaultTag() + '>' + $.FE.MARKERS + '<br/></' + defaultTag() + '>');
              editor.selection.restore();
            }
            else {
              editor.$el.html('<' + defaultTag() + '>' + '<br/></' + defaultTag() + '>');
            }
          }
        }
        else {
          // There is nothing in the editor.
          if (editor.$el.get(0).querySelectorAll('*:not(.fr-marker):not(br)').length === 0) {
            if (editor.core.hasFocus()) {
              editor.$el.html($.FE.MARKERS + '<br/>');
              editor.selection.restore();
            }
            else {
              editor.$el.html('<br/>');
            }
          }
        }
      }
    }

    function extractNode (html, tag) {
      return _extractMatch(html, '<' + tag +'[^>]*?>([\\w\\W]*)<\/' + tag + '>', 1);
    }

    function extractNodeAttrs (html, tag) {
      var $dv = $('<div ' + (_extractMatch(html, '<' + tag + '([^>]*?)>', 1) || '') + '>');
      return editor.node.rawAttributes($dv.get(0));
    }

    function extractDoctype (html) {
      return _extractMatch(html, '<!DOCTYPE([^>]*?)>', 0) || '<!DOCTYPE html>';
    }

    /**
     * Set HTML.
     */
    function set (html) {
      var clean_html = editor.clean.html(html || '', [], [], editor.opts.fullPage);

      if (!editor.opts.fullPage) {
        editor.$el.html(clean_html);
      }
      else {
        // Get BODY data.
        var body_html = (extractNode(clean_html, 'body') || (clean_html.indexOf('<body') >= 0 ? '' : clean_html));
        var body_attrs = extractNodeAttrs(clean_html, 'body');

        // Get HEAD data.
        var head_html = extractNode(clean_html, 'head') || '<title></title>';
        var head_attrs = extractNodeAttrs(clean_html, 'head');

        // Get HTML that might be in <head> other than meta tags.
        // https://github.com/froala/wysiwyg-editor/issues/1208
        var head_bad_html = $('<div>')
                              .append(head_html)
                              .find('base, link, meta, noscript, script, style, template, title')
                              .remove().end()
                              .html().trim();

        // Filter and keep only meta tags in <head>.
        // https://html.spec.whatwg.org/multipage/dom.html#metadata-content-2
        head_html = $('<div>')
                              .append(head_html)
                              .find('base, link, meta, noscript, script, style, template, title')
                              .map(function () {
                                return this.outerHTML;
                              }).toArray().join('');

        // Get DOCTYPE.
        var doctype = extractDoctype(clean_html);

        // Get HTML attributes.
        var html_attrs = extractNodeAttrs(clean_html, 'html');

        editor.$el.html(head_bad_html + '\n' + body_html);
        editor.node.clearAttributes(editor.$el.get(0));
        editor.$el.attr(body_attrs);

        editor.$head.html(head_html);
        editor.node.clearAttributes(editor.$head.get(0));
        editor.$head.attr(head_attrs);

        editor.node.clearAttributes(editor.$html.get(0));

        editor.$html.attr(html_attrs);

        editor.iframe_document.doctype.parentNode.replaceChild(
          _newDoctype(doctype, editor.iframe_document),
          editor.iframe_document.doctype
        );
      }

      // Make sure the content is editable.
      var disabled = editor.edit.isDisabled();
      editor.edit.on();

      editor.core.injectStyle(editor.opts.iframeStyle);

      _normalize();

      if (!editor.opts.useClasses) {
        // Restore orignal attributes if present.
        editor.$el.find('[fr-original-class]').each (function () {
          this.setAttribute('class', this.getAttribute('fr-original-class'));
          this.removeAttribute('fr-original-class');
        });

        editor.$el.find('[fr-original-style]').each (function () {
          this.setAttribute('style', this.getAttribute('fr-original-style'));
          this.removeAttribute('fr-original-style');
        });
      }

      if (disabled) editor.edit.off();

      editor.events.trigger('html.set');
    }

    /**
     * Get HTML.
     */
    function get (keep_markers, keep_classes) {
      if (!editor.$wp) {
        return editor.$oel.clone()
                .removeClass('fr-view')
                .removeAttr('contenteditable')
                .get(0).outerHTML;
      }

      var html = '';

      editor.events.trigger('html.beforeGet');

      var specifity = function (selector) {
        var idRegex = /(#[^\s\+>~\.\[:]+)/g;
        var attributeRegex = /(\[[^\]]+\])/g;
        var classRegex = /(\.[^\s\+>~\.\[:]+)/g;
        var pseudoElementRegex = /(::[^\s\+>~\.\[:]+|:first-line|:first-letter|:before|:after)/gi;
        var pseudoClassWithBracketsRegex = /(:[\w-]+\([^\)]*\))/gi;
  			// A regex for other pseudo classes, which don't have brackets
  			var pseudoClassRegex = /(:[^\s\+>~\.\[:]+)/g;
  			var elementRegex = /([^\s\+>~\.\[:]+)/g;

        // Remove the negation psuedo-class (:not) but leave its argument because specificity is calculated on its argument
    		(function() {
    			var regex = /:not\(([^\)]*)\)/g;
    			if (regex.test(selector)) {
    				selector = selector.replace(regex, '     $1 ');
    			}
    		}());

        var s = (selector.match(idRegex) || []).length * 100 +
                (selector.match(attributeRegex) || []).length * 10 +
                (selector.match(classRegex) || []).length * 10 +
                (selector.match(pseudoClassWithBracketsRegex) || []).length * 10 +
                (selector.match(pseudoClassRegex) || []).length * 10 +
                (selector.match(pseudoElementRegex) || []).length;

        // Remove universal selector and separator characters
    		selector = selector.replace(/[\*\s\+>~]/g, ' ');

    		// Remove any stray dots or hashes which aren't attached to words
    		// These may be present if the user is live-editing this selector
    		selector = selector.replace(/[#\.]/g, ' ');

        s += (selector.match(elementRegex) || []).length;

        return s;
      }

      // Convert STYLE from CSS files to inline style.
      var updated_elms = [];
      var elms_info = {};
      var i;
      if (!editor.opts.useClasses && !keep_classes) {
        for (i = 0; i < editor.doc.styleSheets.length; i++) {
          var rules;
          var head_style = 0;
          try {
            rules = editor.doc.styleSheets[i].cssRules;
            if (editor.doc.styleSheets[i].ownerNode && editor.doc.styleSheets[i].ownerNode.nodeType == 'STYLE') {
              head_style = 1;
            }
          }
          catch (ex) {
          }

          if (rules) {
            for (var idx = 0, len = rules.length; idx < len; idx++) {
              var class_selector = editor.opts.iframe ? 'body ' : '.fr-view ';

              if (rules[idx].selectorText && rules[idx].selectorText.indexOf(class_selector) === 0) {
                if (rules[idx].style.cssText.length > 0) {
                  var selector = rules[idx].selectorText.replace(class_selector, '').replace(/::/g, ':');
                  var elms = editor.$el.get(0).querySelectorAll(selector);

                  for (var j = 0; j < elms.length; j++) {
                    // Save original style.
                    if (!elms[j].getAttribute('fr-original-style') && elms[j].getAttribute('style')) {
                      elms[j].setAttribute('fr-original-style', elms[j].getAttribute('style'));
                      updated_elms.push(elms[j]);
                    }
                    else if (!elms[j].getAttribute('fr-original-style')) {
                      updated_elms.push(elms[j]);
                    }

                    if (!elms_info[elms[j]]) {
                      elms_info[elms[j]] = {};
                    }

                    // Compute specification.
                    var spec = head_style * 1000 + specifity(rules[idx].selectorText);

                    // Get CSS text of the rule.
                    var css_text = rules[idx].style.cssText.split(';');

                    // Get each rule.
                    for (var k = 0; k < css_text.length; k++) {
                      // Rule.
                      var rule = css_text[k].trim().split(':')[0];
                      if (!elms_info[elms[j]][rule]) {
                        elms_info[elms[j]][rule] = 0;

                        if ((elms[j].getAttribute('fr-original-style') || '').indexOf(rule + ':') >= 0) {
                          elms_info[elms[j]][rule] = 10000;
                        }
                      }

                      // Current spec is higher than the existing one.
                      if (spec >= elms_info[elms[j]][rule]) {
                        elms_info[elms[j]][rule] = spec;

                        if (css_text[k].trim().length) {
                          elms[j].style[rule.trim()] = css_text[k].trim().split(':')[1].trim();
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }

        // Save original class.
        for (i = 0; i < updated_elms.length; i++) {
          if (updated_elms[i].getAttribute('class')) {
            updated_elms[i].setAttribute('fr-original-class', updated_elms[i].getAttribute('class'));
            updated_elms[i].removeAttribute('class');
          }
        }
      }

      // If editor is not empty.
      if (!editor.core.isEmpty()) {
        if (typeof keep_markers == 'undefined') keep_markers = false;

        if (!editor.opts.fullPage) {
          html = editor.$el.html();
        }
        else {
          html = getDoctype(editor.iframe_document);
          html += '<html' + editor.node.attributes(editor.$html.get(0)) + '>' + editor.$html.html() + '</html>';
        }
      }
      else if (editor.opts.fullPage) {
        html = getDoctype(editor.iframe_document);
        html += '<html' + editor.node.attributes(editor.$html.get(0)) + '>' + editor.$html.find('head').get(0).outerHTML + '<body></body></html>';
      }

      // Remove unwanted attributes.
      if (!editor.opts.useClasses && !keep_classes) {
        for (i = 0; i < updated_elms.length; i++) {
          if (updated_elms[i].getAttribute('fr-original-class')) {
            updated_elms[i].setAttribute('class', updated_elms[i].getAttribute('fr-original-class'));
            updated_elms[i].removeAttribute('fr-original-class');
          }

          if (updated_elms[i].getAttribute('fr-original-style')) {
            updated_elms[i].setAttribute('style', updated_elms[i].getAttribute('fr-original-style'));
            updated_elms[i].removeAttribute('fr-original-style');
          }
          else {
            updated_elms[i].removeAttribute('style');
          }
        }
      }

      // Clean helpers.
      if (editor.opts.fullPage) {
        html = html.replace(/<style data-fr-style="true">(?:[\w\W]*?)<\/style>/g, '');
        html = html.replace(/<link(?:[\w\W]*?)data-fr-style="true"(?:[\w\W]*?)>/g, '');
        html = html.replace(/<style(?:[\w\W]*?)class="firebugResetStyles"(?:[\w\W]*?)>(?:[\w\W]*?)<\/style>/g, '');
        html = html.replace(/<body((?:[\w\W]*?)) spellcheck="true"((?:[\w\W]*?))>((?:[\w\W]*?))<\/body>/g, '<body$1$2>$3</body>');
        html = html.replace(/<body((?:[\w\W]*?)) contenteditable="(true|false)"((?:[\w\W]*?))>((?:[\w\W]*?))<\/body>/g, '<body$1$3>$4</body>');

        html = html.replace(/<body((?:[\w\W]*?)) dir="([\w]*)"((?:[\w\W]*?))>((?:[\w\W]*?))<\/body>/g, '<body$1$3>$4</body>');
        html = html.replace(/<body((?:[\w\W]*?))class="([\w\W]*?)(fr-rtl|fr-ltr)([\w\W]*?)"((?:[\w\W]*?))>((?:[\w\W]*?))<\/body>/g, '<body$1class="$2$4"$5>$6</body>');
        html = html.replace(/<body((?:[\w\W]*?)) class=""((?:[\w\W]*?))>((?:[\w\W]*?))<\/body>/g, '<body$1$2>$3</body>');
      }

      // Ampersand fix.
      if (editor.opts.htmlSimpleAmpersand) {
        html = html.replace(/\&amp;/gi, '&');
      }

      editor.events.trigger('html.afterGet');

      // Remove markers.
      if (!keep_markers) {
        html = html.replace(/<span[^>]*? class\s*=\s*["']?fr-marker["']?[^>]+>\u200b<\/span>/gi, '');
      }

      html = editor.clean.invisibleSpaces(html);

      var new_html = editor.events.chainTrigger('html.get', html);
      if (typeof new_html == 'string') {
        html = new_html;
      }

      // Deal with pre.
      html = html.replace(/<pre(?:[\w\W]*?)>(?:[\w\W]*?)<\/pre>/g, function (str) {
        return str.replace(/<br>/g, '\n');
      });

      return html;
    }

    /**
     * Get selected HTML.
     */
    function getSelected () {
      var wrapSelection = function (container, node) {
        while (node && (node.nodeType == Node.TEXT_NODE || !editor.node.isBlock(node))) {
          if (node && node.nodeType != Node.TEXT_NODE) {
            $(container).wrapInner(editor.node.openTagString(node) + editor.node.closeTagString(node));
          }

          node = node.parentNode;
        }

        if (node && container.innerHTML == node.innerHTML) {
          container.innerHTML = node.outerHTML;
        }
      }

      var selectionParent = function () {
        var parent = null;
        var sel;

        if (editor.win.getSelection) {
          sel = editor.win.getSelection();
          if (sel && sel.rangeCount) {
            parent = sel.getRangeAt(0).commonAncestorContainer;
            if (parent.nodeType != Node.ELEMENT_NODE) {
              parent = parent.parentNode;
            }
          }
        } else if ((sel = editor.doc.selection) && sel.type != 'Control') {
          parent = sel.createRange().parentElement();
        }

        if (parent != null && ($.inArray(editor.$el.get(0), $(parent).parents()) >= 0 || parent == editor.$el.get(0))) {
          return parent;
        }
        else {
          return null;
        }
      }

      var html = '';
      if (typeof editor.win.getSelection != 'undefined') {

        // Multiple ranges hack.
        if (editor.browser.mozilla) {
          editor.selection.save();
          if (editor.$el.find('.fr-marker[data-type="false"]').length > 1) {
            editor.$el.find('.fr-marker[data-type="false"][data-id="0"]').remove();
            editor.$el.find('.fr-marker[data-type="false"]:last').attr('data-id', '0');
            editor.$el.find('.fr-marker').not('[data-id="0"]').remove();
          }
          editor.selection.restore();
        }

        var ranges = editor.selection.ranges();
        for (var i = 0; i < ranges.length; i++) {
          var container = document.createElement('div');
          container.appendChild(ranges[i].cloneContents());
          wrapSelection(container, selectionParent());

          // Fix for https://github.com/froala/wysiwyg-editor/issues/1010.
          if ($(container).find('.fr-element').length > 0) {
            container = editor.$el.get(0);
          }

          html += container.innerHTML;
        }
      }

      else if (typeof editor.doc.selection != 'undefined') {
        if (editor.doc.selection.type == 'Text') {
          html = editor.doc.selection.createRange().htmlText;
        }
      }
      return html;
    }

    function _hasBlockTags (html) {
      var $tmp = $('<div>').html(html);
      return $tmp.find(blockTagsQuery()).length > 0;
    }

    function _setCursorAtEnd (html) {
      var tmp = editor.doc.createElement('div');
      tmp.innerHTML = html;

      editor.selection.setAtEnd(tmp);

      return tmp.innerHTML;
    }

    function escapeEntities (str) {
      return str.replace(/</gi, '&lt;')
                .replace(/>/gi, '&gt;')
                .replace(/"/gi, '&quot;')
                .replace(/'/gi, '&apos;')
    }

    /**
     * Insert HTML.
     */
    function insert (dirty_html, clean, do_split) {
      // There is no selection.
      if (!editor.selection.isCollapsed()) {
        editor.selection.remove();
      }

      var clean_html;
      if (!clean) {
        clean_html = editor.clean.html(dirty_html);
      }
      else {
        clean_html = dirty_html;
      }

      clean_html = clean_html.replace(/\r|\n/g, ' ');

      if (dirty_html.indexOf('class="fr-marker"') < 0) {
        clean_html = _setCursorAtEnd(clean_html);
      }

      if (editor.core.isEmpty()) {
        editor.$el.html(clean_html);
      }
      else {
        // Insert a marker.
        var marker = editor.markers.insert();

        if (!marker) {
          editor.$el.append(clean_html);
        }
        else {
          // Check if HTML contains block tags and if so then break the current HTML.
          var deep_parent;
          if ((_hasBlockTags(clean_html) || do_split) && (deep_parent = editor.node.deepestParent(marker))) {
            var marker = editor.markers.split();
            if (!marker) return false;
            $(marker).replaceWith(clean_html);
          }
          else {
            $(marker).replaceWith(clean_html);
          }
        }
      }

      _normalize();

      editor.events.trigger('html.inserted');
    }

    /**
     * Clean those tags that have an invisible space inside.
     */
    function cleanWhiteTags (ignore_selection) {
      var current_el = null;
      if (typeof ignore_selection == 'undefined') {
        current_el = editor.selection.element();
      }

      var possible_elements;
      var removed;
      do {
        removed = false;
        possible_elements = editor.$el.get(0).querySelectorAll('*:not(.fr-marker)');
        for (var i = 0; i < possible_elements.length; i++) {
          var el = possible_elements[i];
          if (current_el == el) continue;

          var text = el.textContent;
          if (el.children.length === 0 && text.length === 1 && text.charCodeAt(0) == 8203) {
            $(el).remove();
            removed = true;
          }
        }
      } while (removed);
    }

    /**
     * Initialization.
     */
    function _init () {
      var cleanTags = function () {
        cleanWhiteTags();

        if (editor.placeholder) editor.placeholder.refresh();
      }

      editor.events.on('mouseup', cleanTags);
      editor.events.on('keydown', cleanTags);
      editor.events.on('contentChanged', checkIfEmpty);
    }

    return {
      defaultTag: defaultTag,
      emptyBlocks: emptyBlocks,
      emptyBlockTagsQuery: emptyBlockTagsQuery,
      blockTagsQuery: blockTagsQuery,
      fillEmptyBlocks: fillEmptyBlocks,
      cleanEmptyTags: cleanEmptyTags,
      cleanWhiteTags: cleanWhiteTags,
      doNormalize: doNormalize,
      cleanBlankSpaces: cleanBlankSpaces,
      blocks: blocks,
      getDoctype: getDoctype,
      set: set,
      get: get,
      getSelected: getSelected,
      insert: insert,
      wrap: _wrap,
      unwrap: unwrap,
      escapeEntities: escapeEntities,
      checkIfEmpty: checkIfEmpty,
      extractNode: extractNode,
      extractNodeAttrs: extractNodeAttrs,
      extractDoctype: extractDoctype,
      _init: _init
    }
  }


  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    height: null,
    heightMax: null,
    heightMin: null,
    width: null
  });

  $.FE.MODULES.size = function (editor) {
    function syncIframe () {
      if (editor.opts.height) {
        editor.$el.css('minHeight', editor.opts.height - editor.helpers.getPX(editor.$el.css('padding-top')) - editor.helpers.getPX(editor.$el.css('padding-bottom')));
      }

      editor.$iframe.height(editor.$el.outerHeight(true));
    }

    function refresh () {
      if (editor.opts.heightMin) {
        editor.$el.css('minHeight', editor.opts.heightMin);
      }
      else {
        editor.$el.css('minHeight', '');
      }

      if (editor.opts.heightMax) {
        editor.$wp.css('maxHeight', editor.opts.heightMax);
        editor.$wp.css('overflow', 'auto');
      }
      else {
        editor.$wp.css('maxHeight', '');
        editor.$wp.css('overflow', '');
      }

      // Set height.
      if (editor.opts.height) {
        editor.$wp.height(editor.opts.height);
        editor.$el.css('minHeight', editor.opts.height - editor.helpers.getPX(editor.$el.css('padding-top')) - editor.helpers.getPX(editor.$el.css('padding-bottom')));
        editor.$wp.css('overflow', 'auto');
      }
      else {
        editor.$wp.css('height', '');
        if (!editor.opts.heightMin) editor.$el.css('minHeight', '');
        if (!editor.opts.heightMax) editor.$wp.css('overflow', '');
      }

      if (editor.opts.width) editor.$box.width(editor.opts.width);
    }

    function _init () {
      if (!editor.$wp) return false;

      refresh();

      // Sync iframe height.
      if (editor.opts.iframe) {
        editor.events.on('keyup', syncIframe);
        editor.events.on('commands.after', syncIframe);
        editor.events.on('html.set', syncIframe);
        editor.events.on('init', syncIframe);
        editor.events.on('initialized', syncIframe);
      }
    }

    return {
      _init: _init,
      syncIframe: syncIframe,
      refresh: refresh
    }
  };


  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    language: null
  });

  $.FE.LANGUAGE = {};

  $.FE.MODULES.language = function (editor) {
    var lang;

    /**
     * Translate.
     */
    function translate (str) {
      if (lang && lang.translation[str]) {
        return lang.translation[str];
      }
      else {
        return str;
      }
    }

    /* Initialize */
    function _init () {
      // Load lang.
      if ($.FE.LANGUAGE) {
        lang = $.FE.LANGUAGE[editor.opts.language];
      }

      // Set direction.
      if (lang && lang.direction) {
        editor.opts.direction = lang.direction;
      }
    }

    return {
      _init: _init,
      translate: translate
    }
  };



  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    placeholderText: 'Type something'
  });

  $.FE.MODULES.placeholder = function (editor) {
    /* Show placeholder. */
    function show () {
      if (!editor.$placeholder) _add();

      // Determine the placeholder position based on the first element inside editor.
      var margin_top = 0;
      var contents = editor.node.contents(editor.$el.get(0));
      if (contents.length && contents[0].nodeType == Node.ELEMENT_NODE) {
        if (!editor.opts.toolbarInline) margin_top = editor.helpers.getPX($(contents[0]).css('margin-top'));
        editor.$placeholder.css('font-size', $(contents[0]).css('font-size'));
        editor.$placeholder.css('line-height', $(contents[0]).css('line-height'));
      }
      else {
        editor.$placeholder.css('font-size', editor.$el.css('font-size'));
        editor.$placeholder.css('line-height', editor.$el.css('line-height'));
      }

      editor.$wp.addClass('show-placeholder');
      editor.$placeholder
        .css('margin-top', Math.max(editor.helpers.getPX(editor.$el.css('margin-top')), margin_top))
        .text(editor.language.translate(editor.opts.placeholderText || editor.$oel.attr('placeholder') || ''));
    }

    /* Hide placeholder. */
    function hide () {
      editor.$wp.removeClass('show-placeholder');
    }

    /* Check if placeholder is visible */
    function isVisible () {
      return !editor.$wp ? true : editor.$wp.hasClass('show-placeholder');
    }

    /* Refresh placeholder. */
    function refresh () {
      if (!editor.$wp) return false;

      if (editor.core.isEmpty()) {
        show();
      }
      else {
        hide();
      }
    }

    function _add () {
      editor.$placeholder = $('<span class="fr-placeholder"></span>');
      editor.$wp.append(editor.$placeholder);
    }

    /* Initialize. */
    function _init () {
      if (!editor.$wp) return false;

      editor.events.on('init input keydown keyup contentChanged', refresh);
    }

    return {
      _init: _init,
      show: show,
      hide: hide,
      refresh: refresh,
      isVisible: isVisible
    }
  };


  $.FE.MODULES.edit = function (editor) {
    /**
     * Disable editing design.
     */
    function disableDesign () {
      if (editor.browser.mozilla) {
        try {
          editor.doc.execCommand('enableObjectResizing', false, 'false');
          editor.doc.execCommand('enableInlineTableEditing', false, 'false');
        }
        catch (ex) {

        }
      }

      if (editor.browser.msie) {
        try {
          editor.doc.body.addEventListener('mscontrolselect', function (e) {
            e.preventDefault();
            return false;
          });
        }
        catch (ex) {

        }
      }
    }

    var disabled = false;

    /**
     * Add contneteditable attribute.
     */
    function on () {
      if (editor.$wp) {
        editor.$el.attr('contenteditable', true);
        editor.$el.removeClass('fr-disabled');
        if (editor.$tb) editor.$tb.removeClass('fr-disabled');
        disableDesign();
      }
      else if (editor.$el.is('a')) {
        editor.$el.attr('contenteditable', true);
      }

      disabled = false;
    }

    /**
     * Remove contenteditable attribute.
     */
    function off () {
      if (editor.$wp) {
        editor.$el.attr('contenteditable', false);
        editor.$el.addClass('fr-disabled');
        if (editor.$tb) editor.$tb.addClass('fr-disabled');
      }
      else if (editor.$el.is('a')) {
        editor.$el.attr('contenteditable', false);
      }

      disabled = true;
    }

    function isDisabled () {
      return disabled;
    }

    return {
      on: on,
      off: off,
      disableDesign: disableDesign,
      isDisabled: isDisabled
    }
  };



  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    editorClass: null,
    typingTimer: 500,
    iframe: false,
    requestWithCORS: true,
    requestHeaders: {},
    useClasses: true,
    spellcheck: true,
    iframeStyle: 'html{margin: 0px;}body{padding:10px;background:transparent;color:#000000;position:relative;z-index: 2;-webkit-user-select:auto;margin:0px;overflow:hidden;min-height:20px;}body:after{content:"";display:block;clear:both;}',
    iframeStyleFiles: [],
    direction: 'auto',
    zIndex: 1,
    disableRightClick: false,
    scrollableContainer: 'body',
    keepFormatOnDelete: false,
    theme: null
  })

  $.FE.MODULES.core = function(editor) {
    function injectStyle(style) {
      if (editor.opts.iframe) {
        editor.$head.find('style[data-fr-style], link[data-fr-style]').remove();
        editor.$head.append('<style data-fr-style="true">' + style + '</style>');

        for (var i = 0; i < editor.opts.iframeStyleFiles.length; i++) {
          editor.$head.append('<link data-fr-style="true" rel="stylesheet" href="' + editor.opts.iframeStyleFiles[i] + '">');
        }
      }
    }

    function _initElementStyle() {
      if (!editor.opts.iframe) {
        editor.$el.addClass('fr-element fr-view');
      }
    }

    /**
     * Init the editor style.
     */

    function _initStyle() {
      editor.$box.addClass('fr-box' + (editor.opts.editorClass ? ' ' + editor.opts.editorClass : ''));
      editor.$wp.addClass('fr-wrapper');

      _initElementStyle();

      if (editor.opts.iframe) {
        editor.$iframe.addClass('fr-iframe');
        editor.$html.find('body').addClass('fr-view');

        for (var i = 0; i < editor.o_doc.styleSheets.length; i++) {
          var rules;
          try {
            rules = editor.o_doc.styleSheets[i].cssRules;
          }
          catch (ex) {

          }

          if (rules) {
            for (var idx = 0, len = rules.length; idx < len; idx++) {
              if (rules[idx].selectorText && (rules[idx].selectorText.indexOf('.fr-view') === 0 || rules[idx].selectorText.indexOf('.fr-element') === 0)) {
                if (rules[idx].style.cssText.length > 0) {
                  if (rules[idx].selectorText.indexOf('.fr-view') === 0) {
                    editor.opts.iframeStyle += rules[idx].selectorText.replace(/\.fr-view/g, 'body') + '{' + rules[idx].style.cssText + '}';
                  }
                  else {
                    editor.opts.iframeStyle += rules[idx].selectorText.replace(/\.fr-element/g, 'body') + '{' + rules[idx].style.cssText + '}';
                  }
                }
              }
            }
          }
        }
      }

      if (editor.opts.direction != 'auto') {
        editor.$box.removeClass('fr-ltr fr-rtl').addClass('fr-' + editor.opts.direction);
      }
      editor.$el.attr('dir', editor.opts.direction);
      editor.$wp.attr('dir', editor.opts.direction);

      if (editor.opts.zIndex > 1) {
        editor.$box.css('z-index', editor.opts.zIndex);
      }

      if (editor.opts.theme) {
        editor.$box.addClass(editor.opts.theme + '-theme');
      }
    }

    /**
     * Determine if the editor is empty.
     */

    function isEmpty() {
      return editor.node.isEmpty(editor.$el.get(0));
    }

    /**
     * Check if the browser allows drag and init it.
     */

    function _initDrag() {
      // Drag and drop support.
      editor.drag_support = {
        filereader: typeof FileReader != 'undefined',
        formdata: !! editor.win.FormData,
        progress: 'upload' in new XMLHttpRequest()
      };
    }

    /**
     * Return an XHR object.
     */

    function getXHR(url, method) {
      var xhr = new XMLHttpRequest();

      // Make it async.
      xhr.open(method, url, true);

      // Set with credentials.
      if (editor.opts.requestWithCORS) {
        xhr.withCredentials = true;
      }

      // Set headers.
      for (var header in editor.opts.requestHeaders) {
        if (editor.opts.requestHeaders.hasOwnProperty(header)) {
          xhr.setRequestHeader(header, editor.opts.requestHeaders[header]);
        }
      }

      return xhr;
    }

    function _destroy (html) {
      if (editor.$oel.get(0).tagName == 'TEXTAREA') {
        editor.$oel.val(html);
      }

      if (editor.$wp) {
        if (editor.$oel.get(0).tagName == 'TEXTAREA') {
          editor.$el.html('');
          editor.$wp.html('');
          editor.$box.replaceWith(editor.$oel);
          editor.$oel.show();
        } else {
          editor.$wp.replaceWith(html);
          editor.$el.html('');
          editor.$box.removeClass('fr-view fr-ltr fr-box ' + (editor.opts.editorClass || ''));

          if (editor.opts.theme) {
            editor.$box.addClass(editor.opts.theme + '-theme');
          }
        }
      }

      this.$wp = null;
      this.$el = null;
      this.$box = null;
    }

    function hasFocus() {
      if (editor.browser.mozilla && editor.helpers.isMobile()) return editor.selection.inEditor();
      return editor.node.hasFocus(editor.$el.get(0)) || editor.$el.find('*:focus').length > 0;
    }

    function sameInstance ($obj) {
      if (!$obj) return false;

      var inst = $obj.data('instance');

      return (inst ? inst.id == editor.id : false);
    }

    /**
     * Tear up.
     */

    function _init () {
      $.FE.INSTANCES.push(editor);

      _initDrag();

      // Call initialization methods.
      if (editor.$wp) {
        _initStyle();
        editor.html.set(editor._original_html);

        // Set spellcheck.
        editor.$el.attr('spellcheck', editor.opts.spellcheck);

        // Disable autocomplete.
        if (editor.helpers.isMobile()) {
          editor.$el.attr('autocomplete', editor.opts.spellcheck ? 'on' : 'off');
          editor.$el.attr('autocorrect', editor.opts.spellcheck ? 'on' : 'off');
          editor.$el.attr('autocapitalize', editor.opts.spellcheck ? 'on' : 'off');
        }

        // Disable right click.
        if (editor.opts.disableRightClick) {
          editor.events.$on(editor.$el, 'contextmenu', function(e) {
            if (e.button == 2) {
              return false;
            }
          });
        }

        try {
          editor.doc.execCommand('styleWithCSS', false, false);
        } catch (ex) {

        }
      }

      // Do not allow drop inside the editor.
      editor.events.on('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
      });

      if (editor.$oel.get(0).tagName == 'TEXTAREA') {
        // Sync on contentChanged.
        editor.events.on('contentChanged', function() {
          editor.$oel.val(editor.html.get());
        });

        // Set HTML on form submit.
        editor.events.on('form.submit', function() {
          editor.$oel.val(editor.html.get());
        });

        editor.events.on('form.reset', function () {
          editor.html.set(editor._original_html);
        })

        editor.$oel.val(editor.html.get());
      }

      // iOS focus fix.
      if (editor.helpers.isIOS()) {
        editor.events.$on(editor.$doc, 'selectionchange', function () {
          if (!editor.$doc.get(0).hasFocus()) {
            editor.$win.get(0).focus();
          }
        });
      }

      editor.events.trigger('init');
    }

    return {
      _init: _init,
      destroy: _destroy,
      isEmpty: isEmpty,
      getXHR: getXHR,
      injectStyle: injectStyle,
      hasFocus: hasFocus,
      sameInstance: sameInstance
    }
  }


  'use strict';

  $.FE.MODULES.format = function (editor) {
    /**
     * Create open tag string.
     */
    function _openTag (tag, attrs) {
      var str = '<' + tag;

      for (var key in attrs) {
        if (attrs.hasOwnProperty(key)) {
          str += ' ' + key + '="' + attrs[key] + '"';
        }
      }

      str += '>';

      return str;
    }

    /**
     * Create close tag string.
     */
    function _closeTag (tag) {
      return '</' + tag + '>';
    }

    /**
     * Create query for the current format.
     */
    function _query (tag, attrs) {
      var selector = tag;

      for (var key in attrs) {
        if (attrs.hasOwnProperty(key)) {
          if (key == 'id') tag += '#' + attrs[key];
          else if (key == 'class') tag += '.' + attrs[key];
          else tag += '[' + key + '="' + attrs[key] + '"]';
        }
      }

      return selector;
    }

    /**
     * Test matching element.
     */
    function _matches (el, selector) {
      if (!el || el.nodeType != Node.ELEMENT_NODE) return false;

      return (el.matches || el.matchesSelector || el.msMatchesSelector || el.mozMatchesSelector || el.webkitMatchesSelector || el.oMatchesSelector).call(el, selector);
    }

    /**
     * Apply format to the current node till we find a marker.
     */
    function _processNodeFormat (start_node, tag, attrs) {
      // No start node.
      if (!start_node) return;

      // If we are in a block process starting with the first child.
      if (editor.node.isBlock(start_node)) {
        _processNodeFormat(start_node.firstChild, tag, attrs);
        return false;
      }

      // Create new element.
      var $span = $(_openTag(tag, attrs)).insertBefore(start_node);

      // Start with the next sibling of the current node.
      var node = start_node;

      // Search while there is a next node.
      // Next node is not marker.
      // Next node does not contain marker.
      while (node && !$(node).is('.fr-marker') && $(node).find('.fr-marker').length == 0) {
        var tmp = node;
        node = node.nextSibling;
        $span.append(tmp);
      }

      // If there is no node left at the right look at parent siblings.
      if (!node) {
        var p_node = $span.get(0).parentNode;
        while (p_node && !p_node.nextSibling && !editor.node.isElement(p_node)) {
          p_node = p_node.parentNode;
        }

        if (p_node) {
          var sibling = p_node.nextSibling;
          if (sibling) {
            // Parent sibling is block then look next.
            if (!editor.node.isBlock(sibling)) {
              _processNodeFormat(sibling, tag, attrs);
            }
            else {
              _processNodeFormat(sibling.firstChild, tag, attrs);
            }
          }
        }
      }
      // Start processing child nodes if there is a marker.
      else if ($(node).find('.fr-marker').length) {
        _processNodeFormat(node.firstChild, tag, attrs);
      }

      if ($span.is(':empty')) {
        $span.remove();
      }
    }

    /**
     * Apply tag format.
     */
    function apply (tag, attrs) {
      if (typeof attrs == 'undefined') attrs = {};
      if (attrs.style) {
        delete attrs.style;
      }

      // Selection is collapsed.
      if (editor.selection.isCollapsed()) {
        editor.markers.insert();
        var $marker = editor.$el.find('.fr-marker');
        $marker.replaceWith(_openTag(tag, attrs) + $.FE.INVISIBLE_SPACE + $.FE.MARKERS + _closeTag(tag));
        editor.selection.restore();
      }

      // Selection is not collapsed.
      else {
        editor.selection.save();

        // Check if selection can be deleted.
        var start_marker = editor.$el.find('.fr-marker[data-type="true"]').get(0).nextSibling;
        _processNodeFormat(start_marker, tag, attrs);

        // Clean inner spans.
        var inner_spans;
        do {
          inner_spans = editor.$el.find(_query(tag, attrs) + ' > ' + _query(tag, attrs));
          inner_spans.each (function () {
            $(this).replaceWith(this.innerHTML);
          });
        } while (inner_spans.length);

        editor.$el.get(0).normalize();

        // Have markers inside the new tag.
        var markers = editor.$el.get(0).querySelectorAll('.fr-marker');
        for (var i = 0; i < markers.length; i++) {
          var $mk = $(markers[i]);
          if ($mk.data('type') == true) {
            if (_matches($mk.next().get(0), _query(tag, attrs))) {
              $mk.next().prepend($mk);
            }
          }
          else {
            if (_matches($mk.prev().get(0), _query(tag, attrs))) {
              $mk.prev().append($mk);
            }
          }
        }

        editor.selection.restore();
      }
    }

    /**
     * Split at current node the parents with tag.
     */
    function _split ($node, tag, attrs) {
      // Check if current node has parents which match our tag.
      if ($node.parents(tag).length || typeof tag == 'undefined') {
        var close_str = '';
        var open_str = '';
        var $p_node = $node.parent();

        // Do not split when parent is block.
        if ($p_node.is(editor.$el) || editor.node.isBlock($p_node.get(0))) return false;

        // Check undefined so that we
        while ((typeof tag == 'undefined' && !editor.node.isBlock($p_node.parent().get(0))) || (typeof tag != 'undefined' && !_matches($p_node.get(0), _query(tag, attrs)))) {
          close_str = close_str + editor.node.closeTagString($p_node.get(0));
          open_str = editor.node.openTagString($p_node.get(0)) + open_str;
          $p_node = $p_node.parent();
        }

        // Node STR.
        var node_str = $node.get(0).outerHTML;

        // Replace node with marker.
        $node.replaceWith('<span id="mark"></span>');

        // Rebuild the HTML for the node.
        var p_html = $p_node.html().replace(/<span id="mark"><\/span>/, close_str + editor.node.closeTagString($p_node.get(0)) + open_str + node_str + close_str + editor.node.openTagString($p_node.get(0)) + open_str);
        $p_node.replaceWith(editor.node.openTagString($p_node.get(0)) + p_html + editor.node.closeTagString($p_node.get(0)));

        return true;
      }

      return false;
    }

    /**
     * Process node remove.
     */
    function _processNodeRemove ($node, should_remove, tag, attrs) {
      // Get contents.
      var contents = editor.node.contents($node.get(0));

      // Loop contents.
      for (var i = 0; i < contents.length; i++) {
        var node = contents[i];

        // We found a marker => change should_remove flag.
        if ($(node).hasClass('fr-marker')) {
          should_remove = (should_remove + 1) % 2;
        }
        // We should remove.
        else if (should_remove) {
          // Check if we have a marker inside it.
          if ($(node).find('.fr-marker').length > 0) {
            should_remove = _processNodeRemove($(node), should_remove, tag, attrs);
          }
          // Remove everything starting with the most inner nodes.
          else {
            $($(node).find(tag || '*').get().reverse()).each(function() {
              if (!editor.node.isBlock(this) && !editor.node.isVoid(this)) {
                $(this).replaceWith(this.innerHTML);
              }
            });

            // Check inner nodes.
            if ((typeof tag == 'undefined' && node.nodeType == Node.ELEMENT_NODE && !editor.node.isVoid(node) && !editor.node.isBlock(node)) || _matches(node, _query(tag, attrs))) {
              $(node).replaceWith(node.innerHTML);
            }
          }
        }
        else {
          // There is a marker.
          if ($(node).find('.fr-marker').length > 0) {
            should_remove = _processNodeRemove($(node), should_remove, tag, attrs);
          }
        }
      }

      return should_remove;
    }

    /**
     * Remove tag.
     */
    function remove (tag, attrs) {
      if (typeof attrs == 'undefined') attrs = {};
      if (attrs.style) {
        delete attrs.style;
      }

      var collapsed = editor.selection.isCollapsed();
      editor.selection.save();

      // Split at start and end marker.
      var reassess = true;
      while (reassess) {
        reassess = false;
        var markers = editor.$el.find('.fr-marker');
        for (var i = 0; i < markers.length; i++) {
          if (_split($(markers[i]), tag, attrs)) {
            reassess = true;
            break;
          }
        }
      }

      // Remove format between markers.
      _processNodeRemove(editor.$el, 0, tag, attrs);

      // Selection is collapsed => add invisible spaces.
      if (collapsed) {
        editor.$el.find('.fr-marker').before($.FE.INVISIBLE_SPACE).after($.FE.INVISIBLE_SPACE);
      }

      editor.html.cleanEmptyTags();

      editor.$el.get(0).normalize();
      editor.selection.restore();
    }

    /**
     * Toggle format.
     */
    function toggle (tag, attrs) {
      if (is(tag, attrs)) {
        remove(tag, attrs);
      }
      else {
        apply(tag, attrs);
      }
    }

    /**
     * Clean format.
     */
    function _cleanFormat (elem, prop) {
      var $elem = $(elem);
      $elem.css(prop, '');

      if ($elem.attr('style') === '') {
        $elem.replaceWith($elem.html());
      }
    }

    /**
     * Filter spans with specific property.
     */
    function _filterSpans (elem, prop) {
      return $(elem).attr('style').indexOf(prop + ':') === 0 || $(elem).attr('style').indexOf(';' + prop + ':') >= 0 || $(elem).attr('style').indexOf('; ' + prop + ':') >= 0;
    };

    /**
     * Apply inline style.
     */
    function applyStyle (prop, val) {
      // Selection is collapsed.
      if (editor.selection.isCollapsed()) {
        editor.markers.insert();
        var $marker = editor.$el.find('.fr-marker');
        var $parent = $marker.parent();

        // https://github.com/froala/wysiwyg-editor/issues/1084
        if (editor.node.openTagString($parent.get(0)) == '<span style="' + prop + ': ' + $parent.css(prop) + ';">' && editor.node.isEmpty($parent.get(0))) {
          $parent.replaceWith('<span style="' + prop + ': ' + val + ';">' + $.FE.INVISIBLE_SPACE + $.FE.MARKERS + '</span>');
        }
        else if (editor.node.isEmpty($parent.get(0)) && $parent.is('span')) {
          $marker.replaceWith($.FE.MARKERS);
          $parent.css(prop, val);
        }
        else {
          $marker.replaceWith('<span style="' + prop + ': ' + val + ';">' + $.FE.INVISIBLE_SPACE + $.FE.MARKERS + '</span>');
        }

        editor.selection.restore();
      }
      else {
        editor.selection.save();

        // Check if selection can be deleted.
        var start_marker = editor.$el.find('.fr-marker[data-type="true"]').get(0).nextSibling;

        var attrs = { 'class': 'fr-unprocessed' };
        if (val) attrs.style = prop + ': ' + val + ';'
        _processNodeFormat(start_marker, 'span', attrs);

        editor.$el.find('.fr-marker + .fr-unprocessed').each(function () {
          $(this).prepend($(this).prev());
        });

        editor.$el.find('.fr-unprocessed + .fr-marker').each(function () {
          $(this).prev().append(this);
        });

        while (editor.$el.find('span.fr-unprocessed').length > 0) {
          var $span = editor.$el.find('span.fr-unprocessed:first').removeClass('fr-unprocessed');

          // Look at parent node to see if we can merge with it.
          $span.parent().get(0).normalize();
          if ($span.parent().is('span') && $span.parent().get(0).childNodes.length == 1) {
            $span.parent().css(prop, val);
            var $child = $span;
            $span = $span.parent();
            $child.replaceWith($child.html());
          }

          // Replace in reverse order to take care of the inner spans first.
          var inner_spans = $span.find('span');
          for (var i = inner_spans.length - 1; i >= 0; i--) {
            _cleanFormat(inner_spans[i], prop);
          }

          // Look at parents with the same property.
          var $outer_span = $span.parentsUntil(editor.$el, 'span[style]').filter(function() {
            return _filterSpans(this, prop);
          });

          if ($outer_span.length) {
            var c_str = '';
            var o_str = '';
            var ic_str = '';
            var io_str = '';
            var c_node = $span.get(0);

            do {
              c_node = c_node.parentNode;
              c_str = c_str + editor.node.closeTagString(c_node);
              o_str = editor.node.openTagString(c_node) + o_str;

              // Inner close and open.
              if ($outer_span.get(0) != c_node) {
                ic_str = ic_str + editor.node.closeTagString(c_node);
                io_str = editor.node.openTagString(c_node) + io_str;
              }
            } while ($outer_span.get(0) != c_node);

            // Build breaking string.
            var str = c_str + editor.node.openTagString($outer_span.clone().css(prop, val || '').get(0)) + io_str + $span.html() + ic_str + '</span>' + o_str;
            $span.replaceWith('<span id="fr-break"></span>');
            var html = $outer_span.get(0).outerHTML;

            // Replace the outer node.
            $($outer_span.get(0)).replaceWith(html.replace(/<span id="fr-break"><\/span>/g, str));
          }
        }

        editor.html.cleanEmptyTags();

        editor.$el.find('span[style=""]').removeAttr('style');
        editor.$el.find('span[class=""]').removeAttr('class');

        editor.$el.find('span').each(function () {
          if (!this.attributes || this.attributes.length == 0) {
            $(this).replaceWith(this.innerHTML);
          }
        });

        // Join current spans together if they are one next to each other.
        var just_spans = editor.$el.find('span[style] + span[style]');
        for (i = 0; i < just_spans.length; i++) {
          var $x = $(just_spans[i]);
          var $p = $(just_spans[i]).prev();

          if (editor.node.openTagString($x.get(0)) == editor.node.openTagString($p.get(0))) {
            $x.prepend($p.html());
            $p.remove();
          }
        }

        editor.$el.get(0).normalize();
        editor.selection.restore();
      }
    }

    /**
     * Remove inline style.
     */
    function removeStyle (prop) {
      applyStyle(prop, null);
    }

    /**
     * Get the current state.
     */
    function is (tag, attrs) {
      if (typeof attrs == 'undefined') attrs = {};
      if (attrs.style) {
        delete attrs.style;
      }

      var range = editor.selection.ranges(0);
      var el = range.startContainer;
      if (el.nodeType == Node.ELEMENT_NODE) {
        // Search for node deeper.
        if (el.childNodes.length > 0 && el.childNodes[range.startOffset]) {
          el = el.childNodes[range.startOffset];
        }
      }

      // Check first childs.
      var f_child = el;
      while (f_child && f_child.nodeType == Node.ELEMENT_NODE && !_matches(f_child, _query(tag, attrs))) {
        f_child = f_child.firstChild;
      }

      if (f_child && f_child.nodeType == Node.ELEMENT_NODE && _matches(f_child, _query(tag, attrs))) return true;

      // Check parents.
      var p_node = el;
      if (p_node && p_node.nodeType != Node.ELEMENT_NODE) p_node = p_node.parentNode;
      while (p_node && p_node.nodeType == Node.ELEMENT_NODE && p_node != editor.$el.get(0) && !_matches(p_node, _query(tag, attrs))) {
        p_node = p_node.parentNode;
      }

      if (p_node && p_node.nodeType == Node.ELEMENT_NODE && p_node != editor.$el.get(0) && _matches(p_node, _query(tag, attrs))) return true;

      return false;
    }

    return {
      is: is,
      toggle: toggle,
      apply: apply,
      remove: remove,
      applyStyle: applyStyle,
      removeStyle: removeStyle
    }
  }



  $.FE.COMMANDS = {
    bold: {
      title: 'Bold',
      refresh: function ($btn) {
        $btn.toggleClass('fr-active', this.format.is('strong'));
      }
    },
    italic: {
      title: 'Italic',
      refresh: function ($btn) {
        $btn.toggleClass('fr-active', this.format.is('em'));
      }
    },
    underline: {
      title: 'Underline',
      refresh: function ($btn) {
        $btn.toggleClass('fr-active', this.format.is('u'));
      }
    },
    strikeThrough: {
      title: 'Strikethrough',
      refresh: function ($btn) {
        $btn.toggleClass('fr-active', this.format.is('s'));
      }
    },
    subscript: {
      title: 'Subscript',
      refresh: function ($btn) {
        $btn.toggleClass('fr-active', this.format.is('sub'));
      }
    },
    superscript: {
      title: 'Superscript',
      refresh: function ($btn) {
        $btn.toggleClass('fr-active', this.format.is('sup'));
      }
    },
    outdent: {
      title: 'Decrease Indent'
    },
    indent: {
      title: 'Increase Indent'
    },
    undo: {
      title: 'Undo',
      undo: false,
      forcedRefresh: true,
      disabled: true
    },
    redo: {
      title: 'Redo',
      undo: false,
      forcedRefresh: true,
      disabled: true
    },
    insertHR: {
      title: 'Insert Horizontal Line'
    },
    clearFormatting: {
      title: 'Clear Formatting'
    },
    selectAll: {
      title: 'Select All',
      undo: false
    }
  };

  $.FE.RegisterCommand = function (name, info) {
    $.FE.COMMANDS[name] = info;
  }

  $.FE.MODULES.commands = function (editor) {
    var mapping = {
      bold: function () {
        _execCommand('bold', 'strong');
      },

      subscript: function () {
        _execCommand('subscript', 'sub');
      },

      superscript: function () {
        _execCommand('superscript', 'sup');
      },

      italic: function () {
        _execCommand('italic', 'em');
      },

      strikeThrough: function () {
        _execCommand('strikeThrough', 's');
      },

      underline: function () {
        _execCommand('underline', 'u');
      },

      undo: function () {
        editor.undo.run();
      },

      redo: function () {
        editor.undo.redo();
      },

      indent: function () {
        _processIndent(1);
      },

      outdent: function () {
        _processIndent(-1);
      },

      show: function () {
        if (editor.opts.toolbarInline) {
          editor.toolbar.showInline(null, true);
        }
      },

      insertHR: function () {
        editor.selection.remove();

        var empty = '';
        if (editor.core.isEmpty()) {
          empty = '<br>';
          if (editor.html.defaultTag()) {
            empty = '<' + editor.html.defaultTag() + '>' + empty + '</' + editor.html.defaultTag() + '>';
          }
        }

        editor.html.insert('<hr id="fr-just">' + empty);

        var $hr = editor.$el.find('hr#fr-just');
        $hr.removeAttr('id');

        editor.selection.setAfter($hr.get(0)) || editor.selection.setBefore($hr.get(0));

        editor.selection.restore();
      },

      clearFormatting: function () {
        editor.format.remove();
      },

      selectAll: function () {
        editor.doc.execCommand('selectAll', false, false);
      }
    }

    /**
     * Exec command.
     */
    function exec (cmd, params) {
      // Trigger before command to see if to execute the default callback.
      if (editor.events.trigger('commands.before', $.merge([cmd], params || [])) !== false) {
        // Get the callback.
        var callback = ($.FE.COMMANDS[cmd] && $.FE.COMMANDS[cmd].callback) || mapping[cmd];

        var focus = true;
        if ($.FE.COMMANDS[cmd] && typeof $.FE.COMMANDS[cmd].focus != 'undefined') {
          focus = $.FE.COMMANDS[cmd].focus;
        }

        // Make sure we have focus.
        if (!editor.core.hasFocus() && focus && !editor.popups.areVisible()) {
          // Focus in the editor at any position.
          editor.events.focus(true);
        }

        // Callback.
        // Save undo step.
        if ($.FE.COMMANDS[cmd] && $.FE.COMMANDS[cmd].undo !== false) {
          editor.undo.saveStep();
        }

        if (callback) {
          callback.apply(editor, $.merge([cmd], params || []));
        }

        // Trigger after command.
        editor.events.trigger('commands.after', $.merge([cmd], params || []));

        // Save undo step again.
        if ($.FE.COMMANDS[cmd] && $.FE.COMMANDS[cmd].undo !== false) editor.undo.saveStep();
      }
    }

    /**
     * Exex default.
     */
    function _execCommand(cmd, tag) {
      editor.format.toggle(tag);
    }

    function _processIndent(indent) {
      editor.selection.save();
      editor.html.wrap(true, true, true, true);
      editor.selection.restore();

      var blocks = editor.selection.blocks();

      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].tagName != 'LI' && blocks[i].parentNode.tagName != 'LI') {
          var $block = $(blocks[i]);

          var prop = (editor.opts.direction == 'rtl' || $block.css('direction') == 'rtl') ? 'margin-right' : 'margin-left';

          var margin_left = editor.helpers.getPX($block.css(prop));

          $block.css(prop, Math.max(margin_left + indent * 20, 0) || '');
          $block.removeClass('fr-temp-div');
        }
      }

      editor.selection.save();
      editor.html.unwrap();
      editor.selection.restore();
    }

    function callExec (k) {
      return function () {
        exec(k);
      }
    }

    var resp = {};
    for (var k in mapping) {
      if (mapping.hasOwnProperty(k)) {
        resp[k] = callExec(k);
      }
    }

    return $.extend(resp, {
      exec: exec
    });
  };


  $.FE.MODULES.cursorLists = function (editor) {
    /**
     * Find the first li parent.
     */
    function _firstParentLI (node) {
      var p_node = node;
      while (p_node.tagName != 'LI') {
        p_node = p_node.parentNode;
      }

      return p_node;
    }

    /**
     * Find the first list parent.
     */
    function _firstParentList (node) {
      var p_node = node;
      while (!editor.node.isList(p_node)) {
        p_node = p_node.parentNode;
      }

      return p_node;
    }


    /**
     * Do enter at the beginning of a list item.
     */
    function _startEnter (marker) {
      var li = _firstParentLI(marker);

      // Get previous and next siblings.
      var next_li = li.nextSibling;
      var prev_li = li.previousSibling;
      var default_tag = editor.html.defaultTag();

      var ul;

      // We are in a list item at the middle of the list or an list item that is not empty.
      if (editor.node.isEmpty(li, true) && next_li) {
        var o_str = '';
        var c_str = ''
        var p_node = marker.parentNode;

        // Create open / close string.
        while (!editor.node.isList(p_node) && p_node.parentNode && p_node.parentNode.tagName !== 'LI') {
          o_str = editor.node.openTagString(p_node) + o_str;
          c_str = c_str + editor.node.closeTagString(p_node);
          p_node = p_node.parentNode;
        }

        o_str = editor.node.openTagString(p_node) + o_str;
        c_str = c_str + editor.node.closeTagString(p_node);

        var str = ''
        if (p_node.parentNode && p_node.parentNode.tagName == 'LI') {
          str = c_str + '<li>' + $.FE.MARKERS + '<br>' + o_str;
        }
        else {
          if (default_tag) {
            str = c_str + '<' + default_tag + '>' + $.FE.MARKERS + '<br>' + '</' + default_tag + '>' + o_str;
          }
          else {
            str = c_str + $.FE.MARKERS + '<br>' + o_str;
          }
        }

        $(li).html('<span id="fr-break"></span>');

        while (['UL', 'OL'].indexOf(p_node.tagName) < 0 || (p_node.parentNode && p_node.parentNode.tagName === 'LI')) {
          p_node = p_node.parentNode;
        }
        var html = editor.node.openTagString(p_node) + $(p_node).html() + editor.node.closeTagString(p_node);
        html = html.replace(/<span id="fr-break"><\/span>/g, str);

        $(p_node).replaceWith(html);

        editor.$el.find('li:empty').remove();
      }
      else if ((prev_li && next_li) || !editor.node.isEmpty(li, true)) {
        $(li).before('<li><br></li>');
        $(marker).remove();
      }

      // There is no previous list item so transform the current list item to an empty line.
      else if (!prev_li) {
        ul = _firstParentList(li);

        // We are in a nested list so add a new li before it.
        if (ul.parentNode && ul.parentNode.tagName == 'LI') {
          $(ul.parentNode).before('<li>' + $.FE.MARKERS + '<br></li>');
        }

        // We are in a normal list. Add a new line before.
        else {
          if (default_tag) {
            $(ul).before('<' + default_tag + '>' + $.FE.MARKERS + '<br></' + default_tag + '>');
          }
          else {
            $(ul).before($.FE.MARKERS  + '<br>');
          }
        }

        // Remove the current li.
        $(li).remove();
      }

      // There is no next_li item so transform the current list item to an empty line.
      else {
        ul = _firstParentList(li);

        // We are in a nested lists so add a new li after it.
        if (ul.parentNode && ul.parentNode.tagName == 'LI') {
          $(ul.parentNode).after('<li>' + $.FE.MARKERS + '<br></li>');
        }

        // We are in a normal list. Add a new line after.
        else {
          if (default_tag) {
            $(ul).after('<' + default_tag + '>' + $.FE.MARKERS + '<br></' + default_tag + '>');
          }
          else {
            $(ul).after($.FE.MARKERS  + '<br>');
          }
        }

        // Remove the current li.
        $(li).remove();
      }
    }

    /**
     * Enter at the middle of a list.
     */
    function _middleEnter (marker) {
      var li = _firstParentLI(marker);

      // Build the closing / opening list item string.
      var str = '';
      var node = marker;
      var o_str = '';
      var c_str = '';
      while (node != li) {
        node = node.parentNode;

        var cls = (node.tagName == 'A' && editor.cursor.isAtEnd(marker, node)) ? 'fr-to-remove' : '';

        o_str = editor.node.openTagString($(node).clone().addClass(cls).get(0)) + o_str;
        c_str = editor.node.closeTagString(node) + c_str;
      }

      // Add markers.
      str = c_str + str + o_str + $.FE.MARKERS;

      // Build HTML.
      $(marker).replaceWith('<span id="fr-break"></span>');
      var html = editor.node.openTagString(li) + $(li).html() + editor.node.closeTagString(li);
      html = html.replace(/<span id="fr-break"><\/span>/g, str);

      // Replace the current list item.
      $(li).replaceWith(html);
    }

    /**
     * Enter at the end of a list item.
     */
    function _endEnter (marker) {
      var li = _firstParentLI(marker);

      var end_str = $.FE.MARKERS;
      var start_str = '';
      var node = marker;

      var add_invisible = false;
      while (node != li) {
        node = node.parentNode;

        var cls = (node.tagName == 'A' && editor.cursor.isAtEnd(marker, node)) ? 'fr-to-remove' : '';

        if (!add_invisible && node != li && !editor.node.isBlock(node)) {
          add_invisible = true;
          start_str = start_str + $.FE.INVISIBLE_SPACE;
        }

        start_str = editor.node.openTagString($(node).clone().addClass(cls).get(0)) + start_str;
        end_str = end_str + editor.node.closeTagString(node);
      }

      var str = start_str + end_str;

      $(marker).remove();
      $(li).after(str);
    }

    /**
     * Do backspace on a list item. This method is called only when wer are at the beginning of a LI.
     */
    function _backspace (marker) {
      var li = _firstParentLI(marker);

      // Get previous sibling.
      var prev_li = li.previousSibling;

      // There is a previous li.
      if (prev_li) {
        // Get the li inside a nested list or inner block tags.
        prev_li = $(prev_li).find(editor.html.blockTagsQuery()).get(-1) || prev_li;

        // Add markers.
        $(marker).replaceWith($.FE.MARKERS);

        // Remove possible BR at the end of the previous list.
        var contents = editor.node.contents(prev_li);
        if (contents.length && contents[contents.length - 1].tagName == 'BR') {
          $(contents[contents.length - 1]).remove();
        }

        // Remove any nodes that might be wrapped.
        $(li).find(editor.html.blockTagsQuery()).not('ol, ul, table').each (function () {
          if (this.parentNode == li) {
            $(this).replaceWith($(this).html() + (editor.node.isEmpty(this) ? '' : '<br>'));
          }
        })

        // Append the current list item content to the previous one.
        var node = editor.node.contents(li)[0];
        var tmp;
        while (node && !editor.node.isList(node)) {
          tmp = node.nextSibling;
          $(prev_li).append(node);
          node = tmp;
        }

        prev_li = li.previousSibling;
        while (node) {
          tmp = node.nextSibling;
          $(prev_li).append(node);
          node = tmp;
        }

        // Remove the current LI.
        $(li).remove();
      }

      // No previous li.
      else {
        var ul = _firstParentList(li);

        // Add markers.
        $(marker).replaceWith($.FE.MARKERS);

        // Nested lists.
        if (ul.parentNode && ul.parentNode.tagName == 'LI') {
          var prev_node = ul.previousSibling;

          // Previous node is block.
          if (editor.node.isBlock(prev_node)) {
            // Remove any nodes that might be wrapped.
            $(li).find(editor.html.blockTagsQuery()).not('ol, ul, table').each (function () {
              if (this.parentNode == li) {
                $(this).replaceWith($(this).html() + (editor.node.isEmpty(this) ? '' : '<br>'));
              }
            });

            $(prev_node).append($(li).html());
          }

          // Text right in li.
          else {
            $(ul).before($(li).html());
          }
        }

        // Normal lists. Add an empty li instead.
        else {
          var default_tag = editor.html.defaultTag();
          if (default_tag && $(li).find(editor.html.blockTagsQuery()).length === 0) {
            $(ul).before('<' + default_tag + '>' + $(li).html() + '</' + default_tag + '>');
          }
          else {
            $(ul).before($(li).html());
          }
        }

        // Remove the current li.
        $(li).remove();

        // Remove the ul if it is empty.
        if ($(ul).find('li').length === 0) $(ul).remove();
      }
    }

    /**
     * Delete at the end of list item.
     */
    function _del (marker) {
      var li = _firstParentLI(marker);
      var next_li = li.nextSibling;
      var contents;

      // There is a next li.
      if (next_li) {
        // Remove possible BR at the beginning of the next LI.
        contents = editor.node.contents(next_li);
        if (contents.length && contents[0].tagName == 'BR') {
          $(contents[0]).remove();
        }

        // Unwrap content from the next node.
        $(next_li).find(editor.html.blockTagsQuery()).not('ol, ul, table').each (function () {
          if (this.parentNode == next_li) {
            $(this).replaceWith($(this).html() + (editor.node.isEmpty(this) ? '' : '<br>'));
          }
        });

        // Append the next LI to the current LI.
        var last_node = marker;
        var node = editor.node.contents(next_li)[0];
        var tmp;
        while (node && !editor.node.isList(node)) {
          tmp = node.nextSibling;
          $(last_node).after(node);
          last_node = node;
          node = tmp;
        }

        // Append nested lists.
        while (node) {
          tmp = node.nextSibling;
          $(li).append(node);
          node = tmp;
        }

        // Replace marker with markers.
        $(marker).replaceWith($.FE.MARKERS);

        // Remove next li.
        $(next_li).remove();
      }

      // No next li.
      else {
        // Search the next sibling in parents.
        var next_node = li;
        while (!next_node.nextSibling && next_node != editor.$el.get(0)) {
          next_node = next_node.parentNode;
        }

        // We're right at the end.
        if (next_node == editor.$el.get(0)) return false;

        // Get the next sibling.
        next_node = next_node.nextSibling;

        // Next sibling is a block tag.
        if (editor.node.isBlock(next_node)) {
          // Check if we can do delete in it.
          if ($.FE.NO_DELETE_TAGS.indexOf(next_node.tagName) < 0) {

            // Add markers.
            $(marker).replaceWith($.FE.MARKERS);

            // Remove any possible BR at the end of the LI.
            contents = editor.node.contents(li);
            if (contents.length && contents[contents.length - 1].tagName == 'BR') {
              $(contents[contents.length - 1]).remove();
            }

            // Append next node.
            $(li).append($(next_node).html());

            // Remove the next node.
            $(next_node).remove();
          }
        }

        // Append everything till the next block tag or BR.
        else {
          // Remove any possible BR at the end of the LI.
          contents = editor.node.contents(li);
          if (contents.length && contents[contents.length - 1].tagName == 'BR') {
            $(contents[contents.length - 1]).remove();
          }

          // var next_node = next_li;
          $(marker).replaceWith($.FE.MARKERS);
          while (next_node && !editor.node.isBlock(next_node) && next_node.tagName != 'BR') {
            $(li).append($(next_node));
            next_node = next_node.nextSibling;
          }
        }
      }
    }

    return {
      _startEnter: _startEnter,
      _middleEnter: _middleEnter,
      _endEnter: _endEnter,
      _backspace: _backspace,
      _del: _del
    }
  };


  // Do not merge with the previous one.
  $.FE.NO_DELETE_TAGS = ['TH', 'TD', 'TABLE', 'FORM'];

  // Do simple enter.
  $.FE.SIMPLE_ENTER_TAGS = ['TH', 'TD', 'LI', 'DL', 'DT', 'FORM'];

  $.FE.MODULES.cursor = function (editor) {
    /**
     * Check if node is at the end of a block tag.
     */
    function _atEnd (node) {
      if (!node) return false;
      if (editor.node.isBlock(node)) return true;
      if (node.nextSibling) return false;

      return _atEnd(node.parentNode);
    }

    /**
     * Check if node is at the start of a block tag.
     */
    function _atStart (node) {
      if (!node) return false;
      if (editor.node.isBlock(node)) return true;
      if (node.previousSibling) return false;

      return _atStart(node.parentNode);
    }

    /**
     * Check if node is a the start of the container.
     */
    function _isAtStart (node, container) {
      if (!node) return false;
      if (node == editor.$wp.get(0)) return false;
      if (node.previousSibling) return false;
      if (node.parentNode == container) return true;

      return _isAtStart(node.parentNode, container);
    }

    /**
     * Check if node is a the start of the container.
     */
    function _isAtEnd (node, container) {
      if (!node) return false;
      if (node == editor.$wp.get(0)) return false;
      if (node.nextSibling) return false;
      if (node.parentNode == container) return true;

      return _isAtEnd(node.parentNode, container);
    }

    /**
     * Check if the node is inside a LI.
     */
    function _inLi (node) {
      return $(node).parentsUntil(editor.$el, 'LI').length > 0 && $(node).parentsUntil('LI', 'TABLE').length === 0;
    }

    /**
     * Do backspace at the start of a block tag.
     */
    function _startBackspace (marker) {
      var quote = $(marker).parentsUntil(editor.$el, 'BLOCKQUOTE').length > 0;
      var deep_parent = editor.node.deepestParent(marker, [], !quote);

      if (deep_parent && deep_parent.tagName == 'BLOCKQUOTE') {
        var m_parent = editor.node.deepestParent(marker, [$(marker).parentsUntil(editor.$el, 'BLOCKQUOTE').get(0)]);
        if (m_parent && m_parent.previousSibling) {
          deep_parent = m_parent;
        }
      }

      // Deepest parent is not the main element.
      if (deep_parent !== null) {
        var prev_node = deep_parent.previousSibling;
        var contents;

        // We are inside a block tag.
        if (editor.node.isBlock(deep_parent) && editor.node.isEditable(deep_parent)) {
          // There is a previous node.
          if (prev_node && $.FE.NO_DELETE_TAGS.indexOf(prev_node.tagName) < 0) {
            if (editor.node.isDeletable(prev_node)) {
              $(prev_node).remove();
              $(marker).replaceWith($.FE.MARKERS);
            }
            else {
              // Previous node is a block tag.
              if (editor.node.isEditable(prev_node)) {
                if (editor.node.isBlock(prev_node)) {
                  if (editor.node.isEmpty(prev_node) && !editor.node.isList(prev_node)) {
                    $(prev_node).remove();
                  }
                  else {
                    if (editor.node.isList(prev_node)) {
                      prev_node = $(prev_node).find('li:last').get(0);
                    }

                    // Remove last BR.
                    contents = editor.node.contents(prev_node);
                    if (contents.length && contents[contents.length - 1].tagName == 'BR') {
                      $(contents[contents.length - 1]).remove();
                    }

                    // Prev node is blockquote but the current one isn't.
                    if (prev_node.tagName == 'BLOCKQUOTE' && deep_parent.tagName != 'BLOCKQUOTE') {
                      contents = editor.node.contents(prev_node);
                      while (contents.length && editor.node.isBlock(contents[contents.length - 1])) {
                        prev_node = contents[contents.length - 1];
                        contents = editor.node.contents(prev_node);
                      }
                    }
                    // Prev node is not blockquote, but the current one is.
                    else if (prev_node.tagName != 'BLOCKQUOTE' && deep_parent.tagName == 'BLOCKQUOTE') {
                      contents = editor.node.contents(deep_parent);
                      while (contents.length && editor.node.isBlock(contents[0])) {
                        deep_parent = contents[0];
                        contents = editor.node.contents(deep_parent);
                      }
                    }

                    $(marker).replaceWith($.FE.MARKERS);
                    $(prev_node).append(editor.node.isEmpty(deep_parent) ? $.FE.MARKERS : deep_parent.innerHTML);
                    $(deep_parent).remove();
                  }
                }
                else {
                  $(marker).replaceWith($.FE.MARKERS);

                  if (deep_parent.tagName == 'BLOCKQUOTE' && prev_node.nodeType == Node.ELEMENT_NODE) {
                    $(prev_node).remove();
                  }
                  else {
                    $(prev_node).after(editor.node.isEmpty(deep_parent) ? '' : $(deep_parent).html());
                    $(deep_parent).remove();
                    if (prev_node.tagName == 'BR') $(prev_node).remove();
                  }
                }
              }
            }
          }
        }

        // No block tag.
        /* jshint ignore:start */
        /* jscs:disable */
        else {
          // This should never happen.
        }
        /* jshint ignore:end */
        /* jscs:enable */
      }
    }

    /**
     * Do backspace at the middle of a block tag.
     */
    function _middleBackspace (marker) {
      var prev_node = marker;

      // Get the parent node that has a prev sibling.
      while (!prev_node.previousSibling) {
        prev_node = prev_node.parentNode;

        if (editor.node.isElement(prev_node)) return false;
      }
      prev_node = prev_node.previousSibling;

      // Not block tag.
      var contents;
      if (!editor.node.isBlock(prev_node) && editor.node.isEditable(prev_node)) {
        contents = editor.node.contents(prev_node);

        // Previous node is text.
        while (prev_node.nodeType != Node.TEXT_NODE && !editor.node.isDeletable(prev_node) && contents.length && editor.node.isEditable(prev_node)) {
          prev_node = contents[contents.length - 1];
          contents = editor.node.contents(prev_node);
        }

        if (prev_node.nodeType == Node.TEXT_NODE) {
          if (editor.helpers.isIOS()) return true;

          var txt = prev_node.textContent;
          var len = txt.length - 1;

          // Tab UNDO.
          if (editor.opts.tabSpaces && txt.length >= editor.opts.tabSpaces) {
            var tab_str = txt.substr(txt.length - editor.opts.tabSpaces, txt.length - 1);
            if (tab_str.replace(/ /g, '').replace(new RegExp($.FE.UNICODE_NBSP, 'g'), '').length == 0) {
              len = txt.length - editor.opts.tabSpaces;
            }
          }

          prev_node.textContent = txt.substring(0, len);
          if (prev_node.textContent.length && prev_node.textContent.charCodeAt(prev_node.textContent.length - 1) == 55357) {
            prev_node.textContent = prev_node.textContent.substr(0, prev_node.textContent.length - 1);
          }

          // Remove node if empty.
          if (prev_node.textContent.length == 0) {
            if (prev_node.parentNode.childNodes.length == 2 && prev_node.parentNode == marker.parentNode && !editor.node.isBlock(prev_node.parentNode) && !editor.node.isElement(prev_node.parentNode)) {
              $(prev_node.parentNode).after($.FE.MARKERS);
              $(prev_node.parentNode).remove();
            }
            else {
              $(prev_node).after($.FE.MARKERS);
              prev_node.parentNode.removeChild(prev_node);
            }
          }
          else {
            $(prev_node).after($.FE.MARKERS);
          }
        }
        else if (editor.node.isDeletable(prev_node)) {
          $(prev_node).after($.FE.MARKERS);
          $(prev_node).remove();
        }
        else {
          if (editor.events.trigger('node.remove', [$(prev_node)]) !== false) {
            $(prev_node).after($.FE.MARKERS);
            $(prev_node).remove();
          }
        }
      }

      // Block tag but we are allowed to delete it.
      else if ($.FE.NO_DELETE_TAGS.indexOf(prev_node.tagName) < 0 && editor.node.isEditable(prev_node)) {
        if (editor.node.isEmpty(prev_node) && !editor.node.isList(prev_node)) {
          $(prev_node).remove();
          $(marker).replaceWith($.FE.MARKERS);
        }
        else {
          // List correction.
          if (editor.node.isList(prev_node)) prev_node = $(prev_node).find('li:last').get(0);

          contents = editor.node.contents(prev_node);
          if (contents && contents[contents.length - 1].tagName == 'BR') {
            $(contents[contents.length - 1]).remove();
          }

          contents = editor.node.contents(prev_node);
          while (contents && editor.node.isBlock(contents[contents.length - 1])) {
            prev_node = contents[contents.length - 1];
            contents = editor.node.contents(prev_node);
          }

          $(prev_node).append($.FE.MARKERS);

          var next_node = marker;
          while (!next_node.previousSibling) {
            next_node = next_node.parentNode;
          }

          while (next_node && next_node.tagName !== 'BR' && !editor.node.isBlock(next_node)) {
            var copy_node = next_node;
            next_node = next_node.nextSibling;
            $(prev_node).append(copy_node);
          }

          // Remove BR.
          if (next_node && next_node.tagName == 'BR') $(next_node).remove();

          $(marker).remove();
        }
      }

      else {
        if (marker.nextSibling && marker.nextSibling.tagName == 'BR') {
          $(marker.nextSibling).remove();
        }
      }
    }

    /**
     * Do backspace.
     */
    function backspace () {
      var do_default = false;

      // Add a marker in HTML.
      var marker = editor.markers.insert();

      if (!marker) return true;

      editor.$el.get(0).normalize();

      // We should remove invisible space first of all.
      var prev_node = marker.previousSibling;
      if (prev_node) {
        var txt = prev_node.textContent;
        if (txt && txt.length && txt.charCodeAt(txt.length - 1) == 8203) {
          if (txt.length == 1) {
            $(prev_node).remove()
          }
          else {
            prev_node.textContent = prev_node.textContent.substr(0, txt.length - 1);
            if (prev_node.textContent.length && prev_node.textContent.charCodeAt(prev_node.textContent.length - 1) == 55357) {
              prev_node.textContent = prev_node.textContent.substr(0, prev_node.textContent.length - 1);
            }
          }
        }
      }

      // Delete at end.
      if (_atEnd(marker)) {
        do_default = _middleBackspace(marker);
      }

      // Delete at start.
      else if (_atStart(marker)) {
        if (_inLi(marker) && _isAtStart(marker, $(marker).parents('li:first').get(0))) {
          editor.cursorLists._backspace(marker);
        }
        else {
          _startBackspace(marker);
        }
      }

      // Delete at middle.
      else {
        do_default = _middleBackspace(marker);
      }

      $(marker).remove();

      editor.$el.find('blockquote:empty').remove();
      editor.html.fillEmptyBlocks();
      editor.html.cleanEmptyTags();
      editor.clean.quotes();
      editor.clean.lists();
      editor.spaces.normalize();
      editor.selection.restore();

      return do_default;
    }

    /**
     * Delete at the end of a block tag.
     */
    function _endDel (marker) {
      var quote = $(marker).parentsUntil(editor.$el, 'BLOCKQUOTE').length > 0;
      var deep_parent = editor.node.deepestParent(marker, [], !quote);

      if (deep_parent && deep_parent.tagName == 'BLOCKQUOTE') {
        var m_parent = editor.node.deepestParent(marker, [$(marker).parentsUntil(editor.$el, 'BLOCKQUOTE').get(0)]);
        if (m_parent && m_parent.nextSibling) {
          deep_parent = m_parent;
        }
      }

      // Deepest parent is not the main element.
      if (deep_parent !== null) {
        var next_node = deep_parent.nextSibling;
        var contents;

        // We are inside a block tag.
        if (editor.node.isBlock(deep_parent) && (editor.node.isEditable(deep_parent) || editor.node.isDeletable(deep_parent))) {
          // There is a next node.
          if (next_node && $.FE.NO_DELETE_TAGS.indexOf(next_node.tagName) < 0) {
            if (editor.node.isDeletable(next_node)) {
              $(next_node).remove();
              $(marker).replaceWith($.FE.MARKERS);
            }
            else {
              // Next node is a block tag.
              if (editor.node.isBlock(next_node) && editor.node.isEditable(next_node)) {
                // Next node is a list.
                if (editor.node.isList(next_node)) {
                  // Current block tag is empty.
                  if (editor.node.isEmpty(deep_parent, true)) {
                    $(deep_parent).remove();

                    $(next_node).find('li:first').prepend($.FE.MARKERS);
                  }
                  else {
                    var $li = $(next_node).find('li:first');

                    if (deep_parent.tagName == 'BLOCKQUOTE') {
                      contents = editor.node.contents(deep_parent);
                      if (contents.length && editor.node.isBlock(contents[contents.length - 1])) {
                        deep_parent = contents[contents.length - 1];
                      }
                    }

                    // There are no nested lists.
                    if ($li.find('ul, ol').length === 0) {
                      $(marker).replaceWith($.FE.MARKERS);

                      // Remove any nodes that might be wrapped.
                      $li.find(editor.html.blockTagsQuery()).not('ol, ul, table').each (function () {
                        if (this.parentNode == $li.get(0)) {
                          $(this).replaceWith($(this).html() + (editor.node.isEmpty(this) ? '' : '<br>'));
                        }
                      });

                      $(deep_parent).append(editor.node.contents($li.get(0)));
                      $li.remove();

                      if ($(next_node).find('li').length === 0) $(next_node).remove();
                    }
                  }
                }
                else {
                  // Remove last BR.
                  contents = editor.node.contents(next_node);
                  if (contents.length && contents[0].tagName == 'BR') {
                    $(contents[0]).remove();
                  }

                  if (next_node.tagName != 'BLOCKQUOTE' && deep_parent.tagName == 'BLOCKQUOTE') {
                    contents = editor.node.contents(deep_parent);
                    while (contents.length && editor.node.isBlock(contents[contents.length - 1])) {
                      deep_parent = contents[contents.length - 1];
                      contents = editor.node.contents(deep_parent);
                    }
                  }
                  else if (next_node.tagName == 'BLOCKQUOTE' && deep_parent.tagName != 'BLOCKQUOTE') {
                    contents = editor.node.contents(next_node);
                    while (contents.length && editor.node.isBlock(contents[0])) {
                      next_node = contents[0];
                      contents = editor.node.contents(next_node);
                    }
                  }

                  $(marker).replaceWith($.FE.MARKERS);
                  $(deep_parent).append(next_node.innerHTML);
                  $(next_node).remove();
                }
              }
              else {
                $(marker).replaceWith($.FE.MARKERS);

                // var next_node = next_node.nextSibling;
                while (next_node && next_node.tagName !== 'BR' && !editor.node.isBlock(next_node) && editor.node.isEditable(next_node)) {
                  var copy_node = next_node;
                  next_node = next_node.nextSibling;
                  $(deep_parent).append(copy_node);
                }

                if (next_node && next_node.tagName == 'BR' && editor.node.isEditable(next_node)) {
                  $(next_node).remove();
                }
              }
            }
          }
        }

        // No block tag.
        /* jshint ignore:start */
        /* jscs:disable */
        else {
          // This should never happen.
        }
        /* jshint ignore:end */
        /* jscs:enable */
      }
    }

    /**
     * Delete at the middle of a block tag.
     */
    function _middleDel (marker) {
      var next_node = marker;

      // Get the parent node that has a next sibling.
      while (!next_node.nextSibling) {
        next_node = next_node.parentNode;

        if (editor.node.isElement(next_node)) return false;
      }
      next_node = next_node.nextSibling;

      // Handle the case when the next node is a BR.
      if (next_node.tagName == 'BR' && editor.node.isEditable(next_node)) {
        // There is a next sibling.
        if (next_node.nextSibling) {
          if (editor.node.isBlock(next_node.nextSibling) && editor.node.isEditable(next_node.nextSibling)) {
            if ($.FE.NO_DELETE_TAGS.indexOf(next_node.nextSibling.tagName) < 0) {
              next_node = next_node.nextSibling;
              $(next_node.previousSibling).remove();
            }
            else {
              $(next_node).remove();
              return;
            }
          }
        }

        // No next sibling. We should check if BR is at the end.
        else if (_atEnd(next_node)) {
          if (_inLi(marker)) {
            editor.cursorLists._del(marker);
          }
          else {
            var deep_parent = editor.node.deepestParent(next_node);
            if (deep_parent) {
              $(next_node).remove();
              _endDel(marker);
            }
          }

          return;
        }
      }

      // Not block tag.
      var contents;
      if (!editor.node.isBlock(next_node) && editor.node.isEditable(next_node)) {
        contents = editor.node.contents(next_node);

        // Next node is text.
        while (next_node.nodeType != Node.TEXT_NODE && contents.length && !editor.node.isDeletable(next_node) && editor.node.isEditable(next_node)) {
          next_node = contents[0];
          contents = editor.node.contents(next_node);
        }

        if (next_node.nodeType == Node.TEXT_NODE) {
          $(next_node).before($.FE.MARKERS);

          if (next_node.textContent.length && next_node.textContent.charCodeAt(0) == 55357) {
            next_node.textContent = next_node.textContent.substring(2, next_node.textContent.length);
          }
          else {
            next_node.textContent = next_node.textContent.substring(1, next_node.textContent.length);
          }
        }
        else if (editor.node.isDeletable(next_node)) {
          $(next_node).before($.FE.MARKERS);
          $(next_node).remove();
        }
        else {
          if (editor.events.trigger('node.remove', [$(next_node)]) !== false) {
            $(next_node).before($.FE.MARKERS);
            $(next_node).remove();
          }
        }

        $(marker).remove();
      }

      // Block tag.
      else if ($.FE.NO_DELETE_TAGS.indexOf(next_node.tagName) < 0 && (editor.node.isEditable(next_node) || editor.node.isDeletable(next_node))) {
        if (editor.node.isDeletable(next_node)) {
          $(marker).replaceWith($.FE.MARKERS);
          $(next_node).remove();
        }
        else {
          if (editor.node.isList(next_node)) {
            // There is a previous sibling.
            if (marker.previousSibling) {
              $(next_node).find('li:first').prepend(marker);
              editor.cursorLists._backspace(marker);
            }

            // No previous sibling.
            else {
              $(next_node).find('li:first').prepend($.FE.MARKERS);
              $(marker).remove();
            }
          }
          else {
            contents = editor.node.contents(next_node);
            if (contents && contents[0].tagName == 'BR') {
              $(contents[0]).remove();
            }

            // Deal with blockquote.
            if (contents && next_node.tagName == 'BLOCKQUOTE') {
              var node = contents[0];
              $(marker).before($.FE.MARKERS);
              while (node && node.tagName != 'BR') {
                var tmp = node;
                node = node.nextSibling;
                $(marker).before(tmp);
              }

              if (node && node.tagName == 'BR') {
                $(node).remove();
              }
            }
            else {
              $(marker)
                .after($(next_node).html())
                .after($.FE.MARKERS);

              $(next_node).remove();
            }
          }
        }
      }
    }

    /**
     * Delete.
     */
    function del () {
      var marker = editor.markers.insert();

      if (!marker) return false;

      editor.$el.get(0).normalize();

      // Delete at end.
      if (_atEnd(marker)) {
        if (_inLi(marker)) {
          if ($(marker).parents('li:first').find('ul, ol').length === 0) {
            editor.cursorLists._del(marker);
          }
          else {
            var $li = $(marker).parents('li:first').find('ul:first, ol:first').find('li:first');
            $li = $li.find(editor.html.blockTagsQuery()).get(-1) || $li;

            $li.prepend(marker);
            editor.cursorLists._backspace(marker);
          }
        }
        else {
          _endDel(marker);
        }
      }

      // Delete at start.
      else if (_atStart(marker)) {
        _middleDel(marker);
      }

      // Delete at middle.
      else {
        _middleDel(marker);
      }

      $(marker).remove();
      editor.$el.find('blockquote:empty').remove();
      editor.html.fillEmptyBlocks();
      editor.html.cleanEmptyTags();
      editor.clean.quotes();
      editor.clean.lists();
      editor.spaces.normalize();
      editor.selection.restore();
    }

    function _cleanNodesToRemove() {
      editor.$el.find('.fr-to-remove').each (function () {
        var contents = editor.node.contents(this);
        for (var i = 0; i < contents.length; i++) {
          if (contents[i].nodeType == Node.TEXT_NODE) {
            contents[i].textContent = contents[i].textContent.replace(/\u200B/g, '');
          }
        }

        $(this).replaceWith(this.innerHTML);
      })
    }

    /**
     * Enter at the end of a block tag.
     */
    function _endEnter (marker, shift, quote) {
      var deep_parent = editor.node.deepestParent(marker, [], !quote);
      var default_tag;

      if (deep_parent && deep_parent.tagName == 'BLOCKQUOTE') {
        if (_isAtEnd(marker, deep_parent)) {
          default_tag = editor.html.defaultTag();
          if (default_tag) {
            $(deep_parent).after('<' + default_tag + '>' + $.FE.MARKERS + '<br>' + '</' + default_tag + '>');
          }
          else {
            $(deep_parent).after($.FE.MARKERS + '<br>');
          }

          $(marker).remove();
          return false;
        }
        else {
          _middleEnter(marker, shift, quote);
          return false;
        }
      }

      // We are right in the main element.
      if (deep_parent == null) {
        $(marker).replaceWith('<br/>' + $.FE.MARKERS + '<br/>');
      }

      // There is a parent.
      else {
        // Block tag parent.
        var c_node = marker;
        var str = '';
        if (!editor.node.isBlock(deep_parent) || shift) {
          str = '<br/>';
        }

        var c_str = '';
        var o_str = '';

        default_tag = editor.html.defaultTag();
        var open_default_tag = '';
        var close_default_tag = '';
        if (default_tag && editor.node.isBlock(deep_parent)) {
          open_default_tag = '<' + default_tag + '>';
          close_default_tag = '</' + default_tag + '>';

          if (deep_parent.tagName == default_tag.toUpperCase()) {
            open_default_tag = editor.node.openTagString($(deep_parent).clone().removeAttr('id').get(0));
          }
        }

        do {
          c_node = c_node.parentNode;

          // Shift condition.
          if (!shift || c_node != deep_parent || (shift && !editor.node.isBlock(deep_parent))) {
            c_str = c_str + editor.node.closeTagString(c_node);

            // Open str when there is a block parent.
            if (c_node == deep_parent && editor.node.isBlock(deep_parent)) {
              o_str = open_default_tag + o_str;
            }
            else {
              var cls = (c_node.tagName == 'A' && _isAtEnd(marker, c_node)) ? 'fr-to-remove' : '';
              o_str = editor.node.openTagString($(c_node).clone().addClass(cls).get(0)) + o_str;
            }
          }
        } while (c_node != deep_parent);

        // Add BR if deep parent is block tag.
        str = c_str + str + o_str + ((marker.parentNode == deep_parent && editor.node.isBlock(deep_parent)) ? '' : $.FE.INVISIBLE_SPACE) + $.FE.MARKERS;

        if (editor.node.isBlock(deep_parent) && !$(deep_parent).find('*:last').is('br')) {
          $(deep_parent).append('<br/>');
        }

        $(marker).after('<span id="fr-break"></span>');
        $(marker).remove();

        // Add a BR after to make sure we display the last line.
        if ((!deep_parent.nextSibling || editor.node.isBlock(deep_parent.nextSibling)) && !editor.node.isBlock(deep_parent)) {
          $(deep_parent).after('<br>');
        }

        var html;
        // No shift.
        if (!shift && editor.node.isBlock(deep_parent)) {
          html = editor.node.openTagString(deep_parent) + $(deep_parent).html() + close_default_tag;
        }
        else {
          html = editor.node.openTagString(deep_parent) + $(deep_parent).html() + editor.node.closeTagString(deep_parent);
        }

        html = html.replace(/<span id="fr-break"><\/span>/g, str);

        $(deep_parent).replaceWith(html);
      }
    }

    /**
     * Start at the beginning of a block tag.
     */
    function _startEnter (marker, shift, quote) {
      var deep_parent = editor.node.deepestParent(marker, [], !quote);

      if (deep_parent && deep_parent.tagName == 'BLOCKQUOTE') {
        if (_isAtStart(marker, deep_parent)) {
          var default_tag = editor.html.defaultTag();
          if (default_tag) {
            $(deep_parent).before('<' + default_tag + '>' + $.FE.MARKERS + '<br>' + '</' + default_tag + '>');
          }
          else {
            $(deep_parent).before($.FE.MARKERS + '<br>');
          }

          $(marker).remove();
          return false;
        }
        else if (_isAtEnd(marker, deep_parent)) {
          _endEnter(marker, shift, true);
        }
        else {
          _middleEnter(marker, shift, true);
        }
      }

      // We are right in the main element.
      if (deep_parent == null) {
        $(marker).replaceWith('<br>' + $.FE.MARKERS);
      }
      else {
        if (editor.node.isBlock(deep_parent)) {
          if (shift) {
            $(marker).remove();
            $(deep_parent).prepend('<br>' + $.FE.MARKERS);
          }
          else if (editor.node.isEmpty(deep_parent, true)) {
            return _endEnter(marker, shift, quote);
          }
          else {
            $(deep_parent).before(editor.node.openTagString($(deep_parent).clone().removeAttr('id').get(0)) + '<br>' + editor.node.closeTagString(deep_parent));
          }
        }
        else {
          $(deep_parent).before('<br>');
        }

        $(marker).remove();
      }
    }

    /**
     * Enter at the middle of a block tag.
     */
    function _middleEnter (marker, shift, quote) {
      var deep_parent = editor.node.deepestParent(marker, [], !quote);

      // We are right in the main element.
      if (deep_parent == null) {
        // Default tag is not enter.
        if (editor.html.defaultTag() && marker.parentNode === editor.$el.get(0)) {
          $(marker).replaceWith('<' + editor.html.defaultTag() + '>' + $.FE.MARKERS + '<br></' + editor.html.defaultTag() + '>');
        }
        else {
          // Add a BR after to make sure we display the last line.
          if ((!marker.nextSibling || editor.node.isBlock(marker.nextSibling))) {
            $(marker).after('<br>');
          }

          $(marker).replaceWith('<br>' + $.FE.MARKERS);
        }
      }

      // There is a parent.
      else {
        // Block tag parent.
        var c_node = marker;
        var str = '';

        if (deep_parent.tagName == 'PRE') shift = true;
        if (!editor.node.isBlock(deep_parent) || shift) {
          str = '<br>';
        }

        var c_str = '';
        var o_str = '';

        do {
          var tmp = c_node;
          c_node = c_node.parentNode;

          // Move marker after node it if is empty and we are in quote.
          if (deep_parent.tagName == 'BLOCKQUOTE' && editor.node.isEmpty(tmp) && !$(tmp).hasClass('fr-marker')) {
            if ($(tmp).find(marker).length > 0) {
              $(tmp).after(marker);
            }
          }

          // If not at end or start of element in quote.
          if (!(deep_parent.tagName == 'BLOCKQUOTE' && (_isAtEnd(marker, c_node) || _isAtStart(marker, c_node)))) {
            // 1. No shift.
            // 2. c_node is not deep parent.
            // 3. Shift and deep parent is not block tag.
            if (!shift || c_node != deep_parent || (shift && !editor.node.isBlock(deep_parent))) {
              c_str = c_str + editor.node.closeTagString(c_node);

              var cls = (c_node.tagName == 'A' && _isAtEnd(marker, c_node)) ? 'fr-to-remove' : '';
              o_str = editor.node.openTagString($(c_node).clone().addClass(cls).removeAttr('id').get(0)) + o_str;
            }
          }
        } while (c_node != deep_parent);

        // We should add an invisible space if:
        // 1. parent node is not deep parent and block tag.
        // 2. marker has no next sibling.
        var add = (
                    (deep_parent == marker.parentNode && editor.node.isBlock(deep_parent)) ||
                    marker.nextSibling
                  );

        if (deep_parent.tagName == 'BLOCKQUOTE') {
          if (marker.previousSibling && editor.node.isBlock(marker.previousSibling) && marker.nextSibling && marker.nextSibling.tagName == 'BR') {
            $(marker.nextSibling).after(marker);

            if (marker.nextSibling && marker.nextSibling.tagName == 'BR') {
              $(marker.nextSibling).remove();
            }
          }

          var default_tag = editor.html.defaultTag();
          str = c_str + str + (default_tag ? '<' + default_tag + '>' : '') + $.FE.MARKERS + '<br>' + (default_tag ? '</' + default_tag + '>' : '') + o_str;
        }
        else {
          str = c_str + str + o_str + (add ? '' : $.FE.INVISIBLE_SPACE) + $.FE.MARKERS;
        }

        $(marker).replaceWith('<span id="fr-break"></span>');
        var html = editor.node.openTagString(deep_parent) + $(deep_parent).html() + editor.node.closeTagString(deep_parent);
        html = html.replace(/<span id="fr-break"><\/span>/g, str);

        $(deep_parent).replaceWith(html);
      }
    }

    /**
     * Do enter.
     */
    function enter (shift) {
      // Add a marker in HTML.
      var marker = editor.markers.insert();

      if (!marker) return true;

      editor.$el.get(0).normalize();

      var quote = false;
      if ($(marker).parentsUntil(editor.$el, 'BLOCKQUOTE').length > 0) {
        shift = false;
        quote = true;
      }

      if ($(marker).parentsUntil(editor.$el, 'TD, TH').length) quote = false;

      // At the end.
      if (_atEnd(marker)) {
        // Enter in list.
        if (_inLi(marker) && !shift && !quote) {
          editor.cursorLists._endEnter(marker);
        }
        else {
          _endEnter(marker, shift, quote);
        }
      }

      // At start.
      else if (_atStart(marker)) {
        // Enter in list.
        if (_inLi(marker) && !shift && !quote) {
          editor.cursorLists._startEnter(marker);
        }
        else {
          _startEnter(marker, shift, quote);
        }
      }

      // At middle.
      else {
        // Enter in list.
        if (_inLi(marker) && !shift && !quote) {
          editor.cursorLists._middleEnter(marker);
        }
        else {
          _middleEnter(marker, shift, quote);
        }
      }

      _cleanNodesToRemove();

      editor.html.fillEmptyBlocks();
      editor.html.cleanEmptyTags();
      editor.clean.lists();
      editor.spaces.normalize();
      editor.selection.restore();
    }

    return {
      enter: enter,
      backspace: backspace,
      del: del,
      isAtEnd: _isAtEnd
    }
  }

$.FE.MODULES.data=function(a){function b(a){return a}function c(a){if(!a)return a;for(var c="",f=b("charCodeAt"),g=b("fromCharCode"),h=l.indexOf(a[0]),i=1;i<a.length-2;i++){for(var j=d(++h),k=a[f](i),m="";/[0-9-]/.test(a[i+1]);)m+=a[++i];m=parseInt(m,10)||0,k=e(k,j,m),k^=h-1&31,c+=String[g](k)}return c}function d(a){for(var b=a.toString(),c=0,d=0;d<b.length;d++)c+=parseInt(b.charAt(d),10);return c>10?c%9+1:c}function e(a,b,c){for(var d=Math.abs(c);d-- >0;)a-=b;return 0>c&&(a+=123),a}function f(a){return a&&"none"==a.css("display")?(a.remove(),!0):!1}function g(){return f(j)||f(k)}function h(){return a.$box?(a.$box.append(n(b(n("kTDD4spmKD1klaMB1C7A5RA1G3RA10YA5qhrjuvnmE1D3FD2bcG-7noHE6B2JB4C3xXA8WF6F-10RG2C3G3B-21zZE3C3H3xCA16NC4DC1f1hOF1MB3B-21whzQH5UA2WB10kc1C2F4D3XC2YD4D1C4F3GF2eJ2lfcD-13HF1IE1TC11TC7WE4TA4d1A2YA6XA4d1A3yCG2qmB-13GF4A1B1KH1HD2fzfbeQC3TD9VE4wd1H2A20A2B-22ujB3nBG2A13jBC10D3C2HD5D1H1KB11uD-16uWF2D4A3F-7C9D-17c1E4D4B3d1D2CA6B2B-13qlwzJF2NC2C-13E-11ND1A3xqUA8UE6bsrrF-7C-22ia1D2CF2H1E2akCD2OE1HH1dlKA6PA5jcyfzB-22cXB4f1C3qvdiC4gjGG2H2gklC3D-16wJC1UG4dgaWE2D5G4g1I2H3B7vkqrxH1H2EC9C3E4gdgzKF1OA1A5PF5C4WWC3VA6XA4e1E3YA2YA5HE4oGH4F2H2IB10D3D2NC5G1B1qWA9PD6PG5fQA13A10XA4C4A3e1H2BA17kC-22cmOB1lmoA2fyhcptwWA3RA8A-13xB-11nf1I3f1B7GB3aD3pavFC10D5gLF2OG1LSB2D9E7fQC1F4F3wpSB5XD3NkklhhaE-11naKA9BnIA6D1F5bQA3A10c1QC6Kjkvitc2B6BE3AF3E2DA6A4JD2IC1jgA-64MB11D6C4==")))),j=a.$box.find("> div:last"),k=j.find("> a"),void("rtl"==a.opts.direction&&j.css("left","auto").css("right",0))):!1}function i(){var c=a.opts.key||[""];"string"==typeof c&&(c=[c]),a.ul=!0;for(var d=0;d<c.length;d++){var e=n(c[d])||"";if(!(e!==n(b(n("mcVRDoB1BGILD7YFe1BTXBA7B6==")))&&e.indexOf(m,e.length-m.length)<0&&[n("9qqG-7amjlwq=="),n("KA3B3C2A6D1D5H5H1A3==")].indexOf(m)<0)){a.ul=!1;break}}a.ul===!0&&h(),a.events.on("contentChanged",function(){a.ul===!0&&g()&&h()}),a.events.on("destroy",function(){j&&j.length&&j.remove()},!0)}var j,k,l="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",m=function(){for(var a=0,b=document.domain,c=b.split("."),d="_gd"+(new Date).getTime();a<c.length-1&&-1==document.cookie.indexOf(d+"="+d);)b=c.slice(-1-++a).join("."),document.cookie=d+"="+d+";domain="+b+";";return document.cookie=d+"=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain="+b+";",b}(),n=b(c);return{_init:i}}

  // Enter possible actions.
  $.FE.ENTER_P = 0;
  $.FE.ENTER_DIV = 1;
  $.FE.ENTER_BR = 2;

  $.FE.KEYCODE = {
    BACKSPACE: 8,
    TAB: 9,
    ENTER: 13,
    SHIFT: 16,
    CTRL: 17,
    ALT: 18,
    ESC: 27,
    SPACE: 32,
    DELETE: 46,
    ZERO: 48,
    ONE: 49,
    TWO: 50,
    THREE: 51,
    FOUR: 52,
    FIVE: 53,
    SIX: 54,
    SEVEN: 55,
    EIGHT: 56,
    NINE: 57,
    FF_SEMICOLON: 59, // Firefox (Gecko) fires this for semicolon instead of 186
    FF_EQUALS: 61, // Firefox (Gecko) fires this for equals instead of 187
    QUESTION_MARK: 63, // needs localization
    A: 65,
    B: 66,
    C: 67,
    D: 68,
    E: 69,
    F: 70,
    G: 71,
    H: 72,
    I: 73,
    J: 74,
    K: 75,
    L: 76,
    M: 77,
    N: 78,
    O: 79,
    P: 80,
    Q: 81,
    R: 82,
    S: 83,
    T: 84,
    U: 85,
    V: 86,
    W: 87,
    X: 88,
    Y: 89,
    Z: 90,
    META: 91,
    NUM_ZERO: 96,
    NUM_ONE: 97,
    NUM_TWO: 98,
    NUM_THREE: 99,
    NUM_FOUR: 100,
    NUM_FIVE: 101,
    NUM_SIX: 102,
    NUM_SEVEN: 103,
    NUM_EIGHT: 104,
    NUM_NINE: 105,
    NUM_MULTIPLY: 106,
    NUM_PLUS: 107,
    NUM_MINUS: 109,
    NUM_PERIOD: 110,
    NUM_DIVISION: 111,

    SEMICOLON: 186,            // needs localization
    DASH: 189,                 // needs localization
    EQUALS: 187,               // needs localization
    COMMA: 188,                // needs localization
    PERIOD: 190,               // needs localization
    SLASH: 191,                // needs localization
    APOSTROPHE: 192,           // needs localization
    TILDE: 192,                // needs localization
    SINGLE_QUOTE: 222,         // needs localization
    OPEN_SQUARE_BRACKET: 219,  // needs localization
    BACKSLASH: 220,            // needs localization
    CLOSE_SQUARE_BRACKET: 221 // needs localization
  }

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    enter: $.FE.ENTER_P,
    multiLine: true,
    tabSpaces: 0
  });

  $.FE.MODULES.keys = function (editor) {
    var IME = false;

    /**
     * Hide and then show the keyboard again to make the keyboard change.
     */
    function _hideShowiOSKeyboard() {
      if (editor.helpers.isIOS()) {
        var is_chrome = navigator.userAgent.match('CriOS');
        var is_uiwebview = /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(navigator.userAgent);

        if (!is_chrome && !is_uiwebview) {
          var c_scroll = $(editor.o_win).scrollTop();
          editor.events.disableBlur();
          editor.selection.save();
          editor.$el.blur();
          editor.selection.restore();
          editor.events.enableBlur();
          $(editor.o_win).scrollTop(c_scroll);
        }
      }
    }

    /**
     * ENTER.
     */
    function _enter (e) {
      e.preventDefault();
      e.stopPropagation();

      if (editor.opts.multiLine) {
        if (!editor.selection.isCollapsed()) editor.selection.remove();

        editor.cursor.enter();
      }

      _hideShowiOSKeyboard();
    }

    /**
     * SHIFT ENTER.
     */
    function _shiftEnter (e) {
      e.preventDefault();
      e.stopPropagation();

      if (editor.opts.multiLine) {
        if (!editor.selection.isCollapsed()) editor.selection.remove();

        editor.cursor.enter(true);
      }
    }

    /**
     * BACKSPACE.
     */
    var regular_backspace;
    function _backspace (e) {
      // There is no selection.
      if (editor.selection.isCollapsed()) {
        if (!editor.cursor.backspace()) {
          e.preventDefault();
          e.stopPropagation();
          regular_backspace = false;
        }
      }

      // We have text selected.
      else {
        e.preventDefault();
        e.stopPropagation();

        editor.selection.remove();
        editor.html.fillEmptyBlocks();

        regular_backspace = false;
      }

      editor.placeholder.refresh();
    }

    /**
     * DELETE
     */
    function _del (e) {
      e.preventDefault();
      e.stopPropagation();

      // There is no selection.
      if (editor.selection.text() === '') {
        editor.cursor.del();
      }

      // We have text selected.
      else {
        editor.selection.remove();
      }

      editor.placeholder.refresh();
    }

    /**
     * SPACE
     */
    function _space (e) {
      if (editor.browser.mozilla) {
        e.preventDefault();
        e.stopPropagation();

        if (!editor.selection.isCollapsed()) editor.selection.remove();
        editor.markers.insert();

        var marker = editor.$el.find('.fr-marker').get(0);
        var prev_node = marker.previousSibling;
        var next_node = marker.nextSibling;

        if (!next_node && marker.parentNode && marker.parentNode.tagName == 'A') {
          $(marker).parent().after('&nbsp;' + $.FE.MARKERS);
          $(marker).remove();
        }
        else {
          if (prev_node && prev_node.nodeType == Node.TEXT_NODE && prev_node.textContent.length == 1 && prev_node.textContent.charCodeAt(0) == 160) {
            $(prev_node).after(' ');
          }
          else {
            $(marker).before('&nbsp;')
          }
          $(marker).replaceWith($.FE.MARKERS);
        }

        editor.selection.restore();
      }
    }

    /**
     * Handle typing in Korean for FF.
     */
    function _input () {
      // Select is collapsed and we're not using IME.
      if (editor.browser.mozilla && editor.selection.isCollapsed() && !IME) {
        var range = editor.selection.ranges(0);
        var start_container = range.startContainer;
        var start_offset = range.startOffset;

        // Start container is text and last char before cursor is space.
        if (start_container && start_container.nodeType == Node.TEXT_NODE && start_offset <= start_container.textContent.length && start_offset > 0 && start_container.textContent.charCodeAt(start_offset - 1) == 32) {
          editor.selection.save();
          editor.spaces.normalize();
          editor.selection.restore();
        }
      }
    }

    /**
     * Cut.
     */
    function _cut() {
      if (editor.selection.isFull()) {
        setTimeout(function () {
          var default_tag = editor.html.defaultTag();
          if (default_tag) {
            editor.$el.html('<' + default_tag + '>' + $.FE.MARKERS + '<br/></' + default_tag + '>');
          }
          else {
            editor.$el.html($.FE.MARKERS + '<br/>');
          }
          editor.selection.restore();

          editor.placeholder.refresh();
          editor.button.bulkRefresh();
          editor.undo.saveStep();
        }, 0);
      }
    }

    /**
     * Tab.
     */
    function _tab (e) {
      if (editor.opts.tabSpaces > 0) {
        if (editor.selection.isCollapsed()) {
          e.preventDefault();
          e.stopPropagation();

          var str = '';
          for (var i = 0; i < editor.opts.tabSpaces; i++) str += '&nbsp;';
          editor.html.insert(str);
          editor.placeholder.refresh();
        }
        else {
          e.preventDefault();
          e.stopPropagation();

          if (!e.shiftKey) {
            editor.commands.indent();
          }
          else {
            editor.commands.outdent();
          }
        }
      }
    }

    /**
     * Map keyPress actions.
     */
    function _mapKeyPress (e) {
      IME = false;
    }

    /**
     * If is IME.
     */
    function isIME() {
      return IME;
    }

    /**
     * Map keyDown actions.
     */
    function _mapKeyDown (e) {
      editor.events.disableBlur();

      regular_backspace = true;

      var key_code = e.which;

      if (key_code === 16) return true;

      // Handle Japanese typing.
      if (key_code === 229) {
        IME = true;
        return true;
      }
      else {
        IME = false;
      }

      var char_key = (isCharacter(key_code) && !ctrlKey(e));
      var del_key = (key_code == $.FE.KEYCODE.BACKSPACE || key_code == $.FE.KEYCODE.DELETE);

      // 1. Selection is full.
      // 2. Del key is hit, editor is empty and there is keepFormatOnDelete.
      if ((editor.selection.isFull() && !editor.opts.keepFormatOnDelete && !editor.placeholder.isVisible()) || (del_key && editor.placeholder.isVisible() && editor.opts.keepFormatOnDelete)) {
        if (char_key || del_key) {
          var default_tag = editor.html.defaultTag();
          if (default_tag) {
            editor.$el.html('<' + default_tag + '>' + $.FE.MARKERS + '<br/></' + default_tag + '>');
          }
          else {
            editor.$el.html($.FE.MARKERS + '<br/>');
          }

          editor.selection.restore();

          if (!isCharacter(key_code)) {
            e.preventDefault();
            return true;
          }
        }
      }

      // ENTER.
      if (key_code == $.FE.KEYCODE.ENTER) {
        if (e.shiftKey) {
          _shiftEnter(e);
        }
        else {
          _enter(e);
        }
      }

      // Backspace.
      else if (key_code == $.FE.KEYCODE.BACKSPACE && !ctrlKey(e) && !e.altKey && !editor.placeholder.isVisible()) {
        _backspace(e);
      }

      // Delete.
      else if (key_code == $.FE.KEYCODE.DELETE && !ctrlKey(e) && !e.altKey && !editor.placeholder.isVisible()) {
        _del(e);
      }

      else if (key_code == $.FE.KEYCODE.SPACE) {
        _space(e);
      }

      else if (key_code == $.FE.KEYCODE.TAB) {
        _tab(e);
      }

      else if (!ctrlKey(e) && isCharacter(e.which) && !editor.selection.isCollapsed()) {
        editor.selection.remove();
      }

      editor.events.enableBlur();
    }

    /**
     * Remove U200B.
     */
    function _replaceU200B (contents) {
      for (var i = 0; i < contents.length; i++) {
        if (contents[i].nodeType == Node.TEXT_NODE && /\u200B/gi.test(contents[i].textContent)) {
          contents[i].textContent = contents[i].textContent.replace(/\u200B/gi, '');
          if (contents[i].textContent.length === 0) {
            $(contents[i]).remove();
          }
        }
        else if (contents[i].nodeType == Node.ELEMENT_NODE && contents[i].nodeType != 'IFRAME') _replaceU200B(editor.node.contents(contents[i]));
      }
    }

    function _positionCaret () {
      if (!editor.$wp) return true;

      var info;
      if (!editor.opts.height && !editor.opts.heightMax) {
        // Make sure we scroll bottom.
        info = editor.position.getBoundingRect().top;

        // https://github.com/froala/wysiwyg-editor/issues/834.
        if (editor.opts.toolbarBottom) info += editor.opts.toolbarStickyOffset;

        if (editor.helpers.isIOS()) info -= $(editor.o_win).scrollTop();

        if (editor.opts.iframe) {
          info += editor.$iframe.offset().top;
        }

        info += editor.opts.toolbarStickyOffset;

        if (info > editor.o_win.innerHeight - 20) {
          $(editor.o_win).scrollTop(info + $(editor.o_win).scrollTop() - editor.o_win.innerHeight + 20);
        }

        // Make sure we scroll top.
        info = editor.position.getBoundingRect().top;

        // https://github.com/froala/wysiwyg-editor/issues/834.
        if (!editor.opts.toolbarBottom) info -= editor.opts.toolbarStickyOffset;

        if (editor.helpers.isIOS()) info -= $(editor.o_win).scrollTop();

        if (editor.opts.iframe) {
          info += editor.$iframe.offset().top;
        }
        if (info < editor.$tb.height() + 20) {
          $(editor.o_win).scrollTop(info + $(editor.o_win).scrollTop() - editor.$tb.height() - 20);
        }
      }
      else {
        // Make sure we scroll bottom.
        info = editor.position.getBoundingRect().top;
        if (editor.helpers.isIOS()) info -= $(editor.o_win).scrollTop();

        if (editor.opts.iframe) {
          info += editor.$iframe.offset().top;
        }

        if (info > editor.$wp.offset().top - $(editor.o_win).scrollTop() + editor.$wp.height() - 20) {
          editor.$wp.scrollTop(info + editor.$wp.scrollTop() - (editor.$wp.height() + editor.$wp.offset().top) + $(editor.o_win).scrollTop() + 20);
        }
      }
    }

    /**
     * Map keyUp actions.
     */
    function _mapKeyUp (e) {
      // IME IE.
      if (IME) return false;
      if (!editor.selection.isCollapsed()) return true;

      if (e && (e.which == $.FE.KEYCODE.ENTER || e.which == $.FE.KEYCODE.BACKSPACE || (e.which >= 37 && e.which <= 40 && !editor.browser.msie))) {
        if (!(e.which == $.FE.KEYCODE.BACKSPACE && regular_backspace)) _positionCaret();
      }

      // Remove BR from elements that are not empty.
      var els = editor.$el.find(editor.html.blockTagsQuery());
      els.push(editor.$el.get(0));

      var brs = [];
      for (var i = 0; i < els.length; i++) {
        if (['TD', 'TH'].indexOf(els[i].tagName) < 0) {
          var new_brs = els[i].children;
          for (var j = 0; j < new_brs.length; j++) {
            if (new_brs[j].tagName == 'BR') {
              brs.push(new_brs[j]);
            }
          }
        }
      }
      var els = [];

      for (var i = 0; i < brs.length; i++) {
        var br = brs[i];

        var prev_node = br.previousSibling;
        var next_node = br.nextSibling;

        // Get the parent node.
        var parent_node = editor.node.blockParent(br) || editor.$el.get(0);

        // Previous node.
        // Previous node is not BR.
        // Previoues node is not block tag.
        // No next node.
        // Parent node has text.
        // Previous node has text.
        if (prev_node && parent_node && prev_node.tagName != 'BR' && !editor.node.isBlock(prev_node) && !next_node && $(parent_node).text().replace(/\u200B/g, '').length > 0 && $(prev_node).text().length > 0) {
          // Fix for https://github.com/froala/wysiwyg-editor/issues/1166#issuecomment-204549406.
          if (!(editor.$el.is(parent_node) && !next_node && editor.opts.enter == $.FE.ENTER_BR && editor.browser.msie)) {
            editor.selection.save();
            $(br).remove();
            editor.selection.restore();
          }
        }
      }
      brs = [];

      // Remove invisible space where possible.
      var has_invisible = function (node) {
        if (!node) return false;

        var text = $(node).html();
        text = text.replace(/<span[^>]*? class\s*=\s*["']?fr-marker["']?[^>]+>\u200b<\/span>/gi, '');
        if (text && /\u200B/.test(text) && text.replace(/\u200B/gi, '').length > 0) return true;
        return false;
      }

      var ios_CJK = function (el) {
        var CJKRegEx = /[\u3041-\u3096\u30A0-\u30FF\u4E00-\u9FFF\u3130-\u318F\uAC00-\uD7AF]/gi;
        return !editor.helpers.isIOS() || ((el.textContent || '').match(CJKRegEx) || []).length === 0;
      }

      // Get the selection element.
      var el = editor.selection.element();
      if (has_invisible(el) && $(el).find('li').length === 0 && !$(el).hasClass('fr-marker') && el.tagName != 'IFRAME' && ios_CJK(el)) {
        editor.selection.save();
        _replaceU200B(editor.node.contents(el));
        editor.selection.restore();
      }

      // https://github.com/froala/wysiwyg-editor/issues/1011
      if (!editor.browser.mozilla && editor.html.doNormalize()) {
        editor.selection.save();
        editor.spaces.normalize();
        editor.selection.restore();
      }
    }

    // Check if we should consider that CTRL key is pressed.
    function ctrlKey (e) {
      if (navigator.userAgent.indexOf('Mac OS X') != -1) {
        if (e.metaKey && !e.altKey) return true;
      } else {
        if (e.ctrlKey && !e.altKey) return true;
      }

      return false;
    }

    function isCharacter (key_code) {
      if (key_code >= $.FE.KEYCODE.ZERO &&
          key_code <= $.FE.KEYCODE.NINE) {
        return true;
      }

      if (key_code >= $.FE.KEYCODE.NUM_ZERO &&
          key_code <= $.FE.KEYCODE.NUM_MULTIPLY) {
        return true;
      }

      if (key_code >= $.FE.KEYCODE.A &&
          key_code <= $.FE.KEYCODE.Z) {
        return true;
      }

      // Safari sends zero key code for non-latin characters.
      if (editor.browser.webkit && key_code === 0) {
        return true;
      }

      switch (key_code) {
      case $.FE.KEYCODE.SPACE:
      case $.FE.KEYCODE.QUESTION_MARK:
      case $.FE.KEYCODE.NUM_PLUS:
      case $.FE.KEYCODE.NUM_MINUS:
      case $.FE.KEYCODE.NUM_PERIOD:
      case $.FE.KEYCODE.NUM_DIVISION:
      case $.FE.KEYCODE.SEMICOLON:
      case $.FE.KEYCODE.FF_SEMICOLON:
      case $.FE.KEYCODE.DASH:
      case $.FE.KEYCODE.EQUALS:
      case $.FE.KEYCODE.FF_EQUALS:
      case $.FE.KEYCODE.COMMA:
      case $.FE.KEYCODE.PERIOD:
      case $.FE.KEYCODE.SLASH:
      case $.FE.KEYCODE.APOSTROPHE:
      case $.FE.KEYCODE.SINGLE_QUOTE:
      case $.FE.KEYCODE.OPEN_SQUARE_BRACKET:
      case $.FE.KEYCODE.BACKSLASH:
      case $.FE.KEYCODE.CLOSE_SQUARE_BRACKET:
        return true;
      default:
        return false;
      }
    }

    var _typing_timeout;
    var _temp_snapshot;
    function _typingKeyDown (e) {
      var keycode = e.which;
      if (ctrlKey(e) || (keycode >= 37 && keycode <= 40) || (!isCharacter(keycode) && keycode != $.FE.KEYCODE.DELETE && keycode != $.FE.KEYCODE.BACKSPACE && keycode != $.FE.KEYCODE.ENTER)) return true;

      if (!_typing_timeout) {
        _temp_snapshot = editor.snapshot.get();
      }

      clearTimeout(_typing_timeout);
      _typing_timeout = setTimeout(function () {
        _typing_timeout = null;
        editor.undo.saveStep();
      }, Math.max(250, editor.opts.typingTimer));
    }

    function _typingKeyUp (e) {
      if (ctrlKey(e)) return true;

      if (_temp_snapshot && _typing_timeout) {
        editor.undo.saveStep(_temp_snapshot);
        _temp_snapshot = null;
      }
    }

    function forceUndo () {
      if (_typing_timeout) {
        clearTimeout(_typing_timeout);
        editor.undo.saveStep();
        _temp_snapshot = null;
      }
    }

    /**
     * Tear up.
     */
    function _init () {
      editor.events.on('keydown', _typingKeyDown);
      editor.events.on('input', _input);
      editor.events.on('keyup', _typingKeyUp);

      // Register for handling.
      editor.events.on('keypress', _mapKeyPress);
      editor.events.on('keydown', _mapKeyDown);
      editor.events.on('keyup', _mapKeyUp);

      editor.events.on('html.inserted', _mapKeyUp);

      // Handle cut.
      editor.events.on('cut', _cut);


      // IME
      if (editor.$el.get(0).msGetInputContext) {
        try {
          editor.$el.get(0).msGetInputContext().addEventListener('MSCandidateWindowShow', function () {
            IME = true;
          })

          editor.$el.get(0).msGetInputContext().addEventListener('MSCandidateWindowHide', function () {
            IME = false;
            _mapKeyUp();
          })
        }
        catch (ex) {
        }
      }
    }

    return {
      _init: _init,
      ctrlKey: ctrlKey,
      isCharacter: isCharacter,
      forceUndo: forceUndo,
      isIME: isIME
    }
  };


  $.extend($.FE.DEFAULTS, {
    pastePlain: false,
    pasteDeniedTags: ['colgroup', 'col'],
    pasteDeniedAttrs: ['class', 'id', 'style'],
    pasteAllowLocalImages: false
  });

  $.FE.MODULES.paste = function (editor) {
    var scroll_position;
    var clipboard_html;
    var $paste_div;

    /**
     * Handle copy and cut.
     */
    function _handleCopy (e) {
      $.FE.copied_html = editor.html.getSelected();
      $.FE.copied_text = $('<div>').html($.FE.copied_html).text();

      if (e.type == 'cut') {
        editor.undo.saveStep();
        setTimeout(function () {
          editor.html.wrap();
          editor.events.focus();
          editor.undo.saveStep();
        }, 0);
      }
    }

    /**
     * Handle pasting.
     */
    var stop_paste = false;
    function _handlePaste (e) {
      if (stop_paste) {
        return false;
      }

      if (e.originalEvent) e = e.originalEvent;

      if (editor.events.trigger('paste.before', [e]) === false) {
        return false;
      }

      scroll_position = editor.$win.scrollTop();

      // Read data from clipboard.
      if (e && e.clipboardData && e.clipboardData.getData) {
        var types = '';
        var clipboard_types = e.clipboardData.types;

        if (editor.helpers.isArray(clipboard_types)) {
          for (var i = 0 ; i < clipboard_types.length; i++) {
            types += clipboard_types[i] + ';';
          }
        } else {
          types = clipboard_types;
        }

        clipboard_html = '';

        // HTML.
        if (/text\/html/.test(types)) {
          clipboard_html = e.clipboardData.getData('text/html');
        }

        // Safari HTML.
        else if (/text\/rtf/.test(types) && editor.browser.safari) {
          clipboard_html = e.clipboardData.getData('text/rtf');
        }

        else if (/text\/plain/.test(types) && !this.browser.mozilla) {
          clipboard_html = editor.html.escapeEntities(e.clipboardData.getData('text/plain')).replace(/\n/g, '<br>');
        }

        if (clipboard_html !== '') {
          _processPaste();

          if (e.preventDefault) {
            e.stopPropagation();
            e.preventDefault();
          }

          return false;
        }
        else {
          clipboard_html = null;
        }
      }

      // Normal paste.
      _beforePaste();
    }

    /**
     * Before starting to paste.
     */
    function _beforePaste () {
      // Save selection
      editor.selection.save();
      editor.events.disableBlur();

      // Set clipboard HTML.
      clipboard_html = null;

      // Remove and store the editable content
      if (!$paste_div) {
        $paste_div = $('<div contenteditable="true" style="position: fixed; top: 0; left: -9999px; height: 100%; width: 0; word-break: break-all; overflow:hidden; z-index: 9999; line-height: 140%;" tabindex="-1"></div>');
        editor.$box.after($paste_div);

        editor.events.on('destroy', function () {
          $paste_div.remove();
        })
      }
      else {
        $paste_div.html('');
      }

      // Focus on the pasted div.
      $paste_div.focus();

      // Process paste soon.
      editor.win.setTimeout(_processPaste, 1);
    }

    /**
     * Clean HTML that was pasted from Word.
     */
    function _wordClean (html) {
      // Single item list.
      html = html.replace(
        /<p(.*?)class="?'?MsoListParagraph"?'? ([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<ul><li>$3</li></ul>'
      );
      html = html.replace(
        /<p(.*?)class="?'?NumberedText"?'? ([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<ol><li>$3</li></ol>'
      );

      // List start.
      html = html.replace(
        /<p(.*?)class="?'?MsoListParagraphCxSpFirst"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<ul><li$3>$5</li>'
      );
      html = html.replace(
        /<p(.*?)class="?'?NumberedTextCxSpFirst"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<ol><li$3>$5</li>'
      );

      // List middle.
      html = html.replace(
        /<p(.*?)class="?'?MsoListParagraphCxSpMiddle"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<li$3>$5</li>'
      );
      html = html.replace(
        /<p(.*?)class="?'?NumberedTextCxSpMiddle"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<li$3>$5</li>'
      );
      html = html.replace(
        /<p(.*?)class="?'?MsoListBullet"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<li$3>$5</li>'
      );

      // List end.
      html = html.replace(
        /<p(.*?)class="?'?MsoListParagraphCxSpLast"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<li$3>$5</li></ul>'
      );
      html = html.replace(
        /<p(.*?)class="?'?NumberedTextCxSpLast"?'?([\s\S]*?)(level\d)?([\s\S]*?)>([\s\S]*?)<\/p>/gi,
        '<li$3>$5</li></ol>'
      );

      // Clean list bullets.
      html = html.replace(/<span([^<]*?)style="?'?mso-list:Ignore"?'?([\s\S]*?)>([\s\S]*?)<span/gi, '<span><span');

      // Webkit clean list bullets.
      html = html.replace(/<!--\[if \!supportLists\]-->([\s\S]*?)<!--\[endif\]-->/gi, '');
      html = html.replace(/<!\[if \!supportLists\]>([\s\S]*?)<!\[endif\]>/gi, '');

      // Remove mso classes.
      html = html.replace(/(\n|\r| class=(")?Mso[a-zA-Z0-9]+(")?)/gi, ' ');

      // Remove comments.
      html = html.replace(/<!--[\s\S]*?-->/gi, '');

      // Remove tags but keep content.
      html = html.replace(/<(\/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>/gi, '');

      // Remove no needed tags.
      var word_tags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];
      for (var i = 0; i < word_tags.length; i++) {
        var regex = new RegExp('<' + word_tags[i] + '.*?' + word_tags[i] + '(.*?)>', 'gi');
        html = html.replace(regex, '');
      }

      // Remove spaces.
      html = html.replace(/&nbsp;/gi, ' ');

      // Keep empty TH and TD.
      html = html.replace(/<td([^>]*)><\/td>/g, '<td$1><br></td>');
      html = html.replace(/<th([^>]*)><\/th>/g, '<th$1><br></th>');

      // Remove empty tags.
      var oldHTML;
      do {
        oldHTML = html;
        html = html.replace(/<[^\/>][^>]*><\/[^>]+>/gi, '');
      } while (html != oldHTML);

      // Process list indentation.
      html = html.replace(/<lilevel([^1])([^>]*)>/gi, '<li data-indent="true"$2>');
      html = html.replace(/<lilevel1([^>]*)>/gi, '<li$1>');

      // Clean HTML.
      html = editor.clean.html(html, editor.opts.pasteDeniedTags, editor.opts.pasteDeniedAttrs);

      // Clean empty links.
      html = html.replace(/<a>(.[^<]+)<\/a>/gi, '$1');

      // Process list indent.
      var $div = $('<div>').html(html);
      $div.find('li[data-indent]').each (function (index, li) {
        var $li = $(li);
        if ($li.prev('li').length > 0) {
          var $list = $li.prev('li').find('> ul, > ol');
          if ($list.length === 0) {
            $list = $('ul');
            $li.prev('li').append($list);
          }

          $list.append(li);
        }
        else {
          $li.removeAttr('data-indent');
        }
      });

      editor.html.cleanBlankSpaces($div.get(0));

      html = $div.html();

      return html;
    }

    /**
     * Plain clean.
     */
    function _plainPasteClean (html) {
      var $div = $('<div>').html(html);

      $div.find('p, div, h1, h2, h3, h4, h5, h6, pre, blockquote').each (function (i, el) {
        $(el).replaceWith('<' + (editor.html.defaultTag() || 'DIV') + '>' + $(el).html() + '</' + (editor.html.defaultTag() || 'DIV') + '>');
      });

      // Remove with the content.
      $($div.find('*').not('p, div, h1, h2, h3, h4, h5, h6, pre, blockquote, ul, ol, li, table, tbody, thead, tr, td, br, img').get().reverse()).each (function () {
        $(this).replaceWith($(this).html());
      });

      // Remove comments.
      var cleanComments = function (node) {
        var contents = editor.node.contents(node);

        for (var i = 0; i < contents.length; i++) {
          if (contents[i].nodeType != 3 && contents[i].nodeType != 1) {
            $(contents[i]).remove();
          }
          else {
            cleanComments(contents[i]);
          }
        }
      };

      cleanComments($div.get(0));

      return $div.html();
    }

    /**
     * Process the pasted HTML.
     */
    function _processPaste () {
      editor.keys.forceUndo();
      var snapshot = editor.snapshot.get();

      if (clipboard_html === null) {
        clipboard_html = $paste_div.html();

        editor.selection.restore();
        editor.events.enableBlur();
      }

      var response = editor.events.chainTrigger('paste.beforeCleanup', clipboard_html);
      if (typeof(response) === 'string') {
        clipboard_html = response;
      }

      // Keep only body if there is.
      if (clipboard_html.indexOf('<body') >= 0) {
        clipboard_html = clipboard_html.replace(/[.\s\S\w\W<>]*<body[^>]*>([.\s\S\w\W<>]*)<\/body>[.\s\S\w\W<>]*/g, '$1');
      }

      // Google Docs paste.
      if (clipboard_html.indexOf('id="docs-internal-guid') >= 0) {
        clipboard_html = clipboard_html.replace(/^.* id="docs-internal-guid[^>]*>(.*)<\/b>.*$/, '$1');
      }

      // Word paste.
      if (clipboard_html.match(/(class=\"?Mso|style=\"[^\"]*\bmso\-|w:WordDocument)/gi)) {
        // Strip spaces at the beginning.
        clipboard_html = clipboard_html.replace(/^\n*/g, '').replace(/^ /g, '');

        // Firefox paste.
        if (clipboard_html.indexOf('<colgroup>') === 0) {
          clipboard_html = '<table>' + clipboard_html + '</table>';
        }

        clipboard_html = _wordClean(clipboard_html);
        clipboard_html = _removeEmptyTags(clipboard_html);
      }

      // Paste.
      else {
        editor.opts.htmlAllowComments = false;
        clipboard_html = editor.clean.html(clipboard_html, editor.opts.pasteDeniedTags, editor.opts.pasteDeniedAttrs);
        editor.opts.htmlAllowComments = true;
        clipboard_html = _removeEmptyTags(clipboard_html);

        clipboard_html = clipboard_html.replace(/\r|\n|\t/g, '');

        if ($.FE.copied_text && $('<div>').html(clipboard_html).text().replace(/(\u00A0)/gi, ' ').replace(/\r|\n/gi, '') == $.FE.copied_text.replace(/(\u00A0)/gi, ' ').replace(/\r|\n/gi, '')) {
          clipboard_html = $.FE.copied_html;
        }

        clipboard_html = clipboard_html.replace(/^ */g, '').replace(/ *$/g, '');
      }

      // Do plain paste cleanup.
      if (editor.opts.pastePlain) {
        clipboard_html = _plainPasteClean(clipboard_html);
      }

      // After paste cleanup event.
      response = editor.events.chainTrigger('paste.afterCleanup', clipboard_html);
      if (typeof(response) === 'string') {
        clipboard_html = response;
      }

      // Check if there is anything to clean.
      if (clipboard_html !== '') {
        // Normalize spaces.
        var $tmp = $('<div>').html(clipboard_html);
        editor.spaces.normalize($tmp.get(0));
        $tmp.find('span').each (function () {
          if (this.attributes.length == 0) {
            $(this).replaceWith(this.innerHTML);
          }
        })

        // Remove unecessary new_lines.
        $tmp.find('br').each (function () {
          if (this.previousSibling && editor.node.isBlock(this.previousSibling)) {
            $(this).remove();
          }
        })

        clipboard_html = $tmp.html();

        console.log (clipboard_html)

        // Insert HTML.
        editor.html.insert(clipboard_html, true);
      }

      _afterPaste();

      editor.undo.saveStep(snapshot);
      editor.undo.saveStep();
    }

    /**
     * After pasting.
     */
    function _afterPaste () {
      editor.events.trigger('paste.after');
    }

    /**
     * Remove possible empty tags in pasted HTML.
     */
    function _removeEmptyTags (html) {
      var i;
      var $div = $('<div>').html(html);

      // Clean empty tags.
      var empty_tags = $div.find('*:empty:not(br, img, td, th)');
      while (empty_tags.length) {
        for (i = 0; i < empty_tags.length; i++) {
          $(empty_tags[i]).remove();
        }

        empty_tags = $div.find('*:empty:not(br, img, td, th)');
      }

      // Workaround for Nodepad paste.
      var divs = $div.find('> div:not([style]), td > div, th > div, li > div');
      while (divs.length) {
        var $dv = $(divs[divs.length - 1]);

        if (editor.html.defaultTag() && editor.html.defaultTag() != 'DIV') {
          $dv.replaceWith('<' + editor.html.defaultTag() + '>' + $dv.html() + '</' + editor.html.defaultTag() + '>' );
        }
        else if (!editor.html.defaultTag()) {
          if (!$dv.find('*:last').is('br')) {
            $dv.replaceWith($dv.html() + '<br>');
          }
          else {
            $dv.replaceWith($dv.html());
          }
        }

        divs = $div.find('> div:not([style]), td > div, th > div, li > div');
      }

      // Remove divs.
      divs = $div.find('div:not([style])');
      while (divs.length) {
        for (i = 0; i < divs.length; i++) {
          var $el = $(divs[i]);
          var text = $el.html().replace(/\u0009/gi, '').trim();

          $el.replaceWith(text);
        }

        divs = $div.find('div:not([style])');
      }

      return $div.html();
    }

    /**
     * Initialize.
     */
    function _init () {
      editor.events.on('copy', _handleCopy);
      editor.events.on('cut', _handleCopy);
      editor.events.on('paste', _handlePaste);

      if (editor.browser.msie && editor.browser.version < 11) {
        editor.events.on('mouseup', function (e) {
          if (e.button == 2) {
            setTimeout(function () {
              stop_paste = false;
            }, 50);
            stop_paste = true;
          }
        }, true)

        editor.events.on('beforepaste', _handlePaste);
      }
    }

    return {
      _init: _init
    }
  };


  $.extend($.FE.DEFAULTS, {
    shortcutsEnabled: ['show', 'bold', 'italic', 'underline', 'strikeThrough', 'indent', 'outdent', 'undo', 'redo'],
    shortcutsHint: true
  });

  $.FE.SHORTCUTS_MAP = {};

  $.FE.RegisterShortcut = function (key, cmd, val, letter, shift, option) {
    $.FE.SHORTCUTS_MAP[(shift ? '^' : '') + (option ? '@' : '') + key] = {
      cmd: cmd,
      val: val,
      letter: letter,
      shift: shift,
      option: option
    }

    $.FE.DEFAULTS.shortcutsEnabled.push(cmd);
  }

  $.FE.RegisterShortcut($.FE.KEYCODE.E, 'show', null, 'E', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.B, 'bold', null, 'B', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.I, 'italic', null, 'I', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.U, 'underline', null, 'U', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.S, 'strikeThrough', null, 'S', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.CLOSE_SQUARE_BRACKET, 'indent', null, ']', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.OPEN_SQUARE_BRACKET, 'outdent', null, '[', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.Z, 'undo', null, 'Z', false, false);
  $.FE.RegisterShortcut($.FE.KEYCODE.Z, 'redo', null, 'Z', true, false);

  $.FE.MODULES.shortcuts = function (editor) {
    var inverse_map = null;

    function get (cmd) {
      if (!editor.opts.shortcutsHint) return null;

      if (!inverse_map) {
        inverse_map = {};
        for (var key in $.FE.SHORTCUTS_MAP) {
          if ($.FE.SHORTCUTS_MAP.hasOwnProperty(key) && editor.opts.shortcutsEnabled.indexOf($.FE.SHORTCUTS_MAP[key].cmd) >= 0) {
            inverse_map[$.FE.SHORTCUTS_MAP[key].cmd + '.' + ($.FE.SHORTCUTS_MAP[key].val || '')] = {
              shift: $.FE.SHORTCUTS_MAP[key].shift,
              option: $.FE.SHORTCUTS_MAP[key].option,
              letter: $.FE.SHORTCUTS_MAP[key].letter
            }
          }
        }
      }

      var srct = inverse_map[cmd];

      if (!srct) return null;
      return (editor.helpers.isMac() ? String.fromCharCode(8984) : 'Ctrl+') + (srct.shift ? (editor.helpers.isMac() ? String.fromCharCode(8679) : 'Shift+') : '') + (srct.option ? (editor.helpers.isMac() ? String.fromCharCode(8997) : 'Alt+') : '') + srct.letter;
    }

    /**
     * Execute shortcut.
     */
    function exec (e) {
      if (!editor.core.hasFocus()) return true;

      var keycode = e.which;

      var ctrlKey = navigator.userAgent.indexOf('Mac OS X') != -1 ? e.metaKey : e.ctrlKey;

      // Build shortcuts map.
      var map_key = (e.shiftKey ? '^' : '') + (e.altKey ? '@' : '') + keycode;
      if (ctrlKey && $.FE.SHORTCUTS_MAP[map_key]) {
        var cmd = $.FE.SHORTCUTS_MAP[map_key].cmd;

        // Check if shortcut is enabled.
        if (cmd && editor.opts.shortcutsEnabled.indexOf(cmd) >= 0) {
          var val = $.FE.SHORTCUTS_MAP[map_key].val;

          // Search for button.
          var $btn;
          if (cmd && !val) {
            $btn = editor.$tb.find('.fr-command[data-cmd="' + cmd + '"]');
          }
          else if (cmd && val) {
            $btn = editor.$tb.find('.fr-command[data-cmd="' + cmd + '"][data-param1="' + val + '"]');
          }

          // Button found.
          if ($btn.length) {
            e.preventDefault();
            e.stopPropagation();

            $btn.parents('.fr-toolbar').data('instance', editor);

            if (e.type == 'keydown') {
              editor.button.exec($btn);
            }

            return false;
          }

          // Search for command.
          else if (cmd && editor.commands[cmd]) {
            e.preventDefault();
            e.stopPropagation();

            if (e.type == 'keydown') {
              editor.commands[cmd]();
            }

            return false;
          }
        }
      }
    }

    /**
     * Initialize.
     */
    function _init () {
      editor.events.on('keydown', exec, true);
    }

    return {
      _init: _init,
      get: get
    }
  }


  $.FE.MODULES.snapshot = function (editor) {
    /**
     * Get the index of a node inside it's parent.
     */
    function _getNodeIndex (node) {
      var childNodes = node.parentNode.childNodes;
      var idx = 0;
      var prevNode = null;
      for (var i = 0; i < childNodes.length; i++) {
        if (prevNode) {
          // Current node is text and it is empty.
          var isEmptyText = (childNodes[i].nodeType === Node.TEXT_NODE && childNodes[i].textContent === '');

          // Previous node is text, current node is text.
          var twoTexts = (prevNode.nodeType === Node.TEXT_NODE && childNodes[i].nodeType === Node.TEXT_NODE);

          if (!isEmptyText && !twoTexts) idx++;
        }

        if (childNodes[i] == node) return idx;

        prevNode = childNodes[i];
      }
    }

    /**
     * Determine the location of the node inside the element.
     */
    function _getNodeLocation (node) {
      var loc = [];
      if (!node.parentNode) return [];
      while (!editor.node.isElement(node)) {
        loc.push(_getNodeIndex(node));
        node = node.parentNode;
      }

      return loc.reverse();
    }

    /**
     * Get the range offset inside the node.
     */
    function _getRealNodeOffset (node, offset) {
      while (node && node.nodeType === Node.TEXT_NODE) {
        var prevNode = node.previousSibling;
        if (prevNode && prevNode.nodeType == Node.TEXT_NODE) {
          offset += prevNode.textContent.length;
        }
        node = prevNode;
      }

      return offset;
    }

    /**
     * Codify each range.
     */
    function _getRange (range) {
      return {
        scLoc: _getNodeLocation(range.startContainer),
        scOffset: _getRealNodeOffset(range.startContainer, range.startOffset),
        ecLoc: _getNodeLocation(range.endContainer),
        ecOffset: _getRealNodeOffset(range.endContainer, range.endOffset)
      }
    }

    /**
     * Get the current snapshot.
     */
    function get () {
      var snapshot = {};

      editor.events.trigger('snapshot.before');

      snapshot.html = editor.$wp ? editor.$el.html() : editor.$oel.get(0).outerHTML;

      snapshot.ranges = [];
      if (editor.$wp && editor.selection.inEditor() && editor.core.hasFocus()) {
        var ranges = editor.selection.ranges();
        for (var i = 0; i < ranges.length; i++) {
          snapshot.ranges.push(_getRange(ranges[i]));
        }
      }

      editor.events.trigger('snapshot.after');

      return snapshot;
    }

    /**
     * Determine node by its location in the main element.
     */
    function _getNodeByLocation (loc) {
      var node = editor.$el.get(0);
      for (var i = 0; i < loc.length; i++) {
        node = node.childNodes[loc[i]];
      }

      return node;
    }

    /**
     * Restore range from snapshot.
     */
    function _restoreRange (sel, range_snapshot) {
      try {
        // Get range info.
        var startNode = _getNodeByLocation(range_snapshot.scLoc);
        var startOffset = range_snapshot.scOffset;
        var endNode = _getNodeByLocation(range_snapshot.ecLoc);
        var endOffset = range_snapshot.ecOffset;

        // Restore range.
        var range = editor.doc.createRange();
        range.setStart(startNode, startOffset);
        range.setEnd(endNode, endOffset);

        sel.addRange(range);
      }
      catch (ex) {
        console.warn (ex)
      }
    }

    /**
     * Restore snapshot.
     */
    function restore (snapshot) {
      // Restore HTML.
      if (editor.$el.html() != snapshot.html) editor.$el.html(snapshot.html);

      // Get selection.
      var sel = editor.selection.get();

      // Make sure to clear current selection.
      editor.selection.clear();

      // Focus.
      editor.events.focus(true);

      // Restore Ranges.
      for (var i = 0; i < snapshot.ranges.length; i++) {
        _restoreRange(sel, snapshot.ranges[i]);
      }
    }

    /**
     * Compare two snapshots.
     */
    function equal (s1, s2) {
      if (s1.html != s2.html) return false;
      if (editor.core.hasFocus() && JSON.stringify(s1.ranges) != JSON.stringify(s2.ranges)) return false;

      return true;
    }

    return {
      get: get,
      restore: restore,
      equal: equal
    }
  };


  $.FE.MODULES.undo = function (editor) {
    /**
     * Disable the default browser undo.
     */
    function _disableBrowserUndo (e) {
      var keyCode = e.which;
      var ctrlKey = editor.keys.ctrlKey(e);

      // Ctrl Key.
      if (ctrlKey) {
        if (keyCode == 90 && e.shiftKey) {
          e.preventDefault();
        }

        if (keyCode == 90) {
          e.preventDefault();
        }
      }
    }

    function canDo () {
      if (editor.undo_stack.length === 0 || editor.undo_index <= 1) {
        return false;
      }

      return true;
    }

    function canRedo () {
      if (editor.undo_index == editor.undo_stack.length) {
        return false;
      }

      return true;
    }

    var last_html = null;
    function saveStep (snapshot) {
      if (!editor.undo_stack || editor.undoing || editor.$el.get(0).querySelectorAll('.fr-marker').length) return false;

      if (typeof snapshot == 'undefined') {
        snapshot = editor.snapshot.get();

        if (!editor.undo_stack[editor.undo_index - 1] || !editor.snapshot.equal(editor.undo_stack[editor.undo_index - 1], snapshot)) {
          dropRedo();
          editor.undo_stack.push(snapshot);
          editor.undo_index++;

          if (snapshot.html != last_html) {
            editor.events.trigger('contentChanged');
            last_html = snapshot.html;
          }
        }
      }
      else {
        dropRedo();
        if (editor.undo_index > 0) {
          editor.undo_stack[editor.undo_index - 1] = snapshot;
        }
        else {
          editor.undo_stack.push(snapshot);
          editor.undo_index++;
        }
      }
    }

    function dropRedo () {
      if (!editor.undo_stack || editor.undoing) return false;

      while (editor.undo_stack.length > editor.undo_index) {
        editor.undo_stack.pop();
      }
    }

    function _do () {
      if (editor.undo_index > 1) {
        editor.undoing = true;

        // Get snapshot.
        var snapshot = editor.undo_stack[--editor.undo_index - 1];

        // Clear any existing content changed timers.
        clearTimeout(editor._content_changed_timer);

        // Restore snapshot.
        editor.snapshot.restore(snapshot);

        // Hide popups.
        editor.popups.hideAll();

        // Enable toolbar.
        editor.toolbar.enable();

        // Call content changed.
        editor.events.trigger('contentChanged');

        editor.events.trigger('commands.undo');

        editor.undoing = false;
      }
    }

    function _redo () {
      if (editor.undo_index < editor.undo_stack.length) {
        editor.undoing = true;

        // Get snapshot.
        var snapshot = editor.undo_stack[editor.undo_index++];

        // Clear any existing content changed timers.
        clearTimeout(editor._content_changed_timer)

        // Restore snapshot.
        editor.snapshot.restore(snapshot);

        // Hide popups.
        editor.popups.hideAll();

        // Enable toolbar.
        editor.toolbar.enable();

        // Call content changed.
        editor.events.trigger('contentChanged');

        editor.events.trigger('commands.redo');

        editor.undoing = false;
      }
    }

    function reset () {
      editor.undo_index = 0;
      editor.undo_stack = [];
    }

    function _destroy () {
      editor.undo_stack = [];
    }

    /**
     * Initialize
     */
    function _init () {
      reset();
      editor.events.on('initialized', function () {
        last_html = editor.html.get(false, true);
      });

      editor.events.on('blur', function () {
        editor.undo.saveStep();
      })

      editor.events.on('keydown', _disableBrowserUndo);

      editor.events.on('destroy', _destroy);
    }

    return {
      _init: _init,
      run: _do,
      redo: _redo,
      canDo: canDo,
      canRedo: canRedo,
      dropRedo: dropRedo,
      reset: reset,
      saveStep: saveStep
    }
  };


  $.FE.ICON_DEFAULT_TEMPLATE = 'font_awesome';

  $.FE.ICON_TEMPLATES = {
    font_awesome: '<i class="fa fa-[NAME]"></i>',
    text: '<span style="text-align: center;">[NAME]</span>',
    image: '<img src=[SRC] alt=[ALT] />'
  }

  $.FE.ICONS = {
    bold: {NAME: 'bold'},
    italic: {NAME: 'italic'},
    underline: {NAME: 'underline'},
    strikeThrough: {NAME: 'strikethrough'},
    subscript: {NAME: 'subscript'},
    superscript: {NAME: 'superscript'},
    color: {NAME: 'tint'},
    outdent: {NAME: 'outdent'},
    indent: {NAME: 'indent'},
    undo: {NAME: 'rotate-left'},
    redo: {NAME: 'rotate-right'},
    insertHR: {NAME: 'minus'},
    clearFormatting: {NAME: 'eraser'},
    selectAll: {NAME: 'mouse-pointer'}
  }

  $.FE.DefineIconTemplate = function (name, options) {
    $.FE.ICON_TEMPLATES[name] = options;
  }

  $.FE.DefineIcon = function (name, options) {
    $.FE.ICONS[name] = options;
  }

  $.FE.MODULES.icon = function (editor) {
    function create (command) {
      var icon = null;
      var info = $.FE.ICONS[command];
      if (typeof info != 'undefined') {
        var template = info.template || $.FE.ICON_DEFAULT_TEMPLATE;
        if (template && (template = $.FE.ICON_TEMPLATES[template])) {
          icon = template.replace(/\[([a-zA-Z]*)\]/g, function (str, a1) {
            return (a1 == 'NAME' ? (info[a1] || command) : info[a1]);
          });
        }
      }

      return (icon || command);
    }

    return {
      create: create
    }
  };


  $.FE.MODULES.button = function (editor) {
    var buttons = [];

    if (editor.opts.toolbarInline || editor.opts.toolbarContainer) {
      if (!editor.shared.buttons) editor.shared.buttons = [];
      buttons = editor.shared.buttons;
    }

    var popup_buttons = [];
    if (!editor.shared.popup_buttons) editor.shared.popup_buttons = [];
    popup_buttons = editor.shared.popup_buttons;

    /**
     * Click was made on a dropdown button.
     */
    function _dropdownButtonClick (e) {
      // Get current btn and dropdown.
      var $btn = $(e.currentTarget);
      var $dropdown = $btn.next();

      var active = $btn.hasClass('fr-active');
      var mobile = editor.helpers.isMobile();

      var $active_dropdowns = $('.fr-dropdown.fr-active').not($btn);

      var inst = $btn.parents('.fr-toolbar, .fr-popup').data('instance') || editor;

      // Hide keyboard. We need the entire space.
      if (inst.helpers.isIOS() && inst.$el.get(0).querySelectorAll('.fr-marker').length == 0) {
        inst.selection.save();
        inst.selection.clear();
        inst.selection.restore();
      }

      // Dropdown is not active.
      if (!active) {
        // Call refresh on show.
        var cmd = $btn.data('cmd');
        $dropdown.find('.fr-command').removeClass('fr-active');
        if ($.FE.COMMANDS[cmd] && $.FE.COMMANDS[cmd].refreshOnShow) {
          $.FE.COMMANDS[cmd].refreshOnShow.apply(inst, [$btn, $dropdown]);
        }

        $dropdown.css('left', $btn.offset().left - $btn.parent().offset().left - (editor.opts.direction == 'rtl' ? $dropdown.width() - $btn.outerWidth() : 0));

        if (!editor.opts.toolbarBottom) {
          $dropdown.css('top', $btn.position().top + $btn.outerHeight());
        }
        else {
          $dropdown.css('bottom', editor.$tb.height() - $btn.position().top);
        }
      }

      // Blink and activate.
      $btn.addClass('fr-blink').toggleClass('fr-active');
      setTimeout (function () {
        $btn.removeClass('fr-blink');
      }, 300);

      // Check if it exceeds window on the right.
      if ($dropdown.offset().left + $dropdown.outerWidth() > $(editor.opts.scrollableContainer).offset().left +  $(editor.opts.scrollableContainer).outerWidth()) {
        $dropdown.css('margin-left', -($dropdown.offset().left + $dropdown.outerWidth() - $(editor.opts.scrollableContainer).offset().left - $(editor.opts.scrollableContainer).outerWidth()))
      }

      // Hide dropdowns that might be active.
      $active_dropdowns.removeClass('fr-active');
      $active_dropdowns.parent('.fr-toolbar:not(.fr-inline)').css('zIndex', '');

      if ($btn.parents('.fr-popup').length == 0 && !editor.opts.toolbarInline) {
        if ($btn.hasClass('fr-active')) {
          editor.$tb.css('zIndex', (editor.opts.zIndex || 1) + 4);
        }
        else {
          editor.$tb.css('zIndex', '');
        }
      }
    }

    function exec ($btn) {
      // Blink.
      $btn.addClass('fr-blink');
      setTimeout (function () {
        $btn.removeClass('fr-blink');
      }, 500);

      // Get command, value and additional params.
      var cmd = $btn.data('cmd');
      var params = [];
      while (typeof $btn.data('param' + (params.length + 1)) != 'undefined') {
        params.push($btn.data('param' + (params.length + 1)));
      }

      // Hide dropdowns that might be active including the current one.
      var $active_dropdowns = $('.fr-dropdown.fr-active');
      if ($active_dropdowns.length) {
        $active_dropdowns.removeClass('fr-active');

        $active_dropdowns.parent('.fr-toolbar:not(.fr-inline)').css('zIndex', '');
      }

      // Call the command.
      $btn.parents('.fr-popup, .fr-toolbar').data('instance').commands.exec(cmd, params);
    }

    /**
     * Click was made on a command button.
     */
    function _commandButtonClick (e) {
      var $btn = $(e.currentTarget);
      exec($btn);
    }

    function _click (e) {
      var $btn = $(e.currentTarget);

      var inst = $btn.parents('.fr-popup, .fr-toolbar').data('instance');

      if ($btn.parents('.fr-popup').length == 0 && !$btn.data('popup')) {
        inst.popups.hideAll();
      }

      // Popups are visible, but not in the current instance.
      if (inst.popups.areVisible() && !inst.popups.areVisible(inst)) {
        // Hide markers in other instances.
        for (var i = 0; i < $.FE.INSTANCES.length; i++) {
          if ($.FE.INSTANCES[i] != inst && $.FE.INSTANCES[i].popups && $.FE.INSTANCES[i].popups.areVisible()) {
            $.FE.INSTANCES[i].$el.find('.fr-marker').remove();
          }
        }

        inst.popups.hideAll();
      }

      // Dropdown button.
      if ($btn.hasClass('fr-dropdown')) {
        _dropdownButtonClick(e);
      }

      // Regular button.
      else {
        _commandButtonClick(e);

        if ($.FE.COMMANDS[$btn.data('cmd')] && $.FE.COMMANDS[$btn.data('cmd')].refreshAfterCallback != false) {
          inst.button.bulkRefresh();
        }
      }
    }

    function _hideActiveDropdowns ($el) {
      var $active_dropdowns = $el.find('.fr-dropdown.fr-active');

      if ($active_dropdowns.length) {
        $active_dropdowns.removeClass('fr-active');

        $active_dropdowns.parent('.fr-toolbar:not(.fr-inline)').css('zIndex', '');
      }
    }

    /**
     * Click in the dropdown menu.
     */
    function _dropdownMenuClick (e) {
      e.preventDefault();
      e.stopPropagation();
    }

    /**
     * Click on the dropdown wrapper.
     */
    function _dropdownWrapperClick (e) {
      e.stopPropagation();

      // Prevent blurring.
      if (!editor.helpers.isMobile()) {
        return false;
      }
    }

    /**
     * Bind callbacks for commands.
     */
    function bindCommands ($el, tooltipAbove) {
      editor.events.bindClick($el, '.fr-command:not(.fr-disabled)', _click);

      // Click on the dropdown menu.
      editor.events.$on($el, editor._mousedown + ' ' + editor._mouseup + ' ' + editor._move, '.fr-dropdown-menu', _dropdownMenuClick, true);

      // Click on the dropdown wrapper.
      editor.events.$on($el, editor._mousedown + ' ' + editor._mouseup + ' ' + editor._move, '.fr-dropdown-menu .fr-dropdown-wrapper', _dropdownWrapperClick, true);

      // Hide dropdowns that might be active.
      var _document = $el.get(0).ownerDocument;
      var _window = 'defaultView' in _document ? _document.defaultView : _document.parentWindow;
      var hideDropdowns = function (e) {
        if (!e || (e.type == editor._mouseup && e.target != $('html').get(0)) || (e.type == 'keydown' && ((editor.keys.isCharacter(e.which) && !editor.keys.ctrlKey(e)) || e.which == $.FE.KEYCODE.ESC))) {
          _hideActiveDropdowns($el);
        }
      }
      editor.events.$on($(_window), editor._mouseup + ' resize keydown', hideDropdowns, true);

      if (editor.opts.iframe) {
        editor.events.$on(editor.$win, editor._mouseup, hideDropdowns, true);
      }

      // Add refresh.
      if ($el.hasClass('fr-popup')) {
        $.merge(popup_buttons, $el.find('.fr-btn').toArray());
      }
      else {
        $.merge(buttons, $el.find('.fr-btn').toArray());
      }

      // Assing tooltips to buttons.
      editor.tooltip.bind($el, '.fr-btn, .fr-title', tooltipAbove);
    }

    /**
     * Create the content for dropdown.
     */
    function _content (command, info) {
      var c = '';

      if (info.html) {
        if (typeof info.html == 'function') {
          c += info.html.call(editor);
        }
        else {
          c += info.html;
        }
      }
      else {
        var options = info.options;
        if (typeof options == 'function') options = options();

        c += '<ul class="fr-dropdown-list">';
        for (var val in options) {
          if (options.hasOwnProperty(val)) {
            var shortcut = editor.shortcuts.get(command + '.' + val);
            if (shortcut) {
              shortcut = '<span class="fr-shortcut">' + shortcut + '</span>';
            }
            else {
              shortcut = '';
            }

            c += '<li><a class="fr-command" data-cmd="' + command + '" data-param1="' + val + '" title="' + options[val] + '">' + editor.language.translate(options[val]) + '</a></li>';
          }
        }
        c += '</ul>';
      }

      return c;
    }

    /**
     * Create button.
     */
    function _build (command, info, visible) {
      var display_selection = info.displaySelection;
      if (typeof display_selection == 'function') {
        display_selection = display_selection(editor);
      }

      var icon;
      if (display_selection) {
        var default_selection = (typeof info.defaultSelection == 'function' ? info.defaultSelection(editor) : info.defaultSelection);
        icon = '<span style="width:' + (info.displaySelectionWidth || 100) + 'px">' + (default_selection || editor.language.translate(info.title)) + '</span>';
      }
      else {
        icon = editor.icon.create(info.icon || command)
      }

      var popup = info.popup ? ' data-popup="true"' : '';

      var shortcut = editor.shortcuts.get(command + '.');
      if (shortcut) {
        shortcut = ' (' + shortcut + ')';
      }
      else {
        shortcut = '';
      }

      var btn = '<button type="button" tabindex="-1" aria-label="' + (editor.language.translate(info.title) || '') + '" title="' + (editor.language.translate(info.title) || '') + shortcut + '" class="fr-command fr-btn' + (info.type == 'dropdown' ? ' fr-dropdown' : '') + (info.displaySelection ? ' fr-selection' : '') + (info.back ? ' fr-back' : '') + (info.disabled ? ' fr-disabled' : '') + (!visible ? ' fr-hidden' : '') + '" data-cmd="' + command + '"' + popup + '>' + icon + '</button>';

      if (info.type == 'dropdown') {
        // Build dropdown.
        var dropdown = '<div class="fr-dropdown-menu"><div class="fr-dropdown-wrapper"><div class="fr-dropdown-content">';

        dropdown += _content(command, info);

        dropdown += '</div></div></div>';

        btn += dropdown;
      }

      return btn;
    }

    function buildList (buttons, visible_buttons) {
      var str = '';
      for (var i = 0; i < buttons.length; i++) {
        var cmd_name = buttons[i];
        var cmd_info = $.FE.COMMANDS[cmd_name];

        if (cmd_info && typeof cmd_info.plugin !== 'undefined' && editor.opts.pluginsEnabled.indexOf(cmd_info.plugin) < 0) continue;

        if (cmd_info) {
          var visible = typeof visible_buttons != 'undefined' ? visible_buttons.indexOf(cmd_name) >= 0 : true;
          str += _build(cmd_name, cmd_info, visible);
        }
        else if (cmd_name == '|') {
          str += '<div class="fr-separator fr-vs"></div>';
        }
        else if (cmd_name == '-') {
          str += '<div class="fr-separator fr-hs"></div>';
        }
      }

      return str;
    }

    function refresh ($btn) {
      var inst = $btn.parents('.fr-popup, .fr-toolbar').data('instance') || editor;

      var cmd = $btn.data('cmd');

      var $dropdown;
      if (!$btn.hasClass('fr-dropdown')) $btn.removeClass('fr-active');
      else $dropdown = $btn.next();

      if ($.FE.COMMANDS[cmd] && $.FE.COMMANDS[cmd].refresh) {
       $.FE.COMMANDS[cmd].refresh.apply(inst, [$btn, $dropdown]);
      }
      else if (editor.refresh[cmd]) {
        inst.refresh[cmd]($btn, $dropdown);
      }
    }

    function _bulkRefresh (btns) {
      var inst = editor.$tb ? (editor.$tb.data('instance') || editor) : editor;

      // Check the refresh event.
      if (editor.events.trigger('buttons.refresh') == false) return true;

      setTimeout(function () {
        var focused = (inst.selection.inEditor() && inst.core.hasFocus());

        for (var i = 0; i < btns.length; i++) {
          var $btn = $(btns[i]);
          var cmd = $btn.data('cmd');
          if ($btn.parents('.fr-popup').length == 0) {
            if (focused || ($.FE.COMMANDS[cmd] && $.FE.COMMANDS[cmd].forcedRefresh)) {
              inst.button.refresh($btn);
            }
            else {
              if (!$btn.hasClass('fr-dropdown')) $btn.removeClass('fr-active');
            }
          }
          else if ($btn.parents('.fr-popup').is(':visible')) {
            inst.button.refresh($btn);
          }
        }
      }, 0);
    }

    /**
     * Do buttons refresh.
     */
    function bulkRefresh () {
      _bulkRefresh(buttons);
      _bulkRefresh(popup_buttons);
    }

    function _destroy () {
      buttons = [];
      popup_buttons = [];
    }

    /**
     * Initialize.
     */
    function _init () {
      // Assign refresh and do refresh.
      if (editor.opts.toolbarInline) {
        editor.events.on('toolbar.show', bulkRefresh);
      }
      else {
        editor.events.on('mouseup', bulkRefresh);
        editor.events.on('keyup', bulkRefresh);
        editor.events.on('blur', bulkRefresh);
        editor.events.on('focus', bulkRefresh);
        editor.events.on('contentChanged', bulkRefresh);
      }

      editor.events.on('shared.destroy', _destroy);
    }

    return {
      _init: _init,
      buildList: buildList,
      bindCommands: bindCommands,
      refresh: refresh,
      bulkRefresh: bulkRefresh,
      exec: exec
    }
  };


  $.FE.POPUP_TEMPLATES = {
    'text.edit': '[_EDIT_]'
  };

  $.FE.RegisterTemplate = function (name, template) {
    $.FE.POPUP_TEMPLATES[name] = template;
  }

  $.FE.MODULES.popups = function (editor) {
    if (!editor.shared.popups) editor.shared.popups = {};
    var popups = editor.shared.popups;

    function setContainer(id, $container) {
      if (!$container.is(':visible')) $container = $(editor.opts.scrollableContainer);

      if (!$container.is(popups[id].data('container'))) {
        popups[id].data('container', $container);
        $container.append(popups[id]);
      }
    }

    /**
     * Show popup at a specific position.
     */
    function show (id, left, top, obj_height) {
      // Restore selection on show if it is there.
      if (areVisible() && editor.$el.find('.fr-marker').length > 0) {
        editor.events.disableBlur();
        editor.selection.restore();
      }

      hideAll([id]);

      if (!popups[id]) return false;

      // Hide active dropdowns.
      $('.fr-dropdown.fr-active').removeClass('fr-active').parent('.fr-toolbar').css('zIndex', '');

      // Set the current instance for the popup.
      popups[id].data('instance', editor);
      if (editor.$tb) editor.$tb.data('instance', editor);

      var width = popups[id].outerWidth();
      var height = popups[id].outerHeight();
      var is_visible = isVisible(id);
      popups[id].addClass('fr-active').removeClass('fr-hidden').find('input, textarea').removeAttr('disabled');

      var $container = popups[id].data('container');

      // If container is toolbar then increase zindex.
      if ($container.is(editor.$tb)) editor.$tb.css('zIndex', (editor.opts.zIndex || 1) + 4);

      // Inline mode when container is toolbar.
      if (editor.opts.toolbarInline && $container && editor.$tb && $container.get(0) == editor.$tb.get(0)) {
        setContainer(id, $(editor.opts.scrollableContainer));
        top = editor.$tb.offset().top - editor.helpers.getPX(editor.$tb.css('margin-top'));
        left = editor.$tb.offset().left + editor.$tb.outerWidth() / 2 + (parseFloat(editor.$tb.find('.fr-arrow').css('margin-left')) || 0) + editor.$tb.find('.fr-arrow').outerWidth() / 2;

        if (editor.$tb.hasClass('fr-above') && top) {
          top += editor.$tb.outerHeight();
        }

        obj_height = 0;
      }

      // Apply iframe correction.
      $container = popups[id].data('container');
      if (editor.opts.iframe && !obj_height && !is_visible) {
        if (left) left -= editor.$iframe.offset().left;
        if (top) top -= editor.$iframe.offset().top;
      }

      // Apply left correction.
      if (left) left = left - width / 2;

      // Toolbar at the bottom and container is toolbar.
      if (editor.opts.toolbarBottom && $container && editor.$tb && $container.get(0) == editor.$tb.get(0)) {
        popups[id].addClass('fr-above');
        if (top) top = top - popups[id].outerHeight();
      }

      // Position editor.
      popups[id].removeClass('fr-active');
      editor.position.at(left, top, popups[id], obj_height || 0);
      popups[id].addClass('fr-active');

      // Focus in the first field.
      var active_popup = popups[id].find('input:visible, textarea:visible').get(0);
      if (active_popup) {
        // Save selection if necessary.

        if (editor.$el.find('.fr-marker').length == 0 && editor.core.hasFocus()) {
          editor.selection.save();
        }

        editor.events.disableBlur();
        $(active_popup).select().focus();
      }

      if (editor.opts.toolbarInline) editor.toolbar.hide();

      editor.events.trigger('popups.show.' + id);

      // https://github.com/froala/wysiwyg-editor/issues/1248
      _events(id)._repositionPopup();
    }

    function onShow (id, callback) {
      editor.events.on('popups.show.' + id, callback);
    }

    /**
     * Find visible popup.
     */
    function isVisible (id) {
      return (popups[id] && popups[id].hasClass('fr-active') && editor.core.sameInstance(popups[id])) || false;
    }

    /**
     * Check if there is any popup visible.
     */
    function areVisible (new_instance) {
      for (var id in popups) {
        if (popups.hasOwnProperty(id)) {
          if (isVisible(id) && (typeof new_instance == 'undefined' || popups[id].data('instance') == new_instance)) return true;
        }
      }

      return false;
    }

    /**
     * Hide popup.
     */
    function hide (id) {
      if (popups[id] && popups[id].hasClass('fr-active')) {
        popups[id].removeClass('fr-active fr-above');
        editor.events.trigger('popups.hide.' + id);

        // Reset toolbar zIndex.
        if (editor.$tb) {
          if (editor.opts.zIndex > 1) {
            editor.$tb.css('zIndex', editor.opts.zIndex + 1);
          }
          else {
            editor.$tb.css('zIndex', '');
          }
        }

        editor.events.disableBlur();
        popups[id].find('input, textarea, button').filter(':focus').blur();
        popups[id].find('input, textarea').attr('disabled', 'disabled');
      }
    }

    /**
     * Assign an event for hiding.
     */
    function onHide (id, callback) {
      editor.events.on('popups.hide.' + id, callback);
    }

    /**
     * Get the popup with the specific id.
     */
    function get (id) {
      var $popup = popups[id];

      if ($popup && !$popup.data('inst' + editor.id)) {
        var ev = _events(id);
        _bindInstanceEvents(ev, id);
      }

      return $popup;
    }

    function onRefresh (id, callback) {
      editor.events.on('popups.refresh.' + id, callback);
    }

    /**
     * Refresh content inside the popup.
     */
    function refresh (id) {
      editor.events.trigger('popups.refresh.' + id);

      var btns = popups[id].find('.fr-command');
      for (var i = 0; i < btns.length; i++) {
        var $btn = $(btns[i]);
        if ($btn.parents('.fr-dropdown-menu').length == 0) {
          editor.button.refresh($btn);
        }
      }
    }

    /**
     * Hide all popups.
     */
    function hideAll (except) {
      if (typeof except == 'undefined') except = [];

      for (var id in popups) {
        if (popups.hasOwnProperty(id)) {
          if (except.indexOf(id) < 0) {
            hide(id);
          }
        }
      }
    }

    editor.shared.exit_flag = false;
    function _markExit () {
      editor.shared.exit_flag = true;
    }

    function _unmarkExit () {
      editor.shared.exit_flag = false;
    }

    function _canExit () {
      return editor.shared.exit_flag;
    }

    function _buildTemplate (id, template) {
      // Load template.
      var html = $.FE.POPUP_TEMPLATES[id];
      if (typeof html == 'function') html = html.apply(editor);

      for (var nm in template) {
        if (template.hasOwnProperty(nm)) {
          html = html.replace('[_' + nm.toUpperCase() + '_]', template[nm]);
        }
      }

      return html;
    }

    function _build (id, template) {
      var html = _buildTemplate(id, template);

      var $popup = $('<div class="fr-popup' + (editor.helpers.isMobile() ? ' fr-mobile' : ' fr-desktop') +  (editor.opts.toolbarInline ? ' fr-inline' : '') + '"><span class="fr-arrow"></span>' + html + '</div>');

      if (editor.opts.theme) {
        $popup.addClass(editor.opts.theme + '-theme');
      }

      if (editor.opts.zIndex > 1) {
        editor.$tb.css('z-index', editor.opts.zIndex + 2);
      }

      if (editor.opts.direction != 'auto') {
        $popup.removeClass('fr-ltr fr-rtl').addClass('fr-' + editor.opts.direction);
      }

      $popup.find('input, textarea').attr('dir', editor.opts.direction).attr('disabled', 'disabled');

      var $container = $('body');
      $container.append($popup);
      $popup.data('container', $container);

      popups[id] = $popup;

      // Bind commands from the popup.
      editor.button.bindCommands($popup, false);

      return $popup;
    }

    function _events (id) {
      var $popup = popups[id];

      return {
        /**
         * Resize window.
         */
        _windowResize: function () {
          var inst = $popup.data('instance') || editor;

          if (!inst.helpers.isMobile() && $popup.is(':visible')) {
            inst.events.disableBlur();
            inst.popups.hide(id);
            inst.events.enableBlur();
          }
        },

        /**
         * Focus on an input.
         */
        _inputFocus: function (e) {
          var inst = $popup.data('instance') || editor;

          e.preventDefault();

          // IE workaround.
          setTimeout(function () {
            inst.events.enableBlur();
          }, 0);

          // Reposition scroll on mobile to the original one.
          if (inst.helpers.isMobile()) {
            var t = $(inst.o_win).scrollTop();
            setTimeout(function () {
              $(inst.o_win).scrollTop(t);
            }, 0);
          }
        },

        /**
         * Blur on an input.
         */
        _inputBlur: function (e) {
          var inst = $popup.data('instance') || editor;

          // Do not do blur on window change.
          if (document.activeElement != this && $(this).is(':visible')) {
            if (inst.events.blurActive()) {
              inst.events.trigger('blur');
            }

            inst.events.enableBlur();
          }
        },

        /**
         * Keydown on an input.
         */
        _inputKeydown: function (e) {
          var inst = $popup.data('instance') || editor;
          var key_code = e.which;

          // Tabbing.
          if ($.FE.KEYCODE.TAB == key_code) {
            e.preventDefault();

            var inputs = $popup.find('input, textarea, button, select').filter(':visible').not(':disabled').toArray();
            inputs.sort(function (a, b) {
              if (e.shiftKey) return $(a).attr('tabIndex') < $(b).attr('tabIndex');
              return $(a).attr('tabIndex') > $(b).attr('tabIndex');
            });

            inst.events.disableBlur();
            var idx = inputs.indexOf(this) + 1;
            if (idx == inputs.length) idx = 0;
            $(inputs[idx]).focus();
          }

          // ENTER.
          else if ($.FE.KEYCODE.ENTER == key_code) {
            if ($popup.find('.fr-submit:visible').length > 0) {
              e.preventDefault();
              e.stopPropagation();
              inst.events.disableBlur();
              inst.button.exec($popup.find('.fr-submit:visible:first'));
            }
          }

          // ESC.
          else if ($.FE.KEYCODE.ESC == key_code) {
            e.preventDefault();
            e.stopPropagation();

            if (inst.$el.find('.fr-marker')) {
              inst.events.disableBlur();
              $(this).data('skip', true);
              inst.selection.restore();
              inst.events.enableBlur();
            }

            if (isVisible(id) && $popup.find('.fr-back:visible').length) {
              inst.button.exec($popup.find('.fr-back:visible:first'))
            }
            else {
              inst.popups.hide(id);
            }

            if (inst.opts.toolbarInline) inst.toolbar.showInline(null, true);

            return false;
          }

          // Other KEY. Stop propagation to the window.
          else {
            e.stopPropagation();
          }
        },

        /**
         * Window keydown.
         */
        _windowKeydown: function (e) {
          if (!editor.core.sameInstance($popup)) return true;

          var inst = $popup.data('instance') || editor;

          var key_code = e.which;

          // ESC.
          if ($.FE.KEYCODE.ESC == key_code) {
            if (isVisible(id) && inst.opts.toolbarInline) {
              e.stopPropagation();

              if (isVisible(id) && $popup.find('.fr-back:visible').length) {
                inst.button.exec($popup.find('.fr-back:visible:first'))
              }
              else {
                inst.popups.hide(id);
                inst.toolbar.showInline(null, true);
              }
              return false;
            }
            else {
              if (isVisible(id) && $popup.find('.fr-back:visible').length) {
                inst.button.exec($popup.find('.fr-back:visible:first'))
              }
              else {
                inst.popups.hide(id);
              }
            }
          }
        },

        /**
         * Editor keydown.
         */
        _editorKeydown: function (e) {
          var inst = $popup.data('instance') || editor;

          // ESC.
          if (!inst.keys.ctrlKey(e) && e.which != $.FE.KEYCODE.ESC) {
            if (isVisible(id) && $popup.find('.fr-back:visible').length) {
              inst.button.exec($popup.find('.fr-back:visible:first'))
            }
            else {
              inst.popups.hide(id);
            }
          }
        },

        /**
         * Handling hitting the popup elements with the mouse.
         */
        _preventFocus: function (e) {
          var inst = $popup.data('instance') || editor;
          inst.events.disableBlur();

          // Get the original target.
          var originalTarget = e.originalEvent ? (e.originalEvent.target || e.originalEvent.originalTarget) : null;

          // Define the input selector.
          var input_selector = 'input, textarea, button, select, label, .fr-command';

          // Click was not made inside an input.
          if (originalTarget && !$(originalTarget).is(input_selector) && $(originalTarget).parents(input_selector).length === 0) {
            e.stopPropagation();
            return false;
          }

          // Click was made on another input inside popup. Prevent propagation of the event.
          else if (originalTarget && $(originalTarget).is(input_selector)) {
            e.stopPropagation();
          }
        },

        /**
         * Mouseup inside the editor.
         */
        _editorMouseup: function (e) {
          // Check if popup is visible and we can exit.
          if ($popup.is(':visible') && _canExit()) {
            // If we have an input focused, then disable blur.
            if ($popup.find('input:focus, textarea:focus, button:focus, select:focus').filter(':visible').length > 0) {
              editor.events.disableBlur();
            }
          }
        },

        /**
         * Mouseup on window.
         */
        _windowMouseup: function (e) {
          if (!editor.core.sameInstance($popup)) return true;

          var inst = $popup.data('instance') || editor;

          if ($popup.is(':visible') && _canExit()) {
            e.stopPropagation();
            inst.markers.remove();
            inst.popups.hide(id);

            _unmarkExit();
          }
        },

        /**
         * Placeholder effect.
         */
        _doPlaceholder: function (e) {
          var $label = $(this).next();
          if ($label.length == 0) {
            $(this).after('<label>' + $(this).attr('placeholder') + '</label>');
          }

          $(this).toggleClass('fr-not-empty', $(this).val() != '');
        },

        /**
         * Reposition popup.
         */
        _repositionPopup: function (e) {
          // No height set or toolbar inline.
          if (!(editor.opts.height || editor.opts.heightMax) || editor.opts.toolbarInline) return true;

          if (editor.$wp && isVisible(id) && $popup.parent().get(0) == $(editor.opts.scrollableContainer).get(0)) {
            // Popup top - wrapper top.
            var p_top = $popup.offset().top - editor.$wp.offset().top;

            // Wrapper height.
            var w_height = editor.$wp.outerHeight();

            if ($popup.hasClass('fr-above')) p_top += $popup.outerHeight();

            // 1. Popup top > w_height.
            // 2. Popup top + popup height < 0.
            if (p_top > w_height || p_top < 0) {
              $popup.addClass('fr-hidden');
            }
            else {
              $popup.removeClass('fr-hidden');
            }
          }
        }
      }
    }

    function _bindInstanceEvents (ev, id) {
      // Editor mouseup.
      editor.events.on('mouseup', ev._editorMouseup, true);
      if (editor.$wp) editor.events.on('keydown', ev._editorKeydown);

      // Hide all popups on blur.
      editor.events.on('blur', function (e) {
        if (areVisible()) editor.markers.remove();

        hideAll();
      });

      // Update the position of the popup.
      if (editor.$wp && !editor.helpers.isMobile()) {
        editor.events.$on(editor.$wp, 'scroll.popup' + id, ev._repositionPopup);
      }

      editor.events.on('window.keydown', ev._windowKeydown);
      editor.events.on('window.mouseup', ev._windowMouseup, true);

      popups[id].data('inst' + editor.id, true);

      editor.events.on('destroy', function () {
        if (editor.core.sameInstance(popups[id])) {
          popups[id].removeClass('fr-active').appendTo('body');
        }
      }, true)
    }

    /**
     * Create a popup.
     */
    function create (id, template) {
      var $popup = _build(id, template);

      // Build events.
      var ev = _events(id);

      _bindInstanceEvents(ev, id);

      // Input Focus / Blur / Keydown.
      editor.events.$on($popup, 'mousedown mouseup touchstart touchend touch', '*', ev._preventFocus, true);
      editor.events.$on($popup, 'focus', 'input, textarea, button, select', ev._inputFocus, true);
      editor.events.$on($popup, 'blur', 'input, textarea, button, select', ev._inputBlur, true);
      editor.events.$on($popup, 'keydown', 'input, textarea, button, select', ev._inputKeydown, true);

      // Placeholder.
      editor.events.$on($popup, 'keydown keyup change input', 'input, textarea', ev._doPlaceholder, true);

      // Toggle checkbox.
      if (editor.helpers.isIOS()) {
        editor.events.$on($popup, 'touchend', 'label', function () {
          $('#' + $(this).attr('for')).prop('checked', function (i, val) {
            return !val;
          })
        }, true);
      }

      // Window mouseup.
      editor.events.$on($(editor.o_win), 'resize', ev._windowResize, true);

      return $popup;
    }

    /**
     * Destroy.
     */
    function _destroy () {
      for (var id in popups) {
        if (popups.hasOwnProperty(id)) {
          var $popup = popups[id];
          $popup.html('').removeData().remove();
          popups[id] = null;
        }
      }

      popups = [];
    }

    /**
     * Initialization.
     */
    function _init () {
      editor.events.on('shared.destroy', _destroy, true);

      editor.events.on('window.mousedown', _markExit);
      editor.events.on('window.touchmove', _unmarkExit);

      editor.events.on('mousedown', function (e) {
        if (areVisible()) {
          e.stopPropagation();

          // Remove markers.
          editor.$el.find('.fr-marker').remove();

          // Prepare for exit.
          _markExit();

          // Disable blur.
          editor.events.disableBlur();
        }
      })
    }

    return {
      _init: _init,
      create: create,
      get: get,
      show: show,
      hide: hide,
      onHide: onHide,
      hideAll: hideAll,
      setContainer: setContainer,
      refresh: refresh,
      onRefresh: onRefresh,
      onShow: onShow,
      isVisible: isVisible,
      areVisible: areVisible
    }
  };


  $.FE.MODULES.position = function (editor) {
    /**
    * Get bounding rect around selection.
    *
    */
    function getBoundingRect () {
      var boundingRect;

      var range = editor.selection.ranges(0);
      if (range && range.collapsed && editor.selection.inEditor()) {
        var remove = false;
        if (editor.$el.find('.fr-marker').length == 0) {
          editor.selection.save();
          remove = true;
        }

        var $marker = editor.$el.find('.fr-marker:first');
        $marker.css('display', 'inline');
        $marker.css('line-height', '');
        var offset = $marker.offset();
        var height = $marker.outerHeight();
        $marker.css('display', 'none');
        $marker.css('line-height', 0);

        boundingRect = {}
        boundingRect.left = offset.left;
        boundingRect.width = 0;
        boundingRect.height = height;
        boundingRect.top = offset.top - (editor.helpers.isIOS() ? 0 : $(editor.o_win).scrollTop());
        boundingRect.right = 1;
        boundingRect.bottom = 1;
        boundingRect.ok = true;

        if (remove) editor.selection.restore();
      }
      else if (range) {
        boundingRect = range.getBoundingClientRect();
      }

      return boundingRect;
    }

    /**
     * Normalize top positioning.
     */
    function _topNormalized ($el, top, obj_height) {
      var height = $el.outerHeight();

      if (!editor.helpers.isMobile() && editor.$tb && $el.parent().get(0) != editor.$tb.get(0)) {
        // 1. Parent offset + toolbar top + toolbar height > scrollableContainer height.
        // 2. Selection doesn't go above the screen.
        var p_height = $el.parent().height() - 20 - (editor.opts.toolbarBottom ? editor.$tb.outerHeight() : 0);
        var p_offset = $el.parent().offset().top;
        var new_top = top - height - (obj_height || 0);

        if ($el.parent().get(0) == $(editor.opts.scrollableContainer).get(0)) p_offset = p_offset - $el.parent().position().top;

        var s_height = $(editor.opts.scrollableContainer).get(0).scrollHeight;
        if (p_offset + top + height > $(editor.opts.scrollableContainer).offset().top + s_height && $el.parent().offset().top + new_top > 0) {
          top = new_top;
          $el.addClass('fr-above');
        }
        else {
          $el.removeClass('fr-above');
        }
      }

      return top;
    }

    /**
     * Normalize left position.
     */
    function _leftNormalized ($el, left) {
      var width = $el.outerWidth();

      // Normalize right.
      if ($el.parent().offset().left + left + width > $(editor.opts.scrollableContainer).width() - 10) {
       left = $(editor.opts.scrollableContainer).width() - width - 10 - $el.parent().offset().left + $(editor.opts.scrollableContainer).offset().left;
      }

      // Normalize left.
      if ($el.parent().offset().left + left < $(editor.opts.scrollableContainer).offset().left) {
        left = 10 - $el.parent().offset().left + $(editor.opts.scrollableContainer).offset().left;
      }

      return left;
    }

    /**
     * Place editor below selection.
     */
    function forSelection ($el) {
      var selection_rect = getBoundingRect();

      $el.css('top', 0).css('left', 0);

      var top = selection_rect.top + selection_rect.height;
      var left = selection_rect.left + selection_rect.width / 2 - $el.outerWidth() / 2 + $(editor.o_win).scrollLeft();

      if (!editor.opts.iframe) {
        top += $(editor.o_win).scrollTop();
      }

      at(left, top, $el, selection_rect.height);
    }

    /**
     * Position element at the specified position.
     */
    function at (left, top, $el, obj_height) {
      var $container = $el.data('container');

      if ($container && $container.get(0).tagName != 'BODY') {
        if (left) left -= $container.offset().left;
        if (top) top -= $container.offset().top - $container.scrollTop();
      }

      // Apply iframe correction.
      if (editor.opts.iframe && $container && editor.$tb && $container.get(0) != editor.$tb.get(0)) {
        if (left) left += editor.$iframe.offset().left;
        if (top) top += editor.$iframe.offset().top;
      }

      var new_left = _leftNormalized($el, left);

      if (left) {
        // Set the new left.
        $el.css('left', new_left);

        // Normalize arrow.
        var $arrow = $el.find('.fr-arrow');
        if (!$arrow.data('margin-left')) $arrow.data('margin-left', editor.helpers.getPX($arrow.css('margin-left')));
        $arrow.css('margin-left', left - new_left + $arrow.data('margin-left'));
      }

      if (top) $el.css('top', _topNormalized($el, top, obj_height));
    }

    /**
     * Special case for update sticky on iOS.
     */
    function _updateIOSSticky (el) {
      var $el = $(el);
      var is_on = $el.is('.fr-sticky-on');
      var prev_top = $el.data('sticky-top');
      var scheduled_top = $el.data('sticky-scheduled');

      // Create a dummy div that we show then sticky is on.
			if (typeof prev_top == 'undefined') {
        $el.data('sticky-top', 0);
        var $dummy = $('<div class="fr-sticky-dummy" style="height: ' + $el.outerHeight() + 'px;"></div>');
			  editor.$box.prepend($dummy);
			}
      else {
        editor.$box.find('.fr-sticky-dummy').css('height', $el.outerHeight());
      }

      // Position sticky doesn't work when the keyboard is on the screen.
      if (editor.core.hasFocus() || editor.$tb.find('input:visible:focus').length > 0) {
        // Get the current scroll.
        var x_scroll = $(window).scrollTop();

        // Get the current top.
        // We make sure that we keep it within the editable box.
        var x_top = Math.min(Math.max(x_scroll - editor.$tb.parent().offset().top, 0), editor.$tb.parent().outerHeight() - $el.outerHeight());

        // Not the same top and different than the already scheduled.
        if (x_top != prev_top && x_top != scheduled_top) {
          // Clear any too soon change to avoid flickering.
          clearTimeout($el.data('sticky-timeout'));

          // Store the current scheduled top.
          $el.data('sticky-scheduled', x_top);

          // Hide the toolbar for a rich experience.
          if ($el.outerHeight() < x_scroll - editor.$tb.parent().offset().top) {
            $el.addClass('fr-opacity-0');
          }

          // Set the timeout for changing top.
          // Based on the test 100ms seems to be the best timeout.
          $el.data('sticky-timeout', setTimeout(function () {
            // Get the current top.
            var c_scroll = $(window).scrollTop();
            var c_top = Math.min(Math.max(c_scroll - editor.$tb.parent().offset().top, 0), editor.$tb.parent().outerHeight() - $el.outerHeight());

            if (c_top > 0 && editor.$tb.parent().get(0).tagName == 'BODY') c_top += editor.$tb.parent().position().top;

            // Don't update if it is not different than the prev top.
            if (c_top != prev_top) {
              $el.css('top', Math.max(c_top, 0));

              $el.data('sticky-top', c_top);
              $el.data('sticky-scheduled', c_top);
            }

            // Show toolbar.
            $el.removeClass('fr-opacity-0');
          }, 100));
        }

        // Turn on sticky mode.
        if (!is_on) {
          $el.css('top', '0');
          $el.width(editor.$tb.parent().width());
          $el.addClass('fr-sticky-on');
          editor.$box.addClass('fr-sticky-box');
        }
      }

      // Turn off sticky mode.
      else {
        clearTimeout($(el).css('sticky-timeout'));
        $el.css('top', '0');
        $el.css('position', '');
        $el.width('');
        $el.data('sticky-top', 0);
        $el.removeClass('fr-sticky-on');
        editor.$box.removeClass('fr-sticky-box');
      }
    }

    /**
     * Update sticky location for browsers that don't support sticky.
     * https://github.com/filamentgroup/fixed-sticky
     *
     * The MIT License (MIT)
     *
     * Copyright (c) 2013 Filament Group
     */
    function _updateSticky (el) {
      if( !el.offsetWidth ) { return; }

			var el_top;
			var el_bottom;
      var $el = $(el);
      var height = $el.outerHeight();

      var position = $el.data('sticky-position');

      // Viewport height.
      var viewport_height = $(editor.opts.scrollableContainer == 'body' ? editor.o_win : editor.opts.scrollableContainer).outerHeight();

      var scrollable_top = 0;
      var scrollable_bottom = 0;
      if (editor.opts.scrollableContainer !== 'body') {
        scrollable_top = $(editor.opts.scrollableContainer).offset().top;
        scrollable_bottom = $(editor.o_win).outerHeight() - scrollable_top - viewport_height;
      }

      var offset_top = editor.opts.scrollableContainer == 'body' ? $(editor.o_win).scrollTop() : scrollable_top;

			var is_on = $el.is('.fr-sticky-on');

      // Decide parent.
      if (!$el.data('sticky-parent')) {
        $el.data('sticky-parent', $el.parent());
      }
      var $parent = $el.data('sticky-parent');
		  var parent_top = $parent.offset().top;
			var parent_height = $parent.outerHeight();


			if (!$el.data('sticky-offset')) {
				$el.data('sticky-offset', true);
				$el.after('<div class="fr-sticky-dummy" style="height: ' + height + 'px;"></div>');
			}

      // Detect position placement.
      if (!position) {
				// Some browsers require fixed/absolute to report accurate top/left values.
				var skip_setting_fixed = $el.css('top') !== 'auto' || $el.css('bottom') !== 'auto';

        // Set to position fixed for a split of second.
				if(!skip_setting_fixed) {
					$el.css('position', 'fixed');
				}

        // Find position.
				position = {
					top: $el.hasClass('fr-top'),
					bottom: $el.hasClass('fr-bottom')
				};

        // Remove position fixed.
				if(!skip_setting_fixed) {
					$el.css('position', '');
				}

        // Store position.
				$el.data('sticky-position', position);

        $el.data('top', $el.hasClass('fr-top') ? $el.css('top') : 'auto');
        $el.data('bottom', $el.hasClass('fr-bottom') ? $el.css('bottom') : 'auto');
  		}

      // Detect if is OK to fix at the top.
			var isFixedToTop = function () {
				// 1. Top condition.
        // 2. Bottom condition.
				return parent_top <  offset_top + el_top &&
                parent_top + parent_height - height >= offset_top + el_top;
			}

      // Detect if it is OK to fix at the bottom.
			var isFixedToBottom = function () {
				return parent_top + height < offset_top + viewport_height - el_bottom &&
        				parent_top + parent_height > offset_top + viewport_height - el_bottom ;
			}

			el_top = editor.helpers.getPX($el.data('top'));
			el_bottom = editor.helpers.getPX($el.data('bottom'));

      var at_top = (position.top && isFixedToTop());
      var at_bottom = (position.bottom && isFixedToBottom());

      // Should be fixed.
			if (at_top || at_bottom) {
        $el.css('width', $parent.width() + 'px');

        if (!is_on) {
					$el.addClass('fr-sticky-on')
          $el.removeClass('fr-sticky-off');

          if ($el.css('top')) {
            if ($el.data('top') != 'auto') {
              $el.css('top', editor.helpers.getPX($el.data('top')) + scrollable_top);
            }
            else {
              $el.data('top', 'auto');
            }
          }

          if ($el.css('bottom')) {
            if ($el.data('bottom') != 'auto') {
              $el.css('bottom', editor.helpers.getPX($el.data('bottom')) + scrollable_bottom);
            }
            else {
              $el.css('bottom', 'auto');
            }
          }
				}
			}

      // Shouldn't be fixed.
      else {
				if (!$el.hasClass('fr-sticky-off')) {
          // Reset.
          $el.width('');
          $el.removeClass('fr-sticky-on');
          $el.addClass('fr-sticky-off');

          if ($el.css('top') && $el.css('top') != 'auto') {
            $el.css('top', 0);
          }

          if ($el.css('bottom')) $el.css('bottom', 0);
				}
      }
    }

   /**
    * Test if browser supports sticky.
    * https://github.com/filamentgroup/fixed-sticky
    *
    * The MIT License (MIT)
    *
    * Copyright (c) 2013 Filament Group
    */
    function _testSticky () {
      var el = document.createElement('test');
  		var mStyle = el.style;

			mStyle.cssText = 'position:' + [ '-webkit-', '-moz-', '-ms-', '-o-', '' ].join('sticky; position:') + ' sticky;';

  		return mStyle['position'].indexOf('sticky') !== -1 && !editor.helpers.isIOS() && !editor.helpers.isAndroid();
    }

    /**
     * Initialize sticky position.
     */
    function _initSticky () {
      if (!_testSticky()) {
        editor._stickyElements = [];

        // iOS special case.
        if (editor.helpers.isIOS()) {
          // Use an animation frame to make sure we're always OK with the updates.
          var animate = function () {
            editor.helpers.requestAnimationFrame()(animate);

            for (var i = 0; i < editor._stickyElements.length; i++) {
              _updateIOSSticky(editor._stickyElements[i]);
            }
          };
          animate();

          // Hide toolbar on touchmove. This is very useful on iOS versions < 8.
          editor.events.$on($(editor.o_win), 'scroll', function () {
            if (editor.core.hasFocus()) {
              for (var i = 0; i < editor._stickyElements.length; i++) {
                var $el = $(editor._stickyElements[i]);
                var $parent = $el.parent();
                var c_scroll = $(window).scrollTop();

                if ($el.outerHeight() < c_scroll - $parent.offset().top) {
                  $el.addClass('fr-opacity-0');
                  $el.data('sticky-top', -1);
                  $el.data('sticky-scheduled', -1);
                }
              }
            }
          }, true);
        }

        // Default case. Do the updates on scroll.
        else {
          editor.events.$on($(editor.opts.scrollableContainer == 'body' ? editor.o_win : editor.opts.scrollableContainer), 'scroll', refresh, true);
          editor.events.$on($(editor.o_win), 'resize', refresh, true);

          editor.events.on('initialized', refresh);
          editor.events.on('focus', refresh);

          editor.events.$on($(editor.o_win), 'resize', 'textarea', refresh, true);
        }
      }

      editor.events.on('destroy', function (e) {
        editor._stickyElements = [];
      });
    }

    function refresh () {
      for (var i = 0; i < editor._stickyElements.length; i++) {
        _updateSticky(editor._stickyElements[i]);
      }
    }

    /**
     * Mark element as sticky.
     */
    function addSticky ($el) {
      $el.addClass('fr-sticky');

      if (editor.helpers.isIOS()) $el.addClass('fr-sticky-ios');

      if (!_testSticky()) {
        editor._stickyElements.push($el.get(0));
      }
    }

    function _init () {
      _initSticky();
    }

    return {
      _init: _init,
      forSelection: forSelection,
      addSticky: addSticky,
      refresh: refresh,
      at: at,
      getBoundingRect: getBoundingRect
    }
  };


  $.FE.MODULES.refresh = function (editor) {
    function undo ($btn) {
      $btn.toggleClass('fr-disabled', !editor.undo.canDo());
    }

    function redo ($btn) {
      $btn.toggleClass('fr-disabled', !editor.undo.canRedo());
    }

    function indent ($btn) {
      if ($btn.hasClass('fr-no-refresh')) return false;

      var blocks = editor.selection.blocks();
      for (var i = 0; i < blocks.length; i++) {
        var p_node = blocks[i].previousSibling;
        while (p_node && p_node.nodeType == Node.TEXT_NODE && p_node.textContent.length === 0) {
          p_node = p_node.previousSibling;
        }
        if (blocks[i].tagName == 'LI' && !p_node) {
          $btn.addClass('fr-disabled');
        }
        else {
          $btn.removeClass('fr-disabled');
          return true;
        }
      }
    }

    function outdent ($btn) {
      if ($btn.hasClass('fr-no-refresh')) return false;

      var blocks = editor.selection.blocks();
      for (var i = 0; i < blocks.length; i++) {
        var prop = (editor.opts.direction == 'rtl' || $(blocks[i]).css('direction') == 'rtl') ? 'margin-right' : 'margin-left';

        if (blocks[i].tagName == 'LI' || blocks[i].parentNode.tagName == 'LI') {
          $btn.removeClass('fr-disabled');
          return true;
        }

        if (editor.helpers.getPX($(blocks[i]).css(prop)) > 0) {
          $btn.removeClass('fr-disabled');
          return true;
        }
      }

      $btn.addClass('fr-disabled');
    }

    return {
      undo: undo,
      redo: redo,
      outdent: outdent,
      indent: indent
    }
  };


  $.extend($.FE.DEFAULTS, {
    editInPopup: false
  });

  $.FE.MODULES.textEdit = function (editor) {
    function _initPopup () {
      // Image buttons.
      var txt = '<div id="fr-text-edit-' + editor.id + '" class="fr-layer fr-text-edit-layer"><div class="fr-input-line"><input type="text" placeholder="' + editor.language.translate('Text') + '" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="updateText" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>'

      var template = {
        edit: txt
      };

      var $popup = editor.popups.create('text.edit', template);
    }

    function _showPopup () {
      var $popup = editor.popups.get('text.edit');

      var text;
      if (editor.$el.prop('tagName') === 'INPUT') {
        text = editor.$el.attr('placeholder');
      }
      else {
        text = editor.$el.text();
      }

      $popup.find('input').val(text).trigger('change');
      editor.popups.setContainer('text.edit', $('body'));
      editor.popups.show('text.edit', editor.$el.offset().left + editor.$el.outerWidth() / 2, editor.$el.offset().top + editor.$el.outerHeight(), editor.$el.outerHeight());
    }

    function _initEvents () {
      // Show edit popup.
      editor.events.$on(editor.$el, editor._mouseup, function (e) {
        setTimeout (function () {
          _showPopup();
        }, 10);
      })
    }

    function update () {
      var $popup = editor.popups.get('text.edit');

      var new_text = $popup.find('input').val();

      if (new_text.length == 0) new_text = editor.opts.placeholderText;

      if (editor.$el.prop('tagName') === 'INPUT') {
        editor.$el.attr('placeholder', new_text);
      }
      else {
        editor.$el.text(new_text);
      }

      editor.events.trigger('contentChanged');

      editor.popups.hide('text.edit');
    }

    /**
     * Initialize.
     */
    function _init () {
      if (editor.opts.editInPopup) {
        _initPopup();
        _initEvents();
      }
    }

    return {
      _init: _init,
      update: update
    }
  };

  $.FE.RegisterCommand('updateText', {
    focus: false,
    undo: false,
    callback: function () {
      this.textEdit.update();
    }
  })


  $.FE.MODULES.tooltip = function (editor) {
    function hide () {
      // Position fixed for: https://github.com/froala/wysiwyg-editor/issues/1247.
      if (editor.$tooltip) editor.$tooltip.removeClass('fr-visible').css('left', '-3000px').css('position', 'fixed');
    }

    function to ($el, above) {
      if (!$el.data('title')) {
        $el.data('title', $el.attr('title'));
      }

      if (!$el.data('title')) return false;

      if (!editor.$tooltip) _init();

      $el.removeAttr('title');
      editor.$tooltip.text($el.data('title'));
      editor.$tooltip.addClass('fr-visible');

      var left = $el.offset().left + ($el.outerWidth() - editor.$tooltip.outerWidth()) / 2;

      // Normalize screen position.
      if (left < 0) left = 0;
      if (left + editor.$tooltip.outerWidth() > $(editor.o_win).width()) {
        left = $(editor.o_win).width() - editor.$tooltip.outerWidth();
      }

      if (typeof above == 'undefined') above = editor.opts.toolbarBottom;
      var top = !above ? $el.offset().top + $el.outerHeight() : $el.offset().top - editor.$tooltip.height();

      editor.$tooltip.css('position', '');
      editor.$tooltip.css('left', left);
      editor.$tooltip.css('top', top);
    }

    function bind ($el, selector, above) {
      if (!editor.helpers.isMobile()) {
        editor.events.$on($el, 'mouseenter', selector, function (e) {
          if (!$(e.currentTarget).hasClass('fr-disabled') && !editor.edit.isDisabled()) {
            to($(e.currentTarget), above);
          }
        }, true);

        editor.events.$on($el, 'mouseleave ' + editor._mousedown + ' ' + editor._mouseup, selector, function (e) {
          hide();
        }, true);
      }
    }

    function _init () {
      if (!editor.helpers.isMobile()) {
        if (!editor.shared.$tooltip) {
          editor.shared.$tooltip = $('<div class="fr-tooltip"></div>');

          editor.$tooltip = editor.shared.$tooltip;

          if (editor.opts.theme) {
            editor.$tooltip.addClass(editor.opts.theme + '-theme');
          }

          $(editor.o_doc).find('body').append(editor.$tooltip);
        }
        else {
          editor.$tooltip = editor.shared.$tooltip;
        }

        editor.events.on('shared.destroy', function () {
          editor.$tooltip.html('').removeData().remove();
          editor.$tooltip = null;
        }, true);
      }
    }

    return {
      hide: hide,
      to: to,
      bind: bind
    }
  };


  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    toolbarBottom: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html', 'applyFormat', 'removeFormat'],
    toolbarButtonsXS: ['bold', 'italic', 'fontFamily', 'fontSize', '|', 'undo', 'redo'],
    toolbarButtonsSM: ['bold', 'italic', 'underline', '|', 'fontFamily', 'fontSize', 'insertLink', 'insertImage', 'table', '|', 'undo', 'redo'],
    toolbarButtonsMD: ['fullscreen', 'bold', 'italic', 'underline', 'fontFamily', 'fontSize', 'color', 'paragraphStyle', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting'],
    toolbarContainer: null,
    toolbarInline: false,
    toolbarSticky: true,
    toolbarStickyOffset: 0,
    toolbarVisibleWithoutSelection: false
  });

  $.FE.MODULES.toolbar = function (editor) {
    var _document, _window;

    // Create a button map for each screen size.
    var _buttons_map = [];
    _buttons_map[$.FE.XS] = editor.opts.toolbarButtonsXS || editor.opts.toolbarButtons;
    _buttons_map[$.FE.SM] = editor.opts.toolbarButtonsSM || editor.opts.toolbarButtons;
    _buttons_map[$.FE.MD] = editor.opts.toolbarButtonsMD || editor.opts.toolbarButtons;
    _buttons_map[$.FE.LG] = editor.opts.toolbarButtons;

    function _addOtherButtons (buttons, toolbarButtons) {
      for (var i = 0; i < toolbarButtons.length; i++) {
        if (toolbarButtons[i] != '-' && toolbarButtons[i] != '|' && buttons.indexOf(toolbarButtons[i]) < 0) {
          buttons.push(toolbarButtons[i]);
        }
      }
    }

    /**
     * Add buttons to the toolbar.
     */
    function _addButtons () {
      var _buttons = $.merge([], _screenButtons());
      _addOtherButtons(_buttons, editor.opts.toolbarButtonsXS || []);
      _addOtherButtons(_buttons, editor.opts.toolbarButtonsSM || []);
      _addOtherButtons(_buttons, editor.opts.toolbarButtonsMD || []);
      _addOtherButtons(_buttons, editor.opts.toolbarButtons);

      for (var i = _buttons.length - 1; i >= 0; i--) {
        if (_buttons[i] != '-' && _buttons[i] != '|' && _buttons.indexOf(_buttons[i]) < i) {
          _buttons.splice(i, 1);
        }
      }

      var buttons_list = editor.button.buildList(_buttons, _screenButtons());
      editor.$tb.append(buttons_list);
      editor.button.bindCommands(editor.$tb);
    }

    /**
     * The buttons that should be visible on the current screen size.
     */
    function _screenButtons () {
      var screen_size = editor.helpers.screenSize();
      return _buttons_map[screen_size];
    }

    function _showScreenButtons () {
      var c_buttons = _screenButtons();

      // Remove separator from toolbar.
      editor.$tb.find('.fr-separator').remove();

      // Hide all buttons.
      editor.$tb.find('> .fr-command').addClass('fr-hidden');

      // Reorder buttons.
      for (var i = 0; i < c_buttons.length; i++) {
        if (c_buttons[i] == '|' || c_buttons[i] == '-') {
          editor.$tb.append(editor.button.buildList([c_buttons[i]]));
        }
        else {
          var $btn = editor.$tb.find('> .fr-command[data-cmd="' + c_buttons[i] + '"]');
          var $dropdown = null;
          if ($btn.next().hasClass('fr-dropdown-menu')) $dropdown = $btn.next();

          $btn.removeClass('fr-hidden').appendTo(editor.$tb);
          if ($dropdown) $dropdown.appendTo(editor.$tb);
        }
      }
    }

    /**
     * Set the buttons visibility based on screen size.
     */
    function _setVisibility () {
      editor.events.$on($(editor.o_win), 'resize', _showScreenButtons, true);
      editor.events.$on($(editor.o_win), 'orientationchange', _showScreenButtons, true);
    }

    function showInline (e, force) {
      setTimeout(function () {
        if (e && e.which == $.FE.KEYCODE.ESC) {
          // Nothing.
        }
        else if (editor.selection.inEditor() && editor.core.hasFocus() && !editor.popups.areVisible()) {
          if ((editor.opts.toolbarVisibleWithoutSelection && e && e.type != 'keyup') || (!editor.selection.isCollapsed() && !editor.keys.isIME()) || force) {
            editor.$tb.data('instance', editor);

            // Check if we should actually show the toolbar.
            if (editor.events.trigger('toolbar.show', [e]) == false) return false;

            if (!editor.opts.toolbarContainer) {
              editor.position.forSelection(editor.$tb);
            }

            editor.$tb.show();
          }
        }
      }, 0);
    }

    function hide (e) {
      // Check if we should actually hide the toolbar.
      if (editor.events.trigger('toolbar.hide') == false) return false;

      editor.$tb.hide();
    }

    function show () {
      // Check if we should actually hide the toolbar.
      if (editor.events.trigger('toolbar.show') == false) return false;

      editor.$tb.show();
    }

    /**
     * Set the events for show / hide toolbar.
     */
    function _initInlineBehavior () {
      // Window mousedown.
      editor.events.on('window.mousedown', hide);

      // Element keydown.
      editor.events.on('keydown', hide);

      // Element blur.
      editor.events.on('blur', hide);

      // Window mousedown.
      editor.events.on('window.mouseup', showInline);

      if (editor.helpers.isMobile()) {
        if (!editor.helpers.isIOS()) {
          editor.events.on('window.touchend', showInline);

          if (editor.browser.mozilla) {
            setInterval(showInline, 200);
          }
        }
      }
      else {
        editor.events.on('window.keyup', showInline);
      }

      // Hide editor on ESC.
      editor.events.on('keydown', function (e) {
        if (e && e.which == $.FE.KEYCODE.ESC) {
          hide();
        }
      });

      editor.events.$on(editor.$wp, 'scroll.toolbar', showInline);
      editor.events.on('commands.after', showInline);

      if (editor.helpers.isMobile()) {
        editor.events.$on(editor.$doc, 'selectionchange', showInline);
        editor.events.$on(editor.$doc, 'orientationchange', showInline);
      }
    }


    function _initPositioning () {
      // Toolbar is inline.
      if (editor.opts.toolbarInline) {
        // Mobile should handle this as regular.
        $(editor.opts.scrollableContainer).append(editor.$tb);

        // Add toolbar to body.
        editor.$tb.data('container', $(editor.opts.scrollableContainer));

        // Add inline class.
        editor.$tb.addClass('fr-inline');

        // Add arrow.
        editor.$tb.prepend('<span class="fr-arrow"></span>')

        // Init mouse behavior.
        _initInlineBehavior();

        editor.opts.toolbarBottom = false;
      }

      // Toolbar is normal.
      else {
        // Won't work on iOS.
        if (editor.opts.toolbarBottom && !editor.helpers.isIOS()) {
          editor.$box.append(editor.$tb);
          editor.$tb.addClass('fr-bottom');
          editor.$box.addClass('fr-bottom');
        }
        else {
          editor.opts.toolbarBottom = false;
          editor.$box.prepend(editor.$tb);
          editor.$tb.addClass('fr-top');
          editor.$box.addClass('fr-top');
        }

        editor.$tb.addClass('fr-basic');

        if (editor.opts.toolbarSticky) {
          if (editor.opts.toolbarStickyOffset) {
            if (editor.opts.toolbarBottom) {
              editor.$tb.css('bottom', editor.opts.toolbarStickyOffset);
            }
            else {
              editor.$tb.css('top', editor.opts.toolbarStickyOffset);
            }
          }

          editor.position.addSticky(editor.$tb);
        }
      }
    }

    /**
     * Destroy.
     */
    function _sharedDestroy () {
      editor.$tb.html('').removeData().remove();
      editor.$tb = null;
    }

    function _destroy () {
      editor.$box.removeClass('fr-top fr-bottom fr-inline fr-basic');
      editor.$box.find('.fr-sticky-dummy').remove();
    }

    function _setDefaults () {
      if (editor.opts.theme) {
        editor.$tb.addClass(editor.opts.theme + '-theme');
      }

      if (editor.opts.zIndex > 1) {
        editor.$tb.css('z-index', editor.opts.zIndex + 1);
      }

      // Set direction.
      if (editor.opts.direction != 'auto') {
        editor.$tb.removeClass('fr-ltr fr-rtl').addClass('fr-' + editor.opts.direction);
      }

      // Mark toolbar for desktop / mobile.
      if (!editor.helpers.isMobile()) {
        editor.$tb.addClass('fr-desktop');
      }
      else {
        editor.$tb.addClass('fr-mobile');
      }

      // Set the toolbar specific position inline / normal.
      if (!editor.opts.toolbarContainer) {
        _initPositioning();
      }
      else {
        if (editor.opts.toolbarInline) {
          _initInlineBehavior();
          hide();
        }

        if (editor.opts.toolbarBottom) editor.$tb.addClass('fr-bottom');
        else editor.$tb.addClass('fr-top');
      }

      // Set documetn and window for toolbar.
      _document = editor.$tb.get(0).ownerDocument;
      _window = 'defaultView' in _document ? _document.defaultView : _document.parentWindow;

      // Add buttons to the toolbar.
      // Set their visibility for different screens.
      // Asses commands to the butttons.
      _addButtons();
      _setVisibility();

      // Make sure we don't trigger blur.
      editor.events.$on(editor.$tb, editor._mousedown + ' ' + editor._mouseup, function (e) {
        var originalTarget = e.originalEvent ? (e.originalEvent.target || e.originalEvent.originalTarget) : null;
        if (originalTarget && originalTarget.tagName != 'INPUT' && !editor.edit.isDisabled()) {
          e.stopPropagation();
          e.preventDefault();
          return false;
        }
      }, true);
    }

    /**
     * Initialize
     */
    var tb_exists = false;
    function _init () {
      if (!editor.$wp) return false;

      // Container for toolbar.
      if (editor.opts.toolbarContainer) {
        // Shared toolbar.
        if (!editor.shared.$tb) {
          editor.shared.$tb = $('<div class="fr-toolbar"></div>');
          editor.$tb = editor.shared.$tb;
          $(editor.opts.toolbarContainer).append(editor.$tb);
          _setDefaults();
          editor.$tb.data('instance', editor);
        }
        else {
          editor.$tb = editor.shared.$tb;

          if (editor.opts.toolbarInline) _initInlineBehavior();
        }

        // On focus set the current instance.
        editor.events.on('focus', function () {
          editor.$tb.data('instance', editor);
        }, true);

        editor.opts.toolbarInline = false;
      }
      else {
        // Inline toolbar.
        if (editor.opts.toolbarInline) {
          // Update box.
          editor.$box.addClass('fr-inline');

          // Check for shared toolbar.
          if (!editor.shared.$tb) {
            editor.shared.$tb = $('<div class="fr-toolbar"></div>');
            editor.$tb = editor.shared.$tb;
            _setDefaults();
          }
          else {
            editor.$tb = editor.shared.$tb;

            // Init mouse behavior.
            _initInlineBehavior();
          }
        }
        else {
          editor.$box.addClass('fr-basic');
          editor.$tb = $('<div class="fr-toolbar"></div>');
          _setDefaults();

          editor.$tb.data('instance', editor);
        }
      }

      // Destroy.
      editor.events.on('destroy', _destroy, true);
      editor.events.on(!editor.opts.toolbarInline ? 'destroy' : 'shared.destroy', _sharedDestroy, true);
    }

    var disabled = false;
    function disable () {
      if (!disabled && editor.$tb) {
        editor.$tb.find('> .fr-command').addClass('fr-disabled fr-no-refresh');
        disabled = true;
      }
    }

    function enable () {
      if (disabled && editor.$tb) {
        editor.$tb.find('> .fr-command').removeClass('fr-disabled fr-no-refresh');
        disabled = false;
      }

      editor.button.bulkRefresh();
    }

    return {
      _init: _init,
      hide: hide,
      show: show,
      showInline: showInline,
      disable: disable,
      enable: enable
    }
  };




  'use strict';

  $.FE.PLUGINS.align = function (editor) {
    function apply (val) {
      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true, true, true);
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

  $.FE.DefineIcon('align', { NAME: 'align-left' });
  $.FE.DefineIcon('align-left', { NAME: 'align-left' });
  $.FE.DefineIcon('align-right', { NAME: 'align-right' });
  $.FE.DefineIcon('align-center', { NAME: 'align-center' });
  $.FE.DefineIcon('align-justify', { NAME: 'align-justify' });
  $.FE.RegisterCommand('align', {
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
      var options =  $.FE.COMMANDS.align.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command fr-title" data-cmd="align" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.icon.create('align-' + val) + '</a></li>';
        }
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
    },
    plugin: 'align'
  })


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    charCounterMax: -1,
    charCounterCount: true
  });


  $.FE.PLUGINS.charCounter = function (editor) {
    var $counter;

    /**
     * Get the char number.
     */
    function count () {
      return editor.$el.text().length;
    }

    /**
     * Check chars on typing.
     */
    function _checkCharNumber (e) {
      // Continue if infinite characters;
      if (editor.opts.charCounterMax < 0) return true;

      // Continue if enough characters.
      if (count() < editor.opts.charCounterMax) return true;

      // Stop if the key will produce a new char.
      var keyCode = e.which;
      if (!editor.keys.ctrlKey(e) && editor.keys.isCharacter(keyCode)) {
        e.preventDefault();
        e.stopPropagation();
        editor.events.trigger('charCounter.exceeded');
        return false;
      }

      return true;
    }

    /**
     * Check chars on paste.
     */
    function _checkCharNumberOnPaste (html) {
      if (editor.opts.charCounterMax < 0) return html;

      var len = $('<div>').html(html).text().length;
      if (len + count() <= editor.opts.charCounterMax) return html;

      editor.events.trigger('charCounter.exceeded');

      return '';
    }

    /**
     * Update the char counter.
     */
    function _updateCharNumber () {
      if (editor.opts.charCounterCount) {
        var chars = count() + (editor.opts.charCounterMax > 0 ?  '/' + editor.opts.charCounterMax : '');

        $counter.text(chars);

        if (editor.opts.toolbarBottom) {
          $counter.css('margin-bottom', editor.$tb.outerHeight(true))
        }

        // Scroll size correction.
        var scroll_size = editor.$wp.get(0).offsetWidth - editor.$wp.get(0).clientWidth;
        if (scroll_size >= 0) {
          if (editor.opts.direction == 'rtl') {
            $counter.css('margin-left', scroll_size);
          }
          else {
            $counter.css('margin-right', scroll_size);
          }
        }
      }
    }

    /*
     * Initialize.
     */
    function _init () {
      if (!editor.$wp) return false;

      if (!editor.opts.charCounterCount) return false;

      $counter = $('<span class="fr-counter"></span>');
      $counter.css('bottom', editor.$wp.css('border-bottom-width'));
      editor.$box.append($counter);

      editor.events.on('keydown', _checkCharNumber, true);
      editor.events.on('paste.afterCleanup', _checkCharNumberOnPaste);
      editor.events.on('keyup contentChanged', function () {
        editor.events.trigger('charCounter.update');
      });

      editor.events.on('charCounter.update', _updateCharNumber);
      editor.events.trigger('charCounter.update');

      editor.events.on('destroy', function () {
        $(editor.o_win).off('resize.char' + editor.id);
        $counter.removeData().remove();
        $counter = null;
      });
    }

    return {
      _init: _init,
      count: count
    }
  }


  'use strict';

  $.FE.PLUGINS.codeBeautifier = function () {
    /**
     * HTML BEAUTIFIER
     *
     * LICENSE: The MIT License (MIT)
     *
     * Written by Nochum Sossonko, (nsossonko@hotmail.com)
     *
     * Based on code initially developed by: Einar Lielmanis, <einar@jsbeautifier.org>
     * http://jsbeautifier.org/
     *
     */
    /* jshint ignore:start */
    /* jscs:disable */


    var acorn = {};
    (function(exports) {
        /* jshint curly: false */
        // This section of code is taken from acorn.
        //
        // Acorn was written by Marijn Haverbeke and released under an MIT
        // license. The Unicode regexps (for identifiers and whitespace) were
        // taken from [Esprima](http://esprima.org) by Ariya Hidayat.
        //
        // Git repositories for Acorn are available at
        //
        //     http://marijnhaverbeke.nl/git/acorn
        //     https://github.com/marijnh/acorn.git

        // ## Character categories

        // Big ugly regular expressions that match characters in the
        // whitespace, identifier, and identifier-start categories. These
        // are only applied when a character is found to actually have a
        // code point above 128.

        var nonASCIIwhitespace = /[\u1680\u180e\u2000-\u200a\u202f\u205f\u3000\ufeff]/; // jshint ignore:line
        var nonASCIIidentifierStartChars = "\xaa\xb5\xba\xc0-\xd6\xd8-\xf6\xf8-\u02c1\u02c6-\u02d1\u02e0-\u02e4\u02ec\u02ee\u0370-\u0374\u0376\u0377\u037a-\u037d\u0386\u0388-\u038a\u038c\u038e-\u03a1\u03a3-\u03f5\u03f7-\u0481\u048a-\u0527\u0531-\u0556\u0559\u0561-\u0587\u05d0-\u05ea\u05f0-\u05f2\u0620-\u064a\u066e\u066f\u0671-\u06d3\u06d5\u06e5\u06e6\u06ee\u06ef\u06fa-\u06fc\u06ff\u0710\u0712-\u072f\u074d-\u07a5\u07b1\u07ca-\u07ea\u07f4\u07f5\u07fa\u0800-\u0815\u081a\u0824\u0828\u0840-\u0858\u08a0\u08a2-\u08ac\u0904-\u0939\u093d\u0950\u0958-\u0961\u0971-\u0977\u0979-\u097f\u0985-\u098c\u098f\u0990\u0993-\u09a8\u09aa-\u09b0\u09b2\u09b6-\u09b9\u09bd\u09ce\u09dc\u09dd\u09df-\u09e1\u09f0\u09f1\u0a05-\u0a0a\u0a0f\u0a10\u0a13-\u0a28\u0a2a-\u0a30\u0a32\u0a33\u0a35\u0a36\u0a38\u0a39\u0a59-\u0a5c\u0a5e\u0a72-\u0a74\u0a85-\u0a8d\u0a8f-\u0a91\u0a93-\u0aa8\u0aaa-\u0ab0\u0ab2\u0ab3\u0ab5-\u0ab9\u0abd\u0ad0\u0ae0\u0ae1\u0b05-\u0b0c\u0b0f\u0b10\u0b13-\u0b28\u0b2a-\u0b30\u0b32\u0b33\u0b35-\u0b39\u0b3d\u0b5c\u0b5d\u0b5f-\u0b61\u0b71\u0b83\u0b85-\u0b8a\u0b8e-\u0b90\u0b92-\u0b95\u0b99\u0b9a\u0b9c\u0b9e\u0b9f\u0ba3\u0ba4\u0ba8-\u0baa\u0bae-\u0bb9\u0bd0\u0c05-\u0c0c\u0c0e-\u0c10\u0c12-\u0c28\u0c2a-\u0c33\u0c35-\u0c39\u0c3d\u0c58\u0c59\u0c60\u0c61\u0c85-\u0c8c\u0c8e-\u0c90\u0c92-\u0ca8\u0caa-\u0cb3\u0cb5-\u0cb9\u0cbd\u0cde\u0ce0\u0ce1\u0cf1\u0cf2\u0d05-\u0d0c\u0d0e-\u0d10\u0d12-\u0d3a\u0d3d\u0d4e\u0d60\u0d61\u0d7a-\u0d7f\u0d85-\u0d96\u0d9a-\u0db1\u0db3-\u0dbb\u0dbd\u0dc0-\u0dc6\u0e01-\u0e30\u0e32\u0e33\u0e40-\u0e46\u0e81\u0e82\u0e84\u0e87\u0e88\u0e8a\u0e8d\u0e94-\u0e97\u0e99-\u0e9f\u0ea1-\u0ea3\u0ea5\u0ea7\u0eaa\u0eab\u0ead-\u0eb0\u0eb2\u0eb3\u0ebd\u0ec0-\u0ec4\u0ec6\u0edc-\u0edf\u0f00\u0f40-\u0f47\u0f49-\u0f6c\u0f88-\u0f8c\u1000-\u102a\u103f\u1050-\u1055\u105a-\u105d\u1061\u1065\u1066\u106e-\u1070\u1075-\u1081\u108e\u10a0-\u10c5\u10c7\u10cd\u10d0-\u10fa\u10fc-\u1248\u124a-\u124d\u1250-\u1256\u1258\u125a-\u125d\u1260-\u1288\u128a-\u128d\u1290-\u12b0\u12b2-\u12b5\u12b8-\u12be\u12c0\u12c2-\u12c5\u12c8-\u12d6\u12d8-\u1310\u1312-\u1315\u1318-\u135a\u1380-\u138f\u13a0-\u13f4\u1401-\u166c\u166f-\u167f\u1681-\u169a\u16a0-\u16ea\u16ee-\u16f0\u1700-\u170c\u170e-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176c\u176e-\u1770\u1780-\u17b3\u17d7\u17dc\u1820-\u1877\u1880-\u18a8\u18aa\u18b0-\u18f5\u1900-\u191c\u1950-\u196d\u1970-\u1974\u1980-\u19ab\u19c1-\u19c7\u1a00-\u1a16\u1a20-\u1a54\u1aa7\u1b05-\u1b33\u1b45-\u1b4b\u1b83-\u1ba0\u1bae\u1baf\u1bba-\u1be5\u1c00-\u1c23\u1c4d-\u1c4f\u1c5a-\u1c7d\u1ce9-\u1cec\u1cee-\u1cf1\u1cf5\u1cf6\u1d00-\u1dbf\u1e00-\u1f15\u1f18-\u1f1d\u1f20-\u1f45\u1f48-\u1f4d\u1f50-\u1f57\u1f59\u1f5b\u1f5d\u1f5f-\u1f7d\u1f80-\u1fb4\u1fb6-\u1fbc\u1fbe\u1fc2-\u1fc4\u1fc6-\u1fcc\u1fd0-\u1fd3\u1fd6-\u1fdb\u1fe0-\u1fec\u1ff2-\u1ff4\u1ff6-\u1ffc\u2071\u207f\u2090-\u209c\u2102\u2107\u210a-\u2113\u2115\u2119-\u211d\u2124\u2126\u2128\u212a-\u212d\u212f-\u2139\u213c-\u213f\u2145-\u2149\u214e\u2160-\u2188\u2c00-\u2c2e\u2c30-\u2c5e\u2c60-\u2ce4\u2ceb-\u2cee\u2cf2\u2cf3\u2d00-\u2d25\u2d27\u2d2d\u2d30-\u2d67\u2d6f\u2d80-\u2d96\u2da0-\u2da6\u2da8-\u2dae\u2db0-\u2db6\u2db8-\u2dbe\u2dc0-\u2dc6\u2dc8-\u2dce\u2dd0-\u2dd6\u2dd8-\u2dde\u2e2f\u3005-\u3007\u3021-\u3029\u3031-\u3035\u3038-\u303c\u3041-\u3096\u309d-\u309f\u30a1-\u30fa\u30fc-\u30ff\u3105-\u312d\u3131-\u318e\u31a0-\u31ba\u31f0-\u31ff\u3400-\u4db5\u4e00-\u9fcc\ua000-\ua48c\ua4d0-\ua4fd\ua500-\ua60c\ua610-\ua61f\ua62a\ua62b\ua640-\ua66e\ua67f-\ua697\ua6a0-\ua6ef\ua717-\ua71f\ua722-\ua788\ua78b-\ua78e\ua790-\ua793\ua7a0-\ua7aa\ua7f8-\ua801\ua803-\ua805\ua807-\ua80a\ua80c-\ua822\ua840-\ua873\ua882-\ua8b3\ua8f2-\ua8f7\ua8fb\ua90a-\ua925\ua930-\ua946\ua960-\ua97c\ua984-\ua9b2\ua9cf\uaa00-\uaa28\uaa40-\uaa42\uaa44-\uaa4b\uaa60-\uaa76\uaa7a\uaa80-\uaaaf\uaab1\uaab5\uaab6\uaab9-\uaabd\uaac0\uaac2\uaadb-\uaadd\uaae0-\uaaea\uaaf2-\uaaf4\uab01-\uab06\uab09-\uab0e\uab11-\uab16\uab20-\uab26\uab28-\uab2e\uabc0-\uabe2\uac00-\ud7a3\ud7b0-\ud7c6\ud7cb-\ud7fb\uf900-\ufa6d\ufa70-\ufad9\ufb00-\ufb06\ufb13-\ufb17\ufb1d\ufb1f-\ufb28\ufb2a-\ufb36\ufb38-\ufb3c\ufb3e\ufb40\ufb41\ufb43\ufb44\ufb46-\ufbb1\ufbd3-\ufd3d\ufd50-\ufd8f\ufd92-\ufdc7\ufdf0-\ufdfb\ufe70-\ufe74\ufe76-\ufefc\uff21-\uff3a\uff41-\uff5a\uff66-\uffbe\uffc2-\uffc7\uffca-\uffcf\uffd2-\uffd7\uffda-\uffdc";
        var nonASCIIidentifierChars = "\u0300-\u036f\u0483-\u0487\u0591-\u05bd\u05bf\u05c1\u05c2\u05c4\u05c5\u05c7\u0610-\u061a\u0620-\u0649\u0672-\u06d3\u06e7-\u06e8\u06fb-\u06fc\u0730-\u074a\u0800-\u0814\u081b-\u0823\u0825-\u0827\u0829-\u082d\u0840-\u0857\u08e4-\u08fe\u0900-\u0903\u093a-\u093c\u093e-\u094f\u0951-\u0957\u0962-\u0963\u0966-\u096f\u0981-\u0983\u09bc\u09be-\u09c4\u09c7\u09c8\u09d7\u09df-\u09e0\u0a01-\u0a03\u0a3c\u0a3e-\u0a42\u0a47\u0a48\u0a4b-\u0a4d\u0a51\u0a66-\u0a71\u0a75\u0a81-\u0a83\u0abc\u0abe-\u0ac5\u0ac7-\u0ac9\u0acb-\u0acd\u0ae2-\u0ae3\u0ae6-\u0aef\u0b01-\u0b03\u0b3c\u0b3e-\u0b44\u0b47\u0b48\u0b4b-\u0b4d\u0b56\u0b57\u0b5f-\u0b60\u0b66-\u0b6f\u0b82\u0bbe-\u0bc2\u0bc6-\u0bc8\u0bca-\u0bcd\u0bd7\u0be6-\u0bef\u0c01-\u0c03\u0c46-\u0c48\u0c4a-\u0c4d\u0c55\u0c56\u0c62-\u0c63\u0c66-\u0c6f\u0c82\u0c83\u0cbc\u0cbe-\u0cc4\u0cc6-\u0cc8\u0cca-\u0ccd\u0cd5\u0cd6\u0ce2-\u0ce3\u0ce6-\u0cef\u0d02\u0d03\u0d46-\u0d48\u0d57\u0d62-\u0d63\u0d66-\u0d6f\u0d82\u0d83\u0dca\u0dcf-\u0dd4\u0dd6\u0dd8-\u0ddf\u0df2\u0df3\u0e34-\u0e3a\u0e40-\u0e45\u0e50-\u0e59\u0eb4-\u0eb9\u0ec8-\u0ecd\u0ed0-\u0ed9\u0f18\u0f19\u0f20-\u0f29\u0f35\u0f37\u0f39\u0f41-\u0f47\u0f71-\u0f84\u0f86-\u0f87\u0f8d-\u0f97\u0f99-\u0fbc\u0fc6\u1000-\u1029\u1040-\u1049\u1067-\u106d\u1071-\u1074\u1082-\u108d\u108f-\u109d\u135d-\u135f\u170e-\u1710\u1720-\u1730\u1740-\u1750\u1772\u1773\u1780-\u17b2\u17dd\u17e0-\u17e9\u180b-\u180d\u1810-\u1819\u1920-\u192b\u1930-\u193b\u1951-\u196d\u19b0-\u19c0\u19c8-\u19c9\u19d0-\u19d9\u1a00-\u1a15\u1a20-\u1a53\u1a60-\u1a7c\u1a7f-\u1a89\u1a90-\u1a99\u1b46-\u1b4b\u1b50-\u1b59\u1b6b-\u1b73\u1bb0-\u1bb9\u1be6-\u1bf3\u1c00-\u1c22\u1c40-\u1c49\u1c5b-\u1c7d\u1cd0-\u1cd2\u1d00-\u1dbe\u1e01-\u1f15\u200c\u200d\u203f\u2040\u2054\u20d0-\u20dc\u20e1\u20e5-\u20f0\u2d81-\u2d96\u2de0-\u2dff\u3021-\u3028\u3099\u309a\ua640-\ua66d\ua674-\ua67d\ua69f\ua6f0-\ua6f1\ua7f8-\ua800\ua806\ua80b\ua823-\ua827\ua880-\ua881\ua8b4-\ua8c4\ua8d0-\ua8d9\ua8f3-\ua8f7\ua900-\ua909\ua926-\ua92d\ua930-\ua945\ua980-\ua983\ua9b3-\ua9c0\uaa00-\uaa27\uaa40-\uaa41\uaa4c-\uaa4d\uaa50-\uaa59\uaa7b\uaae0-\uaae9\uaaf2-\uaaf3\uabc0-\uabe1\uabec\uabed\uabf0-\uabf9\ufb20-\ufb28\ufe00-\ufe0f\ufe20-\ufe26\ufe33\ufe34\ufe4d-\ufe4f\uff10-\uff19\uff3f";
        var nonASCIIidentifierStart = new RegExp("[" + nonASCIIidentifierStartChars + "]");
        var nonASCIIidentifier = new RegExp("[" + nonASCIIidentifierStartChars + nonASCIIidentifierChars + "]");

        // Whether a single character denotes a newline.

        exports.newline = /[\n\r\u2028\u2029]/;

        // Matches a whole line break (where CRLF is considered a single
        // line break). Used to count lines.

        // in javascript, these two differ
        // in python they are the same, different methods are called on them
        exports.lineBreak = new RegExp('\r\n|' + exports.newline.source);
        exports.allLineBreaks = new RegExp(exports.lineBreak.source, 'g');


        // Test whether a given character code starts an identifier.

        exports.isIdentifierStart = function(code) {
            // permit $ (36) and @ (64). @ is used in ES7 decorators.
            if (code < 65) return code === 36 || code === 64;
            // 65 through 91 are uppercase letters.
            if (code < 91) return true;
            // permit _ (95).
            if (code < 97) return code === 95;
            // 97 through 123 are lowercase letters.
            if (code < 123) return true;
            return code >= 0xaa && nonASCIIidentifierStart.test(String.fromCharCode(code));
        };

        // Test whether a given character is part of an identifier.

        exports.isIdentifierChar = function(code) {
            if (code < 48) return code === 36;
            if (code < 58) return true;
            if (code < 65) return false;
            if (code < 91) return true;
            if (code < 97) return code === 95;
            if (code < 123) return true;
            return code >= 0xaa && nonASCIIidentifier.test(String.fromCharCode(code));
        };
    })(acorn);

    function run (html_source, options) {
      function ltrim(s) {
        return s.replace(/^\s+/g, '');
      }

      function rtrim(s) {
        return s.replace(/\s+$/g, '');
      }

      var multi_parser;
      var indent_inner_html;
      var indent_size;
      var indent_character;
      var wrap_line_length;
      var brace_style;
      var unformatted;
      var preserve_newlines;
      var max_preserve_newlines;
      var indent_handlebars;
      var wrap_attributes;
      var wrap_attributes_indent_size;
      var end_with_newline;
      var extra_liners;

      options = options || {};

      // backwards compatibility to 1.3.4
      if ((options.wrap_line_length === undefined || parseInt(options.wrap_line_length, 10) === 0) &&
        (options.max_char !== undefined && parseInt(options.max_char, 10) !== 0)) {
        options.wrap_line_length = options.max_char;
      }

      indent_inner_html = (options.indent_inner_html === undefined) ? false : options.indent_inner_html;
      indent_size = (options.indent_size === undefined) ? 4 : parseInt(options.indent_size, 10);
      indent_character = (options.indent_char === undefined) ? ' ' : options.indent_char;
      brace_style = (options.brace_style === undefined) ? 'collapse' : options.brace_style;
      wrap_line_length = parseInt(options.wrap_line_length, 10) === 0 ? 32786 : parseInt(options.wrap_line_length || 250, 10);
      unformatted = options.unformatted || ['a', 'span', 'img', 'bdo', 'em', 'strong', 'dfn', 'code', 'samp', 'kbd',
        'var', 'cite', 'abbr', 'acronym', 'q', 'sub', 'sup', 'tt', 'i', 'b', 'big', 'small', 'u', 's', 'strike',
        'font', 'ins', 'del', 'address', 'pre'
      ];
      preserve_newlines = (options.preserve_newlines === undefined) ? true : options.preserve_newlines;
      max_preserve_newlines = preserve_newlines ?
        (isNaN(parseInt(options.max_preserve_newlines, 10)) ? 32786 : parseInt(options.max_preserve_newlines, 10)) : 0;
      indent_handlebars = (options.indent_handlebars === undefined) ? false : options.indent_handlebars;
      wrap_attributes = (options.wrap_attributes === undefined) ? 'auto' : options.wrap_attributes;
      wrap_attributes_indent_size = (options.wrap_attributes_indent_size === undefined) ? indent_size : parseInt(options.wrap_attributes_indent_size, 10) || indent_size;
      end_with_newline = (options.end_with_newline === undefined) ? false : options.end_with_newline;
      extra_liners = Array.isArray(options.extra_liners) ?
        options.extra_liners.concat() : (typeof options.extra_liners == 'string') ?
        options.extra_liners.split(',') : 'head,body,/html'.split(',');

      if (options.indent_with_tabs) {
        indent_character = '\t';
        indent_size = 1;
      }

      function Parser() {

        this.pos = 0; //Parser position
        this.token = '';
        this.current_mode = 'CONTENT'; //reflects the current Parser mode: TAG/CONTENT
        this.tags = { //An object to hold tags, their position, and their parent-tags, initiated with default values
          parent: 'parent1',
          parentcount: 1,
          parent1: ''
        };
        this.tag_type = '';
        this.token_text = this.last_token = this.last_text = this.token_type = '';
        this.newlines = 0;
        this.indent_content = indent_inner_html;

        this.Utils = { //Uilities made available to the various functions
          whitespace: "\n\r\t ".split(''),
          single_token: 'br,input,link,meta,source,!doctype,basefont,base,area,hr,wbr,param,img,isindex,embed'.split(','), //all the single tags for HTML
          extra_liners: extra_liners, //for tags that need a line of whitespace before them
          in_array: function(what, arr) {
            for (var i = 0; i < arr.length; i++) {
              if (what == arr[i]) {
                return true;
              }
            }
            return false;
          }
        };

        // Return true if the given text is composed entirely of whitespace.
        this.is_whitespace = function(text) {
          for (var n = 0; n < text.length; text++) {
            if (!this.Utils.in_array(text.charAt(n), this.Utils.whitespace)) {
              return false;
            }
          }
          return true;
        };

        this.traverse_whitespace = function() {
          var input_char = '';

          input_char = this.input.charAt(this.pos);
          if (this.Utils.in_array(input_char, this.Utils.whitespace)) {
            this.newlines = 0;
            while (this.Utils.in_array(input_char, this.Utils.whitespace)) {
              if (preserve_newlines && input_char == '\n' && this.newlines <= max_preserve_newlines) {
                this.newlines += 1;
              }

              this.pos++;
              input_char = this.input.charAt(this.pos);
            }
            return true;
          }
          return false;
        };

        // Append a space to the given content (string array) or, if we are
        // at the wrap_line_length, append a newline/indentation.
        this.space_or_wrap = function(content) {
          if (this.line_char_count >= this.wrap_line_length) { //insert a line when the wrap_line_length is reached
            this.print_newline(false, content);
            this.print_indentation(content);
          } else {
            this.line_char_count++;
            content.push(' ');
          }
        };

        this.get_content = function() { //function to capture regular content between tags
          var input_char = '',
              content = [];

          while (this.input.charAt(this.pos) != '<') {
            if (this.pos >= this.input.length) {
              return content.length ? content.join('') : ['', 'TK_EOF'];
            }

            if (this.traverse_whitespace()) {
              this.space_or_wrap(content);
              continue;
            }

            if (indent_handlebars) {
              // Handlebars parsing is complicated.
              // {{#foo}} and {{/foo}} are formatted tags.
              // {{something}} should get treated as content, except:
              // {{else}} specifically behaves like {{#if}} and {{/if}}
              var peek3 = this.input.substr(this.pos, 3);
              if (peek3 == '{{#' || peek3 == '{{/') {
                // These are tags and not content.
                break;
              } else if (peek3 == '{{!') {
                return [this.get_tag(), 'TK_TAG_HANDLEBARS_COMMENT'];
              } else if (this.input.substr(this.pos, 2) == '{{') {
                if (this.get_tag(true) == '{{else}}') {
                  break;
                }
              }
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;
            this.line_char_count++;
            content.push(input_char); //letter at-a-time (or string) inserted to an array
          }
          return content.length ? content.join('') : '';
        };

        this.get_contents_to = function(name) { //get the full content of a script or style to pass to js_beautify
          if (this.pos == this.input.length) {
            return ['', 'TK_EOF'];
          }
          var content = '';
          var reg_match = new RegExp('</' + name + '\\s*>', 'igm');
          reg_match.lastIndex = this.pos;
          var reg_array = reg_match.exec(this.input);
          var end_script = reg_array ? reg_array.index : this.input.length; //absolute end of script
          if (this.pos < end_script) { //get everything in between the script tags
            content = this.input.substring(this.pos, end_script);
            this.pos = end_script;
          }
          return content;
        };

        this.record_tag = function(tag) { //function to record a tag and its parent in this.tags Object
          if (this.tags[tag + 'count']) { //check for the existence of this tag type
            this.tags[tag + 'count']++;
            this.tags[tag + this.tags[tag + 'count']] = this.indent_level; //and record the present indent level
          } else { //otherwise initialize this tag type
            this.tags[tag + 'count'] = 1;
            this.tags[tag + this.tags[tag + 'count']] = this.indent_level; //and record the present indent level
          }
          this.tags[tag + this.tags[tag + 'count'] + 'parent'] = this.tags.parent; //set the parent (i.e. in the case of a div this.tags.div1parent)
          this.tags.parent = tag + this.tags[tag + 'count']; //and make this the current parent (i.e. in the case of a div 'div1')
        };

        this.retrieve_tag = function(tag) { //function to retrieve the opening tag to the corresponding closer
          if (this.tags[tag + 'count']) { //if the openener is not in the Object we ignore it
            var temp_parent = this.tags.parent; //check to see if it's a closable tag.
            while (temp_parent) { //till we reach '' (the initial value);
              if (tag + this.tags[tag + 'count'] == temp_parent) { //if this is it use it
                break;
              }
              temp_parent = this.tags[temp_parent + 'parent']; //otherwise keep on climbing up the DOM Tree
            }
            if (temp_parent) { //if we caught something
              this.indent_level = this.tags[tag + this.tags[tag + 'count']]; //set the indent_level accordingly
              this.tags.parent = this.tags[temp_parent + 'parent']; //and set the current parent
            }
            delete this.tags[tag + this.tags[tag + 'count'] + 'parent']; //delete the closed tags parent reference...
            delete this.tags[tag + this.tags[tag + 'count']]; //...and the tag itself
            if (this.tags[tag + 'count'] == 1) {
              delete this.tags[tag + 'count'];
            } else {
              this.tags[tag + 'count']--;
            }
          }
        };

        this.indent_to_tag = function(tag) {
          // Match the indentation level to the last use of this tag, but don't remove it.
          if (!this.tags[tag + 'count']) {
            return;
          }
          var temp_parent = this.tags.parent;
          while (temp_parent) {
            if (tag + this.tags[tag + 'count'] == temp_parent) {
              break;
            }
            temp_parent = this.tags[temp_parent + 'parent'];
          }
          if (temp_parent) {
            this.indent_level = this.tags[tag + this.tags[tag + 'count']];
          }
        };

        this.get_tag = function(peek) { //function to get a full tag and parse its type
          var input_char = '',
            content = [],
            comment = '',
            space = false,
            first_attr = true,
            tag_start, tag_end,
            tag_start_char,
            orig_pos = this.pos,
            orig_line_char_count = this.line_char_count;

          peek = peek !== undefined ? peek : false;

          do {
            if (this.pos >= this.input.length) {
              if (peek) {
                this.pos = orig_pos;
                this.line_char_count = orig_line_char_count;
              }
              return content.length ? content.join('') : ['', 'TK_EOF'];
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;

            if (this.Utils.in_array(input_char, this.Utils.whitespace)) { //don't want to insert unnecessary space
              space = true;
              continue;
            }

            if (input_char == "'" || input_char == '"') {
              input_char += this.get_unformatted(input_char);
              space = true;

            }

            if (input_char == '=') { //no space before =
              space = false;
            }

            if (content.length && content[content.length - 1] != '=' && input_char != '>' && space) {
              //no space after = or before >
              this.space_or_wrap(content);
              space = false;
              if (!first_attr && wrap_attributes == 'force' && input_char != '/') {
                this.print_newline(true, content);
                this.print_indentation(content);
                for (var count = 0; count < wrap_attributes_indent_size; count++) {
                  content.push(indent_character);
                }
              }
              for (var i = 0; i < content.length; i++) {
                if (content[i] == ' ') {
                  first_attr = false;
                  break;
                }
              }
            }

            if (indent_handlebars && tag_start_char == '<') {
              // When inside an angle-bracket tag, put spaces around
              // handlebars not inside of strings.
              if ((input_char + this.input.charAt(this.pos)) == '{{') {
                input_char += this.get_unformatted('}}');
                if (content.length && content[content.length - 1] != ' ' && content[content.length - 1] != '<') {
                  input_char = ' ' + input_char;
                }
                space = true;
              }
            }

            if (input_char == '<' && !tag_start_char) {
              tag_start = this.pos - 1;
              tag_start_char = '<';
            }

            if (indent_handlebars && !tag_start_char) {
              if (content.length >= 2 && content[content.length - 1] == '{' && content[content.length - 2] == '{') {
                if (input_char == '#' || input_char == '/' || input_char == '!') {
                  tag_start = this.pos - 3;
                } else {
                  tag_start = this.pos - 2;
                }
                tag_start_char = '{';
              }
            }

            this.line_char_count++;
            content.push(input_char); //inserts character at-a-time (or string)

            if (content[1] && (content[1] == '!' || content[1] == '?' || content[1] == '%')) { //if we're in a comment, do something special
              // We treat all comments as literals, even more than preformatted tags
              // we just look for the appropriate close tag
              content = [this.get_comment(tag_start)];
              break;
            }

            if (indent_handlebars && content[1] && content[1] == '{' && content[2] && content[2] == '!') { //if we're in a comment, do something special
              // We treat all comments as literals, even more than preformatted tags
              // we just look for the appropriate close tag
              content = [this.get_comment(tag_start)];
              break;
            }

            if (indent_handlebars && tag_start_char == '{' && content.length > 2 && content[content.length - 2] == '}' && content[content.length - 1] == '}') {
              break;
            }
          } while (input_char != '>');

          var tag_complete = content.join('');
          var tag_index;
          var tag_offset;

          if (tag_complete.indexOf(' ') != -1) { //if there's whitespace, thats where the tag name ends
            tag_index = tag_complete.indexOf(' ');
          } else if (tag_complete[0] == '{') {
            tag_index = tag_complete.indexOf('}');
          } else { //otherwise go with the tag ending
            tag_index = tag_complete.indexOf('>');
          }
          if (tag_complete[0] == '<' || !indent_handlebars) {
            tag_offset = 1;
          } else {
            tag_offset = tag_complete[2] == '#' ? 3 : 2;
          }
          var tag_check = tag_complete.substring(tag_offset, tag_index).toLowerCase();
          if (tag_complete.charAt(tag_complete.length - 2) == '/' ||
            this.Utils.in_array(tag_check, this.Utils.single_token)) { //if this tag name is a single tag type (either in the list or has a closing /)
            if (!peek) {
              this.tag_type = 'SINGLE';
            }
          } else if (indent_handlebars && tag_complete[0] == '{' && tag_check == 'else') {
            if (!peek) {
              this.indent_to_tag('if');
              this.tag_type = 'HANDLEBARS_ELSE';
              this.indent_content = true;
              this.traverse_whitespace();
            }
          } else if (this.is_unformatted(tag_check, unformatted)) { // do not reformat the "unformatted" tags
            comment = this.get_unformatted('</' + tag_check + '>', tag_complete); //...delegate to get_unformatted function
            content.push(comment);
            tag_end = this.pos - 1;
            this.tag_type = 'SINGLE';
          } else if (tag_check == 'script' &&
            (tag_complete.search('type') == -1 ||
              (tag_complete.search('type') > -1 &&
                tag_complete.search(/\b(text|application)\/(x-)?(javascript|ecmascript|jscript|livescript)/) > -1))) {
            if (!peek) {
              this.record_tag(tag_check);
              this.tag_type = 'SCRIPT';
            }
          } else if (tag_check == 'style' &&
            (tag_complete.search('type') == -1 ||
              (tag_complete.search('type') > -1 && tag_complete.search('text/css') > -1))) {
            if (!peek) {
              this.record_tag(tag_check);
              this.tag_type = 'STYLE';
            }
          } else if (tag_check.charAt(0) == '!') { //peek for <! comment
            // for comments content is already correct.
            if (!peek) {
              this.tag_type = 'SINGLE';
              this.traverse_whitespace();
            }
          } else if (!peek) {
            if (tag_check.charAt(0) == '/') { //this tag is a double tag so check for tag-ending
              this.retrieve_tag(tag_check.substring(1)); //remove it and all ancestors
              this.tag_type = 'END';
            } else { //otherwise it's a start-tag
              this.record_tag(tag_check); //push it on the tag stack
              if (tag_check.toLowerCase() != 'html') {
                this.indent_content = true;
              }
              this.tag_type = 'START';
            }

            // Allow preserving of newlines after a start or end tag
            if (this.traverse_whitespace()) {
              this.space_or_wrap(content);
            }

            if (this.Utils.in_array(tag_check, this.Utils.extra_liners)) { //check if this double needs an extra line
              this.print_newline(false, this.output);
              if (this.output.length && this.output[this.output.length - 2] != '\n') {
                this.print_newline(true, this.output);
              }
            }
          }

          if (peek) {
            this.pos = orig_pos;
            this.line_char_count = orig_line_char_count;
          }

          return content.join(''); //returns fully formatted tag
        };

        this.get_comment = function(start_pos) { //function to return comment content in its entirety
          // this is will have very poor perf, but will work for now.
          var comment = '',
            delimiter = '>',
            matched = false;

          this.pos = start_pos;
          var input_char = this.input.charAt(this.pos);
          this.pos++;

          while (this.pos <= this.input.length) {
            comment += input_char;

            // only need to check for the delimiter if the last chars match
            if (comment[comment.length - 1] == delimiter[delimiter.length - 1] &&
              comment.indexOf(delimiter) != -1) {
              break;
            }

            // only need to search for custom delimiter for the first few characters
            if (!matched && comment.length < 10) {
              if (comment.indexOf('<![if') === 0) { //peek for <![if conditional comment
                delimiter = '<![endif]>';
                matched = true;
              } else if (comment.indexOf('<![cdata[') === 0) { //if it's a <[cdata[ comment...
                delimiter = ']]>';
                matched = true;
              } else if (comment.indexOf('<![') === 0) { // some other ![ comment? ...
                delimiter = ']>';
                matched = true;
              } else if (comment.indexOf('<!--') === 0) { // <!-- comment ...
                delimiter = '-->';
                matched = true;
              } else if (comment.indexOf('{{!') === 0) { // {{! handlebars comment
                delimiter = '}}';
                matched = true;
              } else if (comment.indexOf('<?') === 0) { // {{! handlebars comment
                delimiter = '?>';
                matched = true;
              } else if (comment.indexOf('<%') === 0) { // {{! handlebars comment
                delimiter = '%>';
                matched = true;
              }
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;
          }

          return comment;
        };

        this.get_unformatted = function(delimiter, orig_tag) { //function to return unformatted content in its entirety

          if (orig_tag && orig_tag.toLowerCase().indexOf(delimiter) != -1) {
            return '';
          }
          var input_char = '';
          var content = '';
          var min_index = 0;
          var space = true;
          do {

            if (this.pos >= this.input.length) {
              return content;
            }

            input_char = this.input.charAt(this.pos);
            this.pos++;

            if (this.Utils.in_array(input_char, this.Utils.whitespace)) {
              if (!space) {
                this.line_char_count--;
                continue;
              }
              if (input_char == '\n' || input_char == '\r') {
                content += '\n';
                /*  Don't change tab indention for unformatted blocks.  If using code for html editing, this will greatly affect <pre> tags if they are specified in the 'unformatted array'
              for (var i=0; i<this.indent_level; i++) {
                content += this.indent_string;
              }
              space = false; //...and make sure other indentation is erased
              */
                this.line_char_count = 0;
                continue;
              }
            }
            content += input_char;
            this.line_char_count++;
            space = true;

            if (indent_handlebars && input_char == '{' && content.length && content[content.length - 2] == '{') {
              // Handlebars expressions in strings should also be unformatted.
              content += this.get_unformatted('}}');
              // These expressions are opaque.  Ignore delimiters found in them.
              min_index = content.length;
            }
          } while (content.toLowerCase().indexOf(delimiter, min_index) == -1);
          return content;
        };

        this.get_token = function() { //initial handler for token-retrieval
          var token;

          if (this.last_token == 'TK_TAG_SCRIPT' || this.last_token == 'TK_TAG_STYLE') { //check if we need to format javascript
            var type = this.last_token.substr(7);
            token = this.get_contents_to(type);
            if (typeof token != 'string') {
              return token;
            }
            return [token, 'TK_' + type];
          }
          if (this.current_mode == 'CONTENT') {
            token = this.get_content();
            if (typeof token != 'string') {
              return token;
            } else {
              return [token, 'TK_CONTENT'];
            }
          }

          if (this.current_mode == 'TAG') {
            token = this.get_tag();
            if (typeof token != 'string') {
              return token;
            } else {
              var tag_name_type = 'TK_TAG_' + this.tag_type;
              return [token, tag_name_type];
            }
          }
        };

        this.get_full_indent = function(level) {
          level = this.indent_level + level || 0;
          if (level < 1) {
            return '';
          }

          return (new Array(level + 1)).join(this.indent_string);
        };

        this.is_unformatted = function(tag_check, unformatted) {
          //is this an HTML5 block-level link?
          if (!this.Utils.in_array(tag_check, unformatted)) {
            return false;
          }

          if (tag_check.toLowerCase() != 'a' || !this.Utils.in_array('a', unformatted)) {
            return true;
          }

          //at this point we have an  tag; is its first child something we want to remain
          //unformatted?
          var next_tag = this.get_tag(true /* peek. */ );

          // test next_tag to see if it is just html tag (no external content)
          var tag = (next_tag || '').match(/^\s*<\s*\/?([a-z]*)\s*[^>]*>\s*$/);

          // if next_tag comes back but is not an isolated tag, then
          // let's treat the 'a' tag as having content
          // and respect the unformatted option
          if (!tag || this.Utils.in_array(tag, unformatted)) {
            return true;
          } else {
            return false;
          }
        };

        this.printer = function(js_source, indent_character, indent_size, wrap_line_length, brace_style) { //handles input/output and some other printing functions

          this.input = js_source || ''; //gets the input for the Parser
          this.output = [];
          this.indent_character = indent_character;
          this.indent_string = '';
          this.indent_size = indent_size;
          this.brace_style = brace_style;
          this.indent_level = 0;
          this.wrap_line_length = wrap_line_length;
          this.line_char_count = 0; //count to see if wrap_line_length was exceeded

          for (var i = 0; i < this.indent_size; i++) {
            this.indent_string += this.indent_character;
          }

          this.print_newline = function(force, arr) {
            this.line_char_count = 0;
            if (!arr || !arr.length) {
              return;
            }
            if (force || (arr[arr.length - 1] != '\n')) { //we might want the extra line
              if ((arr[arr.length - 1] != '\n')) {
                arr[arr.length - 1] = rtrim(arr[arr.length - 1]);
              }
              arr.push('\n');
            }
          };

          this.print_indentation = function(arr) {
            for (var i = 0; i < this.indent_level; i++) {
              arr.push(this.indent_string);
              this.line_char_count += this.indent_string.length;
            }
          };

          this.print_token = function(text) {
            // Avoid printing initial whitespace.
            if (this.is_whitespace(text) && !this.output.length) {
              return;
            }
            if (text || text !== '') {
              if (this.output.length && this.output[this.output.length - 1] == '\n') {
                this.print_indentation(this.output);
                text = ltrim(text);
              }
            }
            this.print_token_raw(text);
          };

          this.print_token_raw = function(text) {
            // If we are going to print newlines, truncate trailing
            // whitespace, as the newlines will represent the space.
            if (this.newlines > 0) {
              text = rtrim(text);
            }

            if (text && text !== '') {
              if (text.length > 1 && text[text.length - 1] == '\n') {
                // unformatted tags can grab newlines as their last character
                this.output.push(text.slice(0, -1));
                this.print_newline(false, this.output);
              } else {
                this.output.push(text);
              }
            }

            for (var n = 0; n < this.newlines; n++) {
              this.print_newline(n > 0, this.output);
            }
            this.newlines = 0;
          };

          this.indent = function() {
            this.indent_level++;
          };

          this.unindent = function() {
            if (this.indent_level > 0) {
              this.indent_level--;
            }
          };
        };
        return this;
      }

      /*_____________________--------------------_____________________*/

      multi_parser = new Parser(); //wrapping functions Parser
      multi_parser.printer(html_source, indent_character, indent_size, wrap_line_length, brace_style); //initialize starting values

      while (true) {
        var t = multi_parser.get_token();
        multi_parser.token_text = t[0];
        multi_parser.token_type = t[1];

        if (multi_parser.token_type == 'TK_EOF') {
          break;
        }

        switch (multi_parser.token_type) {
          case 'TK_TAG_START':
            multi_parser.print_newline(false, multi_parser.output);
            multi_parser.print_token(multi_parser.token_text);
            if (multi_parser.indent_content) {
              multi_parser.indent();
              multi_parser.indent_content = false;
            }
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_STYLE':
          case 'TK_TAG_SCRIPT':
            multi_parser.print_newline(false, multi_parser.output);
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_END':
            // Print new line only if the tag has no content and has child
            if (multi_parser.last_token == 'TK_CONTENT' && multi_parser.last_text === '') {
              var tag_name = multi_parser.token_text.match(/\w+/)[0];
              var tag_extracted_from_last_output = null;
              if (multi_parser.output.length) {
                tag_extracted_from_last_output = multi_parser.output[multi_parser.output.length - 1].match(/(?:<|{{#)\s*(\w+)/);
              }
              if (tag_extracted_from_last_output == null ||
                (tag_extracted_from_last_output[1] != tag_name && !multi_parser.Utils.in_array(tag_extracted_from_last_output[1], unformatted))) {
                multi_parser.print_newline(false, multi_parser.output);
              }
            }
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_SINGLE':
            // Don't add a newline before elements that should remain unformatted.
            var tag_check = multi_parser.token_text.match(/^\s*<([a-z-]+)/i);
            if (!tag_check || !multi_parser.Utils.in_array(tag_check[1], unformatted)) {
              multi_parser.print_newline(false, multi_parser.output);
            }
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_HANDLEBARS_ELSE':
            multi_parser.print_token(multi_parser.token_text);
            if (multi_parser.indent_content) {
              multi_parser.indent();
              multi_parser.indent_content = false;
            }
            multi_parser.current_mode = 'CONTENT';
            break;
          case 'TK_TAG_HANDLEBARS_COMMENT':
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'TAG';
            break;
          case 'TK_CONTENT':
            multi_parser.print_token(multi_parser.token_text);
            multi_parser.current_mode = 'TAG';
            break;
          case 'TK_STYLE':
          case 'TK_SCRIPT':
            if (multi_parser.token_text !== '') {
              multi_parser.print_newline(false, multi_parser.output);
              var text = multi_parser.token_text;
              var _beautifier;
              var script_indent_level = 1;

              if (multi_parser.token_type == 'TK_SCRIPT') {
                _beautifier = typeof js_beautify == 'function' && js_beautify;
              } else if (multi_parser.token_type == 'TK_STYLE') {
                _beautifier = typeof css_beautify == 'function' && css_beautify;
              }

              if (options.indent_scripts == 'keep') {
                script_indent_level = 0;
              } else if (options.indent_scripts == 'separate') {
                script_indent_level = -multi_parser.indent_level;
              }

              var indentation = multi_parser.get_full_indent(script_indent_level);
              if (_beautifier) {
                // call the Beautifier if avaliable
                text = _beautifier(text.replace(/^\s*/, indentation), options);
              } else {
                // simply indent the string otherwise
                var white = text.match(/^\s*/)[0];
                var _level = white.match(/[^\n\r]*$/)[0].split(multi_parser.indent_string).length - 1;
                var reindent = multi_parser.get_full_indent(script_indent_level - _level);

                text = text.replace(/^\s*/, indentation)
                  .replace(/\r\n|\r|\n/g, '\n' + reindent)
                  .replace(/\s+$/, '');
              }
              if (text) {
                multi_parser.print_token_raw(text);
                multi_parser.print_newline(true, multi_parser.output);
              }
            }
            multi_parser.current_mode = 'TAG';
            break;
          default:
            // We should not be getting here but we don't want to drop input on the floor
            // Just output the text and move on
            if (multi_parser.token_text !== '') {
              multi_parser.print_token(multi_parser.token_text);
            }
            break;
        }
        multi_parser.last_token = multi_parser.token_type;
        multi_parser.last_text = multi_parser.token_text;
      }
      var sweet_code = multi_parser.output.join('').replace(/[\r\n\t ]+$/, '');
      if (end_with_newline) {
        sweet_code += '\n';
      }
      return sweet_code;
    }

    function css_beautify (source_text, options) {
        var NESTED_AT_RULE = {
            "@page": true,
            "@font-face": true,
            "@keyframes": true,
            // also in CONDITIONAL_GROUP_RULE below
            "@media": true,
            "@supports": true,
            "@document": true
        };
        var CONDITIONAL_GROUP_RULE = {
            "@media": true,
            "@supports": true,
            "@document": true
        };

        options = options || {};
        source_text = source_text || '';
        // HACK: newline parsing inconsistent. This brute force normalizes the input.
        source_text = source_text.replace(/\r\n|[\r\u2028\u2029]/g, '\n')

        var indentSize = options.indent_size || 4;
        var indentCharacter = options.indent_char || ' ';
        var selectorSeparatorNewline = (options.selector_separator_newline === undefined) ? true : options.selector_separator_newline;
        var end_with_newline = (options.end_with_newline === undefined) ? false : options.end_with_newline;
        var newline_between_rules = (options.newline_between_rules === undefined) ? true : options.newline_between_rules;
        var eol = options.eol ? options.eol : '\n';

        // compatibility
        if (typeof indentSize === "string") {
            indentSize = parseInt(indentSize, 10);
        }

        if(options.indent_with_tabs){
            indentCharacter = '\t';
            indentSize = 1;
        }

        eol = eol.replace(/\\r/, '\r').replace(/\\n/, '\n')


        // tokenizer
        var whiteRe = /^\s+$/;
        var wordRe = /[\w$\-_]/;

        var pos = -1,
            ch;
        var parenLevel = 0;

        function next() {
            ch = source_text.charAt(++pos);
            return ch || '';
        }

        function peek(skipWhitespace) {
            var result = '';
            var prev_pos = pos;
            if (skipWhitespace) {
                eatWhitespace();
            }
            result = source_text.charAt(pos + 1) || '';
            pos = prev_pos - 1;
            next();
            return result;
        }

        function eatString(endChars) {
            var start = pos;
            while (next()) {
                if (ch === "\\") {
                    next();
                } else if (endChars.indexOf(ch) !== -1) {
                    break;
                } else if (ch === "\n") {
                    break;
                }
            }
            return source_text.substring(start, pos + 1);
        }

        function peekString(endChar) {
            var prev_pos = pos;
            var str = eatString(endChar);
            pos = prev_pos - 1;
            next();
            return str;
        }

        function eatWhitespace() {
            var result = '';
            while (whiteRe.test(peek())) {
                next();
                result += ch;
            }
            return result;
        }

        function skipWhitespace() {
            var result = '';
            if (ch && whiteRe.test(ch)) {
                result = ch;
            }
            while (whiteRe.test(next())) {
                result += ch;
            }
            return result;
        }

        function eatComment(singleLine) {
            var start = pos;
            singleLine = peek() === "/";
            next();
            while (next()) {
                if (!singleLine && ch === "*" && peek() === "/") {
                    next();
                    break;
                } else if (singleLine && ch === "\n") {
                    return source_text.substring(start, pos);
                }
            }

            return source_text.substring(start, pos) + ch;
        }


        function lookBack(str) {
            return source_text.substring(pos - str.length, pos).toLowerCase() ===
                str;
        }

        // Nested pseudo-class if we are insideRule
        // and the next special character found opens
        // a new block
        function foundNestedPseudoClass() {
            var openParen = 0;
            for (var i = pos + 1; i < source_text.length; i++) {
                var ch = source_text.charAt(i);
                if (ch === "{") {
                    return true;
                } else if (ch === '(') {
                    // pseudoclasses can contain ()
                    openParen += 1;
                } else if (ch === ')') {
                    if (openParen == 0) {
                        return false;
                    }
                    openParen -= 1;
                } else if (ch === ";" || ch === "}") {
                    return false;
                }
            }
            return false;
        }

        // printer
        var basebaseIndentString = source_text.match(/^[\t ]*/)[0];
        var singleIndent = new Array(indentSize + 1).join(indentCharacter);
        var indentLevel = 0;
        var nestedLevel = 0;

        function indent() {
            indentLevel++;
            basebaseIndentString += singleIndent;
        }

        function outdent() {
            indentLevel--;
            basebaseIndentString = basebaseIndentString.slice(0, -indentSize);
        }

        var print = {};
        print["{"] = function(ch) {
            print.singleSpace();
            output.push(ch);
            print.newLine();
        };
        print["}"] = function(ch) {
            print.newLine();
            output.push(ch);
            print.newLine();
        };

        print._lastCharWhitespace = function() {
            return whiteRe.test(output[output.length - 1]);
        };

        print.newLine = function(keepWhitespace) {
            if (output.length) {
                if (!keepWhitespace && output[output.length - 1] !== '\n') {
                    print.trim();
                }

                output.push('\n');

                if (basebaseIndentString) {
                    output.push(basebaseIndentString);
                }
            }
        };
        print.singleSpace = function() {
            if (output.length && !print._lastCharWhitespace()) {
                output.push(' ');
            }
        };

        print.preserveSingleSpace = function() {
            if (isAfterSpace) {
                print.singleSpace();
            }
        };

        print.trim = function() {
            while (print._lastCharWhitespace()) {
                output.pop();
            }
        };


        var output = [];
        /*_____________________--------------------_____________________*/

        var insideRule = false;
        var insidePropertyValue = false;
        var enteringConditionalGroup = false;
        var top_ch = '';
        var last_top_ch = '';

        while (true) {
            var whitespace = skipWhitespace();
            var isAfterSpace = whitespace !== '';
            var isAfterNewline = whitespace.indexOf('\n') !== -1;
            last_top_ch = top_ch;
            top_ch = ch;

            if (!ch) {
                break;
            } else if (ch === '/' && peek() === '*') { /* css comment */
                var header = indentLevel === 0;

                if (isAfterNewline || header) {
                    print.newLine();
                }

                output.push(eatComment());
                print.newLine();
                if (header) {
                    print.newLine(true);
                }
            } else if (ch === '/' && peek() === '/') { // single line comment
                if (!isAfterNewline && last_top_ch !== '{' ) {
                    print.trim();
                }
                print.singleSpace();
                output.push(eatComment());
                print.newLine();
            } else if (ch === '@') {
                print.preserveSingleSpace();
                output.push(ch);

                // strip trailing space, if present, for hash property checks
                var variableOrRule = peekString(": ,;{}()[]/='\"");

                if (variableOrRule.match(/[ :]$/)) {
                    // we have a variable or pseudo-class, add it and insert one space before continuing
                    next();
                    variableOrRule = eatString(": ").replace(/\s$/, '');
                    output.push(variableOrRule);
                    print.singleSpace();
                }

                variableOrRule = variableOrRule.replace(/\s$/, '')

                // might be a nesting at-rule
                if (variableOrRule in NESTED_AT_RULE) {
                    nestedLevel += 1;
                    if (variableOrRule in CONDITIONAL_GROUP_RULE) {
                        enteringConditionalGroup = true;
                    }
                }
            } else if (ch === '#' && peek() === '{') {
              print.preserveSingleSpace();
              output.push(eatString('}'));
            } else if (ch === '{') {
                if (peek(true) === '}') {
                    eatWhitespace();
                    next();
                    print.singleSpace();
                    output.push("{}");
                    print.newLine();
                    if (newline_between_rules && indentLevel === 0) {
                        print.newLine(true);
                    }
                } else {
                    indent();
                    print["{"](ch);
                    // when entering conditional groups, only rulesets are allowed
                    if (enteringConditionalGroup) {
                        enteringConditionalGroup = false;
                        insideRule = (indentLevel > nestedLevel);
                    } else {
                        // otherwise, declarations are also allowed
                        insideRule = (indentLevel >= nestedLevel);
                    }
                }
            } else if (ch === '}') {
                outdent();
                print["}"](ch);
                insideRule = false;
                insidePropertyValue = false;
                if (nestedLevel) {
                    nestedLevel--;
                }
                if (newline_between_rules && indentLevel === 0) {
                    print.newLine(true);
                }
            } else if (ch === ":") {
                eatWhitespace();
                if ((insideRule || enteringConditionalGroup) &&
                    !(lookBack("&") || foundNestedPseudoClass())) {
                    // 'property: value' delimiter
                    // which could be in a conditional group query
                    insidePropertyValue = true;
                    output.push(':');
                    print.singleSpace();
                } else {
                    // sass/less parent reference don't use a space
                    // sass nested pseudo-class don't use a space
                    if (peek() === ":") {
                        // pseudo-element
                        next();
                        output.push("::");
                    } else {
                        // pseudo-class
                        output.push(':');
                    }
                }
            } else if (ch === '"' || ch === '\'') {
                print.preserveSingleSpace();
                output.push(eatString(ch));
            } else if (ch === ';') {
                insidePropertyValue = false;
                output.push(ch);
                print.newLine();
            } else if (ch === '(') { // may be a url
                if (lookBack("url")) {
                    output.push(ch);
                    eatWhitespace();
                    if (next()) {
                        if (ch !== ')' && ch !== '"' && ch !== '\'') {
                            output.push(eatString(')'));
                        } else {
                            pos--;
                        }
                    }
                } else {
                    parenLevel++;
                    print.preserveSingleSpace();
                    output.push(ch);
                    eatWhitespace();
                }
            } else if (ch === ')') {
                output.push(ch);
                parenLevel--;
            } else if (ch === ',') {
                output.push(ch);
                eatWhitespace();
                if (selectorSeparatorNewline && !insidePropertyValue && parenLevel < 1) {
                    print.newLine();
                } else {
                    print.singleSpace();
                }
            } else if (ch === ']') {
                output.push(ch);
            } else if (ch === '[') {
                print.preserveSingleSpace();
                output.push(ch);
            } else if (ch === '=') { // no whitespace before or after
                eatWhitespace()
                ch = '=';
                output.push(ch);
            } else {
                print.preserveSingleSpace();
                output.push(ch);
            }
        }


        var sweetCode = '';
        if (basebaseIndentString) {
            sweetCode += basebaseIndentString;
        }

        sweetCode += output.join('').replace(/[\r\n\t ]+$/, '');

        // establish end_with_newline
        if (end_with_newline) {
            sweetCode += '\n';
        }

        if (eol != '\n') {
            sweetCode = sweetCode.replace(/[\n]/g, eol);
        }

        return sweetCode;
    }

    function in_array(what, arr) {
        for (var i = 0; i < arr.length; i += 1) {
            if (arr[i] === what) {
                return true;
            }
        }
        return false;
    }

    function trim(s) {
        return s.replace(/^\s+|\s+$/g, '');
    }

    function ltrim(s) {
        return s.replace(/^\s+/g, '');
    }

    function rtrim(s) {
        return s.replace(/\s+$/g, '');
    }

    function js_beautify(js_source_text, options) {
        "use strict";
        var beautifier = new Beautifier(js_source_text, options);
        return beautifier.beautify();
    }

    var MODE = {
            BlockStatement: 'BlockStatement', // 'BLOCK'
            Statement: 'Statement', // 'STATEMENT'
            ObjectLiteral: 'ObjectLiteral', // 'OBJECT',
            ArrayLiteral: 'ArrayLiteral', //'[EXPRESSION]',
            ForInitializer: 'ForInitializer', //'(FOR-EXPRESSION)',
            Conditional: 'Conditional', //'(COND-EXPRESSION)',
            Expression: 'Expression' //'(EXPRESSION)'
        };

    function Beautifier(js_source_text, options) {
        "use strict";
        var output
        var tokens = [], token_pos;
        var Tokenizer;
        var current_token;
        var last_type, last_last_text, indent_string;
        var flags, previous_flags, flag_store;
        var prefix;

        var handlers, opt;
        var baseIndentString = '';

        handlers = {
            'TK_START_EXPR': handle_start_expr,
            'TK_END_EXPR': handle_end_expr,
            'TK_START_BLOCK': handle_start_block,
            'TK_END_BLOCK': handle_end_block,
            'TK_WORD': handle_word,
            'TK_RESERVED': handle_word,
            'TK_SEMICOLON': handle_semicolon,
            'TK_STRING': handle_string,
            'TK_EQUALS': handle_equals,
            'TK_OPERATOR': handle_operator,
            'TK_COMMA': handle_comma,
            'TK_BLOCK_COMMENT': handle_block_comment,
            'TK_COMMENT': handle_comment,
            'TK_DOT': handle_dot,
            'TK_UNKNOWN': handle_unknown,
            'TK_EOF': handle_eof
        };

        function create_flags(flags_base, mode) {
            var next_indent_level = 0;
            if (flags_base) {
                next_indent_level = flags_base.indentation_level;
                if (!output.just_added_newline() &&
                    flags_base.line_indent_level > next_indent_level) {
                    next_indent_level = flags_base.line_indent_level;
                }
            }

            var next_flags = {
                mode: mode,
                parent: flags_base,
                last_text: flags_base ? flags_base.last_text : '', // last token text
                last_word: flags_base ? flags_base.last_word : '', // last 'TK_WORD' passed
                declaration_statement: false,
                declaration_assignment: false,
                multiline_frame: false,
                if_block: false,
                else_block: false,
                do_block: false,
                do_while: false,
                in_case_statement: false, // switch(..){ INSIDE HERE }
                in_case: false, // we're on the exact line with "case 0:"
                case_body: false, // the indented case-action block
                indentation_level: next_indent_level,
                line_indent_level: flags_base ? flags_base.line_indent_level : next_indent_level,
                start_line_index: output.get_line_number(),
                ternary_depth: 0
            };
            return next_flags;
        }

        // Some interpreters have unexpected results with foo = baz || bar;
        options = options ? options : {};
        opt = {};

        // compatibility
        if (options.braces_on_own_line !== undefined) { //graceful handling of deprecated option
            opt.brace_style = options.braces_on_own_line ? "expand" : "collapse";
        }
        opt.brace_style = options.brace_style ? options.brace_style : (opt.brace_style ? opt.brace_style : "collapse");

        // graceful handling of deprecated option
        if (opt.brace_style === "expand-strict") {
            opt.brace_style = "expand";
        }


        opt.indent_size = options.indent_size ? parseInt(options.indent_size, 10) : 4;
        opt.indent_char = options.indent_char ? options.indent_char : ' ';
        opt.eol = options.eol ? options.eol : '\n';
        opt.preserve_newlines = (options.preserve_newlines === undefined) ? true : options.preserve_newlines;
        opt.break_chained_methods = (options.break_chained_methods === undefined) ? false : options.break_chained_methods;
        opt.max_preserve_newlines = (options.max_preserve_newlines === undefined) ? 0 : parseInt(options.max_preserve_newlines, 10);
        opt.space_in_paren = (options.space_in_paren === undefined) ? false : options.space_in_paren;
        opt.space_in_empty_paren = (options.space_in_empty_paren === undefined) ? false : options.space_in_empty_paren;
        opt.jslint_happy = (options.jslint_happy === undefined) ? false : options.jslint_happy;
        opt.space_after_anon_function = (options.space_after_anon_function === undefined) ? false : options.space_after_anon_function;
        opt.keep_array_indentation = (options.keep_array_indentation === undefined) ? false : options.keep_array_indentation;
        opt.space_before_conditional = (options.space_before_conditional === undefined) ? true : options.space_before_conditional;
        opt.unescape_strings = (options.unescape_strings === undefined) ? false : options.unescape_strings;
        opt.wrap_line_length = (options.wrap_line_length === undefined) ? 0 : parseInt(options.wrap_line_length, 10);
        opt.e4x = (options.e4x === undefined) ? false : options.e4x;
        opt.end_with_newline = (options.end_with_newline === undefined) ? false : options.end_with_newline;
        opt.comma_first = (options.comma_first === undefined) ? false : options.comma_first;

        // For testing of beautify ignore:start directive
        opt.test_output_raw = (options.test_output_raw === undefined) ? false : options.test_output_raw;

        // force opt.space_after_anon_function to true if opt.jslint_happy
        if(opt.jslint_happy) {
            opt.space_after_anon_function = true;
        }

        if(options.indent_with_tabs){
            opt.indent_char = '\t';
            opt.indent_size = 1;
        }

        opt.eol = opt.eol.replace(/\\r/, '\r').replace(/\\n/, '\n')

        //----------------------------------
        indent_string = '';
        while (opt.indent_size > 0) {
            indent_string += opt.indent_char;
            opt.indent_size -= 1;
        }

        var preindent_index = 0;
        if(js_source_text && js_source_text.length) {
            while ( (js_source_text.charAt(preindent_index) === ' ' ||
                    js_source_text.charAt(preindent_index) === '\t')) {
                baseIndentString += js_source_text.charAt(preindent_index);
                preindent_index += 1;
            }
            js_source_text = js_source_text.substring(preindent_index);
        }

        last_type = 'TK_START_BLOCK'; // last token type
        last_last_text = ''; // pre-last token text
        output = new Output(indent_string, baseIndentString);

        // If testing the ignore directive, start with output disable set to true
        output.raw = opt.test_output_raw;


        // Stack of parsing/formatting states, including MODE.
        // We tokenize, parse, and output in an almost purely a forward-only stream of token input
        // and formatted output.  This makes the beautifier less accurate than full parsers
        // but also far more tolerant of syntax errors.
        //
        // For example, the default mode is MODE.BlockStatement. If we see a '{' we push a new frame of type
        // MODE.BlockStatement on the the stack, even though it could be object literal.  If we later
        // encounter a ":", we'll switch to to MODE.ObjectLiteral.  If we then see a ";",
        // most full parsers would die, but the beautifier gracefully falls back to
        // MODE.BlockStatement and continues on.
        flag_store = [];
        set_mode(MODE.BlockStatement);

        this.beautify = function() {

            /*jshint onevar:true */
            var local_token, sweet_code;
            Tokenizer = new tokenizer(js_source_text, opt, indent_string);
            tokens = Tokenizer.tokenize();
            token_pos = 0;

            while (local_token = get_token()) {
                for(var i = 0; i < local_token.comments_before.length; i++) {
                    // The cleanest handling of inline comments is to treat them as though they aren't there.
                    // Just continue formatting and the behavior should be logical.
                    // Also ignore unknown tokens.  Again, this should result in better behavior.
                    handle_token(local_token.comments_before[i]);
                }
                handle_token(local_token);

                last_last_text = flags.last_text;
                last_type = local_token.type;
                flags.last_text = local_token.text;

                token_pos += 1;
            }

            sweet_code = output.get_code();
            if (opt.end_with_newline) {
                sweet_code += '\n';
            }

            if (opt.eol != '\n') {
                sweet_code = sweet_code.replace(/[\n]/g, opt.eol);
            }

            return sweet_code;
        };

        function handle_token(local_token) {
            var newlines = local_token.newlines;
            var keep_whitespace = opt.keep_array_indentation && is_array(flags.mode);

            if (keep_whitespace) {
                for (i = 0; i < newlines; i += 1) {
                    print_newline(i > 0);
                }
            } else {
                if (opt.max_preserve_newlines && newlines > opt.max_preserve_newlines) {
                    newlines = opt.max_preserve_newlines;
                }

                if (opt.preserve_newlines) {
                    if (local_token.newlines > 1) {
                        print_newline();
                        for (var i = 1; i < newlines; i += 1) {
                            print_newline(true);
                        }
                    }
                }
            }

            current_token = local_token;
            handlers[current_token.type]();
        }

        // we could use just string.split, but
        // IE doesn't like returning empty strings
        function split_newlines(s) {
            //return s.split(/\x0d\x0a|\x0a/);

            s = s.replace(/\x0d/g, '');
            var out = [],
                idx = s.indexOf("\n");
            while (idx !== -1) {
                out.push(s.substring(0, idx));
                s = s.substring(idx + 1);
                idx = s.indexOf("\n");
            }
            if (s.length) {
                out.push(s);
            }
            return out;
        }

        function allow_wrap_or_preserved_newline(force_linewrap) {
            force_linewrap = (force_linewrap === undefined) ? false : force_linewrap;

            // Never wrap the first token on a line
            if (output.just_added_newline()) {
                return
            }

            if ((opt.preserve_newlines && current_token.wanted_newline) || force_linewrap) {
                print_newline(false, true);
            } else if (opt.wrap_line_length) {
                var proposed_line_length = output.current_line.get_character_count() + current_token.text.length +
                    (output.space_before_token ? 1 : 0);
                if (proposed_line_length >= opt.wrap_line_length) {
                    print_newline(false, true);
                }
            }
        }

        function print_newline(force_newline, preserve_statement_flags) {
            if (!preserve_statement_flags) {
                if (flags.last_text !== ';' && flags.last_text !== ',' && flags.last_text !== '=' && last_type !== 'TK_OPERATOR') {
                    while (flags.mode === MODE.Statement && !flags.if_block && !flags.do_block) {
                        restore_mode();
                    }
                }
            }

            if (output.add_new_line(force_newline)) {
                flags.multiline_frame = true;
            }
        }

        function print_token_line_indentation() {
            if (output.just_added_newline()) {
                if (opt.keep_array_indentation && is_array(flags.mode) && current_token.wanted_newline) {
                    output.current_line.push(current_token.whitespace_before);
                    output.space_before_token = false;
                } else if (output.set_indent(flags.indentation_level)) {
                    flags.line_indent_level = flags.indentation_level;
                }
            }
        }

        function print_token(printable_token) {
            if (output.raw) {
                output.add_raw_token(current_token)
                return;
            }

            if (opt.comma_first && last_type === 'TK_COMMA'
                && output.just_added_newline()) {
                if(output.previous_line.last() === ',') {
                    output.previous_line.pop();
                    print_token_line_indentation();
                    output.add_token(',');
                    output.space_before_token = true;
                }
            }

            printable_token = printable_token || current_token.text;
            print_token_line_indentation();
            output.add_token(printable_token);
        }

        function indent() {
            flags.indentation_level += 1;
        }

        function deindent() {
            if (flags.indentation_level > 0 &&
                ((!flags.parent) || flags.indentation_level > flags.parent.indentation_level))
                flags.indentation_level -= 1;
        }

        function set_mode(mode) {
            if (flags) {
                flag_store.push(flags);
                previous_flags = flags;
            } else {
                previous_flags = create_flags(null, mode);
            }

            flags = create_flags(previous_flags, mode);
        }

        function is_array(mode) {
            return mode === MODE.ArrayLiteral;
        }

        function is_expression(mode) {
            return in_array(mode, [MODE.Expression, MODE.ForInitializer, MODE.Conditional]);
        }

        function restore_mode() {
            if (flag_store.length > 0) {
                previous_flags = flags;
                flags = flag_store.pop();
                if (previous_flags.mode === MODE.Statement) {
                    output.remove_redundant_indentation(previous_flags);
                }
            }
        }

        function start_of_object_property() {
            return flags.parent.mode === MODE.ObjectLiteral && flags.mode === MODE.Statement && (
                (flags.last_text === ':' && flags.ternary_depth === 0) || (last_type === 'TK_RESERVED' && in_array(flags.last_text, ['get', 'set'])));
        }

        function start_of_statement() {
            if (
                    (last_type === 'TK_RESERVED' && in_array(flags.last_text, ['var', 'let', 'const']) && current_token.type === 'TK_WORD') ||
                    (last_type === 'TK_RESERVED' && flags.last_text === 'do') ||
                    (last_type === 'TK_RESERVED' && flags.last_text === 'return' && !current_token.wanted_newline) ||
                    (last_type === 'TK_RESERVED' && flags.last_text === 'else' && !(current_token.type === 'TK_RESERVED' && current_token.text === 'if')) ||
                    (last_type === 'TK_END_EXPR' && (previous_flags.mode === MODE.ForInitializer || previous_flags.mode === MODE.Conditional)) ||
                    (last_type === 'TK_WORD' && flags.mode === MODE.BlockStatement
                        && !flags.in_case
                        && !(current_token.text === '--' || current_token.text === '++')
                        && last_last_text !== 'function'
                        && current_token.type !== 'TK_WORD' && current_token.type !== 'TK_RESERVED') ||
                    (flags.mode === MODE.ObjectLiteral && (
                        (flags.last_text === ':' && flags.ternary_depth === 0) || (last_type === 'TK_RESERVED' && in_array(flags.last_text, ['get', 'set']))))
                ) {

                set_mode(MODE.Statement);
                indent();

                if (last_type === 'TK_RESERVED' && in_array(flags.last_text, ['var', 'let', 'const']) && current_token.type === 'TK_WORD') {
                    flags.declaration_statement = true;
                }

                // Issue #276:
                // If starting a new statement with [if, for, while, do], push to a new line.
                // if (a) if (b) if(c) d(); else e(); else f();
                if (!start_of_object_property()) {
                    allow_wrap_or_preserved_newline(
                        current_token.type === 'TK_RESERVED' && in_array(current_token.text, ['do', 'for', 'if', 'while']));
                }

                return true;
            }
            return false;
        }

        function all_lines_start_with(lines, c) {
            for (var i = 0; i < lines.length; i++) {
                var line = trim(lines[i]);
                if (line.charAt(0) !== c) {
                    return false;
                }
            }
            return true;
        }

        function each_line_matches_indent(lines, indent) {
            var i = 0,
                len = lines.length,
                line;
            for (; i < len; i++) {
                line = lines[i];
                // allow empty lines to pass through
                if (line && line.indexOf(indent) !== 0) {
                    return false;
                }
            }
            return true;
        }

        function is_special_word(word) {
            return in_array(word, ['case', 'return', 'do', 'if', 'throw', 'else']);
        }

        function get_token(offset) {
            var index = token_pos + (offset || 0);
            return (index < 0 || index >= tokens.length) ? null : tokens[index];
        }

        function handle_start_expr() {
            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
            }

            var next_mode = MODE.Expression;
            if (current_token.text === '[') {

                if (last_type === 'TK_WORD' || flags.last_text === ')') {
                    // this is array index specifier, break immediately
                    // a[x], fn()[x]
                    if (last_type === 'TK_RESERVED' && in_array(flags.last_text, Tokenizer.line_starters)) {
                        output.space_before_token = true;
                    }
                    set_mode(next_mode);
                    print_token();
                    indent();
                    if (opt.space_in_paren) {
                        output.space_before_token = true;
                    }
                    return;
                }

                next_mode = MODE.ArrayLiteral;
                if (is_array(flags.mode)) {
                    if (flags.last_text === '[' ||
                        (flags.last_text === ',' && (last_last_text === ']' || last_last_text === '}'))) {
                        // ], [ goes to new line
                        // }, [ goes to new line
                        if (!opt.keep_array_indentation) {
                            print_newline();
                        }
                    }
                }

            } else {
                if (last_type === 'TK_RESERVED' && flags.last_text === 'for') {
                    next_mode = MODE.ForInitializer;
                } else if (last_type === 'TK_RESERVED' && in_array(flags.last_text, ['if', 'while'])) {
                    next_mode = MODE.Conditional;
                } else {
                    // next_mode = MODE.Expression;
                }
            }

            if (flags.last_text === ';' || last_type === 'TK_START_BLOCK') {
                print_newline();
            } else if (last_type === 'TK_END_EXPR' || last_type === 'TK_START_EXPR' || last_type === 'TK_END_BLOCK' || flags.last_text === '.') {
                // TODO: Consider whether forcing this is required.  Review failing tests when removed.
                allow_wrap_or_preserved_newline(current_token.wanted_newline);
                // do nothing on (( and )( and ][ and ]( and .(
            } else if (!(last_type === 'TK_RESERVED' && current_token.text === '(') && last_type !== 'TK_WORD' && last_type !== 'TK_OPERATOR') {
                output.space_before_token = true;
            } else if ((last_type === 'TK_RESERVED' && (flags.last_word === 'function' || flags.last_word === 'typeof')) ||
                (flags.last_text === '*' && last_last_text === 'function')) {
                // function() vs function ()
                if (opt.space_after_anon_function) {
                    output.space_before_token = true;
                }
            } else if (last_type === 'TK_RESERVED' && (in_array(flags.last_text, Tokenizer.line_starters) || flags.last_text === 'catch')) {
                if (opt.space_before_conditional) {
                    output.space_before_token = true;
                }
            }

            // Should be a space between await and an IIFE
            if(current_token.text === '(' && last_type === 'TK_RESERVED' && flags.last_word === 'await'){
                output.space_before_token = true;
            }

            // Support of this kind of newline preservation.
            // a = (b &&
            //     (c || d));
            if (current_token.text === '(') {
                if (last_type === 'TK_EQUALS' || last_type === 'TK_OPERATOR') {
                    if (!start_of_object_property()) {
                        allow_wrap_or_preserved_newline();
                    }
                }
            }

            set_mode(next_mode);
            print_token();
            if (opt.space_in_paren) {
                output.space_before_token = true;
            }

            // In all cases, if we newline while inside an expression it should be indented.
            indent();
        }

        function handle_end_expr() {
            // statements inside expressions are not valid syntax, but...
            // statements must all be closed when their container closes
            while (flags.mode === MODE.Statement) {
                restore_mode();
            }

            if (flags.multiline_frame) {
                allow_wrap_or_preserved_newline(current_token.text === ']' && is_array(flags.mode) && !opt.keep_array_indentation);
            }

            if (opt.space_in_paren) {
                if (last_type === 'TK_START_EXPR' && ! opt.space_in_empty_paren) {
                    // () [] no inner space in empty parens like these, ever, ref #320
                    output.trim();
                    output.space_before_token = false;
                } else {
                    output.space_before_token = true;
                }
            }
            if (current_token.text === ']' && opt.keep_array_indentation) {
                print_token();
                restore_mode();
            } else {
                restore_mode();
                print_token();
            }
            output.remove_redundant_indentation(previous_flags);

            // do {} while () // no statement required after
            if (flags.do_while && previous_flags.mode === MODE.Conditional) {
                previous_flags.mode = MODE.Expression;
                flags.do_block = false;
                flags.do_while = false;

            }
        }

        function handle_start_block() {
            // Check if this is should be treated as a ObjectLiteral
            var next_token = get_token(1)
            var second_token = get_token(2)
            if (second_token && (
                    (second_token.text === ':' && in_array(next_token.type, ['TK_STRING', 'TK_WORD', 'TK_RESERVED']))
                    || (in_array(next_token.text, ['get', 'set']) && in_array(second_token.type, ['TK_WORD', 'TK_RESERVED']))
                )) {
                // We don't support TypeScript,but we didn't break it for a very long time.
                // We'll try to keep not breaking it.
                if (!in_array(last_last_text, ['class','interface'])) {
                    set_mode(MODE.ObjectLiteral);
                } else {
                    set_mode(MODE.BlockStatement);
                }
            } else {
                set_mode(MODE.BlockStatement);
            }

            var empty_braces = !next_token.comments_before.length &&  next_token.text === '}';
            var empty_anonymous_function = empty_braces && flags.last_word === 'function' &&
                last_type === 'TK_END_EXPR';

            if (opt.brace_style === "expand" ||
                (opt.brace_style === "none" && current_token.wanted_newline)) {
                if (last_type !== 'TK_OPERATOR' &&
                    (empty_anonymous_function ||
                        last_type === 'TK_EQUALS' ||
                        (last_type === 'TK_RESERVED' && is_special_word(flags.last_text) && flags.last_text !== 'else'))) {
                    output.space_before_token = true;
                } else {
                    print_newline(false, true);
                }
            } else { // collapse
                if (last_type !== 'TK_OPERATOR' && last_type !== 'TK_START_EXPR') {
                    if (last_type === 'TK_START_BLOCK') {
                        print_newline();
                    } else {
                        output.space_before_token = true;
                    }
                } else {
                    // if TK_OPERATOR or TK_START_EXPR
                    if (is_array(previous_flags.mode) && flags.last_text === ',') {
                        if (last_last_text === '}') {
                            // }, { in array context
                            output.space_before_token = true;
                        } else {
                            print_newline(); // [a, b, c, {
                        }
                    }
                }
            }
            print_token();
            indent();
        }

        function handle_end_block() {
            // statements must all be closed when their container closes
            while (flags.mode === MODE.Statement) {
                restore_mode();
            }
            var empty_braces = last_type === 'TK_START_BLOCK';

            if (opt.brace_style === "expand") {
                if (!empty_braces) {
                    print_newline();
                }
            } else {
                // skip {}
                if (!empty_braces) {
                    if (is_array(flags.mode) && opt.keep_array_indentation) {
                        // we REALLY need a newline here, but newliner would skip that
                        opt.keep_array_indentation = false;
                        print_newline();
                        opt.keep_array_indentation = true;

                    } else {
                        print_newline();
                    }
                }
            }
            restore_mode();
            print_token();
        }

        function handle_word() {
            if (current_token.type === 'TK_RESERVED' && flags.mode !== MODE.ObjectLiteral &&
                in_array(current_token.text, ['set', 'get'])) {
                current_token.type = 'TK_WORD';
            }

            if (current_token.type === 'TK_RESERVED' && flags.mode === MODE.ObjectLiteral) {
                var next_token = get_token(1);
                if (next_token.text == ':') {
                    current_token.type = 'TK_WORD';
                }
            }

            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
            } else if (current_token.wanted_newline && !is_expression(flags.mode) &&
                (last_type !== 'TK_OPERATOR' || (flags.last_text === '--' || flags.last_text === '++')) &&
                last_type !== 'TK_EQUALS' &&
                (opt.preserve_newlines || !(last_type === 'TK_RESERVED' && in_array(flags.last_text, ['var', 'let', 'const', 'set', 'get'])))) {

                print_newline();
            }

            if (flags.do_block && !flags.do_while) {
                if (current_token.type === 'TK_RESERVED' && current_token.text === 'while') {
                    // do {} ## while ()
                    output.space_before_token = true;
                    print_token();
                    output.space_before_token = true;
                    flags.do_while = true;
                    return;
                } else {
                    // do {} should always have while as the next word.
                    // if we don't see the expected while, recover
                    print_newline();
                    flags.do_block = false;
                }
            }

            // if may be followed by else, or not
            // Bare/inline ifs are tricky
            // Need to unwind the modes correctly: if (a) if (b) c(); else d(); else e();
            if (flags.if_block) {
                if (!flags.else_block && (current_token.type === 'TK_RESERVED' && current_token.text === 'else')) {
                    flags.else_block = true;
                } else {
                    while (flags.mode === MODE.Statement) {
                        restore_mode();
                    }
                    flags.if_block = false;
                    flags.else_block = false;
                }
            }

            if (current_token.type === 'TK_RESERVED' && (current_token.text === 'case' || (current_token.text === 'default' && flags.in_case_statement))) {
                print_newline();
                if (flags.case_body || opt.jslint_happy) {
                    // switch cases following one another
                    deindent();
                    flags.case_body = false;
                }
                print_token();
                flags.in_case = true;
                flags.in_case_statement = true;
                return;
            }

            if (current_token.type === 'TK_RESERVED' && current_token.text === 'function') {
                if (in_array(flags.last_text, ['}', ';']) || (output.just_added_newline() && ! in_array(flags.last_text, ['[', '{', ':', '=', ',']))) {
                    // make sure there is a nice clean space of at least one blank line
                    // before a new function definition
                    if ( !output.just_added_blankline() && !current_token.comments_before.length) {
                        print_newline();
                        print_newline(true);
                    }
                }
                if (last_type === 'TK_RESERVED' || last_type === 'TK_WORD') {
                    if (last_type === 'TK_RESERVED' && in_array(flags.last_text, ['get', 'set', 'new', 'return', 'export', 'async'])) {
                        output.space_before_token = true;
                    } else if (last_type === 'TK_RESERVED' && flags.last_text === 'default' && last_last_text === 'export') {
                        output.space_before_token = true;
                    } else {
                        print_newline();
                    }
                } else if (last_type === 'TK_OPERATOR' || flags.last_text === '=') {
                    // foo = function
                    output.space_before_token = true;
                } else if (!flags.multiline_frame && (is_expression(flags.mode) || is_array(flags.mode))) {
                    // (function
                } else {
                    print_newline();
                }
            }

            if (last_type === 'TK_COMMA' || last_type === 'TK_START_EXPR' || last_type === 'TK_EQUALS' || last_type === 'TK_OPERATOR') {
                if (!start_of_object_property()) {
                    allow_wrap_or_preserved_newline();
                }
            }

            if (current_token.type === 'TK_RESERVED' &&  in_array(current_token.text, ['function', 'get', 'set'])) {
                print_token();
                flags.last_word = current_token.text;
                return;
            }

            prefix = 'NONE';

            if (last_type === 'TK_END_BLOCK') {
                if (!(current_token.type === 'TK_RESERVED' && in_array(current_token.text, ['else', 'catch', 'finally']))) {
                    prefix = 'NEWLINE';
                } else {
                    if (opt.brace_style === "expand" ||
                        opt.brace_style === "end-expand" ||
                        (opt.brace_style === "none" && current_token.wanted_newline)) {
                        prefix = 'NEWLINE';
                    } else {
                        prefix = 'SPACE';
                        output.space_before_token = true;
                    }
                }
            } else if (last_type === 'TK_SEMICOLON' && flags.mode === MODE.BlockStatement) {
                // TODO: Should this be for STATEMENT as well?
                prefix = 'NEWLINE';
            } else if (last_type === 'TK_SEMICOLON' && is_expression(flags.mode)) {
                prefix = 'SPACE';
            } else if (last_type === 'TK_STRING') {
                prefix = 'NEWLINE';
            } else if (last_type === 'TK_RESERVED' || last_type === 'TK_WORD' ||
                (flags.last_text === '*' && last_last_text === 'function')) {
                prefix = 'SPACE';
            } else if (last_type === 'TK_START_BLOCK') {
                prefix = 'NEWLINE';
            } else if (last_type === 'TK_END_EXPR') {
                output.space_before_token = true;
                prefix = 'NEWLINE';
            }

            if (current_token.type === 'TK_RESERVED' && in_array(current_token.text, Tokenizer.line_starters) && flags.last_text !== ')') {
                if (flags.last_text === 'else' || flags.last_text === 'export') {
                    prefix = 'SPACE';
                } else {
                    prefix = 'NEWLINE';
                }

            }

            if (current_token.type === 'TK_RESERVED' && in_array(current_token.text, ['else', 'catch', 'finally'])) {
                if (last_type !== 'TK_END_BLOCK' ||
                    opt.brace_style === "expand" ||
                    opt.brace_style === "end-expand" ||
                    (opt.brace_style === "none" && current_token.wanted_newline)) {
                    print_newline();
                } else {
                    output.trim(true);
                    var line = output.current_line;
                    // If we trimmed and there's something other than a close block before us
                    // put a newline back in.  Handles '} // comment' scenario.
                    if (line.last() !== '}') {
                        print_newline();
                    }
                    output.space_before_token = true;
                }
            } else if (prefix === 'NEWLINE') {
                if (last_type === 'TK_RESERVED' && is_special_word(flags.last_text)) {
                    // no newline between 'return nnn'
                    output.space_before_token = true;
                } else if (last_type !== 'TK_END_EXPR') {
                    if ((last_type !== 'TK_START_EXPR' || !(current_token.type === 'TK_RESERVED' && in_array(current_token.text, ['var', 'let', 'const']))) && flags.last_text !== ':') {
                        // no need to force newline on 'var': for (var x = 0...)
                        if (current_token.type === 'TK_RESERVED' && current_token.text === 'if' && flags.last_text === 'else') {
                            // no newline for } else if {
                            output.space_before_token = true;
                        } else {
                            print_newline();
                        }
                    }
                } else if (current_token.type === 'TK_RESERVED' && in_array(current_token.text, Tokenizer.line_starters) && flags.last_text !== ')') {
                    print_newline();
                }
            } else if (flags.multiline_frame && is_array(flags.mode) && flags.last_text === ',' && last_last_text === '}') {
                print_newline(); // }, in lists get a newline treatment
            } else if (prefix === 'SPACE') {
                output.space_before_token = true;
            }
            print_token();
            flags.last_word = current_token.text;

            if (current_token.type === 'TK_RESERVED' && current_token.text === 'do') {
                flags.do_block = true;
            }

            if (current_token.type === 'TK_RESERVED' && current_token.text === 'if') {
                flags.if_block = true;
            }
        }

        function handle_semicolon() {
            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
                // Semicolon can be the start (and end) of a statement
                output.space_before_token = false;
            }
            while (flags.mode === MODE.Statement && !flags.if_block && !flags.do_block) {
                restore_mode();
            }
            print_token();
        }

        function handle_string() {
            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
                // One difference - strings want at least a space before
                output.space_before_token = true;
            } else if (last_type === 'TK_RESERVED' || last_type === 'TK_WORD') {
                output.space_before_token = true;
            } else if (last_type === 'TK_COMMA' || last_type === 'TK_START_EXPR' || last_type === 'TK_EQUALS' || last_type === 'TK_OPERATOR') {
                if (!start_of_object_property()) {
                    allow_wrap_or_preserved_newline();
                }
            } else {
                print_newline();
            }
            print_token();
        }

        function handle_equals() {
            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
            }

            if (flags.declaration_statement) {
                // just got an '=' in a var-line, different formatting/line-breaking, etc will now be done
                flags.declaration_assignment = true;
            }
            output.space_before_token = true;
            print_token();
            output.space_before_token = true;
        }

        function handle_comma() {
            if (flags.declaration_statement) {
                if (is_expression(flags.parent.mode)) {
                    // do not break on comma, for(var a = 1, b = 2)
                    flags.declaration_assignment = false;
                }

                print_token();

                if (flags.declaration_assignment) {
                    flags.declaration_assignment = false;
                    print_newline(false, true);
                } else {
                    output.space_before_token = true;
                    // for comma-first, we want to allow a newline before the comma
                    // to turn into a newline after the comma, which we will fixup later
                    if (opt.comma_first) {
                        allow_wrap_or_preserved_newline();
                    }
                }
                return;
            }

            print_token();
            if (flags.mode === MODE.ObjectLiteral ||
                (flags.mode === MODE.Statement && flags.parent.mode === MODE.ObjectLiteral)) {
                if (flags.mode === MODE.Statement) {
                    restore_mode();
                }
                print_newline();
            } else {
                // EXPR or DO_BLOCK
                output.space_before_token = true;
                // for comma-first, we want to allow a newline before the comma
                // to turn into a newline after the comma, which we will fixup later
                if (opt.comma_first) {
                    allow_wrap_or_preserved_newline();
                }
            }

        }

        function handle_operator() {
            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
            }

            if (last_type === 'TK_RESERVED' && is_special_word(flags.last_text)) {
                // "return" had a special handling in TK_WORD. Now we need to return the favor
                output.space_before_token = true;
                print_token();
                return;
            }

            // hack for actionscript's import .*;
            if (current_token.text === '*' && last_type === 'TK_DOT') {
                print_token();
                return;
            }

            if (current_token.text === ':' && flags.in_case) {
                flags.case_body = true;
                indent();
                print_token();
                print_newline();
                flags.in_case = false;
                return;
            }

            if (current_token.text === '::') {
                // no spaces around exotic namespacing syntax operator
                print_token();
                return;
            }

            // Allow line wrapping between operators
            if (last_type === 'TK_OPERATOR') {
                allow_wrap_or_preserved_newline();
            }

            var space_before = true;
            var space_after = true;

            if (in_array(current_token.text, ['--', '++', '!', '~']) || (in_array(current_token.text, ['-', '+']) && (in_array(last_type, ['TK_START_BLOCK', 'TK_START_EXPR', 'TK_EQUALS', 'TK_OPERATOR']) || in_array(flags.last_text, Tokenizer.line_starters) || flags.last_text === ','))) {
                // unary operators (and binary +/- pretending to be unary) special cases

                space_before = false;
                space_after = false;

                // http://www.ecma-international.org/ecma-262/5.1/#sec-7.9.1
                // if there is a newline between -- or ++ and anything else we should preserve it.
                if (current_token.wanted_newline && (current_token.text === '--' || current_token.text === '++')) {
                    print_newline(false, true);
                }

                if (flags.last_text === ';' && is_expression(flags.mode)) {
                    // for (;; ++i)
                    //        ^^^
                    space_before = true;
                }

                if (last_type === 'TK_RESERVED') {
                    space_before = true;
                } else if (last_type === 'TK_END_EXPR') {
                    space_before = !(flags.last_text === ']' && (current_token.text === '--' || current_token.text === '++'));
                } else if (last_type === 'TK_OPERATOR') {
                    // a++ + ++b;
                    // a - -b
                    space_before = in_array(current_token.text, ['--', '-', '++', '+']) && in_array(flags.last_text, ['--', '-', '++', '+']);
                    // + and - are not unary when preceeded by -- or ++ operator
                    // a-- + b
                    // a * +b
                    // a - -b
                    if (in_array(current_token.text, ['+', '-']) && in_array(flags.last_text, ['--', '++'])) {
                        space_after = true;
                    }
                }

                if ((flags.mode === MODE.BlockStatement || flags.mode === MODE.Statement) && (flags.last_text === '{' || flags.last_text === ';')) {
                    // { foo; --i }
                    // foo(); --bar;
                    print_newline();
                }
            } else if (current_token.text === ':') {
                if (flags.ternary_depth === 0) {
                    // Colon is invalid javascript outside of ternary and object, but do our best to guess what was meant.
                    space_before = false;
                } else {
                    flags.ternary_depth -= 1;
                }
            } else if (current_token.text === '?') {
                flags.ternary_depth += 1;
            } else if (current_token.text === '*' && last_type === 'TK_RESERVED' && flags.last_text === 'function') {
                space_before = false;
                space_after = false;
            }
            output.space_before_token = output.space_before_token || space_before;
            print_token();
            output.space_before_token = space_after;
        }

        function handle_block_comment() {
            if (output.raw) {
                output.add_raw_token(current_token)
                if (current_token.directives && current_token.directives['preserve'] === 'end') {
                    // If we're testing the raw output behavior, do not allow a directive to turn it off.
                    if (!opt.test_output_raw) {
                        output.raw = false;
                    }
                }
                return;
            }

            if (current_token.directives) {
                print_newline(false, true);
                print_token();
                if (current_token.directives['preserve'] === 'start') {
                    output.raw = true;
                }
                print_newline(false, true);
                return;
            }

            // inline block
            if (!acorn.newline.test(current_token.text) && !current_token.wanted_newline) {
                output.space_before_token = true;
                print_token();
                output.space_before_token = true;
                return;
            }

            var lines = split_newlines(current_token.text);
            var j; // iterator for this case
            var javadoc = false;
            var starless = false;
            var lastIndent = current_token.whitespace_before;
            var lastIndentLength = lastIndent.length;

            // block comment starts with a new line
            print_newline(false, true);
            if (lines.length > 1) {
                if (all_lines_start_with(lines.slice(1), '*')) {
                    javadoc = true;
                }
                else if (each_line_matches_indent(lines.slice(1), lastIndent)) {
                    starless = true;
                }
            }

            // first line always indented
            print_token(lines[0]);
            for (j = 1; j < lines.length; j++) {
                print_newline(false, true);
                if (javadoc) {
                    // javadoc: reformat and re-indent
                    print_token(' ' + ltrim(lines[j]));
                } else if (starless && lines[j].length > lastIndentLength) {
                    // starless: re-indent non-empty content, avoiding trim
                    print_token(lines[j].substring(lastIndentLength));
                } else {
                    // normal comments output raw
                    output.add_token(lines[j]);
                }
            }

            // for comments of more than one line, make sure there's a new line after
            print_newline(false, true);
        }

        function handle_comment() {
            if (current_token.wanted_newline) {
                print_newline(false, true);
            } else {
                output.trim(true);
            }

            output.space_before_token = true;
            print_token();
            print_newline(false, true);
        }

        function handle_dot() {
            if (start_of_statement()) {
                // The conditional starts the statement if appropriate.
            }

            if (last_type === 'TK_RESERVED' && is_special_word(flags.last_text)) {
                output.space_before_token = true;
            } else {
                // allow preserved newlines before dots in general
                // force newlines on dots after close paren when break_chained - for bar().baz()
                allow_wrap_or_preserved_newline(flags.last_text === ')' && opt.break_chained_methods);
            }

            print_token();
        }

        function handle_unknown() {
            print_token();

            if (current_token.text[current_token.text.length - 1] === '\n') {
                print_newline();
            }
        }

        function handle_eof() {
            // Unwind any open statements
            while (flags.mode === MODE.Statement) {
                restore_mode();
            }
        }
    }


    function OutputLine(parent) {
        var _character_count = 0;
        // use indent_count as a marker for lines that have preserved indentation
        var _indent_count = -1;

        var _items = [];
        var _empty = true;

        this.set_indent = function(level) {
            _character_count = parent.baseIndentLength + level * parent.indent_length
            _indent_count = level;
        }

        this.get_character_count = function() {
            return _character_count;
        }

        this.is_empty = function() {
            return _empty;
        }

        this.last = function() {
            if (!this._empty) {
              return _items[_items.length - 1];
            } else {
              return null;
            }
        }

        this.push = function(input) {
            _items.push(input);
            _character_count += input.length;
            _empty = false;
        }

        this.pop = function() {
            var item = null;
            if (!_empty) {
                item = _items.pop();
                _character_count -= item.length;
                _empty = _items.length === 0;
            }
            return item;
        }

        this.remove_indent = function() {
            if (_indent_count > 0) {
                _indent_count -= 1;
                _character_count -= parent.indent_length
            }
        }

        this.trim = function() {
            while (this.last() === ' ') {
                var item = _items.pop();
                _character_count -= 1;
            }
            _empty = _items.length === 0;
        }

        this.toString = function() {
            var result = '';
            if (!this._empty) {
                if (_indent_count >= 0) {
                    result = parent.indent_cache[_indent_count];
                }
                result += _items.join('')
            }
            return result;
        }
    }

    function Output(indent_string, baseIndentString) {
        baseIndentString = baseIndentString || '';
        this.indent_cache = [ baseIndentString ];
        this.baseIndentLength = baseIndentString.length;
        this.indent_length = indent_string.length;
        this.raw = false;

        var lines =[];
        this.baseIndentString = baseIndentString;
        this.indent_string = indent_string;
        this.previous_line = null;
        this.current_line = null;
        this.space_before_token = false;

        this.add_outputline = function() {
            this.previous_line = this.current_line;
            this.current_line = new OutputLine(this);
            lines.push(this.current_line);
        }

        // initialize
        this.add_outputline();


        this.get_line_number = function() {
            return lines.length;
        }

        // Using object instead of string to allow for later expansion of info about each line
        this.add_new_line = function(force_newline) {
            if (this.get_line_number() === 1 && this.just_added_newline()) {
                return false; // no newline on start of file
            }

            if (force_newline || !this.just_added_newline()) {
                if (!this.raw) {
                    this.add_outputline();
                }
                return true;
            }

            return false;
        }

        this.get_code = function() {
            var sweet_code = lines.join('\n').replace(/[\r\n\t ]+$/, '');
            return sweet_code;
        }

        this.set_indent = function(level) {
            // Never indent your first output indent at the start of the file
            if (lines.length > 1) {
                while(level >= this.indent_cache.length) {
                    this.indent_cache.push(this.indent_cache[this.indent_cache.length - 1] + this.indent_string);
                }

                this.current_line.set_indent(level);
                return true;
            }
            this.current_line.set_indent(0);
            return false;
        }

        this.add_raw_token = function(token) {
            for (var x = 0; x < token.newlines; x++) {
                this.add_outputline();
            }
            this.current_line.push(token.whitespace_before);
            this.current_line.push(token.text);
            this.space_before_token = false;
        }

        this.add_token = function(printable_token) {
            this.add_space_before_token();
            this.current_line.push(printable_token);
        }

        this.add_space_before_token = function() {
            if (this.space_before_token && !this.just_added_newline()) {
                this.current_line.push(' ');
            }
            this.space_before_token = false;
        }

        this.remove_redundant_indentation = function (frame) {
            // This implementation is effective but has some issues:
            //     - can cause line wrap to happen too soon due to indent removal
            //           after wrap points are calculated
            // These issues are minor compared to ugly indentation.

            if (frame.multiline_frame ||
                frame.mode === MODE.ForInitializer ||
                frame.mode === MODE.Conditional) {
                return;
            }

            // remove one indent from each line inside this section
            var index = frame.start_line_index;
            var line;

            var output_length = lines.length;
            while (index < output_length) {
                lines[index].remove_indent();
                index++;
            }
        }

        this.trim = function(eat_newlines) {
            eat_newlines = (eat_newlines === undefined) ? false : eat_newlines;

            this.current_line.trim(indent_string, baseIndentString);

            while (eat_newlines && lines.length > 1 &&
                this.current_line.is_empty()) {
                lines.pop();
                this.current_line = lines[lines.length - 1]
                this.current_line.trim();
            }

            this.previous_line = lines.length > 1 ? lines[lines.length - 2] : null;
        }

        this.just_added_newline = function() {
            return this.current_line.is_empty();
        }

        this.just_added_blankline = function() {
            if (this.just_added_newline()) {
                if (lines.length === 1) {
                    return true; // start of the file and newline = blank
                }

                var line = lines[lines.length - 2];
                return line.is_empty();
            }
            return false;
        }
    }


    var Token = function(type, text, newlines, whitespace_before, mode, parent) {
        this.type = type;
        this.text = text;
        this.comments_before = [];
        this.newlines = newlines || 0;
        this.wanted_newline = newlines > 0;
        this.whitespace_before = whitespace_before || '';
        this.parent = null;
        this.directives = null;
    }

    function tokenizer(input, opts, indent_string) {

        var whitespace = "\n\r\t ".split('');
        var digit = /[0-9]/;
        var digit_oct = /[01234567]/;
        var digit_hex = /[0123456789abcdefABCDEF]/;

        var punct = ('+ - * / % & ++ -- = += -= *= /= %= == === != !== > < >= <= >> << >>> >>>= >>= <<= && &= | || ! ~ , : ? ^ ^= |= :: =>').split(' ');
        // words which should always start on new line.
        this.line_starters = 'continue,try,throw,return,var,let,const,if,switch,case,default,for,while,break,function,import,export'.split(',');
        var reserved_words = this.line_starters.concat(['do', 'in', 'else', 'get', 'set', 'new', 'catch', 'finally', 'typeof', 'yield', 'async', 'await']);

        //  /* ... */ comment ends with nearest */ or end of file
        var block_comment_pattern = /([\s\S]*?)((?:\*\/)|$)/g;

        // comment ends just before nearest linefeed or end of file
        var comment_pattern = /([^\n\r\u2028\u2029]*)/g;

        var directives_block_pattern = /\/\* beautify( \w+[:]\w+)+ \*\//g;
        var directive_pattern = / (\w+)[:](\w+)/g;
        var directives_end_ignore_pattern = /([\s\S]*?)((?:\/\*\sbeautify\signore:end\s\*\/)|$)/g;

        var template_pattern = /((<\?php|<\?=)[\s\S]*?\?>)|(<%[\s\S]*?%>)/g

        var n_newlines, whitespace_before_token, in_html_comment, tokens, parser_pos;
        var input_length;

        this.tokenize = function() {
            // cache the source's length.
            input_length = input.length
            parser_pos = 0;
            in_html_comment = false
            tokens = [];

            var next, last;
            var token_values;
            var open = null;
            var open_stack = [];
            var comments = [];

            while (!(last && last.type === 'TK_EOF')) {
                token_values = tokenize_next();
                next = new Token(token_values[1], token_values[0], n_newlines, whitespace_before_token);
                while(next.type === 'TK_COMMENT' || next.type === 'TK_BLOCK_COMMENT' || next.type === 'TK_UNKNOWN') {
                    if (next.type === 'TK_BLOCK_COMMENT') {
                        next.directives = token_values[2];
                    }
                    comments.push(next);
                    token_values = tokenize_next();
                    next = new Token(token_values[1], token_values[0], n_newlines, whitespace_before_token);
                }

                if (comments.length) {
                    next.comments_before = comments;
                    comments = [];
                }

                if (next.type === 'TK_START_BLOCK' || next.type === 'TK_START_EXPR') {
                    next.parent = last;
                    open_stack.push(open);
                    open = next;
                }  else if ((next.type === 'TK_END_BLOCK' || next.type === 'TK_END_EXPR') &&
                    (open && (
                        (next.text === ']' && open.text === '[') ||
                        (next.text === ')' && open.text === '(') ||
                        (next.text === '}' && open.text === '{')))) {
                    next.parent = open.parent;
                    open = open_stack.pop();
                }

                tokens.push(next);
                last = next;
            }

            return tokens;
        }

        function get_directives (text) {
            if (!text.match(directives_block_pattern)) {
                return null;
            }

            var directives = {};
            directive_pattern.lastIndex = 0;
            var directive_match = directive_pattern.exec(text);

            while (directive_match) {
                directives[directive_match[1]] = directive_match[2];
                directive_match = directive_pattern.exec(text);
            }

            return directives;
        }

        function tokenize_next() {
            var i, resulting_string;
            var whitespace_on_this_line = [];

            n_newlines = 0;
            whitespace_before_token = '';

            if (parser_pos >= input_length) {
                return ['', 'TK_EOF'];
            }

            var last_token;
            if (tokens.length) {
                last_token = tokens[tokens.length-1];
            } else {
                // For the sake of tokenizing we can pretend that there was on open brace to start
                last_token = new Token('TK_START_BLOCK', '{');
            }


            var c = input.charAt(parser_pos);
            parser_pos += 1;

            while (in_array(c, whitespace)) {

                if (acorn.newline.test(c)) {
                    if (!(c === '\n' && input.charAt(parser_pos-2) === '\r')) {
                        n_newlines += 1;
                        whitespace_on_this_line = [];
                    }
                } else {
                    whitespace_on_this_line.push(c);
                }

                if (parser_pos >= input_length) {
                    return ['', 'TK_EOF'];
                }

                c = input.charAt(parser_pos);
                parser_pos += 1;
            }

            if(whitespace_on_this_line.length) {
                whitespace_before_token = whitespace_on_this_line.join('');
            }

            if (digit.test(c)) {
                var allow_decimal = true;
                var allow_e = true;
                var local_digit = digit;

                if (c === '0' && parser_pos < input_length && /[Xxo]/.test(input.charAt(parser_pos))) {
                    // switch to hex/oct number, no decimal or e, just hex/oct digits
                    allow_decimal = false;
                    allow_e = false;
                    c += input.charAt(parser_pos);
                    parser_pos += 1;
                    local_digit = /[o]/.test(input.charAt(parser_pos)) ? digit_oct : digit_hex;
                } else {
                    // we know this first loop will run.  It keeps the logic simpler.
                    c = '';
                    parser_pos -= 1;
                }

                // Add the digits
                while (parser_pos < input_length && local_digit.test(input.charAt(parser_pos))) {
                    c += input.charAt(parser_pos);
                    parser_pos += 1;

                    if (allow_decimal && parser_pos < input_length && input.charAt(parser_pos) === '.') {
                        c += input.charAt(parser_pos);
                        parser_pos += 1;
                        allow_decimal = false;
                    }

                    if (allow_e && parser_pos < input_length && /[Ee]/.test(input.charAt(parser_pos))) {
                        c += input.charAt(parser_pos);
                        parser_pos += 1;

                        if (parser_pos < input_length && /[+-]/.test(input.charAt(parser_pos))) {
                            c += input.charAt(parser_pos);
                            parser_pos += 1;
                        }

                        allow_e = false;
                        allow_decimal = false;
                    }
                }

                return [c, 'TK_WORD'];
            }

            if (acorn.isIdentifierStart(input.charCodeAt(parser_pos-1))) {
                if (parser_pos < input_length) {
                    while (acorn.isIdentifierChar(input.charCodeAt(parser_pos))) {
                        c += input.charAt(parser_pos);
                        parser_pos += 1;
                        if (parser_pos === input_length) {
                            break;
                        }
                    }
                }

                if (!(last_token.type === 'TK_DOT' ||
                        (last_token.type === 'TK_RESERVED' && in_array(last_token.text, ['set', 'get'])))
                    && in_array(c, reserved_words)) {
                    if (c === 'in') { // hack for 'in' operator
                        return [c, 'TK_OPERATOR'];
                    }
                    return [c, 'TK_RESERVED'];
                }

                return [c, 'TK_WORD'];
            }

            if (c === '(' || c === '[') {
                return [c, 'TK_START_EXPR'];
            }

            if (c === ')' || c === ']') {
                return [c, 'TK_END_EXPR'];
            }

            if (c === '{') {
                return [c, 'TK_START_BLOCK'];
            }

            if (c === '}') {
                return [c, 'TK_END_BLOCK'];
            }

            if (c === ';') {
                return [c, 'TK_SEMICOLON'];
            }

            if (c === '/') {
                var comment = '';
                // peek for comment /* ... */
                if (input.charAt(parser_pos) === '*') {
                    parser_pos += 1;
                    block_comment_pattern.lastIndex = parser_pos;
                    var comment_match = block_comment_pattern.exec(input);
                    comment = '/*' + comment_match[0];
                    parser_pos += comment_match[0].length;
                    var directives = get_directives(comment);
                    if (directives && directives['ignore'] === 'start') {
                        directives_end_ignore_pattern.lastIndex = parser_pos;
                        comment_match = directives_end_ignore_pattern.exec(input)
                        comment += comment_match[0];
                        parser_pos += comment_match[0].length;
                    }
                    comment = comment.replace(acorn.lineBreak, '\n');
                    return [comment, 'TK_BLOCK_COMMENT', directives];
                }
                // peek for comment // ...
                if (input.charAt(parser_pos) === '/') {
                    parser_pos += 1;
                    comment_pattern.lastIndex = parser_pos;
                    var comment_match = comment_pattern.exec(input);
                    comment = '//' + comment_match[0];
                    parser_pos += comment_match[0].length;
                    return [comment, 'TK_COMMENT'];
                }

            }

            if (c === '`' || c === "'" || c === '"' || // string
                (
                    (c === '/') || // regexp
                    (opts.e4x && c === "<" && input.slice(parser_pos - 1).match(/^<([-a-zA-Z:0-9_.]+|{[^{}]*}|!\[CDATA\[[\s\S]*?\]\])(\s+[-a-zA-Z:0-9_.]+\s*=\s*('[^']*'|"[^"]*"|{.*?}))*\s*(\/?)\s*>/)) // xml
                ) && ( // regex and xml can only appear in specific locations during parsing
                    (last_token.type === 'TK_RESERVED' && in_array(last_token.text , ['return', 'case', 'throw', 'else', 'do', 'typeof', 'yield'])) ||
                    (last_token.type === 'TK_END_EXPR' && last_token.text === ')' &&
                        last_token.parent && last_token.parent.type === 'TK_RESERVED' && in_array(last_token.parent.text, ['if', 'while', 'for'])) ||
                    (in_array(last_token.type, ['TK_COMMENT', 'TK_START_EXPR', 'TK_START_BLOCK',
                        'TK_END_BLOCK', 'TK_OPERATOR', 'TK_EQUALS', 'TK_EOF', 'TK_SEMICOLON', 'TK_COMMA'
                    ]))
                )) {

                var sep = c,
                    esc = false,
                    has_char_escapes = false;

                resulting_string = c;

                if (sep === '/') {
                    //
                    // handle regexp
                    //
                    var in_char_class = false;
                    while (parser_pos < input_length &&
                            ((esc || in_char_class || input.charAt(parser_pos) !== sep) &&
                            !acorn.newline.test(input.charAt(parser_pos)))) {
                        resulting_string += input.charAt(parser_pos);
                        if (!esc) {
                            esc = input.charAt(parser_pos) === '\\';
                            if (input.charAt(parser_pos) === '[') {
                                in_char_class = true;
                            } else if (input.charAt(parser_pos) === ']') {
                                in_char_class = false;
                            }
                        } else {
                            esc = false;
                        }
                        parser_pos += 1;
                    }
                } else if (opts.e4x && sep === '<') {
                    //
                    // handle e4x xml literals
                    //
                    var xmlRegExp = /<(\/?)([-a-zA-Z:0-9_.]+|{[^{}]*}|!\[CDATA\[[\s\S]*?\]\])(\s+[-a-zA-Z:0-9_.]+\s*=\s*('[^']*'|"[^"]*"|{.*?}))*\s*(\/?)\s*>/g;
                    var xmlStr = input.slice(parser_pos - 1);
                    var match = xmlRegExp.exec(xmlStr);
                    if (match && match.index === 0) {
                        var rootTag = match[2];
                        var depth = 0;
                        while (match) {
                            var isEndTag = !! match[1];
                            var tagName = match[2];
                            var isSingletonTag = ( !! match[match.length - 1]) || (tagName.slice(0, 8) === "![CDATA[");
                            if (tagName === rootTag && !isSingletonTag) {
                                if (isEndTag) {
                                    --depth;
                                } else {
                                    ++depth;
                                }
                            }
                            if (depth <= 0) {
                                break;
                            }
                            match = xmlRegExp.exec(xmlStr);
                        }
                        var xmlLength = match ? match.index + match[0].length : xmlStr.length;
                        xmlStr = xmlStr.slice(0, xmlLength);
                        parser_pos += xmlLength - 1;
                        xmlStr = xmlStr.replace(acorn.lineBreak, '\n');
                        return [xmlStr, "TK_STRING"];
                    }
                } else {
                    //
                    // handle string
                    //
                    // Template strings can travers lines without escape characters.
                    // Other strings cannot
                    while (parser_pos < input_length &&
                            (esc || (input.charAt(parser_pos) !== sep &&
                            (sep === '`' || !acorn.newline.test(input.charAt(parser_pos)))))) {
                        // Handle \r\n linebreaks after escapes or in template strings
                        if ((esc || sep === '`') && acorn.newline.test(input.charAt(parser_pos))) {
                            if (input.charAt(parser_pos) === '\r' && input.charAt(parser_pos + 1) === '\n') {
                                parser_pos += 1;
                            }
                            resulting_string += '\n';
                        } else {
                            resulting_string += input.charAt(parser_pos);
                        }
                        if (esc) {
                            if (input.charAt(parser_pos) === 'x' || input.charAt(parser_pos) === 'u') {
                                has_char_escapes = true;
                            }
                            esc = false;
                        } else {
                            esc = input.charAt(parser_pos) === '\\';
                        }
                        parser_pos += 1;
                    }

                }

                if (has_char_escapes && opts.unescape_strings) {
                    resulting_string = unescape_string(resulting_string);
                }

                if (parser_pos < input_length && input.charAt(parser_pos) === sep) {
                    resulting_string += sep;
                    parser_pos += 1;

                    if (sep === '/') {
                        // regexps may have modifiers /regexp/MOD , so fetch those, too
                        // Only [gim] are valid, but if the user puts in garbage, do what we can to take it.
                        while (parser_pos < input_length && acorn.isIdentifierStart(input.charCodeAt(parser_pos))) {
                            resulting_string += input.charAt(parser_pos);
                            parser_pos += 1;
                        }
                    }
                }
                return [resulting_string, 'TK_STRING'];
            }

            if (c === '#') {

                if (tokens.length === 0 && input.charAt(parser_pos) === '!') {
                    // shebang
                    resulting_string = c;
                    while (parser_pos < input_length && c !== '\n') {
                        c = input.charAt(parser_pos);
                        resulting_string += c;
                        parser_pos += 1;
                    }
                    return [trim(resulting_string) + '\n', 'TK_UNKNOWN'];
                }



                // Spidermonkey-specific sharp variables for circular references
                // https://developer.mozilla.org/En/Sharp_variables_in_JavaScript
                // http://mxr.mozilla.org/mozilla-central/source/js/src/jsscan.cpp around line 1935
                var sharp = '#';
                if (parser_pos < input_length && digit.test(input.charAt(parser_pos))) {
                    do {
                        c = input.charAt(parser_pos);
                        sharp += c;
                        parser_pos += 1;
                    } while (parser_pos < input_length && c !== '#' && c !== '=');
                    if (c === '#') {
                        //
                    } else if (input.charAt(parser_pos) === '[' && input.charAt(parser_pos + 1) === ']') {
                        sharp += '[]';
                        parser_pos += 2;
                    } else if (input.charAt(parser_pos) === '{' && input.charAt(parser_pos + 1) === '}') {
                        sharp += '{}';
                        parser_pos += 2;
                    }
                    return [sharp, 'TK_WORD'];
                }
            }

            if (c === '<' && (input.charAt(parser_pos) === '?' || input.charAt(parser_pos) === '%')) {
                template_pattern.lastIndex = parser_pos - 1;
                var template_match = template_pattern.exec(input);
                if(template_match) {
                    c = template_match[0];
                    parser_pos += c.length - 1;
                    c = c.replace(acorn.lineBreak, '\n');
                    return [c, 'TK_STRING'];
                }
            }

            if (c === '<' && input.substring(parser_pos - 1, parser_pos + 3) === '<!--') {
                parser_pos += 3;
                c = '<!--';
                while (!acorn.newline.test(input.charAt(parser_pos)) && parser_pos < input_length) {
                    c += input.charAt(parser_pos);
                    parser_pos++;
                }
                in_html_comment = true;
                return [c, 'TK_COMMENT'];
            }

            if (c === '-' && in_html_comment && input.substring(parser_pos - 1, parser_pos + 2) === '-->') {
                in_html_comment = false;
                parser_pos += 2;
                return ['-->', 'TK_COMMENT'];
            }

            if (c === '.') {
                return [c, 'TK_DOT'];
            }

            if (in_array(c, punct)) {
                while (parser_pos < input_length && in_array(c + input.charAt(parser_pos), punct)) {
                    c += input.charAt(parser_pos);
                    parser_pos += 1;
                    if (parser_pos >= input_length) {
                        break;
                    }
                }

                if (c === ',') {
                    return [c, 'TK_COMMA'];
                } else if (c === '=') {
                    return [c, 'TK_EQUALS'];
                } else {
                    return [c, 'TK_OPERATOR'];
                }
            }

            return [c, 'TK_UNKNOWN'];
        }


        function unescape_string(s) {
            var esc = false,
                out = '',
                pos = 0,
                s_hex = '',
                escaped = 0,
                c;

            while (esc || pos < s.length) {

                c = s.charAt(pos);
                pos++;

                if (esc) {
                    esc = false;
                    if (c === 'x') {
                        // simple hex-escape \x24
                        s_hex = s.substr(pos, 2);
                        pos += 2;
                    } else if (c === 'u') {
                        // unicode-escape, \u2134
                        s_hex = s.substr(pos, 4);
                        pos += 4;
                    } else {
                        // some common escape, e.g \n
                        out += '\\' + c;
                        continue;
                    }
                    if (!s_hex.match(/^[0123456789abcdefABCDEF]+$/)) {
                        // some weird escaping, bail out,
                        // leaving whole string intact
                        return s;
                    }

                    escaped = parseInt(s_hex, 16);

                    if (escaped >= 0x00 && escaped < 0x20) {
                        // leave 0x00...0x1f escaped
                        if (c === 'x') {
                            out += '\\x' + s_hex;
                        } else {
                            out += '\\u' + s_hex;
                        }
                        continue;
                    } else if (escaped === 0x22 || escaped === 0x27 || escaped === 0x5c) {
                        // single-quote, apostrophe, backslash - escape these
                        out += '\\' + String.fromCharCode(escaped);
                    } else if (c === 'x' && escaped > 0x7e && escaped <= 0xff) {
                        // we bail out on \x7f..\xff,
                        // leaving whole string escaped,
                        // as it's probably completely binary
                        return s;
                    } else {
                        out += String.fromCharCode(escaped);
                    }
                } else if (c === '\\') {
                    esc = true;
                } else {
                    out += c;
                }
            }
            return out;
        }

    }

    /* jshint ignore:end */
    /* jscs:enable */

    return {
      run: run
    }
  };


  'use strict';

  $.extend($.FE.DEFAULTS, {
    codeMirror: true,
    codeMirrorOptions: {
      lineNumbers: true,
      tabMode: 'indent',
      indentWithTabs: true,
      lineWrapping: true,
      mode: 'text/html',
      tabSize: 2
    },
    codeBeautifierOptions: {
      end_with_newline: true,
      indent_inner_html: true,
      extra_liners: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre', 'ul', 'ol', 'table', 'dl'],
      brace_style: 'expand',
      indent_char: '\t',
      indent_size: 1,
      wrap_line_length: 0
    }
  })

  $.FE.PLUGINS.codeView = function (editor) {
    var $html_area;
    var code_mirror;

    /**
     * Check if code view is enabled.
     */
    function isActive () {
      return editor.$box.hasClass('fr-code-view');
    }

    function get () {
      if (code_mirror) {
        return code_mirror.getValue();
      } else {
        return $html_area.val();
      }
    }

    /**
     * Get back to edit mode.
     */
    function _showText ($btn) {
      var html = get();

      // Code mirror enabled.
      editor.html.set(html);

      // Blur the element.
      editor.$el.blur();

      // Toolbar no longer disabled.
      editor.$tb.find(' > .fr-command').not($btn).removeClass('fr-disabled');
      $btn.removeClass('fr-active');

      editor.events.focus(true);
      editor.placeholder.refresh();

      editor.undo.saveStep();
    }

    /**
     * Get to code mode.
     */
    function _showHTML ($btn) {
      if (!$html_area) _initArea();

      // Enable code mirror.
      if (!code_mirror && editor.opts.codeMirror && typeof CodeMirror != 'undefined') {
        code_mirror = CodeMirror.fromTextArea($html_area.get(0), editor.opts.codeMirrorOptions);
      }

      editor.undo.saveStep();

      // Clean white tags but ignore selection.
      editor.html.cleanEmptyTags();
      editor.html.cleanWhiteTags(true);

      // Blur the element.
      if (editor.core.hasFocus()) {
        if (!editor.core.isEmpty()) {
          editor.selection.save();
          editor.$el.find('.fr-marker[data-type="true"]:first').replaceWith('<span class="fr-tmp fr-sm">F</span>');
          editor.$el.find('.fr-marker[data-type="false"]:last').replaceWith('<span class="fr-tmp fr-em">F</span>');
        }
      }

      // Get HTML.
      var html = editor.html.get(false, true);
      editor.$el.find('span.fr-tmp').remove();

      if (editor.core.hasFocus()) editor.$el.blur();

      html = html.replace(/<span class="fr-tmp fr-sm">F<\/span>/, 'FROALA-SM');
      html = html.replace(/<span class="fr-tmp fr-em">F<\/span>/, 'FROALA-EM');

      // Beautify HTML.
      if (editor.codeBeautifier) {
        html = editor.codeBeautifier.run(html, editor.opts.codeBeautifierOptions);
      }

      var s_index;
      var e_index;

      // Code mirror is enabled.
      if (code_mirror) {
        s_index = html.indexOf('FROALA-SM');
        e_index = html.indexOf('FROALA-EM');

        if (s_index > e_index) {
          s_index = e_index;
        }
        else {
          e_index = e_index - 9;
        }

        html = html.replace(/FROALA-SM/g, '').replace(/FROALA-EM/g, '')
        var s_line = html.substring(0, s_index).length - html.substring(0, s_index).replace(/\n/g, '').length;
        var e_line = html.substring(0, e_index).length - html.substring(0, e_index).replace(/\n/g, '').length;

        s_index = html.substring(0, s_index).length - html.substring(0, html.substring(0, s_index).lastIndexOf('\n') + 1).length;
        e_index = html.substring(0, e_index).length - html.substring(0, html.substring(0, e_index).lastIndexOf('\n')  + 1).length;

        code_mirror.setSize(null, editor.opts.height ? editor.opts.height : 'auto');
        if (editor.opts.heightMin) editor.$box.find('.CodeMirror-scroll').css('min-height', editor.opts.heightMin);
        code_mirror.setValue(html);
        code_mirror.focus();
        code_mirror.setSelection({ line: s_line, ch: s_index }, { line: e_line, ch: e_index })
        code_mirror.refresh();
        code_mirror.clearHistory();
      }

      // No code mirror.
      else {
        s_index = html.indexOf('FROALA-SM');
        e_index = html.indexOf('FROALA-EM') - 9;

        if (editor.opts.heightMin) {
          $html_area.css('min-height', editor.opts.heightMin);
        }

        if (editor.opts.height || editor.opts.heightMax) {
          $html_area.css('max-height', editor.opts.height || editor.opts.heightMax);
        }

        $html_area.val(html.replace(/FROALA-SM/g, '').replace(/FROALA-EM/g, ''));
        $html_area.focus();
        $html_area.get(0).setSelectionRange(s_index, e_index);
      }

      // Disable buttons.
      editor.$tb.find(' > .fr-command').not($btn).addClass('fr-disabled');
      $btn.addClass('fr-active');

      if (!editor.helpers.isMobile() && editor.opts.toolbarInline) {
        editor.toolbar.hide();
      }
    }

    /**
     * Toggle the code view.
     */
    function toggle (val) {
      if (typeof val == 'undefined') val = !isActive();

      var $btn = editor.$tb.find('.fr-command[data-cmd="html"]');

      if (!val) {
        editor.$box.toggleClass('fr-code-view', false);
        _showText($btn);
      } else {
        editor.popups.hideAll();
        editor.$box.toggleClass('fr-code-view', true);
        _showHTML($btn);
      }
    }

    /**
     * Destroy.
     */
    function _destroy () {
      if (isActive()) {
        toggle(editor.$tb.find('button[data-cmd="html"]'));
      }

      if (code_mirror) code_mirror.toTextArea();
      $html_area.val('').removeData().remove();
      $html_area = null;

      if ($back_button) {
        $back_button.remove();
        $back_button = null;
      }
    }

    function _initArea () {
      // Add the coding textarea to the wrapper.
      $html_area = $('<textarea class="fr-code" tabindex="-1">');
      editor.$wp.append($html_area);

      $html_area.attr('dir', editor.opts.direction);

      // Exit code view button for inline toolbar.
      if (editor.opts.toolbarInline) {
        $back_button = $('<a data-cmd="html" title="Code View" class="fr-command fr-btn html-switch' + (editor.helpers.isMobile() ? '' : ' fr-desktop') + '" role="button" tabindex="-1"><i class="fa fa-code"></i></button>');
        editor.$box.append($back_button);

        editor.events.bindClick(editor.$box, 'a.html-switch', function () {
          toggle(false);
        });
      }

      var cancel = function () {
        return !isActive();
      }

      // Disable refresh of the buttons while enabled.
      editor.events.on('buttons.refresh', cancel);
      editor.events.on('copy', cancel, true);
      editor.events.on('cut', cancel, true);
      editor.events.on('paste', cancel, true);

      editor.events.on('destroy', _destroy, true);

      editor.events.on('html.set', function () {
        if (isActive()) toggle(true);
      });

      editor.events.on('form.submit', function () {
        if (isActive()) {
          // Code mirror enabled.
          editor.html.set(get());

          editor.events.trigger('contentChanged', [], true);
        }
      }, true);
    }

    /**
     * Initialize.
     */
    var $back_button;
    function _init () {
      if (!editor.$wp) return false;
    }

    return {
      _init: _init,
      toggle: toggle,
      isActive: isActive,
      get: get
    }
  };

  $.FE.RegisterCommand('html', {
    title: 'Code View',
    undo: false,
    focus: false,
    forcedRefresh: true,
    callback: function () {
      this.codeView.toggle();
    },
    plugin: 'codeView'
  })

  $.FE.DefineIcon('html', {
    NAME: 'code'
  });


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'colors.picker': '[_BUTTONS_][_TEXT_COLORS_][_BACKGROUND_COLORS_]'
  })

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    colorsText: [
      '#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
      '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
      '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
      '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', 'REMOVE'
    ],
    colorsBackground: [
      '#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
      '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
      '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
      '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', 'REMOVE'
    ],
    colorsStep: 7,
    colorsDefaultTab: 'text',
    colorsButtons: ['colorsBack', '|', '-']
  });

  $.FE.PLUGINS.colors = function (editor) {
    /*
     * Show the colors popup.
     */
    function _showColorsPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="color"]');

      var $popup = editor.popups.get('colors.picker');
      if (!$popup) $popup = _initColorsPopup();

      if (!$popup.hasClass('fr-active')) {
        // Colors popup
        editor.popups.setContainer('colors.picker', editor.$tb);

        // Refresh selected color.
        _refreshColor($popup.find('.fr-selected-tab').attr('data-param1'));

        // Colors popup left and top position.
        if ($btn.is(':visible')) {
          var left = $btn.offset().left + $btn.outerWidth() / 2;
          var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
          editor.popups.show('colors.picker', left, top, $btn.outerHeight());
        }
        else {
          editor.position.forSelection($popup);
          editor.popups.show('colors.picker');
        }
      }
    }

    /*
     * Hide colors popup.
     */
    function _hideColorsPopup () {
      // Hide popup.
      editor.popups.hide('colors.picker');
    }

    /**
     * Init the colors popup.
     */
    function _initColorsPopup () {
      var colors_buttons = '<div class="fr-buttons fr-colors-buttons">';

      if (editor.opts.toolbarInline) {
        // Colors buttons.
        if (editor.opts.colorsButtons.length > 0) {
          colors_buttons += editor.button.buildList(editor.opts.colorsButtons)
        }
      }

      colors_buttons += _colorsTabsHTML() + '</div>';

      var template = {
        buttons: colors_buttons,
        text_colors: _colorPickerHTML('text'),
        background_colors: _colorPickerHTML('background')
      };

      // Create popup.
      var $popup = editor.popups.create('colors.picker', template);

      return $popup;
    }

    /*
     * HTML for the color picker text and background tabs.
     */
    function _colorsTabsHTML () {
      var tabs_html = '<div class="fr-colors-tabs">';

      // Text tab.
      tabs_html += '<span class="fr-colors-tab ' + (editor.opts.colorsDefaultTab == 'background' ? '' : 'fr-selected-tab ') + 'fr-command" data-param1="text" data-cmd="colorChangeSet" title="' + editor.language.translate('Text') + '">' + editor.language.translate('Text') + '</span>';

      // Background tab.
      tabs_html += '<span class="fr-colors-tab ' + (editor.opts.colorsDefaultTab == 'background' ? 'fr-selected-tab ' : '') + 'fr-command" data-param1="background" data-cmd="colorChangeSet" title="' + editor.language.translate('Background') + '">' + editor.language.translate('Background') + '</span>';

      return tabs_html + '</div>';
    }

    /*
     * HTML for the color picker colors.
     */
    function _colorPickerHTML (tab) {
      // Get colors according to tab name.
      var colors = (tab == 'text' ? editor.opts.colorsText : editor.opts.colorsBackground);

      // Create colors html.
      var colors_html = '<div class="fr-color-set fr-' + tab + '-color' + ((editor.opts.colorsDefaultTab == tab || (editor.opts.colorsDefaultTab != 'text' && editor.opts.colorsDefaultTab != 'background' && tab == 'text')) ? ' fr-selected-set' : '') + '">';

      // Add colors.
      for (var i = 0; i < colors.length; i++) {
        if (i !== 0 && i % editor.opts.colorsStep === 0) {
          colors_html += '<br>';
        }

        if (colors[i] != 'REMOVE') {
          colors_html += '<span class="fr-command fr-select-color" style="background: ' + colors[i] + ';" data-cmd="' + tab + 'Color" data-param1="' + colors[i] + '"></span>';
        }

        else {
          colors_html += '<span class="fr-command fr-select-color" data-cmd="' + tab + 'Color" data-param1="REMOVE" title="' + editor.language.translate('Clear Formatting') + '">' + editor.icon.create('remove') + '</span>';
        }
      }

      return colors_html + '</div>';
    }

    /*
     * Show the current selected color.
     */
    function _refreshColor (tab) {
      var $popup = editor.popups.get('colors.picker');
      var $element = $(editor.selection.element());

      // The color css property.
      var color_type;
      if (tab == 'background') {
        color_type = 'background-color';
      }
      else {
        color_type = 'color';
      }

      // Remove current color selection.
      $popup.find('.fr-' + tab + '-color .fr-select-color').removeClass('fr-selected-color');

      // Find the selected color.
      while ($element.get(0) != editor.$el.get(0)) {
        // Transparent or black.
        if ($element.css(color_type) == 'transparent' || $element.css(color_type) == 'rgba(0, 0, 0, 0)') {
          $element = $element.parent();
        }

        // Select the correct color.
        else {
          $popup.find('.fr-' + tab + '-color .fr-select-color[data-param1="' + editor.helpers.RGBToHex($element.css(color_type)) + '"]').addClass('fr-selected-color');
          break;
        }
      }
    }

    /*
     * Change the colors' tab.
     */
    function _changeSet ($tab, val) {
      // Only on the tab that is not selected yet. On left click only.
      if (!$tab.hasClass('fr-selected-tab')) {
        // Switch selected tab.
        $tab.siblings().removeClass('fr-selected-tab');
        $tab.addClass('fr-selected-tab');

        // Switch the color set.
        $tab.parents('.fr-popup').find('.fr-color-set').removeClass('fr-selected-set');
        $tab.parents('.fr-popup').find('.fr-color-set.fr-' + val + '-color').addClass('fr-selected-set');

        // Refresh selected color.
        _refreshColor(val);
      }
    }

    /*
     * Change background color.
     */
    function background (val) {
      // Set background  color.
      if (val != 'REMOVE') {
        editor.format.applyStyle('background-color', editor.helpers.HEXtoRGB(val));
      }

      // Remove background color.
      else {
        editor.format.removeStyle('background-color');
      }

      _hideColorsPopup();
    }

    /*
     * Change text color.
     */
    function text (val) {
      // Set text color.
      if (val != 'REMOVE') {
        editor.format.applyStyle('color', editor.helpers.HEXtoRGB(val));
      }

      // Remove text color.
      else {
        editor.format.removeStyle('color');
      }

      _hideColorsPopup();
    }

    /*
     * Go back to the inline editor.
     */
    function back () {
      editor.popups.hide('colors.picker');
      editor.toolbar.showInline();
    }

    return {
      showColorsPopup: _showColorsPopup,
      hideColorsPopup: _hideColorsPopup,
      changeSet: _changeSet,
      background: background,
      text: text,
      back: back
    }
  }

  // Toolbar colors button.
  $.FE.DefineIcon('colors', { NAME: 'tint' });
  $.FE.RegisterCommand('color', {
    title: 'Colors',
    undo: false,
    focus: true,
    refreshOnCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('colors.picker')) {
        this.colors.showColorsPopup();
      }
      else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('colors.picker');
      }
    },
    plugin: 'colors'
  });

  // Select text color command.
  $.FE.RegisterCommand('textColor', {
    undo: true,
    callback: function (cmd, val) {
      this.colors.text(val);
    }
  });

  // Select background color command.
  $.FE.RegisterCommand('backgroundColor', {
    undo: true,
    callback: function (cmd, val) {
      this.colors.background(val);
    }
  });

  $.FE.RegisterCommand('colorChangeSet', {
    undo: false,
    focus: false,
    callback: function (cmd, val) {
      var $tab = this.popups.get('colors.picker').find('.fr-command[data-cmd="' + cmd + '"][data-param1="' + val + '"]');
      this.colors.changeSet($tab, val);
    }
  });

  // Colors back.
  $.FE.DefineIcon('colorsBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('colorsBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.colors.back();
    }
  });

  $.FE.DefineIcon('remove', { NAME: 'eraser' });


  'use strict';
  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    dragInline: true
  });


  $.FE.PLUGINS.draggable = function (editor) {
    function _dragStart (e) {
      // Image with link.
      if (e.target && e.target.tagName == 'A' && e.target.childNodes.length == 1 && e.target.childNodes[0].tagName == 'IMG') {
        e.target = e.target.childNodes[0];
      }

      if (!$(e.target).hasClass('fr-draggable')) {
        e.preventDefault();
        return false;
      }

      $(e.target).addClass('fr-dragging');

      if (editor.opts.dragInline) {
        editor.$el.attr('contenteditable', true);
      }
      else {
        editor.$el.attr('contenteditable', false);
      }

      if (editor.opts.toolbarInline) editor.toolbar.hide();

      // Save in undo step if we cannot do.
      if (!editor.undo.canDo()) editor.undo.saveStep();

      if (!editor.browser.msie && !editor.browser.edge) {
        editor.selection.clear();
      }

      e.originalEvent.dataTransfer.setData('text', 'Froala');
    }

    function _tagOK (tag_under) {
      return !(tag_under && (tag_under.tagName == 'HTML' || tag_under.tagName == 'BODY' || editor.node.isElement(tag_under)));
    }

    function _setHelperSize (top, left, width) {
      if (editor.opts.iframe) {
        top += editor.$iframe.offset().top;
        left += editor.$iframe.offset().left;
      }

      if ($draggable_helper.offset().top != top) $draggable_helper.css('top', top);
      if ($draggable_helper.offset().left != left) $draggable_helper.css('left', left);
      if ($draggable_helper.width() != width) $draggable_helper.css('width', width);
    }

    function _positionHelper (e) {
      // The tag under the mouse cursor.
      var tag_under = editor.doc.elementFromPoint(e.originalEvent.pageX - editor.win.pageXOffset, e.originalEvent.pageY - editor.win.pageYOffset);
      if (!_tagOK(tag_under)) {

        // Look above for the closest tag.
        var top_offset = 0;
        var top_tag = tag_under;
        while (!_tagOK(top_tag) && top_tag == tag_under && e.originalEvent.pageY - editor.win.pageYOffset - top_offset > 0) {
          top_offset++;
          top_tag = editor.doc.elementFromPoint(e.originalEvent.pageX - editor.win.pageXOffset, e.originalEvent.pageY - editor.win.pageYOffset - top_offset);
        }
        if (!_tagOK(top_tag) || ($draggable_helper && editor.$el.find(top_tag).length === 0 && top_tag != $draggable_helper.get(0))) { top_tag = null; }

        // Look below for the closest tag.
        var bottom_offset = 0;
        var bottom_tag = tag_under;
        while (!_tagOK(bottom_tag) && bottom_tag == tag_under && e.originalEvent.pageY - editor.win.pageYOffset + bottom_offset < $(editor.doc).height()) {
          bottom_offset++;
          bottom_tag = editor.doc.elementFromPoint(e.originalEvent.pageX - editor.win.pageXOffset, e.originalEvent.pageY - editor.win.pageYOffset + bottom_offset);
        }

        if (!_tagOK(bottom_tag) || ($draggable_helper &&  editor.$el.find(bottom_tag).length === 0  && bottom_tag != $draggable_helper.get(0))) { bottom_tag = null; }

        if (bottom_tag == null && top_tag) tag_under = top_tag;
        else if (bottom_tag && top_tag == null) tag_under = bottom_tag;
        else if (bottom_tag && top_tag) {
          tag_under = (top_offset < bottom_offset ? top_tag : bottom_tag);
        }
        else {
          tag_under = null;
        }
      }

      // Stop if tag under is draggable helper.
      if ($(tag_under).hasClass('fr-drag-helper')) return false;

      // Get block parent.
      if (tag_under && !editor.node.isBlock(tag_under)) {
        tag_under = editor.node.blockParent(tag_under);
      }

      // Normalize TABLE parent.
      if (tag_under && ['TD', 'TH', 'TR', 'THEAD', 'TBODY'].indexOf(tag_under.tagName) >= 0) {
        tag_under = $(tag_under).parents('table').get(0);
      }

      // Normalize LIST parent.
      if (tag_under && ['LI'].indexOf(tag_under.tagName) >= 0) {
        tag_under = $(tag_under).parents('UL, OL').get(0);
      }

      if (tag_under && !$(tag_under).hasClass('fr-drag-helper')) {
        // Init helper.
        if (!$draggable_helper) {
          if (!$.FE.$draggable_helper) $.FE.$draggable_helper = $('<div class="fr-drag-helper"></div>');

          $draggable_helper = $.FE.$draggable_helper;

          editor.events.on('shared.destroy', function () {
            $draggable_helper.html('').removeData().remove();
            $draggable_helper = null;
          }, true);
        }

        var above;
        var mouse_y = e.originalEvent.pageY;

        if (mouse_y < $(tag_under).offset().top + $(tag_under).outerHeight() / 2) above = true;
        else above = false;

        var $tag_under = $(tag_under);
        var margin = 0 ;

        // Should go below and there is no tag below.
        if (!above && $tag_under.next().length === 0) {
          if ($draggable_helper.data('fr-position') != 'after' || !$tag_under.is($draggable_helper.data('fr-tag'))) {
            margin = parseFloat($tag_under.css('margin-bottom')) || 0;

            _setHelperSize(
              $tag_under.offset().top + $(tag_under).height() + margin / 2  - editor.$box.offset().top,
              $tag_under.offset().left - editor.win.pageXOffset - editor.$box.offset().left,
              $tag_under.width()
            );

            $draggable_helper.data('fr-position', 'after');
          }
        }
        else {
          // Should go below then we take the next tag.
          if (!above) {
            $tag_under = $tag_under.next();
          }

          if ($draggable_helper.data('fr-position') != 'before' || !$tag_under.is($draggable_helper.data('fr-tag'))) {
            if ($tag_under.prev().length > 0) {
              margin = parseFloat($tag_under.prev().css('margin-bottom')) || 0;
            }
            margin = Math.max(margin, parseFloat($tag_under.css('margin-top')) || 0);

            _setHelperSize(
              $tag_under.offset().top - margin / 2  - editor.$box.offset().top,
              $tag_under.offset().left - editor.win.pageXOffset  - editor.$box.offset().left,
              $tag_under.width()
            )

            $draggable_helper.data('fr-position', 'before');
          }
        }

        $draggable_helper.data('fr-tag', $tag_under);

        $draggable_helper.addClass('fr-visible');
        $draggable_helper.appendTo(editor.$box);
      }
      else if ($draggable_helper && editor.$box.find($draggable_helper).length > 0) {
        $draggable_helper.removeClass('fr-visible');
      }
    }

    function _dragOver (e) {
      e.originalEvent.dataTransfer.dropEffect = 'move';

      if (!editor.opts.dragInline) {
        e.preventDefault();

        _positionHelper(e);
      }

      else if (!_getDraggedEl() && (editor.browser.msie || editor.browser.edge)) {
        e.preventDefault();
      }
    }

    function _dragEnter (e) {
      e.originalEvent.dataTransfer.dropEffect = 'move';

      if (!editor.opts.dragInline) {
        e.preventDefault();
      }
    }

    function _documentDragEnd (e) {
      editor.$el.attr('contenteditable', true);
      var $draggedEl = editor.$el.find('.fr-dragging');

      if ($draggable_helper && $draggable_helper.hasClass('fr-visible') && editor.$box.find($draggable_helper).length) {
        _drop(e);
      }
      else if ($draggedEl.length) {
        e.preventDefault();
        e.stopPropagation();

        if ($draggable_helper && !$draggable_helper.hasClass('fr-visible')) {
          $draggedEl.removeClass('fr-dragging');
        }
      }

      if ($draggable_helper && editor.$box.find($draggable_helper).length) {
        $draggable_helper.removeClass('fr-visible');
      }
    }

    function _getDraggedEl () {
      var $draggedEl = null;

      // Search of the instance we're dragging from.
      for (var i = 0; i < $.FE.INSTANCES.length; i++) {
        $draggedEl = $.FE.INSTANCES[i].$el.find('.fr-dragging');
        if ($draggedEl.length) {
          return $draggedEl.get(0);
        }
      }
    }

    function _drop (e) {
      var $draggedEl;
      var inst;

      // Search of the instance we're dragging from.
      for (var i = 0; i < $.FE.INSTANCES.length; i++) {
        $draggedEl = $.FE.INSTANCES[i].$el.find('.fr-dragging');
        if ($draggedEl.length) {
          inst = $.FE.INSTANCES[i];
          break;
        }
      }

      if ($draggedEl.length) {
        // Cancel anything else.
        e.preventDefault();
        e.stopPropagation();

        if ($draggable_helper && $draggable_helper.hasClass('fr-visible') && editor.$box.find($draggable_helper).length) {
          $draggable_helper.data('fr-tag')[$draggable_helper.data('fr-position')]('<span class="fr-marker"></span>');
          $draggable_helper.removeClass('fr-visible');
        }
        else {
          var ok = editor.markers.insertAtPoint(e.originalEvent);
          if (ok === false) return false;
        }

        // Hide all popups.
        editor.popups.hideAll();

        // Save undo step if the current instance is different than the original one.
        if (inst != editor && !editor.undo.canDo()) {
          editor.undo.saveStep();
        }

        // Image with link.
        var $droppedEl = $draggedEl;
        if ($draggedEl.parent().is('A')) {
          $droppedEl = $draggedEl.parent();
        }

        // Replace marker with the dragged element.
        if (!editor.core.isEmpty()) {
          var $marker = editor.$el.find('.fr-marker');
          $marker.replaceWith($droppedEl);
          $draggedEl.after($.FE.MARKERS);
          editor.selection.restore();
        }
        else {
          editor.$el.html($droppedEl);
        }

        $draggedEl.removeClass('fr-dragging');
        editor.$el.find(editor.html.emptyBlockTagsQuery()).not('TD, TH, LI, .fr-inner').remove();
        editor.html.wrap();
        editor.html.fillEmptyBlocks();
        editor.undo.saveStep();

        if (editor.opts.iframe) editor.size.syncIframe();

        // Mark changes in the original instance as well.
        if (inst != editor) {
          inst.popups.hideAll();
          inst.$el.find(editor.html.emptyBlockTagsQuery()).not('TD, TH, LI, .fr-inner').remove();
          inst.html.wrap();
          inst.html.fillEmptyBlocks();
          inst.undo.saveStep();
          inst.events.trigger('element.dropped');

          if (inst.opts.iframe) inst.size.syncIframe();
        }

        editor.events.trigger('element.dropped', [$draggedEl]);

        // Stop bubbling.
        return false;
      }
    }

    /*
     * Initialize.
     */
    var $draggable_helper;
    function _init () {
      // Force drag inline when ENTER_BR is active.
      if (editor.opts.enter == $.FE.ENTER_BR) editor.opts.dragInline = true;

      // Starting to drag.
      editor.events.on('dragstart', _dragStart, true);

      // Inline dragging is off.
      editor.events.on('dragover', _dragOver, true);
      editor.events.on('dragenter', _dragEnter, true);

      // Document drop. Remove moving class.
      editor.events.on('document.dragend', _documentDragEnd, true);
      editor.events.on('document.drop', _documentDragEnd, true);

      // Drop.
      editor.events.on('drop', _drop, true);

      // Clean getting the HTML.
      editor.events.on('html.get', function (html) {
        html = html.replace(/<(div)((?:[\w\W]*?))class="([\w\W]*?)fr-drag-helper([\w\W]*?)"((?:[\w\W]*?))>((?:[\w\W]*?))<\/(div)>/g, '');

        return html;
      });
    }

    return {
      _init: _init
    }
  }


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    emoticons: '[_BUTTONS_][_EMOTICONS_]'
  })

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    emoticonsStep: 8,
    emoticonsSet: [
      { code: '1f600', desc: 'Grinning face' },
      { code: '1f601', desc: 'Grinning face with smiling eyes' },
      { code: '1f602', desc: 'Face with tears of joy' },
      { code: '1f603', desc: 'Smiling face with open mouth' },
      { code: '1f604', desc: 'Smiling face with open mouth and smiling eyes' },
      { code: '1f605', desc: 'Smiling face with open mouth and cold sweat' },
      { code: '1f606', desc: 'Smiling face with open mouth and tightly-closed eyes' },
      { code: '1f607', desc: 'Smiling face with halo' },

      { code: '1f608', desc: 'Smiling face with horns' },
      { code: '1f609', desc: 'Winking face' },
      { code: '1f60a', desc: 'Smiling face with smiling eyes' },
      { code: '1f60b', desc: 'Face savoring delicious food' },
      { code: '1f60c', desc: 'Relieved face' },
      { code: '1f60d', desc: 'Smiling face with heart-shaped eyes' },
      { code: '1f60e', desc: 'Smiling face with sunglasses' },
      { code: '1f60f', desc: 'Smirking face' },

      { code: '1f610', desc: 'Neutral face' },
      { code: '1f611', desc: 'Expressionless face' },
      { code: '1f612', desc: 'Unamused face' },
      { code: '1f613', desc: 'Face with cold sweat' },
      { code: '1f614', desc: 'Pensive face' },
      { code: '1f615', desc: 'Confused face' },
      { code: '1f616', desc: 'Confounded face' },
      { code: '1f617', desc: 'Kissing face' },

      { code: '1f618', desc: 'Face throwing a kiss' },
      { code: '1f619', desc: 'Kissing face with smiling eyes' },
      { code: '1f61a', desc: 'Kissing face with closed eyes' },
      { code: '1f61b', desc: 'Face with stuck out tongue' },
      { code: '1f61c', desc: 'Face with stuck out tongue and winking eye' },
      { code: '1f61d', desc: 'Face with stuck out tongue and tightly-closed eyes' },
      { code: '1f61e', desc: 'Disappointed face' },
      { code: '1f61f', desc: 'Worried face' },

      { code: '1f620', desc: 'Angry face' },
      { code: '1f621', desc: 'Pouting face' },
      { code: '1f622', desc: 'Crying face' },
      { code: '1f623', desc: 'Persevering face' },
      { code: '1f624', desc: 'Face with look of triumph' },
      { code: '1f625', desc: 'Disappointed but relieved face' },
      { code: '1f626', desc: 'Frowning face with open mouth' },
      { code: '1f627', desc: 'Anguished face' },

      { code: '1f628', desc: 'Fearful face' },
      { code: '1f629', desc: 'Weary face' },
      { code: '1f62a', desc: 'Sleepy face' },
      { code: '1f62b', desc: 'Tired face' },
      { code: '1f62c', desc: 'Grimacing face' },
      { code: '1f62d', desc: 'Loudly crying face' },
      { code: '1f62e', desc: 'Face with open mouth' },
      { code: '1f62f', desc: 'Hushed face' },

      { code: '1f630', desc: 'Face with open mouth and cold sweat' },
      { code: '1f631', desc: 'Face screaming in fear' },
      { code: '1f632', desc: 'Astonished face' },
      { code: '1f633', desc: 'Flushed face' },
      { code: '1f634', desc: 'Sleeping face' },
      { code: '1f635', desc: 'Dizzy face' },
      { code: '1f636', desc: 'Face without mouth' },
      { code: '1f637', desc: 'Face with medical mask' }
    ],
    emoticonsButtons: ['emoticonsBack', '|'],
    emoticonsUseImage: true
  });

  $.FE.PLUGINS.emoticons = function (editor) {
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
      var emoticons_html = '<div style="text-align: center">';

      // Add emoticons.
      for (var i = 0; i < editor.opts.emoticonsSet.length; i++) {
        if (i !== 0 && i % editor.opts.emoticonsStep === 0) {
          emoticons_html += '<br>';
        }

        emoticons_html += '<span class="fr-command fr-emoticon" data-cmd="insertEmoticon" title="' + editor.language.translate(editor.opts.emoticonsSet[i].desc) + '" data-param1="' + editor.opts.emoticonsSet[i].code + '">' + (editor.opts.emoticonsUseImage ? '<img src="' + 'https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/' + editor.opts.emoticonsSet[i].code + '.svg' + '"/>' : '&#x' + editor.opts.emoticonsSet[i].code + ';') + '</span>';
      }

      if (editor.opts.emoticonsUseImage) emoticons_html += '<p style="font-size: 12px; text-align: center; padding: 0 5px;">Emoji free by <a href="http://emojione.com/" target="_blank" rel="nofollow">Emoji One</a></p>';
      emoticons_html += '</div>';

      return emoticons_html;
    }

    /*
     * Insert emoticon.
     */
    function insert (emoticon, img) {
      // Insert emoticon.
      editor.html.insert('<span class="fr-emoticon fr-deletable' + (img ? ' fr-emoticon-img' : '') + '"' + (img ? ' style="background: url(' + img + ')' : '') + '">' + (img ? '&nbsp;' : emoticon) + '</span>' + '&nbsp;' + $.FE.MARKERS, true);
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
      var setDeletable = function () {
        var emtcs = editor.$el.get(0).querySelectorAll('.fr-emoticon:not(.fr-deletable)');
        for (var i = 0; i < emtcs.length; i++) {
          emtcs[i].className += ' fr-deletable';
        }
      }
      setDeletable();

      editor.events.on('html.set', setDeletable);

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
            if (range.startOffset === 0 && editor.selection.element() === el) {
              $(el).before($.FE.MARKERS + $.FE.INVISIBLE_SPACE);
            }
            else {
              $(el).after($.FE.INVISIBLE_SPACE + $.FE.MARKERS);
            }
            editor.selection.restore();
          }
        }
      });

      editor.events.on('keyup', function (e) {
        var emtcs = editor.$el.get(0).querySelectorAll('.fr-emoticon');

        for (var i = 0; i < emtcs.length; i++) {
          if (typeof emtcs[i].textContent != 'undefined' && emtcs[i].textContent.replace(/\u200B/gi, '').length === 0) {
            $(emtcs[i]).remove();
          }
        }

        if (!(e.which >= 37 && e.which <= 40)) {
          var el = inEmoticon();
          if (el && (el.className || '').indexOf('fr-emoticon-img')) {
            $(el).append($.FE.MARKERS);
            editor.selection.restore();
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
  $.FE.DefineIcon('emoticons', { NAME: 'smile-o' });
  $.FE.RegisterCommand('emoticons', {
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
    },
    plugin: 'emoticons'
  });

  // Insert emoticon command.
  $.FE.RegisterCommand('insertEmoticon', {
    callback: function (cmd, code) {
      // Insert emoticon.
      this.emoticons.insert('&#x' + code + ';', this.opts.emoticonsUseImage ? 'https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/' + code + '.svg' : null);

      // Hide emoticons popup.
      this.emoticons.hideEmoticonsPopup();
    }
  });

  // Emoticons back.
  $.FE.DefineIcon('emoticonsBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('emoticonsBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.emoticons.back();
    }
  });


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    entities: '&amp;&lt;&gt;&quot;&apos;&iexcl;&cent;&pound;&curren;&yen;&brvbar;&sect;&uml;&copy;&ordf;&laquo;&not;&shy;&reg;&macr;&deg;&plusmn;&sup2;&sup3;&acute;&micro;&para;&middot;&cedil;&sup1;&ordm;&raquo;&frac14;&frac12;&frac34;&iquest;&Agrave;&Aacute;&Acirc;&Atilde;&Auml;&Aring;&AElig;&Ccedil;&Egrave;&Eacute;&Ecirc;&Euml;&Igrave;&Iacute;&Icirc;&Iuml;&ETH;&Ntilde;&Ograve;&Oacute;&Ocirc;&Otilde;&Ouml;&times;&Oslash;&Ugrave;&Uacute;&Ucirc;&Uuml;&Yacute;&THORN;&szlig;&agrave;&aacute;&acirc;&atilde;&auml;&aring;&aelig;&ccedil;&egrave;&eacute;&ecirc;&euml;&igrave;&iacute;&icirc;&iuml;&eth;&ntilde;&ograve;&oacute;&ocirc;&otilde;&ouml;&divide;&oslash;&ugrave;&uacute;&ucirc;&uuml;&yacute;&thorn;&yuml;&OElig;&oelig;&Scaron;&scaron;&Yuml;&fnof;&circ;&tilde;&Alpha;&Beta;&Gamma;&Delta;&Epsilon;&Zeta;&Eta;&Theta;&Iota;&Kappa;&Lambda;&Mu;&Nu;&Xi;&Omicron;&Pi;&Rho;&Sigma;&Tau;&Upsilon;&Phi;&Chi;&Psi;&Omega;&alpha;&beta;&gamma;&delta;&epsilon;&zeta;&eta;&theta;&iota;&kappa;&lambda;&mu;&nu;&xi;&omicron;&pi;&rho;&sigmaf;&sigma;&tau;&upsilon;&phi;&chi;&psi;&omega;&thetasym;&upsih;&piv;&ensp;&emsp;&thinsp;&zwnj;&zwj;&lrm;&rlm;&ndash;&mdash;&lsquo;&rsquo;&sbquo;&ldquo;&rdquo;&bdquo;&dagger;&Dagger;&bull;&hellip;&permil;&prime;&Prime;&lsaquo;&rsaquo;&oline;&frasl;&euro;&image;&weierp;&real;&trade;&alefsym;&larr;&uarr;&rarr;&darr;&harr;&crarr;&lArr;&uArr;&rArr;&dArr;&hArr;&forall;&part;&exist;&empty;&nabla;&isin;&notin;&ni;&prod;&sum;&minus;&lowast;&radic;&prop;&infin;&ang;&and;&or;&cap;&cup;&int;&there4;&sim;&cong;&asymp;&ne;&equiv;&le;&ge;&sub;&sup;&nsub;&sube;&supe;&oplus;&otimes;&perp;&sdot;&lceil;&rceil;&lfloor;&rfloor;&lang;&rang;&loz;&spades;&clubs;&hearts;&diams;'
  });


  $.FE.PLUGINS.entities = function (editor) {
    var _reg_exp;
    var _map;

    function _process(el) {
      var text = el.textContent;
      if (text.match(_reg_exp)) {
        var new_text = '';
        for (var j = 0; j < text.length; j++) {
          if (_map[text[j]]) new_text += _map[text[j]];
          else new_text += text[j];
        }
        el.textContent = new_text;
      }
    }

    function _encode (el) {
      if (el && ['STYLE', 'SCRIPT'].indexOf(el.tagName) >= 0) return true;

      var contents = editor.node.contents(el);

      for (var i = 0; i < contents.length; i++) {
        if (contents[i].nodeType == Node.TEXT_NODE) {
          _process(contents[i]);
        }
        else {
          _encode(contents[i]);
        }
      }

      if (el.nodeType == Node.TEXT_NODE) _process(el);
    }

    /**
     * Encode entities.
     */
    function _encodeEntities (html) {
      if (html.length === 0) return '';

      return editor.clean.exec(html, _encode);
    }

    /*
     * Initialize.
     */
    function _init () {
      if (editor.opts.htmlSimpleAmpersand) {
        editor.opts.entities = editor.opts.entities.replace('&amp;', '');
      }

      // Do escape.
      var entities_text = $('<div>').html(editor.opts.entities).text();
      var entities_array = editor.opts.entities.split(';');
      _map = {};
      _reg_exp = '';
      for (var i = 0; i < entities_text.length; i++) {
        var chr = entities_text.charAt(i);
        _map[chr] = entities_array[i] + ';';
        _reg_exp += '\\' + chr + (i < entities_text.length - 1 ? '|' : '');
      }
      _reg_exp = new RegExp('(' + _reg_exp + ')', 'g');

      editor.events.on('html.get', _encodeEntities, true);
    }

    return {
      _init: _init
    }
  }


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'file.insert': '[_BUTTONS_][_UPLOAD_LAYER_][_PROGRESS_BAR_]'
  })

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    fileUploadURL: 'http://i.froala.com/upload',
    fileUploadParam: 'file',
    fileUploadParams: {},
    fileUploadToS3: false,
    fileUploadMethod: 'POST',
    fileMaxSize: 10 * 1024 * 1024,
    fileAllowedTypes: ['*'],
    fileInsertButtons: ['fileBack', '|'],
    fileUseSelectedText: false
  });


  $.FE.PLUGINS.file = function (editor) {
    var BAD_LINK = 1;
    var MISSING_LINK = 2;
    var ERROR_DURING_UPLOAD = 3;
    var BAD_RESPONSE = 4;
    var MAX_SIZE_EXCEEDED = 5;
    var BAD_FILE_TYPE = 6;
    var NO_CORS_IE = 7;

    var error_messages = {};
    error_messages[BAD_LINK] = 'File cannot be loaded from the passed link.';
    error_messages[MISSING_LINK] = 'No link in upload response.';
    error_messages[ERROR_DURING_UPLOAD] = 'Error during file upload.';
    error_messages[BAD_RESPONSE] = 'Parsing response failed.';
    error_messages[MAX_SIZE_EXCEEDED] = 'File is too large.';
    error_messages[BAD_FILE_TYPE] = 'File file type is invalid.';
    error_messages[NO_CORS_IE] = 'Files can be uploaded only to same domain in IE 8 and IE 9.';

    function showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertFile"]');

      var $popup = editor.popups.get('file.insert');
      if (!$popup) $popup = _initInsertPopup();

      hideProgressBar();
      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('file.insert');
        editor.popups.setContainer('file.insert', editor.$tb);

        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 0 : $btn.outerHeight());
        editor.popups.show('file.insert', left, top, $btn.outerHeight());
      }
    }

    /**
     * Show progress bar.
     */
    function showProgressBar () {
      var $popup = editor.popups.get('file.insert');
      if (!$popup) $popup = _initInsertPopup();

      $popup.find('.fr-layer.fr-active').removeClass('fr-active').addClass('fr-pactive');
      $popup.find('.fr-file-progress-bar-layer').addClass('fr-active');
      $popup.find('.fr-buttons').hide();

      _setProgressMessage('Uploading', 0);
    }

    /**
     * Hide progress bar.
     */
    function hideProgressBar (dismiss) {
      var $popup = editor.popups.get('file.insert');

      if ($popup) {
        $popup.find('.fr-layer.fr-pactive').addClass('fr-active').removeClass('fr-pactive');
        $popup.find('.fr-file-progress-bar-layer').removeClass('fr-active');
        $popup.find('.fr-buttons').show();

        if (dismiss) {
          editor.popups.show('file.insert', null, null);
        }
      }
    }

    /**
     * Set a progress message.
     */
    function _setProgressMessage (message, progress) {
      var $popup = editor.popups.get('file.insert');

      if ($popup) {
        var $layer = $popup.find('.fr-file-progress-bar-layer');
        $layer.find('h3').text(message + (progress ? ' ' + progress + '%' : ''));

        $layer.removeClass('fr-error');

        if (progress) {
          $layer.find('div').removeClass('fr-indeterminate');
          $layer.find('div > span').css('width', progress + '%');
        }
        else {
          $layer.find('div').addClass('fr-indeterminate');
        }
      }
    }

    /**
     * Show error message to the user.
     */
    function _showErrorMessage (message) {
      showProgressBar();
      var $popup = editor.popups.get('file.insert');
      var $layer = $popup.find('.fr-file-progress-bar-layer');
      $layer.addClass('fr-error')
      $layer.find('h3').text(message);
    }

    /**
     * Insert the uploaded file.
     */
    function insert (link, text, response) {
      editor.edit.on();

      // Focus in the editor.
      editor.events.focus(true);
      editor.selection.restore();

      // Insert the link.
      editor.html.insert('<a href="' + link + '" id="fr-inserted-file" class="fr-file">' + (text || editor.selection.text()) + '</a>');

      // Get the file.
      var $file = editor.$el.find('#fr-inserted-file');
      $file.removeAttr('id');

      editor.popups.hide('file.insert');

      editor.undo.saveStep();

      editor.events.trigger('file.inserted', [$file, response]);
    }

    /**
     * Parse file response.
     */
    function _parseResponse (response) {
      try {
        if (editor.events.trigger('file.uploaded', [response], true) === false) {
          editor.edit.on();
          return false;
        }

        var resp = $.parseJSON(response);
        if (resp.link) {
          return resp;
        } else {
          // No link in upload request.
          _throwError(MISSING_LINK, response);
          return false;
        }
      } catch (ex) {

        // Bad response.
        _throwError(BAD_RESPONSE, response);
        return false;
      }
    }

    /**
     * Parse file response.
     */
    function _parseXMLResponse (response) {
      try {
        var link = $(response).find('Location').text();
        var key = $(response).find('Key').text();

        if (editor.events.trigger('file.uploadedToS3', [link, key, response], true) === false) {
          editor.edit.on();
          return false;
        }

        return link;
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response);
        return false;
      }
    }

    /**
     * File was uploaded to the server and we have a response.
     */
    function _fileUploaded (text) {
      var status = this.status;
      var response = this.response;
      var responseXML = this.responseXML;
      var responseText = this.responseText;

      try {
        if (editor.opts.fileUploadToS3) {
          if (status == 201) {
            var link = _parseXMLResponse(responseXML);
            if (link) {
              insert(link, text, response || responseXML);
            }
          } else {
            _throwError(BAD_RESPONSE, response || responseXML);
          }
        }
        else {
          if (status >= 200 && status < 300) {
            var resp = _parseResponse(responseText);
            if (resp) {
              insert(resp.link, text, response || responseText);
            }
          }
          else {
            _throwError(ERROR_DURING_UPLOAD, response || responseText);
          }
        }
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response || responseText);
      }
    }

    /**
     * File upload error.
     */
    function _fileUploadError () {
      _throwError(BAD_RESPONSE, this.response || this.responseText || this.responseXML);
    }

    /**
     * File upload progress.
     */
    function _fileUploadProgress (e) {
      if (e.lengthComputable) {
        var complete = (e.loaded / e.total * 100 | 0);
        _setProgressMessage('Uploading', complete);
      }
    }

    /**
     * Throw an file error.
     */
    function _throwError (code, response) {
      editor.edit.on();
      _showErrorMessage(editor.language.translate('Something went wrong. Please try again.'));

      editor.events.trigger('file.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    /**
     * File upload aborted.
     */
    function _fileUploadAborted () {
      editor.edit.on();
      hideProgressBar(true);
    }

    function upload (files) {
      // Check if we should cancel the file upload.
      if (editor.events.trigger('file.beforeUpload', [files]) === false) {
        return false;
      }

      // Make sure we have what to upload.
      if (typeof files != 'undefined' && files.length > 0) {
        var file = files[0];

        // Check file max size.
        if (file.size > editor.opts.fileMaxSize) {
          _throwError(MAX_SIZE_EXCEEDED);
          return false;
        }

        // Check file types.
        if (editor.opts.fileAllowedTypes.indexOf('*') < 0 && editor.opts.fileAllowedTypes.indexOf(file.type.replace(/file\//g,'')) < 0) {
          _throwError(BAD_FILE_TYPE);
          return false;
        }

        // Create form Data.
        var form_data;
        if (editor.drag_support.formdata) {
          form_data = editor.drag_support.formdata ? new FormData() : null;
        }

        // Prepare form data for request.
        if (form_data) {
          var key;

          // Upload to S3.
          if (editor.opts.fileUploadToS3 !== false) {
            form_data.append('key', editor.opts.fileUploadToS3.keyStart + (new Date()).getTime() + '-' + (file.name || 'untitled'));
            form_data.append('success_action_status', '201');
            form_data.append('X-Requested-With', 'xhr');
            form_data.append('Content-Type', file.type);

            for (key in editor.opts.fileUploadToS3.params) {
              if (editor.opts.fileUploadToS3.params.hasOwnProperty(key)) {
                form_data.append(key, editor.opts.fileUploadToS3.params[key]);
              }
            }
          }

          // Add upload params.
          for (key in editor.opts.fileUploadParams) {
            if (editor.opts.fileUploadParams.hasOwnProperty(key)) {
              form_data.append(key, editor.opts.fileUploadParams[key]);
            }
          }

          // Set the file in the request.
          form_data.append(editor.opts.fileUploadParam, file);

          // Create XHR request.
          var url = editor.opts.fileUploadURL;
          if (editor.opts.fileUploadToS3) {
            url = 'https://' + editor.opts.fileUploadToS3.region + '.amazonaws.com/' + editor.opts.fileUploadToS3.bucket;
          }
          var xhr = editor.core.getXHR(url, editor.opts.fileUploadMethod);

          // Set upload events.
          xhr.onload = function () {
            _fileUploaded.call(xhr, [(editor.opts.fileUseSelectedText ? null : file.name)]);
          };
          xhr.onerror = _fileUploadError;
          xhr.upload.onprogress = _fileUploadProgress;
          xhr.onabort = _fileUploadAborted;

          showProgressBar();
          editor.edit.off();

          var $popup = editor.popups.get('file.insert');
          if ($popup) {
            $popup.off('abortUpload').on('abortUpload', function () {
              if (xhr.readyState != 4) {
                xhr.abort();
              }
            })
          }

          // Send data.
          xhr.send(form_data);
        }
      }
    }

    function _bindInsertEvents ($popup) {
      // Drag over the dropable area.
      editor.events.$on($popup, 'dragover dragenter', '.fr-file-upload-layer', function () {
        $(this).addClass('fr-drop');
        return false;
      }, true);

      // Drag end.
      editor.events.$on($popup, 'dragleave dragend', '.fr-file-upload-layer', function () {
        $(this).removeClass('fr-drop');
        return false;
      }, true);

      // Drop.
      editor.events.$on($popup, 'drop', '.fr-file-upload-layer', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).removeClass('fr-drop');

        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files) {
          var inst = $popup.data('instance') || editor;
          inst.file.upload(dt.files);
        }
      }, true);

      editor.events.$on($popup, 'change', '.fr-file-upload-layer input[type="file"]', function () {
        if (this.files) {
          var inst = $popup.data('instance') || editor;
          inst.file.upload(this.files);
        }

        // Else IE 9 case.

        // Chrome fix.
        $(this).val('');
      }, true);
    }

    function _hideInsertPopup () {
      hideProgressBar();
    }

    function _initInsertPopup (delayed) {
      if (delayed) {
        editor.popups.onHide('file.insert', _hideInsertPopup);

        return true;
      }

      // Image buttons.
      var file_buttons = '';
      file_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.fileInsertButtons) + '</div>';

      // File upload layer.
      var upload_layer = '';
      upload_layer = '<div class="fr-file-upload-layer fr-layer fr-active" id="fr-file-upload-layer-' + editor.id + '"><strong>' + editor.language.translate('Drop file') + '</strong><br>(' + editor.language.translate('or click') + ')<div class="fr-form"><input type="file" name="' + editor.opts.fileUploadParam + '" accept="/*" tabIndex="-1"></div></div>'


      // Progress bar.
      var progress_bar_layer = '<div class="fr-file-progress-bar-layer fr-layer"><h3 class="fr-message">Uploading</h3><div class="fr-loader"><span class="fr-progress"></span></div><div class="fr-action-buttons"><button type="button" class="fr-command" data-cmd="fileDismissError" tabIndex="2">OK</button></div></div>';

      var template = {
        buttons: file_buttons,
        upload_layer: upload_layer,
        progress_bar: progress_bar_layer
      };

      // Set the template in the popup.
      var $popup = editor.popups.create('file.insert', template);

      _bindInsertEvents($popup);

      return $popup;
    }

    function _onRemove (link) {
      if ($(link).hasClass('fr-file')) {
        return editor.events.trigger('file.unlink', [link]);
      }
    }

    function _drop (e) {
      // Check if we are dropping files.
      var dt = e.originalEvent.dataTransfer;
      if (dt && dt.files && dt.files.length) {
        var file = dt.files[0];
        if (file && typeof file.type != 'undefined') {
          // Dropped file is an file that we allow.
          if (file.type.indexOf('image') < 0 && (editor.opts.fileAllowedTypes.indexOf(file.type) >= 0 || editor.opts.fileAllowedTypes.indexOf('*') >= 0)) {
            editor.markers.remove();
            editor.markers.insertAtPoint(e.originalEvent);
            editor.$el.find('.fr-marker').replaceWith($.FE.MARKERS);

            // Hide popups.
            editor.popups.hideAll();

            // Show the file insert popup.
            var $popup = editor.popups.get('file.insert');
            if (!$popup) $popup = _initInsertPopup();
            editor.popups.setContainer('file.insert', $(editor.opts.scrollableContainer));
            editor.popups.show('file.insert', e.originalEvent.pageX, e.originalEvent.pageY);
            showProgressBar();

            // Upload files.
            upload(dt.files);

            // Cancel anything else.
            e.preventDefault();
            e.stopPropagation();

            return false;
          }
        }
      }
    }

    function _initEvents() {
      // Drop inside the editor.
      editor.events.on('drop', _drop);

      editor.events.$on(editor.$win, 'keydown', function (e) {
        var key_code = e.which;
        var $popup = editor.popups.get('file.insert');
        if ($popup && key_code == $.FE.KEYCODE.ESC) {
          $popup.trigger('abortUpload');
        }
      });

      editor.events.on('destroy', function () {
        var $popup = editor.popups.get('file.insert');
        if ($popup) {
          $popup.trigger('abortUpload');
        }
      });
    }

    function back () {
      editor.events.disableBlur();
      editor.selection.restore();
      editor.events.enableBlur();

      editor.popups.hide('file.insert');
      editor.toolbar.showInline();
    }

    /*
     * Initialize.
     */
    function _init () {
      _initEvents();

      editor.events.on('link.beforeRemove', _onRemove);

      _initInsertPopup(true);
    }

    return {
      _init: _init,
      showInsertPopup: showInsertPopup,
      upload: upload,
      insert: insert,
      back: back,
      hideProgressBar: hideProgressBar
    }
  }

  // Insert file button.
  $.FE.DefineIcon('insertFile', { NAME: 'file-o' });
  $.FE.RegisterCommand('insertFile', {
    title: 'Upload File',
    undo: false,
    focus: true,
    refershAfterCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('file.insert')) {
        this.file.showInsertPopup();
      }
      else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('file.insert');
      }
    },
    plugin: 'file'
  });

  $.FE.DefineIcon('fileBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('fileBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.file.back();
    },
    refresh: function ($btn) {
      if (!this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  $.FE.RegisterCommand('fileDismissError', {
    title: 'OK',
    callback: function () {
      this.file.hideProgressBar(true);
    }
  })


  'use strict';

  $.extend($.FE.DEFAULTS, {
    fontFamily: {
      'Arial,Helvetica,sans-serif': 'Arial',
      'Georgia,serif': 'Georgia',
      'Impact,Charcoal,sans-serif': 'Impact',
      'Tahoma,Geneva,sans-serif': 'Tahoma',
      'Times New Roman,Times,serif': 'Times New Roman',
      'Verdana,Geneva,sans-serif': 'Verdana'
    },
    fontFamilySelection: false,
    fontFamilyDefaultSelection: 'Font Family'
  })

  $.FE.PLUGINS.fontFamily = function (editor) {
    function apply (val) {
      editor.format.applyStyle('font-family', val);
    }

    function refreshOnShow($btn, $dropdown) {
      $dropdown.find('.fr-command.fr-active').removeClass('fr-active');
      $dropdown.find('.fr-command[data-param1="' + _getSelection() + '"]').addClass('fr-active');

      var $list = $dropdown.find('.fr-dropdown-list');
      var $active = $dropdown.find('.fr-active').parent();
      if ($active.length) {
        $list.parent().scrollTop($active.offset().top - $list.offset().top - ($list.parent().outerHeight() / 2 - $active.outerHeight() / 2));
      }
      else {
        $list.parent().scrollTop(0);
      }
    }

    function _getArray (val) {
      var font_array = val.replace(/(sans-serif|serif|monospace|cursive|fantasy)/gi, '').replace(/"|'| /g, '').split(',');

      return $.grep(font_array, function (txt) { return txt.length > 0 });
    }

    /**
     * Return first match position.
     */
    function _matches (array1, array2) {
      for (var i = 0; i < array1.length; i++) {
        for (var j = 0; j < array2.length; j++) {
          if (array1[i] == array2[j]) {
            return [i, j];
          }
        }
      }

      return null;
    }

    function _getSelection () {
      var val = $(editor.selection.element()).css('font-family');
      var font_array = _getArray(val);

      var font_matches = [];
      for (var key in editor.opts.fontFamily) {
        if (editor.opts.fontFamily.hasOwnProperty(key)) {
          var c_font_array = _getArray(key);

          var match = _matches(font_array, c_font_array);
          if (match) {
            font_matches.push([key, match]);
          }
        }
      }

      if (font_matches.length === 0) return null;

      // Sort matches by their position.
      // Times,Arial should be detected as being Times, not Arial.
      font_matches.sort(function (a, b) {
        var f_diff = a[1][0] - b[1][0];
        if (f_diff === 0) {
          return a[1][1] - b[1][1];
        }
        else {
          return f_diff;
        }
      });

      return font_matches[0][0];
    }

    function refresh ($btn) {
      if (editor.opts.fontFamilySelection) {
        var val = $(editor.selection.element()).css('font-family').replace(/(sans-serif|serif|monospace|cursive|fantasy)/gi, '').replace(/"|'|/g, '').split(',');

        $btn.find('> span').text(editor.opts.fontFamily[_getSelection()] || val[0] || editor.opts.fontFamilyDefaultSelection);
      }
    }

    return {
      apply: apply,
      refreshOnShow: refreshOnShow,
      refresh: refresh
    }
  }

  // Register the font size command.
  $.FE.RegisterCommand('fontFamily', {
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
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="fontFamily" data-param1="' + val + '" style="font-family: ' + val + '" title="' + options[val] + '">' + options[val] + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    title: 'Font Family',
    callback: function (cmd, val) {
      this.fontFamily.apply(val);
    },
    refresh: function ($btn) {
      this.fontFamily.refresh($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.fontFamily.refreshOnShow($btn, $dropdown);
    },
    plugin: 'fontFamily'
  })

  // Add the font size icon.
  $.FE.DefineIcon('fontFamily', {
    NAME: 'font'
  });


  'use strict';

  $.extend($.FE.DEFAULTS, {
    fontSize: ['8', '9', '10', '11', '12', '14', '18', '24', '30', '36', '48', '60', '72', '96'],
    fontSizeSelection: false,
    fontSizeDefaultSelection: '12'
  });

  $.FE.PLUGINS.fontSize = function (editor) {
    function apply (val) {
      editor.format.applyStyle('font-size', val);
    }

    function refreshOnShow($btn, $dropdown) {
      var val = $(editor.selection.element()).css('font-size');
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

    function refresh ($btn) {
      if (editor.opts.fontSizeSelection) {
        var val = editor.helpers.getPX($(editor.selection.element()).css('font-size'));
        $btn.find('> span').text(val);
      }
    }

    return {
      apply: apply,
      refreshOnShow: refreshOnShow,
      refresh: refresh
    }
  }

  // Register the font size command.
  $.FE.RegisterCommand('fontSize', {
    type: 'dropdown',
    title: 'Font Size',
    displaySelection: function (editor) {
      return editor.opts.fontSizeSelection;
    },
    displaySelectionWidth: 30,
    defaultSelection: function (editor) {
      return editor.opts.fontSizeDefaultSelection;
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.fontSize;
      for (var i = 0; i < options.length; i++) {
        var val = options[i];
        c += '<li><a class="fr-command" data-cmd="fontSize" data-param1="' + val + 'px" title="' + val + '">' + val + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.fontSize.apply(val);
    },
    refresh: function ($btn) {
      this.fontSize.refresh($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.fontSize.refreshOnShow($btn, $dropdown);
    },
    plugin: 'fontSize'
  })

  // Add the font size icon.
  $.FE.DefineIcon('fontSize', {
    NAME: 'text-height'
  });


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'forms.edit': '[_BUTTONS_]',
    'forms.update': '[_BUTTONS_][_TEXT_LAYER_]'
  })

  $.extend($.FE.DEFAULTS, {
    formEditButtons: ['inputStyle', 'inputEdit'],
    formStyles: {
      'fr-rounded': 'Rounded',
      'fr-large': 'Large'
    },
    formMultipleStyles: true,
    formUpdateButtons: ['inputBack', '|']
  })

  $.FE.PLUGINS.forms = function (editor) {
    var current_input;

    /**
     * Input mousedown.
     */
    function _inputMouseDown (e) {
      e.preventDefault();
      editor.selection.clear();
      $(this).data('mousedown', true);
    }

    /**
     * Mouseup on the input.
     */
    function _inputMouseUp (e) {
      // Mousedown was made.
      if ($(this).data('mousedown')) {
        e.stopPropagation();
        $(this).removeData('mousedown');

        current_input = this;

        showEditPopup(this);
      }

      e.preventDefault();
    }

    /**
     * Cancel if mousedown was made on any input.
     */
    function _cancelSelection () {
      editor.$el.find('input, textarea, button').removeData('mousedown');
    }

    /**
     * Touch move.
     */
    function _inputTouchMove () {
      $(this).removeData('mousedown');
    }

    /**
     * Assign the input events.
     */
    function _bindEvents () {
      editor.events.$on(editor.$el, editor._mousedown, 'input, textarea, button', _inputMouseDown);
      editor.events.$on(editor.$el, editor._mouseup, 'input, textarea, button', _inputMouseUp);
      editor.events.$on(editor.$el, 'touchmove', 'input, textarea, button', _inputTouchMove);
      editor.events.$on(editor.$el, editor._mouseup, _cancelSelection);
      editor.events.$on(editor.$win, editor._mouseup, _cancelSelection);

      _initUpdatePopup(true);
    }

    /**
     * Get the current button.
     */
    function getInput () {
      if (current_input) return current_input;

      return null;
    }

    /**
     * Init the edit button popup.
     */
    function _initEditPopup () {
      // Button edit buttons.
      var buttons = '';
      if (editor.opts.formEditButtons.length > 0) {
        buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.formEditButtons) + '</div>';
      }

      var template = {
        buttons: buttons
      };

      // Set the template in the popup.
      var $popup = editor.popups.create('forms.edit', template);

      if (editor.$wp) {
        editor.events.$on(editor.$wp, 'scroll.link-edit', function () {
          if (get() && editor.popups.isVisible('forms.edit')) {
            showEditPopup(getInput());
          }
        });
      }

      return $popup;
    }

    /**
     * Show the edit button popup.
     */
    function showEditPopup (input) {
      var $popup = editor.popups.get('forms.edit');
      if (!$popup) $popup = _initEditPopup();

      current_input = input;
      var $input = $(input);

      editor.popups.refresh('forms.edit');

      editor.popups.setContainer('forms.edit', $(editor.opts.scrollableContainer));
      var left = $input.offset().left + $input.outerWidth() / 2;
      var top = $input.offset().top + $input.outerHeight();

      editor.popups.show('forms.edit', left, top, $input.outerHeight());
    }

    /**
     * Refresh update button popup callback.
     */
    function _refreshUpdateCallback () {
      var $popup = editor.popups.get('forms.update');

      var input = getInput();
      if (input) {
        var $input = $(input);
        if ($input.is('button')) {
          $popup.find('input[type="text"][name="text"]').val($input.text());
        }
        else {
          $popup.find('input[type="text"][name="text"]').val($input.attr('placeholder'));
        }
      }

      $popup.find('input[type="text"][name="text"]').trigger('change');
    }

    /**
     * Hide update button popup callback.
     */
    function _hideUpdateCallback () {
      current_input = null;
    }

    /**
     * Init update button popup.
     */
    function _initUpdatePopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('forms.update', _refreshUpdateCallback);
        editor.popups.onHide('forms.update', _hideUpdateCallback);

        return true;
      }

      // Button update buttons.
      var buttons = '';
      if (editor.opts.formUpdateButtons.length >= 1) {
        buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.formUpdateButtons) + '</div>';
      }

      var text_layer = '';
      var tab_idx = 0;
      text_layer = '<div class="fr-forms-text-layer fr-layer fr-active">';
      text_layer += '<div class="fr-input-line"><input name="text" type="text" placeholder="Text" tabIndex="' + (++tab_idx) + '"></div>';

      text_layer += '<div class="fr-action-buttons"><button class="fr-command fr-submit" data-cmd="updateInput" href="#" tabIndex="' + (++tab_idx) + '" type="button">' + editor.language.translate('Update') + '</button></div></div>'

      var template = {
        buttons: buttons,
        text_layer: text_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('forms.update', template);

      return $popup;
    }

    /**
     * Show the button update popup.
     */
    function showUpdatePopup () {
      var input = getInput();
      if (input) {
        var $input = $(input);

        var $popup = editor.popups.get('forms.update');
        if (!$popup) $popup = _initUpdatePopup();

        if (!editor.popups.isVisible('forms.update')) {
          editor.popups.refresh('forms.update');
        }

        editor.popups.setContainer('forms.update', $(editor.opts.scrollableContainer));
        var left = $input.offset().left + $input.outerWidth() / 2;
        var top = $input.offset().top + $input.outerHeight();

        editor.popups.show('forms.update', left, top, $input.outerHeight());
      }
    }

    /**
     * Apply specific style.
     */
    function applyStyle (val, formStyles, multipleStyles) {
      if (typeof formStyles == 'undefined') formStyles = editor.opts.formStyles;
      if (typeof multipleStyles == 'undefined') multipleStyles = editor.opts.formMultipleStyles;

      var input = getInput();
      if (!input) return false;

      // Remove multiple styles.
      if (!multipleStyles) {
        var styles = Object.keys(formStyles);
        styles.splice(styles.indexOf(val), 1);
        $(input).removeClass(styles.join(' '));
      }

      $(input).toggleClass(val);
    }

    /**
     * Back button in update button popup.
     */
    function back () {
      editor.events.disableBlur();
      editor.selection.restore();
      editor.events.enableBlur();

      var input = getInput();

      if (input && editor.$wp) {
        if (input.tagName == 'BUTTON') editor.selection.restore();
        showEditPopup(input);
      }
    }

    /**
     * Hit the update button in the input popup.
     */
    function updateInput () {
      var $popup = editor.popups.get('forms.update');

      var input = getInput();
      if (input) {
        var $input = $(input);
        var val = $popup.find('input[type="text"][name="text"]').val() || '';

        if (val.length) {
          if ($input.is('button')) {
            $input.text(val);
          }
          else {
            $input.attr('placeholder', val);
          }
        }

        editor.popups.hide('forms.update');
        showEditPopup(input);
      }
    }

    /**
     * Initialize.
     */
    function _init () {
      // Bind input events.
      _bindEvents();

      // Prevent form submit.
      editor.events.$on(editor.$el, 'submit', 'form', function (e) {
        e.preventDefault();
        return false;
      })
    }

    return {
      _init: _init,
      updateInput: updateInput,
      getInput: getInput,
      applyStyle: applyStyle,
      showUpdatePopup: showUpdatePopup,
      showEditPopup: showEditPopup,
      back: back
    }
  }

  // Register command to update input.
  $.FE.RegisterCommand('updateInput', {
    undo: false,
    focus: false,
    title: 'Update',
    callback: function () {
      this.forms.updateInput();
    }
  });

  // Link styles.
  $.FE.DefineIcon('inputStyle', { NAME: 'magic' })
  $.FE.RegisterCommand('inputStyle', {
    title: 'Style',
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.formStyles;
      for (var cls in options) {
        if (options.hasOwnProperty(cls)) {
          c += '<li><a class="fr-command" data-cmd="inputStyle" data-param1="' + cls + '">' + this.language.translate(options[cls]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      var input = this.forms.getInput();

      if (input) {
        this.forms.applyStyle(val);
        this.forms.showEditPopup(input);
      }
    },
    refreshOnShow: function ($btn, $dropdown) {
      var input = this.forms.getInput();

      if (input) {
        var $input = $(input);
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $input.hasClass(cls));
        })
      }
    }
  });

  $.FE.DefineIcon('inputEdit', { NAME: 'edit' });
  $.FE.RegisterCommand('inputEdit', {
    title: 'Edit Button',
    undo: false,
    refreshAfterCallback: false,
    callback: function () {
      this.forms.showUpdatePopup();
    }
  })

  $.FE.DefineIcon('inputBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('inputBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.forms.back();
    }
  });

  // Register command to update button.
  $.FE.RegisterCommand('updateInput', {
    undo: false,
    focus: false,
    title: 'Update',
    callback: function () {
      this.forms.updateInput();
    }
  });


  'use strict';

  $.FE.PLUGINS.fullscreen = function (editor) {
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
    var height;
    var max_height;
    function _on () {
      old_scroll = $(editor.o_win).scrollTop();
      editor.$box.toggleClass('fr-fullscreen');
      $('body').toggleClass('fr-fullscreen');
      $placeholder = $('<div style="display: none;"></div>');
      editor.$box.after($placeholder);

      if (editor.helpers.isMobile()) {
        editor.$tb.data('parent', editor.$tb.parent());
        editor.$tb.prependTo(editor.$box);
        if (editor.$tb.data('sticky-dummy')) {
          editor.$tb.after(editor.$tb.data('sticky-dummy'));
        }
      }

      height = editor.opts.height;
      max_height = editor.opts.heightMax;

      editor.opts.height = editor.o_win.innerHeight - (editor.opts.toolbarInline ? 0 : editor.$tb.outerHeight());
      editor.size.refresh();

      if (editor.opts.toolbarInline) editor.toolbar.showInline();

      var $parent_node = editor.$box.parent();
      while (!$parent_node.is('body')) {
        $parent_node
          .data('z-index', $parent_node.css('z-index'))
          .css('z-index', '9990');
        $parent_node = $parent_node.parent();
      }

      editor.events.trigger('charCounter.update');
      editor.$win.trigger('scroll');
    }

    /**
     * Turn fullscreen off.
     */
    function _off () {
      editor.$box.toggleClass('fr-fullscreen');
      $('body').toggleClass('fr-fullscreen');

      editor.$tb.prependTo(editor.$tb.data('parent'));
      if (editor.$tb.data('sticky-dummy')) {
        editor.$tb.after(editor.$tb.data('sticky-dummy'));
      }

      editor.opts.height = height;
      editor.opts.heightMax = max_height;
      editor.size.refresh();

      $(editor.o_win).scrollTop(old_scroll)

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

      var $parent_node = editor.$box.parent();
      while (!$parent_node.is('body')) {
        if ($parent_node.data('z-index')) {
          $parent_node.css('z-index', '');
          if ($parent_node.css('z-index') != $parent_node.data('z-index')) {
            $parent_node.css('z-index', $parent_node.data('z-index'));
          }
          $parent_node.removeData('z-index');
        }

        $parent_node = $parent_node.parent();
      }

      editor.$win.trigger('scroll');
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
      $btn.find('> *').replaceWith(!active ? editor.icon.create('fullscreen') : editor.icon.create('fullscreenCompress'));
    }

    function _init () {
      if (!editor.$wp) return false;

      editor.events.$on($(editor.o_win), 'resize', function () {
        if (isActive()) {
          _off();
          _on();
        }
      });

      editor.events.on('toolbar.hide', function () {
        if (isActive() && editor.helpers.isMobile()) return false;
      })
    }

    return {
      _init: _init,
      toggle: toggle,
      refresh: refresh,
      isActive: isActive
    }
  }

  // Register the font size command.
  $.FE.RegisterCommand('fullscreen', {
    title: 'Fullscreen',
    undo: false,
    focus: false,
    forcedRefresh: true,
    callback: function () {
      this.fullscreen.toggle();
    },
    refresh: function ($btn) {
      this.fullscreen.refresh($btn);
    },
    plugin: 'fullscreen'
  })

  // Add the font size icon.
  $.FE.DefineIcon('fullscreen', {
    NAME: 'expand'
  });
  $.FE.DefineIcon('fullscreenCompress', {
    NAME: 'compress'
  });


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'image.insert': '[_BUTTONS_][_UPLOAD_LAYER_][_BY_URL_LAYER_][_PROGRESS_BAR_]',
    'image.edit': '[_BUTTONS_]',
    'image.alt': '[_BUTTONS_][_ALT_LAYER_]',
    'image.size': '[_BUTTONS_][_SIZE_LAYER_]'
  })

  $.extend($.FE.DEFAULTS, {
    imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL'],
    imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],
    imageAltButtons: ['imageBack', '|'],
    imageSizeButtons: ['imageBack', '|'],
    imageUploadURL: 'http://i.froala.com/upload',
    imageUploadParam: 'file',
    imageUploadParams: {},
    imageUploadToS3: false,
    imageUploadMethod: 'POST',
    imageMaxSize: 10 * 1024 * 1024,
    imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif', 'svg+xml'],
    imageResize: true,
    imageResizeWithPercent: false,
    imageRoundPercent: false,
    imageDefaultWidth: 300,
    imageDefaultAlign: 'center',
    imageDefaultDisplay: 'block',
    imageSplitHTML: false,
    imageStyles: {
      'fr-rounded': 'Rounded',
      'fr-bordered': 'Bordered'
    },
    imageMove: true,
    imageMultipleStyles: true,
    imageTextNear: true,
    imagePaste: true,
    imagePasteProcess: false,
    imageMinWidth: 16,
    imageOutputSize: false
  });

  $.FE.PLUGINS.image = function (editor) {
    var $current_image;
    var $image_resizer;
    var $handler;
    var $overlay;
    var mousedown = false;

    var BAD_LINK = 1;
    var MISSING_LINK = 2;
    var ERROR_DURING_UPLOAD = 3;
    var BAD_RESPONSE = 4;
    var MAX_SIZE_EXCEEDED = 5;
    var BAD_FILE_TYPE = 6;
    var NO_CORS_IE = 7;

    var error_messages = {};
    error_messages[BAD_LINK] = 'Image cannot be loaded from the passed link.',
    error_messages[MISSING_LINK] = 'No link in upload response.',
    error_messages[ERROR_DURING_UPLOAD] = 'Error during file upload.',
    error_messages[BAD_RESPONSE] = 'Parsing response failed.',
    error_messages[MAX_SIZE_EXCEEDED] = 'File is too large.',
    error_messages[BAD_FILE_TYPE] = 'Image file type is invalid.',
    error_messages[NO_CORS_IE] = 'Files can be uploaded only to same domain in IE 8 and IE 9.'

    /**
     * Refresh the image insert popup.
     */

    function _refreshInsertPopup () {
      var $popup = editor.popups.get('image.insert');

      var $url_input = $popup.find('.fr-image-by-url-layer input');
      $url_input.val('');

      if ($current_image) {
        $url_input.val($current_image.attr('src'));
      }

      $url_input.trigger('change');
    }

    /**
     * Show the image upload popup.
     */

    function showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertImage"]');

      var $popup = editor.popups.get('image.insert');
      if (!$popup) $popup = _initInsertPopup();

      hideProgressBar();
      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('image.insert');
        editor.popups.setContainer('image.insert', editor.$tb);

        if ($btn.is(':visible')) {
          var left = $btn.offset().left + $btn.outerWidth() / 2;
          var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
          editor.popups.show('image.insert', left, top, $btn.outerHeight());
        } else {
          editor.position.forSelection($popup);
          editor.popups.show('image.insert');
        }
      }
    }

    /**
     * Show the image edit popup.
     */

    function _showEditPopup () {
      var $popup = editor.popups.get('image.edit');
      if (!$popup) $popup = _initEditPopup();

      editor.popups.setContainer('image.edit', $(editor.opts.scrollableContainer));
      editor.popups.refresh('image.edit');
      var left = $current_image.offset().left + $current_image.outerWidth() / 2;
      var top = $current_image.offset().top + $current_image.outerHeight();

      editor.popups.show('image.edit', left, top, $current_image.outerHeight());
    }

    /**
     * Hide image upload popup.
     */

    function _hideInsertPopup () {
      hideProgressBar();
    }

    /**
     * Convert style to classes.
     */

    function _convertStyleToClasses ($img) {
      if (!$img.hasClass('fr-dii') && !$img.hasClass('fr-dib')) {
        // Set float to none.
        var flt = $img.css('float');
        $img.css('float', 'none');

        // Image has display block.
        if ($img.css('display') == 'block') {
          // Set float to the initial value.
          $img.css('float', flt);

          if (editor.opts.imageEditButtons.indexOf('imageAlign') >= 0) {
            // Margin left is 0.
            // Margin right is auto.
            if (parseInt($img.css('margin-left'), 10) === 0 && ($img.attr('style') || '').indexOf('margin-right: auto') >= 0) {
              $img.addClass('fr-fil');
            }

            // Margin left is auto.
            // Margin right is 0.
            else if (parseInt($img.css('margin-right'), 10) === 0 && ($img.attr('style') || '').indexOf('margin-left: auto') >= 0) {
              $img.addClass('fr-fir');
            }
          }

          $img.addClass('fr-dib');
        }

        // Display inline.
        else {
          // Set float.
          $img.css('float', flt);

          if (editor.opts.imageEditButtons.indexOf('imageAlign') >= 0) {
            // Float left.
            if ($img.css('float') == 'left') {
              $img.addClass('fr-fil');
            }

            // Float right.
            else if ($img.css('float') == 'right') {
              $img.addClass('fr-fir');
            }
          }

          $img.addClass('fr-dii');
        }

        // Reset inline style.
        $img.css('margin', '');
        $img.css('float', '');
        $img.css('display', '');
        $img.css('z-index', '');
        $img.css('position', '');
        $img.css('overflow', '');
        $img.css('vertical-align', '');
      }
    }

    /**
     * Refresh the image list.
     */

    function _refreshImageList () {
      var images = editor.$el.get(0).tagName == 'IMG' ? [editor.$el.get(0)] : editor.$el.get(0).querySelectorAll('img');

      for (var i = 0; i < images.length; i++) {
        var $img = $(images[i]);

        if (editor.opts.imageEditButtons.indexOf('imageAlign') >= 0 || editor.opts.imageEditButtons.indexOf('imageDisplay') >= 0) {
          _convertStyleToClasses($img);
        }

        // Set width if it has width.
        if ($img.attr('width')) {
          $img.css('width', $img.width());
          $img.removeAttr('width');
        }

        // Do not allow text near image.
        if (!editor.opts.imageTextNear) {
          $img.removeClass('fr-dii').addClass('fr-dib');
        }

        if (editor.opts.iframe) {
          $img.on('load', editor.size.syncIframe);
        }
      }
    }

    /**
     * Keep images in sync when content changed.
     */
    var images;

    function _syncImages () {
      // Get current images.
      var c_images = Array.prototype.slice.call(editor.$el.get(0).querySelectorAll('img'));

      // Current images src.
      var image_srcs = [];
      var i;
      for (i = 0; i < c_images.length; i++) {
        image_srcs.push(c_images[i].getAttribute('src'));

        $(c_images[i]).toggleClass('fr-draggable', editor.opts.imageMove);
        if (c_images[i].className === '') c_images[i].removeAttribute('class');
        if (c_images[i].getAttribute('style') === '') c_images[i].removeAttribute('style');
      }

      // Loop previous images and check their src.
      if (images) {
        for (i = 0; i < images.length; i++) {
          if (image_srcs.indexOf(images[i].getAttribute('src')) < 0) {
            editor.events.trigger('image.removed', [$(images[i])]);
          }
        }
      }

      // Current images are the old ones.
      images = c_images;
    }

    /**
     * Reposition resizer.
     */

    function _repositionResizer () {
      if (!$image_resizer) _initImageResizer();

      var $container = editor.$wp || $(editor.opts.scrollableContainer);

      $container.append($image_resizer);
      $image_resizer.data('instance', editor);

      var wrap_correction_top = $container.scrollTop() - (($container.css('position') != 'static' ? $container.offset().top : 0));
      var wrap_correction_left = $container.scrollLeft() - (($container.css('position') != 'static' ? $container.offset().left : 0));

      wrap_correction_left -= editor.helpers.getPX($container.css('border-left-width'));
      wrap_correction_top -= editor.helpers.getPX($container.css('border-top-width'));

      $image_resizer
        .css('top', (editor.opts.iframe ? $current_image.offset().top : $current_image.offset().top + wrap_correction_top) - 1)
        .css('left', (editor.opts.iframe ? $current_image.offset().left : $current_image.offset().left + wrap_correction_left) - 1)
        .css('width', $current_image.get(0).getBoundingClientRect().width)
        .css('height', $current_image.get(0).getBoundingClientRect().height)
        .addClass('fr-active')
    }

    /**
     * Create resize handler.
     */

    function _getHandler (pos) {
      return '<div class="fr-handler fr-h' + pos + '"></div>';
    }

    /**
     * Mouse down to start resize.
     */
    function _handlerMousedown (e) {
      // Check if resizer belongs to current instance.
      if (!editor.core.sameInstance($image_resizer)) return true;

      e.preventDefault();
      e.stopPropagation();

      if (editor.$el.find('img.fr-error').left) return false;

      if (!editor.undo.canDo()) editor.undo.saveStep();

      $handler = $(this);
      $handler.data('start-x', e.pageX || e.originalEvent.touches[0].pageX);
      $handler.data('start-width', $current_image.width());
      $handler.data('start-height', $current_image.height());

      // Set current width.
      var width = $current_image.width();
      if (editor.opts.imageResizeWithPercent) {
        var p_node = $current_image.parentsUntil(editor.$el, editor.html.blockTagsQuery()).get(0) || editor.$el.get(0);

        $current_image.css('width', (width / $(p_node).outerWidth() * 100).toFixed(2) + '%');
      } else {
        $current_image.css('width', width);
      }

      $overlay.show();

      editor.popups.hideAll();

      _unmarkExit();
    }

    /**
     * Do resize.
     */

    function _handlerMousemove (e) {
      // Check if resizer belongs to current instance.
      if (!editor.core.sameInstance($image_resizer)) return true;

      if ($handler && $current_image) {
        e.preventDefault()

        if (editor.$el.find('img.fr-error').left) return false;

        var c_x = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);

        if (!c_x) {
          return false;
        }

        var s_x = $handler.data('start-x');

        var diff_x = c_x - s_x;

        var width = $handler.data('start-width');
        if ($handler.hasClass('fr-hnw') || $handler.hasClass('fr-hsw')) {
          diff_x = 0 - diff_x;
        }

        if (editor.opts.imageResizeWithPercent) {
          var p_node = $current_image.parentsUntil(editor.$el, editor.html.blockTagsQuery()).get(0) || editor.$el.get(0);

          width = ((width + diff_x) / $(p_node).outerWidth() * 100).toFixed(2);
          if (editor.opts.imageRoundPercent) width = Math.round(width);

          $current_image.css('width', width + '%');
          $current_image.css('height', '').removeAttr('height');
        } else {
          if (width + diff_x >= editor.opts.imageMinWidth) {
            $current_image.css('width', width + diff_x);
          }

          $current_image.css('height', $handler.data('start-height') * $current_image.width() / $handler.data('start-width'));
        }

        _repositionResizer();

        editor.events.trigger('image.resize', [get()]);
      }
    }

    /**
     * Stop resize.
     */

    function _handlerMouseup (e) {
      // Check if resizer belongs to current instance.
      if (!editor.core.sameInstance($image_resizer)) return true;

      if ($handler && $current_image) {
        if (e) e.stopPropagation();

        if (editor.$el.find('img.fr-error').left) return false;

        $handler = null;
        $overlay.hide();
        _repositionResizer();
        _showEditPopup();

        editor.undo.saveStep();

        editor.events.trigger('image.resizeEnd', [get()]);
      }
    }

    /**
     * Throw an image error.
     */

    function _throwError (code, response) {
      editor.edit.on();
      if ($current_image) $current_image.addClass('fr-error');
      _showErrorMessage(editor.language.translate('Something went wrong. Please try again.'));

      editor.events.trigger('image.error', [{
          code: code,
          message: error_messages[code]
        },
        response
      ]);
    }

    /**
     * Init the image edit popup.
     */

    function _initEditPopup (delayed) {
      if (delayed) {
        if (editor.$wp) {
          editor.events.$on(editor.$wp, 'scroll', function () {
            if ($current_image && editor.popups.isVisible('image.edit')) {
              _showEditPopup();
            }
          });
        }

        return true;
      }

      // Image buttons.
      var image_buttons = '';
      if (editor.opts.imageEditButtons.length > 1) {
        image_buttons += '<div class="fr-buttons">';
        image_buttons += editor.button.buildList(editor.opts.imageEditButtons);
        image_buttons += '</div>';
      }

      var template = {
        buttons: image_buttons
      };

      var $popup = editor.popups.create('image.edit', template);

      return $popup;
    }

    /**
     * Show progress bar.
     */

    function showProgressBar (no_message) {
      var $popup = editor.popups.get('image.insert');
      if (!$popup) $popup = _initInsertPopup();

      $popup.find('.fr-layer.fr-active').removeClass('fr-active').addClass('fr-pactive');
      $popup.find('.fr-image-progress-bar-layer').addClass('fr-active');
      $popup.find('.fr-buttons').hide();

      if ($current_image) {
        editor.popups.setContainer('image.insert', $(editor.opts.scrollableContainer));
        var left = $current_image.offset().left + $current_image.width() / 2;
        var top = $current_image.offset().top + $current_image.height();

        editor.popups.show('image.insert', left, top, $current_image.outerHeight());
      }

      if (typeof no_message == 'undefined') {
        _setProgressMessage('Uploading', 0);
      }
    }

    /**
     * Hide progress bar.
     */
    function hideProgressBar (dismiss) {
      var $popup = editor.popups.get('image.insert');

      if ($popup) {
        $popup.find('.fr-layer.fr-pactive').addClass('fr-active').removeClass('fr-pactive');
        $popup.find('.fr-image-progress-bar-layer').removeClass('fr-active');
        $popup.find('.fr-buttons').show();

        // Dismiss error message.
        if (dismiss || editor.$el.find('img.fr-error').length) {
          editor.events.focus();
          editor.$el.find('img.fr-error').remove();
          editor.undo.saveStep();
          editor.undo.run();
          editor.undo.dropRedo();
        }
      }
    }

    /**
     * Set a progress message.
     */

    function _setProgressMessage (message, progress) {
      var $popup = editor.popups.get('image.insert');

      if ($popup) {
        var $layer = $popup.find('.fr-image-progress-bar-layer');
        $layer.find('h3').text(message + (progress ? ' ' + progress + '%' : ''));

        $layer.removeClass('fr-error');

        if (progress) {
          $layer.find('div').removeClass('fr-indeterminate');
          $layer.find('div > span').css('width', progress + '%');
        } else {
          $layer.find('div').addClass('fr-indeterminate');
        }
      }
    }

    /**
     * Show error message to the user.
     */

    function _showErrorMessage (message) {
      showProgressBar();
      var $popup = editor.popups.get('image.insert');
      var $layer = $popup.find('.fr-image-progress-bar-layer');
      $layer.addClass('fr-error')
      $layer.find('h3').text(message);
    }

    /**
     * Insert image using URL callback.
     */

    function insertByURL () {
      var $popup = editor.popups.get('image.insert');
      var $input = $popup.find('.fr-image-by-url-layer input');

      if ($input.val().length > 0) {
        showProgressBar();
        _setProgressMessage('Loading image');
        insert(editor.helpers.sanitizeURL($input.val()), true, [], $current_image);
        $input.val('');
        $input.blur();
      }
    }

    function _editImg ($img) {
      _edit.call($img.get(0));
    }

    function _loadedCallback () {
      var $img = $(this);

      editor.popups.hide('image.insert');

      $img.removeClass('fr-uploading');

      // Select the image.
      if ($img.next().is('br')) {
        $img.next().remove();
      }

      _editImg($img);

      editor.events.trigger('image.loaded', [$img]);
    }

    /**
     * Insert image into the editor.
     */

    function insert (link, sanitize, data, $existing_img, response) {
      editor.edit.off();
      _setProgressMessage('Loading image');

      var image = new Image();
      image.onload = function () {
        var $img;
        var attr;

        if ($existing_img) {
          var old_src = $existing_img.data('fr-old-src');

          if (editor.$wp) {
            // Clone existing image.
            $img = $existing_img.clone().removeData('fr-old-src').removeClass('fr-uploading');

            // Remove load event.
            $img.off('load');

            // Set new SRC.
            if (old_src) $existing_img.attr('src', old_src);

            // Replace existing image with its clone.
            $existing_img.replaceWith($img);
          } else {
            $img = $existing_img;
          }

          // Remove old data.
          var atts = $img.get(0).attributes;
          for (var i = 0; i < atts.length; i++) {
            var att = atts[i];
            if (att.nodeName.indexOf('data-') === 0) {
              $img.removeAttr(att.nodeName);
            }
          }

          // Set new data.
          if (typeof data != 'undefined') {
            for (attr in data) {
              if (data.hasOwnProperty(attr)) {
                if (attr != 'link') {
                  $img.attr('data-' + attr, data[attr]);
                }
              }
            }
          }

          $img.on('load', _loadedCallback);
          $img.attr('src', link);
          editor.edit.on();
          _syncImages();
          editor.undo.saveStep();
          editor.events.trigger(old_src ? 'image.replaced' : 'image.inserted', [$img, response]);
        } else {
          $img = _addImage(link, data, _loadedCallback);
          _syncImages();
          editor.undo.saveStep();
          editor.events.trigger('image.inserted', [$img, response]);
        }
      }

      image.onerror = function () {
        _throwError(BAD_LINK);
      }

      image.src = link;
    }

    /**
     * Parse image response.
     */

    function _parseResponse (response) {
      try {
        if (editor.events.trigger('image.uploaded', [response], true) === false) {
          editor.edit.on();
          return false;
        }

        var resp = $.parseJSON(response);
        if (resp.link) {
          return resp;
        } else {
          // No link in upload request.
          _throwError(MISSING_LINK, response);
          return false;
        }
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response);
        return false;
      }
    }

    /**
     * Parse image response.
     */

    function _parseXMLResponse (response) {
      try {
        var link = $(response).find('Location').text();
        var key = $(response).find('Key').text();

        if (editor.events.trigger('image.uploadedToS3', [link, key, response], true) === false) {
          editor.edit.on();
          return false;
        }

        return link;
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response);
        return false;
      }
    }

    /**
     * Image was uploaded to the server and we have a response.
     */

    function _imageUploaded ($img) {
      _setProgressMessage('Loading image');

      var status = this.status;
      var response = this.response;
      var responseXML = this.responseXML;
      var responseText = this.responseText;

      try {
        if (editor.opts.imageUploadToS3) {
          if (status == 201) {
            var link = _parseXMLResponse(responseXML);
            if (link) {
              insert(link, false, [], $img, response || responseXML);
            }
          } else {
            _throwError(BAD_RESPONSE, response || responseXML);
          }
        } else {
          if (status >= 200 && status < 300) {
            var resp = _parseResponse(responseText);
            if (resp) {
              insert(resp.link, false, resp, $img, response || responseText);
            }
          } else {
            _throwError(ERROR_DURING_UPLOAD, response || responseText);
          }
        }
      } catch (ex) {
        // Bad response.
        _throwError(BAD_RESPONSE, response || responseText);
      }
    }

    /**
     * Image upload error.
     */

    function _imageUploadError () {
      _throwError(BAD_RESPONSE, this.response || this.responseText || this.responseXML);
    }

    /**
     * Image upload progress.
     */

    function _imageUploadProgress (e) {
      if (e.lengthComputable) {
        var complete = (e.loaded / e.total * 100 | 0);
        _setProgressMessage('Uploading', complete);
      }
    }

    function _addImage (link, data, loadCallback) {
      // Build image data string.
      var data_str = '';
      var attr;
      if (data && typeof data != 'undefined') {
        for (attr in data) {
          if (data.hasOwnProperty(attr)) {
            if (attr != 'link') {
              data_str += ' data-' + attr + '="' + data[attr] + '"';
            }
          }
        }
      }

      var width = editor.opts.imageDefaultWidth;
      if (width && width != 'auto' && ('' + width).indexOf('px') < 0 && ('' + width).indexOf('%') < 0) {
        width = width + 'px';
      }

      // Create image object and set the load event.
      var $img = $('<img class="' + (editor.opts.imageDefaultDisplay ? 'fr-di' + editor.opts.imageDefaultDisplay[0] : '') + (editor.opts.imageDefaultAlign ? (editor.opts.imageDefaultAlign != 'center' ? ' fr-fi' + editor.opts.imageDefaultAlign[0] : '') : '') + '" src="' + link + '"' + data_str + (width ? ' style="width: ' + width + ';"' : '') + '>');

      $img.on('load', loadCallback);

      // Make sure we have focus.
      // Call the event.
      editor.edit.on();
      editor.events.focus(true);
      editor.selection.restore();

      editor.undo.saveStep();

      // Insert marker and then replace it with the image.
      if (editor.opts.imageSplitHTML) {
        editor.markers.split();
      } else {
        editor.markers.insert();
      }

      var $marker = editor.$el.find('.fr-marker');
      $marker.replaceWith($img);

      editor.html.wrap();
      editor.selection.clear();

      return $img;
    }

    /**
     * Image upload aborted.
     */
    function _imageUploadAborted () {
      editor.edit.on();
      hideProgressBar(true);
    }

    /**
     * Start the uploading process.
     */
    function _startUpload (xhr, form_data, image) {
      function _sendRequest () {
        var $img = $(this);

        $img.off('load');

        $img.addClass('fr-uploading');

        if ($img.next().is('br')) {
          $img.next().remove();
        }

        editor.placeholder.refresh();

        // Select the image.
        if (!$img.is($current_image)) _editImg($img);

        _repositionResizer();
        showProgressBar();

        editor.edit.off();

        // Set upload events.
        xhr.onload = function () {
          _imageUploaded.call(xhr, $img);
        };
        xhr.onerror = _imageUploadError;
        xhr.upload.onprogress = _imageUploadProgress;
        xhr.onabort = _imageUploadAborted;

        // Set abort event.
        $img.off('abortUpload').on('abortUpload', function () {
          if (xhr.readyState != 4) {
            xhr.abort();
          }
        });

        // Send data.
        xhr.send(form_data);
      }

      var reader = new FileReader();
      var $img;
      reader.addEventListener('load', function () {
        var link = reader.result;

        if (reader.result.indexOf('svg+xml') < 0) {
          // Convert image to local blob.
          var binary = atob(reader.result.split(',')[1]);
          var array = [];
          for (var i = 0; i < binary.length; i++) {
            array.push(binary.charCodeAt(i));
          }

          // Get local image link.
          link = window.URL.createObjectURL(new Blob([new Uint8Array(array)], {
            type: 'image/jpeg'
          }));
        }

        // No image.
        if (!$current_image) {
          $img = _addImage(link, null, _sendRequest);
        } else {
          $current_image.on('load', _sendRequest);
          editor.edit.on();
          editor.undo.saveStep();
          $current_image.data('fr-old-src', $current_image.attr('src'));
          $current_image.attr('src', link);
        }
      }, false);

      reader.readAsDataURL(image);
    }

    /**
     * Do image upload.
     */

    function upload (images) {
      // Check if we should cancel the image upload.
      if (editor.events.trigger('image.beforeUpload', [images]) === false) {
        return false;
      }

      // Make sure we have what to upload.
      if (typeof images != 'undefined' && images.length > 0) {
        var image = images[0];

        // Check image max size.
        if (image.size > editor.opts.imageMaxSize) {
          _throwError(MAX_SIZE_EXCEEDED);
          return false;
        }

        // Check image types.
        if (editor.opts.imageAllowedTypes.indexOf(image.type.replace(/image\//g, '')) < 0) {
          _throwError(BAD_FILE_TYPE);
          return false;
        }

        // Create form Data.
        var form_data;
        if (editor.drag_support.formdata) {
          form_data = editor.drag_support.formdata ? new FormData() : null;
        }

        // Prepare form data for request.
        if (form_data) {
          var key;

          // Upload to S3.
          if (editor.opts.imageUploadToS3 !== false) {
            form_data.append('key', editor.opts.imageUploadToS3.keyStart + (new Date()).getTime() + '-' + (image.name || 'untitled'));
            form_data.append('success_action_status', '201');
            form_data.append('X-Requested-With', 'xhr');
            form_data.append('Content-Type', image.type);

            for (key in editor.opts.imageUploadToS3.params) {
              if (editor.opts.imageUploadToS3.params.hasOwnProperty(key)) {
                form_data.append(key, editor.opts.imageUploadToS3.params[key]);
              }
            }
          }

          // Add upload params.
          for (key in editor.opts.imageUploadParams) {
            if (editor.opts.imageUploadParams.hasOwnProperty(key)) {
              form_data.append(key, editor.opts.imageUploadParams[key]);
            }
          }

          // Set the image in the request.
          form_data.append(editor.opts.imageUploadParam, image);

          // Create XHR request.
          var url = editor.opts.imageUploadURL;
          if (editor.opts.imageUploadToS3) {
            url = 'https://' + editor.opts.imageUploadToS3.region + '.amazonaws.com/' + editor.opts.imageUploadToS3.bucket;
          }
          var xhr = editor.core.getXHR(url, editor.opts.imageUploadMethod);

          _startUpload(xhr, form_data, image);
        }
      }
    }

    /**
     * Image drop inside the upload zone.
     */

    function _bindInsertEvents ($popup) {
      // Drag over the dropable area.
      editor.events.$on($popup, 'dragover dragenter', '.fr-image-upload-layer', function () {
        $(this).addClass('fr-drop');
        return false;
      });

      // Drag end.
      editor.events.$on($popup, 'dragleave dragend', '.fr-image-upload-layer', function () {
        $(this).removeClass('fr-drop');
        return false;
      });

      // Drop.
      editor.events.$on($popup, 'drop', '.fr-image-upload-layer', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).removeClass('fr-drop');

        var dt = e.originalEvent.dataTransfer;
        if (dt && dt.files) {
          var inst = $popup.data('instance') || editor;
          inst.events.disableBlur();
          inst.image.upload(dt.files);
          inst.events.enableBlur();
        }
      });

      editor.events.$on($popup, 'change', '.fr-image-upload-layer input[type="file"]', function () {
        if (this.files) {
          var inst = $popup.data('instance') || editor;
          inst.events.disableBlur();
          $popup.find('input:focus').blur();
          inst.events.enableBlur();
          inst.image.upload(this.files);
        }
        // Else IE 9 case.

        // Chrome fix.
        $(this).val('');
      });
    }

    function _drop (e) {
      // Check if we are dropping files.
      var dt = e.originalEvent.dataTransfer;
      if (dt && dt.files && dt.files.length) {
        var img = dt.files[0];
        if (img && img.type) {
          // Dropped file is an image that we allow.
          if (editor.opts.imageAllowedTypes.indexOf(img.type.replace(/image\//g, '')) >= 0) {
            editor.markers.remove();
            editor.markers.insertAtPoint(e.originalEvent);
            editor.$el.find('.fr-marker').replaceWith($.FE.MARKERS);

            // Hide popups.
            editor.popups.hideAll();

            // Show the image insert popup.
            var $popup = editor.popups.get('image.insert');
            if (!$popup) $popup = _initInsertPopup();

            editor.popups.setContainer('image.insert', $(editor.opts.scrollableContainer));
            editor.popups.show('image.insert', e.originalEvent.pageX, e.originalEvent.pageY);
            showProgressBar();

            // Upload images.
            upload(dt.files);

            // Cancel anything else.
            e.preventDefault();
            e.stopPropagation();

            return false;
          }
        }
      }
    }

    function _placeCursor () {
      var t;
      var p_node;
      var r = editor.selection.ranges(0);
      if (r.collapsed && r.startContainer.nodeType == Node.ELEMENT_NODE) {
        // Click after image.
        if (r.startContainer.childNodes.length == r.startOffset) {
          t = r.startContainer.childNodes[r.startOffset - 1];
          if (t && t.tagName == 'IMG' && $(t).css('display') == 'block') {
            // Check if image is last node.
            p_node = editor.node.blockParent(t);
            if (p_node && editor.html.defaultTag()) {
              if (!p_node.nextSibling) {
                if (['TD', 'TH'].indexOf(p_node.tagName) < 0) {
                  $(p_node).after('<' + editor.html.defaultTag() + '><br>' + $.FE.MARKERS + '</' + editor.html.defaultTag() + '>');
                }
                else {
                  $(img).after('<br>' + $.FE.MARKERS);
                }
                editor.selection.restore();
              }
            }
            else if (!p_node) {
              $(t).after('<br>' + $.FE.MARKERS);
              editor.selection.restore();
            }
          }
        }
        else if (r.startOffset === 0 && r.startContainer.childNodes.length > r.startOffset) {
          t = r.startContainer.childNodes[r.startOffset];
          if (t && t.tagName == 'IMG' && $(t).css('display') == 'block') {
            // Check if image is last node.
            p_node = editor.node.blockParent(t);
            if (p_node && editor.html.defaultTag()) {
              if (!p_node.previousSibling) {
                if (['TD', 'TH'].indexOf(p_node.tagName) < 0) {
                  $(p_node).before('<' + editor.html.defaultTag() + '><br>' + $.FE.MARKERS + '</' + editor.html.defaultTag() + '>');
                }
                else {
                  $(img).before('<br>' + $.FE.MARKERS);
                }
                editor.selection.restore();
              }
            }
            else if (!p_node) {
              $(t).before($.FE.MARKERS + '<br>');
              editor.selection.restore();
            }
          }
        }
      }
    }

    function _initEvents () {
      // Mouse down on image. It might start move.
      editor.events.$on(editor.$el, editor._mousedown, editor.$el.get(0).tagName == 'IMG' ? null : 'img:not([contenteditable="false"])', function (e) {
        if ($(this).parents('[contenteditable="false"]:not(.fr-element):not(body)').length) return true;

        editor.selection.clear();

        mousedown = true;

        // Prevent the image resizing.
        if (editor.browser.msie) {
          editor.events.disableBlur();
          editor.$el.attr('contenteditable', false);
        }

        if (!editor.draggable) e.preventDefault();

        e.stopPropagation();
      });

      // Mouse up on an image prevent move.
      editor.events.$on(editor.$el, editor._mouseup, editor.$el.get(0).tagName == 'IMG' ? null : 'img:not([contenteditable="false"])', function (e) {
        if ($(this).parents('[contenteditable="false"]:not(.fr-element):not(body)').length) return true;

        if (mousedown) {
          mousedown = false;

          // Remove moving class.
          e.stopPropagation();

          if (editor.browser.msie) {
            editor.$el.attr('contenteditable', true);
            editor.events.enableBlur();
          }
        }
      });

      // Show image popup when it was selected.
      editor.events.on('keyup', function (e) {
        if (e.shiftKey && editor.selection.text().replace(/\n/g, '') === '') {
          var s_el = editor.selection.element();
          var e_el = editor.selection.endElement();
          if (s_el && s_el.tagName == 'IMG') {
            _editImg($(s_el));
          }
          else if (e_el && e_el.tagName == 'IMG') {
            _editImg($(e_el));
          }
        }
      }, true);

      // Drop inside the editor.
      editor.events.on('drop', _drop);

      editor.events.on('mousedown window.mousedown', _markExit);
      editor.events.on('window.touchmove', _unmarkExit);

      editor.events.on('mouseup window.mouseup', function () {
        if ($current_image) {
          _exitEdit();
          return false;
        }
      });
      editor.events.on('commands.mousedown', function ($btn) {
        if ($btn.parents('.fr-toolbar').length > 0) {
          _exitEdit();
        }
      });

      editor.events.on('mouseup', _placeCursor);

      editor.events.on('blur image.hideResizer commands.undo commands.redo element.dropped', function () {
        mousedown = false;
        _exitEdit(true);
      });
    }

    /**
     * Init the image upload popup.
     */

    function _initInsertPopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('image.insert', _refreshInsertPopup);
        editor.popups.onHide('image.insert', _hideInsertPopup);

        return true;
      }

      var active;

      // Image buttons.
      var image_buttons = '';
      if (editor.opts.imageInsertButtons.length > 1) {
        image_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.imageInsertButtons) + '</div>';
      }

      var uploadIndex = editor.opts.imageInsertButtons.indexOf('imageUpload');
      var urlIndex = editor.opts.imageInsertButtons.indexOf('imageByURL');

      // Image upload layer.
      var upload_layer = '';
      if (uploadIndex >= 0) {
        active = ' fr-active';
        if (urlIndex >= 0 && uploadIndex > urlIndex) {
          active = '';
        }

        upload_layer = '<div class="fr-image-upload-layer' + active + ' fr-layer" id="fr-image-upload-layer-' + editor.id + '"><strong>' + editor.language.translate('Drop image') + '</strong><br>(' + editor.language.translate('or click') + ')<div class="fr-form"><input type="file" accept="image/*" tabIndex="-1"></div></div>'
      }

      // Image by url layer.
      var by_url_layer = '';
      if (urlIndex >= 0) {
        active = ' fr-active';
        if (uploadIndex >= 0 && urlIndex > uploadIndex) {
          active = '';
        }

        by_url_layer = '<div class="fr-image-by-url-layer' + active + ' fr-layer" id="fr-image-by-url-layer-' + editor.id + '"><div class="fr-input-line"><input type="text" placeholder="http://" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageInsertByURL" tabIndex="2">' + editor.language.translate('Insert') + '</button></div></div>'
      }

      // Progress bar.
      var progress_bar_layer = '<div class="fr-image-progress-bar-layer fr-layer"><h3 class="fr-message">Uploading</h3><div class="fr-loader"><span class="fr-progress"></span></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-back" data-cmd="imageDismissError" tabIndex="2">OK</button></div></div>';

      var template = {
        buttons: image_buttons,
        upload_layer: upload_layer,
        by_url_layer: by_url_layer,
        progress_bar: progress_bar_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('image.insert', template);

      if (editor.$wp) {
        editor.events.$on(editor.$wp, 'scroll', function () {
          if ($current_image && editor.popups.isVisible('image.insert')) {
            replace();
          }
        });
      }

      _bindInsertEvents($popup);

      return $popup;
    }

    /**
     * Refresh the ALT popup.
     */

    function _refreshAltPopup () {
      if ($current_image) {
        var $popup = editor.popups.get('image.alt');
        $popup.find('input').val($current_image.attr('alt') || '').trigger('change');
      }
    }

    /**
     * Show the ALT popup.
     */

    function showAltPopup () {
      var $popup = editor.popups.get('image.alt');
      if (!$popup) $popup = _initAltPopup();

      hideProgressBar();
      editor.popups.refresh('image.alt');
      editor.popups.setContainer('image.alt', $(editor.opts.scrollableContainer));
      var left = $current_image.offset().left + $current_image.width() / 2;
      var top = $current_image.offset().top + $current_image.height();

      editor.popups.show('image.alt', left, top, $current_image.outerHeight());
    }

    /**
     * Init the image upload popup.
     */

    function _initAltPopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('image.alt', _refreshAltPopup);
        return true;
      }

      // Image buttons.
      var image_buttons = '';
      image_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.imageAltButtons) + '</div>';

      // Image by url layer.
      var alt_layer = '';
      alt_layer = '<div class="fr-image-alt-layer fr-layer fr-active" id="fr-image-alt-layer-' + editor.id + '"><div class="fr-input-line"><input type="text" placeholder="' + editor.language.translate('Alternate Text') + '" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageSetAlt" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>'

      var template = {
        buttons: image_buttons,
        alt_layer: alt_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('image.alt', template);

      if (editor.$wp) {
        editor.events.$on(editor.$wp, 'scroll.image-alt', function () {
          if ($current_image && editor.popups.isVisible('image.alt')) {
            showAltPopup();
          }
        });
      }

      return $popup;
    }

    /**
     * Set ALT based on the values from the popup.
     */

    function setAlt (alt) {
      if ($current_image) {
        var $popup = editor.popups.get('image.alt');
        $current_image.attr('alt', alt || $popup.find('input').val() || '');
        $popup.find('input:focus').blur();
        _editImg($current_image);
      }
    }

    /**
     * Refresh the size popup.
     */

    function _refreshSizePopup () {
      if ($current_image) {
        var $popup = editor.popups.get('image.size');
        $popup.find('input[name="width"]').val($current_image.get(0).style.width).trigger('change');
        $popup.find('input[name="height"]').val($current_image.get(0).style.height).trigger('change');
      }
    }

    /**
     * Show the size popup.
     */

    function showSizePopup () {
      var $popup = editor.popups.get('image.size');
      if (!$popup) $popup = _initSizePopup();

      hideProgressBar();
      editor.popups.refresh('image.size');
      editor.popups.setContainer('image.size', $(editor.opts.scrollableContainer));
      var left = $current_image.offset().left + $current_image.width() / 2;
      var top = $current_image.offset().top + $current_image.height();

      editor.popups.show('image.size', left, top, $current_image.outerHeight());
    }

    /**
     * Init the image upload popup.
     */

    function _initSizePopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('image.size', _refreshSizePopup);

        return true;
      }

      // Image buttons.
      var image_buttons = '';
      image_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.imageSizeButtons) + '</div>';

      // Size layer.
      var size_layer = '';
      size_layer = '<div class="fr-image-size-layer fr-layer fr-active" id="fr-image-size-layer-' + editor.id + '"><div class="fr-image-group"><div class="fr-input-line"><input type="text" name="width" placeholder="' + editor.language.translate('Width') + '" tabIndex="1"></div><div class="fr-input-line"><input type="text" name="height" placeholder="' + editor.language.translate('Height') + '" tabIndex="1"></div></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageSetSize" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>'

      var template = {
        buttons: image_buttons,
        size_layer: size_layer
      };

      // Set the template in the popup.
      var $popup = editor.popups.create('image.size', template);

      if (editor.$wp) {
        editor.events.$on(editor.$wp, 'scroll.image-size', function () {
          if ($current_image && editor.popups.isVisible('image.size')) {
            showSizePopup();
          }
        });
      }

      return $popup;
    }

    /**
     * Set size based on the current image size.
     */

    function setSize (width, height) {
      if ($current_image) {
        var $popup = editor.popups.get('image.size');
        $current_image.css('width', width || $popup.find('input[name="width"]').val());
        $current_image.css('height', height || $popup.find('input[name="height"]').val());

        $popup.find('input:focus').blur();
        _editImg($current_image);
      }
    }

    /**
     * Show the image upload layer.
     */

    function showLayer (name) {
      var $popup = editor.popups.get('image.insert');

      var left;
      var top;

      // Click on the button from the toolbar without image selected.
      if (!$current_image && !editor.opts.toolbarInline) {
        var $btn = editor.$tb.find('.fr-command[data-cmd="insertImage"]');
        left = $btn.offset().left + $btn.outerWidth() / 2;
        top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
      }

      // Image is selected.
      else if ($current_image) {
        // Set the top to the bottom of the image.
        top = $current_image.offset().top + $current_image.outerHeight();
      }

      // Image is selected and we are in inline mode.
      if (!$current_image && editor.opts.toolbarInline) {
        // Set top to the popup top.
        top = $popup.offset().top - editor.helpers.getPX($popup.css('margin-top'));

        // If the popup is above apply height correction.
        if ($popup.hasClass('fr-above')) {
          top += $popup.outerHeight();
        }
      }

      // Show the new layer.
      $popup.find('.fr-layer').removeClass('fr-active');
      $popup.find('.fr-' + name + '-layer').addClass('fr-active');

      editor.popups.show('image.insert', left, top, ($current_image ? $current_image.outerHeight() : 0));
    }

    /**
     * Refresh the upload image button.
     */

    function refreshUploadButton ($btn) {
      var $popup = editor.popups.get('image.insert');
      if ($popup.find('.fr-image-upload-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Refresh the insert by url button.
     */

    function refreshByURLButton ($btn) {
      var $popup = editor.popups.get('image.insert');
      if ($popup.find('.fr-image-by-url-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Init image resizer.
     */

    function _initImageResizer () {
      var doc;

      // No shared image resizer.
      if (!editor.shared.$image_resizer) {
        // Create shared image resizer.
        editor.shared.$image_resizer = $('<div class="fr-image-resizer"></div>');
        $image_resizer = editor.shared.$image_resizer;

        // Bind mousedown event shared.
        editor.events.$on($image_resizer, 'mousedown', function (e) {
          e.stopPropagation();
        }, true);

        // Image resize is enabled.
        if (editor.opts.imageResize) {
          $image_resizer.append(_getHandler('nw') + _getHandler('ne') + _getHandler('sw') + _getHandler('se'));

          // Add image resizer overlay and set it.
          editor.shared.$img_overlay = $('<div class="fr-image-overlay"></div>');
          $overlay = editor.shared.$img_overlay;
          doc = $image_resizer.get(0).ownerDocument;
          $(doc).find('body').append($overlay);
        }
      } else {
        $image_resizer = editor.shared.$image_resizer;
        $overlay = editor.shared.$img_overlay;

        editor.events.on('destroy', function () {
          $image_resizer.removeClass('fr-active').appendTo($('body'));
        }, true);
      }

      // Shared destroy.
      editor.events.on('shared.destroy', function () {
        $image_resizer.html('').removeData().remove();
        $image_resizer = null;

        if (editor.opts.imageResize) {
          $overlay.remove();
          $overlay = null;
        }
      }, true);

      // Window resize. Exit from edit.
      if (!editor.helpers.isMobile()) {
        editor.events.$on($(editor.o_win), 'resize', function () {
          if ($current_image && !$current_image.hasClass('fr-uploading')) {
            _exitEdit(true);
          }
          else if ($current_image) {
            _repositionResizer();
            replace();
            showProgressBar(false);
          }
        });
      }

      // Image resize is enabled.
      if (editor.opts.imageResize) {
        doc = $image_resizer.get(0).ownerDocument;

        editor.events.$on($image_resizer, editor._mousedown, '.fr-handler', _handlerMousedown);
        editor.events.$on($(doc), editor._mousemove, _handlerMousemove);
        editor.events.$on($(doc.defaultView || doc.parentWindow), editor._mouseup, _handlerMouseup);

        editor.events.$on($overlay, 'mouseleave', _handlerMouseup);
      }
    }

    /**
     * Remove the current image.
     */

    function remove ($img) {
      $img = $img || $current_image;
      if ($img) {
        if (editor.events.trigger('image.beforeRemove', [$img]) !== false) {
          editor.popups.hideAll();
          _exitEdit(true);

          if ($img.get(0) == editor.$el.get(0)) {
            $img.removeAttr('src');
          } else {
            if ($img.get(0).parentNode.tagName == 'A') {
              editor.selection.setBefore($img.get(0).parentNode) || editor.selection.setAfter($img.get(0).parentNode) || $img.parent().after($.FE.MARKERS);
              $($img.get(0).parentNode).remove();
            } else {
              editor.selection.setBefore($img.get(0)) || editor.selection.setAfter($img.get(0)) || $img.after($.FE.MARKERS);
              $img.remove();
            }

            editor.html.fillEmptyBlocks();
            editor.selection.restore();
          }

          editor.undo.saveStep();
        }
      }
    }

    /**
     * Initialization.
     */

    function _init () {
      _initEvents();

      // Init on image.
      if (editor.$el.get(0).tagName == 'IMG') {
        editor.$el.addClass('fr-view');
      }

      editor.events.$on(editor.$el, editor.helpers.isMobile() && !editor.helpers.isWindowsPhone() ? 'touchend' : 'click', editor.$el.get(0).tagName == 'IMG' ? null : 'img:not([contenteditable="false"])', _edit);

      if (editor.helpers.isMobile()) {
        editor.events.$on(editor.$el, 'touchstart', editor.$el.get(0).tagName == 'IMG' ? null : 'img:not([contenteditable="false"])', function () {
          touchScroll = false;
        })

        editor.events.$on(editor.$el, 'touchmove', function () {
          touchScroll = true;
        });
      }

      editor.events.on('window.keydown keydown', function (e) {
        var key_code = e.which;
        if ($current_image && (key_code == $.FE.KEYCODE.BACKSPACE || key_code == $.FE.KEYCODE.DELETE)) {
          e.preventDefault();
          e.stopPropagation();
          remove();
          return false;
        }

        if ($current_image && key_code == $.FE.KEYCODE.ESC) {
          var $img = $current_image;
          _exitEdit(true);
          editor.selection.setAfter($img.get(0));
          editor.selection.restore();
          e.preventDefault();
          return false;
        }

        if ($current_image && !editor.keys.ctrlKey(e)) {
          e.preventDefault();
          return false;
        }
      }, true);

      // Do not leave page while uploading.
      editor.events.$on($(editor.o_win), 'keydown', function (e) {
        var key_code = e.which;
        if ($current_image && key_code == $.FE.KEYCODE.BACKSPACE) {
          e.preventDefault();
          return false;
        }
      });

      // Check if image is uploading to abort it.
      editor.events.$on(editor.$win, 'keydown', function (e) {
        var key_code = e.which;
        if ($current_image && $current_image.hasClass('fr-uploading') && key_code == $.FE.KEYCODE.ESC) {
          $current_image.trigger('abortUpload');
        }
      });

      editor.events.on('destroy', function () {
        if ($current_image && $current_image.hasClass('fr-uploading')) {
          $current_image.trigger('abortUpload');
        }
      });

      editor.events.on('paste.before', _clipboardPaste);
      editor.events.on('paste.beforeCleanup', _clipboardPasteCleanup);
      editor.events.on('paste.after', _uploadPastedImages);

      editor.events.on('html.set', _refreshImageList);
      editor.events.on('html.inserted', _refreshImageList);
      _refreshImageList();
      editor.events.on('destroy', function () {
        images = [];
      })

      editor.events.on('html.get', function (html) {
        html = html.replace(/<(img)((?:[\w\W]*?))class="([\w\W]*?)(fr-uploading|fr-error)([\w\W]*?)"((?:[\w\W]*?))>/g, '');

        return html;
      });

      if (editor.opts.imageOutputSize) {
        var imgs;

        editor.events.on('html.beforeGet', function () {
          imgs = editor.$el.get(0).querySelectorAll('img')
          for (var i = 0; i < imgs.length; i++) {
            imgs[i].setAttribute('width', $(imgs[i]).width());
            imgs[i].setAttribute('height', $(imgs[i]).height());
          }
        });

        editor.events.on('html.afterGet', function () {
          for (var i = 0; i < imgs.length; i++) {
            imgs[i].removeAttribute('width');
            imgs[i].removeAttribute('height');
          }
        });
      }

      if (editor.opts.iframe) {
        editor.events.on('image.loaded', editor.size.syncIframe);
      }

      if (editor.$wp) {
        _syncImages();
        editor.events.on('contentChanged', _syncImages);
      }

      editor.events.$on($(editor.o_win), 'orientationchange.image', function () {
        setTimeout(function () {
          var $current_image = get();
          if ($current_image) {
            _editImg($current_image);
          }
        }, 0);
      });

      _initEditPopup(true);
      _initInsertPopup(true);
      _initSizePopup(true);
      _initAltPopup(true);

      editor.events.on('node.remove', function ($node) {
        if ($node.get(0).tagName == 'IMG') {
          remove($node);
          return false;
        }
      });
    }

    function _uploadPastedImages () {
      if (!editor.opts.imagePaste) {
        editor.$el.find('img[data-fr-image-pasted]').remove();
      } else {
        // Safari won't work https://bugs.webkit.org/show_bug.cgi?id=49141
        editor.$el.find('img[data-fr-image-pasted]').each(function (index, img) {
          if (editor.opts.imagePasteProcess) {
            var width = editor.opts.imageDefaultWidth;
            if (width && width != 'auto') {
              width = width + (editor.opts.imageResizeWithPercent ? '%' : 'px');
            }
            $(img).css('width', width);

            $(img)
              .removeClass('fr-dii fr-dib fr-fir fr-fil')
              .addClass((editor.opts.imageDefaultDisplay ? 'fr-di' + editor.opts.imageDefaultDisplay[0] : '') + (editor.opts.imageDefaultAlign ? (editor.opts.imageDefaultAlign != 'center' ? ' fr-fi' + editor.opts.imageDefaultAlign[0] : '') : ''));
          }

          // Data images.
          if (img.src.indexOf('data:') === 0) {
            if (editor.events.trigger('image.beforePasteUpload', [img]) === false) {
              return false;
            }

            // Show the progress bar.
            $current_image = $(img);
            _repositionResizer();
            _showEditPopup();
            replace();
            showProgressBar();
            editor.edit.off();

            // Convert image to blob.
            var binary = atob($(img).attr('src').split(',')[1]);
            var array = [];
            for (var i = 0; i < binary.length; i++) {
              array.push(binary.charCodeAt(i));
            }
            var upload_img = new Blob([new Uint8Array(array)], {
              type: 'image/jpeg'
            });

            upload([upload_img]);

            $(img).removeAttr('data-fr-image-pasted');
          }

          // Images without http (Safari ones.).
          else if (img.src.indexOf('http') !== 0) {
            editor.selection.save();
            $(img).remove();
            editor.selection.restore();
          } else {
            $(img).removeAttr('data-fr-image-pasted');
          }
        });
      }
    }

    function _clipboardPaste (e) {
      if (e && e.clipboardData) {
        if (e.clipboardData.items && e.clipboardData.items[0]) {

          var file = e.clipboardData.items[0].getAsFile();

          if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
              var result = e.target.result;

              // Default width.
              var width = editor.opts.imageDefaultWidth;
              if (width && width != 'auto' && ('' + width).indexOf('px') < 0 && ('' + width).indexOf('%') < 0) {
                width = width + 'px';
              }

              editor.html.insert('<img data-fr-image-pasted="true" class="' + (editor.opts.imageDefaultDisplay ? 'fr-di' + editor.opts.imageDefaultDisplay[0] : '') + (editor.opts.imageDefaultAlign ? (editor.opts.imageDefaultAlign != 'center' ? ' fr-fi' + editor.opts.imageDefaultAlign[0] : '') : '') + '" src="' + result + '"' + (width ? ' style="width: ' + width + ';"' : '') + '>');

              editor.events.trigger('paste.after');
            };

            reader.readAsDataURL(file);

            return false;
          }
        }
      }
    }

    function _clipboardPasteCleanup (clipboard_html) {
      clipboard_html = clipboard_html.replace(/<img /gi, '<img data-fr-image-pasted="true" ');
      return clipboard_html;
    }

    /**
     * Start edit.
     */
    var touchScroll;

    function _edit (e) {
      if ($(this).parents('[contenteditable="false"]:not(.fr-element):not(body)').length) return true;

      if (e && e.type == 'touchend' && touchScroll) {
        return true;
      }

      if (e && editor.edit.isDisabled()) {
        e.stopPropagation();
        e.preventDefault();
        return false;
      }

      // Hide resizer for other instances.
      for (var i = 0; i < $.FE.INSTANCES.length; i++) {
        if ($.FE.INSTANCES[i] != editor) {
          $.FE.INSTANCES[i].events.trigger('image.hideResizer');
        }
      }

      editor.toolbar.disable();

      if (e) {
        e.stopPropagation();
        e.preventDefault();
      }

      // Hide keyboard.
      if (editor.helpers.isMobile()) {
        editor.events.disableBlur();
        editor.$el.blur();
        editor.events.enableBlur();
      }

      if (editor.opts.iframe) {
        editor.size.syncIframe();
      }

      $current_image = $(this);
      _selectImage();
      _repositionResizer();
      _showEditPopup();

      editor.selection.clear();
      editor.button.bulkRefresh();

      editor.events.trigger('video.hideResizer');
    }

    /**
     * Exit edit.
     */

    function _exitEdit (force_exit) {
      if ($current_image && (_canExit() || force_exit === true)) {
        editor.toolbar.enable();

        $image_resizer.removeClass('fr-active');

        editor.popups.hide('image.edit');

        $current_image = null;

        _unmarkExit();
      }
    }

    var img_exit_flag = false;

    function _markExit () {
      img_exit_flag = true;
    }

    function _unmarkExit () {
      img_exit_flag = false;
    }

    function _canExit () {
      return img_exit_flag;
    }

    /**
     * Align image.
     */

    function align (val) {
      $current_image.removeClass('fr-fir fr-fil');
      if (val == 'left') {
        $current_image.addClass('fr-fil');
      } else if (val == 'right') {
        $current_image.addClass('fr-fir');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the align icon.
     */

    function refreshAlign ($btn) {
      if ($current_image) {
        if ($current_image.hasClass('fr-fil')) {
          $btn.find('> *:first').replaceWith(editor.icon.create('align-left'));
        } else if ($current_image.hasClass('fr-fir')) {
          $btn.find('> *:first').replaceWith(editor.icon.create('align-right'));
        } else {
          $btn.find('> *:first').replaceWith(editor.icon.create('align-justify'));
        }
      }
    }

    /**
     * Refresh the align option from the dropdown.
     */

    function refreshAlignOnShow ($btn, $dropdown) {
      if ($current_image) {
        var alignment = 'justify';

        if ($current_image.hasClass('fr-fil')) {
          alignment = 'left';
        } else if ($current_image.hasClass('fr-fir')) {
          alignment = 'right';
        }

        $dropdown.find('.fr-command[data-param1="' + alignment + '"]').addClass('fr-active');
      }
    }

    /**
     * Align image.
     */

    function display (val) {
      $current_image.removeClass('fr-dii fr-dib');
      if (val == 'inline') {
        $current_image.addClass('fr-dii');
      } else if (val == 'block') {
        $current_image.addClass('fr-dib');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the image display selected option.
     */

    function refreshDisplayOnShow ($btn, $dropdown) {
      var d = 'block';
      if ($current_image.hasClass('fr-dii')) {
        d = 'inline';
      }

      $dropdown.find('.fr-command[data-param1="' + d + '"]').addClass('fr-active');
    }

    /**
     * Show the replace popup.
     */

    function replace () {
      var $popup = editor.popups.get('image.insert');
      if (!$popup) $popup = _initInsertPopup();

      if (!editor.popups.isVisible('image.insert')) {
        hideProgressBar();
        editor.popups.refresh('image.insert');
        editor.popups.setContainer('image.insert', $(editor.opts.scrollableContainer));
      }

      var left = $current_image.offset().left + $current_image.width() / 2;
      var top = $current_image.offset().top + $current_image.height();

      editor.popups.show('image.insert', left, top, $current_image.outerHeight());
    }

    /**
     * Place selection around current image.
     */
    function _selectImage () {
      if ($current_image) {
        editor.selection.clear();
        var range = editor.doc.createRange();
        range.selectNode($current_image.get(0));
        var selection = editor.selection.get();
        selection.addRange(range);
      }
    }

    /**
     * Get back to the image main popup.
     */
    function back () {
      if ($current_image) {
        $('.fr-popup input:focus').blur();
        _editImg($current_image);
      } else {
        editor.events.disableBlur();
        editor.selection.restore();
        editor.events.enableBlur();

        editor.popups.hide('image.insert');
        editor.toolbar.showInline();
      }
    }

    /**
     * Get the current image.
     */

    function get () {
      return $current_image;
    }

    /**
     * Apply specific style.
     */

    function applyStyle (val, imageStyles, multipleStyles) {
      if (typeof imageStyles == 'undefined') imageStyles = editor.opts.imageStyles;
      if (typeof multipleStyles == 'undefined') multipleStyles = editor.opts.imageMultipleStyles;

      if (!$current_image) return false;

      // Remove multiple styles.
      if (!multipleStyles) {
        var styles = Object.keys(imageStyles);
        styles.splice(styles.indexOf(val), 1);
        $current_image.removeClass(styles.join(' '));
      }

      $current_image.toggleClass(val);

      _editImg($current_image);
    }

    return {
      _init: _init,
      showInsertPopup: showInsertPopup,
      showLayer: showLayer,
      refreshUploadButton: refreshUploadButton,
      refreshByURLButton: refreshByURLButton,
      upload: upload,
      insertByURL: insertByURL,
      align: align,
      refreshAlign: refreshAlign,
      refreshAlignOnShow: refreshAlignOnShow,
      display: display,
      refreshDisplayOnShow: refreshDisplayOnShow,
      replace: replace,
      back: back,
      get: get,
      insert: insert,
      showProgressBar: showProgressBar,
      remove: remove,
      hideProgressBar: hideProgressBar,
      applyStyle: applyStyle,
      showAltPopup: showAltPopup,
      showSizePopup: showSizePopup,
      setAlt: setAlt,
      setSize: setSize,
      exitEdit: _exitEdit,
      edit: _editImg
    }
  }

  // Insert image button.
  $.FE.DefineIcon('insertImage', {
    NAME: 'image'
  });
  $.FE.RegisterShortcut($.FE.KEYCODE.P, 'insertImage', null, 'I');
  $.FE.RegisterCommand('insertImage', {
    title: 'Insert Image',
    undo: false,
    focus: true,
    refershAfterCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('image.insert')) {
        this.image.showInsertPopup();
      } else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('image.insert');
      }
    },
    plugin: 'image'
  });

  // Image upload button inside the insert image popup.
  $.FE.DefineIcon('imageUpload', {
    NAME: 'upload'
  });
  $.FE.RegisterCommand('imageUpload', {
    title: 'Upload Image',
    undo: false,
    focus: false,
    callback: function () {
      this.image.showLayer('image-upload');
    },
    refresh: function ($btn) {
      this.image.refreshUploadButton($btn);
    }
  });

  // Image by URL button inside the insert image popup.
  $.FE.DefineIcon('imageByURL', {
    NAME: 'link'
  });
  $.FE.RegisterCommand('imageByURL', {
    title: 'By URL',
    undo: false,
    focus: false,
    callback: function () {
      this.image.showLayer('image-by-url');
    },
    refresh: function ($btn) {
      this.image.refreshByURLButton($btn);
    }
  })

  // Insert image button inside the insert by URL layer.
  $.FE.RegisterCommand('imageInsertByURL', {
    title: 'Insert Image',
    undo: true,
    refreshAfterCallback: false,
    callback: function () {
      this.image.insertByURL();
    },
    refresh: function ($btn) {
      var $current_image = this.image.get();
      if (!$current_image) {
        $btn.text(this.language.translate('Insert'));
      } else {
        $btn.text(this.language.translate('Replace'));
      }
    }
  })

  // Image display.
  $.FE.DefineIcon('imageDisplay', {
    NAME: 'star'
  })
  $.FE.RegisterCommand('imageDisplay', {
    title: 'Display',
    type: 'dropdown',
    options: {
      inline: 'Inline',
      block: 'Break Text'
    },
    callback: function (cmd, val) {
      this.image.display(val);
    },
    refresh: function ($btn) {
      if (!this.opts.imageTextNear) $btn.addClass('fr-hidden');
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.image.refreshDisplayOnShow($btn, $dropdown);
    }
  })

  // Image align.
  $.FE.DefineIcon('imageAlign', {
    NAME: 'align-center'
  })
  $.FE.RegisterCommand('imageAlign', {
    type: 'dropdown',
    title: 'Align',
    options: {
      left: 'Align Left',
      justify: 'None',
      right: 'Align Right'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options = $.FE.COMMANDS.imageAlign.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command fr-title" data-cmd="imageAlign" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.icon.create('align-' + val) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.image.align(val);
    },
    refresh: function ($btn) {
      this.image.refreshAlign($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.image.refreshAlignOnShow($btn, $dropdown);
    }
  })

  // Image replace.
  $.FE.DefineIcon('imageReplace', {
    NAME: 'exchange'
  })
  $.FE.RegisterCommand('imageReplace', {
    title: 'Replace',
    undo: false,
    focus: false,
    refreshAfterCallback: false,
    callback: function () {
      this.image.replace();
    }
  })

  // Image remove.
  $.FE.DefineIcon('imageRemove', {
    NAME: 'trash'
  })
  $.FE.RegisterCommand('imageRemove', {
    title: 'Remove',
    callback: function () {
      this.image.remove();
    }
  })

  // Image back.
  $.FE.DefineIcon('imageBack', {
    NAME: 'arrow-left'
  });
  $.FE.RegisterCommand('imageBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    callback: function () {
      this.image.back();
    },
    refresh: function ($btn) {
      var $current_image = this.image.get();
      if (!$current_image && !this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      } else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  $.FE.RegisterCommand('imageDismissError', {
    title: 'OK',
    undo: false,
    callback: function () {
      this.image.hideProgressBar(true);
    }
  })

  // Image styles.
  $.FE.DefineIcon('imageStyle', {
    NAME: 'magic'
  })
  $.FE.RegisterCommand('imageStyle', {
    title: 'Style',
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options = this.opts.imageStyles;
      for (var cls in options) {
        if (options.hasOwnProperty(cls)) {
          c += '<li><a class="fr-command" data-cmd="imageStyle" data-param1="' + cls + '">' + this.language.translate(options[cls]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.image.applyStyle(val);
    },
    refreshOnShow: function ($btn, $dropdown) {
      var $current_image = this.image.get();

      if ($current_image) {
        $dropdown.find('.fr-command').each(function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $current_image.hasClass(cls));
        })
      }
    }
  })

  // Image alt.
  $.FE.DefineIcon('imageAlt', {
    NAME: 'info'
  })
  $.FE.RegisterCommand('imageAlt', {
    undo: false,
    focus: false,
    title: 'Alternate Text',
    callback: function () {
      this.image.showAltPopup();
    }
  });

  $.FE.RegisterCommand('imageSetAlt', {
    undo: true,
    focus: false,
    title: 'Update',
    refreshAfterCallback: false,
    callback: function () {
      this.image.setAlt();
    }
  });

  // Image size.
  $.FE.DefineIcon('imageSize', {
    NAME: 'arrows-alt'
  })
  $.FE.RegisterCommand('imageSize', {
    undo: false,
    focus: false,
    title: 'Change Size',
    callback: function () {
      this.image.showSizePopup();
    }
  });

  $.FE.RegisterCommand('imageSetSize', {
    undo: true,
    focus: false,
    title: 'Update',
    refreshAfterCallback: false,
    callback: function () {
      this.image.setSize();
    }
  });


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    imageManagerLoadURL: 'http://i.froala.com/load-files',
    imageManagerLoadMethod: 'get',
    imageManagerLoadParams: {},
    imageManagerPreloader: '',
    imageManagerDeleteURL: '',
    imageManagerDeleteMethod: 'post',
    imageManagerDeleteParams: {},
    imageManagerPageSize: 12,
    imageManagerScrollOffset: 20,
    imageManagerToggleTags: true
  });

  $.FE.PLUGINS.imageManager = function (editor) {
    var $modal;
    var $preloader;
    var $media_files;
    var $scroller;
    var $image_tags;
    var $modal_title;
    var $overlay;
    var images;
    var page;
    var image_count;
    var loaded_images;
    var column_number;

    // Load errors.
    var BAD_LINK = 10;
    var ERROR_DURING_LOAD = 11;
    var MISSING_LOAD_URL_OPTION = 12;
    var LOAD_BAD_RESPONSE = 13;
    var MISSING_IMG_THUMB = 14;
    var MISSING_IMG_URL = 15;

    // Delete errors
    var ERROR_DURING_DELETE = 21;
    var MISSING_DELETE_URL_OPTION = 22;

    // Error Messages
    var error_messages = {};
    error_messages[BAD_LINK] = 'Image cannot be loaded from the passed link.';
    error_messages[ERROR_DURING_LOAD] = 'Error during load images request.';
    error_messages[MISSING_LOAD_URL_OPTION] = 'Missing imageManagerLoadURL option.';
    error_messages[LOAD_BAD_RESPONSE] = 'Parsing load response failed.';
    error_messages[MISSING_IMG_THUMB] = 'Missing image thumb.';
    error_messages[MISSING_IMG_URL] = 'Missing image URL.';
    error_messages[ERROR_DURING_DELETE] = 'Error during delete image request.';
    error_messages[MISSING_DELETE_URL_OPTION] = 'Missing imageManagerDeleteURL option.';

    var $current_image;

    /*
     * Show the media manager.
     */
    function show () {
      // Build the media manager.
      if (!$modal) _build();

      $modal.data('instance', editor);

      // Show modal.
      $modal.show();
      $overlay.show();

      $current_image = editor.image.get();

      if (!$preloader) {
        _delayedInit();
      }

      // Load images.
      _loadImages();

      // Prevent scrolling in page.
      editor.$doc.find('body').addClass('prevent-scroll');

      // Mobile device
      if (editor.helpers.isMobile()) {
        editor.$doc.find('body').addClass('fr-mobile');
      }
    }

    /*
     * Hide the media manager.
     */
    function hide () {
      var inst = $modal.data('instance') || editor;
      inst.events.enableBlur();
      $modal.hide();
      $overlay.hide();
      inst.$doc.find('body').removeClass('prevent-scroll fr-mobile');
    }

    /*
     * Get the number of columns based on window width.
     */
    function _columnNumber () {
      var window_width = $(window).outerWidth();

      // Screen XS.
      if (window_width < 768) {
        return 2;
      }

      // Screen SM and MD.
      else if (window_width < 1200) {
        return 3;
      }

      // Screen LG.
      else {
        return 4;
      }
    }

    /*
     * Add the correct number of columns.
     */
    function _buildColumns () {
      $media_files.empty();

      for (var i = 0; i < column_number; i++) {
        $media_files.append('<div class="fr-list-column"></div>');
      }
    }

    /*
     * The media manager modal HTML.
     */
    function _modalHTML () {
      var cls = '';

      if (editor.opts.theme) {
        cls = ' ' + editor.opts.theme + '-theme';
      }

      // Modal wrapper.
      var html = '<div class="fr-modal' + cls + '"><div class="fr-modal-wrapper">';

      // Modal title.
      html += '<div class="fr-modal-title"><div class="fr-modal-title-line"><i class="fa fa-bars fr-modal-more fr-not-available" id="fr-modal-more-' + editor.sid + '" title="' + editor.language.translate('Tags') + '"></i><h4 data-text="true">' + editor.language.translate('Manage Images') + '</h4><i title="' + editor.language.translate('Cancel') + '" class="fa fa-times fr-modal-close" id="fr-modal-close"></i></div>';

      // Tags
      html += '<div class="fr-modal-tags" id="fr-modal-tags"></div>';
      html += '</div>';

      // Preloader.
      html += '<img class="fr-preloader" id="fr-preloader" alt="' + editor.language.translate('Loading') + '.." src="' + editor.opts.imageManagerPreloader + '" style="display: none;">';

      // Modal scroller.
      html += '<div class="fr-scroller" id="fr-scroller"><div class="fr-image-list" id="fr-image-list"></div></div>';

      html += '</div></div>';

      return $(html);
    }

    /*
     * Build the image manager.
     */
    function _build () {
      // Build modal.
      if (!editor.shared.$modal) {
        editor.shared.$modal = _modalHTML();

        $modal = editor.shared.$modal;

        // Desktop or mobile device.
        if (!editor.helpers.isMobile()) {
          $modal.addClass('fr-desktop');
        }

        // Append modal to body.
        $modal.appendTo('body');

        editor.shared.$overlay = $('<div class="fr-overlay">').appendTo('body');
        $overlay = editor.shared.$overlay;

        if (editor.opts.theme) {
          $overlay.addClass(editor.opts.theme + '-theme');
        }

        // Finished building the media manager.
        hide();
      }
      else {
        $modal = editor.shared.$modal;
        $overlay = editor.shared.$overlay;
      }

      // Editor destroy.
      editor.events.on('shared.destroy', function () {
        $modal.removeData().remove();
        $overlay.removeData().remove();
      }, true);
    }

    /*
     * Load images from server.
     */
    function _loadImages () {
      $preloader.show();
      $media_files.find('.fr-list-column').empty();

      // If the images load URL is set.
      if (editor.opts.imageManagerLoadURL) {
        // Make GET request to get the images.
        $.ajax({
          url: editor.opts.imageManagerLoadURL,
          method: editor.opts.imageManagerLoadMethod,
          data: editor.opts.imageManagerLoadParams,
          dataType: 'json',
          crossDomain: editor.opts.requestWithCORS,
          xhrFields: {
            withCredentials: editor.opts.requestWithCORS
          },
          headers: editor.opts.requestHeaders
        })
        // On success start processing the response.
        .done(function (data, status, xhr) {
          editor.events.trigger('imageManager.imagesLoaded', [data]);
          _processLoadedImages(data, xhr.response);
          $preloader.hide();
        })

        // On fail throw error during request.
        .fail(function () {
          var xhr = this.xhr();
          _throwError(ERROR_DURING_LOAD, xhr.response || xhr.responseText);
        });
      }

      // Throw missing imageManagerLoadURL option error.
      else {
        _throwError(MISSING_LOAD_URL_OPTION);
      }
    }

    /*
     * Process loaded images.
     */
    function _processLoadedImages (imgs, response) {
      try {
        $media_files.find('.fr-list-column').empty();
        page = 0;
        image_count = 0;
        loaded_images = 0;
        images = imgs;

        // Load files.
        _infiniteScroll();
      }

      // Throw error while parsing the response.
      catch (ex) {
        _throwError(LOAD_BAD_RESPONSE, response);
      }
    }

    /*
     * Load more images if necessary.
     */
    function _infiniteScroll () {
      // If there aren't enough images in the modal or if the user scrolls down.
      if (image_count < images.length &&
        ($media_files.outerHeight() <= $scroller.outerHeight() + editor.opts.imageManagerScrollOffset ||
        $scroller.scrollTop() + editor.opts.imageManagerScrollOffset > $media_files.outerHeight() - $scroller.outerHeight())) {
        // Increase page number.
        page++;

        // Load each image on this page.
        for (var i = editor.opts.imageManagerPageSize * (page - 1); i < Math.min(images.length, editor.opts.imageManagerPageSize * page); i++) {
          _loadImage(images[i]);
        }
      }
    }

    /*
     * Load file.
     */
    function _loadImage (image) {
      var img = new Image();
      var $img_container = $('<div class="fr-image-container fr-empty fr-image-' + (loaded_images++) + '" data-loading="' + editor.language.translate('Loading') + '.." data-deleting="' + editor.language.translate('Deleting') + '..">');

      // After adding image empty container modal might change its height.
      _resizeModal(false);

      // Image has been loaded.
      img.onload = function () {
        // Update image container height.
        $img_container.height(Math.floor($img_container.width() / img.width * img.height));

        // Create image HTML.
        var $img = $('<img/>');

        // Use image thumb in image manager.
        if (image.thumb) {
          // Set image src attribute/
          $img.attr('src', image.thumb);
        }

        // Image does not have thumb.
        else {
          // Throw missing image thumb error.
          _throwError(MISSING_IMG_THUMB, image);

          // Set image URL as src attribute.
          if (image.url) {
            $img.attr('src', image.url);
          }
          // Missing image URL.
          else {
            // Throw missing image url error.
            _throwError(MISSING_IMG_URL, image);

            // Don't go further if image does not have a src attribute.
            return false;
          }
        }

        // Save image URL.
        if (image.url) $img.attr('data-url', image.url);

        // Image tags.
        if (image.tag) {
          // Show tags only if there are any.
          $modal_title.find('.fr-modal-more.fr-not-available').removeClass('fr-not-available');
          $modal_title.find('.fr-modal-tags').show();

          // Image has more than one tag.
          if (image.tag.indexOf(',') >= 0) {
            // Add tags to the image manager tag list.
            var tags = image.tag.split(',');

            for (var i = 0; i < tags.length; i++) {
              // Remove trailing spaces.
              tags[i] = tags[i].trim();

              // Add tag.
              if ($image_tags.find('a[title="' + tags[i] + '"]').length === 0) {
                $image_tags.append('<a role="button" title="' + tags[i] + '">' + tags[i] + '</a>');
              }
            }

            // Set img tag attribute.
            $img.attr('data-tag', tags.join());
          }

          // Image has only one tag.
          else {
            // Add tag to the tag list.
            if ($image_tags.find('a[title="' + image.tag.trim() + '"]').length === 0) {
              $image_tags.append('<a role="button" title="' + image.tag.trim() + '">' + image.tag.trim() + '</a>');
            }

            // Set img tag attribute.
            $img.attr('data-tag', image.tag.trim());
          }
        }

        // Set image additional data.
        for (var key in image) {
          if (image.hasOwnProperty(key)) {
            if (key != 'thumb' && key != 'url' && key != 'tag') {
              $img.attr('data-' + key, image[key]);
            }
          }
        }

        // Add image and insert and delete buttons to the image container.
        $img_container
          .append($img)
          .append($(editor.icon.create('imageManagerDelete')).addClass('fr-delete-img').attr('title', editor.language.translate('Delete')))
          .append($(editor.icon.create('imageManagerInsert')).addClass('fr-insert-img').attr('title', editor.language.translate('Insert')))

        // Show image only if it has selected tags.
        $image_tags.find('.fr-selected-tag').each (function (index, tag) {
          if (!_imageHasTag($img, tag.text)) {
            $img_container.hide();
          }
        });

        // After an image is loaded the modal may need to be resized.
        $img.on('load', function () {
          // Image container is no longer empty.
          $img_container.removeClass('fr-empty');
          $img_container.height('auto');

          // Increase image counter.
          image_count++;

          // A loded image may break the images order. Reorder them starting with this image.
          var imgs = _getImages(parseInt($img.parent().attr('class').match(/fr-image-(\d+)/)[1], 10) + 1);

          // Reorder images.
          _reorderImages(imgs);

          // Image modal may need resizing.
          _resizeModal(false);

          // If this was the last image on page then we might need to load more.
          if (image_count % editor.opts.imageManagerPageSize === 0) {
            _infiniteScroll();
          }
        });

        // Trigger imageLoaded event.
        editor.events.trigger('imageManager.imageLoaded', [$img]);
      };

      // Error while loading the image.
      img.onerror = function () {
        image_count++;
        $img_container.remove();

        // Removing an image container may break image order.
        var imgs = _getImages(parseInt($img_container.attr('class').match(/fr-image-(\d+)/)[1], 10) + 1);

        // Reorder images.
        _reorderImages(imgs);

        _throwError(BAD_LINK, image);

        // If this was the last image on page then we might need to load more.
        if (image_count % editor.opts.imageManagerPageSize === 0) {
          _infiniteScroll();
        }
      };

      // Set the image object's src.
      img.src = image.url;

      // Add loaded or empty image to the media manager image list on the shortest column.
      _shortestColumn().append($img_container);
    }

    /*
     * Get the shortest image column.
     */
    function _shortestColumn () {
      var $col;
      var min_height;

      $media_files.find('.fr-list-column').each (function (index, col) {
        var $column = $(col);

        // Assume that the first column is the shortest.
        if (index === 0) {
          min_height = $column.outerHeight();
          $col = $column;
        }

        // Check if another column is shorter.
        else {
          if ($column.outerHeight() < min_height) {
            min_height = $column.outerHeight();
            $col = $column;
          }
        }
      });

      return $col;
    }

    /*
     * Get all images from the image manager.
     */
    function _getImages (from) {
      if (from === undefined) from = 0;
      var get_images = [];

      for (var i = loaded_images - 1; i >= from; i--) {
        var $image = $media_files.find('.fr-image-' + i);

        if ($image.length) {
          get_images.push($image);

          // Add images here before deleting them so the on load callback is triggered.
          $('<div id="fr-image-hidden-container">').append($image);
          $media_files.find('.fr-image-' + i).remove();
        }
      }

      return get_images;
    }

    /*
     * Add images back into the image manager.
     */
    function _reorderImages (imgs) {
      for (var i = imgs.length - 1; i >= 0; i--) {
        _shortestColumn().append(imgs[i]);
      }
    }

    /*
     * Resize the media manager modal and scroller if height changes.
     */
    function _resizeModal (infinite_scroll) {
      if (infinite_scroll === undefined) infinite_scroll = true;
      if (!$modal.is(':visible')) return true;

      // If width changes, the number of columns may change.
      var cols = _columnNumber();

      if (cols != column_number) {
        column_number = cols;

        // Get all images.
        var imgs = _getImages();

        // Remove current columns and add new ones.
        _buildColumns();

        // Reorder images.
        _reorderImages(imgs);
      }

      var height = editor.$win.height();

      // The wrapper and scroller objects.
      var $wrapper = $modal.find('.fr-modal-wrapper');

      // Wrapper's top and bottom margins.
      var wrapper_margins = parseFloat($wrapper.css('margin-top')) + parseFloat($wrapper.css('margin-bottom'));
      var wrapper_padding = parseFloat($wrapper.css('padding-top')) + parseFloat($wrapper.css('padding-bottom'));
      var wrapper_border_top = parseFloat($wrapper.css('border-top-width'));
      var h4_height = $wrapper.find('h4').outerHeight();

      // Change height.
      $scroller.height(Math.min($media_files.outerHeight(), height - wrapper_margins - wrapper_padding - h4_height - wrapper_border_top));

      // Load more photos when window is resized if necessary.
      if (infinite_scroll) {
        _infiniteScroll();
      }
    }

    function _getImageAttrs ($img) {
      var img_attributes = {};
      var img_data = $img.data();

      for (var key in img_data) {
        if (img_data.hasOwnProperty(key)) {
          if (key != 'url' && key != 'tag') {
            img_attributes[key] = img_data[key];
          }
        }
      }

      return img_attributes;
    }

    /*
     * Insert image into the editor.
     */
    function _insertImage (e) {
      // Image to insert.
      var $img = $(e.currentTarget).siblings('img');

      var inst = $modal.data('instance') || editor;

      hide();
      inst.image.showProgressBar();

      if (!$current_image) {
        // Make sure we have focus.
        inst.events.focus(true);
        inst.selection.restore();

        var rect = inst.position.getBoundingRect();

        var left = rect.left + rect.width / 2;
        var top = rect.top + rect.height;

        // Show the image insert popup.
        inst.popups.setContainer('image.insert', inst.$box || $('body'));
        inst.popups.show('image.insert', left, top);
      }
      else {
        $current_image.trigger('click');
      }

      inst.image.insert($img.data('url'), false, _getImageAttrs($img), $current_image);
    }

    /*
     * Delete image.
     */
    function _deleteImage (e) {
      // Image to delete.
      var $img = $(e.currentTarget).siblings('img');

      // Confirmation message.
      var message = editor.language.translate('Are you sure? Image will be deleted.');

      // Ask for confirmation.
      if (confirm(message)) {
        // If the images delete URL is set.
        if (editor.opts.imageManagerDeleteURL) {
          // Before delete image event.
          if (editor.events.trigger('imageManager.beforeDeleteImage', [$img]) !== false) {
            $img.parent().addClass('fr-image-deleting');

            // Make request to delete image from server.
            $.ajax({
              method: editor.opts.imageManagerDeleteMethod,
              url: editor.opts.imageManagerDeleteURL,
              data: $.extend($.extend({ src: $img.attr('src') }, _getImageAttrs($img)), editor.opts.imageManagerDeleteParams),
              crossDomain: editor.opts.requestWithCORS,
              xhrFields: {
                withCredentials: editor.opts.requestWithCORS
              },
              headers: editor.opts.requestHeaders
            })

              // On success remove the image from the image manager.
              .done(function (data) {
                editor.events.trigger('imageManager.imageDeleted', [data]);
                // A deleted image may break the images order. Reorder them starting with this image.
                var imgs = _getImages(parseInt($img.parent().attr('class').match(/fr-image-(\d+)/)[1], 10) + 1);

                // Remove the image.
                $img.parent().remove();

                // Reorder images.
                _reorderImages(imgs);

                // Modal needs resizing.
                _resizeModal(true);
              })

              // On fail throw error during request.
              .fail(function () {
                var xhr = this.xhr();
                _throwError(ERROR_DURING_DELETE, xhr.response || xhr.responseText);
              });
          }
        }

        // Throw missing imageManagerDeleteURL option error.
        else {
          _throwError(MISSING_DELETE_URL_OPTION);
        }
      }
    }

    /*
     * Throw image manager errors.
     */
    function _throwError (code, response) {
      // Load images error.
      if (10 <= code && code < 20) {
        // Hide preloader.
        $preloader.hide();
      }

      // Delete image error.
      else if (20 <= code && code < 30) {
        // Remove deleting overlay.
        $('.fr-image-deleting').removeClass('fr-image-deleting');
      }

      // Trigger error event.
      editor.events.trigger('imageManager.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    /*
     * Toogle (show or hide) image tags.
     */
    function _toggleTags () {
      var title_height = $modal_title.find('.fr-modal-title-line').outerHeight();
      var tags_height = $image_tags.outerHeight();

      // Use .fr-show-tags.
      $modal_title.toggleClass('.fr-show-tags');

      if ($modal_title.hasClass('.fr-show-tags')) {
        // Show tags by changing height to have transition.
        $modal_title.css('height', title_height + tags_height);
        $image_tags.find('a').css('opacity', 1);
      }

      else {
        // Hide tags by changing height to have transition.
        $modal_title.css('height', title_height);
        $image_tags.find('a').css('opacity', 0);
      }
    }

    /*
     * Show only images with selected tags.
     */
    function _showImagesByTags() {
      // Get all selected tags.
      var $tags = $image_tags.find('.fr-selected-tag');

      // Show only images with selected tags.
      if ($tags.length > 0) {
        // Hide all images.
        $media_files.find('img').parent().show();

        // Show only images with tag.
        $tags.each (function (index, tag) {
          $media_files.find('img').each (function (index, img) {
            var $img = $(img);

            if (!_imageHasTag($img, tag.text)) {
              $img.parent().hide();
            }
          });
        });
      }

      // There are no more tags selected. Show all images.
      else {
        $media_files.find('img').parent().show();
      }

      // Rearrange images.
      var imgs = _getImages();

      // Reorder images.
      _reorderImages(imgs);

      // Load more images if necessary.
      _infiniteScroll();
    }

    /*
     * Select an image tag from the list.
     */
    function _selectTag (e) {
      e.preventDefault();

      // Toggle current tags class.
      var $tag = $(e.currentTarget);
      $tag.toggleClass('fr-selected-tag');

      // Toggle selected tags.
      if (editor.opts.imageManagerToggleTags) $tag.siblings('a').removeClass('fr-selected-tag');

      // Change displayed images.
      _showImagesByTags();
    }

    /*
     * Method to check if an image has a specific tag.
     */
    function _imageHasTag ($image, tag) {
      var tags = $image.attr('data-tag').split(',');

      for (var i = 0; i < tags.length; i++) {
        if (tags[i] == tag) {
          return true;
        }
      }

      return false;
    }

    function _delayedInit() {
      $preloader = $modal.find('#fr-preloader');
      $media_files = $modal.find('#fr-image-list');
      $scroller = $modal.find('#fr-scroller');
      $image_tags = $modal.find('#fr-modal-tags');
      $modal_title = $image_tags.parent();

      // Columns.
      column_number = _columnNumber();
      _buildColumns();

      // Set height for title (we need this for show tags transition).
      var title_height = $modal_title.find('.fr-modal-title-line').outerHeight();
      $modal_title.css('height', title_height);
      $scroller.css('margin-top', title_height);

      // Close button.
      editor.events.bindClick($modal, 'i#fr-modal-close', hide);

      // Resize media manager modal on window resize.
      editor.events.$on($(editor.o_win), 'resize', function () {
        // Window resize with image manager opened.
        if (images) {
          _resizeModal(true);
        }

        // iOS window resize is triggered when modal first opens (no images loaded).
        else {
          _resizeModal(false);
        }
      });

      // Delete and insert buttons for mobile.
      if (editor.helpers.isMobile()) {
        // Show image buttons on mobile.
        editor.events.bindClick($media_files, 'div.fr-image-container', function (e) {
          $modal.find('.fr-mobile-selected').removeClass('fr-mobile-selected');
          $(e.currentTarget).addClass('fr-mobile-selected');
        });

        // Hide image buttons if we click outside it.
        $modal.on(editor._mousedown, function () {
          $modal.find('.fr-mobile-selected').removeClass('fr-mobile-selected');
        });
      }

      // Insert image.
      editor.events.bindClick($media_files, '.fr-insert-img', _insertImage);

      // Delete image.
      editor.events.bindClick($media_files, '.fr-delete-img', _deleteImage);

      // Make sure we don't trigger blur.
      $modal.on(editor._mousedown + ' ' + editor._mouseup, function (e) {
        e.stopPropagation();
      });

      // Mouse down on anything.
      $modal.on(editor._mousedown, '*', function () {
        editor.events.disableBlur();
      });

      // Infinite scroll
      $scroller.on('scroll', _infiniteScroll);

      // Click on image tags button.
      editor.events.bindClick($modal, 'i#fr-modal-more-' + editor.sid, _toggleTags);

      // Select an image tag.
      editor.events.bindClick($image_tags, 'a', _selectTag);
    }

    /*
     * Init media manager.
     */
    function _init () {
      if (!editor.$wp && editor.$el.get(0).tagName != 'IMG') return false;
    }

    return {
      require: ['image'],
      _init: _init,
      show: show,
      hide: hide
    }
  };

  if (!$.FE.PLUGINS.image) {
    throw new Error('Image manager plugin requires image plugin.');
  }

  $.FE.DEFAULTS.imageInsertButtons.push('imageManager');

  $.FE.RegisterCommand('imageManager', {
    title: 'Browse',
    undo: false,
    focus: false,
    callback: function () {
      this.imageManager.show();
    },
    plugin: 'imageManager'
  })

  // Add the font size icon.
  $.FE.DefineIcon('imageManager', {
    NAME: 'folder'
  });

  // Add the font size icon.
  $.FE.DefineIcon('imageManagerInsert', {
    NAME: 'plus'
  });

  // Add the font size icon.
  $.FE.DefineIcon('imageManagerDelete', {
    NAME: 'trash'
  });


  'use strict';

  $.extend($.FE.DEFAULTS, {
    inlineStyles: {
      'Big Red': 'font-size: 20px; color: red;',
      'Small Blue': 'font-size: 14px; color: blue;'
    }
  })

  $.FE.PLUGINS.inlineStyle = function (editor) {
    function apply (val) {
      if (editor.selection.text() !== '') {
        editor.html.insert($.FE.START_MARKER + '<span style="' + val + '">' + editor.selection.text() + '</span>' + $.FE.END_MARKER);
      }
      else {
        editor.html.insert('<span style="' + val + '">' + $.FE.INVISIBLE_SPACE + $.FE.MARKERS + '</span>');
      }
    }

    return {
      apply: apply
    }
  }

  // Register the inline style command.
  $.FE.RegisterCommand('inlineStyle', {
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.inlineStyles;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><span style="' + options[val] + '"><a class="fr-command" data-cmd="inlineStyle" data-param1="' + options[val] + '" title="' + this.language.translate(val) + '">' + this.language.translate(val) + '</a></span></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    title: 'Inline Style',
    callback: function (cmd, val) {
      this.inlineStyle.apply(val);
    },
    plugin: 'inlineStyle'
  })

  // Add the font size icon.
  $.FE.DefineIcon('inlineStyle', {
    NAME: 'paint-brush'
  });


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    lineBreakerTags: ['table', 'hr', 'form', 'dl', 'span.fr-video'],
    lineBreakerOffset: 15
  });

  $.FE.PLUGINS.lineBreaker = function (editor) {
    var $line_breaker;
    var mouseDownFlag;
    var mouseMoveTimer;

    /*
     * Show line breaker.
     * Compute top, left, width and show the line breaker.
     * tag1 and tag2 are the tags between which the line breaker must be showed.
     * If tag1 is null then tag2 is the first tag in the editor.
     * If tag2 is null then tag1 is the last tag in the editor.
     */
    function _show ($tag1, $tag2) {
      // Line breaker's possition and width.
      var breakerTop;
      var breakerLeft;
      var breakerWidth;
      var parent_tag;
      var parent_top;
      var parent_bottom;
      var tag_top;
      var tag_bottom;

      // Mouse is over the first tag in the editor. Show line breaker above tag2.
      if ($tag1 == null) {
        // Compute line breaker's possition and width.
        parent_tag = $tag2.parent();
        parent_top = parent_tag.offset().top;
        tag_top = $tag2.offset().top;

        breakerTop = tag_top - Math.min((tag_top - parent_top) / 2, editor.opts.lineBreakerOffset);
        breakerWidth = parent_tag.outerWidth();
        breakerLeft = parent_tag.offset().left;

      // Mouse is over the last tag in the editor. Show line breaker below tag1.
      } else if ($tag2 == null) {
        // Compute line breaker's possition and width.
        parent_tag = $tag1.parent();
        parent_bottom = parent_tag.offset().top + parent_tag.outerHeight();
        tag_bottom = $tag1.offset().top + $tag1.outerHeight();

        breakerTop = tag_bottom + Math.min((parent_bottom - tag_bottom) / 2, editor.opts.lineBreakerOffset);
        breakerWidth = parent_tag.outerWidth();
        breakerLeft = parent_tag.offset().left;

      // Mouse is between the 2 tags.
      } else {
        // Compute line breaker's possition and width.
        parent_tag = $tag1.parent();
        var tag1_bottom = $tag1.offset().top + $tag1.height();
        var tag2_top = $tag2.offset().top;

        // Tags may be on the same line, so there is no need for line breaker.
        if (tag1_bottom > tag2_top) {
          return false;
        }

        breakerTop = (tag1_bottom + tag2_top) / 2;
        breakerWidth = parent_tag.outerWidth();
        breakerLeft = parent_tag.offset().left;
      }

      if (editor.opts.iframe) {
        breakerLeft += editor.$iframe.offset().left - $(editor.o_win).scrollLeft();
        breakerTop += editor.$iframe.offset().top - $(editor.o_win).scrollTop();
      }

      editor.$box.append($line_breaker);

      // Set line breaker's top, left and width.
      $line_breaker.css('top', breakerTop - editor.win.pageYOffset);
      $line_breaker.css('left', breakerLeft - editor.win.pageXOffset);
      $line_breaker.css('width', breakerWidth);

      $line_breaker.data('tag1', $tag1);
      $line_breaker.data('tag2', $tag2);

      // Show the line breaker.
      $line_breaker.addClass('fr-visible').data('instance', editor);
    }

    /*
     * Check tag siblings.
     * The line breaker hould appear if there is no sibling or if the sibling is also in the line breaker tags list.
     */
    function _checkTagSiblings ($tag, mouseY) {
      // Tag's Y top and bottom coordinate.
      var tag_top = $tag.offset().top;
      var tag_bottom = $tag.offset().top + $tag.outerHeight();
      var $sibling;
      var tag;

      // Only if the mouse is close enough to the bottom or top edges.
      if (Math.abs(tag_bottom - mouseY) <= editor.opts.lineBreakerOffset ||
          Math.abs(mouseY - tag_top) <= editor.opts.lineBreakerOffset) {

        // Mouse is near bottom check for next sibling.
        if (Math.abs(tag_bottom - mouseY) < Math.abs(mouseY - tag_top)) {
          tag = $tag.get(0);

          var next_node = tag.nextSibling;
          while (next_node && next_node.nodeType == Node.TEXT_NODE && next_node.textContent.length === 0) {
            next_node = next_node.nextSibling;
          }

          // Tag has next sibling.
          if (next_node) {
            $sibling = _checkTag(next_node);

            // Sibling is in the line breaker tags list.
            if ($sibling) {
              // Show line breaker.
              _show($tag, $sibling);
              return true;
            }

          // No next sibling.
          } else {
            // Show line breaker
            _show($tag, null);
            return true;
          }
        }

        // Mouse is near top check for prev sibling.
        else {
          tag = $tag.get(0);

          // No prev sibling.
          if (!tag.previousSibling) {
            // Show line breaker
            _show(null, $tag);
            return true;

          // Tag has prev sibling.
          } else {
            $sibling = _checkTag(tag.previousSibling);

            // Sibling is in the line breaker tags list.
            if ($sibling) {
              // Show line breaker.
              _show($sibling, $tag);
              return true;
            }
          }
        }
      }

      $line_breaker.removeClass('fr-visible').removeData('instance');
    }

    /*
     * Check if tag is in the line breaker list and in the editor as well.
     * Returns the tag from the line breaker list or false if the tag is not in the list.
     */
    function _checkTag (tag) {
      if (tag) {
        var $tag = $(tag);

        // Make sure tag is inside the editor.
        if (editor.$el.find($tag).length === 0) return null;

        // Tag is in the line breaker tags list.
        if (tag.nodeType != Node.TEXT_NODE && $tag.is(editor.opts.lineBreakerTags.join(','))) {
          return $tag;
        }

        // Tag's parent is in the line breaker tags list.
        else if ($tag.parents(editor.opts.lineBreakerTags.join(',')).length > 0) {
          tag = $tag.parents(editor.opts.lineBreakerTags.join(',')).get(0);

          return $(tag);
        }
      }

      return null;
    }

    /*
     * Get the tag under the mouse cursor.
     */
    function _tagUnder (e) {
      mouseMoveTimer = null;

      // The tag for which the line breaker should be showed.
      var $tag = null;

      // The tag under the mouse cursor.
      var tag_under = editor.doc.elementFromPoint(e.pageX - editor.win.pageXOffset, e.pageY - editor.win.pageYOffset);
      var i;
      var tag_above;
      var tag_below;

      // Tag is the editor element. Look for closest tag above and bellow.
      if (tag_under && (tag_under.tagName == 'HTML' || tag_under.tagName == 'BODY' || editor.node.isElement(tag_under))) {
        // Look 1px up and 1 down until a tag is found or the line breaker offset is reached.
        for (i = 1; i <= editor.opts.lineBreakerOffset; i++) {
          // Look for tag above.
          tag_above = editor.doc.elementFromPoint(e.pageX - editor.win.pageXOffset, e.pageY - editor.win.pageYOffset - i);

          // We found a tag above.
          if (tag_above && !editor.node.isElement(tag_above) && tag_above != editor.$wp.get(0) && $(tag_above).parents(editor.$wp).length) {
            $tag = _checkTag(tag_above);
            break;
          }

          // Look for tag below.
          tag_below = editor.doc.elementFromPoint(e.pageX - editor.win.pageXOffset, e.pageY - editor.win.pageYOffset + i);

          // We found a tag bellow.
          if (tag_below && !editor.node.isElement(tag_below) && tag_below != editor.$wp.get(0) && $(tag_below).parents(editor.$wp).length) {
            $tag = _checkTag(tag_below);
            break;
          }
        }

      // Tag is not the editor element.
      } else {
        // Check if the tag is in the line breaker list.
        $tag = _checkTag(tag_under);
      }

      // Check tag siblings.
      if ($tag) {
        _checkTagSiblings($tag, e.pageY);
      }
      else if (editor.core.sameInstance($line_breaker)) {
        $line_breaker.removeClass('fr-visible').removeData('instance');
      }
    }

    /*
     * Set mouse timer to improve performance.
     */
    function _mouseTimer (e) {
      if ($line_breaker.hasClass('fr-visible') && !editor.core.sameInstance($line_breaker)) return false;

      if (editor.popups.areVisible() || editor.$el.get(0).querySelectorAll('.fr-selected-cell').length) {
        $line_breaker.removeClass('fr-visible');
        return true;
      }

      if (mouseDownFlag === false) {
        if (mouseMoveTimer) {
          clearTimeout(mouseMoveTimer);
        }

        mouseMoveTimer = setTimeout(_tagUnder, 30, e);
      }
    }

    /*
     * Hide line breaker and prevent timer from showing it again.
     */
    function _hide () {
      if (mouseMoveTimer) {
        clearTimeout(mouseMoveTimer);
      }

      if ($line_breaker.hasClass('fr-visible')) {
        $line_breaker.removeClass('fr-visible').removeData('instance');
      }
    }

    /*
     * Notify that mouse is down and prevent line breaker from showing.
     * This may happen either for selection or for drag.
     */
    function _mouseDown () {
      mouseDownFlag = true;
      _hide();
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
    function _doLineBreak (e) {
      if (!editor.core.sameInstance($line_breaker)) return true;

      e.preventDefault();

      // Hide the line breaker.
      $line_breaker.removeClass('fr-visible').removeData('instance');

      // Tags between which that line break needs to be done.
      var $tag1 = $line_breaker.data('tag1');
      var $tag2 = $line_breaker.data('tag2');

      // P, DIV or none.
      var default_tag = editor.html.defaultTag();

      // The line break needs to be done before the first element in the editor.
      if ($tag1 == null) {
        // If the tag is in a TD tag then just add <br> no matter what the default_tag is.
        if (default_tag && $tag2.parent().get(0).tagName != 'TD') {
          $tag2.before('<' + default_tag + '>' + $.FE.MARKERS + '<br></' + default_tag + '>')
        }
        else {
          $tag2.before($.FE.MARKERS + '<br>');
        }

      // The line break needs to be done either after the last element in the editor or between the 2 tags.
      // Either way the line break is after the first tag.
      } else {
        // If the tag is in a TD tag then just add <br> no matter what the default_tag is.
        if (default_tag && $tag1.parent().get(0).tagName != 'TD' && $tag1.parents(default_tag).length === 0) {
          $tag1.after('<' + default_tag + '>' + $.FE.MARKERS + '<br></' + default_tag + '>')
        }
        else {
          $tag1.after($.FE.MARKERS + '<br>');
        }
      }

      // Cursor is now at the beginning of the new line.
      editor.selection.restore();
    }

    /*
     * Initialize the line breaker.
     */
    function _initLineBreaker () {
      // Append line breaker HTML to editor wrapper.
      if (!editor.shared.$line_breaker) {
        editor.shared.$line_breaker = $('<div class="fr-line-breaker"><a class="fr-floating-btn" role="button" tabindex="-1" title="' + editor.language.translate('Break') + '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect x="21" y="11" width="2" height="8"/><rect x="14" y="17" width="7" height="2"/><path d="M14.000,14.000 L14.000,22.013 L9.000,18.031 L14.000,14.000 Z"/></svg></a></div>');
      }

      $line_breaker = editor.shared.$line_breaker;

      // Editor shared destroy.
      editor.events.on('shared.destroy', function () {
        $line_breaker.html('').removeData().remove();
        $line_breaker = null;
      }, true);

      // Editor destroy.
      editor.events.on('destroy', function () {
        $line_breaker.removeData('instance').removeClass('fr-visible').appendTo('body');
        clearTimeout(mouseMoveTimer);
      }, true)

      editor.events.$on($line_breaker, 'mouseleave', _hide, true);

      editor.events.$on($line_breaker, 'mousemove', function (e) {
        e.stopPropagation();
      }, true)

      // Add new line break.
      editor.events.$on($line_breaker, 'mousedown', 'a', function (e) {
        e.stopPropagation();
      }, true);
      editor.events.$on($line_breaker, 'click', 'a', _doLineBreak, true);
    }

    /*
     * Tear up.
     */
    function _init () {
      if (!editor.$wp) return false;

      _initLineBreaker();

      // Remember if mouse is clicked so the line breaker does not appear.
      mouseDownFlag = false;

      // Check tags under the mouse to see if the line breaker needs to be shown.
      editor.events.$on(editor.$win, 'mousemove', _mouseTimer);

      // Hide the line breaker if the page is scrolled.
      editor.events.$on($(editor.win), 'scroll', _hide);

      // Hide the line breaker on cell edit.
      editor.events.on('popups.show.table.edit', _hide);

      // Prevent line breaker from showing while selecting text or dragging images.
      editor.events.$on($(editor.win), 'mousedown', _mouseDown);

      // Mouse is not pressed anymore, line breaker may be shown.
      editor.events.$on($(editor.win), 'mouseup', _mouseUp);
    }

    return {
      _init: _init
    }
  };


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'link.edit': '[_BUTTONS_]',
    'link.insert': '[_BUTTONS_][_INPUT_LAYER_]'
  })

  $.extend($.FE.DEFAULTS, {
    linkEditButtons: ['linkOpen', 'linkStyle', 'linkEdit', 'linkRemove'],
    linkInsertButtons: ['linkBack', '|', 'linkList'],
    linkAttributes: {},
    linkAutoPrefix: 'http://',
    linkStyles: {
      'fr-green': 'Green',
      'fr-strong': 'Thick'
    },
    linkMultipleStyles: true,
    linkConvertEmailAddress: true,
    linkAlwaysBlank: false,
    linkAlwaysNoFollow: false,
    linkList: [
      {
        text: 'Froala',
        href: 'https://froala.com',
        target: '_blank'
      },
      {
        text: 'Google',
        href: 'https://google.com',
        target: '_blank'
      },
      {
        displayText: 'Facebook',
        href: 'https://facebook.com'
      }
    ],
    linkText: true
  });

  $.FE.PLUGINS.link = function (editor) {
    function get () {
      var $current_image = editor.image ? editor.image.get() : null;

      if (!$current_image && editor.$wp) {
        var s_el = editor.selection.element();
        var e_el = editor.selection.endElement();

        if (s_el.tagName != 'A') {
          s_el = $(s_el).parents('a:first').get(0);
        }

        if (e_el.tagName != 'A') {
          e_el = $(e_el).parents('a:first').get(0);
        }

        if (e_el && e_el == s_el) {
          return s_el;
        }

        return null;
      }
      else if (editor.$el.get(0).tagName == 'A' && editor.core.hasFocus()) {
        return editor.$el.get(0);
      }
      else {
        if ($current_image && $current_image.get(0).parentNode && $current_image.get(0).parentNode.tagName == 'A') {
          return $current_image.get(0).parentNode;
        }
      }
    }

    function allSelected () {
      var $current_image = editor.image ? editor.image.get() : null;

      var selectedLinks = [];
      if ($current_image) {
        if ($current_image.get(0).parentNode.tagName == 'A') {
          selectedLinks.push($current_image.get(0).parentNode);
        }
      }
      else {
        var range;
        var containerEl;
        var links;
        var linkRange;

        if (editor.win.getSelection) {
          var sel = editor.win.getSelection();
          if (sel.getRangeAt && sel.rangeCount) {
            linkRange = editor.doc.createRange();
            for (var r = 0; r < sel.rangeCount; ++r) {
              range = sel.getRangeAt(r);
              containerEl = range.commonAncestorContainer;

              if (containerEl && containerEl.nodeType != 1) {
                containerEl = containerEl.parentNode;
              }

              if (containerEl && containerEl.nodeName.toLowerCase() == 'a') {
                selectedLinks.push(containerEl);
              } else {
                links = containerEl.getElementsByTagName('a');
                for (var i = 0; i < links.length; ++i) {
                  linkRange.selectNodeContents(links[i]);
                  if (linkRange.compareBoundaryPoints(range.END_TO_START, range) < 1 && linkRange.compareBoundaryPoints(range.START_TO_END, range) > -1) {
                    selectedLinks.push(links[i]);
                  }
                }
              }
            }
            // linkRange.detach();
          }
        } else if (editor.doc.selection && editor.doc.selection.type != 'Control') {
          range = editor.doc.selection.createRange();
          containerEl = range.parentElement();
          if (containerEl.nodeName.toLowerCase() == 'a') {
            selectedLinks.push(containerEl);
          } else {
            links = containerEl.getElementsByTagName('a');
            linkRange = editor.doc.body.createTextRange();
            for (var j = 0; j < links.length; ++j) {
              linkRange.moveToElementText(links[j]);
              if (linkRange.compareEndPoints('StartToEnd', range) > -1 && linkRange.compareEndPoints('EndToStart', range) < 1) {
                selectedLinks.push(links[j]);
              }
            }
          }
        }
      }

      return selectedLinks;
    }

    function _edit (e) {
      _hideEditPopup();

      setTimeout (function () {
        // No event passed.
        // Event passed and (left click or other event type).
        if (!e || (e && (e.which == 1 || e.type != 'mouseup'))) {
          var link = get();
          var $current_image = editor.image ? editor.image.get() : null;

          if (link && !$current_image) {
            if (editor.image) {
              var contents = editor.node.contents(link);

              // https://github.com/froala/wysiwyg-editor/issues/1103
              if (contents.length == 1 && contents[0].tagName == 'IMG') {
                var range = editor.selection.ranges(0);
                if (range.startOffset === 0 && range.endOffset === 0) {
                  $(link).before($.FE.MARKERS);
                }
                else {
                  $(link).after($.FE.MARKERS);
                }

                editor.selection.restore();
                return false;
              }
            }

            if (e) {
              e.stopPropagation();
            }

            _showEditPopup(link);
          }
        }
      }, editor.helpers.isIOS() ? 100 : 0);
    }

    function _showEditPopup (link) {
      var $popup = editor.popups.get('link.edit');
      if (!$popup) $popup = _initEditPopup();

      var $link = $(link);

      if (!editor.popups.isVisible('link.edit')) {
        editor.popups.refresh('link.edit');
      }

      editor.popups.setContainer('link.edit', $(editor.opts.scrollableContainer));
      var left = $link.offset().left + $(link).outerWidth() / 2;
      var top = $link.offset().top + $link.outerHeight();

      editor.popups.show('link.edit', left, top, $link.outerHeight());
    }

    function _hideEditPopup () {
      editor.popups.hide('link.edit');
    }

    function _initEditPopup () {
      // Link buttons.
      var link_buttons = '';
      if (editor.opts.linkEditButtons.length > 1) {
        if (editor.$el.get(0).tagName == 'A' && editor.opts.linkEditButtons.indexOf('linkRemove') >= 0) {
          editor.opts.linkEditButtons.splice(editor.opts.linkEditButtons.indexOf('linkRemove'), 1);
        }

        link_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.linkEditButtons) + '</div>';
      }

      var template = {
        buttons: link_buttons
      };

      // Set the template in the popup.
      var $popup = editor.popups.create('link.edit', template);

      if (editor.$wp) {
        editor.events.$on(editor.$wp, 'scroll.link-edit', function () {
          if (get() && editor.popups.isVisible('link.edit')) {
            _showEditPopup(get());
          }
        });
      }

      return $popup;
    }

    /**
     * Hide link insert popup.
     */
    function _hideInsertPopup () {
    }

    function _refreshInsertPopup () {
      var $popup = editor.popups.get('link.insert');
      var link = get();

      if (link) {
        var $link = $(link);
        var text_inputs = $popup.find('input.fr-link-attr[type="text"]');
        var check_inputs = $popup.find('input.fr-link-attr[type="checkbox"]');


        var i;
        var $input;
        for (i = 0; i < text_inputs.length; i++) {
          $input = $(text_inputs[i]);
          $input.val($link.attr($input.attr('name') || ''));
        }

        check_inputs.prop('checked', false);
        for (i = 0; i < check_inputs.length; i++) {
          $input = $(check_inputs[i]);
          if ($link.attr($input.attr('name')) == $input.data('checked')) {
            $input.prop('checked', true);
          }
        }

        $popup.find('input.fr-link-attr[type="text"][name="text"]').val($link.text());
      }
      else {
        $popup.find('input.fr-link-attr[type="text"]').val('');
        $popup.find('input.fr-link-attr[type="checkbox"]').prop('checked', false);
        $popup.find('input.fr-link-attr[type="text"][name="text"]').val(editor.selection.text());
      }

      $popup.find('input.fr-link-attr').trigger('change');

      var $current_image = editor.image ? editor.image.get() : null;
      if ($current_image) {
        $popup.find('.fr-link-attr[name="text"]').parent().hide();
      }
      else {
        $popup.find('.fr-link-attr[name="text"]').parent().show();
      }
    }

    function _showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertLink"]');

      var $popup = editor.popups.get('link.insert');
      if (!$popup) $popup = _initInsertPopup();

      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('link.insert');
        editor.popups.setContainer('link.insert', editor.$tb || $(editor.opts.scrollableContainer));

        if ($btn.is(':visible')) {
          var left = $btn.offset().left + $btn.outerWidth() / 2;
          var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
          editor.popups.show('link.insert', left, top, $btn.outerHeight());
        }
        else {
          editor.position.forSelection($popup);
          editor.popups.show('link.insert');
        }
      }
    }

    function _initInsertPopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('link.insert', _refreshInsertPopup);
        editor.popups.onHide('link.insert', _hideInsertPopup);

        return true;
      }

      // Image buttons.
      var link_buttons = '';
      if (editor.opts.linkInsertButtons.length >= 1) {
        link_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.linkInsertButtons) + '</div>';
      }

      var checkmark = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="10" viewBox="0 0 32 32"><path d="M27 4l-15 15-7-7-5 5 12 12 20-20z" fill="#FFF"></path></svg>';

      // Image by url layer.
      var input_layer = '';
      var tab_idx = 0;
      input_layer = '<div class="fr-link-insert-layer fr-layer fr-active" id="fr-link-insert-layer-' + editor.id + '">';
      input_layer += '<div class="fr-input-line"><input name="href" type="text" class="fr-link-attr" placeholder="URL" tabIndex="' + (++tab_idx) + '"></div>';

      if (editor.opts.linkText) {
        input_layer += '<div class="fr-input-line"><input name="text" type="text" class="fr-link-attr" placeholder="' + editor.language.translate('Text') + '" tabIndex="' + (++tab_idx) + '"></div>';
      }

      // Add any additional fields.
      for (var attr in editor.opts.linkAttributes) {
        if (editor.opts.linkAttributes.hasOwnProperty(attr)) {
          var placeholder = editor.opts.linkAttributes[attr];
          input_layer += '<div class="fr-input-line"><input name="' + attr + '" type="text" class="fr-link-attr" placeholder="' + editor.language.translate(placeholder) + '" tabIndex="' + (++tab_idx) + '"></div>';
        }
      }

      if (!editor.opts.linkAlwaysBlank) {
        input_layer += '<div class="fr-checkbox-line"><span class="fr-checkbox"><input name="target" class="fr-link-attr" data-checked="_blank" type="checkbox" id="fr-link-target-' + editor.id + '" tabIndex="' + (++tab_idx) + '"><span>' + checkmark + '</span></span><label for="fr-link-target-' + editor.id + '">' + editor.language.translate('Open in new tab') + '</label></div>';
      }

      input_layer += '<div class="fr-action-buttons"><button class="fr-command fr-submit" data-cmd="linkInsert" href="#" tabIndex="' + (++tab_idx) + '" type="button">' + editor.language.translate('Insert') + '</button></div></div>'

      var template = {
        buttons: link_buttons,
        input_layer: input_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('link.insert', template);

      if (editor.$wp) {
        editor.events.$on(editor.$wp, 'scroll.link-insert', function () {
          var $current_image = editor.image ? editor.image.get() : null;
          if ($current_image && editor.popups.isVisible('link.insert')) {
            imageLink();
          }

          if (get && editor.popups.isVisible('link.insert')) {
            update();
          }
        });
      }

      return $popup;
    }

    function remove () {
      var link = get();
      var $current_image = editor.image ? editor.image.get() : null;

      if (editor.events.trigger('link.beforeRemove', [link]) === false) return false;

      if ($current_image && link) {
        $current_image.unwrap();
        editor.image.edit($current_image);
      }
      else if (link) {
        editor.selection.save();
        $(link).replaceWith($(link).html());
        editor.selection.restore();
        _hideEditPopup();
      }
    }

    function _init () {
      // Edit on keyup.
      editor.events.on('keyup', function (e) {
        if (e.which != $.FE.KEYCODE.ESC) {
          _edit(e);
        }
      });

      editor.events.on('window.mouseup', _edit);

      if (editor.helpers.isMobile()) {
        editor.events.$on(editor.$doc, 'selectionchange', _edit);
      }

      _initInsertPopup(true);

      // Init on link.
      if (editor.$el.get(0).tagName == 'A') {
        editor.$el.addClass('fr-view');
      }
    }

    function usePredefined (val) {
      var link = editor.opts.linkList[val];

      var $popup = editor.popups.get('link.insert');
      var text_inputs = $popup.find('input.fr-link-attr[type="text"]');
      var check_inputs = $popup.find('input.fr-link-attr[type="checkbox"]');

      var $input;
      var i;
      for (i = 0; i < text_inputs.length; i++) {
        $input = $(text_inputs[i]);
        if (link[$input.attr('name')]) {
          $input.val(link[$input.attr('name')]);
        }
        else if ($input.attr('name') != 'text') {
          $input.val('');
        }
      }

      for (i = 0; i < check_inputs.length; i++) {
        $input = $(check_inputs[i]);
        $input.prop('checked', $input.data('checked') == link[$input.attr('name')]);
      }
    }

    function insertCallback () {
      var $popup = editor.popups.get('link.insert');
      var text_inputs = $popup.find('input.fr-link-attr[type="text"]');
      var check_inputs = $popup.find('input.fr-link-attr[type="checkbox"]');

      var href = text_inputs.filter('[name="href"]').val();
      var text = text_inputs.filter('[name="text"]').val();

      var attrs = {};
      var $input;
      var i;
      for (i = 0; i < text_inputs.length; i++) {
        $input = $(text_inputs[i]);
        if (['href', 'text'].indexOf($input.attr('name')) < 0) {
          attrs[$input.attr('name')] = $input.val();
        }
      }

      for (i = 0; i < check_inputs.length; i++) {
        $input = $(check_inputs[i]);
        if ($input.is(':checked')) {
          attrs[$input.attr('name')] = $input.data('checked');
        }
        else {
          attrs[$input.attr('name')] = $input.data('unchecked');
        }
      }

      var t = $(editor.o_win).scrollTop();
      insert(href, text, attrs);
      $(editor.o_win).scrollTop(t);
    }

    function _split () {
      if (!editor.selection.isCollapsed()) {
        editor.selection.save();
        var markers = editor.$el.find('.fr-marker').addClass('fr-unprocessed').toArray();
        while (markers.length) {
          var $marker = $(markers.pop());
          $marker.removeClass('fr-unprocessed');

          var deep_parent = editor.node.deepestParent($marker.get(0));
          if (deep_parent) {
            var node = $marker.get(0);
            var close_str = '';
            var open_str = '';
            do {
              node = node.parentNode;
              if (!editor.node.isBlock(node)) {
                close_str = close_str + editor.node.closeTagString(node);
                open_str = editor.node.openTagString(node) + open_str;
              }
            } while (node != deep_parent);

            var marker_str = editor.node.openTagString($marker.get(0)) + $marker.html() +  editor.node.closeTagString($marker.get(0));

            $marker.replaceWith('<span id="fr-break"></span>');
            var h = $(deep_parent).html();
            h = h.replace(/<span id="fr-break"><\/span>/g, close_str + marker_str + open_str);

            $(deep_parent).html(h);
          }

          markers = editor.$el.find('.fr-marker.fr-unprocessed').toArray();
        }

        editor.selection.restore();
      }
    }

    /**
     * Insert link into the editor.
     */
    function insert (href, text, attrs) {
      if (typeof attrs == 'undefined') attrs = {};

      // Get image if we have one selected.
      var $current_image = editor.image ? editor.image.get() : null;

      if (!$current_image && editor.$el.get(0).tagName != 'A') {
        editor.selection.restore();
        editor.popups.hide('link.insert');
      }
      else if (editor.$el.get(0).tagName == 'A') {
        editor.$el.focus();
      }

      var original_href = href;

      // Convert email address.
      if (editor.opts.linkConvertEmailAddress) {
        var regex = /^[\w._]+@[a-z\u00a1-\uffff0-9_-]+?\.[a-z\u00a1-\uffff0-9]{2,}$/i;

        if (regex.test(href) && !/^mailto:.*/i.test(href)) {
          href = 'mailto:' + href;
        }
      }

      // Add autoprefix.
      if (editor.opts.linkAutoPrefix !== '' && !/^(mailto|tel|sms|notes|data):.*/i.test(href) && !/^data:image.*/i.test(href) && !/^(https?:|ftps?:|)\/\//i.test(href)) {
        // Do prefix only if starting character is not absolute.
        if (['/', '{', '[', '#', '('].indexOf((href || '')[0]) < 0) {
          href = editor.opts.linkAutoPrefix + href;
        }
      }

      // Sanitize the URL.
      href = editor.helpers.sanitizeURL(href);

      // Default attributs.
      if (editor.opts.linkAlwaysBlank) attrs.target = '_blank';
      if (editor.opts.linkAlwaysNoFollow) attrs.rel = 'nofollow';

      // Format text.
      text = text || '';

      if (href === editor.opts.linkAutoPrefix) {
        var $popup = editor.popups.get('link.insert');
        $popup.find('input[name="href"]').addClass('fr-error');
        editor.events.trigger('link.bad', [original_href]);
        return false;
      }

      // Check if we have selection only in one link.
      var link = get();
      var $link;

      if (link) {
        $link = $(link);

        // Clear attributes.
        var a_list = editor.node.rawAttributes(link);
        for (var attr in a_list) {
          if (a_list.hasOwnProperty(attr)) {
            if (attr != 'class' && attr != 'style') {
              $link.removeAttr(attr);
            }
          }
        }

        $link.attr('href', href);

        // Change text if it is different.
        if (text.length > 0 && $link.text() != text && !$current_image) {
          $link.text(text);
        }

        if (!$current_image) {
          $link
            .prepend($.FE.START_MARKER)
            .append($.FE.END_MARKER);
        }

        // Set attributes.
        $link.attr(attrs);

        if (!$current_image) {
          editor.selection.restore();
        }
      }
      else {
        // We don't have any image selected.
        if (!$current_image) {
          // Remove current links.
          editor.format.remove('a');

          // Nothing is selected.
          if (editor.selection.isCollapsed()) {
            text = (text.length === 0 ? original_href : text);
            editor.html.insert('<a href="' + href + '">' + $.FE.START_MARKER + text + $.FE.END_MARKER + '</a>');
            editor.selection.restore();
          }
          else {
            if (text.length > 0 && text != editor.selection.text().replace(/\n/g, '')) {
              editor.selection.remove();
              editor.html.insert('<a href="' + href + '">' + $.FE.START_MARKER + text + $.FE.END_MARKER + '</a>');
              editor.selection.restore();
            }
            else {
              _split();

              // Add link.
              editor.format.apply('a', { href: href });
            }
          }
        }
        else {
          // Just wrap current image with a link.
          $current_image.wrap('<a href="' + href + '"></a>');
        }

        // Set attributes.
        var links = allSelected();
        for (var i = 0; i < links.length; i++) {
          $link = $(links[i]);
          $link.attr(attrs);
          $link.removeAttr('_moz_dirty');
        }

        // Show link edit if only one link.
        if (links.length == 1 && editor.$wp && !$current_image) {
          $(links[0])
            .prepend($.FE.START_MARKER)
            .append($.FE.END_MARKER);

          editor.selection.restore();
        }
      }

      // Hide popup and try to edit.
      if (!$current_image) {
        _edit();
      }
      else {
        var $pop = editor.popups.get('link.insert');
        $pop.find('input:focus').blur();
        editor.image.edit($current_image);
      }
    }

    function update () {
      _hideEditPopup();

      var link = get();
      if (link) {
        var $popup = editor.popups.get('link.insert');
        if (!$popup) $popup = _initInsertPopup();

        if (!editor.popups.isVisible('link.insert')) {
          editor.popups.refresh('link.insert');
          editor.selection.save();

          if (editor.helpers.isMobile()) {
            editor.events.disableBlur();
            editor.$el.blur();
            editor.events.enableBlur();
          }
        }

        editor.popups.setContainer('link.insert', $(editor.opts.scrollableContainer));
        var $ref = (editor.image ? editor.image.get() : null) || $(link);
        var left = $ref.offset().left + $ref.outerWidth() / 2;
        var top = $ref.offset().top + $ref.outerHeight();

        editor.popups.show('link.insert', left, top, $ref.outerHeight());
      }
    }

    function back () {
      var $current_image = editor.image ? editor.image.get() : null;
      if (!$current_image) {
        editor.events.disableBlur();
        editor.selection.restore();
        editor.events.enableBlur();

        var link = get();

        if (link && editor.$wp) {
          editor.selection.restore();
          _hideEditPopup();
          _edit();
        }
        else if (editor.$el.get(0).tagName == 'A') {
          editor.$el.focus();
          _edit();
        }
        else {
          editor.popups.hide('link.insert');
          editor.toolbar.showInline();
        }
      }
      else {
        editor.image.back();
      }
    }

    function imageLink () {
      var $current_image = editor.image ? editor.image.get() : null;

      if ($current_image) {
        var $popup = editor.popups.get('link.insert');
        if (!$popup) $popup = _initInsertPopup();

        _refreshInsertPopup(true);
        editor.popups.setContainer('link.insert', $(editor.opts.scrollableContainer));
        var left = $current_image.offset().left + $current_image.outerWidth() / 2;
        var top = $current_image.offset().top + $current_image.outerHeight();

        editor.popups.show('link.insert', left, top, $current_image.outerHeight());
      }
    }

    /**
     * Apply specific style.
     */
    function applyStyle (val, linkStyles, multipleStyles) {
      if (typeof multipleStyles == 'undefined') multipleStyles = editor.opts.linkMultipleStyles;
      if (typeof linkStyles == 'undefined') linkStyles = editor.opts.linkStyles;

      var link = get();
      if (!link) return false;

      // Remove multiple styles.
      if (!multipleStyles) {
        var styles = Object.keys(linkStyles);
        styles.splice(styles.indexOf(val), 1);
        $(link).removeClass(styles.join(' '));
      }

      $(link).toggleClass(val);

      _edit();
    }

    return {
      _init: _init,
      remove: remove,
      showInsertPopup: _showInsertPopup,
      usePredefined: usePredefined,
      insertCallback: insertCallback,
      insert: insert,
      update: update,
      get: get,
      allSelected: allSelected,
      back: back,
      imageLink: imageLink,
      applyStyle: applyStyle
    }
  }

  // Register the link command.
  $.FE.DefineIcon('insertLink', { NAME: 'link' });
  $.FE.RegisterShortcut($.FE.KEYCODE.K, 'insertLink', null, 'K');
  $.FE.RegisterCommand('insertLink', {
    title: 'Insert Link',
    undo: false,
    focus: true,
    refreshOnCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('link.insert')) {
        this.link.showInsertPopup();
      }
      else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('link.insert');
      }
    },
    plugin: 'link'
  })

  $.FE.DefineIcon('linkOpen', { NAME: 'external-link' });
  $.FE.RegisterCommand('linkOpen', {
    title: 'Open Link',
    undo: false,
    refresh: function ($btn) {
      var link = this.link.get();
      if (link) {
        $btn.removeClass('fr-hidden');
      }
      else {
        $btn.addClass('fr-hidden');
      }
    },
    callback: function () {
      var link = this.link.get();
      if (link) {
        this.o_win.open(link.href);
      }
    }
  })

  $.FE.DefineIcon('linkEdit', { NAME: 'edit' });
  $.FE.RegisterCommand('linkEdit', {
    title: 'Edit Link',
    undo: false,
    refreshAfterCallback: false,
    callback: function () {
      this.link.update();
    },
    refresh: function ($btn) {
      var link = this.link.get();
      if (link) {
        $btn.removeClass('fr-hidden');
      }
      else {
        $btn.addClass('fr-hidden');
      }
    }
  })

  $.FE.DefineIcon('linkRemove', { NAME: 'unlink' });
  $.FE.RegisterCommand('linkRemove', {
    title: 'Unlink',
    callback: function () {
      this.link.remove();
    },
    refresh: function ($btn) {
      var link = this.link.get();
      if (link) {
        $btn.removeClass('fr-hidden');
      }
      else {
        $btn.addClass('fr-hidden');
      }
    }
  })

  $.FE.DefineIcon('linkBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('linkBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    refreshAfterCallback: false,
    callback: function () {
      this.link.back();
    },
    refresh: function ($btn) {
      var link = this.link.get();
      var $current_image = this.image ? this.image.get() : null;
      if (!$current_image && !link && !this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  $.FE.DefineIcon('linkList', { NAME: 'search' });
  $.FE.RegisterCommand('linkList', {
    title: 'Choose Link',
    type: 'dropdown',
    focus: false,
    undo: false,
    refreshAfterCallback: false,
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.linkList;
      for (var i = 0; i < options.length; i++) {
        c += '<li><a class="fr-command" data-cmd="linkList" data-param1="' + i + '">' + (options[i].displayText || options[i].text) + '</a></li>';
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.link.usePredefined(val);
    }
  })

  $.FE.RegisterCommand('linkInsert', {
    focus: false,
    refreshAfterCallback: false,
    callback: function () {
      this.link.insertCallback();
    },
    refresh: function ($btn) {
      var link = this.link.get();
      if (link) {
        $btn.text(this.language.translate('Update'));
      }
      else {
        $btn.text(this.language.translate('Insert'));
      }
    }
  })

  // Image link.
  $.FE.DefineIcon('imageLink', { NAME: 'link' })
  $.FE.RegisterCommand('imageLink', {
    title: 'Insert Link',
    undo: false,
    focus: false,
    callback: function () {
      this.link.imageLink();
    },
    refresh: function ($btn) {
      var link = this.link.get();
      var $prev;

      if (link) {
        $prev = $btn.prev();
        if ($prev.hasClass('fr-separator')) {
          $prev.removeClass('fr-hidden');
        }

        $btn.addClass('fr-hidden');
      }
      else {
        $prev = $btn.prev();
        if ($prev.hasClass('fr-separator')) {
          $prev.addClass('fr-hidden');
        }

        $btn.removeClass('fr-hidden');
      }
    }
  })

  // Link styles.
  $.FE.DefineIcon('linkStyle', { NAME: 'magic' })
  $.FE.RegisterCommand('linkStyle', {
    title: 'Style',
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.linkStyles;
      for (var cls in options) {
        if (options.hasOwnProperty(cls)) {
          c += '<li><a class="fr-command" data-cmd="linkStyle" data-param1="' + cls + '">' + this.language.translate(options[cls]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.link.applyStyle(val);
    },
    refreshOnShow: function ($btn, $dropdown) {
      var link = this.link.get();

      if (link) {
        var $link = $(link);
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $link.hasClass(cls));
        })
      }
    }
  })


  'use strict';

  $.FE.PLUGINS.lists = function (editor) {
    function _openFlag(tag_name) {
      return '<span class="fr-open-' + tag_name.toLowerCase() + '"></span>';
    }

    function _closeFlag(tag_name) {
      return '<span class="fr-close-' + tag_name.toLowerCase() + '"></span>';
    }

    /**
     * Replace list type.
     */
    function _replace(blocks, tag_name) {
      var lists = [];
      for (var i = 0; i < blocks.length; i++) {
        var parent_node = blocks[i].parentNode;
        if (blocks[i].tagName == 'LI' && parent_node.tagName != tag_name && lists.indexOf(parent_node) < 0) {
          lists.push(parent_node);
        }
      }

      for (i = lists.length - 1; i >= 0; i--) {
        var $l = $(lists[i]);
        $l.replaceWith('<' + tag_name.toLowerCase() + '>' + $l.html() + '</' + tag_name.toLowerCase() + '>');
      }
    }

    /**
     * Format blocks.
     */
    function _format(blocks, tag_name) {
      _replace(blocks, tag_name);

      // Format those blocks that are not LI.
      var default_tag = editor.html.defaultTag();
      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].tagName != 'LI') {
          // Default tag.
          if (default_tag && blocks[i].tagName.toLowerCase() == default_tag) {
            $(blocks[i]).replaceWith('<' + tag_name + '><li' + editor.node.attributes(blocks[i]) + '>' + $(blocks[i]).html() + '</li></' + tag_name + '>');
          }
          else {
            $(blocks[i]).wrap('<' + tag_name + '><li></li></' + tag_name + '>');
          }
        }
      }

      editor.clean.lists();
    }

    /**
     * Unformat.
     */
    function _unformat(blocks) {
      var i;
      var j;

      // If there are LI that have parents selected, then remove them.
      for (i = blocks.length - 1; i >= 0; i--) {
        for (j = i - 1; j >= 0; j--) {
          if ($(blocks[j]).find(blocks[i]).length || blocks[j] == blocks[i]) {
            blocks.splice(i, 1);
            break;
          }
        }
      }

      // Unwrap remaining LI.
      var lists = [];
      for (i = 0; i < blocks.length; i++) {
        var $li = $(blocks[i]);
        var parent_node = blocks[i].parentNode;

        $li.before(_closeFlag(parent_node.tagName));

        // Nested case.
        if (parent_node.parentNode.tagName == 'LI') {
          $li.before(_closeFlag('LI'));
          $li.after(_openFlag('LI'));
        }
        else {
          // Append BR if the node is not empty.
          if (!editor.node.isEmpty($li.get(0), true) && $li.find(editor.html.blockTagsQuery()).length === 0) {
            $li.append('<br>');
          }
          $li.append(_openFlag('LI'));
          $li.prepend(_closeFlag('LI'));
        }

        $li.after(_openFlag(parent_node.tagName));

        // Nested case. We should look for an upper parent.
        if (parent_node.parentNode.tagName == 'LI') {
          parent_node = parent_node.parentNode.parentNode;
        }

        if (lists.indexOf(parent_node) < 0) {
          lists.push(parent_node);
        }
      }

      // Replace the open and close tags.
      for (i = 0; i < lists.length; i++) {
        var $l = $(lists[i]);
        var html = $l.html();
        html = html.replace(/<span class="fr-close-([a-z]*)"><\/span>/g, '</$1>');
        html = html.replace(/<span class="fr-open-([a-z]*)"><\/span>/g, '<$1>');
        $l.replaceWith(editor.node.openTagString($l.get(0)) + html + editor.node.closeTagString($l.get(0)));
      }

      // Clean empty lists.
      editor.$el.find('li:empty').remove();
      editor.$el.find('ul:empty, ol:empty').remove();

      editor.clean.lists();
      editor.html.wrap();
    }

    /**
     * Check if should unformat lists.
     */
    function _shouldUnformat(blocks, tag_name) {
      var do_unformat = true;
      for (var i = 0; i < blocks.length; i++) {
        // Something else than LI is selected.
        if (blocks[i].tagName != 'LI') {
          return false;
        }

        // There is a different kind of list selected. Replace is the appropiate action.
        if (blocks[i].parentNode.tagName != tag_name) {
          do_unformat = false;
        }
      }

      return do_unformat;
    }

    /**
     * Call the list actions.
     */
    function format(tag_name) {
      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true, true, true);
      editor.selection.restore();

      var blocks = editor.selection.blocks();

      // Normalize nodes by keeping the LI.
      // <li><h1>foo<h1></li> will return h1.
      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].tagName != 'LI' && blocks[i].parentNode.tagName == 'LI') {
          blocks[i] = blocks[i].parentNode;
        }
      }

      // Save selection so that we can play at wish.
      editor.selection.save();

      // Decide if to format or unformat list.
      if (_shouldUnformat(blocks, tag_name)) {
        _unformat(blocks);
      }
      else {
        _format(blocks, tag_name);
      }

      // Unwrap.
      editor.html.unwrap();

      // Restore the selection.
      editor.selection.restore();
    }

    /**
     * Refresh list buttons.
     */
    function refresh($btn, tag_name) {
      var $el = $(editor.selection.element());
      if ($el.get(0) != editor.$el.get(0)) {
        var li = $el.get(0);
        if (li.tagName != 'LI') {
          li = $el.parents('li').get(0);
        }

        if (li && li.parentNode.tagName == tag_name && editor.$el.get(0).contains(li.parentNode)) {
          $btn.addClass('fr-active');
        }
      }
    }

    /**
     * Indent selected list items.
     */
    function _indent (blocks) {
      editor.selection.save();
      for (var i = 0; i < blocks.length; i++) {
        // There should be a previous li.
        var prev_li = blocks[i].previousSibling;
        if (prev_li) {
          var nl = $(blocks[i]).find('> ul, > ol').get(0);

          // Current LI has a nested list.
          if (nl) {
            var $li = $('<li>').prependTo($(nl));
            var node = editor.node.contents(blocks[i])[0];
            while (node && !editor.node.isList(node)) {
              var tmp = node.nextSibling;
              $li.append(node);
              node = tmp;
            }

            $(prev_li).append($(nl));
            $(blocks[i]).remove();
          }
          else {
            var prev_nl = $(prev_li).find('> ul, > ol').get(0);
            if (prev_nl) {
              $(prev_nl).append($(blocks[i]));
            }
            else {
              var $new_nl = $('<' + blocks[i].parentNode.tagName + '>');
              $(prev_li).append($new_nl);
              $new_nl.append($(blocks[i]));
            }
          }
        }
      }

      editor.clean.lists();
      editor.selection.restore();
    }

    /**
     * Outdent selected list items.
     */
    function _outdent (blocks) {
      editor.selection.save();
      _unformat(blocks);
      editor.selection.restore();
    }

    /**
     * Hook into the indent/outdent events.
     */
    function _afterCommand (cmd) {
      if (cmd == 'indent' || cmd == 'outdent') {
        var do_indent = false;
        var blocks = editor.selection.blocks();
        var blks = [];
        for (var i = 0; i < blocks.length; i++) {
          if (blocks[i].tagName == 'LI') {
            do_indent = true;
            blks.push(blocks[i]);
          }
          else if (blocks[i].parentNode.tagName == 'LI') {
            do_indent = true;
            blks.push(blocks[i].parentNode);
          }
        }

        if (do_indent) {
          if (cmd == 'indent') _indent(blks);
          else _outdent(blks);
        }
      }
    }

    /**
     * Init.
     */
    function _init () {
      editor.events.on('commands.after', _afterCommand);

      // TAB key in lists.
      editor.events.on('keydown', function (e) {
        if (e.which == $.FE.KEYCODE.TAB) {
          var do_indent;
          var blocks = editor.selection.blocks();
          var blks = [];
          for (var i = 0; i < blocks.length; i++) {
            if (blocks[i].tagName == 'LI') {
              do_indent = true;
              blks.push(blocks[i]);
            }
            else if (blocks[i].parentNode.tagName == 'LI') {
              do_indent = true;
              blks.push(blocks[i].parentNode);
            }
          }

          if (do_indent) {
            e.preventDefault();
            e.stopPropagation();

            if (!e.shiftKey) _indent(blks);
            else _outdent(blks);

            return false;
          }
        }
      }, true);
    }

    return {
      _init: _init,
      format: format,
      refresh: refresh
    }
  }

  // Register the font size command.
  $.FE.RegisterCommand('formatUL', {
    title: 'Unordered List',
    refresh: function ($btn) {
      this.lists.refresh($btn, 'UL');
    },
    callback: function () {
      this.lists.format('UL');
    },
    plugin: 'lists'
  })

  // Register the font size command.
  $.FE.RegisterCommand('formatOL', {
    title: 'Ordered List',
    refresh: function ($btn) {
      this.lists.refresh($btn, 'OL');
    },
    callback: function () {
      this.lists.format('OL');
    },
    plugin: 'lists'
  })

  // Add the list icons.
  $.FE.DefineIcon('formatUL', {
    NAME: 'list-ul'
  });

  $.FE.DefineIcon('formatOL', {
    NAME: 'list-ol'
  });


  'use strict';

  $.extend($.FE.DEFAULTS, {
    paragraphFormat: {
      N: 'Normal',
      H1: 'Heading 1',
      H2: 'Heading 2',
      H3: 'Heading 3',
      H4: 'Heading 4',
      PRE: 'Code'
    },
    paragraphFormatSelection: false
  })

  $.FE.PLUGINS.paragraphFormat = function (editor) {

    /**
     * Style content inside LI when LI is selected.
     * This case happens only when the LI contains a nested list or when it has no block tag inside.
     */
    function _styleLiWithoutBlocks($li, val) {
      var defaultTag = editor.html.defaultTag();

      // If val is null or default tag already do nothing.
      if (val && val.toLowerCase() != defaultTag) {
        // Deal with nested lists.
        if ($li.find('ul, ol').length > 0) {
          var $el = $('<' + val + '>');
          $li.prepend($el);
          var node = editor.node.contents($li.get(0))[0];
          while (node && ['UL', 'OL'].indexOf(node.tagName) < 0) {
            var next_node = node.nextSibling;
            $el.append(node);
            node = next_node;
          }
        }

        // Wrap list content.
        else {
          $li.html('<' + val + '>' + $li.html() + '</' + val + '>');
        }
      }
    }

    /**
     * Style content inside LI.
     */
    function _styleLiWithBlocks($blk, val) {
      var defaultTag = editor.html.defaultTag();

      // Prepare a temp div.
      if (!val) val = 'div class="fr-temp-div" data-empty="true"';

      // In list we don't have P so just unwrap content.
      if (val.toLowerCase() == defaultTag) {
        $blk.replaceWith($blk.html());
      }

      // Replace the current block with the new one.
      else {
        $blk.replaceWith($('<' + val + '>').html($blk.html()));
      }
    }

    /**
     * Style content inside TD.
     */
    function _styleTdWithBlocks($blk, val) {
      var defaultTag = editor.html.defaultTag();

      // Prepare a temp div.
      if (!val) val = 'div class="fr-temp-div"' + (editor.node.isEmpty($blk.get(0), true) ? ' data-empty="true"' : '');

      // Return to the regular case. We don't use P inside TD/TH.
      if (val.toLowerCase() == defaultTag) {
        // If node is not empty, then add a BR.
        if (!editor.node.isEmpty($blk.get(0), true)) {
          $blk.append('<br/>');
        }

        $blk.replaceWith($blk.html());
      }

      // Replace with the new tag.
      else {
        $blk.replaceWith($('<' + val  + '>').html($blk.html()));
      }
    }

    /**
     * Basic style.
     */
    function _style($blk, val) {
      if (!val) val = 'div class="fr-temp-div"' + (editor.node.isEmpty($blk.get(0), true) ? ' data-empty="true"' : '');
      $blk.replaceWith($('<' + val  + ' ' + editor.node.attributes($blk.get(0)) + '>').html($blk.html()));
    }

    /**
     * Apply style.
     */
    function apply (val) {
      // Normal.
      if (val == 'N') val = editor.html.defaultTag();

      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true, true, true);
      editor.selection.restore();

      // Get blocks.
      var blocks = editor.selection.blocks();

      // Save selection to restore it later.
      editor.selection.save();

      editor.$el.find('pre').attr('skip', true);

      // Go through each block and apply style to it.
      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].tagName != val && !editor.node.isList(blocks[i])) {
          var $blk = $(blocks[i]);

          // Style the content inside LI when there is selection right in LI.
          if (blocks[i].tagName == 'LI') {
            _styleLiWithoutBlocks($blk, val);
          }

          // Style the content inside LI when we have other tag in LI.
          else if (blocks[i].parentNode.tagName == 'LI' && blocks[i]) {
            _styleLiWithBlocks($blk, val);
          }

          // Style the content inside TD/TH.
          else if (['TD', 'TH'].indexOf(blocks[i].parentNode.tagName) >= 0) {
            _styleTdWithBlocks($blk, val);
          }

          // Regular case.
          else {
            _style($blk, val);
          }
        }
      }

      // Join PRE together.
      editor.$el.find('pre:not([skip="true"]) + pre:not([skip="true"])').each(function () {
        $(this).prev().append('<br>' + $(this).html());
        $(this).remove();
      });
      editor.$el.find('pre').removeAttr('skip');

      // Unwrap temp divs.
      editor.html.unwrap();

      // Restore selection.
      editor.selection.restore();
    }

    function refreshOnShow($btn, $dropdown) {
      var blocks = editor.selection.blocks();

      if (blocks.length) {
        var blk = blocks[0];
        var tag = 'N';
        var default_tag = editor.html.defaultTag();
        if (blk.tagName.toLowerCase() != default_tag && blk != editor.$el.get(0)) {
          tag = blk.tagName;
        }

        $dropdown.find('.fr-command[data-param1="' + tag + '"]').addClass('fr-active');
      }
      else {
        $dropdown.find('.fr-command[data-param1="N"]').addClass('fr-active');
      }
    }

    function refresh ($btn) {
      if (editor.opts.paragraphFormatSelection) {
        var blocks = editor.selection.blocks();

        if (blocks.length) {
          var blk = blocks[0];
          var tag = 'N';
          var default_tag = editor.html.defaultTag();
          if (blk.tagName.toLowerCase() != default_tag && blk != editor.$el.get(0)) {
            tag = blk.tagName;
          }

          if (['LI', 'TD', 'TH'].indexOf(tag) >= 0) {
            tag = 'N';
          }

          $btn.find('> span').text(editor.opts.paragraphFormat[tag]);
        }
        else {
          $btn.find('> span').text(edior.opts.paragraphFormat.N);
        }
      }
    }

    return {
      apply: apply,
      refreshOnShow: refreshOnShow,
      refresh: refresh
    }
  }

  // Register the font size command.
  $.FE.RegisterShortcut($.FE.KEYCODE.ZERO, 'paragraphFormat', 'N', '0', false, true);
  $.FE.RegisterShortcut($.FE.KEYCODE.ONE, 'paragraphFormat', 'H1', '1', false, true);
  $.FE.RegisterShortcut($.FE.KEYCODE.TWO, 'paragraphFormat', 'H2', '2', false, true);
  $.FE.RegisterShortcut($.FE.KEYCODE.THREE, 'paragraphFormat', 'H3', '3', false, true);
  $.FE.RegisterShortcut($.FE.KEYCODE.FOUR, 'paragraphFormat', 'H4', '4', false, true);
  $.FE.RegisterCommand('paragraphFormat', {
    type: 'dropdown',
    displaySelection: function (editor) {
      return editor.opts.paragraphFormatSelection;
    },
    defaultSelection: 'Normal',
    displaySelectionWidth: 100,
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.paragraphFormat;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          var shortcut = this.shortcuts.get('paragraphFormat.' + val);
          if (shortcut) {
            shortcut = '<span class="fr-shortcut">' + shortcut + '</span>';
          }
          else {
            shortcut = '';
          }

          c += '<li><' + (val == 'N' ? this.html.defaultTag() || 'DIV' : val) + ' style="padding: 0 !important; margin: 0 !important;"><a class="fr-command" data-cmd="paragraphFormat" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></' + (val == 'N' ? this.html.defaultTag() || 'DIV' : val) + '></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    title: 'Paragraph Format',
    callback: function (cmd, val) {
      this.paragraphFormat.apply(val);
    },
    refresh: function ($btn) {
      this.paragraphFormat.refresh($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.paragraphFormat.refreshOnShow($btn, $dropdown);
    },
    plugin: 'paragraphFormat'
  })

  // Add the font size icon.
  $.FE.DefineIcon('paragraphFormat', {
    NAME: 'paragraph'
  });


  'use strict';

  $.extend($.FE.DEFAULTS, {
    paragraphStyles: {
      'fr-text-gray': 'Gray',
      'fr-text-bordered': 'Bordered',
      'fr-text-spaced': 'Spaced',
      'fr-text-uppercase': 'Uppercase'
    },
    paragraphMultipleStyles: true
  });

  $.FE.PLUGINS.paragraphStyle = function (editor) {
    /**
     * Apply style.
     */
    function apply (val, paragraphStyles, paragraphMultipleStyles) {
      if (typeof paragraphStyles == 'undefined') paragraphStyles = editor.opts.paragraphStyles;
      if (typeof paragraphMultipleStyles == 'undefined') paragraphMultipleStyles = editor.opts.paragraphMultipleStyles;

      var styles = '';
      // Remove multiple styles.
      if (!paragraphMultipleStyles) {
        styles = Object.keys(paragraphStyles);
        styles.splice(styles.indexOf(val), 1);
        styles = styles.join(' ');
      }

      editor.selection.save();
      editor.html.wrap(true, true, true, true);
      editor.selection.restore();

      var blocks = editor.selection.blocks();

      // Save selection to restore it later.
      editor.selection.save();

      var hasClass = $(blocks[0]).hasClass(val);
      for (var i = 0; i < blocks.length; i++) {
        $(blocks[i]).removeClass(styles).toggleClass(val, !hasClass);

        if ($(blocks[i]).hasClass('fr-temp-div')) $(blocks[i]).removeClass('fr-temp-div');
        if ($(blocks[i]).attr('class') === '') $(blocks[i]).removeAttr('class');
      }

      // Unwrap temp divs.
      editor.html.unwrap();

      // Restore selection.
      editor.selection.restore();
    }

    function refreshOnShow($btn, $dropdown) {
      var blocks = editor.selection.blocks();

      if (blocks.length) {
        var $blk = $(blocks[0]);
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $blk.hasClass(cls));
        })
      }
    }

    function _init () {
    }

    return {
      _init: _init,
      apply: apply,
      refreshOnShow: refreshOnShow
    }
  }

  // Register the font size command.
  $.FE.RegisterCommand('paragraphStyle', {
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.paragraphStyles;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command ' + val + '" data-cmd="paragraphStyle" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    title: 'Paragraph Style',
    callback: function (cmd, val) {
      this.paragraphStyle.apply(val);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.paragraphStyle.refreshOnShow($btn, $dropdown);
    },
    plugin: 'paragraphStyle'
  })

  // Add the font size icon.
  $.FE.DefineIcon('paragraphStyle', {
    NAME: 'magic'
  });


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    quickInsertButtons: ['image', 'table', 'ul', 'ol', 'hr'],
    quickInsertTags: ['p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'blockquote']
  });

  $.FE.QUICK_INSERT_BUTTONS = {
    image: {
      icon: 'insertImage',
      callback: function () {
        var editor = this;

        if (!editor.shared.$qi_image_input) {
          editor.shared.$qi_image_input = $('<input accept="image/*" name="quickInsertImage' + this.id + '" style="display: none;" type="file">');
          $('body').append(editor.shared.$qi_image_input);

          editor.events.$on(editor.shared.$qi_image_input, 'change', function () {
            var inst = $(this).data('inst');
            if (this.files) {
              inst.quickInsert.hide();

              inst.image.upload(this.files);
            }

            // Chrome fix.
            $(this).val('');
          }, true);
        }

        editor.$qi_image_input = editor.shared.$qi_image_input;

        if (editor.helpers.isMobile()) editor.selection.save();
        editor.$qi_image_input.data('inst', editor).trigger('click');
      },
      requiredPlugin: 'image',
      title: 'Insert Image'
    },
    table: {
      icon: 'insertTable',
      callback: function () {
        this.quickInsert.hide();
        this.table.insert(2, 2);
        this.undo.saveStep();
      },
      requiredPlugin: 'table',
      title: 'Insert Table'
    },
    ol: {
      icon: 'formatOL',
      callback: function () {
        this.quickInsert.hide();
        this.lists.format('OL');
        this.undo.saveStep();
      },
      requiredPlugin: 'lists',
      title: 'Ordered List'
    },
    ul: {
      icon: 'formatUL',
      callback: function () {
        this.quickInsert.hide();
        this.lists.format('UL');
        this.undo.saveStep();
      },
      requiredPlugin: 'lists',
      title: 'Unordered List'
    },
    hr: {
      icon: 'insertHR',
      callback: function () {
        this.quickInsert.hide();
        this.commands.insertHR();
        this.undo.saveStep();
      },
      title: 'Insert Horizontal Line'
    }
  }

  $.FE.RegisterQuickInsertCommand = function (name, data) {
    $.FE.QUICK_INSERT_BUTTONS[name] = data;
  }

  $.FE.PLUGINS.quickInsert = function (editor) {
    var $quick_insert;

    /*
     * Show quick insert.
     * Compute top, left, width and show the quick insert.
     */
    function _show ($tag) {
      if (!$quick_insert) _initquickInsert();

      editor.$box.append($quick_insert);

      // Quick insert's possition.
      var qiTop;
      var qiLeft;

      qiTop = $tag.offset().top - editor.$box.offset().top - ($quick_insert.outerHeight() - $tag.outerHeight()) / 2;
      qiLeft = 0 - $quick_insert.outerWidth();

      if (editor.opts.iframe) {
        qiTop += editor.$iframe.offset().top - $(editor.o_win).scrollTop();
      }

      // Set quick insert's top and left.
      $quick_insert.css('top', qiTop);
      $quick_insert.css('left', qiLeft);

      $quick_insert.data('tag', $tag);

      // Show the quick insert.
      $quick_insert.addClass('fr-visible');
    }

    /*
     * Check the tag where the cursor is.
     */
    function _checkTag () {
      var tag = editor.selection.element();

      // Get block tag.
      if (!editor.node.isBlock(tag)) {
        tag = editor.node.blockParent(tag);
      }

      // Tag must be empty and direct child of element in order to show the quick insert.
      if (tag && editor.node.isEmpty(tag) && editor.node.isElement(tag.parentNode)) {
        // If tag is block and selection is collapsed.
        if (tag && editor.selection.isCollapsed()) {
          _show($(tag));
        }
      }

      // Quick insert should not be visible.
      else {
        hide();
      }
    }

    /*
     * Hide quick insert.
     */
    function hide () {
      if ($quick_insert) {
        editor.html.checkIfEmpty();

        // Hide the quick insert helper if visible.
        if ($quick_insert.hasClass('fr-on')) {
          _hideHelper();
        }

        // Hide the quick insert.
        $quick_insert.removeClass('fr-visible fr-on');
        $quick_insert.css('left', -9999).css('top', -9999);
      }
    }

    /*
     * Show the quick insert helper.
     */
    var $helper;
    function _showQIHelper (e) {
      e.preventDefault();

      // Hide helper.
      if ($quick_insert.hasClass('fr-on')) {
        _hideHelper();
      }

      else {
        if (!editor.shared.$qi_helper) {
          var btns = editor.opts.quickInsertButtons;
          var btns_html = '<div class="fr-qi-helper">';
          var idx = 0;

          for (var i = 0; i < btns.length; i++) {
            var info = $.FE.QUICK_INSERT_BUTTONS[btns[i]];
            if (info) {
              if (!info.requiredPlugin || ($.FE.PLUGINS[info.requiredPlugin] && editor.opts.pluginsEnabled.indexOf(info.requiredPlugin) >= 0)) {
                btns_html += '<a class="fr-btn fr-floating-btn" role="button" title="' + editor.language.translate(info.title) + '" tabindex="-1" data-cmd="' + btns[i] + '" style="transition-delay: ' + (0.025 * (idx++)) + 's;">' + editor.icon.create(info.icon) + '</a>';
              }
            }
          }

          btns_html += '</div>';
          editor.shared.$qi_helper = $(btns_html);

          // Quick insert helper tooltip.
          editor.tooltip.bind(editor.shared.$qi_helper, '.fr-qi-helper > a.fr-btn');
        }

        $helper = editor.shared.$qi_helper;
        $helper.appendTo(editor.$box);

        // Show the quick insert helper.
        setTimeout(function () {
          $helper.css('top', parseFloat($quick_insert.css('top')));
          $helper.css('left', parseFloat($quick_insert.css('left')) + $quick_insert.outerWidth());
          $helper.find('a').addClass('fr-size-1')
          $quick_insert.addClass('fr-on');
        }, 10);
      }
    }

    /*
     * Hides the quick insert helper and places the cursor.
     */
    function _hideHelper () {
      var $helper = editor.$box.find('.fr-qi-helper');

      if ($helper.length) {
        $helper.find('a').removeClass('fr-size-1');
        $helper.css('left', -9999);
        $quick_insert.removeClass('fr-on');
      }
    }

    /*
     * Initialize the quick insert.
     */
    function _initquickInsert () {
      if (!editor.shared.$quick_insert) {
        // Append quick insert HTML to editor wrapper.
        editor.shared.$quick_insert = $('<div class="fr-quick-insert"><a class="fr-floating-btn" role="button" tabindex="-1" title="' + editor.language.translate('Quick Insert') + '"><svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M22,16.75 L16.75,16.75 L16.75,22 L15.25,22.000 L15.25,16.75 L10,16.75 L10,15.25 L15.25,15.25 L15.25,10 L16.75,10 L16.75,15.25 L22,15.25 L22,16.75 Z"/></svg></a></div>');
      }
      $quick_insert = editor.shared.$quick_insert;

      // Quick Insert tooltip.
      editor.tooltip.bind(editor.$box, '.fr-quick-insert > a.fr-floating-btn');

      // Editor destroy.
      editor.events.on('destroy', function () {
        $quick_insert.removeClass('fr-on').appendTo($('body')).css('left', -9999).css('top', -9999);

        if ($helper) {
          _hideHelper();
          $helper.appendTo($('body'));
        }
      }, true);

      editor.events.on('shared.destroy', function () {
        $quick_insert.html('').removeData().remove();
        $quick_insert = null;

        if ($helper) {
          $helper.html('').removeData().remove();
          $helper = null;
        }
      }, true);

      // Hide before a command is executed.
      editor.events.on('commands.before', hide);

      // Check if the quick insert should be shown after a command has been executed.
      editor.events.on('commands.after', function () {
        if (!editor.popups.areVisible()) {
          _checkTag();
        }
      });

      // User clicks on the quick insert.
      editor.events.bindClick(editor.$box, '.fr-quick-insert > a', _showQIHelper);

      // User clicks on a button from the quick insert helper.
      editor.events.bindClick(editor.$box, '.fr-qi-helper > a.fr-btn', function (e) {
        var cmd = $(e.currentTarget).data('cmd');

        $.FE.QUICK_INSERT_BUTTONS[cmd].callback.apply(editor, [e.currentTarget]);
      });
    }

    /*
     * Tear up.
     */
    function _init () {
      if (!editor.$wp) return false;

      if (editor.opts.iframe) {
        editor.$el.parent('html').find('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">');
      }

      // Hide the quick insert if user click on an image.
      editor.popups.onShow('image.edit', hide);

      // Check tag where cursor is to see if the quick insert needs to be shown.
      editor.events.on('mouseup', _checkTag);

      if (editor.helpers.isMobile()) {
        editor.events.$on($(editor.o_doc), 'selectionchange', _checkTag);
      }

      // Hide the quick insert when editor loses focus.
      editor.events.on('blur', hide);

      // Check if the quick insert should be shown after a key was pressed.
      editor.events.on('keyup', _checkTag);
    }

    return {
      _init: _init,
      hide: hide
    }
  };


  'use strict';

  $.FE.PLUGINS.quote = function (editor) {
    function _deepestParent(node) {
      while (node.parentNode && node.parentNode != editor.$el.get(0)) {
        node = node.parentNode;
      }

      return node;
    }

    function _increase () {
      // Get blocks.
      var blocks = editor.selection.blocks();
      var i;

      // Normalize blocks.
      for (i = 0; i < blocks.length; i++) {
        blocks[i] = _deepestParent(blocks[i]);
      }

      // Save selection to restore it later.
      editor.selection.save();

      var $quote = $('<blockquote>');
      $quote.insertBefore(blocks[0]);
      for (i = 0; i < blocks.length; i++) {
        $quote.append(blocks[i]);
      }

      // Unwrap temp divs.
      editor.html.unwrap();

      editor.selection.restore();
    }

    function _decrease () {
      // Get blocks.
      var blocks = editor.selection.blocks();
      var i;

      for (i = 0; i < blocks.length; i++) {
        if (blocks[i].tagName != 'BLOCKQUOTE') {
          blocks[i] = $(blocks[i]).parentsUntil(editor.$el, 'BLOCKQUOTE').get(0);
        }
      }

      editor.selection.save();

      for (i = 0; i < blocks.length; i++) {
        if (blocks[i]) {
          $(blocks[i]).replaceWith(blocks[i].innerHTML);
        }
      }

      // Unwrap temp divs.
      editor.html.unwrap();

      editor.selection.restore();
    }

    function apply (val) {
      // Wrap.
      editor.selection.save();
      editor.html.wrap(true, true, true, true);
      editor.selection.restore();

      if (val == 'increase') {
        _increase();
      }
      else if (val == 'decrease') {
        _decrease();
      }


    }

    return {
      apply: apply
    }
  }

  // Register the quote command.
  $.FE.RegisterShortcut($.FE.KEYCODE.SINGLE_QUOTE, 'quote', 'increase', '\'');
  $.FE.RegisterShortcut($.FE.KEYCODE.SINGLE_QUOTE, 'quote', 'decrease', '\'', true);
  $.FE.RegisterCommand('quote', {
    title: 'Quote',
    type: 'dropdown',
    options: {
      increase: 'Increase',
      decrease: 'Decrease'
    },
    callback: function (cmd, val) {
      this.quote.apply(val);
    },
    plugin: 'quote'
  })

  // Add the quote icon.
  $.FE.DefineIcon('quote', {
    NAME: 'quote-left'
  });


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    saveInterval: 10000,
    saveURL: null,
    saveParams: {},
    saveParam: 'body',
    saveMethod: 'POST'
  });


  $.FE.PLUGINS.save = function (editor) {
    var _timeout = null;
    var _last_html = null;
    var _force = false;

    var BAD_LINK = 1;
    var ERROR_ON_SERVER = 2;

    var error_messages = {};
    error_messages[BAD_LINK] = 'Missing saveURL option.';
    error_messages[ERROR_ON_SERVER] = 'Something went wrong during save.';

    /**
     * Throw an image error.
     */
    function _throwError (code, response) {
      editor.events.trigger('save.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    function save (html) {
      if (typeof html == 'undefined') html = editor.html.get();

      // Trigger before save event.
      if (editor.events.trigger('save.before') === false) return false;

      if (editor.opts.saveURL) {
        var params = {};
        for (var key in editor.opts.saveParams) {
          if (editor.opts.saveParams.hasOwnProperty(key)) {
            var param = editor.opts.saveParams[key];
            if (typeof(param) == 'function') {
              params[key] = param.call(this);
            } else {
              params[key] = param;
            }
          }
        }

        var dt = {};
        dt[editor.opts.saveParam] = html;

        $.ajax({
          type: editor.opts.saveMethod,
          url: editor.opts.saveURL,
          data: $.extend(dt, params),
          crossDomain: editor.opts.requestWithCORS,
          xhrFields: {
            withCredentials: editor.opts.requestWithCORS
          },
          headers: editor.opts.requestHeaders
        })
        .done(function (data) {
          // data
          editor.events.trigger('save.after', [data]);
        })
        .fail(function (xhr) {
          // (error)
          _throwError(ERROR_ON_SERVER, xhr.response);
        });
      } else {
        // (error)
        _throwError(BAD_LINK);
      }
    }

    function _mightSave () {
      clearTimeout(_timeout);
      _timeout = setTimeout(function () {
        var html = editor.html.get();
        if (_last_html != html || _force) {
          _last_html = html;
          _force = false;

          save(html);
        }
      }, editor.opts.saveInterval);
    }

    /**
     * Reset the saving interval.
     */
    function reset () {
      _mightSave();
      _force = false;
    }

    /**
     * Force saving at the end of the current interval.
     */
    function force () {
      _force = true;
    }

    /*
     * Initialize.
     */
    function _init () {
      if (editor.opts.saveInterval) {
        _last_html = editor.html.get();
        editor.events.on('contentChanged', _mightSave);
        editor.events.on('keydown', function () {
          clearTimeout(_timeout);
        })
      }
    }

    return {
      _init: _init,
      save: save,
      reset: reset,
      force: force
    }
  }

  $.FE.DefineIcon('save', { NAME: 'floppy-o' });
  $.FE.RegisterCommand('save', {
    title: 'Save',
    undo: false,
    focus: false,
    refreshAfterCallback: false,
    callback: function () {
      this.save.save();
    },
    plugin: 'save'
  });


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'table.insert': '[_BUTTONS_][_ROWS_COLUMNS_]',
    'table.edit': '[_BUTTONS_]',
    'table.colors': '[_BUTTONS_][_COLORS_]'
  })

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    tableInsertMaxSize: 10,
    tableEditButtons: ['tableHeader', 'tableRemove', '|', 'tableRows', 'tableColumns', 'tableStyle', '-', 'tableCells', 'tableCellBackground', 'tableCellVerticalAlign', 'tableCellHorizontalAlign', 'tableCellStyle'],
    tableInsertButtons: ['tableBack', '|'],
    tableResizerOffset: 5,
    tableResizingLimit: 30,
    tableColorsButtons: ['tableBack', '|'],
    tableColors: [
      '#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
      '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
      '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
      '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', 'REMOVE'
    ],
    tableColorsStep: 7,
    tableCellStyles: {
      'fr-highlighted': 'Highlighted',
      'fr-thick': 'Thick'
    },
    tableStyles: {
      'fr-dashed-borders': 'Dashed Borders',
      'fr-alternate-rows': 'Alternate Rows'
    },
    tableCellMultipleStyles: true,
    tableMultipleStyles: true,
    tableInsertHelper: true,
    tableInsertHelperOffset: 15
  });

  $.FE.PLUGINS.table = function (editor) {
    var $resizer;
    var $insert_helper;
    var mouseDownCellFlag;
    var mouseDownFlag;
    var mouseDownCell;
    var mouseMoveTimer;
    var resizingFlag;

    /*
     * Show the insert table popup.
     */
    function _showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertTable"]');

      var $popup = editor.popups.get('table.insert');
      if (!$popup) $popup = _initInsertPopup();

      if (!$popup.hasClass('fr-active')) {

        // Insert table popup
        editor.popups.refresh('table.insert');
        editor.popups.setContainer('table.insert', editor.$tb);

        // Insert table left and top position.
        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
        editor.popups.show('table.insert', left, top, $btn.outerHeight());
      }
    }

    /*
     * Show the table edit popup.
     */
    function _showEditPopup () {
      // Set popup position.
      var map = _tableMap();
      if (map) {
        var $popup = editor.popups.get('table.edit');
        if (!$popup) $popup = _initEditPopup();

        editor.popups.setContainer('table.edit', $(editor.opts.scrollableContainer));
        var offset = _selectionOffset(map);
        var left = (offset.left + offset.right) / 2;
        var top = offset.bottom;

        editor.popups.show('table.edit', left, top, offset.bottom - offset.top);

        // Disable toolbar buttons only if there are more than one cells selected.
        if (editor.edit.isDisabled()) {
          // Disable toolbar.
          editor.toolbar.disable();

          // Allow text selection.
          editor.$el.removeClass('fr-no-selection');
          editor.edit.on();

          editor.selection.setAtEnd(editor.$el.find('.fr-selected-cell:last').get(0));
          editor.selection.restore();
          editor.button.bulkRefresh();
        }
      }
    }

    /*
     * Show the table colors popup.
     */
    function _showColorsPopup () {
      // Set popup position.
      var map = _tableMap();
      if (map) {
        var $popup = editor.popups.get('table.colors');
        if (!$popup) $popup = _initColorsPopup();

        editor.popups.setContainer('table.colors', $(editor.opts.scrollableContainer));
        var offset = _selectionOffset(map);
        var left = (offset.left + offset.right) / 2;
        var top = offset.bottom;

        // Refresh selected color.
        _refreshColor();

        editor.popups.show('table.colors', left, top, offset.bottom - offset.top);
      }
    }

    /*
     * Called on table edit popup hide.
     */
    function _hideEditPopup () {
      // Enable toolbar.
      if (selectedCells().length === 0) {
        editor.toolbar.enable();
      }
    }

    /**
     * Init the insert table popup.
     */
    function _initInsertPopup (delayed) {
      if (delayed) {
        editor.popups.onHide('table.insert', function () {
          // Clear previous cell selection.
          editor.popups.get('table.insert').find('.fr-table-size .fr-select-table-size > span[data-row="1"][data-col="1"]').trigger('mouseenter');
        });

        return true;
      }

      // Table buttons.
      var table_buttons = '';
      if (editor.opts.tableInsertButtons.length > 0) {
        table_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.tableInsertButtons) + '</div>';
      }

      var template = {
        buttons: table_buttons,
        rows_columns: _insertTableHtml()
      };

      var $popup = editor.popups.create('table.insert', template);

      // Initialize insert table grid events.
      editor.events.$on($popup, 'mouseenter', '.fr-table-size .fr-select-table-size .fr-table-cell', function (e) {
        var $table_cell = $(e.currentTarget);
        var row = $table_cell.data('row');
        var col = $table_cell.data('col');
        var $select_size = $table_cell.parent();

        // Update size in title.
        $select_size.siblings('.fr-table-size-info').html(row + ' &times; ' + col);

        // Remove hover class from all cells.
        $select_size.find('> span').removeClass('hover');

        // Add hover class only to the correct cells.
        for (var i = 1; i <= editor.opts.tableInsertMaxSize; i++) {
          for (var j = 0; j <= editor.opts.tableInsertMaxSize; j++) {
            var $cell = $select_size.find('> span[data-row="' + i + '"][data-col="' + j + '"]');

            if (i <= row && j <= col) {
              $cell.addClass('hover');
            } else if ((i <= row + 1 || (i <= 2 && !editor.helpers.isMobile()))) {
              $cell.css('display', 'inline-block');
            } else if (i > 2 && !editor.helpers.isMobile()) {
              $cell.css('display', 'none');
            }
          }
        }
      }, true);

      return $popup;
    }

    /*
     * The HTML for insert table grid.
     */
    function _insertTableHtml () {
      // Grid html
      var rows_columns = '<div class="fr-table-size"><div class="fr-table-size-info">1 &times; 1</div><div class="fr-select-table-size">'

      for (var i = 1; i <= editor.opts.tableInsertMaxSize; i++) {
        for (var j = 1; j <= editor.opts.tableInsertMaxSize; j++) {
          var display = 'inline-block';

          // Display only first 2 rows.
          if (i > 2 && !editor.helpers.isMobile()) {
            display = 'none';
          }

          var cls = 'fr-table-cell ';
          if (i == 1 && j == 1) {
            cls += ' hover';
          }

          rows_columns += '<span class="fr-command ' + cls + '" data-cmd="tableInsert" data-row="' + i + '" data-col="' + j + '" data-param1="' + i + '" data-param2="' + j + '" style="display: ' + display + ';"><span></span></span>';
        }
        rows_columns += '<div class="new-line"></div>';
      }

      rows_columns += '</div></div>';

      return rows_columns;
    }

    /**
     * Init the table edit popup.
     */
    function _initEditPopup (delayed) {
      if (delayed) {
        editor.popups.onHide('table.edit', _hideEditPopup);

        return true;
      }

      // Table buttons.
      var table_buttons = '';
      if (editor.opts.tableEditButtons.length > 0) {
        table_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.tableEditButtons) + '</div>';
      }

      var template = {
        buttons: table_buttons
      };

      var $popup = editor.popups.create('table.edit', template);

      editor.events.$on(editor.$wp, 'scroll.table-edit', function () {
        if (editor.popups.isVisible('table.edit')) {
          _showEditPopup();
        }
      });

      return $popup;
    }

    /*
     * Init the table cell background popup.
     */
    function _initColorsPopup () {
      // Table colors buttons.
      var table_buttons = '';
      if (editor.opts.tableColorsButtons.length > 0) {
        table_buttons = '<div class="fr-buttons fr-table-colors-buttons">' + editor.button.buildList(editor.opts.tableColorsButtons) + '</div>';
      }

      var template = {
        buttons: table_buttons,
        colors: _colorsHTML()
      };

      var $popup = editor.popups.create('table.colors', template);

      editor.events.$on(editor.$wp, 'scroll.table-colors', function () {
        if (editor.popups.isVisible('table.colors')) {
          _showColorsPopup();
        }
      });

      return $popup;
    }

    /*
     * HTML for the table colors.
     */
    function _colorsHTML () {
      // Create colors html.
      var colors_html = '<div class="fr-table-colors">';

      // Add colors.
      for (var i = 0; i < editor.opts.tableColors.length; i++) {
        if (i !== 0 && i % editor.opts.tableColorsStep === 0) {
          colors_html += '<br>';
        }

        if (editor.opts.tableColors[i] != 'REMOVE') {
          colors_html += '<span class="fr-command" style="background: ' + editor.opts.tableColors[i] + ';" data-cmd="tableCellBackgroundColor" data-param1="' + editor.opts.tableColors[i] + '"></span>';
        }

        else {
          colors_html += '<span class="fr-command" data-cmd="tableCellBackgroundColor" data-param1="REMOVE" title="' + editor.language.translate('Clear Formatting') + '"><i class="fa fa-eraser"></i></span>';
        }
      }

      colors_html += '</div>';

      return colors_html;
    }

    /*
     * Show the current selected color.
     */
    function _refreshColor () {
      var $popup = editor.popups.get('table.colors');
      var $cell = editor.$el.find('.fr-selected-cell:first');

      // Remove current color selection.
      $popup.find('.fr-selected-color').removeClass('fr-selected-color');

      // Find the selected color.
      $popup.find('span[data-param1="' + editor.helpers.RGBToHex($cell.css('background-color')) + '"]').addClass('fr-selected-color');
    }

    /*
     * Insert table method.
     */
    function insert (rows, cols) {
      // Create table HTML.
      var table = '<table style="width: 100%;"><tbody>';
      var cell_width = 100 / cols;
      var i;
      var j;

      for (i = 0; i < rows; i++) {
        table += '<tr>';

        for (j = 0; j < cols; j++) {
          table += '<td style="width: ' + cell_width.toFixed(4) + '%;">';
          if (i === 0 && j === 0)table += $.FE.MARKERS;
          table += '<br></td>';
        }
        table += '</tr>';
      }
      table += '</tbody></table>';

      editor.html.insert(table);

      // Update cursor position.
      editor.selection.restore()
    }

    /*
     * Delete table method.
     */
    function remove () {
      if (selectedCells().length > 0) {
        var $current_table = selectedTable();

        // Update cursor position.
        editor.selection.setBefore($current_table.get(0)) || editor.selection.setAfter($current_table.get(0));
        editor.selection.restore();

        // Hide table edit popup.
        editor.popups.hide('table.edit');

        // Delete table.
        $current_table.remove();

        // Enable toolbar.
        editor.toolbar.enable();
      }
    }

    /*
     * Add table header.
     */
    function addHeader () {
      var $table = selectedTable();

      // If there is a selection in the table and the table doesn't have a header already.
      if ($table.length > 0 && $table.find('th').length === 0) {
        // Create header HTML.
        var thead = '<thead><tr>';

        var i;
        var col = 0;

        // Get first row and count table columns.
        $table.find('tr:first > td').each (function () {
          var $td = $(this);

          col += parseInt($td.attr('colspan'), 10) || 1;
        });

        // Add cells.
        for (i = 0; i < col; i++) {
          thead += '<th><br></th>';
        }

        thead += '</tr></thead>'

        $table.prepend(thead);

        // Reposition table edit popup.
        _showEditPopup();
      }
    }

    /*
     * Remove table header.
     */
    function removeHeader () {
      var $current_table = selectedTable();
      var $table_header = $current_table.find('thead');

      // Table has a header.
      if ($table_header.length > 0) {
        // If table does not have any other rows then delete table.
        if ($current_table.find('tbody tr').length === 0) {
          // Remove table.
          remove();
        }

        else {
          $table_header.remove();

          // Reposition table edit popup if there any more selected celss.
          if (selectedCells().length > 0) {
            _showEditPopup();
          }
          else {
            // Hide popup.
            editor.popups.hide('table.edit');

            // Update cursor position.
            var td = $current_table.find('tbody tr:first td:first').get(0);
            if (td) {
              editor.selection.setAtEnd(td);
              editor.selection.restore();
            }
          }
        }
      }
    }

    /*
     * Insert row method.
     */
    function insertRow (position) {
      var $table = selectedTable();

      // We have selection in a table.
      if ($table.length > 0) {
        // Cannot insert row above the table header.
        if (editor.$el.find('th.fr-selected-cell').length > 0 && position == 'above') {
          return;
        }

        var i;
        var ref_row;

        // Create a table map.
        var map = _tableMap();

        // Get selected cells from the table.
        var selection = _currentSelection(map);

        // Reference row.
        if (position == 'above') {
          ref_row = selection.min_i;
        } else {
          ref_row = selection.max_i;
        }

        // Create row HTML.
        var tr = '<tr>';

        // Add cells.
        for (i = 0; i < map[ref_row].length; i++) {
          // If cell has rowspan we should increase it.
          if ((position == 'below' && ref_row < map.length - 1 && map[ref_row][i] == map[ref_row + 1][i]) ||
              (position == 'above' && ref_row > 0 && map[ref_row][i] == map[ref_row - 1][i])) {

            // Don't increase twice for colspan.
            if (i === 0 || (i > 0 && map[ref_row][i] != map[ref_row][i - 1])) {
              var $cell = $(map[ref_row][i]);
              $cell.attr('rowspan', parseInt($cell.attr('rowspan'), 10) + 1);
            }

          } else {
            tr += '<td><br></td>';
          }
        }

        // Close row tag.
        tr += '</tr>';

        var $ref_row = $($table.find('tr').not($table.find('table tr')).get(ref_row));

        // Insert new row.
        if (position == 'below') {
          // Table edit popup should not change position.
          $ref_row.after(tr);
        }
        else if (position == 'above') {
          $ref_row.before(tr);

          // Reposition table edit popup.
          if (editor.popups.isVisible('table.edit')) {
            _showEditPopup();
          }
        }
      }
    }

    /*
     * Delete row method.
     */
    function deleteRow () {
      var $table = selectedTable();

      // We have selection in a table.
      if ($table.length > 0) {
        var i;
        var j;
        var $row;

        // Create a table map.
        var map = _tableMap();

        // Get selected cells from the table.
        var selection = _currentSelection(map);

        // If all the rows are selected then delete the entire table.
        if (selection.min_i === 0 && selection.max_i == map.length - 1) {
          remove();

        } else {
          // We should delete selected rows.
          for (i = selection.max_i; i >= selection.min_i; i--) {
            $row = $($table.find('tr').not($table.find('table tr')).get(i));

            // Go through the table map to check for rowspan.
            for (j = 0; j < map[i].length; j++) {
              // Don't do this twice if we have a colspan.
              if (j === 0 || map[i][j] != map[i][j - 1]) {
                var $cell = $(map[i][j]);

                // We should decrease rowspan.
                if (parseInt($cell.attr('rowspan'), 10) > 1) {
                  var rowspan = parseInt($cell.attr('rowspan'), 10) - 1;

                  if (rowspan == 1) {
                    $cell.removeAttr('rowspan');
                  } else {
                    $cell.attr('rowspan', rowspan);
                  }
                }

                // We might need to move tds on the row below if we have a rowspan that starts here.
                if (i < map.length - 1 && map[i][j] == map[i + 1][j] && (i === 0 || map[i][j] != map[i - 1][j])) {
                  // Move td to the row below.
                  var td = map[i][j];
                  var col = j;

                  // Go back until we get the last element (we might have colspan).
                  while (col > 0 && map[i][col] == map[i][col - 1]) {
                    col--;
                  }

                  // Add td at the beginning of the row below.
                  if (col === 0) {
                    $($table.find('tr').not($table.find('table tr')).get(i + 1)).prepend(td);

                  } else {
                    $(map[i + 1][col - 1]).after(td);
                  }
                }
              }
            }

            // Remove tbody or thead if there are no more rows.
            var $row_parent = $row.parent();
            $row.remove();
            if ($row_parent.find('tr').length === 0) {
              $row_parent.remove();
            }

            // Table has changed.
            map = _tableMap($table);
          }

          // Update cursor position
          if (selection.min_i > 0) {
            // Place cursor in the row above selection.
            editor.selection.setAtEnd(map[selection.min_i - 1][0]);
          }
          else {
            // Place cursor in the row below selection.
            editor.selection.setAtEnd(map[0][0]);
          }
          editor.selection.restore();

          // Hide table edit popup.
          editor.popups.hide('table.edit');
        }
      }
    }

    /*
     * Insert column method.
     */
    function insertColumn (position) {
      var $table = selectedTable();

      // We have selection in a table.
      if ($table.length > 0) {
        // Create a table map.
        var map = _tableMap();

        // Get selected cells from the table.
        var selection = _currentSelection(map);

        // Reference row.
        var ref_col;

        if (position == 'before') {
          ref_col = selection.min_j;
        } else {
          ref_col = selection.max_j;
        }

        // Old and new column widths.
        var old_width = 100 / map[0].length;
        var new_width = 100 / (map[0].length + 1);

        // Go through all cells and get their initial (old) widths.
        var $cell;

        $table.find('th, td').each (function () {
          $cell = $(this);
          $cell.data('old-width', $cell.outerWidth() / $table.outerWidth() * 100);
        });

        // Parse each row to insert a new td.
        $table.find('tr').not($table.find('table tr')).each (function (index) {
          // Get the exact td index before / after which we have to add the new td.
          // ref_col means the table column number, but this is not the same with the td number in a row.
          // We might have colspan or rowspan greater than 1.
          var $row = $(this);
          var col_no = 0;
          var td_no = 0;
          var ref_td;

          // Start with the first td (td_no = 0) in the current row.
          // Sum colspans (col_no) to see when we reach ref_col.
          // Summing colspans we get the same number with the table column number.
          while (col_no - 1 < ref_col) {
            // Get current td.
            ref_td = $row.find('> th, > td').get(td_no);

            // There are no more tds in this row.
            if (!ref_td) {
              ref_td = null;
              break;
            }

            // The current td is the same with the td from the table map.
            if (ref_td == map[index][col_no]) {
              // The current td might have colspan.
              col_no += parseInt($(ref_td).attr('colspan'), 10) || 1;

              // Go to the next td on the current row.
              td_no++;
            }

            // If current td is not the same with the td from the table map.
            // This means that this table cell (map[index][td_no]) has rowspan.
            // There is at least one td less on this row due to rowspan (based on map[index][td_no] colspan value).
            // We want to count this as a column as well.
            else {
              col_no += parseInt($(map[index][col_no]).attr('colspan'), 10) || 1;

              // ref_td is one td ahead. Get previous td if we want to insert column after.
              if (position == 'after') {
                // There is a rowspan and so ref_td is the first td, but it is not in the first column.
                if (td_no === 0) {
                  ref_td = -1;

                } else {
                  ref_td = $row.find('> th, > td').get(td_no - 1);
                }
              }
            }
          }

          var $ref_td = $(ref_td);

          // If the computed column number is higher than the reference number
          // then on this row we have a colspan longer than the reference column.
          // When adding a column after we should increase colspan on this row.
          //
          // If we want to add a column before, but the td on the reference column is
          // the same with the previous one then we have a td with colspan that
          // starts before the column reference number.
          // When adding a column before we should increase colspan on this row.
          if ((position == 'after' && col_no - 1 > ref_col) ||
              (position == 'before' && ref_col > 0 && map[index][ref_col] == map[index][ref_col - 1])) {

            // Don't increase twice for rowspan.
            if (index === 0 || (index > 0 && map[index][ref_col] != map[index - 1][ref_col])) {
              var colspan = parseInt($ref_td.attr('colspan'), 10) + 1;

              $ref_td.attr('colspan', colspan)

              // Update this cell's width.
              $ref_td.css('width', ($ref_td.data('old-width') * new_width / old_width + new_width).toFixed(4) + '%');
              $ref_td.removeData('old-width');
            }

          }

          else {
            // Create HTML for a new cell.
            var td;

            // Might be a td or a th.
            if ($row.find('th').length > 0) {
              td = '<th style="width: ' + new_width.toFixed(4) + '%;"><br></th>';
            }
            else {
              td = '<td style="width: ' + new_width.toFixed(4) + '%;"><br></td>';
            }

            // Insert exactly at the beginning.
            if (ref_td == -1) {
              $row.prepend(td);

            // Insert exactly at the end.
            } else if (ref_td == null) {
              $row.append(td);

            // Insert td on the current row.
            } else {
              if (position == 'before') {
                $ref_td.before(td);
              }

              else if (position == 'after') {
                $ref_td.after(td);
              }
            }
          }
        });

        // Parse each row to update cells' width.
        $table.find('th, td').each (function () {
          $cell = $(this);

          // Update width and remove data.
          if ($cell.data('old-width')) {
            $cell.css('width', ($cell.data('old-width') * new_width / old_width).toFixed(4) + '%');
            $cell.removeData('old-width')
          }
        });

        // Reposition table edit popup.
        if (editor.popups.isVisible('table.edit')) {
          _showEditPopup();
        }
      }
    }

    /*
     * Delete column method.
     */
    function deleteColumn () {
      var $table = selectedTable();

      // We have selection in a table.
      if ($table.length > 0) {
        var i;
        var j;
        var $cell;

        // Create a table map.
        var map = _tableMap();

        // Get selected cells from the table.
        var selection = _currentSelection(map);

        // If all the rows are selected then delete the entire table.
        if (selection.min_j === 0 && selection.max_j == map[0].length - 1) {
          remove();

        } else {
          // Old and new column widths.
          var old_width = 100 / map[0].length;
          var new_width = 100 / (map[0].length - selection.max_j + selection.min_j - 1);

          // Go through all cells and get their initial (old) widths.
          $table.find('th, td').each (function () {
            $cell = $(this);

            if (!$cell.hasClass('fr-selected-cell')) {
              $cell.data('old-width', $cell.outerWidth() / $table.outerWidth() * 100);
            }
          });

          // We should delete selected columns.
          for (j = selection.max_j; j >= selection.min_j; j--) {
            // Go through the table map to check for colspan.
            for (i = 0; i < map.length; i++) {
              // Don't do this twice if we have a rowspan.
              if (i === 0 || map[i][j] != map[i - 1][j]) {
                // We should decrease colspan.
                $cell = $(map[i][j]);

                if (parseInt($cell.attr('colspan'), 10) > 1) {
                  var colspan = parseInt($cell.attr('colspan'), 10) - 1;

                  if (colspan == 1) {
                    $cell.removeAttr('colspan');
                  } else {
                    $cell.attr('colspan', colspan);
                  }

                  // Update cell width.
                  $cell.css('width', (($cell.data('old-width') - _columnWidth(j, map)) * new_width / old_width).toFixed(4) + '%');
                  $cell.removeData('old-width');

                // If there is no colspan delete the cell.
                } else {
                  // We might need to delete the row too if it is empty.
                  var $row = $($cell.parent().get(0));

                  $cell.remove();

                  // Check if there are any more tds in the current row.
                  if ($row.find('> th, > td').length === 0) {
                    // Check if it is okay to delete the tr.
                    if ($row.prev().length === 0 || $row.next().length === 0 ||
                        $row.prev().find('> th[rowspan], > td[rowspan]').length < $row.prev().find('> th, > td').length) {
                      $row.remove();
                    }
                  }
                }
              }
            }
          }

          // Update cursor position
          if (selection.min_j > 0) {
            // Place cursor in the column before selection.
            editor.selection.setAtEnd(map[selection.min_i][selection.min_j - 1]);
          }
          else {
            // Place cursor in the column after selection.
            editor.selection.setAtEnd(map[selection.min_i][0]);
          }
          editor.selection.restore();

          // Hide table edit popup.
          editor.popups.hide('table.edit');

          // Scale current cells' width after column has been deleted.
          $table.find('th, td').each (function () {
            $cell = $(this);

            // Update width and remove data.
            if ($cell.data('old-width')) {
              $cell.css('width', ($cell.data('old-width') * new_width / old_width).toFixed(4) + '%');
              $cell.removeData('old-width')
            }
          });
        }
      }
    }

    /*
     * Merge selected cells method.
     */
    function mergeCells () {
      // We have more than one cell selected in a table. Cannot merge td and th.
      if (selectedCells().length > 1 && (editor.$el.find('th.fr-selected-cell').length === 0 || editor.$el.find('td.fr-selected-cell').length === 0)) {
        // Create a table map.
        var map = _tableMap();

        // Get selected cells.
        var selection = _currentSelection(map);

        var i;
        var $cell;
        var $row;
        var cells = editor.$el.find('.fr-selected-cell');
        var $first_cell = $(cells[0]);
        var $first_row = $first_cell.parent();
        var first_row_cells = $first_row.find('.fr-selected-cell');
        var $current_table = $first_cell.closest('table');
        var content = $first_cell.html();
        var width = 0;

        // Update cell width.
        for (i = 0; i < first_row_cells.length; i++) {
          width += $(first_row_cells[i]).outerWidth();
        }

        $first_cell.css('width', (width / $first_row.outerWidth() * 100).toFixed(4) + '%');

        // Set the colspan for the merged cells.
        if (selection.min_j < selection.max_j) {
          $first_cell.attr('colspan', selection.max_j - selection.min_j + 1);
        }

        // Set the rowspan for the merged cells.
        if (selection.min_i < selection.max_i) {
          $first_cell.attr('rowspan', selection.max_i - selection.min_i + 1);
        }

        // Go through all selected cells to merge their content.
        for (i = 1; i < cells.length; i++) {
          $cell = $(cells[i])

          // If cell is empty, don't add only <br> tags.
          if ($cell.html() != '<br>' && $cell.html() !== '') {
            content += '<br>' + $cell.html();
          }

          // Remove cell.
          $cell.remove();
        }

        // Set the HTML content.
        $first_cell.html(content);
        editor.selection.setAtEnd($first_cell.get(0));
        editor.selection.restore();

        // Enable toolbar.
        editor.toolbar.enable();

        // Merge is done, check if we have empty trs to clean.
        var empty_trs = $current_table.find('tr:empty');

        for (i = empty_trs.length - 1; i >= 0; i--) {
          $row = $(empty_trs[i]);

          // Check if it is okay to delete the tr.
          if ($row.prev().length === 0 || $row.next().length === 0 ||
              $row.prev().find('> th[rowspan], > td[rowspan]').length < $row.prev().find('> th, > td').length) {
            $row.remove();
          }
        }

        // Reposition table edit popup.
        _showEditPopup();
      }
    }

    /*
     * Split cell horizontally method.
     */
    function splitCellHorizontally () {
      // We have only one cell selected in a table.
      if (selectedCells().length == 1) {
        var $selected_cell = editor.$el.find('.fr-selected-cell');
        var $current_row = $selected_cell.parent();
        var $current_table = $selected_cell.closest('table');
        var current_rowspan = parseInt($selected_cell.attr('rowspan'), 10);

        // Create a table map.
        var map = _tableMap();
        var cell_origin = _cellOrigin($selected_cell.get(0), map);

        // Create new td.
        var $new_td = $selected_cell.clone().html('<br>');

        // Cell has rowspan.
        if (current_rowspan > 1) {
          // Split current cell's rowspan.
          var new_rowspan = Math.ceil(current_rowspan / 2);

          if (new_rowspan > 1) {
            $selected_cell.attr('rowspan', new_rowspan);
          } else {
            $selected_cell.removeAttr('rowspan');
          }

          // Update new td's rowspan.
          if (current_rowspan - new_rowspan > 1) {
            $new_td.attr('rowspan', current_rowspan - new_rowspan);
          } else {
            $new_td.removeAttr('rowspan');
          }

          // Find where we should insert the new td.
          var row = cell_origin.row + new_rowspan;
          var col = cell_origin.col === 0 ? cell_origin.col : cell_origin.col - 1;

          // Go back until we find a td on this row. We might have colspans and rowspans.
          while (col >= 0 && (map[row][col] == map[row][col - 1] || (row > 0 && map[row][col] == map[row - 1][col]))) {
            col--;
          }

          if (col == -1) {
            // We couldn't find a previous td on this row. Prepend the new td.
            $($current_table.find('tr').not($current_table.find('table tr')).get(row)).prepend($new_td);

          } else {
            $(map[row][col]).after($new_td);
          }

        } else {
          // Add new row bellow with only one cell.
          var $row = $('<tr>').append($new_td);
          var i;

          // Increase rowspan for all cells on the current row.
          for (i = 0; i < map[0].length; i++) {
            // Don't do this twice if we have a colspan.
            if (i === 0 || map[cell_origin.row][i] != map[cell_origin.row][i - 1]) {
              var $cell = $(map[cell_origin.row][i]);

              if (!$cell.is($selected_cell)) {
                $cell.attr('rowspan', (parseInt($cell.attr('rowspan'), 10) || 1) + 1);
              }
            }
          }

          $current_row.after($row);
        }

        // Remove selection
        _removeSelection();

        // Hide table edit popup.
        editor.popups.hide('table.edit');
      }
    }

    /*
     * Split cell vertically method.
     */
    function splitCellVertically () {
      // We have only one cell selected in a table.
      if (selectedCells().length == 1) {
        var $selected_cell = editor.$el.find('.fr-selected-cell');
        var current_colspan = parseInt($selected_cell.attr('colspan'), 10) || 1;
        var parent_width = $selected_cell.parent().outerWidth();
        var width = $selected_cell.outerWidth();

        // Create new td.
        var $new_td = $selected_cell.clone().html('<br>');

        // Create a table map.
        var map = _tableMap();
        var cell_origin = _cellOrigin($selected_cell.get(0), map);

        if (current_colspan > 1) {
          // Split current colspan.
          var new_colspan = Math.ceil(current_colspan / 2);

          width = _columnsWidth(cell_origin.col, cell_origin.col + new_colspan - 1, map) / parent_width * 100;
          var new_width = _columnsWidth(cell_origin.col + new_colspan, cell_origin.col + current_colspan - 1, map) / parent_width * 100;

          // Update colspan for current cell.
          if (new_colspan > 1) {
            $selected_cell.attr('colspan', new_colspan);
          } else {
            $selected_cell.removeAttr('colspan');
          }

          // Update new td's colspan.
          if (current_colspan - new_colspan > 1) {
            $new_td.attr('colspan', current_colspan - new_colspan);
          } else {
            $new_td.removeAttr('colspan');
          }

          // Update cell width.
          $selected_cell.css('width', width.toFixed(4) + '%');
          $new_td.css('width', new_width.toFixed(4) + '%');

        // Increase colspan for all cells on the current column.
        } else {
          var i;

          for (i = 0; i < map.length; i++) {
            // Don't do this twice if we have a rowspan.
            if (i === 0 || map[i][cell_origin.col] != map[i - 1][cell_origin.col]) {
              var $cell = $(map[i][cell_origin.col]);

              if (!$cell.is($selected_cell)) {
                var colspan = (parseInt($cell.attr('colspan'), 10) || 1) + 1;
                $cell.attr('colspan', colspan);
              }
            }
          }

          // Update cell width.
          width = width / parent_width * 100 / 2;

          $selected_cell.css('width', width.toFixed(4) + '%');
          $new_td.css('width', width.toFixed(4) + '%');
        }

        // Add a new td after the current one.
        $selected_cell.after($new_td);

        // Remove selection
        _removeSelection();

        // Hide table edit popup.
        editor.popups.hide('table.edit');
      }
    }

    /*
     * Set background color to selected cells.
     */
    function setBackground (color) {
      // Set background  color.
      if (color != 'REMOVE') {
        editor.$el.find('.fr-selected-cell').css('background-color', editor.helpers.HEXtoRGB(color));
      }

      // Remove background color.
      else {
        editor.$el.find('.fr-selected-cell').css('background-color', '');
      }
    }

    /*
     * Set vertical align to selected cells.
     */
    function verticalAlign (val) {
      editor.$el.find('.fr-selected-cell').css('vertical-align', val);
    }

    /*
     * Apply horizontal alignment to selected cells.
     */
    function horizontalAlign (val) {
      editor.$el.find('.fr-selected-cell').css('text-align', val);
    }

    /**
     * Apply specific style to selected table or selected cells.
     * val              class to apply.
     * obj              table or selected cells.
     * multiple_styles  editor.opts.tableStyles or editor.opts.tableCellStyles.
     * style            editor.opts.tableStyles or editor.opts.tableCellStyles
     */
    function applyStyle (val, obj, multiple_styles, styles) {
      if (obj.length > 0) {
        // Remove multiple styles.
        if (!multiple_styles) {
          var classes = Object.keys(styles);
          classes.splice(classes.indexOf(val), 1);
          obj.removeClass(classes.join(' '));
        }

        obj.toggleClass(val);
      }
    }

    /*
     * Create a table map.
     */
    function _tableMap (table) {
      table = table || null;

      var map = [];

      if (table == null && selectedCells().length > 0) {
        table = selectedTable();
      }

      if (table) {
        var $table = $(table);
        $table.find('tr').not($table.find('table tr')).each (function (row, tr) {
          var $tr = $(tr);

          var c_index = 0;
          $tr.find('> th, > td').each (function (col, td) {
            var $td = $(td);
            var cspan = parseInt($td.attr('colspan'), 10) || 1;
            var rspan = parseInt($td.attr('rowspan'), 10) || 1;

            for (var i = row; i < row + rspan; i++) {
              for (var j = c_index; j < c_index + cspan; j++) {
                if (!map[i]) map[i] = [];
                if (!map[i][j]) {
                  map[i][j] = td;
                } else {
                  c_index++;
                }
              }
            }

            c_index += cspan;
          })
        });

        return map;
      }
    }

    /*
     * Get the i, j coordinates of a cell in the table map.
     * These are the coordinates where the cell starts.
     */
    function _cellOrigin (td, map) {
      for (var i = 0; i < map.length; i++) {
        for (var j = 0; j < map[i].length; j++) {
          if (map[i][j] == td) {
            return {
              row: i,
              col: j
            };
          }
        }
      }
    }

    /*
     * Get the i, j coordinates where a cell ends in the table map.
     */
    function _cellEnds (origin_i, origin_j, map) {
      var max_i = origin_i + 1;
      var max_j = origin_j + 1;

      // Compute max_i
      while (max_i < map.length) {
        if (map[max_i][origin_j] != map[origin_i][origin_j]) {
          max_i--;
          break;
        }

        max_i++;
      }

      if (max_i == map.length) {
        max_i--;
      }

      // Compute max_j
      while (max_j < map[origin_i].length) {
        if (map[origin_i][max_j] != map[origin_i][origin_j]) {
          max_j--;
          break;
        }

        max_j++;
      }

      if (max_j == map[origin_i].length) {
        max_j--;
      }

      return {
        row: max_i,
        col: max_j
      };
    }

    /*
     * Remove selection from cells.
     */
    function _removeSelection () {
      var cells = editor.$el.find('.fr-selected-cell');

      if (cells.length > 0) {
        // Remove selection.
        cells.each (function () {
          var $cell = $(this);

          $cell.removeClass('fr-selected-cell');

          if ($cell.attr('class') === '') {
            $cell.removeAttr('class');
          }
        });
      }
    }

    /*
     * Clear selection to prevent Firefox cell selection.
     */
    function _clearSelection () {
      setTimeout(function () {
        editor.selection.clear();

        // Prevent text selection while selecting multiple cells.
        // Happens in Chrome.
        editor.$el.addClass('fr-no-selection');

        // Cursor will not appear if we don't make blur.
        editor.$el.blur();
      }, 0);
    }

    /*
     * Get current selected cells coordintates.
     */
    function _currentSelection (map) {
      var min_i = map.length;
      var max_i = 0;
      var min_j = map[0].length;
      var max_j = 0;
      var i;
      var cells = editor.$el.find('.fr-selected-cell');

      for (i = 0; i < cells.length; i++) {
        var cellOrigin = _cellOrigin(cells[i], map);
        var cellEnd = _cellEnds(cellOrigin.row, cellOrigin.col, map);

        min_i = Math.min(cellOrigin.row, min_i);
        max_i = Math.max(cellEnd.row, max_i);
        min_j = Math.min(cellOrigin.col, min_j);
        max_j = Math.max(cellEnd.col, max_j);
      }

      return {
        min_i: min_i,
        max_i: max_i,
        min_j: min_j,
        max_j: max_j
      };
    }

    /*
     * Minimum and maximum coordinates for the selection in the table map.
     */
    function _selectionLimits (min_i, max_i, min_j, max_j, map) {
      var first_i = min_i;
      var last_i = max_i;
      var first_j = min_j;
      var last_j = max_j;
      var i;
      var j;
      var cellOrigin;
      var cellEnd;

      // Go through first and last columns.
      for (i = first_i; i <= last_i; i++) {
        // First column.
        if ((parseInt($(map[i][first_j]).attr('rowspan'), 10) || 1) > 1 ||
            (parseInt($(map[i][first_j]).attr('colspan'), 10) || 1) > 1) {
          cellOrigin = _cellOrigin(map[i][first_j], map);
          cellEnd = _cellEnds(cellOrigin.row, cellOrigin.col, map);

          first_i = Math.min(cellOrigin.row, first_i);
          last_i = Math.max(cellEnd.row, last_i);
          first_j = Math.min(cellOrigin.col, first_j);
          last_j = Math.max(cellEnd.col, last_j);
        }

        // Last column.
        if ((parseInt($(map[i][last_j]).attr('rowspan'), 10) || 1) > 1 ||
            (parseInt($(map[i][last_j]).attr('colspan'), 10) || 1) > 1) {
          cellOrigin = _cellOrigin(map[i][last_j], map);
          cellEnd = _cellEnds(cellOrigin.row, cellOrigin.col, map);

          first_i = Math.min(cellOrigin.row, first_i);
          last_i = Math.max(cellEnd.row, last_i);
          first_j = Math.min(cellOrigin.col, first_j);
          last_j = Math.max(cellEnd.col, last_j);
        }
      }

      // Go through first and last rows.
      for (j = first_j; j <= last_j; j++) {
        // First row.
        if ((parseInt($(map[first_i][j]).attr('rowspan'), 10) || 1) > 1 ||
            (parseInt($(map[first_i][j]).attr('colspan'), 10) || 1) > 1) {
          cellOrigin = _cellOrigin(map[first_i][j], map);
          cellEnd = _cellEnds(cellOrigin.row, cellOrigin.col, map);

          first_i = Math.min(cellOrigin.row, first_i);
          last_i = Math.max(cellEnd.row, last_i);
          first_j = Math.min(cellOrigin.col, first_j);
          last_j = Math.max(cellEnd.col, last_j);
        }

        // Last column.
        if ((parseInt($(map[last_i][j]).attr('rowspan'), 10) || 1) > 1 ||
            (parseInt($(map[last_i][j]).attr('colspan'), 10) || 1) > 1) {
          cellOrigin = _cellOrigin(map[last_i][j], map);
          cellEnd = _cellEnds(cellOrigin.row, cellOrigin.col, map);

          first_i = Math.min(cellOrigin.row, first_i);
          last_i = Math.max(cellEnd.row, last_i);
          first_j = Math.min(cellOrigin.col, first_j);
          last_j = Math.max(cellEnd.col, last_j);
        }
      }

      if (first_i == min_i && last_i == max_i && first_j == min_j && last_j == max_j) {
        return {
          min_i: min_i,
          max_i: max_i,
          min_j: min_j,
          max_j: max_j
        };

      } else {
        return _selectionLimits(first_i, last_i, first_j, last_j, map);
      }
    }

    /*
     * Get the left and right offset position for the current selection.
     */
    function _selectionOffset (map) {
      var selection = _currentSelection(map);

      // Top left cell.
      var $tl = $(map[selection.min_i][selection.min_j]);

      // Top right cell.
      var $tr = $(map[selection.min_i][selection.max_j]);

      // Bottom left cell.
      var $bl = $(map[selection.max_i][selection.min_j]);

      var left = $tl.offset().left
      var right = $tr.offset().left + $tr.outerWidth();
      var top = $tl.offset().top;
      var bottom = $bl.offset().top + $bl.outerHeight();

      return {
        left: left,
        right: right,
        top: top,
        bottom: bottom
      };
    }

    /*
     * Select table cells
     */
    function _selectCells (firstCell, lastCell) {
      // If the first and last cells are the same then just select it.
      if ($(firstCell).is(lastCell)) {
        // Remove previous selection.
        _removeSelection();

        // Enable editor toolbar.
        editor.edit.on();

        $(firstCell).addClass('fr-selected-cell');

      // Select multiple celss.
      } else {
        // Prevent Firefox cell selection.
        _clearSelection();

        // Turn editor toolbar off.
        editor.edit.off();

        // Create a table map.
        var map = _tableMap();

        // Get first and last cell's i and j map coordinates.
        var firstCellOrigin = _cellOrigin(firstCell, map);
        var lastCellOrigin = _cellOrigin(lastCell, map);

        // Some cells between these coordinates might have colspan or rowspan.
        // The selected area exceeds first and last cells' coordinates.
        var limits = _selectionLimits(Math.min(firstCellOrigin.row, lastCellOrigin.row),
                                                  Math.max(firstCellOrigin.row, lastCellOrigin.row),
                                                  Math.min(firstCellOrigin.col, lastCellOrigin.col),
                                                  Math.max(firstCellOrigin.col, lastCellOrigin.col),
                                                  map);
        // Remove previous selection.
        _removeSelection();

        // Select all cells between the first and last cell.
        for (var i = limits.min_i; i <= limits.max_i; i++) {
          for (var j = limits.min_j; j <= limits.max_j; j++) {
            $(map[i][j]).addClass('fr-selected-cell');
          }
        }
      }
    }

    /*
     * Get the cell under the mouse cursor.
     */
    function _getCellUnder (e) {
      var cell = null;
      var $target = $(e.target);

      if (e.target.tagName == 'TD' || e.target.tagName == 'TH') {
        cell = e.target;
      } else if ($target.closest('td').length > 0) {
        cell = $target.closest('td').get(0);
      } else if ($target.closest('th').length > 0) {
        cell = $target.closest('th').get(0);
      }

      // Cell should reside inside editor.
      if (editor.$el.find(cell).length === 0) return null;

      return cell;
    }

    /*
     * Stop table cell editing and allow text editing.
     */
    function _stopEdit () {
      // Clear previous selection.
      _removeSelection();

      // Hide table edit popup.
      editor.popups.hide('table.edit');
    }

    /*
     * Mark that mouse is down.
     */
    function _mouseDown (e) {
      var cell = _getCellUnder(e);

      // Stop table editing if user clicks outside the table.
      if (selectedCells().length > 0 && !cell) {
        _stopEdit();
      }

      // On left click.
      if (e.which == 1 && !(e.which == 1 && editor.helpers.isMac() && e.ctrlKey)) {
        mouseDownFlag = true;

        // User clicked on a table cell.
        if (cell) {
          // We always have to clear previous selection except when using shift key to select multiple cells.
          if (selectedCells().length > 0 && !e.shiftKey) {
            _stopEdit();
          }

          e.stopPropagation();

          editor.events.trigger('image.hideResizer');
          editor.events.trigger('video.hideResizer');

          // Keep record of left mouse click being down
          mouseDownCellFlag = true;

          var tag_name = cell.tagName.toLowerCase();

          // Select multiple cells using Shift key
          if (e.shiftKey && editor.$el.find(tag_name + '.fr-selected-cell').length > 0) {

            // Cells must be in the same table.
            if ($(editor.$el.find(tag_name + '.fr-selected-cell').closest('table')).is($(cell).closest('table'))) {
              // Select cells between.
              _selectCells(mouseDownCell, cell);

            // Do nothing if cells are not in the same table.
            } else {
              // Prevent Firefox selection.
              _clearSelection();
            }
          }

          else {
            // Prevent Firefox selection for ctrl / cmd key.
            if (editor.keys.ctrlKey(e) || e.shiftKey) {
              _clearSelection();
            }

            // Save cell where mouse has been clicked
            mouseDownCell = cell;

            // Select cell.
            _selectCells(mouseDownCell, mouseDownCell);
          }
        }
      }

      // On right click stop table editing.
      else if ((e.which == 3 || (e.which == 1 && editor.helpers.isMac() && e.ctrlKey)) && cell) {
        _stopEdit();
      }
    }

    /*
     * Notify that mouse is no longer pressed.
     */
    function _mouseUp (e) {
      // On left click.
      if (e.which == 1 && !(e.which == 1 && editor.helpers.isMac() && e.ctrlKey)) {
        mouseDownFlag = false;

        // Mouse down was in a table cell.
        if (mouseDownCellFlag) {
          // Left click is no longer pressed.
          mouseDownCellFlag = false;

          var cell = _getCellUnder(e);

          // If we have one selected cell and mouse is lifted somewhere else.
          if (!cell && selectedCells().length == 1) {
            // We have a text selection and not cell selection.
            _removeSelection();
          }

          // If there are selected cells then show table edit popup.
          else if (selectedCells().length > 0) {
            if (editor.selection.isCollapsed()) {
              _showEditPopup();
            }

            // No text selection.
            else {
              _removeSelection();
            }
          }
        }

        // User clicked somewhere else in the editor (except the toolbar).
        // We need this because mouse down is not triggered outside the editor.
        else if (!editor.$tb.is(e.target) && !editor.$tb.is($(e.target).closest(editor.$tb.get(0)))) {
          if (selectedCells().length > 0) {
            editor.toolbar.enable();
          }

          _removeSelection();
        }

        // Resizing stops.
        if (resizingFlag) {
          resizingFlag = false;

          $resizer.removeClass('fr-moving');

          // Allow text selection.
          editor.$el.removeClass('fr-no-selection');
          editor.edit.on();

          // Set release Y coordinate.
          var left = parseFloat($resizer.css('left')) + editor.opts.tableResizerOffset;
          if (editor.opts.iframe) {
            left -= editor.$iframe.offset().left;
          }
          $resizer.data('release-position', left);

          // Clear resizing limits.
          $resizer.removeData('max-left');
          $resizer.removeData('max-right');

          // Resize.
          _resize(e);

          // Hide resizer.
          _hideResizer();
        }
      }
    }

    /*
     * User drags mouse over multiple cells to select them.
     */
    function _mouseEnter (e) {
      if (mouseDownCellFlag === true) {
        var $cell = $(e.currentTarget);

        // Cells should be in the same table.
        if ($cell.closest('table').is(selectedTable())) {
          // Don't select both ths and tds.
          if (e.currentTarget.tagName == 'TD' && editor.$el.find('th.fr-selected-cell').length === 0) {
            // Select cells between.
            _selectCells(mouseDownCell, e.currentTarget);
            return;
          }

          else if (e.currentTarget.tagName == 'TH' && editor.$el.find('td.fr-selected-cell').length === 0) {
            // Select cells between.
            _selectCells(mouseDownCell, e.currentTarget);
            return;
          }
        }

        // Prevent firefox selection.
        _clearSelection();
      }
    }

    /*
     * Using the arrow keys to move the cursor through the table will not select cells.
     */
    function _usingArrows (e) {
      if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
        if (selectedCells().length > 0) {
          _stopEdit();
        }
      }
    }

    /*
     * Initilize table resizer.
     */
    function _initResizer () {
      // Append resizer HTML to editor wrapper.
      if (!editor.shared.$table_resizer) editor.shared.$table_resizer = $('<div class="fr-table-resizer"><div></div></div>');
      $resizer = editor.shared.$table_resizer;

      // Resize table. Mousedown.
      editor.events.$on($resizer, 'mousedown', function (e) {
        if (!editor.core.sameInstance($resizer)) return true;

        // Stop table editing.
        if (selectedCells().length > 0) {
          _stopEdit();
        }

        // Resize table only using left click.
        if (e.which == 1) {
          resizingFlag = true;

          $resizer.addClass('fr-moving');

          // Prevent text selection while dragging the table resizer.
          _clearSelection();

          // Turn editor toolbar off while resizing.
          editor.edit.off();

          // Show resizer.
          $resizer.find('div').css('opacity', 1);

          // Prevent selecting text when doing resize.
          return false;
        }
      });

      // Mousemove on table resizer.
      editor.events.$on($resizer, 'mousemove', function (e) {
        if (!editor.core.sameInstance($resizer)) return true;

        if (resizingFlag) {
          if (editor.opts.iframe) {
            e.pageX -= editor.$iframe.offset().left;
          }

          _mouseMove(e);
        }
      })

      // Editor destroy.
      editor.events.on('shared.destroy', function () {
        $resizer.html('').removeData().remove();
        $resizer = null;
      }, true);

      editor.events.on('destroy', function () {
        editor.$el.find('.fr-selected-cell').removeClass('fr-selected-cell');
        $resizer.hide().appendTo($('body'));
      }, true);
    }

    /*
     * Also clears top and left values, so it doesn't interfer with the insert helper.
     */
    function _hideResizer () {
      if ($resizer) {
        $resizer.find('div').css('opacity', 0);
        $resizer.css('top', 0);
        $resizer.css('left', 0);
        $resizer.css('height', 0);
        $resizer.find('div').css('height', 0);
        $resizer.hide();
      }
    }

    /**
     * Hide the insert helper.
     */
    function _hideInsertHelper () {
      if ($insert_helper) $insert_helper.removeClass('fr-visible').css('left', '-9999px');
    }

    /*
     * Place the table resizer between the columns where the mouse is.
     */
    function _placeResizer (e, tag_under) {
      var $tag_under = $(tag_under);
      var $table = $tag_under.closest('table');

      // We might have another tag inside the table cell.
      if (tag_under && (tag_under.tagName != 'TD' && tag_under.tagName != 'TH')) {
        if ($tag_under.closest('td').length > 0) {
          tag_under = $tag_under.closest('td');
        } else if ($tag_under.closest('th').length > 0) {
          tag_under = $tag_under.closest('th');
        }
      }

      // The tag should be a table cell (TD or TH).
      if (tag_under && (tag_under.tagName == 'TD' || tag_under.tagName == 'TH')) {
        $tag_under = $(tag_under);

        // https://github.com/froala/wysiwyg-editor/issues/786.
        if (editor.$el.find($tag_under).length === 0) return false;

        // Tag's left and right coordinate.
        var tag_left = $tag_under.offset().left - 1;
        var tag_right = tag_left + $tag_under.outerWidth();

        // Only if the mouse is close enough to the left or right edges.
        if (Math.abs(e.pageX - tag_left) <= editor.opts.tableResizerOffset ||
            Math.abs(tag_right - e.pageX) <= editor.opts.tableResizerOffset) {

          // Create a table map.
          var map = _tableMap($table);
          var tag_origin = _cellOrigin(tag_under, map);

          var tag_end = _cellEnds(tag_origin.row, tag_origin.col, map);

          // The column numbers from the map that have to be resized.
          var first;
          var second;

          // Table resizer position and height.
          var resizer_top = $table.offset().top;
          var resizer_height = $table.outerHeight() - 1;
          var resizer_left;

          // The left and right limits between which the resizer can be moved.
          var max_left;
          var max_right;

          // Mouse is near the cells's left margin (skip left table border).
          if (tag_origin.col > 0 && e.pageX - tag_left <= editor.opts.tableResizerOffset) {
            // Table resizer's left position.
            resizer_left = tag_left;

            // Previous table cell. (There's always a prev cell since we're skipping the left table border)
            var $prev_tag = $(map[tag_origin.row][tag_origin.col - 1]);

            // Left limit.
            if ((parseInt($prev_tag.attr('colspan'), 10) || 1) == 1) {
              max_left = $prev_tag.offset().left - 1 + editor.opts.tableResizingLimit;
            } else {
              max_left = tag_left - _columnWidth(tag_origin.col - 1, map) + editor.opts.tableResizingLimit;
            }

            // Right limit.
            if ((parseInt($tag_under.attr('colspan'), 10) || 1) == 1) {
              max_right = tag_left + $tag_under.outerWidth() - editor.opts.tableResizingLimit;
            } else {
              max_right = tag_left + _columnWidth(tag_origin.col, map) - editor.opts.tableResizingLimit;
            }

            // Columns to resize.
            first = tag_origin.col - 1;
            second = tag_origin.col;
          }

          // Mouse is near the cell's right margin.
          else if (tag_right - e.pageX <= editor.opts.tableResizerOffset) {
            // Table resizer's left possition.
            resizer_left = tag_right;

            // Check for next td.
            if (tag_end.col < map[tag_end.row].length && map[tag_end.row][tag_end.col + 1]) {
              // Next table cell.
              var $next_tag = $(map[tag_end.row][tag_end.col + 1]);

              // Left limit.
              if ((parseInt($tag_under.attr('colspan'), 10) || 1) == 1) {
                max_left = tag_left + editor.opts.tableResizingLimit;
              } else {
                max_left = tag_right - _columnWidth(tag_end.col, map) + editor.opts.tableResizingLimit;
              }

              // Right limit.
              if ((parseInt($next_tag.attr('colspan'), 10) || 1) == 1) {
                max_right = tag_right + $next_tag.outerWidth() - editor.opts.tableResizingLimit;
              } else {
                max_right = tag_right + _columnWidth(tag_origin.col + 1, map) - editor.opts.tableResizingLimit;
              }

              // Columns to resize.
              first = tag_end.col;
              second = tag_end.col + 1;
            }

            // Resize table.
            else {
              // Columns to resize.
              first = tag_end.col;
              second = null;

              // Resizer limits.
              var $table_parent = $table.parent();
              max_left = $table.offset().left - 1 + map[0].length * editor.opts.tableResizingLimit;
              max_right = $table_parent.offset().left - 1 + $table_parent.width() + parseFloat($table_parent.css('padding-left'));
            }
          }

          if (!$resizer) _initResizer();

          // Save table.
          $resizer.data('table', $table);

          // Save columns to resize.
          $resizer.data('first', first);
          $resizer.data('second', second);

          $resizer.data('instance', editor);
          editor.$wp.append($resizer);

          var left = resizer_left - editor.win.pageXOffset - editor.opts.tableResizerOffset;
          var top = resizer_top - editor.win.pageYOffset;

          if (editor.opts.iframe) {
            left += editor.$iframe.offset().left - $(editor.o_win).scrollLeft();
            top += editor.$iframe.offset().top - $(editor.o_win).scrollTop();

            max_left += editor.$iframe.offset().left;
            max_right += editor.$iframe.offset().left;
          }

          // Set resizing limits.
          $resizer.data('max-left', max_left);
          $resizer.data('max-right', max_right);

          // Initial position of the resizer
          $resizer.data('origin', resizer_left - editor.win.pageXOffset);

          // Set table resizer's top, left and height.
          $resizer.css('top', top);
          $resizer.css('left', left);
          $resizer.css('height', resizer_height);
          $resizer.find('div').css('height', resizer_height);

          // Set padding according to tableResizerOffset.
          $resizer.css('padding-left', editor.opts.tableResizerOffset);
          $resizer.css('padding-right', editor.opts.tableResizerOffset);

          // Show table resizer.
          $resizer.show();
        }

        // Hide resizer when the mouse moves away from the cell's border.
        else {
          if (editor.core.sameInstance($resizer)) _hideResizer();
        }
      }

      // Hide resizer if mouse is no longer over it.
      else if ($resizer && $tag_under.get(0) != $resizer.get(0) && $tag_under.parent().get(0) != $resizer.get(0)) {
        if (editor.core.sameInstance($resizer))  _hideResizer();
      }
    }

    /*
     * Show the insert column helper button.
     */
    function _showInsertColHelper (e, table) {
      if (editor.$box.find('.fr-line-breaker').is(':visible')) return false;

      // Insert Helper.
      if (!$insert_helper) _initInsertHelper();

      editor.$box.append($insert_helper);
      $insert_helper.data('instance', editor);

      var $table = $(table);
      var $row = $table.find('tr:first');

      var mouseX = e.pageX;

      var left = 0;
      var top = 0;

      if (editor.opts.iframe) {
        left += editor.$iframe.offset().left - $(editor.o_win).scrollLeft();
        top += editor.$iframe.offset().top - $(editor.o_win).scrollTop();
      }

      // Check where the column should be inserted.
      var btn_width;
      $row.find('th, td').each (function () {
        var $td = $(this);

        // Insert before this td.
        if ($td.offset().left <= mouseX && mouseX < $td.offset().left + $td.outerWidth() / 2) {
          btn_width = parseInt($insert_helper.find('a').css('width'), 10);

          $insert_helper.css('top', top + $td.offset().top - editor.win.pageYOffset - btn_width - 5);
          $insert_helper.css('left', left + $td.offset().left - editor.win.pageXOffset - btn_width / 2);
          $insert_helper.data('selected-cell', $td);
          $insert_helper.data('position', 'before');
          $insert_helper.addClass('fr-visible');

          return false;

        // Insert after this td.
        } else if ($td.offset().left + $td.outerWidth() / 2 <= mouseX && mouseX < $td.offset().left + $td.outerWidth()) {
          btn_width = parseInt($insert_helper.find('a').css('width'), 10);

          $insert_helper.css('top', top + $td.offset().top - editor.win.pageYOffset - btn_width - 5);
          $insert_helper.css('left', left + $td.offset().left + $td.outerWidth() - editor.win.pageXOffset - btn_width / 2);
          $insert_helper.data('selected-cell', $td);
          $insert_helper.data('position', 'after');
          $insert_helper.addClass('fr-visible');

          return false;
        }
      });
    }

    /*
     * Show the insert row helper button.
     */
    function _showInsertRowHelper (e, table) {
      if (editor.$box.find('.fr-line-breaker').is(':visible')) return false;

      if (!$insert_helper) _initInsertHelper();

      editor.$box.append($insert_helper);
      $insert_helper.data('instance', editor);

      var $table = $(table);
      var mouseY = e.pageY;

      var left = 0;
      var top = 0;
      if (editor.opts.iframe) {
        left += editor.$iframe.offset().left - $(editor.o_win).scrollLeft();
        top += editor.$iframe.offset().top - $(editor.o_win).scrollTop();
      }

      // Check where the row should be inserted.
      var btn_width;
      $table.find('tr').each (function () {
        var $tr = $(this);

        // Insert above this tr.
        if ($tr.offset().top <= mouseY && mouseY < $tr.offset().top + $tr.outerHeight() / 2) {
          btn_width = parseInt($insert_helper.find('a').css('width'), 10);

          $insert_helper.css('top', top + $tr.offset().top - editor.win.pageYOffset - btn_width / 2);
          $insert_helper.css('left', left + $tr.offset().left - editor.win.pageXOffset - btn_width - 5);
          $insert_helper.data('selected-cell', $tr.find('td:first'));
          $insert_helper.data('position', 'above');
          $insert_helper.addClass('fr-visible');

          return false;

        // Insert below this tr.
        } else if ($tr.offset().top + $tr.outerHeight() / 2 <= mouseY && mouseY < $tr.offset().top + $tr.outerHeight()) {
          btn_width = parseInt($insert_helper.find('a').css('width'), 10);

          $insert_helper.css('top', top + $tr.offset().top + $tr.outerHeight() - editor.win.pageYOffset - btn_width / 2);
          $insert_helper.css('left', left + $tr.offset().left - editor.win.pageXOffset - btn_width - 5);
          $insert_helper.data('selected-cell', $tr.find('td:first'));
          $insert_helper.data('position', 'below');
          $insert_helper.addClass('fr-visible');

          return false;
        }
      });
    }

    /*
     * Check if should show the insert column / row helper button.
     */
    function _insertHelper (e, tag_under) {
      // Don't show the insert helper if there are table cells selected.
      if (selectedCells().length === 0) {
        var i;
        var tag_below;
        var tag_right;

        // Tag is the editor element or body (inline toolbar). Look for closest tag bellow and at the right.
        if (tag_under && (tag_under.tagName == 'HTML' || tag_under.tagName == 'BODY' || editor.node.isElement(tag_under))) {
          // Look 1px down until a table tag is found or the insert helper offset is reached.
          for (i = 1; i <= editor.opts.tableInsertHelperOffset; i++) {
            // Look for tag below.
            tag_below = editor.doc.elementFromPoint(e.pageX - editor.win.pageXOffset, e.pageY - editor.win.pageYOffset + i);

            // We're on tooltip.
            if ($(tag_below).hasClass('fr-tooltip')) return true;

            // We found a tag bellow.
            if (tag_below && ((tag_below.tagName == 'TH' || tag_below.tagName == 'TD' || tag_below.tagName == 'TABLE') && ($(tag_below).parents('.fr-wrapper').length || editor.opts.iframe))) {
              // Show the insert column helper button.
              _showInsertColHelper (e, tag_below.closest('table'));
              return true;
            }

            // Look for tag at the right.
            tag_right = editor.doc.elementFromPoint(e.pageX - editor.win.pageXOffset + i, e.pageY - editor.win.pageYOffset);

            // We're on tooltip.
            if ($(tag_right).hasClass('fr-tooltip')) return true;

            // We found a tag at the right.
            if (tag_right && ((tag_right.tagName == 'TH' || tag_right.tagName == 'TD' || tag_right.tagName == 'TABLE') && ($(tag_right).parents('.fr-wrapper').length || editor.opts.iframe))) {
              // Show the insert row helper button.
              _showInsertRowHelper (e, tag_right.closest('table'));
              return true;
            }
          }
        }

        // Hide insert helper.
        if (editor.core.sameInstance($insert_helper)) {
          _hideInsertHelper();
        }
      }
    }

    /*
     * Check tag under the mouse on mouse move.
     */
    function _tagUnder (e) {
      mouseMoveTimer = null;

      // The tag under the mouse cursor.
      var tag_under = editor.doc.elementFromPoint(e.pageX - editor.win.pageXOffset, e.pageY - editor.win.pageYOffset);

      // Place table resizer if necessary.
      if (!editor.popups.areVisible() || (editor.popups.areVisible() && editor.popups.isVisible('table.edit'))) {
        _placeResizer(e, tag_under);
      }

      // Show the insert column / row helper button.
      if (!editor.popups.areVisible() && !(editor.$tb.hasClass('fr-inline') && editor.$tb.is(':visible'))) {
        _insertHelper(e, tag_under);
      }
    }

    /*
     * Repositon the resizer if the user scrolls while resizing.
     */
    function _repositionResizer () {
      if (resizingFlag) {
        var $table = $resizer.data('table');
        var top = $table.offset().top - editor.win.pageYOffset;

        if (editor.opts.iframe) {
          top += editor.$iframe.offset().top - $(editor.o_win).scrollTop();
        }

        $resizer.css('top', top);
      }
    }

    /*
     * Resize table method.
     */
    function _resize () {
      // Resizer initial position.
      var initial_positon = $resizer.data('origin');

      // Resizer release position.
      var release_position = $resizer.data('release-position');

      // Do resize only if the resizer's position has changed.
      if (initial_positon !== release_position) {
        // Columns that have to be resized.
        var first = $resizer.data('first');
        var second = $resizer.data('second');

        var $table = $resizer.data('table');
        var table_width = $table.outerWidth();

        // Resize columns and not the table.
        if (first !== null && second !== null) {
          // Create a table map.
          var map = _tableMap($table);

          // Got through all cells on these columns and get their initial width.
          var first_widths = [];
          var first_percentages = [];
          var second_widths = [];
          var second_percentages = [];
          var i;
          var $first_cell;
          var $second_cell;

          // We must do this before updating widths.
          for (i = 0; i < map.length; i++) {
            $first_cell = $(map[i][first]);
            $second_cell = $(map[i][second]);

            // Widths in px.
            first_widths[i] = $first_cell.outerWidth();
            second_widths[i] = $second_cell.outerWidth();

            // Widths in percentages.
            first_percentages[i] = first_widths[i] / table_width * 100;
            second_percentages[i] = second_widths[i] / table_width * 100;
          }

          // Got through all cells on these columns and update their widths.
          for (i = 0; i < map.length; i++) {
            $first_cell = $(map[i][first]);
            $second_cell = $(map[i][second]);

            // New percentage for the first cell.
            var first_cell_percentage = (first_percentages[i] * (first_widths[i] + release_position - initial_positon) / first_widths[i]).toFixed(4);

            $first_cell.css('width', first_cell_percentage + '%');
            $second_cell.css('width', (first_percentages[i] + second_percentages[i] - first_cell_percentage).toFixed(4) + '%');
          }
        }

        // Resize the table.
        else {
          var $table_parent = $table.parent();
          var table_percentage = table_width / $table_parent.width() * 100;
          var width;

          if (first == null) {
            width = (table_width - release_position + initial_positon) / table_width * table_percentage;
          } else {
            width = (table_width + release_position - initial_positon) / table_width * table_percentage;
          }

          $table.css('width', Math.round(width).toFixed(4) + '%');
        }
      }

      // Clear resizer data.
      $resizer.removeData('origin');
      $resizer.removeData('release-position');
      $resizer.removeData('first');
      $resizer.removeData('second');
      $resizer.removeData('table');

      editor.undo.saveStep();
    }

    /*
     * Get the width of the column. (columns may have colspan)
     */
    function _columnWidth (col, map) {
      var i;
      var width = $(map[0][col]).outerWidth();

      for (i = 1; i < map.length; i++) {
        width = Math.min(width, $(map[i][col]).outerWidth());
      }

      return width;
    }

    /*
     * Get the width of the columns between specified indexes.
     */
    function _columnsWidth(col1, col2, map) {
      var i;
      var width = 0;

      // Sum all columns widths.
      for (i = col1; i <= col2; i++) {
        width += _columnWidth(i, map);
      }

      return width;
    }

    /*
     * Set mouse timer to improve performance.
     */
    function _mouseMove (e) {
      // Prevent selecting text when we have cells selected.
      if (selectedCells().length > 1 && mouseDownFlag) {
        _clearSelection();
      }

      // Reset or set timer.
      if (mouseDownFlag === false && mouseDownCellFlag === false && resizingFlag === false) {
        if (mouseMoveTimer) {
          clearTimeout(mouseMoveTimer);
        }

        // Check tag under in order to place the table resizer or insert helper button.
        mouseMoveTimer = setTimeout(_tagUnder, 30, e);

      // Move table resizer.
      } else if (resizingFlag) {
        // Cursor position.
        var pos = e.pageX - editor.win.pageXOffset;

        if (editor.opts.iframe) {
          pos += editor.$iframe.offset().left;
        }

        // Left and right limits.
        var left_limit = $resizer.data('max-left');
        var right_limit = $resizer.data('max-right');

        // Cursor is between the left and right limits.
        if (pos >= left_limit && pos <= right_limit) {
          $resizer.css('left', pos - editor.opts.tableResizerOffset);

        // Cursor has exceeded the left limit. Don't update if it already has the correct value.
        } else if (pos < left_limit && parseFloat($resizer.css('left'), 10) > left_limit - editor.opts.tableResizerOffset) {
          $resizer.css('left', left_limit - editor.opts.tableResizerOffset);

        // Cursor has exceeded the right limit. Don't update if it already has the correct value.
        } else if (pos > right_limit && parseFloat($resizer.css('left'), 10) < right_limit - editor.opts.tableResizerOffset) {
          $resizer.css('left', right_limit - editor.opts.tableResizerOffset);
        }
      } else if (mouseDownFlag) {
        _hideInsertHelper();
      }
    }

    /*
     * Place selection markers in a table cell.
     */
    function _addMarkersInCell ($cell) {
      if (editor.node.isEmpty($cell.get(0))) {
        $cell.prepend($.FE.MARKERS);
      }
      else {
        $cell.prepend($.FE.START_MARKER).append($.FE.END_MARKER);
      }
    }

    /*
     * Use TAB key to navigate through cells.
     */
    function _useTab (e) {
      var key_code = e.which;

      if (key_code == $.FE.KEYCODE.TAB && editor.opts.tabSpaces === 0) {
        // Get starting cell.
        var $cell;

        if (selectedCells().length > 0) {
          $cell = editor.$el.find('.fr-selected-cell:last')
        }
        else {
          var cell = editor.selection.element();

          if (cell.tagName == 'TD' || cell.tagName == 'TH') {
            $cell = $(cell);
          }
          else if ($(cell).closest('td').length > 0) {
            $cell = $(cell).closest('td');
          }
          else if ($(cell).closest('th').length > 0) {
            $cell = $(cell).closest('th');
          }
        }

        if ($cell) {
          e.preventDefault();

          _stopEdit();

          // Go backwards.
          if (e.shiftKey) {
            // Go to previous cell.
            if ($cell.prev().length > 0) {
              _addMarkersInCell($cell.prev());
            }

            // Go to prev row, last cell.
            else if ($cell.closest('tr').length > 0 && $cell.closest('tr').prev().length > 0) {
              _addMarkersInCell($cell.closest('tr').prev().find('td:last'));
            }

            // Go in THEAD, last cell.
            else if ($cell.closest('tbody').length > 0 && $cell.closest('table').find('thead tr').length > 0) {
              _addMarkersInCell($cell.closest('table').find('thead tr th:last'));
            }
          }

          // Go forward.
          else {
            // Go to next cell.
            if ($cell.next().length > 0) {
              _addMarkersInCell($cell.next());
            }

            // Go to next row, first cell.
            else if ($cell.closest('tr').length > 0 && $cell.closest('tr').next().length > 0) {
              _addMarkersInCell($cell.closest('tr').next().find('td:first'));
            }

            // Cursor is in THEAD. Go to next row in TBODY
            else if ($cell.closest('thead').length > 0 && $cell.closest('table').find('tbody tr').length > 0) {
              _addMarkersInCell($cell.closest('table').find('tbody tr td:first'));
            }

            // Add new row.
            else {
              $cell.addClass('fr-selected-cell');
              insertRow('below');
              _removeSelection();

              _addMarkersInCell($cell.closest('tr').next().find('td:first'));
            }
          }

          // Update cursor position.
          editor.selection.restore();
        }
      }
    }

    /*
     * Initilize insert helper.
     */
    function _initInsertHelper () {
      // Append insert helper HTML to editor wrapper.
      if (!editor.shared.$ti_helper) {
        editor.shared.$ti_helper = $('<div class="fr-insert-helper"><a class="fr-floating-btn" role="button" tabindex="-1" title="' + editor.language.translate('Insert') + '"><svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M22,16.75 L16.75,16.75 L16.75,22 L15.25,22.000 L15.25,16.75 L10,16.75 L10,15.25 L15.25,15.25 L15.25,10 L16.75,10 L16.75,15.25 L22,15.25 L22,16.75 Z"/></svg></a></div>');

        // Click on insert helper.
        editor.events.bindClick(editor.shared.$ti_helper, 'a', function () {
          var $td = $insert_helper.data('selected-cell');
          var position = $insert_helper.data('position');

          var inst = $insert_helper.data('instance') || editor;

          if (position == 'before') {
            $td.addClass('fr-selected-cell');
            inst.table.insertColumn(position);
            $td.removeClass('fr-selected-cell');

          } else if (position == 'after') {
            $td.addClass('fr-selected-cell');
            inst.table.insertColumn(position);
            $td.removeClass('fr-selected-cell');

          } else if (position == 'above') {
            $td.addClass('fr-selected-cell');
            inst.table.insertRow(position);
            $td.removeClass('fr-selected-cell');

          } else if (position == 'below') {
            $td.addClass('fr-selected-cell');
            inst.table.insertRow(position);
            $td.removeClass('fr-selected-cell');
          }

          // Hide the insert helper so it will reposition.
          _hideInsertHelper();
        });

        // Editor destroy.
        editor.events.on('shared.destroy', function () {
          editor.shared.$ti_helper.html('').removeData().remove();
          editor.shared.$ti_helper = null;
        }, true);

        // Prevent the insert helper hide when mouse is over it.
        editor.events.$on(editor.shared.$ti_helper, 'mousemove', function (e) {
          e.stopPropagation();
        }, true);

        // Hide the insert helper if the page is scrolled.
        editor.events.$on($(editor.o_win), 'scroll', function () {
          _hideInsertHelper();
        }, true);

        editor.events.$on(editor.$wp, 'scroll', function () {
          _hideInsertHelper();
        }, true);
      }

      $insert_helper = editor.shared.$ti_helper;

      editor.events.on('destroy', function () {
        $insert_helper = null;
      });

      // Table insert helper tooltip.
      editor.tooltip.bind(editor.$box, '.fr-insert-helper > a.fr-floating-btn');
    }

    /**
     * Destroy
     */
    function _destroy () {
      mouseDownCell = null;
      clearTimeout(mouseMoveTimer);
    }

    /*
     * Go back to the table edit popup.
     */
    function back () {
      if (selectedCells().length > 0) {
        _showEditPopup();
      }
      else {
        editor.popups.hide('table.insert');
        editor.toolbar.showInline();
      }
    }

    /**
     * Return selected cells.
     */
    function selectedCells () {
      return editor.$el.get(0).querySelectorAll('.fr-selected-cell');
    }

    /**
     * Return selected table.
     */
    function selectedTable () {
      var cells = selectedCells();
      if (cells.length) {
        var cell = cells[0];
        while (cell && cell.tagName != 'TABLE' && cell.parentNode != editor.$el.get(0)) {
          cell = cell.parentNode;
        }

        if (cell && cell.tagName == 'TABLE') return $(cell);
        return $([]);
      }
    }

    /*
     * Init table.
     */
    function _init () {
      if (!editor.$wp) return false;

      // Do cell selection only on desktops (no touch devices)
      if (!editor.helpers.isMobile()) {
        // Remember if mouse is clicked.
        mouseDownFlag = false;
        mouseDownCellFlag = false;
        resizingFlag = false;

        // Mouse is down in a table cell.
        editor.events.$on(editor.$el, 'mousedown', _mouseDown);

        // Deselect table cells when user clicks on an image.
        editor.popups.onShow('image.edit', function () {
          _removeSelection();
          mouseDownFlag = false;
          mouseDownCellFlag = false;
        });

        // Deselect table cells when user clicks on a link.
        editor.popups.onShow('link.edit', function () {
          _removeSelection();
          mouseDownFlag = false;
          mouseDownCellFlag = false;
        });

        // Deselect table cells when a command is run.
        editor.events.on('commands.mousedown', function ($btn) {
          if ($btn.parents('.fr-toolbar').length > 0) {
            _removeSelection();
          }
        });

        // Mouse enter's a table cell.
        editor.events.$on(editor.$el, 'mouseenter', 'th, td', _mouseEnter);

        // Mouse is no longer pressed.
        editor.events.$on(editor.$win, 'mouseup', _mouseUp);

        // Iframe mouseup.
        if (editor.opts.iframe) {
          editor.events.$on($(editor.o_win), 'mouseup', _mouseUp);
        }

        // Moving cursor with arrow keys.
        editor.events.$on(editor.$el, 'keydown', _usingArrows);

        // Check tags under the mouse to see if the resizer needs to be shown.
        editor.events.$on(editor.$win, 'mousemove', _mouseMove);

        // Update resizer's position on scroll.
        editor.events.$on($(editor.o_win), 'scroll', _repositionResizer);

        // Reposition table edit popup when table cell content changes.
        editor.events.on('contentChanged', function () {
          if (selectedCells().length > 0) {
            _showEditPopup();

            // Make sure we reposition on image load.
            editor.$el.find('img').on('load.selected-cells', function () {
              $(this).off('load.selected-cells');
              if (selectedCells().length > 0) {
                _showEditPopup();
              }
            });
          }
        });

        // Reposition table edit popup on window resize.
        editor.events.$on($(editor.o_win), 'resize', function () {
          _removeSelection();
        });

        // Prevent backspace from doing browser back.
        editor.events.on('keydown', function (e) {
          var selected_cells = selectedCells();
          if (selected_cells.length > 0) {
            if (e.which == $.FE.KEYCODE.ESC) {
              if (editor.popups.isVisible('table.edit')) {
                _removeSelection();
                editor.popups.hide('table.edit');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                selected_cells = [];
                return false;
              }
            }

            if (selected_cells.length > 1) {
              e.preventDefault();
              selected_cells = [];
              return false;
            }
          }

          selected_cells = [];
        }, true);

        // Clean selected cells.
        var c_selected_cells = [];
        editor.events.on('html.beforeGet', function () {
          c_selected_cells = selectedCells();
          for (var i = 0; i < c_selected_cells.length; i++) {
            c_selected_cells[i].className = (c_selected_cells[i].className || '').replace(/fr-selected-cell/g, '');
          }
        });

        editor.events.on('html.get', function (html) {
          html = html.replace(/<(td|th)((?:[\w\W]*?)) class=""((?:[\w\W]*?))>((?:[\w\W]*?))<\/(td|th)>/g, '<$1$2$3>$4</$5>');

          return html;
        });

        editor.events.on('html.afterGet', function () {
          for (var i = 0; i < c_selected_cells.length; i++) {
            c_selected_cells[i].className = (c_selected_cells[i].className ? c_selected_cells[i].className + ' ' : '') + 'fr-selected-cell';
          }
          c_selected_cells = [];
        });

        _initInsertPopup(true);
        _initEditPopup(true);
      }

      // Tab in cell
      editor.events.on('keydown', _useTab, true);

      editor.events.on('destroy', _destroy);
    }

    return {
      _init: _init,
      insert: insert,
      remove: remove,
      insertRow: insertRow,
      deleteRow: deleteRow,
      insertColumn: insertColumn,
      deleteColumn: deleteColumn,
      mergeCells: mergeCells,
      splitCellVertically: splitCellVertically,
      splitCellHorizontally: splitCellHorizontally,
      addHeader: addHeader,
      removeHeader: removeHeader,
      setBackground: setBackground,
      showInsertPopup: _showInsertPopup,
      showEditPopup: _showEditPopup,
      showColorsPopup: _showColorsPopup,
      back: back,
      verticalAlign: verticalAlign,
      horizontalAlign: horizontalAlign,
      applyStyle: applyStyle,
      selectedTable: selectedTable,
      selectedCells: selectedCells
    }
  };

  // Insert table button.
  $.FE.DefineIcon('insertTable', { NAME: 'table' });
  $.FE.RegisterCommand('insertTable', {
    title: 'Insert Table',
    undo: false,
    focus: true,
    refreshOnCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('table.insert')) {
        this.table.showInsertPopup();
      }
      else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('table.insert');
      }
    },
    plugin: 'table'
  });

  $.FE.RegisterCommand('tableInsert', {
    callback: function (cmd, rows, cols) {
      this.table.insert(rows, cols);
      this.popups.hide('table.insert');
    }
  })

  // Table header button.
  $.FE.DefineIcon('tableHeader', { NAME: 'header' })
  $.FE.RegisterCommand('tableHeader', {
    title: 'Table Header',
    focus: false,
    callback: function () {
      var $btn = this.popups.get('table.edit').find('.fr-command[data-cmd="tableHeader"]');

      // If button is active the table has a header,
      if ($btn.hasClass('fr-active')) {
        this.table.removeHeader();
      }

      // Add table header.
      else {
        this.table.addHeader();
      }
    },
    refresh: function ($btn) {
      var $table = this.table.selectedTable();

      if ($table.length > 0) {
        // If table doesn't have a header.
        if ($table.find('th').length === 0) {
          $btn.removeClass('fr-active');
        }

        // Header button is active if table has header.
        else {
          $btn.addClass('fr-active');
        }
      }
    }
  });

  // Table rows action dropdown.
  $.FE.DefineIcon('tableRows', { NAME: 'bars' })
  $.FE.RegisterCommand('tableRows', {
    type: 'dropdown',
    focus: false,
    title: 'Row',
    options: {
      above: 'Insert row above',
      below: 'Insert row below',
      'delete': 'Delete row'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FE.COMMANDS.tableRows.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="tableRows" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      if (val == 'above' || val == 'below') {
        this.table.insertRow(val);
      } else {
        this.table.deleteRow();
      }
    }
  });

  // Table columns action dropdown.
  $.FE.DefineIcon('tableColumns', { NAME: 'bars fa-rotate-90' })
  $.FE.RegisterCommand('tableColumns', {
    type: 'dropdown',
    focus: false,
    title: 'Column',
    options: {
      before: 'Insert column before',
      after: 'Insert column after',
      'delete': 'Delete column'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FE.COMMANDS.tableColumns.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="tableColumns" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      if (val == 'before' || val == 'after') {
        this.table.insertColumn(val);
      } else {
        this.table.deleteColumn();
      }
    }
  });

  // Table cells action dropdown.
  $.FE.DefineIcon('tableCells', { NAME: 'square-o' })
  $.FE.RegisterCommand('tableCells', {
    type: 'dropdown',
    focus: false,
    title: 'Cell',
    options: {
      merge: 'Merge cells',
      'vertical-split': 'Vertical split',
      'horizontal-split': 'Horizontal split'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FE.COMMANDS.tableCells.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="tableCells" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      if (val == 'merge') {
        this.table.mergeCells();
      }
      else if (val == 'vertical-split') {
        this.table.splitCellVertically();
      }
      // 'horizontal-split'
      else {
        this.table.splitCellHorizontally();
      }
    },
    refreshOnShow: function ($btn, $dropdown) {
      // More than one cell selected.
      if (this.$el.find('.fr-selected-cell').length > 1) {
        $dropdown.find('a[data-param1="vertical-split"]').addClass('fr-disabled');
        $dropdown.find('a[data-param1="horizontal-split"]').addClass('fr-disabled');
        $dropdown.find('a[data-param1="merge"]').removeClass('fr-disabled');
      }

      // Only one selected cell.
      else {
        $dropdown.find('a[data-param1="merge"]').addClass('fr-disabled');
        $dropdown.find('a[data-param1="vertical-split"]').removeClass('fr-disabled');
        $dropdown.find('a[data-param1="horizontal-split"]').removeClass('fr-disabled');
      }
    }
  });

  // Remove table button.
  $.FE.DefineIcon('tableRemove', { NAME: 'trash' })
  $.FE.RegisterCommand('tableRemove', {
    title: 'Remove Table',
    focus: false,
    callback: function () {
      this.table.remove();
    }
  });

  // Table styles.
  $.FE.DefineIcon('tableStyle', { NAME: 'paint-brush' })
  $.FE.RegisterCommand('tableStyle', {
    title: 'Table Style',
    type: 'dropdown',
    focus: false,
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.tableStyles;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="tableStyle" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.table.applyStyle(val, this.$el.find('.fr-selected-cell').closest('table'), this.opts.tableMultipleStyles, this.opts.tableStyles);
    },
    refreshOnShow: function ($btn, $dropdown) {
      var $table = this.$el.find('.fr-selected-cell').closest('table');

      if ($table) {
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $table.hasClass(cls));
        })
      }
    }
  });

  // Table cell background color button.
  $.FE.DefineIcon('tableCellBackground', { NAME: 'tint' })
  $.FE.RegisterCommand('tableCellBackground', {
    title: 'Cell Background',
    focus: false,
    callback: function () {
      this.table.showColorsPopup();
    }
  });

  // Select table cell background color command.
  $.FE.RegisterCommand('tableCellBackgroundColor', {
    undo: true,
    focus: false,
    callback: function (cmd, val) {
      this.table.setBackground(val);
    }
  });

  // Table back.
  $.FE.DefineIcon('tableBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('tableBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    callback: function () {
      this.table.back();
    },
    refresh: function ($btn) {
      if (this.table.selectedCells().length === 0 && !this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  // Table vertical align dropdown.
  $.FE.DefineIcon('tableCellVerticalAlign', { NAME: 'arrows-v' })
  $.FE.RegisterCommand('tableCellVerticalAlign', {
    type: 'dropdown',
    focus: false,
    title: 'Vertical Align',
    options: {
      Top: 'Align Top',
      Middle: 'Align Middle',
      Bottom: 'Align Bottom'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FE.COMMANDS.tableCellVerticalAlign.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="tableCellVerticalAlign" data-param1="' + val.toLowerCase() + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(val) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.table.verticalAlign(val);
    },
    refreshOnShow: function ($btn, $dropdown) {
      $dropdown.find('.fr-command[data-param1="' + this.$el.find('.fr-selected-cell').css('vertical-align') + '"]').addClass('fr-active');
    }
  });

  // Table horizontal align dropdown.
  $.FE.DefineIcon('tableCellHorizontalAlign', { NAME: 'align-left' });
  $.FE.DefineIcon('align-left', { NAME: 'align-left' });
  $.FE.DefineIcon('align-right', { NAME: 'align-right' });
  $.FE.DefineIcon('align-center', { NAME: 'align-center' });
  $.FE.DefineIcon('align-justify', { NAME: 'align-justify' });
  $.FE.RegisterCommand('tableCellHorizontalAlign', {
    type: 'dropdown',
    focus: false,
    title: 'Horizontal Align',
    options: {
      left: 'Align Left',
      center: 'Align Center',
      right: 'Align Right',
      justify: 'Align Justify'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FE.COMMANDS.tableCellHorizontalAlign.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command fr-title" data-cmd="tableCellHorizontalAlign" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.icon.create('align-' + val) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.table.horizontalAlign(val);
    },
    refresh: function ($btn) {
      var selected_cells = this.table.selectedCells();

      if (selected_cells.length) {
        $btn.find('> *:first').replaceWith(this.icon.create('align-' + this.helpers.getAlignment($(selected_cells[0]))));
      }
    },
    refreshOnShow: function ($btn, $dropdown) {
      $dropdown.find('.fr-command[data-param1="' + this.helpers.getAlignment(this.$el.find('.fr-selected-cell:first')) + '"]').addClass('fr-active');
    }
  });

  // Table cell styles.
  $.FE.DefineIcon('tableCellStyle', { NAME: 'magic' })
  $.FE.RegisterCommand('tableCellStyle', {
    title: 'Cell Style',
    type: 'dropdown',
    focus: false,
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  this.opts.tableCellStyles;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command" data-cmd="tableCellStyle" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.language.translate(options[val]) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.table.applyStyle(val, this.$el.find('.fr-selected-cell'), this.opts.tableCellMultipleStyles, this.opts.tableCellStyles);
    },
    refreshOnShow: function ($btn, $dropdown) {
      var $cell = this.$el.find('.fr-selected-cell:first');

      if ($cell) {
        $dropdown.find('.fr-command').each (function () {
          var cls = $(this).data('param1');
          $(this).toggleClass('fr-active', $cell.hasClass(cls));
        })
      }
    }
  });


  'use strict';

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {

  });

  $.FE.URLRegEx = /(\s|^|>)((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+(\.[a-zA-Z]{2,3})?(:\d*)?(\/[^\s<]*)?)(\s|$|<)/gi;

  $.FE.PLUGINS.url = function (editor) {

    function _convertURLS (contents) {
      // All content zones.
      contents.each (function () {
        if (this.tagName == 'IFRAME') return;

        // Text node.
        if (this.nodeType == 3) {
          var text = this.textContent.replace(/&nbsp;/gi, '');

          // Check if text is URL.
          if ($.FE.URLRegEx.test(text)) {
            // Convert it to A.
            $(this).before(text.replace($.FE.URLRegEx, '$1<a href="$2">$2</a>$7'));

            $(this).remove();
          }
        }

        // Other type of node.
        else if (this.nodeType == 1 && ['A', 'BUTTON', 'TEXTAREA'].indexOf(this.tagName) < 0) {
          // Convert urls inside it.
          _convertURLS(editor.node.contents(this));
        }
      })
    }

    /*
     * Initialize.
     */
    function _init () {
      editor.events.on('paste.afterCleanup', function (html) {
        if ($.FE.URLRegEx.test(html)) {
          return html.replace($.FE.URLRegEx, '$1<a href="$2">$2</a>$7')
        }
      });

      editor.events.on('keyup', function (e) {
        var keycode = e.which;
        if (keycode == $.FE.KEYCODE.ENTER || keycode == $.FE.KEYCODE.SPACE) {
          _convertURLS(editor.node.contents(editor.$el.get(0)));
        }
      });

      editor.events.on('keydown', function (e) {
        var keycode = e.which;

        if (keycode == $.FE.KEYCODE.ENTER) {
          var el = editor.selection.element();

          if ((el.tagName == 'A' || $(el).parents('a').length) && editor.selection.info(el).atEnd) {
            e.stopImmediatePropagation();

            if (el.tagName !== 'A') el = $(el).parents('a')[0];
            $(el).after('&nbsp;' + $.FE.MARKERS);
            editor.selection.restore();

            return false;
          }
        }
      });
    }

    return {
      _init: _init
    }
  }


  'use strict';

  $.extend($.FE.POPUP_TEMPLATES, {
    'video.insert': '[_BUTTONS_][_BY_URL_LAYER_][_EMBED_LAYER_]',
    'video.edit': '[_BUTTONS_]',
    'video.size': '[_BUTTONS_][_SIZE_LAYER_]'
  })

  $.extend($.FE.DEFAULTS, {
    videoInsertButtons: ['videoBack', '|', 'videoByURL', 'videoEmbed'],
    videoEditButtons: ['videoDisplay', 'videoAlign', 'videoSize', 'videoRemove'],
    videoResize: true,
    videoSizeButtons: ['videoBack', '|'],
    videoSplitHTML: false,
    videoTextNear: true,
    videoDefaultAlign: 'center',
    videoDefaultDisplay: 'block',
    videoMove: true
  });

  $.FE.VIDEO_PROVIDERS = [
    {
      test_regex: /^.*((youtu.be)|(youtube.com))\/((v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))?\??v?=?([^#\&\?]*).*/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/)?([0-9a-zA-Z_\-]+)(.+)?/g,
      url_text: '//www.youtube.com/embed/$1',
      html: '<iframe width="640" height="360" src="{url}?wmode=opaque" frameborder="0" allowfullscreen></iframe>'
    },
    {
      test_regex: /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/(?:channels\/[A-z]+\/|groups\/[A-z]+\/videos\/)?(.+)/g,
      url_text: '//player.vimeo.com/video/$1',
      html: '<iframe width="640" height="360" src="{url}" frameborder="0" allowfullscreen></iframe>'
    },
    {
      test_regex: /^.+(dailymotion.com|dai.ly)\/(video|hub)?\/?([^_]+)[^#]*(#video=([^_&]+))?/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:dailymotion\.com|dai\.ly)\/(?:video|hub)?\/?(.+)/g,
      url_text: '//www.dailymotion.com/embed/video/$1',
      html: '<iframe width="640" height="360" src="{url}" frameborder="0" allowfullscreen></iframe>'
    },
    {
      test_regex: /^.+(screen.yahoo.com)\/[^_&]+/,
      url_regex: '',
      url_text: '',
      html: '<iframe width="640" height="360" src="{url}?format=embed" frameborder="0" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true" allowtransparency="true"></iframe>'
    },
    {
      test_regex: /^.+(rutube.ru)\/[^_&]+/,
      url_regex: /(?:https?:\/\/)?(?:www\.)?(?:rutube\.ru)\/(?:video)?\/?(.+)/g,
      url_text: '//rutube.ru/play/embed/$1',
      html: '<iframe width="640" height="360" src="{url}" frameborder="0" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true" allowtransparency="true"></iframe>'
    }
  ];

  $.FE.VIDEO_EMBED_REGEX = /^\W*((<iframe.*><\/iframe>)|(<embed.*>))\W*$/i;

  $.FE.PLUGINS.video = function (editor) {
    var $overlay;
    var $handler;
    var $video_resizer;
    var $current_video;

    /**
     * Refresh the image insert popup.
     */
    function _refreshInsertPopup () {
      var $popup = editor.popups.get('video.insert');

      var $url_input = $popup.find('.fr-video-by-url-layer input');
      $url_input.val('').trigger('change');

      var $embed_area = $popup.find('.fr-video-embed-layer textarea');
      $embed_area.val('').trigger('change');
    }

    /**
     * Show the video insert popup.
     */
    function showInsertPopup () {
      var $btn = editor.$tb.find('.fr-command[data-cmd="insertVideo"]');

      var $popup = editor.popups.get('video.insert');
      if (!$popup) $popup = _initInsertPopup();

      if (!$popup.hasClass('fr-active')) {
        editor.popups.refresh('video.insert');
        editor.popups.setContainer('video.insert', editor.$tb);

        var left = $btn.offset().left + $btn.outerWidth() / 2;
        var top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
        editor.popups.show('video.insert', left, top, $btn.outerHeight());
      }
    }

    /**
     * Show the image edit popup.
     */
    function _showEditPopup () {
      var $popup = editor.popups.get('video.edit');
      if (!$popup) $popup = _initEditPopup();

      editor.popups.setContainer('video.edit', $(editor.opts.scrollableContainer));
      editor.popups.refresh('video.edit');

      var $video_obj = $current_video.find('iframe, embed, video');
      var left = $video_obj.offset().left + $video_obj.outerWidth() / 2;
      var top = $video_obj.offset().top + $video_obj.outerHeight();

      editor.popups.show('video.edit', left, top, $video_obj.outerHeight());
    }

    function _initInsertPopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('video.insert', _refreshInsertPopup);

        return true;
      }

      // Image buttons.
      var video_buttons = '';
      if (editor.opts.videoInsertButtons.length > 1) {
        video_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.videoInsertButtons) + '</div>';
      }

      // Video by url layer.
      var by_url_layer = '';
      if (editor.opts.videoInsertButtons.indexOf('videoByURL') >= 0) {
        by_url_layer = '<div class="fr-video-by-url-layer fr-layer fr-active" id="fr-video-by-url-layer-' + editor.id + '"><div class="fr-input-line"><input type="text" placeholder="http://" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="videoInsertByURL" tabIndex="2">' + editor.language.translate('Insert') + '</button></div></div>'
      }

      // Video embed layer.
      var embed_layer = '';
      if (editor.opts.videoInsertButtons.indexOf('videoEmbed') >= 0) {
        embed_layer = '<div class="fr-video-embed-layer fr-layer" id="fr-video-embed-layer-' + editor.id + '"><div class="fr-input-line"><textarea type="text" placeholder="' + editor.language.translate('Embedded Code') + '" tabIndex="1" rows="5"></textarea></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="videoInsertEmbed" tabIndex="2">' + editor.language.translate('Insert') + '</button></div></div>'
      }

      var template = {
        buttons: video_buttons,
        by_url_layer: by_url_layer,
        embed_layer: embed_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('video.insert', template);

      return $popup;
    }

    /**
     * Show the image upload layer.
     */
    function showLayer (name) {
      var $popup = editor.popups.get('video.insert');

      var left;
      var top;
      if (!$current_video && !editor.opts.toolbarInline) {
        var $btn = editor.$tb.find('.fr-command[data-cmd="insertVideo"]');
        left = $btn.offset().left + $btn.outerWidth() / 2;
        top = $btn.offset().top + (editor.opts.toolbarBottom ? 10 : $btn.outerHeight() - 10);
      }

      if (editor.opts.toolbarInline) {
        // Set top to the popup top.
        top = $popup.offset().top - editor.helpers.getPX($popup.css('margin-top'));

        // If the popup is above apply height correction.
        if ($popup.hasClass('fr-above')) {
          top += $popup.outerHeight();
        }
      }

      // Show the new layer.
      $popup.find('.fr-layer').removeClass('fr-active');
      $popup.find('.fr-' + name + '-layer').addClass('fr-active');

      editor.popups.show('video.insert', left, top, 0);
    }

    /**
     * Refresh the insert by url button.
     */
    function refreshByURLButton ($btn) {
      var $popup = editor.popups.get('video.insert');
      if ($popup.find('.fr-video-by-url-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Refresh the insert embed button.
     */
    function refreshEmbedButton ($btn) {
      var $popup = editor.popups.get('video.insert');
      if ($popup.find('.fr-video-embed-layer').hasClass('fr-active')) {
        $btn.addClass('fr-active');
      }
    }

    /**
     * Insert video embedded object.
     */
    function insert (embedded_code) {
      // Make sure we have focus.
      editor.events.focus(true);
      editor.selection.restore();

      editor.html.insert('<span contenteditable="false" draggable="true" class="fr-jiv fr-video fr-dv' + (editor.opts.videoDefaultDisplay[0]) + (editor.opts.videoDefaultAlign != 'center' ? ' fr-fv' + editor.opts.videoDefaultAlign[0] : '') + '">' + embedded_code + '</span>', false, editor.opts.videoSplitHTML);

      editor.popups.hide('video.insert');

      var $video = editor.$el.find('.fr-jiv');
      $video.removeClass('fr-jiv');

      $video.toggleClass('fr-draggable', editor.opts.videoMove);

      editor.events.trigger('video.inserted', [$video]);
    }

    /**
     * Insert video by URL.
     */
    function insertByURL (link) {
      if (typeof link == 'undefined') {
        var $popup = editor.popups.get('video.insert');
        link = $popup.find('.fr-video-by-url-layer input[type="text"]').val() || '';
      }

      var video = null;
      if (editor.helpers.isURL(link)) {
        for (var i = 0; i < $.FE.VIDEO_PROVIDERS.length; i++) {
          var vp = $.FE.VIDEO_PROVIDERS[i];
          if (vp.test_regex.test(link)) {
            video = link.replace(vp.url_regex, vp.url_text);
            video = vp.html.replace(/\{url\}/, video);
            break;
          }
        }
      }

      if (video) {
        insert(video);
      }
      else {
        editor.events.trigger('video.linkError', [link]);
      }
    }

    /**
     * Insert embedded video.
     */
    function insertEmbed (code) {
      if (typeof code == 'undefined') {
        var $popup = editor.popups.get('video.insert');
        code = $popup.find('.fr-video-embed-layer textarea').val() || '';
      }

      if (code.length === 0 || !$.FE.VIDEO_EMBED_REGEX.test(code)) {
        editor.events.trigger('video.codeError', [code]);
      }
      else {
        insert(code);
      }
    }

    /**
     * Mouse down to start resize.
     */
    function _handlerMousedown (e) {
      // Check if resizer belongs to current instance.
      if (!editor.core.sameInstance($video_resizer)) return true;

      e.preventDefault();
      e.stopPropagation();

      var c_x = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);
      var c_y = e.pageY || (e.originalEvent.touches ? e.originalEvent.touches[0].pageY : null);

      if (!c_x || !c_y) {
        return false;
      }

      if (!editor.undo.canDo()) editor.undo.saveStep();

      $handler = $(this);
      $handler.data('start-x', c_x);
      $handler.data('start-y', c_y);
      $overlay.show();
      editor.popups.hideAll();

      _unmarkExit();
    }

    /**
     * Do resize.
     */
    function _handlerMousemove (e) {
      // Check if resizer belongs to current instance.
      if (!editor.core.sameInstance($video_resizer)) return true;

      if ($handler) {
        e.preventDefault()

        var c_x = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);
        var c_y = e.pageY || (e.originalEvent.touches ? e.originalEvent.touches[0].pageY : null);

        if (!c_x || !c_y) {
          return false;
        }

        var s_x = $handler.data('start-x');
        var s_y = $handler.data('start-y');

        $handler.data('start-x', c_x);
        $handler.data('start-y', c_y);

        var diff_x = c_x - s_x;
        var diff_y = c_y - s_y;

        var $video_obj = $current_video.find('iframe, embed, video');

        var width = $video_obj.width();
        var height = $video_obj.height();
        if ($handler.hasClass('fr-hnw') || $handler.hasClass('fr-hsw')) {
          diff_x = 0 - diff_x;
        }

        if ($handler.hasClass('fr-hnw') || $handler.hasClass('fr-hne')) {
          diff_y = 0 - diff_y;
        }

        $video_obj.css('width', width + diff_x);
        $video_obj.css('height', height + diff_y);
        $video_obj.removeAttr('width');
        $video_obj.removeAttr('height');

        _repositionResizer();
      }
    }

    /**
     * Stop resize.
     */
    function _handlerMouseup (e) {
      // Check if resizer belongs to current instance.
      if (!editor.core.sameInstance($video_resizer)) return true;

      if ($handler && $current_video) {
        if (e) e.stopPropagation();
        $handler = null;
        $overlay.hide();
        _repositionResizer();
        _showEditPopup();

        editor.undo.saveStep();
      }
    }

    /**
     * Create resize handler.
     */
    function _getHandler (pos) {
      return '<div class="fr-handler fr-h' + pos + '"></div>';
    }

    /**
     * Init video resizer.
     */
    function _initResizer () {
      var doc;

      // No shared video resizer.
      if (!editor.shared.$video_resizer) {
        // Create shared video resizer.
        editor.shared.$video_resizer = $('<div class="fr-video-resizer"></div>');
        $video_resizer = editor.shared.$video_resizer;

        // Bind mousedown event shared.
        editor.events.$on($video_resizer, 'mousedown', function (e) {
          e.stopPropagation();
        }, true);

        // video resize is enabled.
        if (editor.opts.videoResize) {
          $video_resizer.append(_getHandler('nw') + _getHandler('ne') + _getHandler('sw') + _getHandler('se'));

          // Add video resizer overlay and set it.
          editor.shared.$vid_overlay = $('<div class="fr-video-overlay"></div>');
          $overlay = editor.shared.$vid_overlay;
          doc = $video_resizer.get(0).ownerDocument;
          $(doc).find('body').append($overlay);
        }
      } else {
        $video_resizer = editor.shared.$video_resizer;
        $overlay = editor.shared.$vid_overlay;

        editor.events.on('destroy', function () {
          $video_resizer.removeClass('fr-active').appendTo($('body'));
        }, true);
      }

      // Shared destroy.
      editor.events.on('shared.destroy', function () {
        $video_resizer.html('').removeData().remove();
        $video_resizer = null;

        if (editor.opts.videoResize) {
          $overlay.remove();
          $overlay = null;
        }
      }, true);

      // Window resize. Exit from edit.
      if (!editor.helpers.isMobile()) {
        editor.events.$on($(editor.o_win), 'resize.video', function () {
          _exitEdit(true);
        });
      }

      // video resize is enabled.
      if (editor.opts.videoResize) {
        doc = $video_resizer.get(0).ownerDocument;

        editor.events.$on($video_resizer, editor._mousedown, '.fr-handler', _handlerMousedown);
        editor.events.$on($(doc), editor._mousemove, _handlerMousemove);
        editor.events.$on($(doc.defaultView || doc.parentWindow), editor._mouseup, _handlerMouseup);

        editor.events.$on($overlay, 'mouseleave', _handlerMouseup);
      }
    }

    /**
     * Reposition resizer.
     */
    function _repositionResizer () {
      if (!$video_resizer) _initResizer();

      (editor.$wp || $(editor.opts.scrollableContainer)).append($video_resizer);
      $video_resizer.data('instance', editor);

      var $video_obj = $current_video.find('iframe, embed, video');

      $video_resizer
        .css('top', (editor.opts.iframe ? $video_obj.offset().top - 1 : $video_obj.offset().top - editor.$wp.offset().top - 1) + editor.$wp.scrollTop())
        .css('left', (editor.opts.iframe ? $video_obj.offset().left - 1 : $video_obj.offset().left - editor.$wp.offset().left - 1) + editor.$wp.scrollLeft())
        .css('width', $video_obj.outerWidth())
        .css('height', $video_obj.height())
        .addClass('fr-active')
    }

    /**
     * Edit video.
     */
    var touchScroll;
    function _edit (e) {
      if (e && e.type == 'touchend' && touchScroll) {
        return true;
      }

      e.preventDefault();
      e.stopPropagation();

      if (editor.edit.isDisabled()) {
        return false;
      }

      // Hide resizer for other instances.
      for (var i = 0; i < $.FE.INSTANCES.length; i++) {
        if ($.FE.INSTANCES[i] != editor) {
          $.FE.INSTANCES[i].events.trigger('video.hideResizer');
        }
      }

      editor.toolbar.disable();

      // Hide keyboard.
      if (editor.helpers.isMobile()) {
        editor.events.disableBlur();
        editor.$el.blur();
        editor.events.enableBlur();
      }

      $current_video = $(this);
      $(this).addClass('fr-active');

      if (editor.opts.iframe) {
        editor.size.syncIframe();
      }

      _repositionResizer();
      _showEditPopup();

      editor.selection.clear();
      editor.button.bulkRefresh();

      editor.events.trigger('image.hideResizer');
    }

    /**
     * Exit edit.
     */
    function _exitEdit (force_exit) {
      if ($current_video && (_canExit() || force_exit === true)) {
        $video_resizer.removeClass('fr-active');

        editor.toolbar.enable();

        $current_video.removeClass('fr-active');
        $current_video = null;

        _unmarkExit();
      }
    }

    editor.shared.vid_exit_flag = false;
    function _markExit () {
      editor.shared.vid_exit_flag = true;
    }

    function _unmarkExit () {
      editor.shared.vid_exit_flag = false;
    }

    function _canExit () {
      return editor.shared.vid_exit_flag;
    }

    /**
     * Init the video events.
     */
    function _initEvents () {
      editor.events.on('mousedown window.mousedown', _markExit);
      editor.events.on('window.touchmove', _unmarkExit);
      editor.events.on('mouseup window.mouseup', _exitEdit);

      editor.events.on('commands.mousedown', function ($btn) {
        if ($btn.parents('.fr-toolbar').length > 0) {
          _exitEdit();
        }
      });

      editor.events.on('blur video.hideResizer commands.undo commands.redo element.dropped', function () {
        _exitEdit(true);
      });
    }

    /**
     * Init the video edit popup.
     */
    function _initEditPopup () {
      // Image buttons.
      var video_buttons = '';
      if (editor.opts.videoEditButtons.length >= 1) {
        video_buttons += '<div class="fr-buttons">';
        video_buttons += editor.button.buildList(editor.opts.videoEditButtons);
        video_buttons += '</div>';
      }

      var template = {
        buttons: video_buttons
      }

      var $popup = editor.popups.create('video.edit', template);

      editor.events.$on(editor.$wp, 'scroll.video-edit', function () {
        if ($current_video && editor.popups.isVisible('video.edit')) {
          _showEditPopup();
        }
      });

      return $popup;
    }

    /**
     * Refresh the size popup.
     */
    function _refreshSizePopup () {
      if ($current_video) {
        var $popup = editor.popups.get('video.size');
        var $video_obj = $current_video.find('iframe, embed, video')
        $popup.find('input[name="width"]').val($video_obj.get(0).style.width || $video_obj.attr('width')).trigger('change');
        $popup.find('input[name="height"]').val($video_obj.get(0).style.height || $video_obj.attr('height')).trigger('change');
      }
    }

    /**
     * Show the size popup.
     */
    function showSizePopup () {
      var $popup = editor.popups.get('video.size');
      if (!$popup) $popup = _initSizePopup();

      editor.popups.refresh('video.size');
      editor.popups.setContainer('video.size', $(editor.opts.scrollableContainer));
      var $video_obj = $current_video.find('iframe, embed, video')
      var left = $video_obj.offset().left + $video_obj.width() / 2;
      var top = $video_obj.offset().top + $video_obj.height();

      editor.popups.show('video.size', left, top, $video_obj.height());
    }

    /**
     * Init the image upload popup.
     */
    function _initSizePopup (delayed) {
      if (delayed) {
        editor.popups.onRefresh('video.size', _refreshSizePopup);

        return true;
      }

      // Image buttons.
      var video_buttons = '';
      video_buttons = '<div class="fr-buttons">' + editor.button.buildList(editor.opts.videoSizeButtons) + '</div>';

      // Size layer.
      var size_layer = '';
      size_layer = '<div class="fr-video-size-layer fr-layer fr-active" id="fr-video-size-layer-' + editor.id + '"><div class="fr-video-group"><div class="fr-input-line"><input type="text" name="width" placeholder="' + editor.language.translate('Width') + '" tabIndex="1"></div><div class="fr-input-line"><input type="text" name="height" placeholder="' + editor.language.translate('Height') + '" tabIndex="1"></div></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="videoSetSize" tabIndex="2">' + editor.language.translate('Update') + '</button></div></div>';

      var template = {
        buttons: video_buttons,
        size_layer: size_layer
      }

      // Set the template in the popup.
      var $popup = editor.popups.create('video.size', template);

      editor.events.$on(editor.$wp, 'scroll', function () {
        if ($current_video && editor.popups.isVisible('video.size')) {
          showSizePopup();
        }
      });

      return $popup;
    }

    /**
     * Align image.
     */
    function align (val) {
      $current_video.removeClass('fr-fvr fr-fvl');
      if (val == 'left') {
        $current_video.addClass('fr-fvl');
      }
      else if (val == 'right') {
        $current_video.addClass('fr-fvr');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the align icon.
     */
    function refreshAlign ($btn) {
      if (!$current_video) return false;

      if ($current_video.hasClass('fr-fvl')) {
        $btn.find('> *:first').replaceWith(editor.icon.create('align-left'));
      }
      else if ($current_video.hasClass('fr-fvr')) {
        $btn.find('> *:first').replaceWith(editor.icon.create('align-right'));
      }
      else {
        $btn.find('> *:first').replaceWith(editor.icon.create('align-justify'));
      }
    }

    /**
     * Refresh the align option from the dropdown.
     */
    function refreshAlignOnShow ($btn, $dropdown) {
      var alignment = 'justify';
      if ($current_video.hasClass('fr-fvl')) {
        alignment = 'left';
      }
      else if ($current_video.hasClass('fr-fvr')) {
        alignment = 'right';
      }

      $dropdown.find('.fr-command[data-param1="' + alignment + '"]').addClass('fr-active');
    }

    /**
     * Align image.
     */
    function display (val) {
      $current_video.removeClass('fr-dvi fr-dvb');
      if (val == 'inline') {
        $current_video.addClass('fr-dvi');
      }
      else if (val == 'block') {
        $current_video.addClass('fr-dvb');
      }

      _repositionResizer();
      _showEditPopup();
    }

    /**
     * Refresh the image display selected option.
     */
    function refreshDisplayOnShow ($btn, $dropdown) {
      var d = 'block';
      if ($current_video.hasClass('fr-dvi')) {
        d = 'inline';
      }

      $dropdown.find('.fr-command[data-param1="' + d + '"]').addClass('fr-active');
    }

    /**
     * Remove current selected video.
     */
    function remove () {
      if ($current_video) {
        if (editor.events.trigger('video.beforeRemove', [$current_video]) !== false) {
          var $video = $current_video;
          editor.popups.hideAll();
          _exitEdit(true);

          editor.selection.setBefore($video.get(0)) || editor.selection.setAfter($video.get(0));
          $video.remove();
          editor.selection.restore();

          editor.html.fillEmptyBlocks();

          editor.events.trigger('video.removed', [$video]);
        }
      }
    }

    /**
     * Convert style to classes.
     */
    function _convertStyleToClasses ($video) {
      if (!$video.hasClass('fr-dvi') && !$video.hasClass('fr-dvb')) {
        var flt = $video.css('float');
        $video.css('float', 'none');
        if ($video.css('display') == 'block') {
          $video.css('float', flt);
          if (parseInt($video.css('margin-left'), 10) === 0 && ($video.attr('style') || '').indexOf('margin-right: auto') >= 0) {
            $video.addClass('fr-fvl');
          }
          else if (parseInt($video.css('margin-right'), 10) === 0 && ($video.attr('style') || '').indexOf('margin-left: auto') >= 0) {
            $video.addClass('fr-fvr');
          }

          $video.addClass('fr-dvb');
        }
        else {
          $video.css('float', flt);
          if ($video.css('float') == 'left') {
            $video.addClass('fr-fvl');
          }
          else if ($video.css('float') == 'right') {
            $video.addClass('fr-fvr');
          }

          $video.addClass('fr-dvi');
        }

        $video.css('margin', '');
        $video.css('float', '');
        $video.css('display', '');
        $video.css('z-index', '');
        $video.css('position', '');
        $video.css('overflow', '');
        $video.css('vertical-align', '');
      }

      if (!editor.opts.videoTextNear) {
        $video.removeClass('fr-dvi').addClass('fr-dvb');
      }
    }

    /**
     * Refresh video list.
     */
    function _refreshVideoList () {
      // Find possible candidates that are not wrapped.
      editor.$el.find('video').filter(function () {
        return $(this).parents('span.fr-video').length === 0;
      }).wrap('<span class="fr-video" contenteditable="false"></span>');

      editor.$el.find('embed, iframe').filter(function () {
        if (editor.browser.safari && this.getAttribute('src')) {
          this.setAttribute('src', this.src);
        }

        if ($(this).parents('span.fr-video').length > 0) return false;

        var link = $(this).attr('src');
        for (var i = 0; i < $.FE.VIDEO_PROVIDERS.length; i++) {
          var vp = $.FE.VIDEO_PROVIDERS[i];
          if (vp.test_regex.test(link)) {
            return true;
          }
        }
        return false;
      }).map(function () {
        return $(this).parents('object').length === 0 ? this : $(this).parents('object').get(0);
      }).wrap('<span class="fr-video" contenteditable="false"></span>');

      var videos = editor.$el.find('span.fr-video');
      for (var i = 0; i < videos.length; i++) {
        _convertStyleToClasses($(videos[i]));
      }

      videos.toggleClass('fr-draggable', editor.opts.videoMove);
    }

    function _init () {
      _initEvents();

      if (editor.helpers.isMobile()) {
        editor.events.$on(editor.$el, 'touchstart', 'span.fr-video', function () {
          touchScroll = false;
        })

        editor.events.$on(editor.$el, 'touchmove', function () {
          touchScroll = true;
        });
      }

      editor.events.on('html.set', _refreshVideoList);
      _refreshVideoList();

      editor.events.$on(editor.$el, 'mousedown', 'span.fr-video', function (e) {
        e.stopPropagation();
      })
      editor.events.$on(editor.$el, 'click touchend', 'span.fr-video', _edit);

      editor.events.on('keydown', function (e) {
        var key_code = e.which;
        if ($current_video && (key_code == $.FE.KEYCODE.BACKSPACE || key_code == $.FE.KEYCODE.DELETE)) {
          e.preventDefault();
          remove();
          return false;
        }

        if ($current_video && key_code == $.FE.KEYCODE.ESC) {
          _exitEdit(true);
          e.preventDefault();
          return false;
        }

        if ($current_video && !editor.keys.ctrlKey(e)) {
          e.preventDefault();
          return false;
        }
      }, true);

      // Make sure we don't leave empty tags.
      editor.events.on('keydown', function () {
        editor.$el.find('span.fr-video:empty').remove();
      })

      _initInsertPopup(true);
      _initSizePopup(true);
    }

    /**
     * Get back to the video main popup.
     */
    function back () {
      if ($current_video) {
        $current_video.trigger('click');
      }
      else {
        editor.events.disableBlur();
        editor.selection.restore();
        editor.events.enableBlur();

        editor.popups.hide('video.insert');
        editor.toolbar.showInline();
      }
    }

    /**
     * Set size based on the current video size.
     */
    function setSize (width, height) {
      if ($current_video) {
        var $popup = editor.popups.get('video.size');
        var $video_obj = $current_video.find('iframe, embed, video');
        $video_obj.css('width', width || $popup.find('input[name="width"]').val());
        $video_obj.css('height', height || $popup.find('input[name="height"]').val());

        if ($video_obj.get(0).style.width) $video_obj.removeAttr('width');
        if ($video_obj.get(0).style.height) $video_obj.removeAttr('height');

        $popup.find('input').blur();
        setTimeout(function () {
          $current_video.trigger('click');
        }, editor.helpers.isAndroid() ? 50 : 0);
      }
    }

    function get () {
      return $current_video;
    }

    return {
      _init: _init,
      showInsertPopup: showInsertPopup,
      showLayer: showLayer,
      refreshByURLButton: refreshByURLButton,
      refreshEmbedButton: refreshEmbedButton,
      insertByURL: insertByURL,
      insertEmbed: insertEmbed,
      insert: insert,
      align: align,
      refreshAlign: refreshAlign,
      refreshAlignOnShow: refreshAlignOnShow,
      display: display,
      refreshDisplayOnShow: refreshDisplayOnShow,
      remove: remove,
      showSizePopup: showSizePopup,
      back: back,
      setSize: setSize,
      get: get
    }
  }

  // Register the font size command.
  $.FE.RegisterCommand('insertVideo', {
    title: 'Insert Video',
    undo: false,
    focus: true,
    refreshAfterCallback: false,
    popup: true,
    callback: function () {
      if (!this.popups.isVisible('video.insert')) {
        this.video.showInsertPopup();
      }
      else {
        if (this.$el.find('.fr-marker')) {
          this.events.disableBlur();
          this.selection.restore();
        }
        this.popups.hide('video.insert');
      }
    },
    plugin: 'video'
  })

  // Add the font size icon.
  $.FE.DefineIcon('insertVideo', {
    NAME: 'video-camera'
  });

  // Image by URL button inside the insert image popup.
  $.FE.DefineIcon('videoByURL', { NAME: 'link' });
  $.FE.RegisterCommand('videoByURL', {
    title: 'By URL',
    undo: false,
    focus: false,
    callback: function () {
      this.video.showLayer('video-by-url');
    },
    refresh: function ($btn) {
      this.video.refreshByURLButton($btn);
    }
  })

  // Image by URL button inside the insert image popup.
  $.FE.DefineIcon('videoEmbed', { NAME: 'code' });
  $.FE.RegisterCommand('videoEmbed', {
    title: 'Embedded Code',
    undo: false,
    focus: false,
    callback: function () {
      this.video.showLayer('video-embed');
    },
    refresh: function ($btn) {
      this.video.refreshEmbedButton($btn);
    }
  })

  $.FE.RegisterCommand('videoInsertByURL', {
    undo: true,
    focus: true,
    callback: function () {
      this.video.insertByURL();
    }
  })

  $.FE.RegisterCommand('videoInsertEmbed', {
    undo: true,
    focus: true,
    callback: function () {
      this.video.insertEmbed();
    }
  })

  // Image display.
  $.FE.DefineIcon('videoDisplay', { NAME: 'star' })
  $.FE.RegisterCommand('videoDisplay', {
    title: 'Display',
    type: 'dropdown',
    options: {
      inline: 'Inline',
      block: 'Break Text'
    },
    callback: function (cmd, val) {
      this.video.display(val);
    },
    refresh: function ($btn) {
      if (!this.opts.videoTextNear) $btn.addClass('fr-hidden');
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.video.refreshDisplayOnShow($btn, $dropdown);
    }
  })

  // Image align.
  $.FE.DefineIcon('videoAlign', { NAME: 'align-center' })
  $.FE.RegisterCommand('videoAlign', {
    type: 'dropdown',
    title: 'Align',
    options: {
      left: 'Align Left',
      justify: 'None',
      right: 'Align Right'
    },
    html: function () {
      var c = '<ul class="fr-dropdown-list">';
      var options =  $.FE.COMMANDS.videoAlign.options;
      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li><a class="fr-command fr-title" data-cmd="videoAlign" data-param1="' + val + '" title="' + this.language.translate(options[val]) + '">' + this.icon.create('align-' + val) + '</a></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    callback: function (cmd, val) {
      this.video.align(val);
    },
    refresh: function ($btn) {
      this.video.refreshAlign($btn);
    },
    refreshOnShow: function ($btn, $dropdown) {
      this.video.refreshAlignOnShow($btn, $dropdown);
    }
  })

  // Video remove.
  $.FE.DefineIcon('videoRemove', { NAME: 'trash' })
  $.FE.RegisterCommand('videoRemove', {
    title: 'Remove',
    callback: function () {
      this.video.remove();
    }
  })

  // Video size.
  $.FE.DefineIcon('videoSize', { NAME: 'arrows-alt' })
  $.FE.RegisterCommand('videoSize', {
    undo: false,
    focus: false,
    title: 'Change Size',
    callback: function () {
      this.video.showSizePopup();
    }
  });

  // Video back.
  $.FE.DefineIcon('videoBack', { NAME: 'arrow-left' });
  $.FE.RegisterCommand('videoBack', {
    title: 'Back',
    undo: false,
    focus: false,
    back: true,
    callback: function () {
      this.video.back();
    },
    refresh: function ($btn) {
      var $current_video = this.video.get();
      if (!$current_video && !this.opts.toolbarInline) {
        $btn.addClass('fr-hidden');
        $btn.next('.fr-separator').addClass('fr-hidden');
      }
      else {
        $btn.removeClass('fr-hidden');
        $btn.next('.fr-separator').removeClass('fr-hidden');
      }
    }
  });

  $.FE.RegisterCommand('videoSetSize', {
    undo: true,
    focus: false,
    callback: function () {
      this.video.setSize();
    }
  })

}));
