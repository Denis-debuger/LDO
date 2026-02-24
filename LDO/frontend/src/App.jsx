import { lazy, Suspense, useMemo, useState } from 'react'
import './App.css'

const pages = {
  home: lazy(() => import('./pages/HomePage.jsx')),
  diary: lazy(() => import('./pages/DiaryPage.jsx')),
  progress: lazy(() => import('./pages/ProgressPage.jsx')),
}

const pageMeta = {
  home: {
    title: 'Главная',
    subtitle: 'Добро пожаловать в LDO — персонального помощника по фитнесу.',
  },
  diary: {
    title: 'Дневник',
    subtitle: 'Записывайте тренировки и питание с мгновенной статистикой.',
  },
  progress: {
    title: 'Прогресс',
    subtitle: 'Следите за динамикой, чтобы не терять мотивацию.',
  },
}

function PageLoader() {
  return (
    <div className="page-loader" role="status" aria-live="polite">
      <div className="spinner" />
      <p>Подгружаем страницу…</p>
    </div>
  )
}

function App() {
  const [activePage, setActivePage] = useState('home')
  const ActivePage = useMemo(() => pages[activePage], [activePage])

  return (
    <div className="app-shell">
      <div className="release-badge">LDO UI v2 · Новый дизайн и подгрузка страниц</div>

      <header className="hero">
        <h1>{pageMeta[activePage].title}</h1>
        <p>{pageMeta[activePage].subtitle}</p>
      </header>

      <section className="quick-stats" aria-label="Краткие показатели">
        <article>
          <strong>3</strong>
          <span>раздела с lazy load</span>
        </article>
        <article>
          <strong>2</strong>
          <span>типа загрузки (spinner + skeleton)</span>
        </article>
        <article>
          <strong>100%</strong>
          <span>адаптивная навигация</span>
        </article>
      </section>

      <nav className="nav-grid" aria-label="Навигация по разделам">
        {Object.keys(pages).map((key) => (
          <button
            key={key}
            className={`nav-button ${activePage === key ? 'active' : ''}`}
            onClick={() => setActivePage(key)}
          >
            {pageMeta[key].title}
          </button>
        ))}
      </nav>

      <main className="page-frame">
        <Suspense fallback={<PageLoader />}>
          <ActivePage />
        </Suspense>
      </main>
    </div>
  )
}

export default App
