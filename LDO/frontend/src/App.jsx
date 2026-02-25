import { useEffect, useMemo, useState } from 'react'
import './App.css'

const API_BASE = import.meta.env.VITE_API_BASE || 'http://127.0.0.1:8080/api.php'

const activityLabels = {
  sedentary: 'Минимальная',
  light: 'Лёгкая',
  moderate: 'Средняя',
  active: 'Высокая',
  very: 'Очень высокая',
}

const goalLabels = {
  maintain: 'Поддержание',
  lose: 'Похудение',
  gain: 'Набор массы',
}

const mealTypeLabels = {
  breakfast: 'Завтрак',
  lunch: 'Обед',
  dinner: 'Ужин',
  snack: 'Перекус',
}

const appSections = [
  { key: 'home', label: 'Главная' },
  { key: 'profile', label: 'Профиль' },
  { key: 'kbju', label: 'КБЖУ' },
  { key: 'programs', label: 'Программы' },
  { key: 'exercises', label: 'Упражнения' },
  { key: 'diary', label: 'Дневник' },
  { key: 'progress', label: 'Прогресс' },
  { key: 'articles', label: 'Статьи' },
]

async function api(endpoint, options = {}) {
  const res = await fetch(`${API_BASE}?endpoint=${endpoint}`, {
    credentials: 'include',
    headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
    ...options,
  })

  const data = await res.json().catch(() => ({}))
  if (!res.ok || data.ok === false) {
    throw new Error(data.error || `HTTP ${res.status}`)
  }
  return data
}

function PlaceholderPage({ title }) {
  return (
    <div className="card">
      <h2>{title}</h2>
      <p>
        Этот модуль переведён на React-оболочку. Для полного переноса бизнес-логики
        нужно подключение соответствующих API endpoint'ов.
      </p>
    </div>
  )
}

