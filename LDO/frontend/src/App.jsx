import { useEffect, useMemo, useState } from 'react'

const NAV = [
  ['home', 'Главная'],
  ['profile', 'Профиль'],
  ['kbju', 'КБЖУ'],
  ['programs', 'Программы'],
  ['exercises', 'Упражнения'],
  ['diary', 'Дневник'],
  ['progress', 'Прогресс'],
  ['articles', 'Статьи'],
  ['auth/login', 'Вход'],
  ['admin', 'Админ'],
]

const initialStore = {
  user: { name: 'Алексей', email: 'alex@ldo.local', height: 182, weight: 79, age: 27, sex: 'male', activity: 'medium', goal: 'cut' },
  programs: [
    { id: 1, title: 'Сушка 4 недели', description: '4 тренировки/нед, дефицит калорий, кардио после силовой' },
    { id: 2, title: 'Набор массы', description: '5 тренировок/нед, базовые упражнения, профицит калорий' },
  ],
  exercises: [
    { id: 1, name: 'Приседания', muscle: 'Ноги', level: 'Средний' },
    { id: 2, name: 'Жим лёжа', muscle: 'Грудь', level: 'Средний' },
    { id: 3, name: 'Тяга блока', muscle: 'Спина', level: 'Лёгкий' },
  ],
  meals: [{ id: 1, product: 'Куриная грудка', kcal: 165, protein: 31, fat: 3.6, carbs: 0 }],
  diary: [
    { id: 1, date: '2026-02-20', duration: 55, mood: 'Отлично', bodyWeight: 79, notes: 'Тяга + присед', exercises: [{ name: 'Присед', sets: 4, reps: 8, weight: 80 }], meals: [{ type: 'Обед', product: 'Куриная грудка', grams: 200 }] },
  ],
  weightHistory: [
    { date: '2026-01-10', value: 82 },
    { date: '2026-01-24', value: 81.2 },
    { date: '2026-02-10', value: 80.3 },
    { date: '2026-02-24', value: 79 },
  ],
  articles: [
    { id: 1, title: 'Как считать калории правильно', category: 'Питание', content: 'Считайте КБЖУ по каждому продукту и сверяйте с целью.' },
    { id: 2, title: '5 ошибок новичка в зале', category: 'Тренировки', content: 'Избегайте рывков, следите за техникой и восстановлением.' },
  ],
  comments: [
    { id: 1, articleId: 1, text: 'Очень полезно!', status: 'pending' },
  ],
  users: [
    { id: 1, email: 'admin@ldo.local', role: 'admin', blocked: false },
    { id: 2, email: 'alex@ldo.local', role: 'user', blocked: false },
  ],
  adminLogs: [{ id: 1, action: 'Создан пользователь', ts: '2026-02-20 12:30' }],
}

function useStore() {
  const [store, setStore] = useState(() => {
    const raw = localStorage.getItem('ldo-react-store')
    return raw ? JSON.parse(raw) : initialStore
  })

  useEffect(() => {
    localStorage.setItem('ldo-react-store', JSON.stringify(store))
  }, [store])

  return [store, setStore]
}

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

function Shell({ children, route, navigate }) {
  return (
    <>
      <header className="navbar">
        <div className="nav-inner">
          <div className="brand"><span className="brand-badge">L</span>LDO <span>React</span></div>
          <nav className="nav-links">
            {NAV.map(([key, label]) => (
              <button key={key} type="button" className={`nav-link-btn ${route === key ? 'active' : ''}`} onClick={() => navigate(key)}>{label}</button>
            ))}
          </nav>
        </div>
      </header>
      <main className="container home-wrap">{children}</main>
    </>
  )
}

function Card({ title, subtitle, children }) {
  return (
    <section className="card card-accent">
      <div className="card-body">
        <h1 className="page-head">{title}</h1>
        {subtitle && <p className="page-subtitle muted">{subtitle}</p>}
        {children}
      </div>
    </section>
  )
}

