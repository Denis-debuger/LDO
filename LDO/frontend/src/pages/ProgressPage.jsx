import SectionSkeleton from '../components/SectionSkeleton'
import { useDelayedContent } from '../components/useDelayedContent'

function ProgressPage() {
  const isLoading = useDelayedContent(1200)

  if (isLoading) return <SectionSkeleton />

  return (
    <section className="content-card">
      <h2>Аналитика прогресса</h2>
      <p>В этом разделе отображается динамика веса и регулярность занятий.</p>
      <p>
        Добавьте графики по объёмам тренировок и достижениям, чтобы видеть результаты в
        реальном времени.
      </p>
    </section>
  )
}

export default ProgressPage
