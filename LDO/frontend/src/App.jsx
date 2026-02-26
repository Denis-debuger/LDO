import { useMemo, useState } from 'react'

const NAV_ITEMS = [
  { key: 'home', label: 'Главная' },
  { key: 'profile', label: 'Профиль' },
  { key: 'kbju', label: 'КБЖУ' },
  { key: 'programs', label: 'Программы' },
  { key: 'exercises', label: 'Упражнения' },
  { key: 'diary', label: 'Дневник' },
  { key: 'progress', label: 'Прогресс' },
  { key: 'articles', label: 'Статьи' },
  { key: 'auth', label: 'Авторизация' },
  { key: 'admin', label: 'Админ' },
]

const PROGRAMS = [
  ['Сушка 4 недели', 'Кардио + силовые, 4 тренировки/нед'],
  ['Набор массы', 'Базовые упражнения, акцент на прогрессию'],
  ['Для начинающих', '3 тренировки в неделю с техникой'],
]

const EXERCISES = [
  ['Приседания', 'Ноги', 'Сложность: средняя'],
  ['Жим лёжа', 'Грудь', 'Сложность: средняя'],
  ['Тяга верхнего блока', 'Спина', 'Сложность: лёгкая'],
  ['Планка', 'Пресс', 'Сложность: лёгкая'],
]

const ARTICLES = [
  ['Как считать калории правильно', 'Питание'],
  ['5 ошибок в тренировках новичка', 'Тренировки'],
  ['Восстановление и сон', 'Здоровье'],
]

function PageLayout({ title, subtitle, children }) {
  return (
    <section className="card card-accent">
      <div className="card-body">
        <h1 className="page-head">{title}</h1>
        <p className="page-subtitle muted">{subtitle}</p>
        {children}
      </div>
    </section>
  )
}

