import { AppBar, Button, IconButton, Toolbar } from '@material-ui/core'
import HomeRoundedIcon from '@material-ui/icons/HomeRounded'
import { Link, Route, Switch } from 'react-router-dom'
import './styles.css'

function App () {
  return (
    <div>
      <AppBar position='static'>
        <Toolbar>
          <IconButton className='Toolbar-text-color' component={Link} to='/'>
            <HomeRoundedIcon />
          </IconButton>
          <Button className='Toolbar-text-color' component={Link} to='/agendas'>Agendas</Button>
          <Button className='Toolbar-text-color' component={Link} to='/facturacion'>Facturación</Button>
        </Toolbar>
      </AppBar>
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
