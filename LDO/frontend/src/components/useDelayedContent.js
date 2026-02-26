import { useEffect, useState } from 'react'

export function useDelayedContent(delay = 800) {
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    setIsLoading(true)
    const timer = setTimeout(() => setIsLoading(false), delay)

    return () => clearTimeout(timer)
  }, [delay])

  return isLoading
}
