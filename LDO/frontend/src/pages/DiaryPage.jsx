import SectionSkeleton from '../components/SectionSkeleton'
import { useDelayedContent } from '../components/useDelayedContent'

function DiaryPage() {
  const isLoading = useDelayedContent(1000)

  if (isLoading) return <SectionSkeleton />

  return (
    <section className="content-card">
      <h2>Подгрузка дневника</h2>
      <ul>
        <li>Добавление тренировки с подходами, повторениями и рабочим весом.</li>
        <li>Фиксация приёмов пищи и автоматический подсчёт калорий и БЖУ.</li>
        <li>Быстрый просмотр заметок по самочувствию и восстановлению.</li>
      </ul>
    </section>
  )
}

export default DiaryPage
