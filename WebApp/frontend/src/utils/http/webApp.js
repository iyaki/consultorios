export function getErrorMessage (response) {
  window.response = response
  if (response.response.status < 300) {
    throw new Error('El mensaje no es un error')
  }

  return response?.response?.data?.data?.message ?? response.toString()
}
