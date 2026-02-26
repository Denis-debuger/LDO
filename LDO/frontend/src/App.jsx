import { useEffect, useState } from 'react'

function useHashRoute() {
  const [route, setRoute] = useState(window.location.hash.replace('#/', '') || 'home')

  useEffect(() => {
    const onHash = () => setRoute(window.location.hash.replace('#/', '') || 'home')
    window.addEventListener('hashchange', onHash)
    return () => window.removeEventListener('hashchange', onHash)
  }, [])

  const navigate = (next) => {
    window.location.hash = `/${next}`
  }

  return { route, navigate }
}

function Layout({ route, navigate, children }) {
  return (
    <>
      <header className="navbar">
        <div className="container nav-inner">
          <div className="brand">LDO <span>React</span></div>
          <div className="nav-links">
            <button className={`nav-link-btn ${route === 'home' ? 'active' : ''}`} onClick={() => navigate('home')} type="button">Главная</button>
            <button className={`nav-link-btn ${route === 'profile' ? 'active' : ''}`} onClick={() => navigate('profile')} type="button">Профиль</button>
          </div>
        </div>
      </header>
      <main className="container page-wrap">{children}</main>
    </>
  )
}

function HomePage() {
  return (
    <section className="card hero-card">
      <div className="hero-content">
        <p className="badge">LDO • FITNESS PLATFORM</p>
        <h1>Главная страница</h1>
        <p className="lead">Тренировки, питание и прогресс в одном месте. Это типовая статическая главная страница проекта в стиле LDO.</p>
        <div className="hero-grid">
          <article className="mini-card">
            <h3>Тренировки</h3>
            <p>Подбор программ и упражнений для любой цели.</p>
          </article>
          <article className="mini-card">
            <h3>Питание</h3>
            <p>Контроль КБЖУ и пищевых привычек.</p>
          </article>
          <article className="mini-card">
            <h3>Прогресс</h3>
            <p>Наглядная динамика результатов.</p>
          </article>
        </div>
      </div>
    </section>
  )
}

function ProfilePage() {
  return (
    <section className="card">
      <div className="profile-head">
        <div className="avatar">A</div>
        <div>
          <h1>Страница профиля</h1>
          <p className="muted">Типовая статическая карточка пользователя</p>
        </div>
      </div>

      <div className="profile-grid">
        <div className="profile-item">
          <span>Имя</span>
          <strong>Алексей Иванов</strong>
        </div>
        <div className="profile-item">
          <span>Email</span>
          <strong>alex@ldo.local</strong>
        </div>
        <div className="profile-item">
          <span>Рост</span>
          <strong>182 см</strong>
        </div>
        <div className="profile-item">
          <span>Вес</span>
          <strong>79 кг</strong>
        </div>
        <div className="profile-item">
          <span>Цель</span>
          <strong>Снижение веса</strong>
        </div>
        <div className="profile-item">
          <span>Активность</span>
          <strong>Средняя</strong>
        </div>
      </div>
    </section>
  )
}

function App() {
  const { route, navigate } = useHashRoute()

  return (
    <Layout route={route} navigate={navigate}>
      {route === 'profile' ? <ProfilePage /> : <HomePage />}
    </Layout>
  )
}

export default App
