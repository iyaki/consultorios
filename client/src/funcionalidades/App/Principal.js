import { AppBar, Button, CircularProgress, IconButton, Menu, MenuItem, Toolbar } from '@material-ui/core'
import HomeRoundedIcon from '@material-ui/icons/HomeRounded'
import { Suspense, lazy, useState } from 'react'
import { BrowserRouter, Link, Route, Switch } from 'react-router-dom'
import NotFound from './NotFound'
import './Principal.css'

const Agendas = lazy(() => import('../Agendas/Principal'))

const Loading = () => (
  <div style={{ width: '100vw', textAlign: 'center', position: 'absolute', top: '50%' }}>
    <CircularProgress />
  </div>
)

export default function App () {
  const [anchorEl, setAnchorEl] = useState(null)

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget)
  }

  const handleClose = () => {
    setAnchorEl(null)
  }

  return (
    <BrowserRouter>
      <AppBar position='sticky' id='AppBar'>
        <Toolbar variant='dense'>
          <IconButton className='Toolbar-text-color' component={Link} to='/'>
            <HomeRoundedIcon />
          </IconButton>
          <Button className='Toolbar-text-color' onClick={handleClick}>Agendas</Button>
          <Menu
            anchorEl={anchorEl}
            keepMounted
            open={Boolean(anchorEl)}
            onClose={handleClose}
            getContentAnchorEl={null}
            anchorOrigin={{
              vertical: 'bottom'
            }}
          >
            <MenuItem onClick={handleClose} component={Link} to='/agendas/configuracion'>Configurar agendas</MenuItem>
            <MenuItem onClick={handleClose}>Programar citas</MenuItem>
            <MenuItem onClick={handleClose} component={Link} to='/agendas/especialidades'>Configurar especialidades</MenuItem>
            <MenuItem onClick={handleClose} component={Link} to='/agendas/profesionales'>Configurar profesionales</MenuItem>
          </Menu>
          <Button className='Toolbar-text-color' component={Link} to='/facturacion'>Facturación</Button>
        </Toolbar>
      </AppBar>
      <Suspense fallback={Loading()}>
        <Switch>
          <Route exact path='/'>
            /
          </Route>
          <Route path='/agendas'>
            <Agendas />
          </Route>
          <Route path='/facturacion'>
            /facturacion
          </Route>
          <Route path='*' component={NotFound} />
        </Switch>
      </Suspense>
    </BrowserRouter>
  )
}
