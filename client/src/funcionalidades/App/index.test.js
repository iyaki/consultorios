import { render, screen } from '@testing-library/react'
import App from '.'

test('renders main layout', () => {
  render(<App />)
  const agendasButton = screen.getByText(/Agendas/)
  const facturacionButton = screen.getByText(/Facturación/)
  expect(agendasButton).toBeInTheDocument()
  expect(facturacionButton).toBeInTheDocument()
})
