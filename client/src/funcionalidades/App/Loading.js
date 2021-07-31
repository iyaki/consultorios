import { CircularProgress } from '@material-ui/core'
import { useRef } from 'react'
import { useFillHeight } from '../../utils/hooks'

export default function Loading () {
  const ref = useRef()
  const height = useFillHeight(ref.current)
  return (
    <div
      style={{
        width: '100%',
        height: `${height}px`,
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center'
      }}
      ref={ref}
    >
      <CircularProgress />
    </div>
  )
}
