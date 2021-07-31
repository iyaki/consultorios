export function getErrorMessage (response) {
  window.response = response
  if (response.response?.data?.status) {
    if (response.response.data.status !== 'error') {
      throw new Error('El mensaje no es un error')
    }

    return response.response.data.message
  }

  return response.toString()
}
