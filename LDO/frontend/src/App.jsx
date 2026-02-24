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
    subtitle: 'Персональный центр управления тренировками и питанием.',
  },
  diary: {
    title: 'Дневник',
    subtitle: 'Фиксируйте тренировки и приёмы пищи в одном месте.',
  },
  progress: {
    title: 'Прогресс',
    subtitle: 'Отслеживайте динамику и держите фокус на цели.',
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
      <header className="hero">
        <div className="brand-row">
          <span className="brand-dot" />
          <span className="brand-name">LDO</span>
        </div>

        <h1>{pageMeta[activePage].title}</h1>
        <p>{pageMeta[activePage].subtitle}</p>

        <div className="meta-pills">
          <span>Lazy load</span>
          <span>Skeleton</span>
          <span>Minimal UI</span>
        </div>
      </header>

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
