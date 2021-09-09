import { SnackbarProvider } from 'notistack'
import renderer from 'react-test-renderer'
import Especialidades from './Especialidades'

test('Comportamiento por defecto', () => {
  const tree = renderer
    .create(<SnackbarProvider><Especialidades /></SnackbarProvider>)
    .toJSON()
  expect(tree).toMatchSnapshot()
})
