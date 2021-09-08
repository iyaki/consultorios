export function mapeableObject (object) {
  return {
    map (callable) {
      const r = []
      for (const key in object) {
        if (Object.hasOwnProperty.call(object, key)) {
          r.push(callable(object[key], key))
        }
      }
      return r
    }
  }
}
