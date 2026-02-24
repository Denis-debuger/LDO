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

  // График веса (страница прогресса)
  if (typeof window.LDO_WEIGHT_DATA !== 'undefined' && Array.isArray(window.LDO_WEIGHT_DATA) && window.LDO_WEIGHT_DATA.length > 0) {
    var data = window.LDO_WEIGHT_DATA;
    var container = document.getElementById('progress-weight-chart');
    if (container) {
      var weights = data.map(function (d) { return parseFloat(d.weight_kg); });
      var labels = data.map(function (d) { return d.logged_at; });
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

      // Линия графика
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

      // Точки
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
})();
