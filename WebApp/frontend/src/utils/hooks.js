import { useLayoutEffect, useState } from 'react'

export function useFillHeight (element, bottomElement) {
  const [height, setHeight] = useState(calculateHeight())

  function calculateHeight () {
    const top = (
      element
        ? element.offsetTop
        : 0
    )

    const bottom = (
      bottomElement
        ? bottomElement.offsetTop
        : Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0)
    )

    return bottom - top
  }

  function resize () {
    setHeight(calculateHeight())
  }

  window.removeEventListener('resize', resize)
  window.addEventListener('resize', resize)

  useLayoutEffect(() => { setHeight(calculateHeight()) })

  return height
}