function App() {
  const [active, setActive] = useState('home')

  const content = useMemo(() => {
    if (active === 'home') {
      return (
        <PageLayout title="LDO — React версия" subtitle="Весь интерфейс перенесён на React SPA с единой дизайн-системой.">
          <div className="grid grid-2">
            <div className="card">
              <div className="card-body">
                <h3 className="card-title">Твой фитнес-хаб</h3>
                <p className="muted">Программы, дневник, КБЖУ, прогресс и статьи теперь работают как единое React-приложение.</p>
                <div className="action-row section-spacer">
                  <button className="btn btn-primary" onClick={() => setActive('programs')}>К программам</button>
                  <button className="btn btn-ghost" onClick={() => setActive('profile')}>Открыть профиль</button>
                </div>
              </div>
            </div>
            <div className="card">
              <div className="card-body">
                <h3 className="card-title">Дашборд</h3>
                <div className="kpi">
                  <div className="item"><div className="num">12</div><div className="muted">Тренировок в месяце</div></div>
                  <div className="item"><div className="num">-2.4 кг</div><div className="muted">Изменение веса</div></div>
                </div>
              </div>
            </div>
          </div>
        </PageLayout>
      )
    }

    if (active === 'profile') {
      return (
        <PageLayout title="Профиль" subtitle="Редактирование данных пользователя и фитнес-целей.">
          <form className="form">
            <div className="row">
              <label>Имя<input defaultValue="Алексей" /></label>
              <label>Email<input defaultValue="alex@ldo.local" /></label>
            </div>
            <div className="row">
              <label>Рост (см)<input defaultValue="182" /></label>
              <label>Вес (кг)<input defaultValue="79" /></label>
            </div>
            <div className="row">
              <label>Цель<select defaultValue="cut"><option value="cut">Снижение веса</option><option value="maintain">Поддержание</option><option value="bulk">Набор массы</option></select></label>
              <label>Активность<select defaultValue="medium"><option value="low">Низкая</option><option value="medium">Средняя</option><option value="high">Высокая</option></select></label>
            </div>
            <button type="button" className="btn btn-primary">Сохранить профиль</button>
          </form>
        </PageLayout>
      )
    }

    if (active === 'kbju') {
      return (
        <PageLayout title="Калькулятор КБЖУ" subtitle="Расчёт суточной нормы и распределение макронутриентов.">
          <div className="nutrition-progress">
            {[
              ['Калории', 78, '1850 / 2400'],
              ['Белки', 66, '112 / 170 г'],
              ['Жиры', 59, '53 / 90 г'],
              ['Углеводы', 82, '240 / 290 г'],
            ].map(([label, value, text]) => (
              <div className="nutrition-row" key={label}>
                <strong>{label}</strong>
                <div className="progress-line"><span style={{ width: `${value}%` }} /></div>
                <span className="muted">{text}</span>
              </div>
            ))}
          </div>
        </PageLayout>
      )
    }

    if (active === 'programs') {
      return (
        <PageLayout title="Программы тренировок" subtitle="Каталог тренировочных сплитов под разные цели.">
          <div className="grid">
            {PROGRAMS.map(([title, desc]) => (
              <article className="stat-card" key={title}>
                <h3 className="card-title">{title}</h3>
                <p className="muted">{desc}</p>
                <button className="btn btn-ghost">Подробнее</button>
              </article>
            ))}
          </div>
        </PageLayout>
      )
    }

    if (active === 'exercises') {
      return (
        <PageLayout title="Справочник упражнений" subtitle="Поиск и фильтрация упражнений по группам мышц.">
          <div className="table-wrap">
            <table>
              <thead><tr><th>Упражнение</th><th>Группа мышц</th><th>Примечание</th></tr></thead>
              <tbody>
                {EXERCISES.map(([name, group, note]) => <tr key={name}><td>{name}</td><td>{group}</td><td>{note}</td></tr>)}
              </tbody>
            </table>
          </div>
        </PageLayout>
      )
    }

    if (active === 'diary') {
      return (
        <PageLayout title="Дневник" subtitle="Учёт тренировок и питания по дням.">
          <div className="grid stats-grid">
            <div className="stat-card"><div className="value">7</div><div className="label">Дней подряд</div></div>
            <div className="stat-card"><div className="value">18</div><div className="label">Записей в дневнике</div></div>
            <div className="stat-card"><div className="value">3</div><div className="label">Тренировки за неделю</div></div>
          </div>
        </PageLayout>
      )
    }

    if (active === 'progress') {
      return (
        <PageLayout title="Прогресс" subtitle="Визуализация динамики тела и нагрузок.">
          <div className="chart-container">
            <div className="chart-header">
              <h3 className="card-title">Динамика веса</h3>
              <div className="chart-legend"><div className="chart-legend-item"><span className="chart-legend-color" />Вес</div></div>
            </div>
            <svg className="chart-svg" viewBox="0 0 500 200">
              <polyline fill="none" stroke="var(--accent)" strokeWidth="3" points="0,150 70,138 140,130 210,118 280,102 350,108 420,95 500,88" />
            </svg>
          </div>
        </PageLayout>
      )
    }

    if (active === 'articles') {
      return (
        <PageLayout title="Статьи" subtitle="База знаний по тренировкам, питанию и восстановлению.">
          <div className="grid">
            {ARTICLES.map(([title, category]) => (
              <article className="card" key={title}>
                <div className="card-body">
                  <p className="pill">{category}</p>
                  <h3 className="card-title">{title}</h3>
                  <button className="btn btn-ghost">Читать</button>
                </div>
              </article>
            ))}
          </div>
        </PageLayout>
      )
    }

    if (active === 'auth') {
      return (
        <PageLayout title="Авторизация" subtitle="Формы входа, регистрации и восстановления пароля в React.">
          <div className="row">
            <div className="card"><div className="card-body"><h3 className="card-title">Вход</h3><button className="btn btn-primary">Войти</button></div></div>
            <div className="card"><div className="card-body"><h3 className="card-title">Регистрация</h3><button className="btn btn-ghost">Создать аккаунт</button></div></div>
          </div>
        </PageLayout>
      )
    }

    return (
      <PageLayout title="Админ-панель" subtitle="Управление пользователями, контентом и безопасностью.">
        <ul className="list-ideas">
          <li>Модерация контента</li>
          <li>Безопасность и аудит</li>
          <li>Управление упражнениями и программами</li>
        </ul>
      </PageLayout>
    )
  }, [active])

  return (
    <>
      <header className="navbar">
        <div className="nav-inner">
          <div className="brand"><span className="brand-badge">L</span>LDO <span>React</span></div>
          <nav className="nav-links">
            {NAV_ITEMS.map((item) => (
              <button
                type="button"
                className={`nav-link-btn ${active === item.key ? 'active' : ''}`}
                key={item.key}
                onClick={() => setActive(item.key)}
              >
                {item.label}
              </button>
            ))}
          </nav>
        </div>
      </header>
      <main className="container home-wrap">{content}</main>
    </>
  )
}

export default App
