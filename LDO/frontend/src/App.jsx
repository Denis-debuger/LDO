import { useEffect, useState } from 'react'

const NEWS = [
  { title: 'Новый челлендж на 21 день', text: 'Запустили программу привычек: сон, вода, шаги и 3 тренировки в неделю.' },
  { title: 'Обновлены планы питания', text: 'Добавлены варианты рационов под снижение веса и поддержание формы.' },
  { title: 'Гайды по технике', text: 'В библиотеке появились короткие подсказки по базовым упражнениям.' },
]

const WORKOUT_DAYS = [
  ['Понедельник', 'Спина + кардио 20 мин'],
  ['Среда', 'Ноги + мобилити'],
  ['Пятница', 'Грудь + руки'],
  ['Воскресенье', 'Активное восстановление'],
]

const FOOD_PLAN = [
  ['Калории', '2200 ккал'],
  ['Белки', '160 г'],
  ['Жиры', '70 г'],
  ['Углеводы', '240 г'],
  ['Вода', '2.4 л'],
]

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
              {WORKOUT_DAYS.map(([day, task]) => (
                <li key={day}><strong>{day}:</strong> {task}</li>
              ))}
            </ul>
          </article>
          <article className="home-tile">
            <h3>Фокус по питанию</h3>
            <ul>
              {FOOD_PLAN.map(([name, value]) => (
                <li key={name}><strong>{name}:</strong> {value}</li>
              ))}
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

        <section className="home-news">
          <h2>Новости и обновления</h2>
          <div className="news-grid">
            {NEWS.map((item) => (
              <article key={item.title} className="news-card">
                <h3>{item.title}</h3>
                <p>{item.text}</p>
              </article>
            ))}
          </div>
        </section>

        <section className="home-faq">
          <h2>Как начать</h2>
          <ol>
            <li>Заполни профиль (рост, вес, цель).</li>
            <li>Собери недельный план тренировок.</li>
            <li>Следи за питанием и дневной нормой воды.</li>
            <li>Раз в неделю проверяй прогресс и корректируй план.</li>
          </ol>
        </section>
      </div>
    </section>
  )
}

function ProfilePage() {
  return (
    <section className="card profile-page">
      <div className="profile-head">
        <div className="avatar">A</div>
        <div>
          <h1>Страница профиля</h1>
          <p className="muted">Типовая статическая карточка пользователя</p>
        </div>
      </div>

      <div className="profile-grid">
        <div className="profile-item"><span>Имя</span><strong>Алексей Иванов</strong></div>
        <div className="profile-item"><span>Email</span><strong>alex@ldo.local</strong></div>
        <div className="profile-item"><span>Рост</span><strong>182 см</strong></div>
        <div className="profile-item"><span>Вес</span><strong>79 кг</strong></div>
        <div className="profile-item"><span>Цель</span><strong>Снижение веса</strong></div>
        <div className="profile-item"><span>Активность</span><strong>Средняя</strong></div>
      </div>

      <div className="profile-sections">
        <article className="profile-panel">
          <h3>Мои цели на месяц</h3>
          <ul>
            <li>Стабильный дефицит 250–350 ккал</li>
            <li>12 тренировок в месяц</li>
            <li>Не менее 8 000 шагов в день</li>
          </ul>
        </article>
        <article className="profile-panel">
          <h3>Личные рекорды</h3>
          <ul>
            <li>Присед: 100 кг × 5</li>
            <li>Жим лёжа: 80 кг × 6</li>
            <li>Планка: 3:15</li>
          </ul>
        </article>
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
