import { Card, CardContent, Grid, TextField } from '@material-ui/core'
import Autocomplete from '../../App/Autocomplete'
import LeftFiltersGrid from '../../App/LeftFiltersGrid'

const profesionales = [{ nombre: 'asd' }]
const especialidades = [{ nombre: 'asdf' }]

const AgendaCard = () => (
  <Card>
    <CardContent>
      <Grid container spacing={1}>
        <Grid item xs={3}>
          <Autocomplete
            options={profesionales}
            getOptionLabel={option => option.nombre}
            renderInput={props => <TextField {...props} label='Profesionales' />}
          />
        </Grid>
        <Grid item xs={3}>
          <Autocomplete
            options={especialidades}
            getOptionLabel={option => option.nombre}
            renderInput={props => <TextField {...props} label='Especialidades' />}
          />
        </Grid>
      </Grid>
    </CardContent>
  </Card>
)

export default function Configuracion () {
  return (
    <LeftFiltersGrid
      renderFiltros='Filtros'
      renderData={<div>Configuración de agendas <AgendaCard /></div>}
    />
  )
}
