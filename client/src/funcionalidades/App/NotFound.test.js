import renderer from 'react-test-renderer'
import NotFound from './NotFound'

test('Comportamiento por defecto', () => {
  const tree = renderer
    .create(<NotFound />)
    .toJSON()
  expect(tree).toMatchSnapshot()
})