function Home({ navigate, store }) {
  return <Card title="LDO — React SPA" subtitle="Все страницы из app перенесены в React-оболочку с функционалом на клиенте.">
    <div className="grid grid-2">
      <div className="card"><div className="card-body"><h3 className="card-title">Быстрые действия</h3><div className="action-row"><button className="btn btn-primary" onClick={() => navigate('diary/add')}>Добавить тренировку</button><button className="btn btn-ghost" onClick={() => navigate('kbju')}>Открыть КБЖУ</button></div></div></div>
      <div className="card"><div className="card-body"><h3 className="card-title">Сводка</h3><div className="kpi"><div className="item"><div className="num">{store.diary.length}</div><div className="muted">Записей дневника</div></div><div className="item"><div className="num">{store.articles.length}</div><div className="muted">Статей</div></div></div></div></div>
    </div>
  </Card>
}

function Profile({ store, setStore }) {
  const [form, setForm] = useState(store.user)
  const save = () => setStore((s) => ({ ...s, user: form, weightHistory: [...s.weightHistory, { date: new Date().toISOString().slice(0, 10), value: Number(form.weight) }] }))
  return <Card title="Профиль" subtitle="Редактирование профиля, параметров тела и цели.">
    <div className="form">
      <div className="row"><label>Имя<input value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} /></label><label>Email<input value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} /></label></div>
      <div className="row"><label>Рост<input type="number" value={form.height} onChange={(e) => setForm({ ...form, height: Number(e.target.value) })} /></label><label>Вес<input type="number" value={form.weight} onChange={(e) => setForm({ ...form, weight: Number(e.target.value) })} /></label></div>
      <div className="row"><label>Цель<select value={form.goal} onChange={(e) => setForm({ ...form, goal: e.target.value })}><option value="cut">Снижение</option><option value="maintain">Поддержание</option><option value="bulk">Набор</option></select></label><label>Активность<select value={form.activity} onChange={(e) => setForm({ ...form, activity: e.target.value })}><option value="low">Низкая</option><option value="medium">Средняя</option><option value="high">Высокая</option></select></label></div>
      <button type="button" className="btn btn-primary" onClick={save}>Сохранить</button>
    </div>
  </Card>
}

function Kbju({ store }) {
  const { weight, height, age, sex, activity, goal } = store.user
  const bmr = useMemo(() => (10 * weight) + (6.25 * height) - (5 * age) + (sex === 'male' ? 5 : -161), [weight, height, age, sex])
  const mult = { low: 1.2, medium: 1.55, high: 1.725 }[activity] || 1.2
  const adjust = { cut: -350, maintain: 0, bulk: 300 }[goal] || 0
  const kcal = Math.round(bmr * mult + adjust)
  const protein = Math.round(weight * (goal === 'bulk' ? 2 : 2.2))
  const fat = Math.round(weight * 0.9)
  const carbs = Math.round((kcal - (protein * 4 + fat * 9)) / 4)
  return <Card title="КБЖУ" subtitle="Рассчёт по данным профиля и цели.">
    <div className="stats-grid grid"><div className="stat-card"><div className="value">{kcal}</div><div className="label">Ккал/день</div></div><div className="stat-card"><div className="value">{protein} г</div><div className="label">Белки</div></div><div className="stat-card"><div className="value">{fat} г</div><div className="label">Жиры</div></div><div className="stat-card"><div className="value">{carbs} г</div><div className="label">Углеводы</div></div></div>
  </Card>
}

const Table = ({ columns, rows }) => <div className="table-wrap"><table><thead><tr>{columns.map((c) => <th key={c}>{c}</th>)}</tr></thead><tbody>{rows.map((r, i) => <tr key={i}>{r.map((c, j) => <td key={j}>{c}</td>)}</tr>)}</tbody></table></div>

function Programs({ store, setStore }) {
  const [title, setTitle] = useState('')
  const [description, setDescription] = useState('')
  const add = () => {
    if (!title || !description) return
    setStore((s) => ({ ...s, programs: [...s.programs, { id: Date.now(), title, description }] }))
    setTitle(''); setDescription('')
  }
  return <Card title="Программы" subtitle="Просмотр и добавление тренировочных программ.">
    <Table columns={['Название', 'Описание']} rows={store.programs.map((p) => [p.title, p.description])} />
    <div className="row section-spacer"><label>Название<input value={title} onChange={(e) => setTitle(e.target.value)} /></label><label>Описание<input value={description} onChange={(e) => setDescription(e.target.value)} /></label></div>
    <button className="btn btn-primary" type="button" onClick={add}>Добавить программу</button>
  </Card>
}

