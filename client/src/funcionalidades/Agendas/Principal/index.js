import { Route, Switch, useRouteMatch } from 'react-router-dom'
import NotFound from '../../App/NotFound'
import Especialidades from '../Administracion/Especialidades'
import Profesionales from '../Administracion/Profesionales'
import Configuracion from '../Configuracion'

export default function Prinicpal () {
  const { path } = useRouteMatch()

  return (
    <Switch>
        <Route path={`${path}/especialidades`}>
          <Especialidades />
        </Route>
      <Route path='*' component={NotFound} />
    </Switch>
  )
}
