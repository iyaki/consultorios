import { Card, CardContent, Container, Grid, Typography } from '@material-ui/core'
import { useRef } from 'react'
import { useFillHeight } from '../../utils/hooks'

export default function LeftFiltersGrid ({
  renderFiltros,
  renderData,
  hint
}) {
  const ref = useRef()
  const height = useFillHeight(ref.current)

  return (
    <Grid container style={{ height: `${height}px`, overflowY: 'hidden' }} ref={ref}>
      <Grid item xs={3} style={{ height: '100%', overflowY: 'auto' }}>
        <Card style={{ height: '96%', margin: '2%' }}>
          <CardContent>
            <Hint hint={hint} />
            {renderFiltros}
          </CardContent>
        </Card>
      </Grid>
      <Grid item xs={9} style={{ height: '100%', overflowY: 'auto' }}>
        <Container>
          {renderData}
        </Container>
      </Grid>
    </Grid>
  )
}

function Hint ({ hint }) {
  if (hint) {
    return (
      <>
        <Typography color='textSecondary' gutterBottom>
          {hint}
        </Typography>
        <hr />
        <br />
      </>
    )
  }

  return ''
}
