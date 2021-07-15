import { render, screen } from '@testing-library/react'
import Principal from './Principal'

test('renders main layout', () => {
  render(<Principal />)
  const agendasButton = screen.getByText(/Agendas/)
  const facturacionButton = screen.getByText(/Facturación/)
  expect(agendasButton).toBeInTheDocument()
  expect(facturacionButton).toBeInTheDocument()
})
