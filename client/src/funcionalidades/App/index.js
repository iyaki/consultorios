import { Route, Switch } from 'react-router-dom'
import './styles.css'

function App () {
  return (
    <div>
      <Switch>
        <Route exact path='/'>
          /
        </Route>
        <Route exact path='/agendas'>
          /agendas
        </Route>
        <Route exact path='/facturacion'>
          /facturacion
        </Route>
        <Route path='*'>
          404 - Page No Found
        </Route>
      </Switch>
    </div>
  )
}

export default App
