/**
 * LDO — Let's Do It. Client-side scripts.
 */

(function () {
  'use strict';

  // Простая валидация форм на клиенте
  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
      var pass = form.querySelector('input[minlength="8"][type="password"]');
      if (pass && pass.value.length > 0 && pass.value.length < 8) {
        pass.setCustomValidity('Минимум 8 символов');
      } else if (pass) {
        pass.setCustomValidity('');
      }
    });
  });

  // Мобильное меню
  var menuToggle = document.querySelector('[data-menu-toggle]');
  var navLinks = document.querySelector('[data-nav-links]');
  if (menuToggle && navLinks) {
    menuToggle.addEventListener('click', function () {
      var expanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      navLinks.classList.toggle('is-open', !expanded);
      document.body.classList.toggle('menu-open', !expanded);
    });

    navLinks.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        menuToggle.setAttribute('aria-expanded', 'false');
        navLinks.classList.remove('is-open');
        document.body.classList.remove('menu-open');
      });
    });
  }

  // Табы профиля (без React)
  var tabsMount = document.getElementById('profile-tabs');
  if (tabsMount) {
    var buttons = Array.prototype.slice.call(tabsMount.querySelectorAll('[data-tab-target]'));
    var panels = Array.prototype.slice.call(document.querySelectorAll('[data-tab-panel]'));
    var activate = function (tab) {
      buttons.forEach(function (btn) {
        var active = btn.getAttribute('data-tab-target') === tab;
        btn.classList.toggle('is-active', active);
        btn.setAttribute('aria-selected', active ? 'true' : 'false');
      });
      panels.forEach(function (panel) {
        var activePanel = panel.getAttribute('data-tab-panel') === tab;
        panel.classList.toggle('is-hidden', !activePanel);
      });
    };

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        activate(btn.getAttribute('data-tab-target'));
      });
    });

    activate('profile');
  }

  // График веса (страница прогресса)
  if (typeof window.LDO_WEIGHT_DATA !== 'undefined' && Array.isArray(window.LDO_WEIGHT_DATA) && window.LDO_WEIGHT_DATA.length > 0) {
    var data = window.LDO_WEIGHT_DATA;
    var container = document.getElementById('progress-weight-chart');
    if (container) {
      var weights = data.map(function (d) { return parseFloat(d.weight_kg); });
      var minW = Math.min.apply(null, weights);
      var maxW = Math.max.apply(null, weights);
      var range = maxW - minW || 1;
      var padding = { top: 20, right: 20, bottom: 30, left: 40 };
      var w = container.clientWidth - padding.left - padding.right;
      var h = container.clientHeight - padding.top - padding.bottom;

      var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svg.setAttribute('width', '100%');
      svg.setAttribute('height', '100%');
      svg.setAttribute('viewBox', '0 0 ' + (w + padding.left + padding.right) + ' ' + (h + padding.top + padding.bottom));
      svg.style.display = 'block';

      var g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
      g.setAttribute('transform', 'translate(' + padding.left + ',' + padding.top + ')');

      var pathD = data.map(function (d, i) {
        var x = (i / (data.length - 1 || 1)) * w;
        var y = h - ((parseFloat(d.weight_kg) - minW) / range) * h;
        return (i === 0 ? 'M' : 'L') + x + ',' + y;
      }).join(' ');

      var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', pathD);
      path.setAttribute('fill', 'none');
      path.setAttribute('stroke', '#FFD100');
      path.setAttribute('stroke-width', '2');
      path.setAttribute('stroke-linecap', 'round');
      path.setAttribute('stroke-linejoin', 'round');
      g.appendChild(path);

      data.forEach(function (d, i) {
        var x = (i / (data.length - 1 || 1)) * w;
        var y = h - ((parseFloat(d.weight_kg) - minW) / range) * h;
        var circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', x);
        circle.setAttribute('cy', y);
        circle.setAttribute('r', '4');
        circle.setAttribute('fill', '#FFD100');
        circle.setAttribute('title', d.logged_at + ': ' + d.weight_kg + ' кг');
        g.appendChild(circle);
      });

      svg.appendChild(g);
      container.appendChild(svg);
    }
  }

  // Мини-WYSIWYG для админской формы статьи
  var editorRoot = document.getElementById('wysiwyg-editor');
  if (editorRoot) {
    var output = document.getElementById('article-body');
    var editable = document.getElementById('wysiwyg-editable');
    var toolbarButtons = Array.prototype.slice.call(editorRoot.querySelectorAll('[data-cmd]'));
    if (output && editable) {
      editable.addEventListener('input', function () {
        output.value = editable.innerHTML;
      });

      toolbarButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var cmd = btn.getAttribute('data-cmd');
          var val = btn.getAttribute('data-val') || null;
          document.execCommand(cmd, false, val);
          editable.focus();
          output.value = editable.innerHTML;
        });
      });

      var clearBtn = editorRoot.querySelector('[data-clear-format]');
      if (clearBtn) {
        clearBtn.addEventListener('click', function () {
          document.execCommand('removeFormat', false, null);
          output.value = editable.innerHTML;
        });
      }
    }
  }
})();
