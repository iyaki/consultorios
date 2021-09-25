import renderer from 'react-test-renderer'
import Loading from './Loading'

test('Comportamiento por defecto', () => {
  const tree = renderer
    .create(<Loading />)
    .toJSON()
  expect(tree).toMatchSnapshot()
})
