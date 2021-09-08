import { Card, CardContent, CircularProgress, Grid, IconButton, TextField } from '@material-ui/core'
import DeleteIcon from '@material-ui/icons/Delete'
import SaveIcon from '@material-ui/icons/Save'
import SearchIcon from '@material-ui/icons/Search'
import ClearIcon from '@material-ui/icons/Clear'
import { useEffect, useRef, useState } from 'react'
import LeftFiltersGrid from '../../App/LeftFiltersGrid'
import Loading from '../../App/Loading'
import axios from 'axios'
import { useSnackbar } from 'notistack'
import { getErrorMessage } from '../../../utils/http/webApp'

export default function Especialidades () {
  const [especialidades, setEspecialidades] = useState([])
  const [loadingEspecialidades, setLoadingEspecialidades] = useState(true)
  const { enqueueSnackbar } = useSnackbar()
  const [especialidadFilter, setEspecialidadFilter] = useState('')

  const filteredEspecialidades = especialidades.filter((e) => e.nombre.toLowerCase().indexOf(especialidadFilter.toLowerCase()) >= 0)

  function fetchEspecialidades () {
    setLoadingEspecialidades(true)
    axios
      .get('http://localhost:8080/agendas/webapp/especialidades')
      .then(r => {
        if (r.status === 200) {
          setEspecialidades(r.data.data)
        }
      })
      .catch(r => {
        const message = getErrorMessage(r)
        enqueueSnackbar(message, { variant: 'error' })
      })
      .finally(() => {
        setLoadingEspecialidades(false)
      })
  }

  function createEspecialidad (nombre) {
    return new Promise((resolve, reject) => {
      axios
        .post(
          'http://localhost:8080/agendas/webapp/especialidades',
          {
            data: {
              nombre
            }
          }
        )
        .then(r => {
          fetchEspecialidades()
          resolve(r)
        })
        .catch(r => {
          const message = getErrorMessage(r)
          enqueueSnackbar(message, { variant: 'error' })
          reject(r)
        })
    })
  }

  function editEspecialidad (nombre, id) {
    return new Promise((resolve, reject) => {
      axios
        .patch(
          `http://localhost:8080/agendas/webapp/especialidades/${id}`,
          {
            data: {
              nombre
            }
          }
        )
        .then(r => {
          fetchEspecialidades()
          resolve(r)
        })
        .catch(r => {
          const message = getErrorMessage(r)
          enqueueSnackbar(message, { variant: 'error' })
          reject(r)
        })
    })
  }

  function deleteEspecialidad (id) {
    return new Promise((resolve, reject) => {
      axios
        .delete(`http://localhost:8080/agendas/webapp/especialidades/${id}`)
        .then(r => {
          fetchEspecialidades()
          resolve(r)
        })
        .catch(r => {
          const message = getErrorMessage(r)
          enqueueSnackbar(message, { variant: 'error' })
          reject(r)
        })
    })
  }

  // Este hook llama solo una vez a fetchEspecialidades debido al array vacio de dependencias que recibe como segundo argumento
  useEffect(fetchEspecialidades, [])

  return (
    <LeftFiltersGrid
      renderFiltros={<Filters filterCallback={setEspecialidadFilter} filterValue={especialidadFilter} />}
      renderData={
        <>
          <EspecialidadCard saveCallback={createEspecialidad} hideDelete />
          <EspecialiadesList
            loading={loadingEspecialidades}
            especialidades={filteredEspecialidades}
            editCallback={editEspecialidad}
            deleteCallback={deleteEspecialidad}
          />
        </>
      }
    />
  )
}

function EspecialiadesList ({ loading, especialidades, editCallback, deleteCallback }) {
  if (loading) {
    return (
      <Loading />
    )
  }

  return (
    <>
      {
        especialidades.map(e => (
          <EspecialidadCard
            key={e.id}
            especialidadNombre={e.nombre}
            especialidadId={e.id}
            saveCallback={editCallback}
            deleteCallback={deleteCallback}
          />
        ))
      }
    </>
  )
}

function EspecialidadCard ({
  saveCallback,
  especialidadId,
  especialidadNombre = '',
  hideDelete = false,
  deleteCallback
}) {
  const especialidadRef = useRef()
  const [saving, setSaving] = useState(false)
  const [deleting, setDeleting] = useState(false)

  function save () {
    if (saving) {
      return
    }

    setSaving(true)

    saveCallback(especialidadRef.current.value, especialidadId)
      .finally(() => {
        setSaving(false)
        if (!especialidadId) {
          especialidadRef.current.value = ''
        }
      })
  }

  function remove () {
    if (deleting) {
      return
    }

    setDeleting(true)

    deleteCallback(especialidadId)
      .finally(() => {
        setDeleting(false)
      })
  }

  return (
    <Card style={{ marginTop: '1%' }}>
      <CardContent>
        <Grid container spacing={1}>
          <Grid item xs={9}>
            <TextField label='Nombre' defaultValue={especialidadNombre} style={{ width: '100%' }} inputRef={especialidadRef} />
          </Grid>
          <Grid item xs={3} style={{ textAlign: 'center' }}>
            <IconButton onClick={save}>
              {
                saving
                  ? (<CircularProgress />)
                  : (<SaveIcon />)
              }
            </IconButton>
            {
              hideDelete || (
                deleting
                  ? (<CircularProgress />)
                  : (
                    <IconButton onClick={remove}>
                      <DeleteIcon />
                    </IconButton>
                    )
              )
            }
          </Grid>
        </Grid>
      </CardContent>
    </Card>
  )
}

function Filters ({ filterValue, filterCallback }) {
  const inputRef = useRef()

  function clear () {
    inputRef.current.value = ''
    filterCallback('')
  }

  return (
    <Card style={{ height: '96%', margin: '2%' }}>
      <CardContent>
        <TextField label='Nombre' style={{ width: '65%' }} inputRef={inputRef} />
        <IconButton defaultValue={filterValue} onClick={() => { filterCallback(inputRef.current.value) }}>
          <SearchIcon />
        </IconButton>
        <IconButton onClick={clear}>
          <ClearIcon />
        </IconButton>
      </CardContent>
    </Card>
  )
}