function Exercises({ store, setStore }) {
  const [form, setForm] = useState({ name: '', muscle: '', level: 'Лёгкий' })
  const add = () => {
    if (!form.name || !form.muscle) return
    setStore((s) => ({ ...s, exercises: [...s.exercises, { id: Date.now(), ...form }] }))
    setForm({ name: '', muscle: '', level: 'Лёгкий' })
  }
  return <Card title="Упражнения" subtitle="Справочник упражнений и групп мышц.">
    <Table columns={['Упражнение', 'Группа', 'Сложность']} rows={store.exercises.map((e) => [e.name, e.muscle, e.level])} />
    <div className="row section-spacer"><label>Упражнение<input value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} /></label><label>Группа мышц<input value={form.muscle} onChange={(e) => setForm({ ...form, muscle: e.target.value })} /></label></div>
    <button type="button" className="btn btn-primary" onClick={add}>Добавить</button>
  </Card>
}

function Diary({ store, navigate, setStore }) {
  const del = (id) => setStore((s) => ({ ...s, diary: s.diary.filter((d) => d.id !== id) }))
  return <Card title="Дневник" subtitle="Список записей, просмотр и удаление.">
    <div className="action-row"><button className="btn btn-primary" onClick={() => navigate('diary/add')}>Новая запись</button></div>
    <Table columns={['Дата', 'Длительность', 'Самочувствие', 'Действия']} rows={store.diary.map((d) => [d.date, `${d.duration} мин`, d.mood, <span key={d.id}><button className="btn btn-ghost btn-inline" onClick={() => navigate(`diary/view/${d.id}`)}>Открыть</button> <button className="btn btn-ghost btn-inline" onClick={() => navigate(`diary/edit/${d.id}`)}>Ред.</button> <button className="btn btn-danger btn-inline" onClick={() => del(d.id)}>Удалить</button></span>])} />
  </Card>
}

function DiaryForm({ store, setStore, navigate, id }) {
  const current = store.diary.find((d) => String(d.id) === id)
  const [form, setForm] = useState(current || { date: new Date().toISOString().slice(0, 10), duration: 45, mood: '', bodyWeight: store.user.weight, notes: '', exercises: [], meals: [] })
  const save = () => {
    if (id) {
      setStore((s) => ({ ...s, diary: s.diary.map((d) => String(d.id) === id ? { ...form, id: d.id } : d) }))
    } else {
      setStore((s) => ({ ...s, diary: [...s.diary, { ...form, id: Date.now() }] }))
    }
    navigate('diary')
  }
  return <Card title={id ? 'Редактирование записи' : 'Новая запись'} subtitle="Детальная тренировка: длительность, вес, самочувствие, заметки.">
    <div className="row"><label>Дата<input type="date" value={form.date} onChange={(e) => setForm({ ...form, date: e.target.value })} /></label><label>Длительность<input type="number" value={form.duration} onChange={(e) => setForm({ ...form, duration: Number(e.target.value) })} /></label></div>
    <div className="row"><label>Самочувствие<input value={form.mood} onChange={(e) => setForm({ ...form, mood: e.target.value })} /></label><label>Вес тела<input type="number" value={form.bodyWeight} onChange={(e) => setForm({ ...form, bodyWeight: Number(e.target.value) })} /></label></div>
    <label>Заметки<input value={form.notes} onChange={(e) => setForm({ ...form, notes: e.target.value })} /></label>
    <div className="action-row section-spacer"><button type="button" className="btn btn-primary" onClick={save}>Сохранить</button><button type="button" className="btn btn-ghost" onClick={() => navigate('diary')}>Отмена</button></div>
  </Card>
}

