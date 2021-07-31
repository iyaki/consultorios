import { AppBar, Button, IconButton, Menu, MenuItem, Toolbar } from '@material-ui/core'
import CloseRoundedIcon from '@material-ui/icons/CloseRounded'
import HomeRoundedIcon from '@material-ui/icons/HomeRounded'
import { SnackbarProvider } from 'notistack'
import { Suspense, lazy, useState, useRef } from 'react'
import { BrowserRouter, Link, Route, Switch } from 'react-router-dom'
import Loading from './Loading'
import NotFound from './NotFound'
import './Principal.css'

const Agendas = lazy(() => import('../Agendas/Principal'))

export default function App () {
  const [anchorEl, setAnchorEl] = useState(null)

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget)
  }

  const handleClose = () => {
    setAnchorEl(null)
  }

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
        <Suspense fallback={<Loading />}>
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
      </SnackbarProvider>
    </BrowserRouter>
  )
}
