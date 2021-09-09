import renderer from 'react-test-renderer'
import LeftFiltersGrid from './LeftFiltersGrid'

test('Comportamiento por defecto', () => {
  const tree = renderer
    .create(<LeftFiltersGrid />)
    .toJSON()
  expect(tree).toMatchSnapshot()
})

test('Con filtros', () => {
  const Filtro = () => (<div>Le filter</div>)
  const tree = renderer
    .create(<LeftFiltersGrid renderFiltros={<Filtro />} />)
    .toJSON()
  expect(tree).toMatchSnapshot()
})

test('Con data', () => {
  const Data = () => (<div>Le data</div>)
  const tree = renderer
    .create(<LeftFiltersGrid renderData={<Data />} />)
    .toJSON()
  expect(tree).toMatchSnapshot()
})

test('Con data', () => {
  const Hint = () => (<div>Le hint</div>)
  const tree = renderer
    .create(<LeftFiltersGrid hint={<Hint />} />)
    .toJSON()
  expect(tree).toMatchSnapshot()
})