function DiaryView({ store, navigate, id }) {
  const entry = store.diary.find((d) => String(d.id) === id)
  if (!entry) return <Card title="Запись не найдена" />
  return <Card title={`Тренировка ${entry.date}`} subtitle={`Длительность: ${entry.duration} мин, самочувствие: ${entry.mood}`}>
    <p className="muted">{entry.notes || 'Без заметок.'}</p>
    <button className="btn btn-ghost" onClick={() => navigate('diary')}>Назад к дневнику</button>
  </Card>
}

function Progress({ store }) {
  const points = store.weightHistory
  const max = Math.max(...points.map((p) => p.value))
  const min = Math.min(...points.map((p) => p.value))
  const poly = points.map((p, i) => {
    const x = (i / Math.max(points.length - 1, 1)) * 500
    const y = ((max - p.value) / Math.max(max - min, 1)) * 160 + 20
    return `${x},${y}`
  }).join(' ')
  return <Card title="Прогресс" subtitle="График веса на основе дневника и профиля.">
    <div className="chart-container"><svg className="chart-svg" viewBox="0 0 500 200"><polyline fill="none" stroke="var(--accent)" strokeWidth="3" points={poly} /></svg></div>
    <Table columns={['Дата', 'Вес']} rows={points.map((p) => [p.date, `${p.value} кг`])} />
  </Card>
}

function Articles({ store, navigate, setStore }) {
  const [search, setSearch] = useState('')
  const [title, setTitle] = useState('')
  const [category, setCategory] = useState('')
  const [content, setContent] = useState('')
  const filtered = store.articles.filter((a) => a.title.toLowerCase().includes(search.toLowerCase()))
  const add = () => {
    if (!title || !category || !content) return
    setStore((s) => ({ ...s, articles: [...s.articles, { id: Date.now(), title, category, content }] }))
    setTitle(''); setCategory(''); setContent('')
  }
  return <Card title="Статьи" subtitle="Список статей, просмотр и добавление.">
    <label>Поиск<input value={search} onChange={(e) => setSearch(e.target.value)} /></label>
    <div className="grid section-spacer">{filtered.map((a) => <article key={a.id} className="card"><div className="card-body"><p className="pill">{a.category}</p><h3 className="card-title">{a.title}</h3><button className="btn btn-ghost" onClick={() => navigate(`article/${a.id}`)}>Открыть</button></div></article>)}</div>
    <div className="row section-spacer"><label>Новая статья<input value={title} onChange={(e) => setTitle(e.target.value)} /></label><label>Категория<input value={category} onChange={(e) => setCategory(e.target.value)} /></label></div>
    <label>Текст<input value={content} onChange={(e) => setContent(e.target.value)} /></label>
    <button className="btn btn-primary" onClick={add}>Добавить статью</button>
  </Card>
}

function ArticleView({ store, id, setStore }) {
  const article = store.articles.find((a) => String(a.id) === id)
  const [comment, setComment] = useState('')
  if (!article) return <Card title="Статья не найдена" />
  const submit = () => {
    if (!comment) return
    setStore((s) => ({ ...s, comments: [...s.comments, { id: Date.now(), articleId: article.id, text: comment, status: 'pending' }] }))
    setComment('')
  }
  return <Card title={article.title} subtitle={article.category}>
    <p>{article.content}</p>
    <label>Комментарий<input value={comment} onChange={(e) => setComment(e.target.value)} /></label>
    <button className="btn btn-primary" onClick={submit}>Отправить</button>
  </Card>
}

function Auth({ type, setStore, navigate }) {
  const [form, setForm] = useState({ name: '', email: '', password: '' })
  const submit = () => {
    if (type === 'register') {
      setStore((s) => ({ ...s, users: [...s.users, { id: Date.now(), email: form.email, role: 'user', blocked: false }], user: { ...s.user, name: form.name || s.user.name, email: form.email || s.user.email } }))
    }
    if (type === 'login') setStore((s) => ({ ...s, user: { ...s.user, email: form.email || s.user.email } }))
    navigate('home')
  }
  return <Card title={type === 'register' ? 'Регистрация' : type === 'reset' ? 'Сброс пароля' : 'Вход'} subtitle="Формы авторизации как React-компоненты.">
    {type === 'register' && <label>Имя<input value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} /></label>}
    <label>Email<input type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} /></label>
    <label>{type === 'reset' ? 'Новый пароль' : 'Пароль'}<input type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} /></label>
    <div className="action-row"><button className="btn btn-primary" onClick={submit}>Отправить</button><button className="btn btn-ghost" onClick={() => navigate(type === 'login' ? 'auth/register' : 'auth/login')}>{type === 'login' ? 'Регистрация' : 'Вход'}</button></div>
  </Card>
}

