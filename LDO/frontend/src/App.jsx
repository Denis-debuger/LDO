import { useEffect, useState } from 'react'
import './App.css'

const statusMessages = [
  'Инициализация интерфейса…',
  'Загрузка данных профиля…',
  'Почти готово…',
]

function Loader({ progress }) {
  const statusIndex = Math.min(
    statusMessages.length - 1,
    Math.floor((progress / 100) * statusMessages.length),
  )

  return (
    <div className="loader-screen" role="status" aria-live="polite">
      <div className="loader-card">
        <div className="loader-brand">LDO</div>
        <div
          className="loader-ring"
          aria-hidden="true"
          style={{ '--progress': `${progress}%` }}
        >
          <div className="loader-ring-inner" />
        </div>
        <p className="loader-percent">{progress}%</p>
        <p className="loader-message">{statusMessages[statusIndex]}</p>
      </div>
    </div>
  )
}

function AppContent() {
  return (
    <main className="app-content">
      <h1>Добро пожаловать в LDO</h1>
      <p>
        Кастомная загрузка подключена. Здесь может быть ваш основной контент, дашборд
        или главная лента.
      </p>
      <button type="button">Перейти в проект</button>
    </main>
  )
}

function App() {
  const [progress, setProgress] = useState(0)
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const timer = setInterval(() => {
      setProgress((previous) => {
        const nextValue = Math.min(previous + 5, 100)

        if (nextValue >= 100) {
          clearInterval(timer)
          setTimeout(() => setIsLoading(false), 250)
        }

        return nextValue
      })
    }, 90)

    return () => clearInterval(timer)
  }, [])

  return isLoading ? <Loader progress={progress} /> : <AppContent />
}

export default App
