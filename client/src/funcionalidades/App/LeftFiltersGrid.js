import { Container, Grid } from '@material-ui/core'
import { useRef } from 'react'
import { useFillHeight } from '../../utils/hooks'

export default function LeftFiltersGrid ({ renderFiltros, renderData }) {
  const ref = useRef()
  const height = useFillHeight(ref.current)

  return (
    <Grid container style={{ height: `${height}px`, overflowY: 'hidden' }} ref={ref}>
      <Grid item xs={3} style={{ height: '100%', overflowY: 'auto' }}>
        {renderFiltros}
      </Grid>
      <Grid item xs={9} style={{ height: '100%', overflowY: 'auto' }}>
        <Container>
          {renderData}
        </Container>
      </Grid>
    </Grid>
  )
}
