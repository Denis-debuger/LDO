import SectionSkeleton from '../components/SectionSkeleton'
import { useDelayedContent } from '../components/useDelayedContent'

function HomePage() {
  const isLoading = useDelayedContent(700)

  if (isLoading) return <SectionSkeleton />

  return (
    <section className="content-card">
      <h2>Что делает сайт</h2>
      <p>
        LDO помогает пользователям следить за тренировками, питанием и динамикой веса в
        одном месте.
      </p>
      <p>
        Вы можете вести личный профиль, рассчитывать КБЖУ и формировать устойчивые привычки
        через ежедневные записи.
      </p>
    </section>
  )
}

export default HomePage