function Admin({ store, setStore }) {
  const approve = (id) => setStore((s) => ({ ...s, comments: s.comments.map((c) => c.id === id ? { ...c, status: 'approved' } : c), adminLogs: [...s.adminLogs, { id: Date.now(), action: `Одобрен комментарий #${id}`, ts: new Date().toISOString().replace('T', ' ').slice(0, 16) }] }))
  const toggleUser = (id) => setStore((s) => ({ ...s, users: s.users.map((u) => u.id === id ? { ...u, blocked: !u.blocked } : u) }))
  return <Card title="Админ-панель" subtitle="Покрыты страницы админки: пользователи, модерация, безопасность и журналы.">
    <h3 className="card-title">Модерация</h3>
    <Table columns={['ID', 'Комментарий', 'Статус', 'Действие']} rows={store.comments.map((c) => [c.id, c.text, c.status, <button key={c.id} className="btn btn-ghost btn-inline" onClick={() => approve(c.id)}>Одобрить</button>])} />
    <h3 className="card-title section-spacer">Пользователи</h3>
    <Table columns={['Email', 'Роль', 'Статус', 'Действие']} rows={store.users.map((u) => [u.email, u.role, u.blocked ? 'Заблокирован' : 'Активен', <button key={u.id} className="btn btn-ghost btn-inline" onClick={() => toggleUser(u.id)}>{u.blocked ? 'Разблокировать' : 'Блокировать'}</button>])} />
    <h3 className="card-title section-spacer">Логи админа</h3>
    <Table columns={['Время', 'Действие']} rows={store.adminLogs.map((l) => [l.ts, l.action])} />
  </Card>
}

function App() {
  const { route, navigate } = useHashRoute()
  const [store, setStore] = useStore()

  const page = (() => {
    const parts = route.split('/')
    if (route === 'home') return <Home navigate={navigate} store={store} />
    if (route === 'profile') return <Profile store={store} setStore={setStore} />
    if (route === 'kbju') return <Kbju store={store} />
    if (route === 'programs') return <Programs store={store} setStore={setStore} />
    if (route === 'exercises') return <Exercises store={store} setStore={setStore} />
    if (route === 'diary') return <Diary store={store} setStore={setStore} navigate={navigate} />
    if (route === 'diary/add') return <DiaryForm store={store} setStore={setStore} navigate={navigate} />
    if (parts[0] === 'diary' && parts[1] === 'edit') return <DiaryForm store={store} setStore={setStore} navigate={navigate} id={parts[2]} />
    if (parts[0] === 'diary' && parts[1] === 'view') return <DiaryView store={store} id={parts[2]} navigate={navigate} />
    if (route === 'progress') return <Progress store={store} />
    if (route === 'articles') return <Articles store={store} setStore={setStore} navigate={navigate} />
    if (parts[0] === 'article') return <ArticleView store={store} id={parts[1]} setStore={setStore} />
    if (route === 'auth/login') return <Auth type="login" navigate={navigate} setStore={setStore} />
    if (route === 'auth/register') return <Auth type="register" navigate={navigate} setStore={setStore} />
    if (route === 'auth/reset') return <Auth type="reset" navigate={navigate} setStore={setStore} />
    if (route === 'admin') return <Admin store={store} setStore={setStore} />
    return <Card title="404" subtitle={`Маршрут ${route} не найден`}><button className="btn btn-primary" onClick={() => navigate('home')}>На главную</button></Card>
  })()

  return <Shell route={route} navigate={navigate}>{page}</Shell>
}

export default App
