import { AppBar, Button, IconButton, Menu, MenuItem, Toolbar } from '@material-ui/core'
import CloseRoundedIcon from '@material-ui/icons/CloseRounded'
import HomeRoundedIcon from '@material-ui/icons/HomeRounded'
import { SnackbarProvider } from 'notistack'
import { Suspense, lazy, useState, useRef } from 'react'
import { BrowserRouter, Link, Route, Switch } from 'react-router-dom'
import Loading from './Loading'
import * as RUTAS from '../../utils/rutas'
import './index.css'
import { mapeableObject } from '../../utils/common'

export default function App () {
  const notistackRef = useRef()
  const onClickDismiss = key => () => {
    notistackRef.current.closeSnackbar(key)
  }

  return (
    <BrowserRouter>
      <SnackbarProvider
        maxSnack={5}
        ref={notistackRef}
        action={(key) => (
          <IconButton onClick={onClickDismiss(key)}>
            <CloseRoundedIcon />
          </IconButton>
        )}
      >
        <NavBar />
        <Suspense fallback={<Loading />}>
          <Routes />
        </Suspense>
      </SnackbarProvider>
    </BrowserRouter>
  )
}

function NavBar () {
  const [anchorEl, setAnchorEl] = useState(null)

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget)
  }

  const handleClose = () => {
    setAnchorEl(null)
  }

  return (
    <AppBar position='sticky' id='AppBar'>
      <Toolbar variant='dense'>
        <IconButton className='Toolbar-text-color' component={Link} to={RUTAS.HOME}>
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
          <MenuItem onClick={handleClose} component={Link} to={RUTAS.CONFIGURAR_AGENDAS}>Configurar agendas</MenuItem>
          <MenuItem onClick={handleClose}>Programar citas</MenuItem>
          <MenuItem onClick={handleClose} component={Link} to={RUTAS.CONFIGURAR_ESPECIALIDADES}>Configurar especialidades</MenuItem>
          <MenuItem onClick={handleClose} component={Link} to={RUTAS.CONFIGURAR_PROFESIONALES}>Configurar profesionales</MenuItem>
        </Menu>
        <Button className='Toolbar-text-color' component={Link} to={RUTAS.FACTURACION}>Facturación</Button>
      </Toolbar>
    </AppBar>
  )
}

function Routes () {
  const routes = mapeableObject({
    [RUTAS.HOME]: () => '/',
    [RUTAS.CONFIGURAR_AGENDAS]: lazy(() => import('../Agendas/Configuracion')),
    [RUTAS.CONFIGURAR_ESPECIALIDADES]: lazy(() => import('../Agendas/Administracion/Especialidades')),
    [RUTAS.CONFIGURAR_PROFESIONALES]: lazy(() => import('../Agendas/Administracion/Profesionales')),
    [RUTAS.FACTURACION]: () => '/facturacion',
    '*': lazy(() => import('./NotFound'))
  })

  return (
    <Switch>
      {
        routes.map((component, path) => (
          <Route
            key={path}
            exact path={path}
            component={component}
          />
        ))
      }
    </Switch>
  )
}
