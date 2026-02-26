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

function HomePage({ navigate }) {
  return (
    <section className="home-wrap">
      <div className="home-shell card">
        <div className="home-topline">Твой ежедневный фитнес-помощник: тренировки, питание, восстановление</div>

        <div className="home-categories">
          <div className="home-tabs">
            <button type="button" className="home-tab is-active">Тренировки</button>
            <button type="button" className="home-tab">Питание</button>
            <button type="button" className="home-tab">Прогресс</button>
            <button type="button" className="home-tab">Привычки</button>
          </div>
          <div className="home-icons">
            <span>RU</span>
            <span>📊</span>
            <span>🧠</span>
            <span>💪</span>
          </div>
        </div>

        <div className="home-hero-card">
          <div className="home-hero-overlay" />
          <div className="home-hero-content">
            <p className="badge">LDO • FITNESS PLATFORM</p>
            <h1>BLACKOUT MODE</h1>
            <p className="lead">Собери свой режим: планируй тренировки, считай КБЖУ и отслеживай результат каждую неделю.</p>
            <div className="home-actions">
              <button type="button" className="btn btn-primary" onClick={() => navigate('profile')}>Открыть профиль</button>
              <button type="button" className="btn btn-ghost">Рассчитать КБЖУ</button>
            </div>
          </div>
        </div>

        <div className="home-tiles">
          <article className="home-tile">
            <h3>План на неделю</h3>
            <ul>
              <li>Пн — Спина + 20 мин кардио</li>
              <li>Ср — Ноги + мобилити</li>
              <li>Пт — Грудь + руки</li>
            </ul>
          </article>
          <article className="home-tile">
            <h3>Фокус по питанию</h3>
            <ul>
              <li>Калории: 2200 ккал</li>
              <li>Белки: 160 г</li>
              <li>Вода: 2.4 л</li>
            </ul>
          </article>
        </div>

        <div className="hero-grid section-spacer">
          <article className="mini-card">
            <h3>Тренировки</h3>
            <p>Подбор программ и упражнений для любой цели: снижение веса, тонус, набор массы.</p>
          </article>
          <article className="mini-card">
            <h3>Питание</h3>
            <p>Контроль КБЖУ и пищевых привычек без лишней рутины.</p>
          </article>
          <article className="mini-card">
            <h3>Прогресс</h3>
            <p>Наглядная динамика веса, объёмов и тренировочной нагрузки.</p>
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
      {route === 'profile' ? <ProfilePage /> : <HomePage navigate={navigate} />}
    </Layout>
  )
}

export default App
