import { render, screen } from '@testing-library/react'
import App from './index'

test('renders learn react link', () => {
  render(<App />)
  const agendasButton = screen.getByText(/AGENDAS/i)
  const facturacionButton = screen.getByText(/FACTURACIÓN/i)
  expect(agendasButton).toBeInTheDocument()
  expect(facturacionButton).toBeInTheDocument()
})
