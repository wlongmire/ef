/* 
 * Copyright (c) Andreas Ferber <af+symfony@chaos-agency.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    sfAjaxDebugPlugin
 * @author     Andreas Ferber <af+symfony@chaos-agency.de>
 * @version    SVN: $Id: jsSuccess.js.php 10257 2008-07-13 16:05:20Z aferber $
 */

var sfAjaxDebug = {

  keepRequests: <?php echo sfConfig::get('sf_ajax_debug_requests') ?>,

  requestCount: 0,

  current: null,
  toplevel: { id: 'toplevel', loaded: true, title: '[000] initial request', content: '' },
  toolbars: [],
  toolbarsById: {},

  initialize: function()
  {
    var sfWebDebug = document.getElementById('sfWebDebug');
    if (sfWebDebug)
    {
      this.toplevel.content = sfWebDebug;
      this.current = this.toplevel;
      this.toolbarsById['toplevel'] = this.toplevel;
      this.insertRequestMenu();
    }
  },

  insertElement: function(element, content, location)
  {
    if (window.Prototype)
    {
      var insertion = {};
      insertion[location] = content;
      $(element).insert(insertion);
    }
    else if (window.jQuery)
    {
      var method = {
        top: 'prepend',
        bottom: 'append',
        before: 'before',
        after: 'after'
      }[location];
      $(element)[method](content);
    }
  },

  replaceElement: function(element, newElement)
  {
    if (window.Prototype)
    {
      $(element).replace(newElement);
    }
    else if (window.jQuery)
    {
      $(element).replaceWith(newElement);
    }
  },

  newRequest: function(module, action, token)
  {
    if (this.toolbars.length >= this.keepRequests)
    {
      var oldReq = this.toolbars.shift();
      delete this.toolbarsById[oldReq.id];
    }
    this.requestCount++;
    var fmtReqCount = this.requestCount.toString();
    while (fmtReqCount.length < 3)
    {
      fmtReqCount = '0'+fmtReqCount;
    }
    var newReq =
    {
      id: token,
      loaded: false,
      title: '['+fmtReqCount+'] M: '+module+' A: '+action,
      content: ''
    };
    this.toolbars.push(newReq);
    this.toolbarsById[token] = newReq;
    this.insertRequestMenu();

    var url = '<?php echo url_for('sfAjaxDebug/get?token=_placeholder_') ?>';
    url = url.replace('_placeholder_', token);
    var self = this;
    if (window.Prototype)
    {
      new Ajax.Request(url, {
        method: 'get',
        asynchronous: true,
        evalScripts: false,
        onSuccess: function(request, json) {
          var toolbar = self.getToolbar(token);
          if (toolbar) {
            toolbar.content = request.responseText;
            toolbar.loaded = true;
            self.switchToolbar(token);
          }
        }
      });
    }
    else
    {
      $.ajax({
        url: url,
        async: true,
        success: function(data) {
          var toolbar = self.getToolbar(token);
          if (toolbar) {
            toolbar.content = data;
            toolbar.loaded = true;
            self.switchToolbar(token);
          }
        }
      });
    }
  },

  getToolbar: function(id)
  {
    if (id == 'toplevel')
    {
      return this.toplevel;
    }
    for (var i = 0; i < this.toolbars.length; i++)
    {
      if (this.toolbars[i].id == id)
      {
        return this.toolbars[i];
      }
    }
    return false;
  },

  switchToolbar: function(id)
  {
    var toolbar = this.getToolbar(id);
    if (!toolbar)
    {
      alert('sfAjaxDebug: toolbar "'+id+'" not found');
      return;
    }
    if (!toolbar.loaded)
    {
      alert('sfAjaxDebug: The requested toolbar hasn\'t been loaded yet!');
      return;
    }
    this.current = toolbar;
    this.replaceElement(document.getElementById('sfWebDebug'), toolbar.content);
    this.insertRequestMenu();
  },

  insertRequestMenu: function()
  {
    if (!document.getElementById('sfWebDebug'))
    {
      //alert('sfAjaxDebug: could not find the web debug toolbar');
      return;
    }

    var oldNode;

    var menu = document.createElement('div');
    menu.setAttribute('id', 'sfAjaxDebug');
    menu.setAttribute('class', 'sfWebDebugTop');
    menu.style.display = 'none';

    var head = document.createElement('h1');
    head.appendChild(document.createTextNode('Ajax Requests'));
    menu.appendChild(head);

    var list = document.createElement('li');
    list.setAttribute('id', 'sfAjaxDebugMenu');

    list.appendChild(this.makeRequestMenuEntry(this.toplevel));
    for (var i = 0; i < this.toolbars.length; i++)
    {
      list.appendChild(this.makeRequestMenuEntry(this.toolbars[i]));
    }

    menu.appendChild(list);

    if (oldNode = document.getElementById('sfAjaxDebug'))
    {
      this.replaceElement(oldNode, menu);
    }
    else
    {
      this.insertElement(document.getElementById('sfWebDebugBar'), menu, 'after');
    }

    var toolbarBtn = document.createElement('li');
    toolbarBtn.setAttribute('id', 'sfAjaxDebugButton');
    var toolbarLink = document.createElement('a');
    toolbarLink.setAttribute('href', '#');
    toolbarLink.setAttribute('onclick', "sfWebDebugShowDetailsFor('sfAjaxDebug'); return false;");
    toolbarLink.appendChild(document.createTextNode(this.current?this.current.title:'ajax requests'));
    toolbarBtn.appendChild(toolbarLink);

    if (oldNode = document.getElementById('sfAjaxDebugButton'))
    {
      this.replaceElement(oldNode, toolbarBtn);
    }
    else
    {
      this.insertElement(document.getElementById('sfWebDebugDetails'), toolbarBtn, 'top');
    }
  },

  makeRequestMenuEntry: function(entry)
  {
    var liNode = document.createElement('li');
    if (this.current == entry)
    {
      liNode.setAttribute('class', 'sfAjaxDebugCurrent');
    }
    var aNode = document.createElement('a');
    aNode.setAttribute('href', '#');
    aNode.setAttribute('onclick', 'sfAjaxDebug.switchToolbar("'+entry.id+'"); return false;');
    aNode.appendChild(document.createTextNode(entry.title));
    liNode.appendChild(aNode);
    return liNode;
  }
};

var onRequestComplete = function(request) {
  var token = request.getResponseHeader('X-sfAjaxDebug-Token') || '';
  if (token)
  {
    var module = request.getResponseHeader('X-sfAjaxDebug-Module') || '[unknown]';
    var action = request.getResponseHeader('X-sfAjaxDebug-Action') || '[unknown]';
    sfAjaxDebug.newRequest(module, action, token);
  }
}
if (window.Prototype)
{
  document.observe('dom:loaded', function() {
    sfAjaxDebug.initialize();
  });

  Ajax.Responders.register({
    onComplete: function(request) {
      onRequestComplete(request.transport);
    }
  });
}
else if (window.jQuery)
{
  $(document).ready(function() {
    sfAjaxDebug.initialize();
    $(document).ajaxComplete(function(event, request) {
      onRequestComplete(request);
    });
  });
}