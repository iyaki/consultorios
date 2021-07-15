import { Card, CardContent, Grid, IconButton, TextField } from '@material-ui/core'
import DeleteIcon from '@material-ui/icons/Delete'
import SaveIcon from '@material-ui/icons/Save'
import SearchIcon from '@material-ui/icons/Search'
import ClearIcon from '@material-ui/icons/Clear'
import { useState } from 'react'
import LeftFiltersGrid from '../../App/LeftFiltersGrid'

const EspecialiadesList = () => (
  <div>
    <Card style={{ marginTop: '1%' }}>
      <CardContent>
        <Grid container spacing={1}>
          <Grid item xs={9}>
            <TextField label='Nombre' style={{ width: '100%' }} />
          </Grid>
          <Grid item xs={3} style={{ textAlign: 'center' }}>
            <IconButton>
              <SaveIcon />
            </IconButton>
            <IconButton hidden>
              <DeleteIcon />
            </IconButton>
          </Grid>
        </Grid>
      </CardContent>
    </Card>
  </div>
)

function Filters () {
  function calculateCardHeight () {
    const appBarHeight = document.querySelector('#AppBar').offsetHeight
    const viewportHeight = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0)
    return viewportHeight - appBarHeight
  }

  const [height, setHeight] = useState(calculateCardHeight())

  window.addEventListener('resize', () => {
    setHeight(calculateCardHeight())
  })

  return (
    <Card style={{ minHeight: `${height}px` }}>
      <CardContent>
        <TextField label='Nombre' style={{ width: '60%' }} />
        <IconButton>
          <SearchIcon />
        </IconButton>
        <IconButton>
          <ClearIcon />
        </IconButton>
      </CardContent>
    </Card>
  )
}

export default function Especialidades () {
  return (
    <LeftFiltersGrid
      renderFiltros={<Filters />}
      renderData={<EspecialiadesList />}
    />
  )
}
