/**
 * LDO — Let's Do It. Client-side scripts.
 */

(function () {
  'use strict';

  // Глобальный загрузчик для PHP-страниц
  var appLoader = document.getElementById('app-loader');
  if (appLoader) {
    var loaderRing = appLoader.querySelector('.app-loader__ring');
    var loaderPercent = appLoader.querySelector('[data-loader-percent]');
    var loaderMessage = appLoader.querySelector('[data-loader-message]');
    var loaderSteps = [
      { at: 0, text: 'Подготавливаем страницу…' },
      { at: 30, text: 'Проверяем модули…' },
      { at: 60, text: 'Загружаем данные…' },
      { at: 85, text: 'Финальные штрихи…' }
    ];
    var loaderProgress = 4;
    var loaderDone = false;
    var loaderStartedAt = Date.now();
    var minLoaderDurationMs = 950;

    function updateLoader(value) {
      loaderProgress = Math.max(loaderProgress, Math.min(100, value));
      if (loaderRing) loaderRing.style.setProperty('--progress', loaderProgress.toFixed(1) + '%');
      if (loaderPercent) loaderPercent.textContent = Math.round(loaderProgress) + '%';
      if (loaderMessage) {
        var stepText = loaderSteps[0].text;
        loaderSteps.forEach(function (step) {
          if (loaderProgress >= step.at) stepText = step.text;
        });
        loaderMessage.textContent = stepText;
      }
    }

    function hideLoader() {
      appLoader.classList.add('is-hidden');
      document.body.classList.remove('is-app-loading');
      setTimeout(function () {
        appLoader.remove();
      }, 650);
    }

    function closeLoader() {
      if (loaderDone) return;
      loaderDone = true;
      updateLoader(100);

      var elapsed = Date.now() - loaderStartedAt;
      var waitTime = Math.max(0, minLoaderDurationMs - elapsed);
      setTimeout(hideLoader, waitTime);
    }

    function tickLoader() {
      if (loaderDone) return;

      var target = 94;
      var delta = (target - loaderProgress) * 0.12;
      if (delta < 0.25) delta = 0.25;
      updateLoader(loaderProgress + delta);

      requestAnimationFrame(tickLoader);
    }

    updateLoader(6);
    requestAnimationFrame(tickLoader);

    window.addEventListener('load', function () {
      setTimeout(closeLoader, 180);
    });

    setTimeout(closeLoader, 6000);
  }


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


  // ReactJS: глобальный shell на всех страницах
  if (window.React && window.ReactDOM) {
    var globalMount = document.getElementById('react-global-shell');
    if (globalMount) {
      function GlobalShell() {
        var route = document.body.getAttribute('data-route') || 'public';
        return React.createElement('div', { className: 'react-global-banner' },
          React.createElement('span', null, 'ReactJS active • route: ' + route)
        );
      }

      var globalRoot = ReactDOM.createRoot ? ReactDOM.createRoot(globalMount) : null;
      if (globalRoot) globalRoot.render(React.createElement(GlobalShell));
      else ReactDOM.render(React.createElement(GlobalShell), globalMount);
    }
  }

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

  // ReactJS: табы в профиле + кастомная загрузка/подгрузка файлов
  if (window.React && window.ReactDOM) {
    var tabsMount = document.getElementById('profile-react-tabs');
    if (tabsMount) {
      var panels = Array.prototype.slice.call(document.querySelectorAll('[data-tab-panel]'));
      function ProfileTabs() {
        var useState = React.useState;
        var activeState = useState('profile');
        var active = activeState[0];
        var setActive = activeState[1];

        React.useEffect(function () {
          panels.forEach(function (panel) {
            var name = panel.getAttribute('data-tab-panel');
            panel.classList.toggle('is-hidden', name !== active);
          });
        }, [active]);

        var tabItems = [
          { key: 'avatar', label: 'Аватар' },
          { key: 'files', label: 'Файлы' },
          { key: 'profile', label: 'Профиль' }
        ];

        return React.createElement('div', { className: 'react-tabs-shell' },
          tabItems.map(function (tab) {
            return React.createElement('button', {
              key: tab.key,
              type: 'button',
              className: 'react-tab-btn' + (active === tab.key ? ' is-active' : ''),
              onClick: function () { setActive(tab.key); }
            }, tab.label);
          })
        );
      }

      var root = ReactDOM.createRoot ? ReactDOM.createRoot(tabsMount) : null;
      if (root) root.render(React.createElement(ProfileTabs));
      else ReactDOM.render(React.createElement(ProfileTabs), tabsMount);
    }

    var fileMount = document.getElementById('react-file-loader');
    if (fileMount) {
      function FileLoader() {
        var useState = React.useState;
        var filesState = useState([]);
        var files = filesState[0];
        var setFiles = filesState[1];
        var visibleState = useState(3);
        var visible = visibleState[0];
        var setVisible = visibleState[1];

        function onPick(e) {
          var next = Array.prototype.slice.call(e.target.files || []);
          setFiles(next);
          setVisible(3);
        }

        var visibleFiles = files.slice(0, visible);

        return React.createElement('div', { className: 'file-loader' }, [
          React.createElement('label', { key: 'pick', className: 'file-loader-dropzone' }, [
            React.createElement('strong', { key: 't' }, 'Выберите файлы'),
            React.createElement('div', { key: 'd', className: 'muted' }, 'Поддерживается множественный выбор, список подгружается по 3 файла.'),
            React.createElement('input', { key: 'i', type: 'file', multiple: true, onChange: onPick, style: { marginTop: '8px' } })
          ]),
          React.createElement('ul', { key: 'list', className: 'file-loader-list' },
            visibleFiles.map(function (f, index) {
              return React.createElement('li', { key: f.name + index }, f.name + ' (' + Math.round(f.size / 1024) + ' KB)');
            })
          ),
          files.length > visible ? React.createElement('button', {
            key: 'more',
            type: 'button',
            className: 'btn btn-ghost',
            onClick: function () { setVisible(visible + 3); }
          }, 'Подгрузить ещё') : null
        ]);
      }

      var fileRoot = ReactDOM.createRoot ? ReactDOM.createRoot(fileMount) : null;
      if (fileRoot) fileRoot.render(React.createElement(FileLoader));
      else ReactDOM.render(React.createElement(FileLoader), fileMount);
    }
  }

})();
