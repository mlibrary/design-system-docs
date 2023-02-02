/**
 * Detail Widget
 *
 * @author Ayhan Akilli, adapted to Drupal by Martin Anderson-Clutz
 */
 /*jshint maxlen:false, camelcase:false*/
 /*global CKEDITOR */

 (function ($, Drupal) {

'use strict';

  /**
   * DTD
   */
  CKEDITOR.dtd['$block']['summary'] = 1;
  CKEDITOR.dtd['$editable']['summary'] = 1;
  CKEDITOR.dtd['$empty']['drupal-media'] = 1;
  CKEDITOR.dtd['$empty']['drupal-entity'] = 1;

  /**
   * Plugin
   */
  CKEDITOR.plugins.add('detail', {
      requires: 'widget',
      icons: 'detail',
      hidpi: true,
      lang: 'de,en,uk,ru',
      init: function (editor) {
          /**
           * Widget
           */
          editor.widgets.add('detail', {
              button: editor.lang.detail.title,
              template: '<details><summary>' + editor.lang.detail.title + '</summary><div class="details-wrapper"></div></details>',
              editables: {
                  summary: {
                      selector: 'summary',
                      allowedContent: 'abbr br strong em small span strong sub sup time;'
                        + 'drupal-media[data-entity-type,data-entity-uuid,data-align];'
                        + 'img[!src,alt,width,height,data-entity-type,data-entity-uuid,data-align,data-caption];'
                        + 'picture svg video'
                  },
                  content: {
                      selector: '.details-wrapper',
                  }
              },
              allowedContent: {
                details: true,
                summary: true,
                div: {
                  attributes: '!class',
                  classes: 'details-wrapper'
                }
              },
              requiredContent: new CKEDITOR.style({
                element: 'div',
                attributes: '!class',
                classes: 'details-wrapper'
              }),
              upcast: function (el) {
                  if (el.name !== 'details') {
                      return false;
                  }

                  var summary = el.getFirst('summary');

                  if (!summary) {
                      el.add(new CKEDITOR.htmlParser.element('summary'), 0);
                  }

                  var content = el.getFirst('div');
                  if (!content || !content.hasClass('details-wrapper')) {
                    content = new CKEDITOR.htmlParser.element('div', {'class': 'details-wrapper'});
                    el.add(content, 1);
                  }

                  if (el.children.length > 2) {
                      content.children = el.children.slice(2);
                      el.children = el.children.slice(0, 2);
                  }

                  return true;
              },
              init: function () {
                  var summary = this.element.getChild(0);

                  summary.on('blur', function () {
                      if (!summary.getText()) {
                          summary.setText(editor.lang.detail.title);
                      }
                      // Delete any zero-width spaces.
                      var markup = summary.getHtml();
                      markup = String(markup).replace(/\u200B/gi, '');
                      summary.setHtml(markup);
                  });
                  summary.on('keyup', function (ev) {
                    if (ev.data['$'].keyCode === 8 && summary.getText().trim() === '') {
                      editor.insertText('');
                    }
                  });
                  summary.on('keydown', function (ev) {
                      if (ev.data['$'].key === ' ' || ev.data['$'].code === 'Space' || ev.data['$'].keyCode === 32) {
                          ev.data['$'].preventDefault();
                          // Direct insertion of a space as HTML will be ignored at the end of the summary.
                          editor.document.$.execCommand('inserttext', false, ' ');
                      }
                  });
              }
          });
          editor.ui.addButton('detail', {
            label: Drupal.t('Add details'),
            command: 'detail',
            toolbar: 'insert',
          });
      },
      onLoad: function () {
          CKEDITOR.addCss(
              'details {line-height: 1.5rem;padding: 0.75rem;background: #eee;border: 0.0625rem solid #ddd;border-radius: 0.5rem;}' +
              'details > * {padding: 0.375rem;background: #fff;}' +
              'details[open] > :not(:last-child) {margin-bottom: 0.75rem;}' +
              'details .cke_widget_editable {outline: none !important;}'
          );
      },
  });

}(jQuery, Drupal));
