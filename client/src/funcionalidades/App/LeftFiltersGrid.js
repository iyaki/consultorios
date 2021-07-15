import { Container, Grid } from '@material-ui/core'

export default function LeftFiltersGrid ({ renderFiltros, renderData }) {
  return (
    <Grid container>
      <Grid item xs={3}>
        {renderFiltros}
      </Grid>
      <Grid item xs={9}>
        <Container>
          {renderData}
        </Container>
      </Grid>
    </Grid>
  )
}