function ProfilePage({ profile, onProfileChange, foods, meals, onAddMeal, onDeleteMeal, alert }) {
  const [form, setForm] = useState(profile)
  const [mealForm, setMealForm] = useState({ meal_type: 'snack', food_item_id: '', amount_g: 100 })

  useEffect(() => {
    setForm(profile)
  }, [profile])

  const submitProfile = async (event) => {
    event.preventDefault()
    await onProfileChange(form)
  }

  const submitMeal = async (event) => {
    event.preventDefault()
    await onAddMeal(mealForm)
    setMealForm((prev) => ({ ...prev, amount_g: 100 }))
  }

  return (
    <div className="grid">
      <div className="card">
        <h2>Профиль</h2>
        {alert && <div className={`flash ${alert.type}`}>{alert.message}</div>}
        <form onSubmit={submitProfile} className="form-grid">
          <label>Рост (см)
            <input type="number" value={form.height_cm || ''} onChange={(e) => setForm({ ...form, height_cm: Number(e.target.value) || null })} />
          </label>
          <label>Вес (кг)
            <input type="number" step="0.1" value={form.weight_kg || ''} onChange={(e) => setForm({ ...form, weight_kg: Number(e.target.value) || null })} />
          </label>
          <label>Возраст
            <input type="number" value={form.age || ''} onChange={(e) => setForm({ ...form, age: Number(e.target.value) || null })} />
          </label>
          <label>Пол
            <select value={form.gender || ''} onChange={(e) => setForm({ ...form, gender: e.target.value || null })}>
              <option value="">—</option>
              <option value="male">Мужской</option>
              <option value="female">Женский</option>
            </select>
          </label>
          <label>Активность
            <select value={form.activity_level || 'moderate'} onChange={(e) => setForm({ ...form, activity_level: e.target.value })}>
              {Object.entries(activityLabels).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
            </select>
          </label>
          <label>Цель
            <select value={form.goal || 'maintain'} onChange={(e) => setForm({ ...form, goal: e.target.value })}>
              {Object.entries(goalLabels).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
            </select>
          </label>
          <button type="submit">Сохранить профиль</button>
        </form>
      </div>

      <div className="card">
        <h2>Питание за сегодня</h2>
        <form onSubmit={submitMeal} className="form-grid compact">
          <label>Приём пищи
            <select value={mealForm.meal_type} onChange={(e) => setMealForm({ ...mealForm, meal_type: e.target.value })}>
              {Object.entries(mealTypeLabels).map(([k, v]) => <option key={k} value={k}>{v}</option>)}
            </select>
          </label>
          <label>Продукт
            <select
              value={mealForm.food_item_id}
              onChange={(e) => setMealForm({ ...mealForm, food_item_id: e.target.value })}
              required
            >
              <option value="">Выберите продукт</option>
              {foods.map((food) => <option key={food.id} value={food.id}>{food.name}</option>)}
            </select>
          </label>
          <label>Количество (г)
            <input
              type="number"
              min="1"
              value={mealForm.amount_g}
              onChange={(e) => setMealForm({ ...mealForm, amount_g: Number(e.target.value) || 0 })}
              required
            />
          </label>
          <button type="submit">Добавить продукт</button>
        </form>

        <table>
          <thead>
            <tr><th>Приём</th><th>Продукт</th><th>Г</th><th>Ккал</th><th>Б</th><th>Ж</th><th>У</th><th /></tr>
          </thead>
          <tbody>
            {meals.length === 0 && (
              <tr><td colSpan="8" className="muted">Пока нет записей о еде.</td></tr>
            )}
            {meals.map((meal) => (
              <tr key={meal.id}>
                <td>{mealTypeLabels[meal.meal_type] || '—'}</td>
                <td>{meal.food_item_name || meal.food_name || 'Продукт'}</td>
                <td>{meal.amount_g}</td>
                <td>{meal.calories}</td>
                <td>{meal.protein}</td>
                <td>{meal.fat}</td>
                <td>{meal.carbs}</td>
                <td>
                  <button className="ghost" onClick={() => onDeleteMeal(meal.id)} type="button">×</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

function KbjuPage({ profile, total, meals }) {
  const ready = profile?.weight_kg && profile?.height_cm && profile?.age
  return (
    <div className="grid">
      <div className="card">
        <h2>КБЖУ</h2>
        {!ready ? (
          <p>Заполните профиль, чтобы видеть вашу целевую норму.</p>
        ) : (
          <p>
            Параметры: {profile.weight_kg} кг, {profile.height_cm} см, {profile.age} лет, {activityLabels[profile.activity_level || 'moderate']}, {goalLabels[profile.goal || 'maintain']}.
          </p>
        )}
      </div>
      <div className="card">
        <h2>Съедено сегодня</h2>
        <div className="kpi-row">
          <div><strong>{total.calories || 0}</strong><span>ккал</span></div>
          <div><strong>{total.protein || 0}</strong><span>Б</span></div>
          <div><strong>{total.fat || 0}</strong><span>Ж</span></div>
          <div><strong>{total.carbs || 0}</strong><span>У</span></div>
        </div>
        <ul className="meal-list">
          {meals.length === 0 && <li className="muted">Пока нет добавленных продуктов.</li>}
          {meals.map((meal) => (
            <li key={meal.id}>
              <span>{mealTypeLabels[meal.meal_type] || '—'} · {meal.food_item_name || meal.food_name}</span>
              <strong>{meal.calories} ккал</strong>
            </li>
          ))}
        </ul>
      </div>
    </div>
  )
}

function App() {
  const [section, setSection] = useState('home')
  const [profile, setProfile] = useState({})
  const [foods, setFoods] = useState([])
  const [nutrition, setNutrition] = useState({ total: {}, meals: [] })
  const [alert, setAlert] = useState(null)
  const [loading, setLoading] = useState(true)

  const bootstrap = async () => {
    try {
      setLoading(true)
      const [profileData, foodsData, nutritionData] = await Promise.all([
        api('profile'),
        api('foods'),
        api('nutrition-today'),
      ])
      setProfile(profileData.profile || {})
      setFoods(foodsData.foods || [])
      setNutrition({ total: nutritionData.total || {}, meals: nutritionData.meals || [] })
    } catch (error) {
      setAlert({ type: 'err', message: `Ошибка загрузки: ${error.message}` })
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    bootstrap()
  }, [])

  const saveProfile = async (payload) => {
    try {
      const response = await api('profile', { method: 'POST', body: JSON.stringify(payload) })
      setProfile(response.profile || {})
      setAlert({ type: 'ok', message: 'Профиль обновлён.' })
    } catch (error) {
      setAlert({ type: 'err', message: error.message })
    }
  }

  const refreshNutrition = async () => {
    const nutritionData = await api('nutrition-today')
    setNutrition({ total: nutritionData.total || {}, meals: nutritionData.meals || [] })
  }

  const addMeal = async (payload) => {
    try {
      await api('meal-add', { method: 'POST', body: JSON.stringify(payload) })
      await refreshNutrition()
      setAlert({ type: 'ok', message: 'Продукт добавлен.' })
    } catch (error) {
      setAlert({ type: 'err', message: error.message })
    }
  }

  const deleteMeal = async (mealId) => {
    try {
      await api('meal-delete', { method: 'POST', body: JSON.stringify({ meal_id: mealId }) })
      await refreshNutrition()
      setAlert({ type: 'ok', message: 'Запись удалена.' })
    } catch (error) {
      setAlert({ type: 'err', message: error.message })
    }
  }

  const renderedPage = useMemo(() => {
    if (section === 'profile') {
      return (
        <ProfilePage
          profile={profile}
          onProfileChange={saveProfile}
          foods={foods}
          meals={nutrition.meals}
          onAddMeal={addMeal}
          onDeleteMeal={deleteMeal}
          alert={alert}
        />
      )
    }

    if (section === 'kbju') {
      return <KbjuPage profile={profile} total={nutrition.total} meals={nutrition.meals} />
    }

    if (section === 'home') {
      return (
        <div className="card">
          <h1>LDO на React</h1>
          <p>
            Проект переведён на единое React-приложение: навигация, профиль, питание и КБЖУ теперь
            работают как SPA.
          </p>
        </div>
      )
    }

    const title = appSections.find((item) => item.key === section)?.label || 'Раздел'
    return <PlaceholderPage title={title} />
  }, [section, profile, foods, nutrition, alert])

  return (
    <div className="app-shell">
      <aside className="sidebar">
        <h2>LDO</h2>
        <nav>
          {appSections.map((item) => (
            <button
              key={item.key}
              className={section === item.key ? 'active' : ''}
              onClick={() => setSection(item.key)}
              type="button"
            >
              {item.label}
            </button>
          ))}
        </nav>
      </aside>
      <main className="content">
        {loading ? <div className="card">Загрузка...</div> : renderedPage}
      </main>
    </div>
  )
}

export default App
